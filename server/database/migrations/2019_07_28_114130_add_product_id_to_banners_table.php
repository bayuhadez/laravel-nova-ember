<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductIdToBannersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('banners', function (Blueprint $table) {
			$table
				->integer('product_id')
				->after('company_id')
				->unsigned()
				->nullable();

			$table->foreign('product_id')
				->references('id')->on('products')
				->onDelete('set null')
				->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('banners', function (Blueprint $table) {
			$table->dropForeign(['product_id']);
			$table->dropColumn('product_id');
		});
	}
}
