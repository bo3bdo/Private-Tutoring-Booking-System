<?php

namespace App\Services\Meeting;

use App\Models\Booking;
use App\Models\LiveMeeting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomMeetingProvider implements MeetingProviderInterface
{
    private string $apiKey;

    private string $apiSecret;

    private string $baseUrl = 'https://api.zoom.us/v2';

    public function __construct()
    {
        $this->apiKey = config('services.zoom.api_key') ?? '';
        $this->apiSecret = config('services.zoom.api_secret') ?? '';
    }

    public function createMeeting(Booking $booking): LiveMeeting
    {
        try {
            $token = $this->generateJwtToken();

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/users/me/meetings", [
                'topic' => "Lesson: {$booking->subject->name}",
                'type' => 2, // Scheduled meeting
                'start_time' => $booking->start_at->format('Y-m-d\TH:i:s'),
                'duration' => $booking->durationInMinutes(),
                'timezone' => config('app.timezone'),
                'password' => $this->generatePassword(),
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => false,
                    'waiting_room' => true,
                    'recording' => 'cloud',
                    'auto_recording' => 'cloud',
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return LiveMeeting::create([
                    'booking_id' => $booking->id,
                    'provider' => 'zoom',
                    'meeting_id' => (string) $data['id'],
                    'meeting_url' => $data['join_url'],
                    'join_url' => $data['join_url'],
                    'host_url' => $data['start_url'],
                    'password' => $data['password'] ?? null,
                    'scheduled_at' => $booking->start_at,
                    'duration_minutes' => $data['duration'] ?? 60,
                    'metadata' => [
                        'zoom_meeting_uuid' => $data['uuid'] ?? null,
                        'host_id' => $data['host_id'] ?? null,
                    ],
                ]);
            }

            Log::error('Failed to create Zoom meeting', [
                'booking_id' => $booking->id,
                'response' => $response->json(),
            ]);

            throw new \Exception('Failed to create Zoom meeting: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Zoom meeting creation error', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function deleteMeeting(LiveMeeting $meeting): bool
    {
        try {
            $token = $this->generateJwtToken();

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
            ])->delete("{$this->baseUrl}/meetings/{$meeting->meeting_id}");

            return $response->successful() || $response->status() === 404;
        } catch (\Exception $e) {
            Log::error('Failed to delete Zoom meeting', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function updateMeeting(LiveMeeting $meeting, array $data): LiveMeeting
    {
        try {
            $token = $this->generateJwtToken();

            $updateData = [];
            if (isset($data['start_at'])) {
                $updateData['start_time'] = $data['start_at']->format('Y-m-d\TH:i:s');
            }
            if (isset($data['duration'])) {
                $updateData['duration'] = $data['duration'];
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'Content-Type' => 'application/json',
            ])->patch("{$this->baseUrl}/meetings/{$meeting->meeting_id}", $updateData);

            if ($response->successful()) {
                $responseData = $response->json();
                $meeting->update([
                    'scheduled_at' => $data['start_at'] ?? $meeting->scheduled_at,
                    'duration_minutes' => $data['duration'] ?? $meeting->duration_minutes,
                ]);

                return $meeting->fresh();
            }

            throw new \Exception('Failed to update Zoom meeting');
        } catch (\Exception $e) {
            Log::error('Failed to update Zoom meeting', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getJoinUrl(LiveMeeting $meeting, bool $isHost = false): string
    {
        return $isHost ? $meeting->host_url : $meeting->join_url;
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) && ! empty($this->apiSecret);
    }

    public function getName(): string
    {
        return 'zoom';
    }

    private function generateJwtToken(): string
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $time = time();
        $payload = json_encode([
            'iss' => $this->apiKey,
            'exp' => $time + 3600,
            'iat' => $time,
        ]);

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64Header.'.'.$base64Payload, $this->apiSecret, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64Header.'.'.$base64Payload.'.'.$base64Signature;
    }

    private function generatePassword(): string
    {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    }
}
