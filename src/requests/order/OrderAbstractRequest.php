<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\order;

use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Abstract main request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
abstract class OrderAbstractRequest extends AbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $request = 'order';
    protected $header = 'order';
    protected $bodyVariables = [
        'order_id' => 'order_id',
    ];
    protected $answer = [
        'delimiter' => 'order',
        'fields' => [
            'subject-contract' => 'contract',
            'order_id' => 'order_id',
            'state' => 'state',
        ],
        'subinfo' => [
            [
                'delimiter' => 'order-item',
                'fields' => [
                    'state' => 'state',
                    'subject-contract' => 'contract',
                    'admin-c' => 'admin_epp',
                    'tech-c' => 'tech_epp',
                    'billing-c' => 'billing_epp',
                    'domain' => 'domain',
                    'service-state' => 'service_state',
                ],
                'limit' => 1,
            ],
        ],
    ];
}
