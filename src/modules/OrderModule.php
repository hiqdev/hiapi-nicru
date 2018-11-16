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

use hiapi\nicru\requests\OrderInfoRequest;
use hiapi\nicru\requests\OrderCancelRequest;

/**
 * Domain operations.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class OrderModule extends AbstractModule
{
    const STATE_FAILED = 'failed';
    const STATE_DELETED = 'deleted';

    public function orderInfo($row)
    {
        unset($row['contract']);
        $request = new OrderInfoRequest($this->tool->data, $row);
        $res = $this->post($request);
        $res = reset($res);
        if (in_array(self::STATE_FAILED, $res['state'], true) || in_array(self::STATE_DELETED, $res['state'], true)) {
            throw new \Exception('action failed');
        }

        return $res;
    }

    public function orderCancel($row)
    {
        $request = new OrderCancelRequest($this->tool->data, $row);
        $res = $this->post($request);
        return reset($res);
    }
}
