<script setup>
import { useForm } from '@inertiajs/vue3';

const { item, show, maxWidth, closeable } = defineProps({
	item: [Array, Object],
	show: { type: Boolean, default: false },
	maxWidth: { type: String, default: '2xl' },
	closeable: { type: Boolean, default: true },
});

const data = computed(() => usePage().props.data);
const app = computed(() => usePage().props.app);
const message = computed(() => data.value.message);
const user = computed(() => usePage().props.user);
const items = computed(() => data.value.items);


const form = useForm({
	message: item?.message ?? '',

	
});


</script>
<template>
  <ul class="chat">
    <li class="left clearfix" v-for="message in messages">

      <div class="clearfix">
        <div class="header">
          <strong>
            {{ item.message}}
          </strong>
        </div>
        <!-- <p>
          {{ item.message }}
        </p> -->
      </div>
    </li>
  </ul>
</template>

