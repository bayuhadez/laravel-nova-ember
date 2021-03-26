import Component from '@ember/component';
import Env from 'rcf/config/environment';
import Echo from 'laravel-echo';
import io from 'socket.io-client';
import { observer } from '@ember/object';
import { inject as service } from '@ember/service';
import $ from 'jquery';

export default Component.extend({
	store: service(),
	session: service(),
	didInsertElement()
	{
		let self = this;

		window.Echo = new Echo({
			broadcaster: 'socket.io',
			host: window.location.hostname + ':6001'
		});

		window.Echo.channel('chat')
			.listen('MessageSent', (e) => {
				self.get('store').findRecord('chat-room', self.get('chatRoom.id'), {include:'chats'});
			});
	},

	// scroll to bottom of messages div when a new message comes in
	onMessagesAdded: observer('messages.[]', function () {
		setTimeout(function () {
			let messagesDiv = $(".live-chat-messages")[0];
			messagesDiv.scrollTop = messagesDiv.scrollHeight;
		}, 100);
	}),

	init()
	{
		this._super();
		this.set('messages', []);
	},

	actions: {
		sendMessage()
		{
			var self = this;

			var message = this.get('message');

			var chat = this.get('store').createRecord('chat', {
				message: message,
				chatRoom: this.get('chatRoom'),
			});

			chat.save();

			// empty the input text field to prepare for next chat message
			this.set('message', '');
		}
	}

});
