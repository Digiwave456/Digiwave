<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class dmineeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'surname' => 'Admin',
                'login' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make("admin44"),
                'is_admin' => true,
            ]
        ]);
    }
}
