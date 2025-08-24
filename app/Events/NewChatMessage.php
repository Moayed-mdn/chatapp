<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $roomId;

    public function __construct(ChatMessage $message, $roomId)
    {
        $this->message = $message;
        $this->roomId = $roomId;
        $this->message->load('user');
    }

    public function broadcastOn()
    {   
        return new PrivateChannel('chat-room.' . $this->roomId); // the name of the channel
    }

    // public function broadcastAs()
    // {   
    //     return 'new-chat';
    //     return $this;
    //     return 'new.message'; // This is what makes it work with .new.message the name of the channel
    // }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
            ],
            'created_at' => $this->message->created_at->toDateTimeString(),
            'room_id' => $this->roomId
        ];
    }

    public function dontBroadcastToCurrentUser(){

        return false;
    }
}