<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Crypt;

class LikeSystemController extends Controller
{
    /**
     * Handle the incoming request to like or unlike an event.
     *
     * @param  String  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function __invoke(String $id)
    {
        try {
            $event_id = (int) Crypt::decrypt($id);
            $event = Event::findOrFail($event_id);
            $like = $event->likes()->where('attendeer_id', auth()->id())->first();
            if (!is_null($like)) {
                $like->delete();
                return response()->json(['message' => 'Event unliked successfully.'], 200);
            } else {
                $like = $event->likes()->create([
                    'attendeer_id' => auth()->id()
                ]);
                return response()->json(['message' => 'Event liked successfully.', 'like' => $like], 201);
            }
        } catch (\Exception $e) {
             \Log::error('Error like event: ' . $e->getMessage());
            return back()->with('error', 'Unable to like event at the moment. Please try again later.');
        }
    }
}
