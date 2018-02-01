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
            $serializer = new JsonApiSerializer($this->getApiUrlBase());

            $fractal->setSerializer($serializer);

            return $fractal;
        });

    }

    protected function getApiUrlBase()
    {
        $urlBase = '';
        if(($apiPrefix = config('api.prefix')) && ($apiPrefix != '/')) {
            $urlBase = '/' . ltrim($apiPrefix, '/');
        }
        if(($apiDomain = $this->getApiDomain())) {
            $scheme = $this->getApiScheme();
            $urlBase = $scheme . '://' . $apiDomain . $urlBase;
        }
        return $urlBase;
    }

    protected function getApiDomain()
    {
        if(!($apiDomain = config('api.domain'))) {
            if(($dd = config('api.default_domain'))) {
                if(in_array($dd, ['{HTTP_HOST}', '{SERVER_NAME}', '{SERVER_ADDR}'])) {
                    $apiDomain = $_SERVER[trim($dd,'{}')];
                } else {
                    $apiDomain = $dd;
                }
            }
        }
        return $apiDomain;
    }

    protected function getApiScheme()
    {
        if(!($scheme = config('api.scheme'))) {
            if($_SERVER['HTTPS'] || $_SERVER['HTTPS'] === 'off') {
                $scheme = 'https';
            } elseif(!($scheme = $_SERVER['REQUEST_SCHEME'])) {
                $scheme = 'http';
            }
        }
        return $scheme;
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
