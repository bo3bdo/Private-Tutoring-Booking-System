<?php

namespace App\Services\Meeting;

use App\Models\Booking;
use App\Models\LiveMeeting;

interface MeetingProviderInterface
{
    /**
     * Create a new meeting for a booking
     */
    public function createMeeting(Booking $booking): LiveMeeting;

    /**
     * Delete a meeting
     */
    public function deleteMeeting(LiveMeeting $meeting): bool;

    /**
     * Update meeting details
     */
    public function updateMeeting(LiveMeeting $meeting, array $data): LiveMeeting;

    /**
     * Get join URL for a participant
     */
    public function getJoinUrl(LiveMeeting $meeting, bool $isHost = false): string;

    /**
     * Check if the provider is properly configured
     */
    public function isConfigured(): bool;

    /**
     * Get the provider name
     */
    public function getName(): string;
}
