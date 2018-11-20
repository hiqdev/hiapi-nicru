<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\service;

use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Service search request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ServicesSearchRequest extends AbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $operation = 'search';
    protected $request = 'service-object';
    protected $header = 'service-object';
    protected $bodyStatic = [
        'service-objects-first' => 1,
        'service-objects-limit' => 64000,
        'object-only' => 1
    ];
    protected $bodyVariables = [
        'service' => 'service',
        'domain' => 'domain',
    ];
    protected $answer = [
        'delimiter' => 'service-object',
        'fields' => [
            'service' => 'service',
            'domain' => 'domain',
            'service-state' => 'state',
            'subject-contract' => 'contract',
            'autoprolong' => 'autorenew',
            'nameservers' => 'nss',
            'phone' => 'voice_phone',
            'fax-no' => 'fax_phone',
        ],
    ];
    protected $search = 'service-object';
}
