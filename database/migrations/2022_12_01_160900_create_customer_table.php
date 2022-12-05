<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->integer('customer_id', true);
            $table->string('customer_type', 3);
            $table->string('firstname', 200)->nullable();
            $table->string('lastname', 32)->nullable();
            $table->string('email', 96)->nullable()->unique('email');
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->integer('country')->nullable();
            $table->string('phone', 20)->nullable();
            $table->integer('mytotalnoexp')->nullable();
            $table->string('myqualificaton')->nullable();
            $table->string('mylastposition')->nullable();
            $table->string('mycurrentposition')->nullable();
            $table->date('dob')->nullable();
            $table->string('companyname')->nullable();
            $table->string('companylogo')->nullable();
            $table->string('establishment', 100)->nullable();
            $table->string('ip', 15)->nullable();
            $table->string('status', 10)->nullable();
            $table->dateTime('date_added')->nullable();
            $table->dateTime('date_updated')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('resume')->nullable();
            $table->text('brief_bio')->nullable();
            $table->string('archievements', 1000)->nullable();
            $table->string('certification', 1000)->nullable();
            $table->string('certification_file')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('tinno')->nullable();
            $table->string('howsb', 150)->nullable();
            $table->string('lead_mapping')->nullable();
            $table->string('mc_hour', 125)->nullable();
            $table->string('mc_location', 125)->nullable();
            $table->string('mc_cost', 125)->nullable();
            $table->integer('mc_status')->nullable();
            $table->integer('whatsapp')->default(0);
            $table->string('step', 40)->default('0')->comment('1- Register,
2- Account Activation,
3-  prequalification,
4- payment page
5- already prequalified from chatbot');
            $table->integer('pre_qualified')->default(0)->comment('0- not prequalified,
1- prequalified');
            $table->string('reg_url', 500)->nullable()->comment('url from where customer has registered');
            $table->integer('unsubscribe')->default(0)->comment('0- unsubscribed,
1- subscribed,');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
