<?php

namespace Alkoumi\LaravelShamelSms\Facades;

use Illuminate\Support\Facades\Facade;

class Shamel extends Facade
{

    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shamel';
    }

}
