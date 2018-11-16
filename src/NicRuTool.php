<?php
/**
 * hiAPI NIC.ru plugin
 *
 * @link      https://github.com/hiqdev/hiapi-nicru
 * @package   hiapi-nicru
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiapi\nicru;

use hiapi\nicru\modules\AbstractModule;
use hiapi\nicru\modules\DomainModule;
use hiapi\nicru\modules\HostModule;
use hiapi\nicru\requests\AbstractRequest;
use Exception;

/**
 * NIC.ru tool.
 */
class NicRuTool extends \hiapi\components\AbstractTool
{

    protected $url;
    protected $login;
    protected $password;

    protected $httpClient = null;

    protected $modules = [
        'domain'    => DomainModule::class,
        'domains'   => DomainModule::class,
        'host'      => HostModule::class,
        'hosts'     => HostModule::class,
    ];

    public function __construct($base = null, $data = null)
    {
        parent::__construct($base, $data);
        foreach (['url','login','password'] as $key) {
            if (empty($data[$key])) {
                throw new Exception("`$key` must be given for NicRuTool");
            }
            $this->{$key} = $data[$key];
        }
    }

    public function __call($command, $args)
    {
        $parts = preg_split('/(?=[A-Z])/', $command);
        $entity = reset($parts);
        $module = $this->getModule($entity);

        return call_user_func_array([$module, $command], $args);
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function getModule($name)
    {
        if (empty($this->modules[$name])) {
            throw new InvalidCallException("module `$name` not found");
        }
        $module = $this->modules[$name];
        if (!is_object($module)) {
            $this->modules[$name] = $this->createModule($module);
        }

        return $this->modules[$name];
    }

    /**
     * This method is for testing purpose only
     *
     * @param string $name
     * @param AbstractModule $module
     */
    public function setModule(string $name, AbstractModule $module): void
    {
        if (!key_exists($name, $this->modules)) {
            throw new InvalidCallException("module `$name` not found");
        }
        $this->modules[$name] = $module;
    }

    public function createModule($class)
    {
        return new $class($this);
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient(): HttpClient
    {
        if ($this->httpClient === null) {
            $guzzle = new \GuzzleHttp\Client(['base_uri' => $this->url]);
            $this->httpClient = new HttpClient($guzzle);
        }
        return $this->httpClient;
    }

    public function setHttpClient($httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Performs http request with specified method
     * Direct usage is deprecated
     *
     * @param string $httpMethod
     * @param object $data
     * @return array
     */
    public function request(string $method, AbstractRequest $request)
    {
        return $this->getHttpClient()->performRequest($method, $request);
    }
}
