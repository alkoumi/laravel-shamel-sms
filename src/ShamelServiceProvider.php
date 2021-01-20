<?php

namespace Alkoumi\LaravelShamelSms;

use Alkoumi\LaravelShamelSms\Facades\Shamel;
use Illuminate\Foundation\AliasLoader;
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


        // $this->app->singleton('shamel', function () {
        //     return $this->app->make('Alkoumi\LaravelShamelSms\ShamelSMS');
        // });

        // $this->app->alias(Shamel::class, 'Shamel');

        //call sentry's service provider
        $this->app->register(ShamelServiceProvider::class);

        //set up facade
        AliasLoader::getInstance()->alias('Shamel', Shamel::class);

        //bind mypackage class
        $this->app->bind('shamel', function () {
            return new ShamelSMS();
        });
        

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
