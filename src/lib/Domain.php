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

class Domain extends AbstractObject
{
    public function loadInfo()
    {
        $this->setLimit(64000);
        $this->setFirst(1);
        $contracts = $this->findContracts();

        foreach ($contracts as $contract) {
            $domains = new Domain($this->data);
            $domains
                ->setContract($contract->getContract())
                ->setLimit(64000)
                ->setFirst(1)
                ->search();
            foreach ($domains->getParsedResponse() as $data) {
                $res[$data['domain']] = $data;
            }
        }

        return $res;
    }

    protected function info($row = [])
    {
        $this->search($row);
        return reset($this->parsedResponse);
    }

    protected function renew($row)
    {
        return $this
            ->setRequestHeader([
                'operation' => 'create',
                'request' => 'create',
            ])
            ->setRequestBodyHeader('order-item')
            ->setRequestBodyStatic([
                'service' => 'domain',
                'action' => 'prolong',
                'autoprolong' => 0,
            ])
            ->setRequestBodyVariables([
                'domain' => 'domain',
                'prolong' => 'amount',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'order',
                'fields' => [
                    'order_id' => 'order_id',
                ],
            ]);

    }

    protected function update($row)
    {
        $this
            ->setRequestHeader([
                'operation' => 'create',
                'request' => 'order',
            ])
            ->setRequestBodyHeader('order-item')
            ->setRequestBodyStatic([
                'service' => 'domain',
                'action' => 'update',
                'check-ns' => 'OFF',
            ])
            ->setRequestBodyVariables([
                'domain' => 'domain',
                'nserver' => 'nss,arrray',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'order',
                'fields' => [
                    'order_id' => 'order_id',
                ],
            ]);
        return reset($this->parsedResponse);
    }

    protected function search($row = [])
    {
        return $this
            ->setRequestHeader([
                'operation' => 'search',
                'request' => 'service-object',
            ])
            ->setRequestBodyHeader('service-object')
            ->setRequestBodyStatic([
                'service' => 'domain',
                'service-objects-limit' => $this->limit,
                'service-objects-first' => $this->first,
            ])
            ->setRequestBodyVariables([
                'domain,strtoupper' => 'domain',
            ], $row)
            ->request()
            ->parseResponse([
                'delimiter' => 'service-object',
                'fields' => [
                    'subject-contract' => 'contract',
                    'service-id' => 'service_id',
                    'domain' => 'domain',
                    'nameservers' => 'nss',
                    'payed-till' => 'expires',
                    'service-state' => 'status_id',
                ],
            ]);
    }
}
