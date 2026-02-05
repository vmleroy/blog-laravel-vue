<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-md">
      <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laravel Blog</h1>
        <div class="flex gap-4">
          <button
            @click="showTester = false"
            :class="!showTester ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
            class="px-4 py-2 rounded-lg font-medium transition"
          >
            Blog
          </button>
          <button
            @click="showTester = true"
            :class="showTester ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
            class="px-4 py-2 rounded-lg font-medium transition"
          >
            API Tester
          </button>
        </div>
      </div>
    </nav>

    <main v-if="!showTester" class="max-w-4xl mx-auto px-6 py-8">
      <PostList :posts="posts" @select-post="selectedPost = $event" />

      <PostDetail v-if="selectedPost" :post="selectedPost" @close="selectedPost = null" />
    </main>

    <ApiTester v-else />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import PostList from './PostList.vue';
import PostDetail from './PostDetail.vue';
import ApiTester from './ApiTester.vue';

const posts = ref([]);
const selectedPost = ref(null);
const showTester = ref(false);

onMounted(async () => {
  try {
    const response = await window.axios.get('/api/v1/posts');
    posts.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar posts:', error);
  }
});
</script>
