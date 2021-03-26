<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('company_id')->unsigned()->index();
			$table->string('name', 256)->index();
			$table->decimal('stock');
			$table->decimal('amount', 8, 2);
            $table->timestamps();

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
        Schema::dropIfExists('vouchers');
    }
}
