<template>
  <div class="border-t pt-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Comments</h3>

    <div v-if="currentUser" class="mb-6">
      <form @submit.prevent="submitComment">
        <textarea
          v-model="newComment"
          placeholder="Add a comment..."
          class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
          rows="3"
        ></textarea>
        <button
          type="submit"
          :disabled="!newComment.trim() || loading"
          class="bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg transition"
        >
          {{ loading ? 'Posting...' : 'Comment' }}
        </button>
        <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
      </form>
    </div>

    <div v-else class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
      <p class="text-sm text-blue-700 dark:text-blue-200">Sign in to comment</p>
    </div>

    <div class="space-y-4">
      <div v-if="comments.length === 0" class="text-gray-500 text-center py-4">
        No comments yet
      </div>

      <div
        v-for="comment in comments"
        :key="comment.id"
        class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg"
      >
        <div class="flex justify-between items-start mb-2">
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ comment.author_name }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(comment.created_at) }}</p>
          </div>
          <div class="flex gap-2 ml-4">
            <span
              v-if="isArchived(comment.created_at)"
              class="text-xs bg-gray-400 text-white px-2 py-1 rounded"
            >
              Archived
            </span>
            <div class="flex gap-2">
              <button
                v-if="canEditComment(comment)"
                @click="editingCommentId = comment.id; editingCommentBody = comment.body"
                class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition"
              >
                Edit
              </button>
              <button
                v-if="canDeleteComment(comment)"
                @click="deleteComment(comment.id)"
                class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
        <div v-if="editingCommentId === comment.id" class="mt-3 space-y-2">
          <textarea
            v-model="editingCommentBody"
            class="w-full p-2 border rounded dark:bg-gray-600 dark:border-gray-500 dark:text-white text-sm"
            rows="2"
          ></textarea>
          <div class="flex gap-2">
            <button
              @click="saveEditComment(comment.id)"
              class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded transition"
            >
              Save
            </button>
            <button
              @click="editingCommentId = null"
              class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white text-xs rounded transition"
            >
              Cancel
            </button>
          </div>
        </div>
        <p v-else class="text-gray-800 dark:text-gray-200">{{ formatBody(comment.body) }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '../composables/useAuth.js';

const props = defineProps({
  postId: Number,
});

const { currentUser } = useAuth();

const comments = ref([]);
const newComment = ref('');
const loading = ref(false);
const error = ref('');
const editingCommentId = ref(null);
const editingCommentBody = ref('');

onMounted(async () => {
  await fetchComments();
});

const fetchComments = async () => {
  try {
    const response = await window.axios.get(`/api/v1/posts/${props.postId}/comments`);
    comments.value = response.data;
  } catch (err) {
    console.error('Error loading comments:', err);
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
    error.value = err.response?.data?.message || 'Error adding comment';
  } finally {
    loading.value = false;
  }
};

const canEditComment = (comment) => {
  return currentUser.value?.id === comment.user_id;
};

const canDeleteComment = (comment) => {
  return currentUser.value?.id === comment.user_id || currentUser.value?.role === 'admin';
};

const isAdmin = () => {
  return currentUser.value?.role === 'admin';
};

const saveEditComment = async (commentId) => {
  if (!editingCommentBody.value.trim()) return;

  try {
    await window.axios.put(
      `/api/v1/posts/${props.postId}/comments/${commentId}`,
      { body: editingCommentBody.value }
    );
    const comment = comments.value.find(c => c.id === commentId);
    if (comment) {
      comment.body = editingCommentBody.value;
    }
    editingCommentId.value = null;
    editingCommentBody.value = '';
  } catch (err) {
    console.error('Error updating comment:', err);
  }
};

const deleteComment = async (commentId) => {
  if (confirm('Are you sure you want to delete this comment?')) {
    try {
      await window.axios.delete(
        `/api/v1/posts/${props.postId}/comments/${commentId}`
      );
      comments.value = comments.value.filter(c => c.id !== commentId);
    } catch (err) {
      console.error('Error deleting comment:', err);
    }
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
