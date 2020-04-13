<template>
		<div class="chats-list col-xs-4 col-md-4">
			<div class="header-menu">
				<a href="#" class="tab showMessages" id="active" >Messages</a>
				<a href="#" class="tab showNegotiations" >Negotiations</a>
			</div>
			<div class="search">
				<input type="text" name="search" placeholder="Search" id="" class="col-xs-12">
                <img class="search-icon" src="/images/search-icon.svg"/>
			</div>
			<div class="list col-xs-2" id="messages">
			    	<div v-show="isNewMessage">
						<a href="#" class="item">
							<div class="avatar col-xs-1">
								<img src="/images/default-board-image.png"  width="35" height="35" />
							</div>
							<div class="details col-xs-11">
								<div class="top">
								<div class="new-message">New Message</div>
								</div>
							</div>
						</a>
					</div> 
				 	<a href="#" class="item"  v-for="chat in chats" :key="chat.id" @click="change_current_chat(chat)" >
						<div class="avatar col-xs-1">
							<img src='/images/user.png' width="35" height="35" />
						</div>
						<div class="details col-xs-11">
							<div class="top">
							<div v-for="user in chat.users" :key="user.id" class="name">{{user.name}}</div>
								<div class="time">{{chat.lastMessage ? chat.lastMessage.updated_at  : '' }}</div>
							</div>
							<div class="message-brief">{{chat.lastMessage ? chat.lastMessage.content  : ''}}</div>
						</div>
					</a>			
				    <h4 v-show="(chats && chats.length == 0 ) " class="no-messages">No messages yet</h4>	
			</div>
			<div class="list col-xs-2" id="negotiations" style="display:none">
					<a href="#" class="item"  v-for="chat in negotiations" @click="change_current_chat(chat)" :key="chat.id" >
						<div class="avatar col-xs-1"  style=" (chat.inquiry.length > 2) ` width:75px` :  `flex-wrap : wrap">
								<img v-for="product in chat.inquiry" :key="product.id" :src="serverURL+product.product.images[0].image" width="30" height="30" style="border-radius : 4px"/>
						</div>
						<div class="details col-xs-11">
							<div class="top">
									<div class="name">
                     			 	    <div class="company-name"> {{chat.inquiry[0].supplier.companyname}}</div> 
										<div v-if="chat.inquiry.length > 1">{{chat.deal_id}} ({{chat.inquiry.lenght}} products)</div>
										<div v-if="chat.inquiry.length == 1">{{chat.inquiry[0].product.name}} </div>
									</div>
							</div>		
						</div>
						<div class="price-details">
								<div class="price">1000$</div>
								<div class="discount">3000$</div>
						</div>
					</a>
					<h4 v-show="negotiations.length == 0" class="no-messages">No messages yet</h4>	
			</div>
			<div class="new-chat col-xs-8" >
				<a href="#" @click="create_new_message()" class="biz-button colored-default col-xs-12" id="lnksubmit">
					<span title="New Message">New Message</span>
				</a>
			</div>
		</div>
</template>

<script>
    export default {
		name:'Chats',
		props: {
		  userid: Number,
		},
        data() {
            return {
				chats: [],
				negotiations : [] ,
				currentChatId : 0  , 
				isNewMessage : false , 
				serverURL : 'http://'+window.location.host + "/"
            }
        },
        methods: {
			change_current_chat (chat) {
				this.isNewMessage = false;
				this.$emit('onChangeChat', chat);
			},
			//Create new message
			create_new_message () {
				this.isNewMessage = true ;
				this.scrollToTop();
				this.$emit('newMessage' , this.isNewMessage);
			},
			scrollToTop: function() {    	
     			$('#messages').scrollTop(0);
    		},
        },watch :{
			isNewMessage : function(newStatus) { 
				this.isNewMessage = newStatus;
			}
		},
        created() {
			axios.get('/chat/userchat').then(({data}) => {
				this.chats = data.chats;
				this.negotiations = data.negotiations;
			});
		}
    }
</script>