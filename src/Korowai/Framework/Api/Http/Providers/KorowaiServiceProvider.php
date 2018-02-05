<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Api\Http\Providers;

use Illuminate\Support\ServiceProvider;
use Korowai\Framework\Api\Http\Providers\FractalServiceProvider;
use Korowai\Framework\Providers\LdapServiceProvider;
use Korowai\Framework\Api\Http\Serializers\JsonApiSerializer;
use Korowai\Framework\Api\Http\Exceptions\Handler as KorowaiExceptionHandler;
use Korowai\Framework\Api\Http\Middleware\LdapBind as LdapBindMiddleware;
use Korowai\Component\Ldap\Exception\LdapException;
use Dingo\Api\Provider\LumenServiceProvider as DingoServiceProvider;
use Dingo\Api\Exception\Handler as DingoExceptionHandler;


class KorowaiServiceProvider extends ServiceProvider
{
    use \Korowai\Framework\Api\Http\Traits\ApiConfigTrait;

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
        $this->registerRequiredProviders();
        $this->app->configure('api');
        $this->registerMiddleware();
        $this->registerRoutes();
        $this->registerExceptionHandler();
    }

    /**
     * Register other providers required by this one.
     */
    protected function registerRequiredProviders()
    {
        $this->app->register(FractalServiceProvider::class);
        $this->app->register(LdapServiceProvider::class);
        $this->app->register(DingoServiceProvider::class);
    }

    /**
     * Read our part of 'api' config
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../../config/api.php'), 'api');
        if(!$this->app->runningInConsole()) {
            // Internally this validates configuration and throws an exception
            // if something gets wrong...
            $this->apiConfigDetermineUrlBase();
        }
    }

    /**
     * Register korowai middlewares.
     */
    protected function registerMiddleware()
    {
        $this->app->routeMiddleware([ 'ldapBind' => LdapBindMiddleware::class ]);
    }

    /**
     * Read routes.
     */
    protected function registerRoutes()
    {
        $app = $this->app;
        require __DIR__.'/../../../routes/api.php';
    }

    /**
     * Register korowai exception handler.
     */
    protected function registerExceptionHandler()
    {
        $dingoHandler = $this->app->make(DingoExceptionHandler::class);
        $korowaiHandler = new KorowaiExceptionHandler($dingoHandler);
        $this->app->instance(KorowaiExceptionHandler::class, $korowaiHandler);
    }
}

// vim: syntax=php sw=4 ts=4 et:
