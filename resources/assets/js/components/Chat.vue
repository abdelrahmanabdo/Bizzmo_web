<template>
	<div class="chat-container">	
		<chats @onChangeChat="changeChat($event)" @newMessage="changeNewMessageStatus($event)"></chats>
		<chat-box :chatid="chatid" :userid ="this.userid" :chat="currentChat" :isNewMessage="this.newMessage"  ></chat-box>
	</div>
</template>
<script>
	import ChatBox from './ChatBox';
	import Chats from './Chats';
    export default {
		components : {
			Chats ,
			ChatBox
		},
		props : {
			userid : Number
		},
        data() {
            return {
				chatid : 0,
				currentChat : null , 
				newMessage : false
            }
        },
        computed: {
            contentExists() {
                return this.text.length > 0;
            }
		},
        methods: {
			changeChat(chat){
				this.chatid = chat.id ;
				this.currentChat = chat ;
				this.newMessage = false;
			},
			changeNewMessageStatus(status){
				this.newMessage = status ; 
			}
		},
    }
</script>