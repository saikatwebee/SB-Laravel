<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_category', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('customer_category_ibfk_2')->comment('link with customer table');
            $table->integer('category_id')->nullable()->index('customer_category_ibfk_1')->comment('link with category table');
            $table->string('other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_category');
    }
};
