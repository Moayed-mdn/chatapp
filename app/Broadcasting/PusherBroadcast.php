<?php

namespace App\Broadcasting;

use Pusher\Pusher;
use Illuminate\Support\Facades\Log;

class PusherBroadcast
{
    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => config('broadcasting.connections.pusher.options.useTLS', true),
                'encrypted' => config('broadcasting.connections.pusher.options.encrypted', true)
            ]
        );
    }

    /**
     * Broadcast to a specific channel
     */
    public function send(string $channel, string $event, array $data): bool
    {
        try {
            $response = $this->pusher->trigger($channel, $event, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Pusher broadcast failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Broadcast to multiple channels
     */
    public function sendToChannels(array $channels, string $event, array $data): bool
    {
        try {
            $response = $this->pusher->trigger($channels, $event, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Pusher multi-channel broadcast failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Broadcast to all users (public announcement)
     */
    public function broadcastToAll(string $event, array $data): bool
    {
        return $this->send('public-channel', $event, $data);
    }

    /**
     * Broadcast to a specific user
     */
    public function broadcastToUser(int $userId, string $event, array $data): bool
    {
        return $this->send('private-user.' . $userId, $event, $data);
    }

    /**
     * Get Pusher instance for advanced operations
     */
    public function getPusherInstance(): Pusher
    {
        return $this->pusher;
    }
}