<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\modules;

use hiapi\nicru\requests\domain\DomainInfoRequest;
use hiapi\nicru\requests\domain\DomainRenewRequest;
use hiapi\nicru\requests\domain\DomainUpdateRequest;
use hiapi\nicru\requests\domain\DomainsSearchRequest;
use hiapi\nicru\requests\domain\DomainWPRequest;
use hiapi\nicru\requests\service\ServicesSearchRequest;

/**
 * Domain operations.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainModule extends AbstractModule implements ObjectModuleInterface
{
    const ERROR_WP_IS_NOT_AVAILABLE = 'Errors in order item templates: For this TLD service is not available.';
    /**
     * @param array $row
     * @return array
     */
    protected $domainStatuses = [
        0 => 'ok',
    ];

    /// XXX REWRITE
    /**
     * Get info about domains
     *
     * @param array $rows
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    public function domainsGetInfo(array $rows) :array
    {
        foreach ($rows as $id => $row) {
            $tmp = new DomainModule($this->tool);
            $res[$id] = $tmp->domainInfo($row);
        }

        return $res;
    }

    /**
     * Load info about all domain
     *
     * @param array|void $rows
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    public function domainsLoadNicRu($rows = []) : array
    {

        $request = new ServicesSearchRequest($this->tool->data, [
            'service' => 'domain',
        ]);
        $result = $this->post($request);
        foreach ($result as $info) {
            $info = $this->_domainPostParseRequest($info);
            $domains[$info['domain']] = $info;
        }

        return $domains;

        unset($rows['access_od'], $rows['dummy']);
        $contract = new ContractModule($this->tool);
        $contracts = $contract->contractsSearch([]);
        if (empty($contracts)) {
            return [];
        }

        foreach ($contracts as $contract) {
            $request = new DomainsSearchRequest($this->tool->data, $contract);
            $res = $this->post($request);
            if (empty($res) || (count($res) == 1 && !empty($res['status']))) {
                continue;
            }

            foreach ($res as $id => $domain) {
                $domain = $this->_domainPostParseRequest($domain);
                $domains[$domain['domain']] = $domain;
            }
        }

        return $domains;
    }

    /**
     * Load info about domains
     *
     * @param array $rows
     * @return array
     */
    public function domainsLoadInfo(array $rows) : array
    {
        return $rows;
    }

    /**
     * Empty function
     */
    public function domainsSaveContacts(array $rows) : array
    {
        return $rows;
    }

    /**
     * Get info about domain
     *
     * @param array $row
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    protected function domainInfo(array $row): array
    {
        $request = new DomainInfoRequest($this->tool->data, $row);
        $res = $this->post($request);
        return array_merge($this->_domainPostParseRequest($res), $row);
    }

    /**
     * Set info about domain
     *
     * @param array $row
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    protected function domainUpdate(array $row) : array
    {
        $_row = $row;
        if ($_row['nss']) {
            foreach ($_row['nss'] as $key => &$value) {
                if (strpos($value, $_row['domain']) !== false) {
                    $host = $this->base->hostGetInfo(['host' => $value]);
                    $value = "{$value} " . ($host['ip'] ? : implode(",", $host['ips']));
                }
            }
        }

        $request = new DomainUpdateRequest($this->tool->data, $_row);
        $res = $this->post($request);
        $order = new OrderModule($this->tool);
        $res = $order->orderInfo(['order_id' => $res['order_id']]);
        return $row;
    }

    /**
     * Set NSs to domain
     *
     * @param array $row
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    protected function domainSetNSs(array $row) : array
    {
        return $this->domainUpdate($row);
    }

    /**
     * Renew domain
     *
     * @param array $row
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    protected function domainRenew(array $row) : array
    {
        $request = new DomainRenewRequest($this->tool->data, $row);
        $res = $this->post($request);
        $order = new OrderModule($this->tool);
        $res = $order->orderInfo(['order_id' => $res['order_id']]);
        return $row;
    }

    /**
     * Enable/Disable WHOISPROXY
     *
     * @param array $row
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    protected function domainSetContacts($row)
    {
        return $this->domainSetWhoisProtect($row);
    }

    protected function domainSaveContacts(array $row): array
    {
        return $this->base->_simple_domainSaveContacts($row, false);
    }

    protected function domainEnableWhoisProtect(array $row): array
    {
        return $this->domainSetWhoisProtect($row, true);
    }

    protected function domainDisableWhoisProtect(array $row): array
    {
        return $this->domainSetWhoisProtect($row, false);
    }

    protected function domainSetWhoisProtect(array $row, bool $enable = null): array
    {
        $enable = $enable === null ? ($row['whois_protected'] ? true : false) : $enable;
        $enable = $enable === true ? 'ON' : 'OFF';
        $info = $this->domainInfo($row);

        foreach (['switch', 'admin-on', 'tech-on', 'bill-on'] as $key) {
            $row[$key] = $enable;
        }

        $row['action'] = 'update';

        if (empty($info['wp_purchased'])) {
            $row = array_merge($row, [
                'amount' => 1,
                'action' => 'new',
                'switch' => 'ON',
            ]);
        }

        $request = new DomainWPRequest($this->tool->data, $row);
        try {
            $res = $this->post($request);
        } catch (\Exception $e) {
           if ($e->getMessage() === self::ERROR_WP_IS_NOT_AVAILABLE) {
               return $row;
           }

           throw new \Exception($e->getMessage());
        }
        $order = new OrderModule($this->tool);
        $res = $order->orderInfo(['order_id' => $res['order_id']]);
        return $row;
    }

    /**
     * Postprocess domain info
     *
     * @param array $domain
     * @return array
     */
    protected function _domainPostParseRequest(array $domain) : array
    {
        if (empty($domain)) {
            return ['_error' => self::ERROR_OBJECT_DOES_NOT_EXIST];
        }

        $expires = $domain['expires'];
        unset($domain['expires']);

        return array_merge($domain, [
            'domain' => strtolower($domain['domain']),
            'statuses' => implode(",", array_filter([
                'inactive' => $domain['status.state'] !== 'DELEGATED' && $domain['status.state'] !== 'LOCK' ? 'inactive' : null,
                'clientTransferProhibited' => ($domain['status.transfer'] === 'ON' || $domain['status.state'] === 'LOCK') ? 'clientTransferProhibited' : null,
                'autoprolong' => $domain['status.autoprolong'] == 1 ? 'autoprolong' : null,
            ])),
            'nameservers' => $domain['nss'] ? implode(',', $domain['nss']) : '',
            'expiration_date' => date("Y-m-d H:i:s", strtotime($expires)),
            'wp_enabled' => $domain['wp_enabled'] === 'ON',
            'wp_purchased' => in_array($domain['wp_enabled'], ['ON', 'OFF'], true),
        ]);
    }
}
