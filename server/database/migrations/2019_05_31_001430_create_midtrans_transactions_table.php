<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMidtransTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('midtrans_transactions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->string('order_number', 64);
			$table->string('payment_type', 64);
			$table->string('bank', 64)->nullable();
			$table->string('status_code', 3)->nullable();
			$table->json('raw_json')->nullable();
			$table->timestamps();
			$table->softDeletes();

			// relations:
			$table->foreign('order_id')
				->references('id')->on('orders')
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
		Schema::dropIfExists('midtrans_transactions');
	}
}
