<?php

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Regency;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = Regency::count();
        if($count == 0) {
            return $this->command->alert(
                'Seed Error !! Silahkan Seed Regency Terlebih Dahulu'
            );
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        District::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $data = array_map('str_getcsv', file(base_path()."/storage/districts.csv"));
        foreach ($data as $x) {
            $prov = new District();
            $prov->id = $x[0];
            $prov->regency_id = $x[1];
            $prov->name = $x[2];
            $prov->save();
        }
    }
}
