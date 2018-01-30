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
    const DEFAULT_CONFIG = 'ldap.databases';
    const UI_OPTIONS = array(
        'anonymous',
        'base',
        'binddn',
        'bindpw',
        'desc',
        'factory',
        'id',
        'name',
    );

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
        $databases = config(static::DEFAULT_CONFIG);
        foreach($databases as $db) {

            $factory = array_key_exists('factory', $db) ? $db['factory'] : null;
            $this->app->singleton("ldap.db." . $db['id'], function ($app) use ($db, $factory) {
                $config = array_filter($db, function ($key) {
                    return !in_array($key, static::UI_OPTIONS);
                }, ARRAY_FILTER_USE_KEY);
                $ldap = Ldap::createWithConfig($config, $factory);
                if (array_key_exists('binddn', $db)) {
                    if(array_key_exists('anonymous', $db) && $db['anonymous']) {
                        $ldap->bind();
                    } elseif(array_key_exists('bindpw', $db)) {
                        $ldap->bind($db['binddn'], $db['bindpw']);
                    } else {
                        $ldap->bind($db['binddn']);
                    }
                }
                return $ldap;
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_map(function ($db) {
            return 'ldap.db.' . $db['id'];
        }, config(static::DEFAULT_CONFIG));
    }
}

// vim: syntax=php sw=4 ts=4 et:
