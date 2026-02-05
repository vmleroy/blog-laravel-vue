<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <div class="sticky top-0 bg-gray-50 dark:bg-gray-700 p-6 flex justify-between items-center border-b">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ post.title }}</h2>
      <button
        @click="$emit('close')"
        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
      >
        ✕
      </button>
    </div>

    <div class="p-6">
      <div class="prose dark:prose-invert max-w-none mb-8">
        <div class="mb-4 pb-4 border-b dark:border-gray-600">
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">By <strong>{{ post.author_name }}</strong></p>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(post.createdAt) }}</p>
        </div>
        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ post.content }}</p>
      </div>

      <CommentSection :post-id="post.id" />
    </div>
  </div>
</template>

<script setup>
import CommentSection from './CommentSection.vue';

defineProps({
  post: Object,
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>
