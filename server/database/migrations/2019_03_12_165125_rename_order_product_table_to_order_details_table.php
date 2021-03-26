<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOrderProductTableToOrderDetailsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_product', function (Blueprint $table) {
			$table->dropForeign(['order_id']);
			$table->dropForeign(['product_id']);
		});

		Schema::rename('order_product', 'order_details');

		Schema::table('order_details', function (Blueprint $table) {
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
		Schema::table('order_details', function (Blueprint $table) {
			$table->dropForeign(['order_id']);
			$table->dropForeign(['product_id']);
		});

		Schema::rename('order_details', 'order_product');

		Schema::table('order_product', function (Blueprint $table) {
			// relations:
			$table->foreign('order_id')->references('id')->on('orders')
				->onUpdate('restrict')
				->onDelete('restrict');

			$table->foreign('product_id')->references('id')->on('products')
				->onUpdate('restrict')
				->onDelete('restrict');
		});
	}
}
