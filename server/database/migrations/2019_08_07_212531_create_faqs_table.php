<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faqs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index();
			$table->integer('created_by')->unsigned()->index();
			$table->string('question', 256)->index();
			$table->text('answer')->nullable();
			$table->smallInteger('sorting_number')->nullable();
			$table->timestamps();
			$table->softDeletes();

			// relations
			$table->foreign('created_by')
				->references('id')
				->on('users')
				->onDelete('restrict')
				->onUpdate('restrict');

			$table->foreign('company_id')
				->references('id')
				->on('companies')
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
		Schema::dropIfExists('faqs');
	}
}
