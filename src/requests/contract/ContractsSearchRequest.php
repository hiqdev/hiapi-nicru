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

class ContractsSearchRequest extends ContractAbstractRequest
{
    protected $bodyStatic = [
        'contracts-limit' => 64000,
        'contracts-first' => 1,
    ];
}
