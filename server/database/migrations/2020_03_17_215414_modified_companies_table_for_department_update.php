<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifiedCompaniesTableForDepartmentUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('value_added_tax_type')->after('name')->nullable();
            $table->string('value_added_tax_number')->after('name')->nullable();
            $table->string('phone')->after('name')->nullable();
            $table->string('address')->after('name')->nullable();
            $table->string('code')->after('name')->nullable()->unique();
            $table->boolean('division')->after('name')->default(0);
            $table->integer('company_id')->unsigned()->after('id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
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
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->dropColumn('code');
            $table->dropColumn('division');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('value_added_tax_number');
            $table->dropColumn('value_added_tax_type');
        });
    }
}
