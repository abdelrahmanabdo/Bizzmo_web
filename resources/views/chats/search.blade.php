@extends('layouts.app', ['hideRightMenuAndExtend' => true])
@section('content')	
	<Chat :userid={{\Auth::user()->id}} />
@stop	
@push('scripts')	
	<script>
		//Show negotiations list
		$('.showNegotiations').on('click',function(){``
			$('#messages').hide();
			$('.showMessages').removeAttr('id');
			$('#negotiations').show();
			$('.showNegotiations').attr('id','active')
			$('.new-chat').hide();
		});

		//Show messages list
		$('.showMessages').on('click',function(){
			$('.showNegotiations').removeAttr('id');
			$('#negotiations').hide();
			$('#messages').show();
			$('.showMessages').attr('id','active')
			$('.new-chat').show();
		});
		
		//Get chat messages
	 	$('.item').on('click',function(){
			var chatId = $(this).attr('id');
			var clickedItem = $(this).html();
			chatId = chatId.split('-')[1];
			var chatName = $(this).find('.details .top .name').html();
			var chatAvatar =  $(this).find('.avatar img').attr('src');
			$.ajax({
                url: '/chat/getAll/' + chatId,
                dataType: 'json',
                success: function (response) {
					$('.chat-box .messages').empty();
					$('.chat-box .header .name').html(chatName);
					$('.chat-box .item-details').remove();
					$('.chat-box .header .avatar').html('<img src="'+chatAvatar+'" width="30" height="30" />');
					$(
						`<div class="item-details">`+
							clickedItem
						+`</div>`
					).insertBefore('.chat-box .messages');
                    $.each(response, function (index, value) {
						if(value.user_id == {{Auth::user()->id}}){
							$('.chat-box .messages').append(
							`
							<div class="message col-xs-12 sent">
								<div class="avatar col-xs-1">
									<img src="{{asset('/images/user.png')}}" width="30" height="30" />
								</div>
								<div class="text col-xs-11">
									`+ value.content+`
								</div>
							</div>
							`
						);
						}else{
							$('.chat-box .messages').append(
							`
							<div class="message col-xs-12 received">
								<div class="avatar col-xs-1">
									<img src="{{asset('/images/user.png')}}" width="30" height="30" />
								</div>
								<div class="text col-xs-11">
									`+ value.content+`
								</div>
							</div>
							`
						);
						}

					});
                },

            });
		});
		//Search in chats list
		$('.chats-list .search input').on('keyup',function(){
		   var query =	$(this).val().toLowerCase();
		   $('.list .item').filter(function(){
			 $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1)
		   });
		});
	</script>
@endpush

<style>
	.page-content{
		padding : 5px 0 0 0 !important;
		margin: 0px !important; 
	}
	.content {
		margin : 0px !important;
	}
</style>