<template>
  <div class="space-y-4">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Posts</h2>

    <div v-if="posts.length === 0" class="text-center text-gray-500">
      Nenhum post encontrado
    </div>

    <div
      v-for="post in posts"
      :key="post.id"
      class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition"
      @click="$emit('select-post', post)"
    >
      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ post.title }}</h3>
      <p class="text-gray-600 dark:text-gray-400 mb-3">{{ getPreview(post.content) }}</p>

      <div class="flex justify-between items-center text-sm text-gray-500">
        <span>{{ formatDate(post.createdAt) }}</span>
        <span v-if="isRecent(post.updatedAt)" class="bg-green-100 text-green-800 px-2 py-1 rounded">Recente</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  posts: Array,
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR');
};

const getPreview = (content, length = 100) => {
  return content.substring(0, length) + (content.length > length ? '...' : '');
};

const isRecent = (date) => {
  const now = new Date();
  const postDate = new Date(date);
  const diffTime = Math.abs(now - postDate);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays < 7;
};
</script>
