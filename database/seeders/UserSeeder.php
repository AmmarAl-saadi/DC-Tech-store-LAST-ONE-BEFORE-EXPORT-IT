<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@mail.com',
                'password' => Hash::make('123456'),
                'verified' => 1,
            ]
        ];


        if(!User::first()){
            foreach ($items as $i) {
                User::create($i);
            }
        }



    }
}
