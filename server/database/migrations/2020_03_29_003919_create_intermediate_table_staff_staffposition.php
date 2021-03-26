<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntermediateTableStaffStaffposition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_staffposition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->integer('staff_position_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('staff_id')
                ->references('id')
                ->on('staffs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('staff_position_id')
                ->references('id')
                ->on('staff_positions')
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
        Schema::dropIfExists('staff_staffposition');
    }
}
