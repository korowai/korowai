<?php

namespace App\Providers\Ldap;

use Illuminate\Support\ServiceProvider;
use Korowai\Component\Ldap\Adapter\ExtLdap\AdapterFactory;
use App\Korowai\LdapAdapterFactories;

class LdapAdapterFactoriesProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred
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
      $this->app->singleton(LdapAdapterFactories::class, function($app) {
        $servers = config('ldap.servers');
        $factories = new LdapAdapterFactories();
        // TODO: error handling (array_walk returns FALSE on failure)
        array_walk($servers, function(&$config, $key) use (&$factories) {
          if(isset($config['factory'])) {
            $factoryClass = $config['factory'];
            unset($config['factory']);
          } else {
            $factoryClass = AdapterFactory::class;
          }
          $factory = new $factoryClass();
          $factory->configure($config);
          $factories[$key] = $factory;
        });
        return $factories;
      });
    }

    /**
     * Get the services provided by the provider
     *
     * @return array
     */
    public function provides()
    {
      return [LdapAdapterFactories];
    }
}
