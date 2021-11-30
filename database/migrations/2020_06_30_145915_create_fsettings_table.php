<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFsettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fsettings', function (Blueprint $table) {
            $table->id();
            $table->boolean('petrol_available')->default(true);
            $table->boolean('diesel_available')->default(true);
            $table->double('petrol_price');
            $table->double('diesel_price');
            $table->double('oil_price')->nullable();
            $table->string('pay_method');
            $table->string('last_changed_by');
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
        Schema::dropIfExists('fsettings');
    }
}
