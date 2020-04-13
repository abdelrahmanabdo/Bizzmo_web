<template>
    		<div class="chat-box col-xs-8" > <!-- Column 2 -->
			<div class="header" v-show="!isNewMessage">
				<div class="avatar col-xs-1" v-if="this.chatid != null"><img :src="defaultImg" width="30" height="30" /></div>
				<div class="name col-xs-10" v-if="this.chatid != null" >{{this.chat.type == 'normal' ?  this.chat.users[0].name : this.chat.inquiry[0].supplier.companyname}}</div>
				<div class="more col-xs-1" v-if="this.chatid != null"><img :src="moreSettingsImg" width="25" height="25" /></div>
			</div>
			<div class="header" v-show="isNewMessage">
				<div class="avatar col-xs-1" ><img src="/images/default-board-image.png" width="30" height="30" /> </div>
				<div class="user-search col-xs-10">
					<input v-model="searchQuery" type="text"  @keyup="searchResult($event.target.value)" placeholder="To : Type the name of person or company" >
					<div class="search-result col-xs-6" v-if="searchUsers.length != 0" >
						<a href="#" @click="select_new_chat_user(user)" v-for="user in searchUsers" :key="user.id" :if="searchUsers.length != 0">
					   		<img  :src="defaultImg" width="25" height="25" />
							<div class="name" >{{user.name}}</div>
						</a>
						<span v-if="searchUsers.length == 0 ">No Result</span>
					</div>		
				</div>	
				
				<div class="more col-xs-1" ><img :src="moreSettingsImg" width="25" height="25" /></div>
			</div>
            <div class="item-details" v-if="!this.isNewMessage && this.chat.type == 'negotiation'">
						<div class="avatar col-xs-1"  :style="(this.chat.inquiry.length > 2) ? ` width:75px` :  `flex-wrap : wrap`">
								<img v-for="product in this.chat.inquiry" :key="product.id" :src="serverURL+product.product.images[0].image" width="30" height="30" style="border-radius : 4px"/>
						</div>
						<div class="details col-xs-11">
							<div class="top">
									<div class="name">
										<div v-if="this.chat.inquiry.length > 1">{{this.chat.deal_id}} ({{this.chat.inquiry.lenght}} products)</div>										
                                        <div v-if="this.chat.inquiry.length == 1">{{this.chat.inquiry[0].product.name }} </div>
									</div>
                     			 	<div class="company-name"> {{this.chat.inquiry[0].supplier.companyname}}</div> 
							</div>		
						</div>
						<div class="price-details">
								<div class="price">1000$</div>
								<div class="discount">3000$</div>
						</div>
            </div>
			<div class="messages" >
                <div  v-for="message in messages" :class=' (message.user_id == userid) ? `message col-xs-12 sent` : ` message col-xs-12 received` ' :key="message.id">
                    <div class="avatar col-xs-1">
					   <img :src="defaultImg" width="30" height="30" />
					</div>
					<div class="text col-xs-11">
					   {{ message.content }}
					</div>
				</div>
			</div>
			<div class="footer">
			<div class="avatar col-xs-1"><img :src="defaultImg" width="30" height="30" /></div>
				<div class="type-message col-xs-9">
					<input v-model="message" type="text" name="" placeholder="Type here..." >
				</div>
				<a href="#" class="send col-xs-2" @click="postMessage" :disabled="!contentExists">
					<span> Send </span>
					<img :src="sendArrowImg" width="20" height="20" />
				</a>
			</div>
		</div>
</template>
<script>
    export default {
        name :"ChatBox",
		props: {
          userid: Number,
          chatid : Number ,
		  chat : Object ,
		  isNewMessage : Boolean
		},
        data() {
            return {
                message: '',
				messages: [],
				searchUsers : [],
                defaultImg : '/images/user.png',
                moreSettingsImg : '/images/more.svg',
				sendArrowImg : '/images/send.svg',
				searchQuery : '',
				serverURL : 'http://'+window.location.host + "/" ,
				newChatSelectedUserId : ''
            }
        },
        computed: {
            contentExists() {
                return this.message.length > 0;
            }
        },
       watch : {
			chatid : function (val){
				if(val){
					axios.get('/chat/getAll/'+val).then(({data}) => {
						this.messages = data;
					});		
				}
            },
            chat : function (chat){
				this.chat = chat;
			},
			isNewMessage : function(isNewMessage){
				this.isNewMessage = isNewMessage;
				this.messages = [] ; 
			},
			searchUsers : function(users){
				this.searchUsers = users;
			},
			messages : function (messages){
				this.messages = messages;
			}
		},
        methods: {
            postMessage() {
				if(this.message != ''){
					if(this.isNewMessage){
						axios.post('/chat/normal', {message: this.message , another_user : this.newChatSelectedUserId}).then(({data}) => {
							this.messages.push(data);
							this.message = '';
						});
						
					}else{
						axios.post('/chat/post/' + this.chatid, {message: this.message}).then(({data}) => {
							this.messages.push(data);
							this.message = '';
						});
					}
					this.scrollToEnd();
				}
			},
			searchResult (query) {
				if(query != ""){
					axios.get('/chat/users?q=' + query).then(({data}) => {
						this.searchUsers = data;
					});
				}else{
					this.searchUsers = []
				}
			},
			select_new_chat_user (user){
					this.searchQuery = user.name;
					this.newChatSelectedUserId = user.id;
					this.searchUsers = [] ;
			},
			scrollToEnd: function() {    	
     			 var container = this.$el.querySelector(".messages");
     			 container.scrollTop = container.scrollHeight;
    		},
        },
        created() {
			axios.get('/chat/getAll/' + this.chatid).then(({data}) => {
				this.messages = data;
				this.scrollToEnd();
			});
			// Registered client on public channel to listen to MessageSent event
			Echo.channel('chat').listen('MessageSent', ({message}) => {
				if (message.chat_id == this.chatid) {
					axios.post('/chat/reset/' + this.chatid, {userid: this.userid});	
				}				
				this.messages.push(message);
			});
		}
    }
</script>