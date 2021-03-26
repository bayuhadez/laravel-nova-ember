<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegencyIdAndShippingRegencyIdToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table
                ->unsignedInteger('regency_id')
                ->nullable()
                ->after('shipping_province_id');

            $table
                ->unsignedInteger('shipping_regency_id')
                ->nullable()
                ->after('regency_id');

            $table
                ->foreign('regency_id')
                ->references('id')->on('regencies')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table
                ->foreign('shipping_regency_id')
                ->references('id')->on('regencies')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['shipping_regency_id']);
            $table->dropForeign(['regency_id']);
            $table->dropColumn(['regency_id', 'shipping_regency_id']);
        });
    }
}
