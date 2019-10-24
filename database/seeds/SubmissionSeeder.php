<?php

use App\Submission;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Submission::create([
            'user_id' => 2,
            'level_id' => 1
        ]);
        Submission::create([
            'user_id' => 2,
            'level_id' => 2
        ]);
        Submission::create([
            'user_id' => 2,
            'level_id' => 3
        ]);
        Submission::create([
            'user_id' => 2,
            'level_id' => 4
        ]);
        Submission::create([
            'user_id' => 2,
            'level_id' => 5
        ]);

        Submission::create([
            'user_id' => 3,
            'level_id' => 1
        ]);
        Submission::create([
            'user_id' => 3,
            'level_id' => 2
        ]);
        Submission::create([
            'user_id' => 3,
            'level_id' => 3
        ]);


        Submission::create([
            'user_id' => 2,
            'level_id' => 11
        ]);
    }
}
