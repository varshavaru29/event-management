<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AttendingEventController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        try {
            $orders = Order::with([
                'user:id,name',
                'ticket:id,event_id,ticket_type_id',
                'ticket.event:id,title,slug',
                'ticket.ticketType:id,name'
            ])->whereHas('ticket.event', function ($query) {
                $query->where('organizer_id', Auth::user()->id);
            })->get();


            return view('events.attendingEvents', compact('orders'));
        } catch (\Exception $e) {

            // Log the error for debugging
            \Log::error('Error fetching attended events: ' . $e->getMessage());

            // Redirect with an error message
            return redirect()->route('events.index')->with('error', 'An error occurred while fetching your attended events.');
        }
    }
}
