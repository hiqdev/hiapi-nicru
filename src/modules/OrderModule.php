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

use hiapi\nicru\requests\order\OrderInfoRequest;
use hiapi\nicru\requests\order\OrderCancelRequest;

/**
 * Order operations.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class OrderModule extends AbstractModule
{
    const STATE_FAILED = 'failed';
    const STATE_DELETED = 'deleted';

    /**
     * @param array
     * @return array
     */
    public function orderInfo($row) : array
    {
        unset($row['contract']);
        $request = new OrderInfoRequest($this->tool->data, $row);
        $res = $this->post($request);
        if (in_array(self::STATE_FAILED, $res['state'], true) || in_array(self::STATE_DELETED, $res['state'], true)) {
            throw new \Exception('action failed');
        }

        return $res;
    }

    /**
     * @param array
     * @return array
     */
    public function orderCancel($row)
    {
        $request = new OrderCancelRequest($this->tool->data, $row);
        return $this->post($request);
    }
}
