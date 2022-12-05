<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberplaneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriberplane', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('type', 2)->nullable();
            $table->string('title')->nullable();
            $table->integer('cost')->nullable();
            $table->string('tax', 200)->nullable();
            $table->integer('plancost')->nullable();
            $table->integer('apply')->nullable();
            $table->integer('problem_posting')->nullable();
            $table->integer('number_solution')->nullable();
            $table->integer('validity')->nullable();
            $table->integer('nda_confidentiality');
            $table->string('coupon', 20)->nullable();
            $table->integer('discount')->nullable();
            $table->integer('refer_discount')->nullable();
            $table->integer('refer_wallet')->nullable();
            $table->integer('product_display_details')->nullable();
            $table->integer('company_website_display')->nullable();
            $table->integer('company_phone_number_display')->nullable();
            $table->integer('micro_site')->nullable();
            $table->dateTime('date_added')->nullable();
            $table->integer('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriberplane');
    }
}
