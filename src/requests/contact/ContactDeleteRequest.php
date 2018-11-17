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
 * Contact delete request composer.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class ContactDeleteRequest extends ContactAbstractRequest
{
    /* {@inheritdoc} */
    protected $operation = 'delete';
    protected $bodyStatic = [
        'type' => 'registrant',
    ];
    protected $bodyVariables = [
        'nic-hdl' => 'epp_id',
    ];
    protected $answer = [
        'delimiter' => 'contact',
        'fields' => [
            'nic-hdl' => 'epp_id',
        ],
    ];
}
