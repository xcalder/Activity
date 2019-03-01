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
    
    protected function createSiteActivityGiftMoneyDriver()
    {
        return $this->buildProvider(SiteActivityGiftMoney::class);
    }
    
    protected function createSiteActivitySpikeDriver()
    {
        return $this->buildProvider(SiteActivitySpike::class);
    }
    
    protected function createSiteActivityVoucherDriver()
    {
        return $this->buildProvider(SiteActivityVoucher::class);
    }
    
    protected function createOrderActivityGiftMoneyDriver()
    {
        return $this->buildProvider(OrderActivityGiftMoney::class);
    }
    
    protected function createProductActivityGiftMoneyDriver()
    {
        return $this->buildProvider(ProductActivityGiftMoney::class);
    }
    
    protected function createOrderActivityVoucherDriver()
    {
        return $this->buildProvider(OrderActivityVoucher::class);
    }
    
    protected function createProductActivityVoucherDriver()
    {
        return $this->buildProvider(ProductActivityVoucher::class);
    }
    
    protected function createProductActivityGiftVoucherDriver()
    {
        return $this->buildProvider(ProductActivityGiftVoucher::class);
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
    
    protected function createOrderActivityFullDeliveryDriver()
    {
        return $this->buildProvider(OrderActivityFullDelivery::class);
    }
    
    protected function createOrderActivityFullReductionDriver()
    {
        return $this->buildProvider(OrderActivityFullReduction::class);
    }
    
    protected function createOrderActivityIncrEasePriceRedemptionDriver()
    {
        return $this->buildProvider(OrderActivityIncrEasePriceRedemption::class);
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
    
    /**
     * 检测商品是否和已有活动重叠
     * @param unknown $request
     */
    public function checkoutDateTimeCoincide($request, $id){
        $server = new Server();
        return $server->checkoutDateTimeCoincide($request, $id);
    }
}
