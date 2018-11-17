<?php

namespace hiapi\nicru\exceptions;

/**
 * Thrown when Surpassing the allowed request number.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
final class TooManyRequestException extends NicRuException implements ExceptionInterface
{
}
