<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Broadcasting\PusherBroadcast;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    protected $pusherBroadcast;

    public function __construct(PusherBroadcast $pusherBroadcast)
    {
        $this->pusherBroadcast = $pusherBroadcast;
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $user = auth()->user();

        // Method 1: Using Laravel event
        event(new MessageSent($message, $user));

        // Method 2: Using our custom PusherBroadcast
        $this->pusherBroadcast->send('chat-room', 'message.sent', [
            'message' => $message,
            'user' => $user->name,
            'time' => now()->toDateTimeString()
        ]);

        return response()->json(['status' => 'Message sent!']);
    }

    public function sendToUser(Request $request, $userId)
    {
        $message = $request->input('message');

        $this->pusherBroadcast->broadcastToUser($userId, 'private.message', [
            'message' => $message,
            'from' => auth()->user()->name,
            'time' => now()->toDateTimeString()
        ]);

        return response()->json(['status' => 'Private message sent!']);
    }
}