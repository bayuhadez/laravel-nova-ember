<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewTableSeminarProductSponsors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seminar_product_sponsors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sponsor_image_path', 191)->nullable();
            $table->string('sponsor_name', 191);
            $table->integer('seminar_product_meta_id')->unsigned();
            $table->timestamps();

            $table->foreign('seminar_product_meta_id')
				->references('id')
				->on('seminar_product_metas')
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
        Schema::dropIfExists('seminar_product_sponsors');
    }
}
