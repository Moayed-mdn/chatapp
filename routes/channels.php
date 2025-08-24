<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat-room.{roomId}', function ($user,$roomId) {
    if(Auth::check())
    {
        return ['id'=>$user->id,'name'=>$user->name];
    }

});

Broadcast::channel('private-user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat', function ($user) {
    return true;
});