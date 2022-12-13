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
        Schema::create('proposal', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cid')->nullable()->index('proposal_ibfk_1')->comment('link with customer table');
            $table->integer('pid')->nullable()->index('proposal_ibfk_2')->comment('link with problem table');
            $table->integer('ammount')->nullable()->comment('proposal amount of consultant');
            $table->integer('is_gst')->nullable()->comment('0- with no gst,
1- with gst');
            $table->string('gst', 550)->nullable();
            $table->string('pan', 550)->nullable();
            $table->string('proposal_doc', 650)->nullable();
            $table->timestamp('created')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal');
    }
};
