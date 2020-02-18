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

use hiapi\nicru\requests\contract\ContractInfoRequest;
use hiapi\nicru\requests\contract\ContractsSearchRequest;

/**
 * Contract operations.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContactModule extends AbstractModule implements ObjectModuleInterface
{
    /**
     * @param array
     * @return array
     */
    public function contactInfo(array $row) : array
    {
        return $row;
    }

    /**
     * @param array|void
     * @return array
     */
    public function contactsSearch($rows = []) : array
    {
        return $row;
    }

    protected function contactSet(array $row): array
    {
        return [
            'id' => $row['contract'],
        ];
    }
}
