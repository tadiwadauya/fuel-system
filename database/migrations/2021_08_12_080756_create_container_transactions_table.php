<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->string('container');
            $table->string('batch');
            $table->double('concapacity');
            $table->double('conrate');
            $table->double('b_before');
            $table->double('b_after');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_transactions');
    }
}
