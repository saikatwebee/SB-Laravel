<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMicroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('micro', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 500);
            $table->integer('cid')->nullable()->index('micro_ibfk_1')->comment('link with customer table');
            $table->string('email', 750);
            $table->string('phone', 250);
            $table->string('topic', 1250)->nullable();
            $table->date('selected_date')->nullable();
            $table->string('slot', 500);
            $table->integer('payment_status')->default(0)->comment('0- payment not done,
1- payment done');
            $table->timestamp('date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('micro');
    }
}
