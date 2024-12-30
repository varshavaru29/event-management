<?php

namespace App\Observers;

use App\Jobs\SendTicketPurchaseEmail;
use App\Models\Order;

class OrderObserver
{
    public function created(Order $order)
    {

        $emailDetails = [
            'event_name' => $order->ticket->event->name,
            'event_date' => \Carbon\Carbon::parse($order->ticket?->event?->start_date)->format('F d, Y'),
            'quantity' => $order->quantity,
            'total_price' => $order->total_price,
        ];
        dispatch(new SendTicketPurchaseEmail($order->user->email, $emailDetails));
    }
}
