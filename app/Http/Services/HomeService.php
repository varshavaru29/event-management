<?php
namespace App\Http\Services;

use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class HomeService
{
    protected $eventObj;

    public function __construct(Event $eventObj)
    {
        $this->eventObj = $eventObj;
    }

    /**
     * Search and filter events with caching.
     *
     * @param  string|null  $query
     * @param  string|null  $date
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws \Exception
     */

    public function searchEvents($query = null, $date = null)
    {
        // Define cache key
        $cacheKey = 'events_search_' . md5($query . $date);

        // Check if data exists in cache, if so, return it
        {
            // Define cache key
            $cacheKey = 'events_search_' . md5($query . $date);

            try {
                // Check if data exists in cache, if so, return it
                return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($query, $date) {
                    // Start the query builder
                    $events = $this->eventObj::query();

                    // Apply filters based on query
                    if ($query) {
                        $events->where(function($q) use ($query) {
                            $q->where('title', 'like', "%{$query}%")
                              ->orWhere('description', 'like', "%{$query}%")
                              ->orWhere('location', 'like', "%{$query}%");
                        });
                    }

                    // Apply date filter if provided
                    if ($date) {
                        $events->where(function ($q) use ($date) {
                            $q->whereDate('start_date', '=', $date)
                              ->orWhereDate('end_date', '=', $date);
                        });
                    }

                    // Return the paginated results
                    return $events->paginate(10);
                });
            } catch (\Exception $e) {
                // Log the error with relevant details
                \Log::error('Error occurred while searching events', [
                    'query' => $query,
                    'date' => $date,
                    'exception' => $e->getMessage(),
                ]);

                // Optionally, rethrow or return a default empty result set
                throw new \Exception('Unable to search events at the moment. Please try again later.');
            }
        }
    }

    /**
     * Fetch an event by its encrypted slug and handle likes.
     *
     * @param  string  $id
     * @return array
     */
    public function getEventDetails(string $id): array
    {
        try {
            // Decrypt the event slug to get the original ID
            $eventId = Crypt::decrypt($id);

            // Retrieve the event using the decrypted ID
            $event = $this->eventObj::where('slug', $eventId)->firstOrFail();

            // Check if the current authenticated user has liked this event
            $like = $event->likes()->where('attendeer_id', auth()->id())->first();

            $tickets = $event->tickets()->select('id', 'event_id', 'ticket_type_id', 'price', 'quantity')->with('ticketType:id,name')->get();
            // Return event and like status
            return ['event' => $event, 'like' => $like, 'tickets' => $tickets];
        } catch (ModelNotFoundException $e) {
            // Log error if the event is not found
            \Log::error('Event not found for like system', [
                'event_slug' => $id,
                'user_id' => auth()->id(),
                'exception' => $e->getMessage(),
            ]);

            // Throw a custom exception or handle error
            throw new \Exception('Event not found.');
        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Error showing event details', [
                'event_slug' => $id,
                'user_id' => auth()->id(),
                'exception' => $e->getMessage(),
            ]);

            // Rethrow or handle the error
            throw new \Exception('An error occurred while retrieving the event. Please try again later.');
        }
    }
}
