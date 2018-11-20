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

use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Host main request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
abstract class HostAbstractRequest extends AbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $request = 'server';
    protected $header = 'server';
    protected $bodyVariables = [
        'hostname' => 'host',
    ];
    protected $answer = [
        'delimiter' => 'server',
        'fields' => [
            'hostname' => 'host',
            'ip' => 'ips',
        ],
    ];
}
