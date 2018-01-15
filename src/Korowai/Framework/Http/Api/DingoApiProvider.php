<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Framework\Http\Api;

use Illuminate\Support\ServiceProvider;

class DingoApiProvider extends ServiceProvider
{
    const PATH = '???';
    /**
     * Indicates if loading of the provider is deferred
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      $this->publishes([ /* __DIR__.self::PATH => config('???') */ ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      // $this->app->singleton(Foo::class, ....);
    }

    /**
     * Get the services provided by the provider
     *
     * @return array
     */
    public function provides()
    {
      // return ['???'];
    }
}
// vim: syntax=php sw=4 ts=4 et:
