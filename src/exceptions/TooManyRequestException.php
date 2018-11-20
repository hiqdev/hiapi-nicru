<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\exceptions;

/**
 * Thrown when Surpassing the allowed request number.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
final class TooManyRequestException extends NicRuException implements ExceptionInterface
{
}
