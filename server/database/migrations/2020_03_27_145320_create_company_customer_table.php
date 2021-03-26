<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->double('credit_limit', 15, 2)->nullable();
            $table->double('discount', 6, 2)->nullable();
            $table->integer('term_of_payment')->nullable();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('seller_id')->nullable();
            $table->timestamps();

            // foreign to companies
            $table
                ->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // foreign to customers
            $table
                ->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // foreign to customers
            $table
                ->foreign('seller_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('company_customer');
    }
}
