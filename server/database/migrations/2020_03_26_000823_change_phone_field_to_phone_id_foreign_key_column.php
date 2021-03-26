<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePhoneFieldToPhoneIdForeignKeyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->integer('phone_id')->unsigned()->after('id')->nullable();

            $table->foreign('phone_id')
                ->references('id')
                ->on('phones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['phone_id']);
            $table->dropColumn('phone_id');
            $table->string('phone')->after('address')->nullable();
        });
    }
}
