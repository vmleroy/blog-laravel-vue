<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-md">
      <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <button @click="showTester = false" class="hover:opacity-80 transition">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laravel Blog</h1>
        </button>

        <div class="flex gap-4 items-center">
          <!-- API Tester Button - Only for Admin -->
          <div v-if="currentUser?.role === 'admin'" class="flex gap-4">
            <button
              @click="showTester = true"
              :class="showTester ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
              class="px-4 py-2 rounded-lg font-medium transition"
            >
              API Tester
            </button>
          </div>

          <!-- Auth Section -->
          <div v-if="!isAuthenticated" class="relative">
            <button
              @click="showLoginModal = true"
              class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition"
            >
              Login
            </button>
          </div>

          <!-- User Menu -->
          <div v-else class="relative">
            <button
              @click="showUserMenu = !showUserMenu"
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
            >
              <span class="text-sm font-medium text-gray-900 dark:text-white">{{ currentUser?.name }}</span>
              <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div
              v-if="showUserMenu"
              class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50"
            >
              <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ currentUser?.email }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">User ID: {{ currentUser?.id }}</p>
              </div>
              <button
                @click="handleLogout"
                class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
              >
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <main v-if="!showTester" class="max-w-4xl mx-auto px-6 py-8">
      <PostList :posts="posts" :current-user="currentUser" @select-post="selectedPost = $event" @new-post="showNewPostModal = true" />
    </main>

    <ApiTester v-else />

    <NotificationCenter />
  </div>

  <!-- Post Form Modal using Teleport -->
  <teleport to="body">
    <div v-if="showNewPostModal" class="fixed inset-0 flex items-center justify-center p-4 z-50" style="backdrop-filter: blur(4px); background-color: rgba(0, 0, 0, 0.1);" @click.self="showNewPostModal = false">
      <div class="relative">
        <button
          @click="showNewPostModal = false"
          class="absolute -top-10 right-0 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        <PostForm :is-editing="false" @close="showNewPostModal = false" @saved="handlePostSaved" />
      </div>
    </div>
  </teleport>

  <!-- Login Modal using Teleport -->
  <teleport to="body">
    <div v-if="showLoginModal" class="fixed inset-0 flex items-center justify-center p-4 z-50" style="backdrop-filter: blur(4px); background-color: rgba(0, 0, 0, 0.1);" @click.self="showLoginModal = false">
      <LoginPage @login="handleLoginModal" @close="showLoginModal = false" />
    </div>
  </teleport>

  <!-- Post Detail Modal using Teleport -->
  <teleport to="body">
    <div v-if="selectedPost" class="fixed inset-0 flex items-center justify-center p-4 z-50" style="backdrop-filter: blur(4px); background-color: rgba(0, 0, 0, 0.1);" @click.self="selectedPost = null">
      <PostDetail :post="selectedPost" @close="selectedPost = null" />
    </div>
  </teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '../composables/useAuth.js';
import LoginPage from './LoginPage.vue';
import PostForm from './PostForm.vue';
import PostList from './PostList.vue';
import PostDetail from './PostDetail.vue';
import ApiTester from './ApiTester.vue';

const { isAuthenticated, currentUser, initializeAuth, setAuth, logout } = useAuth();

const posts = ref([]);
const selectedPost = ref(null);
const showTester = ref(false);
const showUserMenu = ref(false);
const showLoginModal = ref(false);
const showNewPostModal = ref(false);

const handleLoginModal = async (authData) => {
  setAuth(authData);
  showLoginModal.value = false;
  await loadPosts();
};

const handleLogin = async (authData) => {
  setAuth(authData);
  await loadPosts();
};

const handleLogout = async () => {
  await logout();
  selectedPost.value = null;
  posts.value = [];
  showUserMenu.value = false;
};

const loadPosts = async () => {
  try {
    const response = await window.axios.get('/api/v1/posts');
    posts.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar posts:', error);
    posts.value = [];
  }
};

const handlePostCreated = (newPost) => {
  posts.value = [newPost, ...posts.value];
  loadPosts();
};

const handlePostSaved = (newPost) => {
  showNewPostModal.value = false;
  posts.value = [newPost, ...posts.value];
  loadPosts();
};

onMounted(() => {
  initializeAuth();
  loadPosts();
});
</script>
