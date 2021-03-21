<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();

        // テスト用ユーザー登録
        factory(User::class)
            ->create([
                'name' => 'テスト 太郎',
                'email' => 'test@email.com',
            ]);

        factory(User::class, 10)->create();
    }
}
