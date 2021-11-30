<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quote_num')->unique();
            $table->string('client');
            $table->string('email')->nullable();
            $table->string('email_cc')->nullable();
            $table->double('price',12, 2);
            $table->double('quantity',12, 2);
            $table->string('currency',5);
            $table->double('amount',12, 2);
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
