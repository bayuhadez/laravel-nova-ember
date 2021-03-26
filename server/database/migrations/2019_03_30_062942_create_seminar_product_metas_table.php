<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeminarProductMetasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seminar_product_metas', function (Blueprint $table) {
			$table->increments('id');
			$table->datetime('start_time')->nullable();
			$table->datetime('end_time')->nullable();
			$table->boolean('is_session_in_progress')->default(false);
			$table->integer('product_id')->unsigned();
			$table->string('stream_key')->nullable();
			$table->timestamps();

			$table->foreign('product_id')
				->references('id')
				->on('products')
				->onUpdate('cascade')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('seminar_product_metas');
	}
}
