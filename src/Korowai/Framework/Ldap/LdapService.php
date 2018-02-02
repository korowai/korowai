<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Ldap;

use Korowai\Component\Ldap\Ldap;
use Korowai\Framework\Ldap\LdapInstance;

class LdapService
{
    /**
     * Ldap instances
     *
     * @var array
     */
    protected $instances = array();

    /**
     * Config used to generate instances
     *
     * @var array
     */
    protected $config;

    /**
     * Initializes the LdapService
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    /**
     * Setup new config array.
     *
     * @param array $config
     */
    protected function setConfig(array $config)
    {
        $keys = array_map(function ($db) { return $db['id']; }, $config);
        $this->config = array_combine($keys, array_values($config));
    }

    /**
     * Return config for database identified by $id. If $id is missing, return
     * whole config array for all databases.
     *
     * @param string $id
     * @return array|null
     */
    public function getConfig(string $id = null)
    {
        return null === $id ? $this->config : ($this->config[$id] ?? null);
    }

    /**
     * Get an array of valid IDs.
     *
     * @return array
     */
    public function getIds() : array
    {
        return array_keys($this->config);
    }

    /**
     * Get Ldap instance with given ID.
     *
     * @param string $id
     * @return \Korowai\Component\Ldap\LdapInterface|null
     */
    public function getInstance(string $id)
    {
        if(!isset($this->instances[$id])) {
            if(null === ($config = $this->getConfig($id))) {
                return null;
            }
            $this->instances[$id] = $this->createInstance($config);
        }
        return $this->instances[$id];
    }

    /**
     * Create Ldap instance according to $config
     *
     * @param array $config
     * @return LdapInstance
     */
    public function createInstance(array $config) : LdapInstance
    {
        $factory = $config['factory'] ?? null;
        $ldap = Ldap::createWithConfig($config['server'], $factory);
        return new LdapInstance($ldap, $config);
    }
}

// vim: syntax=php sw=4 ts=4 et:
