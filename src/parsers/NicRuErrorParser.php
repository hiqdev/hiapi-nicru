<?php
/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */


namespace hiapi\nicru\parsers;

use hiapi\nicru\exceptions\AuthorizationException;
use hiapi\nicru\exceptions\InvalidBodyRequestException;
use hiapi\nicru\exceptions\InvalidHeaderRequestException;
use hiapi\nicru\exceptions\InvalidObjectException;
use hiapi\nicru\exceptions\InvalidRequestException;
use hiapi\nicru\exceptions\NicRuDBErrorException;
use hiapi\nicru\exceptions\NicRuInternalErrorException;
use hiapi\nicru\exceptions\NicRuServerErrorException;
use hiapi\nicru\exceptions\TooManyRequestException;
use hiapi\nicru\requests\AbstractRequest;
use Exception;

/**
 * Parse response and throws Excepions.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class NicRuErrorParser
{
    protected static $errors = [
        400 => InvalidHeaderRequestException::class,
        401 => AuthorizationException::class,
        402 => InvalidBodyRequestException::class,
        403 => InvalidRequestException::class,
        404 => InvalidObjectException::class,
        405 => TooManyRequestException::class,
        500 => NicRuInternalErrorException::class,
        510 => NicRuDBErrorException::class,
        502 => NicRuServerErrorException::class,
    ];

    protected static $delimiter = 'errors';

    /**
     * Parse response during error
     *
     * @var string $response
     * @var object [[AbstractRequest]] $request
     * @return void
     * @throws \hiapi\nicru\exceptions\NicRuException
     */

    public static function parse(string $response, AbstractRequest $request)
    {
        preg_match_all('#^State: ([0-9]+) (.+)$#mui', $response, $errors);
        $state = (int) $errors[1][0];
        $message = trim($errors[2][0]);
        if (strpos($response, "[" . self::$delimiter . "]") === false) {
            self::throwException($state, $message);
        }

        $info = trim(mb_substr($response, mb_strpos($response, "[" . self::$delimiter . "]") + mb_strlen(self::$delimiter) + 2));
        if (!preg_match('#\[(.+)\]#mui', $info)) {
            $message .= ": {$info}";
            self::throwException($state, $message);
        }

        $info = trim(mb_substr($info, 0, mb_strpos($info, "[")));
        $message .= ": {$info}";
        self::throwException($state, $message);

    }

    /**
     * Generate exception
     *
     * @var int $state
     * @var string $message
     * @return void
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    protected static function throwException(int $state, string $message)
    {
        $class = empty(self::$errors[$state]) ? Exception::class : self::$errors[$state];
        throw new $class($message);
    }
}
