<?php

namespace Database\Seeders;

use App\Models\{Role, User};
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
        Role::insert([
            ['name' => 'organizer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'attendee', 'created_at' => now(), 'updated_at' => now()],
        ]);
        User::factory(10)->create()->each(function ($user){
            $role = Role::inRandomOrder()->first();
            $user->roles()->attach($role->id);
        });
    }
}
