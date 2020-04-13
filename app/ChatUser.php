<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    protected $table = "chat_user";
	protected $fillable = ['chat_id','user_id','unread'];

}
