<?php

/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\requests;

class AbstractRequest
{
    /** @var array **/
    protected $data = [];
    /** @var array **/
    protected $requestArray = [];

    protected $operation = null;
    protected $request = null;
    protected $header = null;
    protected $lang = 'en';
    protected $limit = 1;
    protected $first = 1;
    protected $bodyStatic = [];
    protected $bodyVariables = [];
    protected $stringRequest = "";

    protected $answer = [];

    public function __construct($data, $args)
    {
        $this->data = $data;
        foreach (['operation', 'request', 'header'] as $key) {
            if (empty($this->{$key})) {
                throw new \Exception("{$key} is required");
            }
        }

        $headers = array_filter([
            'login' => $this->data['login'],
            'password' => $this->data['password'],
            'subject-contract' => empty($args['contract']) ? null : $args['contract'],
            'lang' => 'en',
            'operation' => $this->operation,
            'request' => $this->request,
            'request-id' => microtime(true) . "@hiqdev.com",
        ]);

        foreach ($headers as $key => $value) {
            $this->requestArray[] = "{$key}:{$value}";
        }

        $this->requestArray[] = "";
        $this->requestArray[] = "[{$this->header}]";
        foreach ($this->bodyStatic as $key => $value) {
            $value = trim($value);
            if (!isset($value)) {
                continue;
            }

            $this->requestArray[] = "{$key}:{$value}";
        }
        $this->setRequestBodyVariables($args);
    }

    public function __toString()
    {
        return trim(implode("\n", $this->requestArray));
    }

    public function getAnswerParams()
    {
        return $this->answer;
    }

    protected function setRequestBodyVariables($row = [])
    {
        foreach ($this->bodyVariables as $key => $value) {
            $func = null;
            if (strpos($key, ",") !== false) {
                [$key, $func] = explode(",", $key);
            }
            if (strpos($value, ',') === false) {
                if (empty($row[$value])) {
                    continue;
                }

                $row[$value] = $func ? $func($row[$value]) : $row[$value];
                $this->requestArray[] = "{$key}:{$row[$value]}";
                continue;
            }

            [$name, $type] = explode(",", $value, 2);
            foreach ($row[$name] as $values) {
                if ($values) {
                    $values = $func ? $func($values) : $values;
                    $this->requestArray[] = "{$key}:{$values}";
                }
            }
        }
    }
}
