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
use hiapi\legacy\lib\deps\err;


/**
 * Domain operations.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class PollModule extends AbstractModule implements ObjectModuleInterface
{
    public function pollsGetNew($data = null)
    {
        foreach (['ok', 'expired', 'outgoing'] as $state) {
            $domains = $this->base->domainsSearchForPolls([
                'status' => $state,
                'access_id' => $this->tool->data['id'],
            ]);

            if (empty($domains)) {
                continue;
            }

            $polls = call_user_func_array([$this, "_pollsGet" . ucfirst($state) . "Message"], [$polls, $domains]);
        }

        return empty($polls) ? true : $polls;
    }

    protected function _pollsGetOkMessage($polls = [], $domains = [])
    {
        if (empty($domains)) {
            return $polls;
        }

        foreach ($domains as $domain) {
            $data = $this->base->domainInfo($domain);

            if (err::not($data)) {
                continue;
            }

            if (strpos(err::get($data), self::ERROR_OBJECT_DOES_NOT_EXIST) !== false) {
                $polls[] = $this->_pollBuild($domain, [
                    'type' => 'pendingTransfer',
                    'message' => 'Transfer requested',
                ], true);
            }
        }

        return $polls;
    }

    protected function _pollsGetExpiredMessage($polls = [], $domains =[])
    {
        if (empty($domains)) {
            return $polls;
        }

        foreach ($domains as $domain) {
            $data = $this->base->domainInfo($domain);

            if (err::not($data)) {
                continue;
            }

            if (strpos(err::get($data), self::ERROR_OBJECT_DOES_NOT_EXIST) !== false) {
                $this->base->domainSetStateInDb(array_merge($domain, ['state' => 'deleting']));
                $polls[] = $this->_pollBuild($domain, [
                    'type' => 'pendingDelete',
                    'message' => 'domain deleted',
                ], false);
            }
        }

        return $polls;
    }

    protected function _pollsGetOutgoingMessage($polls = [], $domains) : array
    {
        if (empty($domains)) {
            return $polls;
        }

        foreach ($domains as $id => $domain) {
            $info = $this->base->domainInfo($domain);

            if (err::is($info) && strpos(err::get($info), self::ERROR_OBJECT_DOES_NOT_EXIST) !== false) {
                $polls[] = $this->_pollBuild($domain, [
                    'type' => 'serverApproved',
                    'message' => 'Transfer approved',
                ], true);
            }
        }

        return $polls;
    }


    private function _pollBuild($row, $data, $outgoing = false) : array
    {
        return array_merge([
            'class' => 'domain',
            'name' => $row['domain'],
            'request_client' => $this->tool->data['name'],
            'request_date' => date("Y-m-d H:i:s"),
            'action_date' => date("Y-m-d H:i:s"),
            'action_client' => $this->tool->data['name'],
            'outgoing' => $outgoing,
        ], $data);
    }
}
