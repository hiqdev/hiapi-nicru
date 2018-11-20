<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\modules;

use hiapi\nicru\NicRuTool;
use hiapi\nicru\requests\AbstractRequest;


/**
 * General module functions.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
interface ObjectModuleInterface
{
    /**
     * Create a class instance
     *
     * @param object [[NicRuTool]] $tool
     */
    public function __construct(NicRuTool $tool);

    /**
     * Preprocessing module function
     *
     * @param string $method
     * @param array $args
     * @throws \hiapi\nicru\requests\InvalidCallException|\hiapi\nicru\exceptions\InvalidObjectException
     */
    public function __call(string $method, array $args);

    /**
     * Performs http GET request
     *
     * @param array $data
     * @return array
     */
    public function get(array $data) : array;

    /**
     * Performs http POST request
     *
     * @param array $data
     * @return array
     */
    public function post(AbstractRequest $request) : array;
}
