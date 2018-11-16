<?php

/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests;

class HostCreateRequest extends HostAbstractRequest
{
    protected $operation = 'create';
    protected $bodyVariables = [
        'hostname' => 'host',
        'ip' => 'ips',
    ];
}
