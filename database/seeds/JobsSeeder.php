<?php

use Illuminate\Database\Seeder;
use App\Models\Job;

class JobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Job::query()->truncate();
    }
}
