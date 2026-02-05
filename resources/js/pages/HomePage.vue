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
      <PostList :posts="posts" :current-user="currentUser" @select-post="selectedPost = $event" @new-post="showNewPostModal = true" @edit-post="handleEditPost" @delete-post="handleDeletePost" />
    </main>

    <ApiTester v-else />

    <NotificationCenter />
  </div>

  <!-- Post Form Modal -->
  <Modal :model-value="showNewPostModal || !!editingPost" :title="editingPost ? 'Edit Post' : 'Create Post'" @close="closePostModal">
    <PostForm :post="editingPost" :is-editing="!!editingPost" @close="closePostModal" @saved="handlePostSaved" :show-buttons="false" />
    <template #footer>
      <div class="flex gap-4">
        <Button variant="secondary" full-width @click="closePostModal">
          Cancel
        </Button>
        <Button type="submit" form="post-form" full-width>
          {{ editingPost ? 'Update' : 'Create' }}
        </Button>
      </div>
    </template>
  </Modal>

  <!-- Login Modal -->
  <Modal :model-value="showLoginModal" title="Laravel Blog" @close="showLoginModal = false">
    <AuthForm @login="handleLoginModal" @close="showLoginModal = false" :show-buttons="false" />
  </Modal>

  <!-- Post Detail Modal -->
  <Modal :model-value="!!selectedPost" title="Post Details" @close="selectedPost = null">
    <PostDetail :post="selectedPost" @close="selectedPost = null" />
  </Modal>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '../composables/useAuth.js';
import Modal from '../components/ui/Modal.vue';
import Button from '../components/ui/Button.vue';
import AuthForm from '../components/auth/AuthForm.vue';
import PostForm from '../components/post/PostForm.vue';
import PostList from '../components/post/PostList.vue';
import PostDetail from '../components/post/PostDetail.vue';
import ApiTester from '../components/ApiTester.vue';

const { isAuthenticated, currentUser, initializeAuth, setAuth, logout } = useAuth();

const posts = ref([]);
const selectedPost = ref(null);
const showTester = ref(false);
const showUserMenu = ref(false);
const showLoginModal = ref(false);
const showNewPostModal = ref(false);
const editingPost = ref(null);

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
  closePostModal();
  posts.value = [newPost, ...posts.value];
  loadPosts();
};

const handleEditPost = (post) => {
  editingPost.value = post;
};

const handleDeletePost = async (postId) => {
  if (confirm('Are you sure you want to delete this post?')) {
    try {
      await window.axios.delete(`/api/v1/posts/${postId}`);
      posts.value = posts.value.filter(p => p.id !== postId);
    } catch (error) {
      console.error('Error deleting post:', error);
    }
  }
};

const closePostModal = () => {
  showNewPostModal.value = false;
  editingPost.value = null;
};

onMounted(() => {
  initializeAuth();
  loadPosts();
});
</script>
