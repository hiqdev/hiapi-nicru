<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests\contact;

/**
 * Contact info request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContactInfoRequest extends ContactAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'search';
    protected $bodyStatic = [
        'contact-limit' => 1,
        'contact-firts' => 1,
        'type' => 'registrant',
    ];
    protected $bodyVariables = [
        'nic-hdl' => 'epp_id',
        'domain' => 'domain',
        'contract' => 'contract_id',
    ];
}
