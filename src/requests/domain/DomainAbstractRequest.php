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
use hiapi\nicru\requests\NicRuRequestInterface;
/**
 * Domain main request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
abstract class DomainAbstractRequest extends AbstractRequest implements NicRuRequestInterface
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
            'object-state' => 'status.state',
            'autoprolong' => 'status.autoprolong',
            'wp-switch' => 'wp_enabled',
            'tech-c' => 'tech_remoteid',
            'admin-c' => 'admin_remoteid',
            'bill-c' => 'billing_remoteid',
        ],
    ];
}
