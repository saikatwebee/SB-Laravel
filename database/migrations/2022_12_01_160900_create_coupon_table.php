<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('coupon', 20)->nullable();
            $table->integer('customer_id')->nullable()->index('coupon_ibfk_1')->comment('link with customer  table');
            $table->integer('silver')->nullable();
            $table->integer('gold')->nullable();
            $table->integer('platinum')->nullable();
            $table->date('date_added')->nullable();
            $table->integer('spinwheel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon');
    }
}
