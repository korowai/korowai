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
use Korowai\Framework\Api\Http\Serializers\JsonApiSerializer;

class FractalServiceProvider extends ServiceProvider
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
        $this->registerFractalManager();
        $this->registerFractalTransformer();
    }

    protected function registerFractalManager()
    {
        $this->app->bind('\League\Fractal\Manager', function($app) {
            $fractal = new \League\Fractal\Manager;
            $serializer = new JsonApiSerializer($this->apiConfigDetermineUrlBase());
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
