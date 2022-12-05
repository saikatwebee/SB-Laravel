<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToKycTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kyc', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'kyc_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kyc', function (Blueprint $table) {
            $table->dropForeign('kyc_ibfk_1');
        });
    }
}
