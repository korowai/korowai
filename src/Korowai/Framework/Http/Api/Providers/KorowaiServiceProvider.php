<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Korowai\Framework\Http\Api\Providers\FractalServiceProvider;
use Korowai\Framework\Providers\LdapAdapterProvider;
use Korowai\Framework\Http\Api\Serializers\JsonApiSerializer;
use Korowai\Framework\Http\Api\Exceptions\Handler as KorowaiExceptionHandler;
use Korowai\Framework\Http\Api\Middleware\LdapBind as LdapBindMiddleware;
use Korowai\Component\Ldap\Exception\LdapException;
use Dingo\Api\Provider\LumenServiceProvider as DingoServiceProvider;
use Dingo\Api\Exception\Handler as DingoExceptionHandler;

class ErrorSourcePointer
{
    private $app;
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function __toString()
    {
        return 'pointer!!';
        //return $this->app->request->path();
    }
}

class KorowaiServiceProvider extends ServiceProvider
{
    use \Korowai\Framework\Http\Api\Traits\ApiConfigTrait;

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
        $this->registerExceptionHandlers();
        $this->setErrorFormat();
    }

    protected function registerRequiredProviders()
    {
        $this->app->register(FractalServiceProvider::class);
        $this->app->register(LdapAdapterProvider::class);
        $this->app->register(DingoServiceProvider::class);
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../../config/api.php'), 'api');
        if(!$this->app->runningInConsole()) {
            // Internally this validates configuration
            $this->apiConfigDetermineUrlBase();
        }
    }

    protected function registerMiddleware()
    {
        $this->app->routeMiddleware([ 'ldapBind' => LdapBindMiddleware::class ]);
    }

    protected function registerRoutes()
    {
        $app = $this->app;
        require __DIR__.'/../../../routes/api.php';
    }

    protected function registerExceptionHandlers()
    {
        $dingoHandler = $this->app[DingoExceptionHandler::class];
        $dingoHandler->register(
          function (LdapException $e) {
            return KorowaiExceptionHandler::handleLdapException($e);
          }
        );
    }

    protected function setErrorFormat()
    {
        $dingoHandler = $this->app[DingoExceptionHandler::class];
        $dingoHandler->setErrorFormat([
            'errors' => [ [
                'detail'    => ':message',
                'code'      => ':code',
                'status'    => ':status_code',
                'source'    => [
                    'pointer' => ':request_path',
                ],
                'meta' => [
                    'errors' => ':errors',
                    'request_query'  => ':request_query',
                    'request_headers' => ':request_headers',
                    'debug'  => ':debug',
                ]
            ] ]
        ]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
