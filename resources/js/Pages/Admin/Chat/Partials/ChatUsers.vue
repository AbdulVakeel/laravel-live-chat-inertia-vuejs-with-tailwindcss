<script setup>
import { useChatStore } from '@/Stores/useChatStore.js'


const user = computed(() => usePage().props.user);
const data = computed(() => usePage().props.data);
const users = computed(() => data.value.users);
const store = useChatStore();

</script>


<template>
    <div class="input-group">

        <ul class="">
            <li v-for="user in store.users" :key="user.id" class="mb-2">

                <p class="flex items-center cursor-pointer  rounded-lg p-2" @click="store.selectedChat = user"
                    :class="{ 'bg-primary/10': store.selectedChat?.id == user.id }">
                    <img class="h-8 w-8 rounded-full m-1" :src="user.profile_photo_url" :alt="user.fullname" />

                    <span>
                        {{ user.fullname }}
                    </span>

                </p>

            </li>
        </ul>


    </div>
</template>