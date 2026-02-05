<template>
  <div class="space-y-4">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Posts</h2>
      <button
        v-if="currentUser"
        @click="$emit('new-post')"
        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        New Post
      </button>
    </div>

    <div v-if="posts.length === 0" class="text-center text-gray-500">
      No posts found
    </div>

    <div
      v-for="post in posts"
      :key="post.id"
      class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer group"
      @click="$emit('select-post', post)"
    >
      <div class="flex justify-between items-start mb-2">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex-1 group-hover:text-blue-500 transition">{{ post.title }}</h3>
        <div class="flex gap-2 ml-4" @click.stop>
          <button
            v-if="isOwnPost(post)"
            @click="$emit('edit-post', post)"
            class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded transition"
          >
            Edit
          </button>
          <button
            v-if="isOwnPost(post) || isAdmin()"
            @click="$emit('delete-post', post.id)"
            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded transition"
          >
            Delete
          </button>
        </div>
      </div>
      <p class="text-gray-600 dark:text-gray-400 mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">{{ getPreview(post.content) }}</p>

      <div class="flex justify-between items-center text-sm text-gray-500">
        <div class="flex flex-col gap-1">
          <span>By <strong>{{ post.author_name }}</strong></span>
          <span>{{ formatDate(post.created_at || post.createdAt) }}</span>
        </div>
        <span v-if="isRecent(post.updated_at || post.updatedAt)" class="bg-green-100 text-green-800 px-2 py-1 rounded">Recent</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  posts: Array,
  currentUser: Object,
});

const emit = defineEmits(['select-post', 'new-post', 'edit-post', 'delete-post']);

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

const isOwnPost = (post) => {
  return props.currentUser?.id && post?.user_id && String(props.currentUser.id) === String(post.user_id);
};

const isAdmin = () => {
  return props.currentUser?.role === 'admin';
};
</script>
