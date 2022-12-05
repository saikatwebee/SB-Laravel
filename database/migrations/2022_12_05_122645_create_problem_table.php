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
        Schema::create('problem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('problem_ibfk_1')->comment('link with customer table');
            $table->string('title')->nullable();
            $table->integer('industries')->nullable()->index('problem_ibfk_2')->comment('link with industries table');
            $table->integer('sub_cat')->nullable()->index('problem_ibfk_3')->comment('link with category table');
            $table->integer('skills')->nullable()->index('problem_ibfk_4')->comment('link with skill table');
            $table->text('describe')->nullable();
            $table->integer('time_limit')->default(1)->comment('1- < 1 month,
2- 1 month – 3 months,
3- 3 months – 6 months,
4- > 6 months');
            $table->string('files', 250)->nullable();
            $table->integer('status')->nullable();
            $table->string('industries_other')->nullable();
            $table->string('category_other')->nullable();
            $table->integer('typeofproject')->nullable()->comment('1- Onsite,
2- Work from home');
            $table->string('budget', 50)->nullable();
            $table->string('location', 50)->nullable();
            $table->string('state', 250)->nullable();
            $table->integer('action')->default(1)->comment('0- Not Live,
1- Live,
2- Awarded(Normal)/Proposal sent (Execution),
3- On Hold,
4- Awarded(Execution),
5- Dropped(Execution),');
            $table->dateTime('date_added')->nullable();
            $table->dateTime('awarded_date')->nullable();
            $table->dateTime('dropped_date')->nullable();
            $table->dateTime('live_date')->nullable();
            $table->dateTime('assigned_date')->nullable();
            $table->integer('assigned_to')->nullable()->index('problem_ibfk_7');
            $table->integer('assigned_to_1')->nullable()->index('problem_ibfk_8');
            $table->integer('execution')->nullable()->comment('0- Not Assigned
1- Normal Project
2- Execution Project');
            $table->string('slug')->nullable();
            $table->integer('potential')->default(0)->comment('0- Marked as Potential customer by Project Managers ,
1- Marked as Not Potential customer by Project Managers');
            $table->integer('ceo_potential')->default(0)->comment('0- Marked as Potential customer by CEO ,
1- Marked as Not Potential customer by CEO');
            $table->integer('auto_project')->default(0)->comment('0- Not Assigned (through Auto Project Assign),
1- Assigned (through Auto Project Assign)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problem');
    }
};
