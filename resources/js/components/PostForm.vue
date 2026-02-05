<template>
  <form id="post-form" @submit.prevent="handleSubmit" class="space-y-4">
    <!-- Title Field -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
      <input
        v-model="formData.title"
        type="text"
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Post title"
        required
      />
      <p v-if="errors.title" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.title }}</p>
    </div>

    <!-- Content Field -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content</label>
      <textarea
        v-model="formData.content"
        rows="6"
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Post content (minimum 10 characters)"
        required
      ></textarea>
      <p v-if="errors.content" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.content }}</p>
      <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ formData.content.length }} characters</p>
    </div>
  </form>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useNotification } from '../composables/useNotification.js';

const props = defineProps({
  post: Object,
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'saved']);

const { success, error } = useNotification();
const loading = ref(false);
const errors = reactive({});

const formData = ref({
  title: props.post?.title || '',
  content: props.post?.content || '',
});

const handleSubmit = async () => {
  // Reset errors
  Object.keys(errors).forEach((key) => {
    delete errors[key];
  });

  loading.value = true;

  try {
    const url = props.isEditing ? `/api/v1/posts/${props.post.id}` : '/api/v1/posts';
    const method = props.isEditing ? 'put' : 'post';

    const response = await window.axios[method](url, formData.value);

    success(props.isEditing ? 'Post updated successfully!' : 'Post created successfully!');
    emit('saved', response.data);
  } catch (err) {
    if (err.response?.data?.errors) {
      Object.assign(errors, err.response.data.errors);
    } else {
      error(err.response?.data?.error || 'Error saving post');
    }
  } finally {
    loading.value = false;
  }
};
</script>
