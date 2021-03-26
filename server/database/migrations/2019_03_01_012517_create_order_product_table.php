<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_product', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('order_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->decimal('price', 10, 2)->default(0);
			$table->integer('quantity')->unsigned()->default(0);
			$table->string('name', 191)->nullable();
			$table->string('category', 191)->nullable();
			$table->string('brand', 191)->nullable();
			$table->string('merchant_name', 191)->nullable();
			$table->timestamps();

			// relations:
			$table->foreign('order_id')->references('id')->on('orders')
				->onUpdate('restrict')
				->onDelete('restrict');

			$table->foreign('product_id')->references('id')->on('products')
				->onUpdate('restrict')
				->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('order_product');
	}
}
