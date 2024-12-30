<?php

namespace App\Jobs;

use App\Mail\TicketPurchaseMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTicketPurchaseEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $ticketDetails;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $ticketDetails)
    {
        $this->email = $email;
        $this->ticketDetails = $ticketDetails;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->queue(new TicketPurchaseMail($this->ticketDetails));
    }
}
