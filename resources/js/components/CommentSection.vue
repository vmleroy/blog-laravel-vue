<template>
  <div class="border-t pt-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Comentários</h3>

    <div class="mb-6">
      <form @submit.prevent="submitComment">
        <textarea
          v-model="newComment"
          placeholder="Adicione um comentário..."
          class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
          rows="3"
        ></textarea>
        <button
          type="submit"
          :disabled="!newComment.trim() || loading"
          class="bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg transition"
        >
          {{ loading ? 'Enviando...' : 'Comentar' }}
        </button>
        <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
      </form>
    </div>

    <div class="space-y-4">
      <div v-if="comments.length === 0" class="text-gray-500 text-center py-4">
        Nenhum comentário ainda
      </div>

      <div
        v-for="comment in comments"
        :key="comment.id"
        class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg"
      >
        <div class="flex justify-between items-start mb-2">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ formatDate(comment.createdAt) }}
          </p>
          <span
            v-if="isArchived(comment.createdAt)"
            class="text-xs bg-gray-400 text-white px-2 py-1 rounded"
          >
            Arquivado
          </span>
        </div>
        <p class="text-gray-800 dark:text-gray-200">{{ formatBody(comment.body) }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  postId: Number,
});

const comments = ref([]);
const newComment = ref('');
const loading = ref(false);
const error = ref('');

onMounted(async () => {
  await fetchComments();
});

const fetchComments = async () => {
  try {
    const response = await window.axios.get(`/api/v1/posts/${props.postId}/comments`);
    comments.value = response.data;
  } catch (err) {
    console.error('Erro ao carregar comentários:', err);
  }
};

const submitComment = async () => {
  if (!newComment.value.trim()) return;

  loading.value = true;
  error.value = '';

  try {
    const response = await window.axios.post(
      `/api/v1/posts/${props.postId}/comments`,
      { body: newComment.value }
    );
    comments.value.push(response.data);
    newComment.value = '';
  } catch (err) {
    error.value = err.response?.data?.message || 'Erro ao adicionar comentário';
  } finally {
    loading.value = false;
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const formatBody = (body) => {
  return body.replace(/</g, '&lt;').replace(/>/g, '&gt;');
};

const isArchived = (date) => {
  const now = new Date();
  const commentDate = new Date(date);
  const diffTime = Math.abs(now - commentDate);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays > 30;
};
</script>
