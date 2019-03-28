<?php

namespace Activity\Facades;

use Illuminate\Support\Facades\Facade;
use Activity\Factory\Factory;

/**
 * @see \Laravel\Socialite\SocialiteManager
 */
class Activity extends Facade
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
