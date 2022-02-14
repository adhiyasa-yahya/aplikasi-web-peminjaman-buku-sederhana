<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            [
                'no_anggota' => \Str::random(10),
                'name' => 'nugroho',
                'email' => 'mail@magang.com',
                'role' => 'admin',
                'password' => \Hash::make('p4ssword'),
            ],
        ]);
    }
}
