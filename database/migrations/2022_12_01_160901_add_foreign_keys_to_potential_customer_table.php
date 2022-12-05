<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPotentialCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('potential_customer', function (Blueprint $table) {
            $table->foreign(['company_id'], 'potential_customer_ibfk_1')->references(['id'])->on('database_complete')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['created_by'], 'potential_customer_ibfk_2')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('potential_customer', function (Blueprint $table) {
            $table->dropForeign('potential_customer_ibfk_1');
            $table->dropForeign('potential_customer_ibfk_2');
        });
    }
}
