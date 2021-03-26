<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLicensesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('licenses');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('licenses', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('name', 191);
			$table->string('number', 191);
			$table->string('expiry_date', 10);
			$table->string('photo')->nullable;
			$table->tinyInteger('status')->default(0);
			$table->timestamps();
			$table->softDeletes();

			// relations
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('restrict')
				->onUpdate('restrict');
		});
	}
}
