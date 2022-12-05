<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('customer_id')->nullable()->index('invoice_ibfk_1')->comment('link with customer table');
            $table->integer('plan_id')->nullable()->index('invoice_ibfk_2')->comment('link with subscriberplane table table');
            $table->date('date')->nullable();
            $table->string('txn_id', 500)->nullable();
            $table->integer('project_id')->nullable()->index('invoice_ibfk_3')->comment('link with problem table');
            $table->string('description', 500)->nullable();
            $table->string('comments', 455)->nullable();
            $table->string('export')->nullable();
            $table->integer('export_amount')->nullable();
            $table->string('source')->nullable();
            $table->integer('plancost')->nullable();
            $table->integer('totalcost')->nullable();
            $table->string('tax', 200)->nullable();
            $table->string('company', 200)->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('gst', 200)->nullable();
            $table->integer('state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
}
