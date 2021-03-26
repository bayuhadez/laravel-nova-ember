<?php

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $data = array_map('str_getcsv', file(base_path()."/storage/provinces.csv"));
        foreach ($data as $x) {
            $prov = new Province();
            $prov->id = $x[0];
            $prov->name = $x[1];
            $prov->save();
        }
        
    }
}
