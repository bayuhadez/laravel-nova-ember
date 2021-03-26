<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 16)->nullable();
            $table->string('full_name', 191);
            $table->string('email', 191)->nullable();
            $table->string('pic_name', 191)->nullable();
            $table->string('company_name', 191)->nullable();
            $table->boolean('is_sub_customer')->default(false);
            $table->boolean('is_shipping_address')->default(false);
            $table->tinyInteger('bill_type');
            $table->string('address', 191)->nullable();
            $table->string('city', 191)->nullable();
            $table->string('pobox', 48)->nullable();
            $table->string('shipping_address', 191)->nullable();
            $table->string('shipping_city', 191)->nullable();
            $table->string('shipping_pobox', 48)->nullable();

            $table->integer('company_id')->unsigned();
            $table->integer('fax_id')->unsigned()->nullable();
            $table->integer('phone_id')->unsigned()->nullable();
            $table->integer('telephone_id')->unsigned();
            $table->integer('province_id')->unsigned()->nullable();
            $table->integer('shipping_province_id')->unsigned()->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
