<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_issues', function (Blueprint $table) {
            $table->id();
            $table->string('trans_code')->unique();
            $table->string('employee');
            $table->string('voucher')->unique();
            $table->string('narration', 100);
            $table->string('ftype');
            $table->string('meter_start');
            $table->string('meter_stop');
            $table->double('quantity',12,2);
            $table->string('reg_num');
            $table->string('done_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_issues');
    }
}
