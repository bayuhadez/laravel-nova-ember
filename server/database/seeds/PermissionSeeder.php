<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // define permissions
        // example will be generated as product.read-price
        $permissions = [
            'product' => [
                'bread', // shorthand of 5 basic abilities
                'read-price',
            ],
            'user' => [
                'bread'
            ]
        ];

        // generate the permissions
        foreach ($permissions as $entity => $abilities) {

            foreach ($abilities as $ability) {

                if ($ability === 'bread') {
                    Permission::firstOrCreate(['name' => $entity.'.browse']);
                    Permission::firstOrCreate(['name' => $entity.'.read']);
                    Permission::firstOrCreate(['name' => $entity.'.edit']);
                    Permission::firstOrCreate(['name' => $entity.'.add']);
                    Permission::firstOrCreate(['name' => $entity.'.delete']);
                } else {
                    Permission::firstOrCreate([
                        'name' => $entity.'.'.$ability,
                    ]);
                }

            }

        }
    }
}
