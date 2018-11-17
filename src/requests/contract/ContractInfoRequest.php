<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\contract;

use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Contract info request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContractInfoRequest extends ContractAbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $operation = 'search';
    protected $bodyStatic = [
        'contracts-limit' => 1,
        'contracts-first' => 1,
    ];
    protected $bodyVariables = [
        'domain' => 'domain',
    ];
    protected $answer = [
        'delimiter' => 'contract',
        'fields' => [
            'contract-num' => 'contract',
            'phone' => 'phone',
            'email' => 'email',
            'person' => 'name',
            'passport' => 'passport',
            'org' => 'organization',
            'code' => 'inn',
        ],
    ];
    protected $search = 'contracts-list';
}
