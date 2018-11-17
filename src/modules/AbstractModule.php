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
use hiapi\nicru\requests\OrderInfoRequest;
use hiapi\nicru\requests\InvalidCallException;
use hiapi\nicru\exceptions\InvalidObjectException;

/**
 * General module functions.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class AbstractModule
{
    /* @var object [[NicRuTool]] */
    public $tool;
    /* @var object [[mrdpBase]] */
    public $base;

    /**
     * Create a class instance
     *
     * @param object [[NicRuTool]] $tool
     */
    public function __construct(NicRuTool $tool)
    {
        $this->tool = $tool;
        $this->base = $tool->getBase();
    }

    /**
     * Preprocessing module function
     *
     * @param string $method
     * @param array $args
     * @throws \hiapi\nicru\requests\InvalidCallException|\hiapi\nicru\exceptions\InvalidObjectException
     */
    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            throw new InvalidCallException("{$method} is not available");
        }

        $data = array_shift($args);
        if (empty($data['contract'])) {
            $contract = new ContractModule($this->tool);
            $contractInfo = $contract->contractInfo($data);
            if (empty($contractInfo)) {
                throw new InvalidObjectException('contract not found');
            }
            $data = array_merge($contractInfo, $data);
        }
        return call_user_func([$this, $method], $data);

    }

    /**
     * Performs http GET request
     *
     * @param array $data
     * @return array
     */
    public function get(array $data) : array
    {
        return $this->tool->request('GET', ['SimpleRequest' => sprintf("%s", $data)]);
    }

    /**
     * Performs http POST request
     *
     * @param array $data
     * @return array
     */
    public function post(AbstractRequest $request) : array
    {
        return $this->tool->request('POST', $request);
    }
}
