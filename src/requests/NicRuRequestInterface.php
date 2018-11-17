<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests;

/**
 * Interface for request functions.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
interface NicRuRequestInterface
{
    public function __construct(array $data, $args);
    public function __toString();

    /**
     * @param void
     * @return array
     */
    public function getParserAnswerRules() : array;
    /**
     * @param void
     * @return string|void
     */
    public function getParserSearchDelimiter() : ?string;

    /**
     * Check if request is search
     *
     * @param void
     * @return bool
     */
    public function isSearchRequest() : bool;

    /**
     * Check if parse some additional block neaded
     *
     * @param void
     * @return bool
     */
    public function isSubInfoQueried() : bool;
}
