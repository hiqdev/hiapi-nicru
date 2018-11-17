<?php
/**
 * hiAPI NicRu plugin
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
use hiapi\nicru\exceptions\InvalidCallException;
use hiapi\nicru\exceptions\RequiredParamMissingException;

/**
 * NicRu tool.
 */
class NicRuTool extends \hiapi\components\AbstractTool
{
    /* @var string */
    protected $url;

    /* @var string */
    protected $login;

    /* @var string */
    protected $password;

    /* @var object [[HttpClient]] */
    protected $httpClient = null;

    /* @var array */
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
                throw new RequiredParamMissingException("`$key` must be given for NicRuTool");
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

    /**
     * @param string $name
     * @return class
     */
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
        if (empty($this->modules[$name])) {
            throw new InvalidCallException("module `$name` not found");
        }
        $this->modules[$name] = $module;
    }

    /**
     * @param class $class
     * @return object of $class
     */
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

    /**
     * @param object [[HttpClient]] $httpClient
     * @return object [[NicRuTool]]
     */
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
     * @param object $request
     * @return array
     */
    public function request(string $method, AbstractRequest $request)
    {
        return $this->getHttpClient()->performRequest($method, $request);
    }
}
