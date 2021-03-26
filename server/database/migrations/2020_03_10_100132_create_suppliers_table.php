<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30);
            $table->string('code', 16)->nullable();
            $table->string('address', 128)->nullable();
            $table->integer('regency_id')->nullable()->unsigned();
            $table->integer('fax_id')->unsigned()->nullable();
            $table->integer('phone_id')->unsigned()->nullable();
            $table->integer('telephone_id')->unsigned()->nullable();
            $table->integer('currency_id')->nullable()->unsigned();
            $table->string('pic', 30)->nullable();
            $table->double('top')->nullable()->unsigned();
            $table->double('plafon')->nullable()->unsigned();
            $table->integer('supplier_category_id')->nullable()->unsigned();
            $table->string('accounting_number', 150)->nullable();
            $table->string('information', 255)->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->timestamps();

            // relations
            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('supplier_category_id')
                ->references('id')
                ->on('supplier_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('suppliers');
    }
}
