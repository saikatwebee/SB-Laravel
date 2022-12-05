<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_request', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('cid')->nullable()->index('payment_request_ibfk_1')->comment('link with customer table');
            $table->integer('pid')->nullable()->index('payment_request_ibfk_2')->comment('link with problem table');
            $table->string('amount', 500)->nullable()->comment('amount requested by customer');
            $table->string('amount_paid', 455)->nullable()->comment('amount paid to customer');
            $table->integer('is_gst')->nullable()->comment('0- no gst,
1- with gst');
            $table->string('gst', 455)->nullable();
            $table->string('doc_type', 1000)->nullable();
            $table->string('reference', 455)->nullable();
            $table->string('payment_doc', 455)->nullable();
            $table->string('account_nm', 500)->nullable();
            $table->string('account_no', 500)->nullable();
            $table->string('ifsc', 455)->nullable();
            $table->integer('status')->default(0)->comment('0- payment request raised,
1- approved by project manager,
2- Tds submitted by accounts,
3- disapproved by project manager,
4- payment completed');
            $table->string('tds', 455)->nullable();
            $table->string('gst_amount', 125)->nullable();
            $table->string('comment', 1000)->nullable();
            $table->string('transaction_no', 500)->nullable();
            $table->timestamp('date_added')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_request');
    }
}
