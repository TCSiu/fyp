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
		Schema::create('order', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->uuid('uuid');
			$table->integer('company_id')->default(0);
			$table->string('sex')->nullable();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('phone_number')->nullable();
			$table->string('delivery1')->nullable();
			$table->string('delivery2')->nullable();
			$table->double('lat')->default(0);
			$table->double('lng')->default(0);
			$table->date('delivery_date')->nullable();
			$table->string('product_name_and_number')->nullable();
			$table->boolean('is_in_group')->nullable();
			$table->boolean('is_delete')->nullable();
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
		Schema::dropIfExists('order');
	}
};
