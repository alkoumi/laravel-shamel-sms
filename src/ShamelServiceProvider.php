<?php

namespace Alkoumi\LaravelShamelSms;

use Alkoumi\LaravelShamelSms\Facades\Shamel;
use Illuminate\Support\ServiceProvider;

class ShamelServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $config = __DIR__ . '/../config/shamelsms.php';
        $this->mergeConfigFrom($config, 'shamelsms');
        $this->publishes([__DIR__ . '/../config/shamelsms.php' => config_path('shamelsms.php')], 'config');


        $this->app->singleton('shamel', function () {
            return $this->app->make('Alkoumi\LaravelShamelSms\ShamelSMS');
        });

        $this->app->alias(Shamel::class, 'Shamel');

    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

}
