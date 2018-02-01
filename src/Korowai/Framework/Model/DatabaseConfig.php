<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Model;

//use Illuminate\Contracts\Support\Arrayable;

/**
 * @todo Write documenation for DatabaseConfig
 */
class DatabaseConfig
{
    const PUBLIC_KEYS = array(
        'id',
        'name',
        'desc',
        'base',
        'server.host',
        'server.port',
        'server.uri'
    );

    /**
     * @var array
     */
    private $config;

    public static function all() : array
    {
        $databases = config('ldap.databases');
        return array_map(function($db) {
            return new DatabaseConfig($db);
        }, $databases);
    }

    public static function findById($id) : array
    {
        $databases = config('ldap.databases');
        $databases = array_filter($databases, function($db) use ($id) {
            return $db['id'] == $id;
        });
        return array_map(function($db) {
            return new DatabaseConfig($db);
        }, $databases);
    }

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getPublicConfig()
    {
        $config = array();
        foreach(self::PUBLIC_KEYS as $path) {
            if($this->hasConfigKey($path)) {
                $value = $this->getConfigValue($path);
                $this->writeToArray($config, $path, $value);
            }
        }
        return $config;
    }

    public function hasConfigKey(string $path)
    {
        return $this->accessConfigEntry($path,
            function($path) {
                return false;
            },  function($value, $path) {
                return true;
            }
        );
    }

    public function getConfigValue(string $path)
    {
        return $this->accessConfigEntry($path,
            function($path) {
                return null;
            },  function($value, $path) {
                return $value;
            }
        );
    }

    protected function accessConfigEntry(string $path, callable $notFound, callable $found)
    {
        $stack = explode('.', $path);
        $config = $this->config;
        foreach($stack as $key) {
            if(!array_key_exists($key, $config)) {
                return call_user_func($notFound, $path);
            }
            $config = $config[$key];
        }
        return call_user_func($found, $config, $path);
    }

    protected function writeToArray(array &$array, string $path, $value)
    {
        $stack = explode('.', $path);
        $last = array_pop($stack);
        $current = &$array;
        foreach($stack as $key) {
            if(!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = array();
            }
            $current = &$current[$key];
        }
        $current[$last] = $value;
    }

    public function getId()
    {
        return $this->config['id'];
    }

    public function getName()
    {
        return $this->config['name'];
    }

    public function getDesc()
    {
        return $this->config['desc'];
    }

    public function getBase()
    {
        return $this->config['base'];
    }

    public function getBind()
    {
        return $this->config['bind'];
    }

    public function getServerConfig($key = null)
    {
        return $key ? $this->config['server'][$key] : $this->config['server'];
    }
}
// vim: syntax=php sw=4 ts=4 et:
