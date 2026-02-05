<template>
  <div class="border-t pt-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Comments</h3>

    <div v-if="currentUser" class="mb-6">
      <form @submit.prevent="submitComment" class="space-y-2">
        <Input
          id="new-comment"
          v-model="newComment"
          type="textarea"
          placeholder="Add a comment..."
          :rows="3"
          :error="error"
        />
        <Button
          type="submit"
          :disabled="!newComment.trim() || loading"
        >
          {{ loading ? 'Posting...' : 'Comment' }}
        </Button>
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
            <Badge v-if="isArchived(comment.created_at)" variant="default" size="sm">
              Archived
            </Badge>
            <div class="flex gap-2">
              <Button
                v-if="canEditComment(comment)"
                @click="editingCommentId = comment.id; editingCommentBody = comment.body"
                size="sm"
              >
                Edit
              </Button>
              <Button
                v-if="canDeleteComment(comment)"
                @click="deleteCommentHandler(comment.id)"
                variant="danger"
                size="sm"
              >
                Delete
              </Button>
            </div>
          </div>
        </div>
        <div v-if="editingCommentId === comment.id" class="mt-3 space-y-2">
          <Input
            id="edit-comment"
            v-model="editingCommentBody"
            type="textarea"
            :rows="2"
          />
          <div class="flex gap-2">
            <Button
              @click="saveEditComment(comment.id)"
              variant="primary"
              size="sm"
            >
              Save
            </Button>
            <Button
              @click="editingCommentId = null"
              variant="secondary"
              size="sm"
            >
              Cancel
            </Button>
          </div>
        </div>
        <p v-else class="text-gray-800 dark:text-gray-200">{{ formatBody(comment.body) }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '../../composables/useAuth.js';
import Button from '../ui/Button.vue';
import Input from '../ui/Input.vue';
import Badge from '../ui/Badge.vue';
import { useComments } from '../../composables/useComments.js';

const props = defineProps({
  postId: Number,
});

const { currentUser } = useAuth();
const { comments, createComment, updateComment, deleteComment, fetchCommentsByPost } = useComments();

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
    await fetchCommentsByPost(props.postId);
  } catch (err) {
    console.error('Error loading comments:', err);
  }
};

const submitComment = async () => {
  if (!newComment.value.trim()) return;

  loading.value = true;
  error.value = '';

  try {
    await createComment(props.postId, newComment.value);
    newComment.value = '';
  } catch (err) {
    error.value = err.message || 'Error adding comment';
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
    await updateComment(commentId, editingCommentBody.value);
    editingCommentId.value = null;
    editingCommentBody.value = '';
  } catch (err) {
    console.error('Error updating comment:', err);
  }
};

const deleteCommentHandler = async (commentId) => {
  if (confirm('Are you sure you want to delete this comment?')) {
    try {
      await deleteComment(commentId);
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
