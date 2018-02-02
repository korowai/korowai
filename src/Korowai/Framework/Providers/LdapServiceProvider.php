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
use Korowai\Framework\Ldap\LdapService;

class LdapServiceProvider extends ServiceProvider
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
        $this->registerConfig();
        $this->app->singleton(LdapService::class, function ($app) {
            return new LdapService(config('ldap.databases'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [LdapService::class];
    }

    /**
     * Read Ldap configuration
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/ldap.php'), 'ldap');
        $this->app->configure('ldap');
    }
}

// vim: syntax=php sw=4 ts=4 et:
