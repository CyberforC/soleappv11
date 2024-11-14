<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Francklin Castro';
        $user->email = 'franroca@gmail.com';
        $user->password = bcrypt('admin');
        $user->email_verified_at = '2022-05-09';
        $user->remember_token = 'nNFhKn76Vic';
        $user->save();
    }
}
