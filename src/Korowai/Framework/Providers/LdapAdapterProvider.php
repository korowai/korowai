<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use Korowai\Component\Ldap\Ldap;

class LdapAdapterProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $databases = config('ldap.databases');
        $keys = static::ldapInstanceNames($databases);
        $this->app->instance('ldap.databases', $keys);
        foreach($databases as $db) {
            $factory = $db['factory'] ?? null;
            $this->app->singleton("ldap.db." . $db['id'],
                function ($app) use ($db, $factory) {
                    return Ldap::createWithConfig($db['server'], $factory);
                }
            );
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        $keys = static::ldapInstanceNames(config('ldap.databases'));
        array_unshift($keys, 'ldap.databases');
        return $keys;
    }

    /**
     * Given an array of database configs returns an array of string keys used
     * to identify corresponding Ldap instances in lumen application.
     *
     * @return array
     */
    static protected function ldapInstanceNames(array $databases) : array
    {
        return array_map(function ($db) {
            return 'ldap.db.' . $db['id'];
        }, $databases);
    }
}

// vim: syntax=php sw=4 ts=4 et:
