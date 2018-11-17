<?php

namespace hiapi\nicru\exceptions;

/**
 * Thrown when Authorization error: the password is missing or doesn't meet the requirements.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
final class AuthorizationException extends NicRuException implements ExceptionInterface
{
}
