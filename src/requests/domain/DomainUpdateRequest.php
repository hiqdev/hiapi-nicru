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

/**
 * Domain update request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainUpdateRequest extends DomainAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'create';
    protected $request = 'order';
    protected $header = 'order-item';
    protected $bodyStatic = [
        'service' => 'domain',
        'action' => 'update',
        'check-ns' => 'OFF',
    ];
    protected $bodyVariables = [
        'domain' => 'domain',
        'nserver' => 'nss,array',
    ];

    protected $answer = [
        'delimiter' => 'order',
        'fields' => [
            'order_id' => 'order_id',
        ],
    ];

}
