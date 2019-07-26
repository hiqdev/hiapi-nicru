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

use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Domain update request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainWPRequest extends DomainAbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $operation = 'create';
    protected $request = 'order';
    protected $header = 'order-item';
    protected $bodyStatic = [
        'service' => 'whois_proxy',
        'action' => 'new',
        'template' => 'whois_proxy',
    ];
    protected $bodyVariables = [
        'domain' => 'domain',
        'switch' => 'switch',
        'multiplier' => 'amount',
    ];

    protected $answer = [
        'delimiter' => 'order',
        'fields' => [
            'order_id' => 'order_id',
        ],
    ];

}
