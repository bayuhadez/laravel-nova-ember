<?php

use Illuminate\Database\Seeder;
use App\Models\ProductMetaFieldGroup;
use Carbon\Carbon;

class ProductMetaFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = ProductMetaFieldGroup::count();
        if($count == 0) {
            return $this->command->alert(
                'Seed Error !! Silahkan Seed ProductMetaFieldGroup Terlebih Dahulu'
            );
        }

        DB::table('product_meta_fields')->delete();

        $data = [
            ['Ukuran', '1'],
            ['Ring',' 1'],
            ['Pattern', '1'],
            ['PCD', '1'],
        ];

        foreach ($data as $x)
        {
            DB::table('product_meta_fields')->insert([
                'label' => $x[0],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'product_meta_field_group_id' => $x[1],
            ]);
        }
    }
}
