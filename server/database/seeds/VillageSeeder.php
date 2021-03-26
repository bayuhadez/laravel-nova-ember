<?php

use Illuminate\Database\Seeder;
use App\Models\Village;
use App\Models\District;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = District::count();
        if($count == 0) {
            return $this->command->alert(
                'Seed Error !! Silahkan Seed District Terlebih Dahulu'
            );
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Village::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $data = array_map('str_getcsv',file(base_path()."/storage/villages.csv"));
        foreach($data as $x){
            $prov = new Village();
            $prov->id = $x[0];
            $prov->district_id = $x[1];
            $prov->name = $x[2];
            $prov->save();
        }
    }
}
