<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Booking $booking
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('user.'.$this->booking->student_id),
        ];

        if ($this->booking->teacher && $this->booking->teacher->user) {
            $channels[] = new PrivateChannel('user.'.$this->booking->teacher->user_id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'booking.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->booking->id,
            'subject' => $this->booking->subject->name,
            'start_at' => $this->booking->start_at->toIso8601String(),
            'status' => $this->booking->status->value,
        ];
    }
}
