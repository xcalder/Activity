<?php

namespace Activity\Interfaces\Facades;

use Activity\Factory;
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
        return Factory::class;
    }
}
