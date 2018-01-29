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
use Korowai\Framework\Http\Api\Serializers\JsonApiSerializer;

class FractalServiceProvider extends ServiceProvider
{

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
        $this->registerFractalManager();
        $this->registerFractalTransformer();
    }

    protected function registerFractalManager()
    {
        $this->app->bind('\League\Fractal\Manager', function($app) {
            $fractal = new \League\Fractal\Manager;

            // FIXME: elaborate how to provide base URL to JsonApiSerializer
            // FIXME: seems like it's too early to use request, router, etc.
            //$serializer = new \League\Fractal\Serializer\JsonApiSerializer('');
            $urlBase = '';
            if((bool)($apiPrefix = config('api.prefix')) && ($apiPrefix != '/')) {
                $urlBase = '/' . ltrim($apiPrefix, '/');
            }
            if((bool)($apiDomain = config('api.domain'))) {
                // FIXME: HTTP vs. HTTPS?
                $urlBase = 'http://' . $apiDomain . $urlBase;
            }
            $serializer = new JsonApiSerializer($urlBase);

            $fractal->setSerializer($serializer);

            return $fractal;
        });

    }

    protected function registerFractalTransformer()
    {
        $this->app->bind('Dingo\Api\Transformer\Adapter\Fractal', function($app) {
            $fractal = $app->make('\League\Fractal\Manager');

            return new \Dingo\Api\Transformer\Adapter\Fractal($fractal);
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
