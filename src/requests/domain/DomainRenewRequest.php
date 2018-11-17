<?php

/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\domain;

/**
 * Domain renew request composet.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainRenewRequest extends DomainAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'create';
    protected $request = 'order';
    protected $header = 'order-item';
    protected $bodyStatic = [
        'service' => 'domain',
        'action' => 'prolong',
        'autoprolong' => 0,
    ];
    protected $bodyVariables = [
        'domain' => 'domain',
        'prolong' => 'amount',
    ];
    protected $answer = [
        'delimiter' => 'order',
        'fields' => [
            'order_id' => 'order_id',
        ],
    ];
}
