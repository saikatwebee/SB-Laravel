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
        Schema::create('message', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('problem_id')->nullable()->index('message_ibfk_1')->comment('link with problem table');
            $table->integer('send_customer_id')->nullable()->index('message_ibfk_3')->comment('link with customer table');
            $table->integer('receive_customer_id')->nullable()->index('message_ibfk_2')->comment('link with customer table');
            $table->text('message')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->string('files')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
};
