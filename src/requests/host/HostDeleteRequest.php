<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\host;

use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Host delete request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class HostDeleteRequest extends HostAbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $operation = 'delete';
}
