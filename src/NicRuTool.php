<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru;

use hiapi\nicru\lib\Domain;
use hiapi\nicru\lib\Host;
use Exception;
use err;
use arr;

/**
 * NIC.ru tool.
 */
class NicRuTool extends \hiapi\components\AbstractTool
{

    protected $commands = [
        'domainInfo' => [
            'class' => Domain::class,
            'command' => 'info',
            'multiple' => false,
        ],
        'domainsInfo' => [
            'mono' => 'domainInfo',
            'multiple' => true,
        ],
        'domainSetNSs' => [
            'class' => Domain::class,
            'command' => 'update',
            'multiple' => false,
        ],
        'domainsSetNSs' => [
            'mono' => 'domainSetNSs',
            'multiple' => true,
        ],
        'domainRenew' => [
            'class' => Domain::class,
            'command' => 'renew',
            'multiple' => false,
        ],
        'domainsRenew' => [
            'mono' => 'domainRenew',
            'multiple' => true,
        ],
        'hostInfo' => [
            'class' => Host::class,
            'command' => 'info',
            'multiple' => false,
        ],
        'hostsInfo' => [
            'mono' => 'hostInfo',
            'multiple' => true,
        ],
        'hostCreate' => [
            'class' => Host::class,
            'command' => 'create',
            'multiple' => false,
        ],
        'hostsCreate' => [
            'mono' => 'hostCreate',
            'multiple' => true,
        ],
        'hostUpdate' => [
            'class' => Host::class,
            'command' => 'update',
            'multiple' => false,
        ],
        'hostsUpdate' => [
            'mono' => 'hostUpdate',
            'multiple' => true,
        ],
        'hostDelete' => [
            'class' => Host::class,
            'command' => 'delete',
            'multiple' => false,
        ],
        'hostsDelete' => [
            'mono' => 'hostDelete',
            'multiple' => true,
        ],
        'hostSet' => [
            'class' => Host::class,
            'command' => 'set',
            'multiple' => false,
        ],
        'hostsSet' => [
            'mono' => 'hostSet',
            'multiple' => true,
        ],
    ];

    public function __construct($base = null, $data = null)
    {
        parent::__construct($base, $data);
    }

    public function __call($method, $data)
    {
        if (!array_key_exists($method, $this->commands)) {
            return err::set($data[0], 'command not supported');
        }

        if ($this->commands[$method]['multiple']) {
            return $this->callMultiple($this->commands[$method]['mono'], $data[0]);
        }

        return $this->callSingle($method, $data[0]);
    }

    public function domainsLoadInfo($rows = [])
    {
        unset($rows['dummy'], $rows['access_id']);
        if (!empty($rows)) {
            return $this->callMultiple('domainInfo', $rows);
        }

        $object = new Domain($this->data);
        try {
            return $object->loadInfo();
        } catch (Exception $e) {
            return err::set($rows, $e->getMessage());
        }
    }

    public function domainsLoadNicRu($rows = [])
    {
        return $this->domainsLoadInfo();
    }

    public function hostSet($row)
    {
        try {
            $parts = explode(".", $row['host']);
            $zone = array_pop($parts);
            if (!in_array($zone, ['ru', 'su', 'рф', 'xn--p1ai'], true)) {
                $object = new Host($this->data);
                return $object->set($row);
            }
            $object = new Domain($this->data);
            $nss = $tmp_nss = $this->_prepareNSs([
                'domain' => $row['domain'],
                'nss' => arr::get($this->base->domainGetNSs($row), 'nss'),
            ]);
            $tmp_nss[$row['host']] = $row['host'] ." " . (reset($row['ips']) ?: $row['ip']);
            if (count($tmp_nss) < 2) {
                $tmp_nss['ns1.topdns.me'] = 'ns1.topdns.me';
            }

            $res = $object->update([
                'domain' => $row['domain'],
                'nss' => $tmp_nss,
            ]);

            return $object->update([
                'domain' => $row['domain'],
                'nss' => $nss,
            ]);
        } catch (Exception $e) {
            return err::set($row, $e->getMessage());
        }
    }

    public function hostsSet($rows)
    {
        foreach ($rows as $id => $row) {
            $res[$id] = $this->hostSet($row);
        }
    }

    protected function callSingle($method, $data)
    {
        $objectData = $this->commands[$method];
        $class = $objectData['class'];
        $object = new $class($this->data);
        try {
            $res = call_user_func([$object, $objectData['command']], $data);
        } catch (Exception $e) {
            return err::set($data, $e->getMessage());
        }

        return $res;
    }

    protected function callMultiple($method, $data)
    {
        foreach ($data as $id => $row) {
            $res[$id] = $this->callSingle($method, $row);
        }

        return err::reduce($res);
    }

    protected function _prepareNSs ($row)
    {
        $domain = $row['domain'];
        foreach (arr::csplit($row['nss']) as $host) {
            if (substr($host,-strlen($domain))==$domain) $my_nss[$host] = $host;
            else $nss[$host] = $host;
        };
        if ($my_nss) {
            $his = $this->base->hostsGetInfo(arr::make_sub($my_nss,'host'));
            if (err::is($his)) return $his;
            foreach ($his as $k=>$v) $nss[$v['host']] = "$v[host],$v[ip]";
        };
        return $nss;

    }
}
