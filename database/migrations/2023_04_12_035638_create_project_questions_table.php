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
        Schema::create('project_questions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('customer_name', 125)->nullable();
            $table->string('project_title')->nullable();
            $table->string('designation', 55)->nullable();
            $table->string('company_name', 55)->nullable();
            $table->string('industry_category', 55)->nullable();
            $table->string('location', 55)->nullable();
            $table->string('language', 55)->nullable();
            $table->string('turnover', 55)->nullable();
            $table->string('industry_vertical', 55)->nullable();
            $table->string('products_manufactured', 55)->nullable();
            $table->string('to_manufacture', 55)->nullable();
            $table->integer('project_no')->nullable()->index('project_no')->comment('link with problem table');
            $table->string('project_type', 25)->nullable();
            $table->string('problem_type', 125)->nullable();
            $table->string('budget', 55)->nullable();
            $table->string('timeline', 25)->nullable();
            $table->string('project_stage', 55)->nullable();
            $table->string('requirement', 10000)->nullable();
            $table->string('type_of_consultant', 600)->nullable();
            $table->string('comments', 650)->nullable();
            $table->string('skills')->nullable();
            $table->string('experience', 55)->nullable();
            $table->string('status', 125)->nullable();
            $table->string('manager', 125)->nullable();
            $table->timestamp('date_added')->useCurrent();
            $table->integer('draft')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_questions');
    }
};
