<?php

namespace hiapi\nicru\exceptions;

/**
 * Thrown when argument missing.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class RequiredParamMissingException extends \InvalidArgumentException implements ExceptionInterface
{
}
