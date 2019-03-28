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
            $table->tinyInteger('type')->default(1);
            $table->bigInteger('store_id')->default(0);
            $table->bigInteger('category_id');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->timestamps();
        });
        
        Schema::create('product_activity_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->float('price', 11, 2)->default(0);
            $table->float('limit', 11, 2)->default(0);
            $table->bigInteger('total')->default(0);
            $table->bigInteger('get_limit')->default(0);
            $table->tinyInteger('status')->default(1);
        });
        
        Schema::create('product_activity_rule_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('activity_rules_id')->default(0);
            $table->bigInteger('role_id');
            $table->float('price', 11, 2)->default(0);
            $table->bigInteger('product_id')->default(0);
            $table->bigInteger('product_specification_value_to_product_id')->default(0);
        });
        
        Schema::create('product_activity_rule_products', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('activity_id');
            $table->bigInteger('activity_rules_id')->default(0);
            $table->bigInteger('product_id');
            $table->bigInteger('product_specification_value_to_product_id');
            $table->bigInteger('sales_storage')->default(0);
            $table->bigInteger('sales_volume')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('type')->default(1);
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
        Schema::dropIfExists('product_activity_rules');
        Schema::dropIfExists('product_activity_rule_roles');
    }
}
