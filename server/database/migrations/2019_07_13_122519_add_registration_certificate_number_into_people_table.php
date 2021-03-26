<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistrationCertificateNumberIntoPeopleTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('people', function (Blueprint $table) {
			$table
				->string('registration_certificate_number', 32)
				->nullable()
				->after('phone');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('people', function (Blueprint $table) {
			$table->dropColumn('registration_certificate_number');
		});
	}
}
