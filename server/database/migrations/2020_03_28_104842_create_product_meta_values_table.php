<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMetaValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_meta_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->integer('product_meta_field_id')->unsigned();
            $table->timestamps();

            $table->foreign('product_meta_field_id')
                ->references('id')
                ->on('product_meta_fields')
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
        Schema::dropIfExists('product_meta_values');
    }
}
