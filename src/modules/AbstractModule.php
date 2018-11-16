<?php
/**
 * hiAPI Directi plugin
 *
 * @link      https://github.com/hiqdev/hiapi-directi
 * @package   hiapi-directi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\modules;

use hiapi\nicru\NicRuTool;
use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\requests\OrderInfoRequest;

/**
 * General module functions.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class AbstractModule
{
    public $tool;
    public $base;

    public function __construct(NicRuTool $tool)
    {
        $this->tool = $tool;
        $this->base = $tool->getBase();
    }

    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            throw new \Exception("{$method} is not available");
        }

        $data = array_shift($args);
        if (empty($data['contract'])) {
            $contract = new ContractModule($this->tool);
            $contractInfo = $contract->contractInfo($data);
            if (empty($contractInfo)) {
                throw new Exception('contract not found');
            }
            $data = array_merge($contract->contractInfo($data), $data);
        }
        return call_user_func([$this, $method], $data);

    }

    /**
     * Performs http GET request
     *
     * @param array $data
     * @return array
     */
    public function get(array $data)
    {
        return $this->tool->request('GET', ['SimpleRequest' => sprintf("%s", $data)]);
    }

    /**
     * Performs http POST request
     *
     * @param array $data
     * @return array
     */
    public function post(AbstractRequest $request) {
        return $this->tool->request('POST', $request);
    }

    public function orderGetInfo($row)
    {
        $request = new OrderInfoRequest($this->tool->data, $row);
        $res = reset($this->post($request));
        return $res;
    }
}
