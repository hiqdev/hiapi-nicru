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
 * Thrown when Authorization error: the password is missing or doesn't meet the requirements.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
final class AuthorizationException extends NicRuException implements ExceptionInterface
{
}
