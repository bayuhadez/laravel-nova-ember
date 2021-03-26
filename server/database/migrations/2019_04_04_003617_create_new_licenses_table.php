<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewLicensesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('licenses', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('name', 191);
			$table->string('number', 48);
			$table->date('expiry_date');
			$table->string('photo')->nullable();
			$table->string('comment', 1024)->nullable();
			$table->integer('approver_id')->unsigned()->nullable();
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

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('licenses');
	}
}
