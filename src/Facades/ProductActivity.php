<?php

namespace Activity\Interfaces\Facades;

use Illuminate\Support\Facades\Facade;

class ProductActivity extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ProductActivity';
    }
}
