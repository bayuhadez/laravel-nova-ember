<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyMobilephoneTelephoneFaxInSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->renameColumn('phone_id', 'mobilephone_id');

            $table->foreign('fax_id')
                ->references('id')
                ->on('phones')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('mobilephone_id')
                ->references('id')
                ->on('phones')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('telephone_id')
                ->references('id')
                ->on('phones')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->renameColumn('mobilephone_id', 'phone_id');
            $table->dropForeign(['mobilephone_id']);
            $table->dropForeign(['fax_id']);
            $table->dropForeign(['telephone_id']);
        });
    }
}
