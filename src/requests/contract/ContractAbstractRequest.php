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

use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Contract main request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
abstract class ContractAbstractRequest extends AbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $request = 'contract';
    protected $header = 'contract';
}
