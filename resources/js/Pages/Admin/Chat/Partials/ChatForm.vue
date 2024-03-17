<script setup>
import { useForm } from '@inertiajs/vue3';
import { useChatStore } from '@/Stores/useChatStore.js'
import { onUpdated } from 'vue';

// auto scroll on msg update
const chatListDiv = ref();
onUpdated(() => {
  const chatContainer = chatListDiv.value;
  chatContainer.scrollTop = chatContainer.scrollHeight;
});
/// end 




const store = useChatStore();
const user = computed(() => usePage().props.user);

const form = useForm({
  user_id: '',
  admin_id: user.value?.id,
  message: '',
});

const submit = () => {

  form.user_id = store.selectedChat?.id;

  form.post(route('admin.sendMessage'), {
    // preserveState: true,
    preserveScroll: true,
    onSuccess: () => {

      const message = {
        user_id: form.user_id,
        admin_id: form.admin_id,
        message: form.message,
        created_at: Date.now(),
      };

      store.addMessage(message)

      form.reset();

    },
  });

};

// Search user
const searchForm = useForm({
  search: '',
});

watch(
  () => searchForm.search,
  (newValue, oldValue) => {

    if (typeof window.LIT !== 'undefined') {
      clearTimeout(window.LIT);
    }

    window.LIT = setTimeout(() => {
      searchForm.get(route('admin.chat.index'), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: (page) => {
          // console.log(page.props?.data?.users);
          store.users = page.props?.data?.users;

        }
      });
    }, 700);
  },
  {
    deep: true,
  }
);
//end
</script>


<template>
  <!-- component -->
  <div>



    <div class="container mx-auto ">
      <div class="py-6 h-screen">
        <div class="flex border border-grey rounded shadow-lg h-full">

          <!-- Left - Users -->
          <div class="w-1/3 border flex flex-col ">

            <!-- Search -->
            <div class="py-2 px-2 bg-[#449388]  ">
              <input v-model="searchForm.search" type="text"
                class="w-full px-2 py-2 border-reduce-[20px] text-sm text-gray-900"
                placeholder="Search or start new chat" />
            </div>

            <!-- Contacts -->
            <div class=" flex-1  overflow-auto">

              <div v-for="user in store.users" :key="user.id" @click="store.setSelectedChat(user)"
                :class="{ 'bg-primary/100': store.selectedChat?.id == user.id }"
                class="px-3 flex items-center bg-grey-light cursor-pointer">
                <div>
                  <img class="h-8 w-8 rounded-full" :src="user.profile_photo_url" :alt="user.fullname" />
                </div>
                <div class="ml-4 flex-1 py-4">
                  <div class="flex items-bottom justify-between">
                    <span> {{ user.fullname }}</span>
                    <span v-if="user.total_unread"
                      class="h-7 w-7 rounded-full inline-flex  items-center justify-center bg-[#449388]  text-white text-xs font-semibold">
                      {{ user.total_unread }}
                    </span>
                  </div>

                </div>
              </div>


            </div>

          </div>


          <!-- Right -->
          <div class="w-2/3 border flex flex-col bg-[#449388]">

            <!-- Header -->
            <div v-if="store.selectedChat" class="py-2 px-3 bg-grey-lighter flex flex-row justify-between items-center">
              <div class="flex items-center">
                <div>
                  <img class="w-10 h-10 rounded-full" :src="store.selectedChat?.profile_photo_url" />
                </div>
                <div class="ml-4">
                  <p>{{ store.selectedChat?.fullname }}</p>
                  <p class="text-xs text-white/80">{{ store.selectedChat?.username }} | {{ store.selectedChat?.email }}</p>
                </div>
              </div>


            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-auto bg-[#DAD3CC]" ref="chatListDiv">
              <div class="py-2 px-3">

                <template v-for="(messages, date) in store.getChatMessages" :key="date">

                  <div class="flex justify-center mb-2">
                    <div class="rounded py-2 px-4 text-gray-900 bg-[#DDECF2]">
                      <p class="text-sm uppercase">
                        {{ formattedDate(date, 'MMMM D, YYYY') }}
                      </p>
                    </div>
                  </div>

                  <div v-for="(message, index) in messages" :key="index">


                    <div v-if="!message.admin_id" class="flex mb-2 text-gray-900">
                      <div class="rounded py-2 px-3" style="background-color: #F2F2F2">
                        <p class="text-sm text-purple">
                          {{ message.message }}
                        </p>

                        <p class="text-right text-xs text-grey-dark mt-1">
                          {{ formattedDate(message.created_at, 'h:mm a') }}
                        </p>
                      </div>
                    </div>

                    <div v-else class=" text-gray-900 flex justify-end mb-2">
                      <div class="rounded py-2 px-3 bg-[#E2F7CB]">
                        <p class="text-sm mt-1">
                          {{ message.message }}
                        </p>
                        <p class="text-right text-xs text-grey-dark mt-1">
                          {{ formattedDate(message.created_at, 'h:mm a') }}
                        </p>
                      </div>
                    </div>



                  </div>
                </template>
              </div>
            </div>

            <!-- Input -->
            <div class="bg-grey-lighter flex items-center">

              <div class="flex-1 mx-4">

                <TextInput :placeholder="__('Type your message here...')" id="message" v-model="form.message"
                  :error="form.errors.message" class="w-full  rounded px-2 py-2" />

              </div>
              <div>
                <Button :processing="form.processing" :disabled="form.processing" intent="primary" as="button" class=""
                  @click="submit">{{ __('Send ') }}
                </Button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>