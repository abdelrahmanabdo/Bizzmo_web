<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Auth;
use DB;
use Input;
use View;
use App\Repositories\ChatRepository;
use App\Chat;
use App\Message;
use App\User;

class chatcontroller extends Controller
{
	public function index(Request $request, $id = '')
    {
		$userChats = \App\ChatUser::whereUserId(Auth::user()->id)->pluck('chat_id')->toArray();
		$chats     = \App\Chat::whereIn('id',$userChats)->with(['users','lastMessage'])->whereType('normal')->get();
		$deals     = \App\Chat::whereIn('id',$userChats)->select(['id','deal_id','updated_at'])->with(['inquiry','inquiry.product.images','lastMessage'])->whereType('negotiation')->get();	
		return View('chats.search')
			->with('chats', $chats)
			->with('negotiations', $deals);
	
    }
	
	public function create()
    {
		return View('chats.manage')
			->with('title', "Create Chat");
    }
	
	public function newchat(Request $request)
	{
		$data = $request->input();
		$chat = ChatRepository::create($data);
		return redirect('/chat/' . $chat->id);
	}
	
	public function userchat()
    {
		$userChats = \App\ChatUser::whereUserId(Auth::user()->id)->pluck('chat_id')->toArray();
		$chats     = \App\Chat::whereIn('id',$userChats)->with(['users','lastMessage'])->whereType('normal')->get();
		$deals     = \App\Chat::whereIn('id',$userChats)->select(['id','deal_id','type','updated_at'])->with(['inquiry','inquiry.supplier','inquiry.product.images','lastMessage'])->whereType('negotiation')->get();
		return response()->json(['chats'=> $chats ,'negotiations'=> $deals]);
    }

    // Allows us to post new message
	public function save(Request $request)
	{
		$message = new Message();
		$content = request('message');
		$message->content = $content;
		$message->chat_id = $request->route('id');
		$message->user_id = Auth::user()->id;
		$message->sendername = Auth::user()->name;
		$message->save();
		DB::table('chat_user')->where('chat_id', $request->route('id'))->where('user_id', '!=', Auth::user()->id)->update(['unread' => DB::raw('unread + 1')]);
		
		$message = Message::find($message->id);
		
		//\Log::warning('broad: ' . $message);
		
		broadcast(new \App\Events\MessageSent($message));
		return $message;
	}
	
	public function getAll(Request $request)
	{
		$messages = Message::select('content', 'user_id', 'chat_id')->where('chat_id', $request->route('id'))->get();
	
		$messages = Message::selectRaw('messages.content, messages.user_id, messages.chat_id, users.name as sendername')
		->join('users', 'messages.user_id', 'users.id')
		->where('chat_id', $request->route('id'))->get();


		return $messages;
	}	
	
	public function dataAjax(Request $request)
	{
		$data = [];
		$search = $request->q;
		$query = User::select("users.id", "users.name")
			->where('users.name', 'LIKE', "%$search%")
			->where('users.active', 1)
			->where('users.id', '<>', Auth::user()->id)
			->orderBy('name');
		if ($search == '') {
			$query = $query->take(5);
		}
		return response()->json($query->get());
	}
	
	public function members(Request $request)
	{
		$list = rtrim($request->memberlist, ',');
		$userarr = explode(',', $list);
		$count = count($userarr);
		$chats = Chat::with('users')->whereHas('users', function ($query) use($userarr) {
			$query->whereIn('user_id', $userarr);
		})->get();
		//return $chats;
		foreach ($chats as $chat) {
			$allusersfound = true;
			foreach ($userarr as $user) {
				if (!in_array($user, $chat->users->pluck('id')->all())) {
					$allusersfound = false;
				}
			}
			if ($allusersfound && $chat->users->count() == $count) {
				return $chat->title;
			}
		}
		return '';
		die;
		
		$list = rtrim($request->memberlist, ',');
		$count = count(explode(',', $list));
		$chats = Chat::whereHas('users', function ($query) use($list) {
			$query->whereIn('user_id', explode(',', $list));
		})->get();
		return response()->json('');
		foreach ($chats as $chat) {
			if ($chat->users->count == $count) {
				return json($chat->title);
			}
		}
		return response()->json('');
	}
	
	public function resetcount(Request $request)
	{
		DB::table('chat_user')->where('chat_id', $request->route('id'))->where('user_id', request('userid'))->update(['unread' => 0]);
	}

	/**
	 *	Create normal chats  
	 */
	public function create_normal_chat (Request $request){
		$newChat = \App\Chat::create([
			'title' => 'normal',
			'user_id' => Auth::user()->id ,
			'type' => 'normal'
		]);
		if($newChat){
		   $newChatUser = \App\ChatUser::create(['chat_id'=>$newChat->id , 'unread'=> 0 ,'user_id' => Auth::user()->id ]);
		   $newChatUser = \App\ChatUser::create(['chat_id'=>$newChat->id , 'unread'=> 1 ,'user_id' => $request->another_user]);
		   $newMessage  = \App\Message::create([
				'user_id' => Auth::user()->id,
				'chat_id' => $newChat->id,
				'content' => $request->message
		   ]); 	
		}
		broadcast(new \App\Events\MessageSent($newMessage));
		return $newMessage;
	}

	/**
	 *	Create neogtiation chats  
	 */
	public function create_negotiation_chats (){
		//Get current user inquiry
		$userInquiry = \App\Inquiry::where(['buyer_id'=> \Auth::user()->getCompanyId() , 'status'=>'waiting'])->groupBy('deal_id')->get();			
		foreach($userInquiry as $deal){
			$isDealExists =!\App\Chat::where(['deal_id' => $deal->deal_id])->exists();
			if($isDealExists){
				$chatId   = \App\Chat::create(['title'=> 'Deal '. $deal->deal_id,'user_id'=>\Auth::user()->id , 'type'=>'negotiation' , 'deal_id' => $deal->deal_id]);
				$supplier_id = \App\Company::whereId($deal->supplier_id)->value('tenant_id');
				if(!empty($supplier_id)){
				   $dealUsers = \App\User::whereTenantId($supplier_id)->pluck('id');
				   foreach($dealUsers as $user){
					   $newChatUser = \App\ChatUser::create(['chat_id'=>$chatId->id , 'user_id' => $user]);
				   }
				}
			}
		}
		$userChats = \App\ChatUser::whereUserId(Auth::user()->id)->pluck('chat_id');
		$chats     = \App\Chat::whereIn('id',$userChats)->with(['users','lastMessage'])->whereType('normal')->get();
		$negotiations = \App\Chat::whereIn('id',$userChats)->with(['users','lastMessage'])->whereType('negotiation')->get();
		return View('chats.search')
					->with('chats', $chats)
					->with('negotiations', $negotiations);
	}
}
