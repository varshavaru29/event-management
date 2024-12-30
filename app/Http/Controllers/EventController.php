<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Country;
use App\Models\Event;
use App\Models\Tag;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::where('organizer_id', Auth::user()->id)->paginate(10);
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ticketTypes = TicketType::all();
        return view('events.create',compact('ticketTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEventRequest $request): RedirectResponse
    {
        //dd($request->all());
        try {
            if (!$request->hasFile('image')) {
                return back()->with('error', 'Image file is required.');
            }
            // Ensure the slug is unique
            $slug = Str::slug($request->title);
            $slugExists = Event::where('slug', $slug)->exists();

            // If slug exists, modify it by appending a timestamp
            if ($slugExists) {
                $slug = $slug . '-' . time(); // Example: "event-title-1634676547"
            }

            $data = $request->validated();
            $data['image'] = Storage::putFile('events', $request->file('image'));
            $data['organizer_id'] = auth()->id();
            $data['slug'] = Str::slug($request->title);

            $event = Event::create($data);

            if ($request->has('ticket_types')) {
                foreach ($request->ticket_types as $ticketData) {
                    // Prepare ticket data
                    $ticketData['ticket_type_id'] = $ticketData['id'];
                    $ticketData['event_id'] = $event->id; // Ensure the ticket is linked to the event
                    // Ensure the ticket data matches your validation rules
                    $ticketData['price'] = $ticketData['price'] ?? 0;
                    $ticketData['quantity'] = $ticketData['quantity'] ?? 1;
                    $ticketData['sale_starts_at'] = $ticketData['sale_starts_at'];
                    $ticketData['sale_ends_at'] = $ticketData['sale_ends_at'];

                    // Create the ticket
                    $event->tickets()->create($ticketData);
                }
            }
            return to_route('events.index')->with('success', 'Event created successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Event creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Return back with an error message
            return back()->with('error', 'An error occurred while creating the event. Please try again.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        $tickets = $event->tickets()->select('id', 'event_id', 'ticket_type_id', 'price', 'quantity', 'sale_starts_at', 'sale_ends_at')->with('ticketType:id,name')->get();
        return view('events.edit', compact('event', "tickets"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                if ($event->image && Storage::exists($event->image)) {
                    Storage::delete($event->image); // Delete the old image
                }
                $data['image'] = Storage::putFile('events', $request->file('image'));
            }

            $slug = Str::slug($request->title);
            if (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $slug . '-' . time(); // If slug exists, append a timestamp to make it unique
            }
            $data['slug'] = $slug;

            // Update the event data
            $event->update($data);
            if ($request->has('ticket_types')) {
                foreach ($request->ticket_types as $ticketData) {
                    // Ensure the ticket data is valid
                    $ticketData['price'] = $ticketData['price'] ?? 0;
                    $ticketData['quantity'] = $ticketData['quantity'] ?? 1;
                    $ticketData['sale_starts_at'] = $ticketData['sale_starts_at'];
                    $ticketData['sale_ends_at'] = $ticketData['sale_ends_at'];
                    $ticketData['ticket_type_id'] = $ticketData['ticket_type_id'];
                    // Ensure the ticket is linked to the event
                    $ticketData['event_id'] = $event->id;

                    // If the ticket ID is present, update the ticket; otherwise, create a new one
                    if (isset($ticketData['id'])) {
                        $ticket = $event->tickets()->find($ticketData['id']);
                        if ($ticket) {
                            $ticket->update($ticketData);
                        } else {
                            // If ticket doesn't exist, create a new one
                            $event->tickets()->create($ticketData);
                        }
                    } else {
                        // Create a new ticket if no ID is provided
                        $event->tickets()->create($ticketData);
                    }
                }
            }
            DB::commit();
        return to_route('events.index')->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            // If an error occurs, roll back the transaction
            DB::rollBack();

            // Log the error and return with an error message
            \Log::error('Error updating event: ' . $e->getMessage());
            return back()->with('error', 'There was an error updating the event. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        Storage::delete($event->image);
        $event->delete();
        return to_route('events.index');
    }
}
