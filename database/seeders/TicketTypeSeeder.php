<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketType::insert([
            ['name' => 'General', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'VIP', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Premium', 'created_at' => now(), 'updated_at' => now()]
        ]);
        // \App\Models\Event::all()->each(function ($event) {
        //     TicketType::factory()->count(3)->create([
        //         'event_id' => $event->id,
        //     ]);
        // });
    }
}
