<?php

/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\order;

use hiapi\nicru\requests\AbstractRequest;

/**
 * Abstract main request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class OrderAbstractRequest extends AbstractRequest
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
    ];
}
