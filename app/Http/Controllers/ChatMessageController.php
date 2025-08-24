<?php

namespace App\Http\Controllers;

use App\Broadcasting\PusherBroadcast;
use App\Events\MessageSent;
use App\Events\NewChatMessage;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function rooms(Request $request)
    {
        return ChatRoom::all();
    }

    public function messages(Request $request,$roomId)
    {

        return ChatMessage::where('chat_room_id',$roomId)
                    ->with('user')
                    ->get();
    }

    public function newMessage(Request $request,$roomId)
    {
       
        $message = ChatMessage::create([
            'chat_room_id' => $roomId,
            'user_id' => $request->user()->id,
            'message' => $request->message
        ]);

         // Load relationships for broadcasting
        $message->load('user');


       // Use Pusher directly (bypass Laravel events)
    $pusher = new \Pusher\Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        [
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'useTLS' => true
        ]
    );
    
    // broadcast(new MessageSent($message, $message->user, $roomId))->toOthers(); 
    broadcast(new NewChatMessage($message, $roomId))->toOthers(); 
    // app(PusherBroadcast::class)->send(
    //         channel:'chat-room.' . $roomId,
    //         event: 'new.message',
    //         data: [
    //             'id' => $message->id,
    //             'message' => $message->message,
    //             'user' => [
    //                 'id' => $message->user->id,
    //                 'name' => $message->user->name,
    //             ],
    //             'created_at' => $message->created_at->toDateTimeString(),
    //             'room_id' => $roomId
    //         ]
    // );
    // $pusher->trigger(
    //     'chat-room.' . $roomId,
    //     'new.message', // Simple event name
    //     [
    //         'id' => $message->id,
    //         'message' => $message->message,
    //         'user' => [
    //             'id' => $message->user->id,
    //             'name' => $message->user->name,
    //         ],
    //         'created_at' => $message->created_at->toDateTimeString(),
    //         'room_id' => $roomId
    //     ]
    // );

        return $message;
    }

}
