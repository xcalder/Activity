<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 32);
            $table->string('description')->nullable();
            $table->string('tag', 32)->nullable();
            $table->string('tag_img')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->bigInteger('store_id')->default(0);
            $table->bigInteger('category_id');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->timestamps();
        });
        
        Schema::create('product_activity_products', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('product_id');
            $table->bigInteger('product_specification_value_to_product_id');
            $table->bigInteger('storage');
            $table->bigInteger('sales_volume')->default(0);
            $table->bigInteger('activity_limit')->default(0);
            $table->float('price', 11, 2);
        });
        
        Schema::create('product_activity_products_gift_products', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('product_id');
            $table->bigInteger('product_specification_value_to_product_id');
            $table->bigInteger('storage');
            $table->bigInteger('sales_volume')->default(0);
            $table->bigInteger('activity_limit')->default(0);
            $table->float('price', 11, 2);
        });
        
        Schema::create('product_activity_products_role_price', function (Blueprint $table) {
            $table->bigInteger('product_to_product_activity_id');
            $table->bigInteger('product_specification_value_to_product_id');
            $table->bigInteger('role_id');
            $table->bigInteger('product_id');
            $table->float('price', 11, 2);
        });
        
        Schema::create('product_activity_order_item', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('price_limit')->default(0);
            $table->float('credit', 11, 2);
        });
        
        Schema::create('product_activity_order_item_gift_products', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('price_limit')->default(0);
            $table->float('credit', 11, 2);
        });
        
        Schema::create('product_activity_order_item_log', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('price_limit')->default(0);
            $table->float('credit', 11, 2);
        });
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_activity');
        Schema::dropIfExists('product_to_product_activity');
        Schema::dropIfExists('product_to_product_activity_role_price');
        Schema::dropIfExists('product_activity_order_item_log');
        Schema::dropIfExists('product_activity_order_item');
        Schema::dropIfExists('product_activity_products_gift_products');
    }
}
