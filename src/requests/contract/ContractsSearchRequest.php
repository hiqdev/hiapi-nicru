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
 * Contract search request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContractsSearchRequest extends ContractInfoRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $bodyStatic = [
        'contracts-limit' => 64000,
        'contracts-first' => 1,
    ];
    protected $bodyVariables = [];
}
