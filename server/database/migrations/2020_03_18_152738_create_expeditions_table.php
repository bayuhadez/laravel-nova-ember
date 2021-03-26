<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpeditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expeditions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 16)->nullable();
            $table->string('name', 30);
            $table->string('address', 128)->nullable();
            $table->integer('regency_id')->nullable()->unsigned();
            $table->integer('mobilephone_id')->unsigned()->nullable();
            $table->integer('telephone_id')->unsigned()->nullable();
            $table->integer('fax_id')->unsigned()->nullable();
            $table->integer('currency_id')->unsigned()->nullable();
            $table->string('pic', 30)->nullable();
            $table->integer('expedition_category_id')->unsigned()->nullable();
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

            $table->foreign('expedition_category_id')
                ->references('id')
                ->on('expedition_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
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
            
            $table->foreign('fax_id')
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
        Schema::dropIfExists('expeditions');
    }
}
