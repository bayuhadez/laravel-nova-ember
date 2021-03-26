<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProductCategoryMembershipIdFromCompaniesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('companies', function (Blueprint $table) {
			$table->dropForeign(['product_category_membership_id']);
			$table->dropColumn('product_category_membership_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('companies', function (Blueprint $table) {
			$table->integer('product_category_membership_id')
				->unsigned()
				->nullable()
				->after('name');

			// foreign key
			$table->foreign('product_category_membership_id')
				->references('id')
				->on('product_categories')
				->onDelete('set null')
				->onUpdate('cascade');
		});
	}
}
