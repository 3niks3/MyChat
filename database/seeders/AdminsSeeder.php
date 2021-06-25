<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        \App\Models\Admins::create([
            'name' => 'Admin',
            'surname' => 'Testing',
            'username' => 'Tester Admin',
            'email' => 'admin@test.te',
            'password' => Hash::make('password'),
        ]);
    }
}
