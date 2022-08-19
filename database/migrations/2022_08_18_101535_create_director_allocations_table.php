<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectorAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('director_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('paynumber');
            $table->string('allocation');
            $table->double('quantity');
            $table->double('balance');
            $table->double('used')->default(0);
            $table->double('extra')->default(0);
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
        Schema::dropIfExists('director_allocations');
    }
}
