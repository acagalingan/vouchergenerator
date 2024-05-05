<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'username' => 'acagalingan',
            'first_name' => 'Austine',
            'email' => 'acagalingan@gmail.com',
            'password' => Hash::make('test1234'),
        ]);
    }
}
