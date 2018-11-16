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

use hiapi\nicru\requests\HostInfoRequest;
use hiapi\nicru\requests\HostCreateRequest;
use hiapi\nicru\requests\HostUpdateRequest;
use hiapi\nicru\requests\HostDeleteRequest;
use hiapi\nicru\requests\HostsSearchRequest;

/**
 * Domain operations.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class HostModule extends AbstractModule
{
    protected $domainStatuses = [
        0 => 'ok',
    ];

    protected $ruZones = ['ru', 'su', 'рф', 'xn--p1ai'];

    protected function hostInfo(array $row): array
    {
        $request = new HostInfoRequest($this->tool->data, $row);
        $res = $this->post($request);
        $res = reset($res);
        return is_array($res) ? array_merge($res, $row, ['exists' => 1]) : array_merge($row, ['exists' => 0]);
    }

    protected function hostSet($row)
    {
        if (empty($row['domain'])) {
            $hostInfo = $this->base->hostGetInfo($row);
            $row['domain'] = $hostInfo['domain'];
        }

        if (empty($row['domain'])) {
            throw new \Exception('host domain not found');
        }
        $parts = explode(".", $row['domain']);
        if (count($parts) > 2) {
            return $this->hostSetViaDomain($row);
        }
        $zone = array_pop($parts);
        if (in_array($zone, $this->ruZones, true)) {
            return $this->hostSetViaDomain($row);
        }
        $row['ips'] = implode(",", $row['ips']);

        return $this->hostSetNative($row);
    }

    protected function hostDelete($row)
    {
    }

    protected function hostSetNative($row)
    {
        $info = $this->hostInfo($row);
        if ($info['exists']) {
            $request = new HostUpdateRequest($this->tool->data, $row);
        } else {
            $request = new HostCreateRequest($this->tool->data, $row);
        }

        return reset($this->post($request));
    }

    protected function hostSetViaDomain($row)
    {
        $domain = new DomainModule($this->tool);
        $info = $domain->domainInfo($row);
        $oldNSs = $info['nss'];
        $row['nss'] = [
            'ns1.topdns.me' => 'ns1.topdns.me',
            $row['host'] => "{$row['host']} " . ($row['ip'] ? : implode(",", $row['ips'])),
        ];
        $res = $domain->domainSetNSs($row);
        return $domain->domainSetNSs(array_merge($row, ['nss' => $oldNSs]));
    }

}
