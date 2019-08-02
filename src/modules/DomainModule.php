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
use hiapi\nicru\requests\service\ServicesSearchRequest;

/**
 * Domain operations.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainModule extends AbstractModule implements ObjectModuleInterface
{
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
        /// XXX DO CHECK IF WP ENABLED
        $request = new DomainWPRequest($this->tool->data, $row);
        $res = $this->post($request);
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
        return array_merge($domain, [
            'domain' => strtolower($domain['domain']),
            'expires' => date("Y-m-d H:i:s", strtotime($domain['expires'])),
            'statuses' => implode(",", array_filter([
                'inactive' => $domain['status.active'] !== 'DELEGATED' ? 'inactive' : null,
                'clientTransferProhibited' => $domain['status.transfer'] === 'ON' ? 'clientTransferProhibited' : null,
                'autoprolong' => $domain['status.autoprolong'] == 1 ? 'autoprolong' : null,
            ])),
            'nameservers' => $domain['nss'] ? implode(',', $domain['nss']) : '',
            'expiration_date' => date("Y-m-d H:i:s", strtotime($domain['expires'])),
        ]);
    }
}
