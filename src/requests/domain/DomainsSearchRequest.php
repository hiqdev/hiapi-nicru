<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\domain;

use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Domain search request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainsSearchRequest extends DomainInfoRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $bodyStatic = [
        'service' => 'domain',
        'service-objects-limit' => 64000,
        'service-objects-first' => 1,
    ];
}
