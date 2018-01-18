<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Model;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @todo Write documenation for DatabaseConfig
 */
class DatabaseConfig implements Arrayable
{
    const DEFAULT_CONFIG = 'ldap.databases';
    const DEFAULT_OPTION_KEYS = array(
        'id',
        'name',
        'desc',
        'base',
        'binddn',
        'host',
        'port',
        'uri',
        'encryption',
        'options'
    );
    const UI_OPTION_KEYS = array(
        'id',
        'name',
        'desc',
        'binddn',
    );

    private $id;
    private $name;
    private $desc;
    private $base;
    private $binddn;
    private $host;
    private $port;
    private $uri;
    private $encryption;
    private $options;

    public static function findById($id) : array
    {
        $databases = config(static::DEFAULT_CONFIG);
        $databases = array_filter($databases, function($db) use ($id) {
            return $db['id'] == $id;
        });
        return array_map(function($db) {
            return new DatabaseConfig($db);
        }, $databases);
    }

    public function __construct(array $config)
    {
        $array = $this->parseConfig($config);
        array_walk($array, function($value, $key) { $this->$key = $value; });
    }

    public function parseConfig(array $config) : array
    {
        $keys = array_filter(static::DEFAULT_OPTION_KEYS, function($key) use ($config) {
            return array_key_exists($key, $config);
        });
        $values = array_map(function ($key) use ($config) {
            return $config[$key];
        }, $keys);
        return array_combine($keys, $values);
    }

    public function toArray() : array
    {
        $keys = array_filter(static::DEFAULT_OPTION_KEYS, function($key) {
            return isset($this->$key);
        });
        $values = array_map(function ($key) {
            return $this->$key;
        }, $keys);
        return array_combine($keys, $values);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getBindDn()
    {
        return $this->binddn;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getEncryption()
    {
        return $this->encryption;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
// vim: syntax=php sw=4 ts=4 et:
