<?php

namespace Database\Seeders;

use App\Models\{Ticket, TicketType, Event};
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Create 10 tickets for each ticket type
        // TicketType::all()->each(function ($ticketType) {
        //     Event::all()->each(function ($event) use ($ticketType) {
        //         Ticket::factory()->count(1)->create([
        //             'event_id' => $event->id,
        //             'ticket_type_id' => $ticketType->id,
        //         ]);
        //     });
        // });
    }
}
