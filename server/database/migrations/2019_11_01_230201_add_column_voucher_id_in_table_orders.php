<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVoucherIdInTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
			$table->unsignedInteger('voucher_id')
				->after('company_id')
				->nullable();

			$table->foreign('voucher_id')
				->references('id')
				->on('vouchers')
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
			$table->dropColumn('voucher_id');
        });
    }
}
