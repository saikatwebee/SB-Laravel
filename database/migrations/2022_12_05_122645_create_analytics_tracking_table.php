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
        Schema::create('analytics_tracking', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('analytics_tracking_ibfk_1')->comment('link with customer table');
            $table->text('sb_first_typ')->nullable();
            $table->text('sb_first_src')->nullable();
            $table->text('sb_first_mdm')->nullable();
            $table->text('sb_first_cmp')->nullable();
            $table->text('sb_first_cnt')->nullable();
            $table->text('sb_first_trm')->nullable();
            $table->text('sb_current_typ')->nullable();
            $table->text('sb_current_src')->nullable();
            $table->text('sb_current_mdm')->nullable();
            $table->text('sb_current_cmp')->nullable();
            $table->text('sb_current_cnt')->nullable();
            $table->text('sb_current_trm')->nullable();
            $table->text('sb_first_add_fd')->nullable();
            $table->text('sb_first_add_ep')->nullable();
            $table->text('sb_first_add_rf')->nullable();
            $table->text('sb_current_add_fd')->nullable();
            $table->text('sb_current_add_ep')->nullable();
            $table->text('sb_current_add_rf')->nullable();
            $table->text('sb_session_pgs')->nullable();
            $table->text('sb_session_cpg')->nullable();
            $table->text('sb_udata_vst')->nullable();
            $table->text('sb_udata_uip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analytics_tracking');
    }
};
