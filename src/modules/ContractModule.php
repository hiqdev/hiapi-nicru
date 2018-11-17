<?php
/**
 * hiAPI NicRu plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\modules;

use hiapi\nicru\requests\contract\ContractInfoRequest;
use hiapi\nicru\requests\contract\ContractsSearchRequest;

/**
 * Contract operations.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContractModule extends AbstractModule
{
    /**
     * @param array
     * @return array
     */
    public function contractInfo($row)
    {
        unset($row['contract']);
        $request = new ContractInfoRequest($this->tool->data, $row);
        return $this->post($request);
    }

    /**
     * @param array
     * @return array
     */
    public function contractsSearch($rows = [])
    {
        unset($row['contract']);
        $request = new ContractsSearchRequest($this->tool->data, $rows);
        return $this->post($request);
    }
}
