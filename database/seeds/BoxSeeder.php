<?php

use App\Box;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Box::create([
            'title' => 'Mr Robot',
            'description' => $faker->text,
            'difficulty' => 6,
            'logo' => 'mr_robot.png',
            'author' => 'amodsachintha'
        ]);

        Box::create([
            'title' => 'UnForGiven',
            'description' => $faker->text,
            'difficulty' => 8,
            'logo' => 'unforgiven.png',
            'author' => 'shapManasick'
        ]);
    }
}
