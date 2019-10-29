<?php

use App\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Config::create([
            'key' => 'allowReportUploads',
            'value' => '1',
        ]);

        Config::create([
            'key' => 'allowFlagSubmission',
            'value' => '1',
        ]);
    }
}
