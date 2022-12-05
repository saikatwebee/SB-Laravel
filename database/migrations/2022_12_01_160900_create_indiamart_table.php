<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndiamartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indiamart', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('api_id', 700);
            $table->string('name', 500)->nullable();
            $table->string('email', 500)->nullable();
            $table->string('phone', 125)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('company_name')->nullable();
            $table->string('product_name', 550)->nullable();
            $table->string('requirement', 1050)->nullable();
            $table->string('enquiry_type', 1000)->nullable();
            $table->dateTime('api_date', 6)->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('date_added')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indiamart');
    }
}
