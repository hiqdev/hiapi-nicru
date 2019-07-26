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

use hiapi\nicru\requests\domain\DomainInfoRequest;
use hiapi\nicru\requests\domain\DomainRenewRequest;
use hiapi\nicru\requests\domain\DomainUpdateRequest;
use hiapi\nicru\requests\domain\DomainsSearchRequest;
use hiapi\nicru\requests\service\ServicesSearchRequest;

/**
 * Domain operations.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class PollModule extends AbstractModule implements ObjectModuleInterface
{
    public function pollsGetNew($data = null)
    {
        return true;
    }
}
