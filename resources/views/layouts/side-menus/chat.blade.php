@if ($isChat && (Auth::user()->hasReadySupplierCompany() || Auth::user()->hasReadyBuyerCompany()))
	<div>
		<h4 class="tb-title"><br>&nbsp;&nbsp;My Chats</h2>
	</div>
	@if (isset($id))
		<chats activechat="{{ $id }}"></chats>
	@else
		<chats activechat=""></chats>
	@endif		
@endif