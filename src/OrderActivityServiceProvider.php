<?php

namespace Activity;

use Illuminate\Support\ServiceProvider;
use Activity\Interfaces\Facades\OrderActivity;

class OrderActivityServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(OrderActivity::class, function ($app) {
            return new OrderActivity();
        });
    }
}
