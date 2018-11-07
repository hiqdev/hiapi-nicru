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

class Contract extends AbstractObject
{
    public function search($row = [])
    {
        return $this
            ->setRequestHeader([
                'operation' => 'search',
                'request' => 'contract',
            ])
            ->setRequestBodyHeader('contract')
            ->setRequestBodyStatic([
                'contracts-limit' => $this->limit,
                'contracts-first' => $this->first,
            ])
            ->setRequestBodyVariables([
                'domain' => 'domain',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'contract',
                'fields' => [
                    'contract-num' => 'contract',
                ],
            ]);
    }
}
