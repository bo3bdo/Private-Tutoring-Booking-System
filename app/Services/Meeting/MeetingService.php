<?php

namespace App\Services\Meeting;

use App\Models\Booking;
use App\Models\LiveMeeting;
use Illuminate\Support\Facades\Log;

class MeetingService
{
    private array $providers = [];

    public function __construct()
    {
        $this->registerDefaultProviders();
    }

    public function registerProvider(string $name, MeetingProviderInterface $provider): void
    {
        $this->providers[$name] = $provider;
    }

    public function getProvider(string $name): ?MeetingProviderInterface
    {
        return $this->providers[$name] ?? null;
    }

    public function createMeetingForBooking(Booking $booking, ?string $provider = null): ?LiveMeeting
    {
        $providerName = $provider ?? $this->getDefaultProvider();
        $providerInstance = $this->getProvider($providerName);

        if (! $providerInstance || ! $providerInstance->isConfigured()) {
            Log::warning("Meeting provider {$providerName} is not available", [
                'booking_id' => $booking->id,
            ]);

            return null;
        }

        try {
            return $providerInstance->createMeeting($booking);
        } catch (\Exception $e) {
            Log::error('Failed to create meeting', [
                'booking_id' => $booking->id,
                'provider' => $providerName,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function deleteMeeting(LiveMeeting $meeting): bool
    {
        $provider = $this->getProvider($meeting->provider);

        if (! $provider) {
            return false;
        }

        return $provider->deleteMeeting($meeting);
    }

    public function updateMeeting(LiveMeeting $meeting, array $data): ?LiveMeeting
    {
        $provider = $this->getProvider($meeting->provider);

        if (! $provider) {
            return null;
        }

        try {
            return $provider->updateMeeting($meeting, $data);
        } catch (\Exception $e) {
            Log::error('Failed to update meeting', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function getJoinUrl(LiveMeeting $meeting, bool $isHost = false): string
    {
        $provider = $this->getProvider($meeting->provider);

        if (! $provider) {
            return $meeting->meeting_url;
        }

        return $provider->getJoinUrl($meeting, $isHost);
    }

    public function getAvailableProviders(): array
    {
        $available = [];
        foreach ($this->providers as $name => $provider) {
            if ($provider->isConfigured()) {
                $available[$name] = $provider->getName();
            }
        }

        return $available;
    }

    private function registerDefaultProviders(): void
    {
        $this->registerProvider('zoom', new ZoomMeetingProvider);
        $this->registerProvider('google_meet', new GoogleMeetProvider);
    }

    private function getDefaultProvider(): string
    {
        return config('services.meeting.default_provider', 'zoom');
    }
}
