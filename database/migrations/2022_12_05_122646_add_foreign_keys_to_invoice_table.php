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
        Schema::table('invoice', function (Blueprint $table) {
            $table->foreign(['plan_id'], 'invoice_ibfk_2')->references(['id'])->on('subscriberplane')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'invoice_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['project_id'], 'invoice_ibfk_3')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropForeign('invoice_ibfk_2');
            $table->dropForeign('invoice_ibfk_1');
            $table->dropForeign('invoice_ibfk_3');
        });
    }
};
