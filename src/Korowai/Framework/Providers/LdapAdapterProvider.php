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
use Korowai\Component\Ldap\Adapter\AdapterFactoryInterface;

class LdapAdapterFactories
{
    // TODO: implement
};

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
        $this->app->singleton('ldap.factories', function ($app) {
            return new LdapAdapterFactories;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ldap.factories'];
    }
}

// vim: syntax=php sw=4 ts=4 et:
