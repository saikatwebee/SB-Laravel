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
        Schema::table('customer_req', function (Blueprint $table) {
            $table->foreign(['industries'], 'customer_req_ibfk_2')->references(['id'])->on('industries')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['location'], 'customer_req_ibfk_4')->references(['state_id'])->on('states')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['cid'], 'customer_req_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['category'], 'customer_req_ibfk_3')->references(['id'])->on('category')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_req', function (Blueprint $table) {
            $table->dropForeign('customer_req_ibfk_2');
            $table->dropForeign('customer_req_ibfk_4');
            $table->dropForeign('customer_req_ibfk_1');
            $table->dropForeign('customer_req_ibfk_3');
        });
    }
};
