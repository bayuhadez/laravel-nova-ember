<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',30);
            $table->string('description')->nullable();
            $table->integer('service_category_id')->nullable()->unsigned();
            $table->timestamps();

            // relation to service category
            $table->foreign('service_category_id')
                ->references('id')
                ->on('service_categories')
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
        Schema::dropIfExists('services');
    }
}
