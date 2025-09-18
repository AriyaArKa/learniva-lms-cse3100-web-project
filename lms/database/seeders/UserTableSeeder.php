<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([

            //admin
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('111'),
                'role' => 'admin',
                'status' => '1',

            ],
            //instructor
            [
                'name' => 'Instructor',
                'username' => 'instructor',
                'email' => 'instructor@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('111'),
                'role' => 'instructor',
                'status' => '1',
            ],
            //user
            [
                'name' => 'User',
                'username' => 'user',
                'email' => 'user@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('111'),
                'role' => 'user',
                'status' => '1',
            ],
        ]);
    }
}
