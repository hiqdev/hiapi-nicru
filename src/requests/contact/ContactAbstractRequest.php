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

use hiapi\nicru\requests\AbstractRequest;
use hiapi\nicru\requests\NicRuRequestInterface;

/**
 * Contact main request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
abstract class ContactAbstractRequest extends AbstractRequest implements NicRuRequestInterface
{
    /* {@inheritdoc} */
    protected $request = 'contact';
    protected $header = 'contact';
    protected $bodyVariables = [
        'nic-hdl' => 'epp_id',
    ];
    protected $answer = [
        'delimiter' => 'contact',
        'fields' => [
            'nic-hdl' => 'epp_id',
            'country' => 'country_code',
            'contract' => 'contract',
            'status' => 'status',
            'org' => 'org',
            'street' => 'street',
            'name' => 'name',
            'region' => 'province',
            'zipcode' => 'postal_code',
            'phone' => 'voice_phone',
            'email' => 'email',
            'city' => 'city',
            'fax' => 'fax_phone',
        ],
    ];
}
