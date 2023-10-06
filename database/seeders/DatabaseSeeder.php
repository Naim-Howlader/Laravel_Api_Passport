<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $user = [
            ['name'=>"Adnan Islam",'email'=>'adnan1234@gmail.com','password'=>'1234'],
            ['name'=>"Jikrul Islam",'email'=>'jikrul1234@gmail.com','password'=>'1234'],
            ['name'=>"Robiul Islam",'email'=>'robiul1234@gmail.com','password'=>'1234'],
        ];
        User::insert($user);
    }
}
