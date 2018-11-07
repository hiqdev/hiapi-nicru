<?php

/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\lib;

class Host extends AbstractObject
{
    protected function info($row)
    {
        return $this
            ->setRequestHeader([
                'operation' => 'search',
                'request' => 'server',
            ])
            ->setRequestBodyHeader('server')
            ->setRequestBodyVariables([
                'hostname' => 'host',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'server',
                'fields' => [
                    'hostname' => 'host',
                    'ip' => 'ips',
                ],
            ]);
    }

    protected function set($row)
    {
        $this->info($row);
        $row['ips'] = implode(",", $row['ips']);
        return empty($this->parsedResponse) ? $this->create($row) : $this->update($row);
    }


    protected function create($row)
    {
        return $this
            ->setRequestHeader([
                'operation' => 'create',
                'request' => 'server',
            ])
            ->setRequestBodyHeader('server')
            ->setRequestBodyVariables([
                'hostname' => 'host',
                'ip' => 'ips',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'server',
                'fields' => [
                    'hostname' => 'host',
                ],
            ]);
    }

    protected function update($row)
    {
        return $this
            ->setRequestHeader([
                'operation' => 'update',
                'request' => 'server',
            ])
            ->setRequestBodyHeader('server')
            ->setRequestBodyVariables([
                'hostname' => 'host',
                'ip' => 'ips',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'server',
                'fields' => [
                    'hostname' => 'host',
                ],
            ]);
    }

    protected function delete($row)
    {
         return $this
            ->setRequestHeader([
                'operation' => 'delete',
                'request' => 'server',
            ])
            ->setRequestBodyHeader('server')
            ->setRequestBodyVariables([
                'hostname' => 'host',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'server',
                'fields' => [
                    'hostname' => 'host',
                ],
            ]);
    }
}
