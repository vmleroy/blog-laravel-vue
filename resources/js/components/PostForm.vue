<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <div class="sticky top-0 bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ isEditing ? 'Edit Post' : 'Create Post' }}
      </h2>
      <button
        @click="$emit('close')"
        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
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

        <!-- Action Buttons -->
        <div class="flex gap-4 pt-4">
          <button
            type="button"
            @click="$emit('close')"
            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white rounded-lg font-medium transition"
          >
            {{ loading ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
          </button>
        </div>
      </form>
  </div>
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
