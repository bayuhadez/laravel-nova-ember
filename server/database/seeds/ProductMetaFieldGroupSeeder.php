<?php

use Illuminate\Database\Seeder;
use App\Models\ProductMetaFieldGroup;
use Carbon\carbon;

class ProductMetaFieldGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        ProductMetaFieldGroup::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        DB::table('product_meta_field_groups')->insert([
            'name' => 'Varian',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
