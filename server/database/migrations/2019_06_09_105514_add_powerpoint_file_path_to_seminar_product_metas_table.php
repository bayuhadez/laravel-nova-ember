<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPowerpointFilePathToSeminarProductMetasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seminar_product_metas', function (Blueprint $table) {
			$table->string('powerpoint_file_path')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('seminar_product_metas', function (Blueprint $table) {
			$table->dropColumn('powerpoint_file_path');
		});
	}
}
