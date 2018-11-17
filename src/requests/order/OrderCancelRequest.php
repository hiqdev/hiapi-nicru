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

/**
 * Order cancel request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class OrderCancelRequest extends OrderAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'delete';
}
