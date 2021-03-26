<?php

use Illuminate\Database\Seeder;

class StarterPackSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// 1. create default company
		$company = factory(App\Models\Company::class)->create([
			'name' => 'RC Corp.',
		]);

		// 2. create product category `Seminar`
		$productCategory = factory(App\Models\ProductCategory::class)->create([
			'name' => 'Seminar',
			'company_id' => $company->id,
		]);

		// create student
		$password = Hash::make('password');
		$student = factory(App\Models\User::class)->create([
			'name' => 'Student',
			'email' => 'student@app.com',
			'password' => $password,
		]);

		// 3. assign all user to created company
		$users = App\Models\User::get();
		foreach ($users as $user) {
			$user->companies()->attach($company->id);
		}

	}
}
