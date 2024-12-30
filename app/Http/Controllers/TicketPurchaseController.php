<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketPurchaseRequest;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class TicketPurchaseController extends Controller
{
     /**
     * Handle ticket purchase.
     *
     * @param Request $request
     * @param Ticket $ticket
     * @throws \Exception
     */
    public function __invoke(TicketPurchaseRequest $request, Ticket $ticket)
    {

        try {
            DB::beginTransaction(); // Start the transaction
            $ticket = Ticket::findOrFail($ticket->id);

            // Check ticket availability
            if ($ticket->quantity < $request->quantity) {
                throw new \Exception(__('Not enough tickets available.'));
            }

            // Calculate total amount (rounded to 2 decimal places)
            $totalAmount = round($ticket->price * $request->quantity, 2);
            Order::create([
                'attendeer_id' => Auth::user()->id,
                'ticket_id' => $ticket->id,
                'qty' => $request->quantity,
                'payment_status' => 'completed',
                'payment_method' => 1,
                'transaction_id' => (string) Str::uuid(),
                'total_amount' => $totalAmount,
            ]);
            DB::commit(); // Commit the transaction
            $ticket->decrement('quantity', $request->quantity);
            return back()->with('success', 'Ticket purchased successfully!');
        } catch (\Exception $e) {
            //dd($e->getMessage());
            DB::rollBack(); // Rollback the transaction
            return back()->with('error', $e->getMessage());
        }
    }
}
