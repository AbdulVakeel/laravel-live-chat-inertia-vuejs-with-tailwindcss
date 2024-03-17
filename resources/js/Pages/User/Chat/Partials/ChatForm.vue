<script setup>
import { onMounted, onUpdated } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useChatStore } from '@/Stores/useChatStore.js'

const store = useChatStore();
const user = computed(() => usePage().props.user);
const data = computed(() => usePage().props.data);

// auto scroll on msg update
const chatListDiv = ref();
onUpdated(() => {
  const chatContainer = chatListDiv.value;
  chatContainer.scrollTop = chatContainer.scrollHeight;
});
/// end 

onMounted(() => {
  store.messages = data.value.messages;

  window.Echo.private('chat')
    .listen('MessageSent', ({ message }) => {
      // console.log(message);
      if (user.value?.id == message.user_id) {
        store.messages.push(message);
      }

    });

})


const form = useForm({
  message: '',
  user_id: user.value?.id,
});

const submit = () => {
  form.post(route('user.sendMessage'), {
    // preserveState: true,
    preserveScroll: true,
    onSuccess: () => {

      const message = {
        user_id: form.user_id,
        message: form.message,
        created_at: Date.now(),
      };

      store.messages.push(message);


      form.reset();
    },
  });

};
</script>
<template>
  <div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-screen" >

    <ul
      class="static flex flex-col flex-grow bg-[#0a1e35] overflow-y-auto border border-secondary border-dotted  transition-all ease-in-out duration-300 group/sidebar h-full"
      ref="chatListDiv">
      <li v-for="message in store.messages" :key="message.id">

        <!-- Message from User -->

        <div v-if="!message.admin_id"
          class="px-4 py-2 rounded-lg  rounded-br-none flex items-end justify-end font-semibold text-dark p-1 mb-2 ">

          <img class="h-8 w-8 rounded-full m-1" :src="user?.profile_photo_url" :alt="user?.fullname" />
          <p class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-[#5aaeff] text-white ">
            {{ user?.fullname }} Reply From {{ message.message }} {{ formattedDateTime(message.created_at) }}
          </p>


        </div>

        <!-- Message from Admin -->
        <div v-else class="flex items-end p-2 px-4 py-2 rounded-lg  rounded-bl-none text-gray-600 mb-2">

          <img class="h-8 w-8 rounded-full m-1" :src="message.admin?.profile_photo_url" :alt="message.admin?.firstname" />
          <p class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-[#36c1da] text-white">

            {{ message.admin?.fullname }} Reply From {{ message.message }} {{ formattedDateTime(message.created_at) }}
          </p>


        </div>


      </li>
    </ul>

    <div>
      <TextInput label="Message" :placeholder="__('Type your message here...')" id="message" v-model="form.message"
        :error="form.errors.message" class="w-full" />


      <Button :processing="form.processing" :disabled="form.processing" intent="primary" as="button" class="mt-5"
        @click="submit">{{ __('Send ') }}</Button>
    </div>


  </div>
</template>
