<?php

/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\host;

/**
 * Host update request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class HostUpdateRequest extends HostAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'update';
    protected $bodyVariables = [
        'hostname' => 'host',
        'ip' => 'ips',
    ];
}
