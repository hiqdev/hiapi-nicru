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

class HostsSearchRequest extends HostAbstractRequest
{
    protected $operation = 'search';
    protected $bodyStatic = [
        'server-limit' => 64000,
        'server-first' => 1,
    ];
}
