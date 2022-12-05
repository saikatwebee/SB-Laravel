<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_category', function (Blueprint $table) {
            $table->foreign(['category_id'], 'customer_category_ibfk_1')->references(['id'])->on('category')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'customer_category_ibfk_2')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_category', function (Blueprint $table) {
            $table->dropForeign('customer_category_ibfk_1');
            $table->dropForeign('customer_category_ibfk_2');
        });
    }
}
