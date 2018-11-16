<?php
/**
 * hiAPI Directi plugin
 *
 * @link      https://github.com/hiqdev/hiapi-directi
 * @package   hiapi-directi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\modules;

use hiapi\nicru\requests\DomainInfoRequest;
use hiapi\nicru\requests\DomainRenewRequest;
use hiapi\nicru\requests\DomainUpdateRequest;
use hiapi\nicru\requests\DomainsSearchRequest;

/**
 * Domain operations.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class DomainModule extends AbstractModule
{
    /**
     * @param array $row
     * @return array
     */

    protected $domainStatuses = [
        0 => 'ok',
    ];

    /// XXX REWRITE
    public function domainsGetInfo($rows)
    {
        foreach ($rows as $id=>$row) {
            $tmp = new DomainModule($this->tool);
            $res[$id] = $tmp->domainInfo($row);
        }
        return $res;
    }

    public function domainsLoadNicRu($rows = [])
    {
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

    public function domainsLoadInfo($rows)
    {
        return true;
    }

    protected function domainInfo(array $row): array
    {
        $request = new DomainInfoRequest($this->tool->data, $row);
        $res = $this->post($request);
        $res = reset($res);
        return array_merge($this->_domainPostParseRequest($res), $row);
    }

    protected function domainUpdate($row)
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
        $res = reset($this->post($request));
        $order = new OrderModule($this->tool);
        $res = $order->orderInfo(['order_id' => $res['order_id']]);
        return $row;
    }

    protected function domainSetNSs($row)
    {
        return $this->domainUpdate($row);
    }

    protected function domainRenew($row)
    {
        $request = new DomainRenewRequest($this->tool->data, $row);
        $res = $this->post($request);
        $res = reset($res);
        $order = new OrderModule($this->tool);
        $res = $order->orderInfo(['order_id' => $res['order_id']]);
        return $row;
    }

    protected function _domainPostParseRequest($domain)
    {
        $domain['domain'] = strtolower($domain['domain']);
        $domain['expires'] = date("Y-m-d H:i:s", strtotime($domain['expires']));
        $domain['statuses'] = implode(",", array_filter([
            'inactive' => $domain['status.active'] !== 'DELEGATED' ? 'inactive' : null,
            'clientTransferProhibited' => $domain['status.transfer'] === 'ON' ? 'clientTransferProhibited' : null,
            'autoprolong' => $domain['status.autoprolong'] == 1 ? 'autoprolong' : null,
        ]));

        return $domain;
    }
}
