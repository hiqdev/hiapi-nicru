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
 * Contract info request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContractInfoRequest extends ContractAbstractRequest
{
    /* {@inheritdoc} */
    protected $bodyVariables = [
        'domain' => 'domain',
    ];
}
