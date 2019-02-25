<?php

namespace Activity;

use Illuminate\Support\Manager;
use Activity\Factory\Factory;

class ActivityManager extends Manager implements Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }
    
    protected function createOrderActivityGiftMoneyDriver()
    {
        return $this->buildProvider(OrderActivityGiftMoney::class);
    }
    
    protected function createOrderActivityVoucherDriver()
    {
        return $this->buildProvider(OrderActivityVoucher::class);
    }
    
    protected function createProductActivitySpikeDriver()
    {
        return $this->buildProvider(ProductActivitySpike::class);
    }
    
    protected function createProductActivityFullDeliveryDriver()
    {
        return $this->buildProvider(ProductActivityFullDelivery::class);
    }
    
    protected function createProductActivityFullReductionDriver()
    {
        return $this->buildProvider(ProductActivityFullReduction::class);
    }
    
    protected function createProductActivityIncrEasePriceRedemptionDriver()
    {
        return $this->buildProvider(ProductActivityIncrEasePriceRedemption::class);
    }
    
    protected function createProductActivityLimitDiscountsDriver()
    {
        return $this->buildProvider(ProductActivityLimitDiscounts::class);
    }
    
    public function buildProvider($provider)
    {
        return new $provider();
    }

    /**
     * Get the default driver name.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Activity driver was specified.');
    }
}
