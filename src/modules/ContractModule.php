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

use hiapi\nicru\requests\contract\ContractInfoRequest;
use hiapi\nicru\requests\contract\ContractsSearchRequest;

/**
 * Domain operations.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ContractModule extends AbstractModule
{
    public function contractInfo($row)
    {
        unset($row['contract']);
        $request = new ContractInfoRequest($this->tool->data, $row);
        $res = $this->post($request);
        return reset($res);
    }

    public function contractsSearch($rows = [])
    {
        unset($row['contract']);
        $request = new ContractsSearchRequest($this->tool->data, $rows);
        return $this->post($request);
    }
}
