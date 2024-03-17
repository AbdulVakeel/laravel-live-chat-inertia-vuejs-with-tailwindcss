<script setup>
import ChatForm from './Partials/ChatForm.vue';


const data = computed(() => usePage().props.data);

</script>
<template>

	<AppContainer>
		<ChatForm  />
	</AppContainer>
</template>
