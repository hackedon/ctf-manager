<?php

use App\Level;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Factory::create();
        Level::create([
            'box_id' => 1,
            'flag_no' => 1,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 2,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 3,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 4,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 5,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 6,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 7,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 8,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 9,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 1,
            'flag_no' => 10,
            'flag' => $faker->md5,
        ]);


        Level::create([
            'box_id' => 2,
            'flag_no' => 1,
            'flag' => $faker->md5,
        ]);
        Level::create([
            'box_id' => 2,
            'flag_no' => 2,
            'flag' => $faker->md5,
        ]);
    }
}
