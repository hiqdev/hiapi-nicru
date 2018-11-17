<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\account;

use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * General request functions.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class AccountInfoRequest extends AbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $operation = 'get';
    protected $request = 'account';
    protected $header = 'account';
    protected $answer = [
        'delimiter' => 'account',
        'fields' => [
            'present_payments' => 'present_payments',
            'payments' => 'payments',
            'services' => 'services',
            'credit' => 'credit',
            'blocked_services' => 'blocked_prolong',
            'blocked_prolong' => 'blocked_prolong',
            'usd_rate' => 'usd_rate',
            'present_services' => 'present_services',
            'blockable' => 'blockable',
            'balance' => 'balance',
            'nds' => 'nds',
            'blocked_new' => 'blocked_new',
            'rate_date' => 'rate_date',
        ],
    ];
}
