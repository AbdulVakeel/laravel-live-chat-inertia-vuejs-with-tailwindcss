import { defineStore } from 'pinia';
import moment from 'moment';

export const useChatStore = defineStore('chat-store', {
	state() {
		return {
			loading: false,
			error: null,
			users: [],
			messages: [],
			selectedChat: '',
		};
	},


	getters: {
		// getItems(state) {
		// 	return state.items;
		// },


		getChatMessages(state) {

			const formattedDate = item => moment(item.created_at).format('YYYY-MM-DD');

			return _(state.selectedChat?.messages)
				.groupBy(formattedDate)
				.value()
		},

	},

	actions: {

		addMessage(message) {
			// this.users.find((user) => {
			// 	if (user.id == form.user_id) {
			// 		user.messages.push(message);
			// 	}
			// })

			if (this.selectedChat.id == message.user_id) {
				this.selectedChat.messages.push(message);
			}
		},

		updateReadMessages(userId) {
			axios.post(route('admin.message.read', userId))
				.then(({ data }) => {
					this.users = data.users
				})
		},

		getUsersWithChat() {
			axios.get(route('admin.fetch.messages'))
				.then(({ data }) => {
					this.users = data.users
				})
		},

		setSelectedChat(user) {
			this.updateReadMessages(user.id)
			this.selectedChat = user
		}


	},
});
