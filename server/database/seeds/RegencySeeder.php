<?php

use Illuminate\Database\Seeder;
use App\Models\Regency;
use App\Models\Province;

class RegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = Province::count();
        if($count == 0) {
            return $this->command->alert(
                'Seed Error !! Silahkan Seed Province Terlebih Dahulu'
            );
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Regency::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $data = array_map('str_getcsv', file(base_path()."/storage/regencies.csv"));
        foreach ($data as $x) {
            $prov = new Regency();
            $prov->id = $x[0];
            $prov->province_id = $x[1];
            $prov->name = $x[2];
            $prov->save();
        }
    }
}
