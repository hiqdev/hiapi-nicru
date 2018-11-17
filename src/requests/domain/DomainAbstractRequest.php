<?php

/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\domain;

use hiapi\nicru\requests\AbstractRequest;
/**
 * Domain main request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainAbstractRequest extends AbstractRequest
{
    /* {@inheritdoc} */
    protected $request = 'service-object';
    protected $header = 'service-object';
    protected $bodyStatic = [
        'service' => 'domain',
    ];
    protected $bodyVariables = [
        'domain,strtoupper' => 'domain',
    ];
    protected $answer = [
        'delimiter' => 'service-object',
        'fields' => [
            'subject-contract' => 'contract',
            'service_id' => 'remoteid',
            'domain' => 'domain',
            'nameservers' => 'nss',
            'payed-till' => 'expires',
            'service-state' => 'status_id',
            'order_id' => 'order_id',
            'client-transfer-prohibited' => 'status.transfer',
            'object-state' => 'status.active',
            'autoprolong' => 'status.autoprolong',
        ],
    ];
}
