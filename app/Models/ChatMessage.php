<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{ 
        protected $fillable=[
                'chat_room_id',
                'user_id',
                'message'
        ];


    public function user()
    {
            return $this->hasOne('App\Models\User','id','user_id');
    }

    public function room()
    {
            return $this->hasOne('App\Models\ChatRoom','id','chat_room_id');
    }

}
