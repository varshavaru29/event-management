<?php

namespace App\Http\Controllers;

use App\Http\Services\HomeService;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class WelcomeController extends Controller
{
    protected $homeService;

    // Injecting the SearchService
    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }
     /**
     * Display a listing of events with pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $events = $this->homeService->searchEvents();
            return view('welcome', compact('events'));
        } catch (\Exception $e) {
            \Log::error('Error fetching events: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch events at the moment. Please try again later.');
        }
    }

    /**
     * Search and filter events based on query and date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function searchEvent(Request $request)
    {
        $query = $request->input('query') ?? '';
        $date = $request->input('date') ?? '';
        try {
            // Get events based on search and filter using the SearchService
            $events = $this->homeService->searchEvents($query, $date);

            // If the request is AJAX, return only the events list partial
            if ($request->ajax()) {
                return view('events.event-list', compact('events'))->render();
            }

            // Return the full page with events
            return view('events.index', compact('events'));

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error searching events in EventController', [
                'query' => $request->input('query'),
                'date' => $request->input('date'),
                'exception' => $e->getMessage(),
            ]);

            // If it's an AJAX request, return a JSON error message
            if ($request->ajax()) {
                return response()->json(['error' => 'Unable to search events. Please try again later.'], 500);
            }

            // Return a view with an error message
            return back()->with('error', 'Unable to search events. Please try again later.');
        }
    }

    /**
     * Handle the incoming request to show event details and the like status.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function eventShow(string $id)
    {
        try {
            $result = $this->homeService->getEventDetails($id);
            // Return the view with the event and like status
            return view('eventsShow', [
                'event' => $result['event'],
                'like' => $result['like'],
                'tickets' => $result['tickets'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error displaying event details', [
                'event_slug' => $id,
                'exception' => $e->getMessage(),
            ]);
           return back()->with('error', 'An error occurred while retrieving the event. Please try again later.');
        }
    }

}
