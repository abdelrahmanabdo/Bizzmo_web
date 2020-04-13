<?php

namespace App\Repositories;

use Auth;
use DB;

use App\Chat;

class ChatRepository
{
	public static function create(array $data) {
		$chat = new Chat;
		$chat->user_id = Auth::user()->id;
		$chat->title = $data['title'];;
		$chat->save();
		
		//Chat users
		$i = 0;
		$users = [];
		if ($data['itemid']) {
			foreach ($data['itemid'] as $item) {				
				if ($data['itemdel'][$i] == '') {
					array_push($users,$item);
				}
			}
			$chat->users()->sync($users);
		}
        return $chat;
	}	
}