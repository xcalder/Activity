<?php

namespace Activity\Interfaces\Facades;

use Activity\Factory;
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
        return Factory::class;
    }
}
