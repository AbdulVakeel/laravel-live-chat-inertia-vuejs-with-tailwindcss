<script setup>
import { onMounted } from 'vue';
import ChatForm from './Partials/ChatForm.vue';
import ChatUsers from './Partials/ChatUsers.vue';
import { useChatStore } from '@/Stores/useChatStore.js'


const store = useChatStore();
const data = computed(() => usePage().props.data);


onMounted(() => {
	store.users = data.value.users;

	window.Echo.private('chat')
		.listen('MessageSent', ({ message }) => {
			console.log(message);

			store.getUsersWithChat()

			store.addMessage(message)


			// store.users.find((user) => {
			// 	if (user.id == message.user_id) {
			// 		user.messages.push(message);
			// 	}
			// })

			// if (store.selectedChat.id == message.user_id) {
			// 	store.selectedChat.messages.push(message);
			// }

		});

})


</script>
<template>
	<AppContainer>
		<div class="grid grid-cols-1 gap-5">
			

			<div>
				<ChatForm />

			</div>

		</div>
	</AppContainer>
</template>


<script>
import Layout from '@/Layouts/AdminLayout.vue';
import { defineComponent } from 'vue';
export default defineComponent({
	layout: Layout,
});
</script>
