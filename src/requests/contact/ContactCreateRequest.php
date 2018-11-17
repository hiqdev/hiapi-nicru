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
class ContactCreateRequest extends ContactAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'create';
    protected $request = 'contact';
    protected $header = 'contact';
    protected $bodyStatic = [
        'status' => 'registrant',
    ];
    protected $bodyVariables = [
        'org' => 'organization',
        'name' => 'name',
        'region' => 'province',
        'country' => 'country_code',
        'city' => 'city',
        'street' => 'street',
        'zipcode' => 'postal_code',
        'phone' => 'voice_phone',
        'fax' => 'fax_phone',
        'email' => 'email',
    ];
    protected $answer = [
        'delimiter' => 'contact',
        'fields' => [
            'nic-hdl' => 'epp_id',
        ],
    ];
}
