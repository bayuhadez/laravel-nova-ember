<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpeakerUserIdToSeminarProductMetas extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seminar_product_metas', function (Blueprint $table) {
			$table->integer('speaker_user_id')->unsigned()->nullable();
			$table->foreign('speaker_user_id')
				->references('id')
				->on('users')
				->onDelete('restrict')
				->onUpdate('restrict');
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
			$table->dropForeign('seminar_product_metas_speaker_user_id_foreign');
			$table->dropColumn('speaker_user_id');
		});
	}
}
