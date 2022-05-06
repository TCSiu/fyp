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
        Schema::create('access_token', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('user_id');
            $table->string('access_token', 80)->unique();
            $table->dateTime('expiry_date');
            $table->string('purpose');
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('access_token');
    }
};
