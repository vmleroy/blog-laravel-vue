<template>
  <div class="max-w-2xl w-full">
    <div class="prose dark:prose-invert max-w-none mb-8">
      <div class="mb-4 pb-4 border-b dark:border-gray-600">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">By <strong>{{ post.author_name }}</strong></p>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(post.created_at) }}</p>
      </div>
      <p class="text-gray-700 dark:text-gray-300 mb-4">{{ post.content }}</p>
    </div>

    <CommentSection :post-id="post.id" />
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
