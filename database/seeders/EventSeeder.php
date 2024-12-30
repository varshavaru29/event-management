<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Event, User, Ticket};


class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizers = User::whereHas('roles', function ($query) {
            $query->where('name', 'organizer');
        })->get();
        $organizers->each(function (){
            Event::factory(10)->create()->each(function ($event){
                \App\Models\TicketType::all()->each(function ($ticketType)  use ($event) {
                    Ticket::factory()->count(1)->create([
                        'event_id' => $event->id,
                        'ticket_type_id' => $ticketType->id,
                    ]);
                });
            });
        });
    }
}
