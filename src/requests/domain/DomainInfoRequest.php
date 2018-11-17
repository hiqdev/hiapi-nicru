<?php

/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\domain;

/**
 * Domain info request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainInfoRequest extends DomainAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'search';
    protected $request = 'service-object';
    protected $header = 'service-object';
    protected $bodyStatic = [
        'service' => 'domain',
        'service-objects-limit' => 1,
        'service-objects-first' => 1,
    ];
    protected $search = [
        'delimiter' => 'service-objects-list',
        'fields' => [
            'service-objects-limit',
            'service-objects-found',
            'service-objects-first',
        ],
    ];
}
