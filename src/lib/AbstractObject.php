<?php

/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru\lib;

class AbstractObject
{
    /** @var array **/
    protected $data = [];
    /** @var string **/
    protected $contract = null;
    /** @var array **/
    protected $requestArray = [];

    protected $limit = 1;
    protected $first = 1;

    protected $parsedResponse;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __call($method, $data)
    {
        $contract = $this->findContract($data[0]);
        if (empty($contract->getContract())) {
            throw new \Exception($data[0], 'contract not found');
        }

        $this->contract = $contract->getContract();
        return call_user_func([$this, $method], $data[0]);
    }

    public function setLimit($n)
    {
        $this->limit = $n;
        return $this;
    }

    public function setFirst($n)
    {
        $this->first = $n;
        return $this;
    }

    public function setContract($contract = null)
    {
        $this->contract = $contract;
        return $this;
    }

    public function getContract()
    {
        return $this->contract;
    }

    public function getParsedResponse()
    {
        return $this->parsedResponse;
    }

    public function setRequestHeader($fields = [])
    {
        $this->requestArray['header'] = [];
        $headers = array_filter(array_merge([
            'login' => $this->data['login'],
            'password' => $this->data['password'],
            'lang' => 'en',
            'request-id' => microtime(true) . "@hiqdev.com",
            'subject-contract' => $this->contract,
        ], $fields));

        foreach ($headers as $key => $value) {
            $this->requestArray['header'][] = "{$key}:{$value}";
        }

        return $this;
    }

    public function setRequestBodyHeader($header)
    {
        $this->requestArray['body']['item'] = "[{$header}]";
        return $this;
    }

    public function setRequestBodyStatic($fields = [])
    {
        $this->requestArray['body']['static'] = [];
        foreach ($fields as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $this->requestArray['body']['static'][] = "{$key}:{$value}";
        }

        return $this;
    }

    public function setRequestBodyVariables($fields = [], $row = [])
    {
        $this->requestArray['body']['params'] = [];
        foreach ($fields as $key => $value) {
            $func = null;
            if (strpos($key, ",") !== false) {
                [$key, $func] = explode(",", $key);
            }
            if (strpos($value, ',') === false) {
                if (empty($row[$value])) {
                    continue;
                }

                $row[$value] = $func ? $func($row[$value]) : $row[$value];
                $this->requestArray['body']['params'][] = "{$key}:{$row[$value]}";
                continue;
            }

            [$name, $type] = explode(",", $value, 2);
            foreach ($row[$name] as $values) {
                if ($values) {
                    $values = $func ? $func($values) : $values;
                    $this->requestArray['body']['params'][] = "{$key}:{$values}";
                }
            }
        }

        return $this;
    }

    public function findContract($row)
    {
        $contract = new Contract($this->data);
        $contract->search($row);
        $c = reset($contract->getParsedResponse());
        $contract->setContract($c['contract']);

        return $contract;
    }

    public function findContracts($rows = [])
    {
        $data = new Contract($this->data);
        $data
            ->setLimit(64000)
            ->setFirst(1)
            ->search();
        foreach ($data->getParsedResponse() as $c) {
            $contract = new Contract($this->data);
            $contract->setContract($c['contract']);
            $contracts[] = $contract;
        }
        return $contracts;
    }

    protected function request()
    {
//        $data['SimpleRequest'] = $this->createRequest();
        $ch = curl_init($this->data['url']);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => "SimpleRequest=" . $this->createRequest(),
        ]);

        $res = curl_exec($ch);

        if ($res === false) {
            throw new \Exception('empty response');
        }

        $res = mb_convert_encoding($res, 'UTF-8', 'KOI8-R');
        $this->response = $res;
        return $this;
    }

    protected function createRequest()
    {
        $requestArray = array_filter([
            'header' => implode("\n", $this->requestArray['header']),
            'item' =>  "\n" . $this->requestArray['body']['item'] ,
            'static' => implode("\n", $this->requestArray['body']['static']),
            'body' => implode("\n", $this->requestArray['body']['params']),
        ]);

        /***
        var_dump(trim(implode("\n", $requestArray)));
        echo "\n\n";
        ***/
        return urlencode(trim(implode("\n", $requestArray)));
    }

    protected function parseResponse($block = [])
    {
        /***
        var_dump($this->response);
        ***/
        $lines = explode("\n", $this->response);
        if (!preg_match('#State: 200#', $lines[0])) {
            throw new \Exception(trim(preg_replace('#State: [0-9]+#', '', $lines[0])));
        }

        if (empty($block)) {
            return ['success' => true];
        }

        $blocks = explode("[{$block['delimiter']}]", $this->response);
        $i = 0;
        foreach ($blocks as $data) {
            $blockData = explode("\n", $data);
            $result[$i] = null;
            foreach ($blockData as $line) {
                [$field, $value] = explode(":", $line, 2);
                if (!empty($block['fields'][$field])) {
                    $result[$i] = $this->setParsedValue($block['fields'][$field], $value, $result[$i]);
                }
            }
            $i++;
        }

        $this->parsedResponse = array_filter($result);

        return $this;
    }

    protected function setParsedValue($field, $value, $res)
    {
        if (empty($res[$field])) {
            $res[$field] = $value;
            return $res;
        }

        if (is_array($res[$field])) {
            $res[$field][] = $value;
            return $res;
        }

        $tmp = $res[$field];
        $res[$field] = [];
        $res[$field][] = $tmp;
        $res[$field][] = $value;
        return $res;
    }
}
