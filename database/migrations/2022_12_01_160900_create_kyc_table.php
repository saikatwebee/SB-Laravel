<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('customer_id')->nullable()->index('kyc_ibfk_1')->comment('link with customer table');
            $table->text('industry')->nullable();
            $table->text('skills')->nullable();
            $table->text('others')->nullable();
            $table->text('location_preference')->nullable();
            $table->text('state')->nullable();
            $table->text('city')->nullable();
            $table->text('language')->nullable();
            $table->text('bio')->nullable();
            $table->text('freelance')->nullable();
            $table->text('latest_project')->nullable();
            $table->text('cv')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->text('certification')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('date_updated')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyc');
    }
}
