<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWebinarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webinar', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'webinar_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webinar', function (Blueprint $table) {
            $table->dropForeign('webinar_ibfk_1');
        });
    }
}
