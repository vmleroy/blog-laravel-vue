<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 p-8">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Backend API Tester</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- GET All Posts -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">GET /api/posts</h2>
          <button
            @click="getAllPosts"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mb-4"
          >
            Testar
          </button>
          <div v-if="results.getAllPosts" class="bg-gray-50 dark:bg-gray-700 p-4 rounded overflow-auto max-h-60">
            <pre class="text-xs text-gray-800 dark:text-gray-200">{{ JSON.stringify(results.getAllPosts, null, 2) }}</pre>
          </div>
        </div>

        <!-- GET Single Post -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">GET /api/posts/:id</h2>
          <div class="flex gap-2 mb-4">
            <input
              v-model="postId"
              type="number"
              placeholder="Post ID"
              class="flex-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
            <button
              @click="getPostById"
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
            >
              Testar
            </button>
          </div>
          <div v-if="results.getPostById" class="bg-gray-50 dark:bg-gray-700 p-4 rounded overflow-auto max-h-60">
            <pre class="text-xs text-gray-800 dark:text-gray-200">{{ JSON.stringify(results.getPostById, null, 2) }}</pre>
          </div>
        </div>

        <!-- POST Create Post -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">POST /api/posts</h2>
          <input
            v-model="newPost.title"
            placeholder="Título"
            class="w-full px-3 py-2 border rounded-lg mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          />
          <textarea
            v-model="newPost.content"
            placeholder="Conteúdo"
            rows="3"
            class="w-full px-3 py-2 border rounded-lg mb-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          ></textarea>
          <button
            @click="createPost"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg mb-4"
          >
            Criar Post
          </button>
          <div v-if="results.createPost" class="bg-gray-50 dark:bg-gray-700 p-4 rounded overflow-auto max-h-60">
            <pre class="text-xs text-gray-800 dark:text-gray-200">{{ JSON.stringify(results.createPost, null, 2) }}</pre>
          </div>
        </div>

        <!-- GET Comments by Post -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">GET /api/posts/:id/comments</h2>
          <div class="flex gap-2 mb-4">
            <input
              v-model="commentPostId"
              type="number"
              placeholder="Post ID"
              class="flex-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
            <button
              @click="getCommentsByPost"
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
            >
              Testar
            </button>
          </div>
          <div v-if="results.getComments" class="bg-gray-50 dark:bg-gray-700 p-4 rounded overflow-auto max-h-60">
            <pre class="text-xs text-gray-800 dark:text-gray-200">{{ JSON.stringify(results.getComments, null, 2) }}</pre>
          </div>
        </div>

        <!-- POST Create Comment -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">POST /api/posts/:id/comments</h2>
          <input
            v-model="newComment.postId"
            type="number"
            placeholder="Post ID"
            class="w-full px-3 py-2 border rounded-lg mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          />
          <textarea
            v-model="newComment.body"
            placeholder="Comentário"
            rows="3"
            class="w-full px-3 py-2 border rounded-lg mb-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          ></textarea>
          <button
            @click="createComment"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg mb-4"
          >
            Criar Comentário
          </button>
          <div v-if="results.createComment" class="bg-gray-50 dark:bg-gray-700 p-4 rounded overflow-auto max-h-60">
            <pre class="text-xs text-gray-800 dark:text-gray-200">{{ JSON.stringify(results.createComment, null, 2) }}</pre>
          </div>
        </div>

        <!-- Error Display -->
        <div v-if="error" class="bg-red-50 dark:bg-red-900 rounded-lg shadow-md p-6 col-span-full">
          <h2 class="text-xl font-bold text-red-900 dark:text-red-100 mb-4">Erro</h2>
          <pre class="text-sm text-red-800 dark:text-red-200">{{ error }}</pre>
        </div>
      </div>

      <!-- Summary -->
      <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Status dos Testes</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div
            v-for="(value, key) in results"
            :key="key"
            class="p-3 rounded-lg"
            :class="value ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-700'"
          >
            <span class="text-sm font-medium" :class="value ? 'text-green-800 dark:text-green-200' : 'text-gray-600 dark:text-gray-400'">
              {{ key }}: {{ value ? '✓' : '○' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import gatewayApi from '../api/gatewayApi.js';

const results = ref({
  getAllPosts: null,
  getPostById: null,
  createPost: null,
  getComments: null,
  createComment: null,
});

const error = ref('');
const postId = ref(1);
const commentPostId = ref(1);
const newPost = ref({
  title: 'Post de Teste',
  content: 'Este é um post criado pelo componente de teste da API',
});
const newComment = ref({
  postId: 1,
  body: 'Comentário de teste criado pela API',
});

const getAllPosts = async () => {
  try {
    error.value = '';
    const response = await gatewayApi.posts.getAll();
    results.value.getAllPosts = response;
  } catch (err) {
    error.value = `GET /gateway/posts: ${err.message}`;
  }
};

const getPostById = async () => {
  try {
    error.value = '';
    const response = await gatewayApi.posts.getById(postId.value);
    results.value.getPostById = response;
  } catch (err) {
    error.value = `GET /gateway/posts/${postId.value}: ${err.message}`;
  }
};

const createPost = async () => {
  try {
    error.value = '';
    const response = await gatewayApi.posts.create(newPost.value);
    results.value.createPost = response;
  } catch (err) {
    error.value = `POST /gateway/posts: ${err.message}`;
  }
};

const getCommentsByPost = async () => {
  try {
    error.value = '';
    const response = await gatewayApi.comments.getByPostId(commentPostId.value);
    results.value.getComments = response;
  } catch (err) {
    error.value = `GET /gateway/comments?post_id=${commentPostId.value}: ${err.message}`;
  }
};

const createComment = async () => {
  try {
    error.value = '';
    const response = await gatewayApi.comments.create({
      post_id: newComment.value.postId,
      body: newComment.value.body,
    });
    results.value.createComment = response;
  } catch (err) {
    error.value = `POST /gateway/comments: ${err.message}`;
  }
};
</script>
