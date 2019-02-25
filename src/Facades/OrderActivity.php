<?php

namespace Activity\Interfaces\Facades;

use Illuminate\Support\Facades\Facade;

class OrderActivity extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'OrderActivity';
    }
}
