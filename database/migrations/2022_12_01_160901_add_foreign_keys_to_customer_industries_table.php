<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerIndustriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_industries', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'customer_industries_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['industries_id'], 'customer_industries_ibfk_2')->references(['id'])->on('industries')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_industries', function (Blueprint $table) {
            $table->dropForeign('customer_industries_ibfk_1');
            $table->dropForeign('customer_industries_ibfk_2');
        });
    }
}
