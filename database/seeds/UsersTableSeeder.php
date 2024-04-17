<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期ユーザーを1つ登録
        User::create([
            'over_name' => 'テスト',
            'under_name' => 'ユーザー',
            'over_name_kana' => 'テスト',
            'under_name_kana' => 'ユーザー',
            'mail_address' => 'test@com',
            'sex' => '男性',
            'old_year' => '1980-01-01',
            'role' => '教師(数学)',
            'password' => bcrypt('password'),
        ]);
    }
}
