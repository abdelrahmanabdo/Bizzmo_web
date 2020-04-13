<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
	protected $table = "chats";

	protected $fillable = ['title','user_id','type' , 'deal_id'];

	public function inquiry(){
		return $this->hasMany('App\Inquiry','deal_id','deal_id');
	}

    public function users()
	{
		return $this->belongsToMany('App\User')->where('user_id','<>',\Auth::user()->id);
	}

	public function lastMessage(){
		return $this->hasOne('App\Message')->orderBy('id','desc');
	}
}
