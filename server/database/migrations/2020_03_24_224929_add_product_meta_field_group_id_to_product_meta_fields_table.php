<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductMetaFieldGroupIdToProductMetaFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_meta_fields', function (Blueprint $table) {
            $table->integer('product_meta_field_group_id')->unsigned()->nullable();

            $table->foreign('product_meta_field_group_id')
                ->references('id')
                ->on('product_meta_field_groups')
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
        Schema::table('product_meta_fields', function (Blueprint $table) {
            $table->dropForeign('product_meta_fields_product_meta_field_group_id_foreign');
            $table->dropColumn('product_meta_field_group_id');
        });
    }
}
