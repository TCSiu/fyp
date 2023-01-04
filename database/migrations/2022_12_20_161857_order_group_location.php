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
        Schema::create('order_group_location', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_group_id');
            $table->string('current_location_lat');
            $table->string('current_location_lng');
            $table->boolean('is_expiry');
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
        Schema::dropIfExists('order_group_location');
    }
};
