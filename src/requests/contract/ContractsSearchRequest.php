<?php

/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\contract;

/**
 * Contract search request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContractsSearchRequest extends ContractInfoRequest
{
    /* {@inheritdoc} */
    protected $bodyStatic = [
        'contracts-limit' => 64000,
        'contracts-first' => 1,
    ];
    protected $bodyVariables = [];
}
