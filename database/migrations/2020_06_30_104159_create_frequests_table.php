<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frequests', function (Blueprint $table) {
            $table->id();
            $table->string('request_type');
            $table->string('employee');
            $table->string('quantity', 5);
            $table->string('ftype');
            $table->boolean('status')->default(false);
            $table->string('approved_by')->nullable();
            $table->string('approved_when')->nullable();
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
        Schema::dropIfExists('frequests');
    }
}
