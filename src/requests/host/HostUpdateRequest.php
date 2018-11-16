<?php

/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\host;

class HostUpdateRequest extends HostAbstractRequest
{
    protected $operation = 'update';
    protected $bodyVariables = [
        'hostname' => 'host',
        'ip' => 'ips',
    ];
}
