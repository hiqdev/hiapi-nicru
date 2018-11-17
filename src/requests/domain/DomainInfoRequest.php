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
 * Domain info request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class DomainInfoRequest extends DomainAbstractRequest implements NicRuRequestInterface
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
    protected $search = 'service-objects-list';
}
