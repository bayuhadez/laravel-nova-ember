<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlaybackVideoFilePathToSeminarProductMetasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seminar_product_metas', function (Blueprint $table) {
			//
			$table->string('playback_video_file_path')->nullable();
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
			$table->dropColumn('playback_video_file_path');
		});
	}
}
