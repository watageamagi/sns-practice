<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::query()->truncate();

        if(env('APP_ENV') === 'local') {
            $pass = Hash::make('secret');
        } else {
            $pass = Hash::make('frz191sh()');
        }

        factory(Admin::class, 1)
            ->create([
               'name' => 'admin',
               'password' => $pass
            ]);

        factory(Admin::class, 1)
            ->create([
                'name' => 'developer',
                'password' => $pass
            ]);
    }
}
