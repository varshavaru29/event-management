<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class StoreCommentController extends Controller
{
    /**
     * Handle the incoming request to store a comment for an event.
     *
     * @param \Illuminate\Http\Request $request
     * @param String $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, String $id)
    {
        try {
            $event_id = (int) Crypt::decrypt($id);
            $event = Event::findOrFail($event_id);
            $event->comments()->create([
                'content' => $request->content,
                'attendeer_id' => auth()->id()
            ]);

            return back();
        } catch (\Exception $e) {
            \Log::error('Error Store Comment: ' . $e->getMessage());
            return back()->with('error', 'Unable to Store Comment. Please try again later.');
        }

    }
}
