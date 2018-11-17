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

use hiapi\nicru\requests\order\OrderInfoRequest;
use hiapi\nicru\requests\order\OrderCancelRequest;
use hiapi\nicru\exceptions\OrderProcessingException;

/**
 * Order operations.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class OrderModule extends AbstractModule
{
    const STATE_FAILED = 'failed';
    const STATE_DELETED = 'deleted';
    const STATE_RUNNING = 'running';

    /**
     * Get info about order
     *
     * @param array $row
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    public function orderInfo(array $row) : array
    {
        unset($row['contract']);
        $request = new OrderInfoRequest($this->tool->data, $row);
        $res = $this->post($request);
        if (!empty($res['order-item'])) {
            if (in_array($res['order-item']['state'], [self::STATE_FAILED, self::STATE_DELETED], true)) {
                throw new OrderProcessingException('action is failed or deleted');
            }
        }

        return $res;
    }

    /**
     * Cancel order
     *
     * @param array $row
     * @return array
     * @throws \hiapi\nicru\exceptions\NicRuException
     */
    public function orderCancel(array $row) : array
    {
        $request = new OrderCancelRequest($this->tool->data, $row);
        return $this->post($request);
    }
}
