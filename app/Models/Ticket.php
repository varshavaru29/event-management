<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'ticket_type_id',
        'price',
        'quantity',
        'sale_starts_at',
        'sale_ends_at'
    ];
    protected $casts = [
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function organizer()
    {
        return $this->hasOneThrough(User::class, Event::class, 'id', 'id', 'event_id', 'organizer_id');
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    /**
     * Custom accessor to get the event's name as a ticket attribute.
     */
    public function getEventNameAttribute()
    {
        return $this->event ? $this->event->title : null;
    }

    /**
     * Custom accessor to get the ticket type name as a ticket attribute.
     */
    public function getTicketTypeNameAttribute()
    {
        return $this->ticketType ? $this->ticketType->name : null;
    }
}
