<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;

class ChangeSizeCodeAndPoboxInCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('code', 191)->change();
            $table->string('pobox', 191)->change();
            $table->string('shipping_pobox', 191)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('code', 16)->change();
            });
        } catch (QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
        }

        try {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('pobox', 48)->change();
            });
        } catch (QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
        }

        try {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('shipping_pobox', 48)->change();
            });
        } catch (QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
        }
    }
}
