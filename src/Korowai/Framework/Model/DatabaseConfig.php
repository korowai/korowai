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

class DatabaseConfig implements Arrayable
{
    const DEFAULT_CONFIG = 'ldap.databases';
    const DEFAULT_OPTION_KEYS = array(
        'id', 'name', 'desc', 'base', 'binddn', 'host', 'port', 'uri',
        'encryption', 'options'
    );

    private $id;
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
        throw new \RuntimeException("hoho: " . var_export($array,true));
        array_walk($array, function($key, $value) { $this->$key = $value; });
    }

    public function parseConfig(array $config) : array
    {
        $keys = array_filter(static::DEFAULT_OPTION_KEYS, function($key) use ($config) {
            return in_array($key, $config, true);
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
}
// vim: syntax=php sw=4 ts=4 et:
