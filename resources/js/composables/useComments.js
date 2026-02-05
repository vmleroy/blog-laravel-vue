/**
 * Comments Composable
 * Handles all comments-related API calls via the gateway
 */

import { ref, computed } from 'vue';
import gatewayApi from '../api/gatewayApi.js';

export const useComments = () => {
  const comments = ref([]);
  const currentComment = ref(null);
  const isLoading = ref(false);
  const error = ref(null);

  const commentCount = computed(() => comments.value.length);
  const hasComments = computed(() => comments.value.length > 0);

  /**
   * Fetch all comments (optionally filtered by post)
   */
  const fetchComments = async (postId = null) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = postId
        ? await gatewayApi.comments.getByPostId(postId)
        : await gatewayApi.comments.getAll();

      comments.value = Array.isArray(response) ? response : [];
      return comments.value;
    } catch (err) {
      error.value = err.message || 'Failed to fetch comments';
      console.error('Error fetching comments:', err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Fetch comments for a specific post
   */
  const fetchCommentsByPost = async (postId) => {
    return fetchComments(postId);
  };

  /**
   * Fetch a single comment by ID
   */
  const fetchCommentById = async (id) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.comments.getById(id);
      currentComment.value = response;
      return response;
    } catch (err) {
      error.value = err.message || `Failed to fetch comment ${id}`;
      console.error(`Error fetching comment ${id}:`, err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Create a new comment
   */
  const createComment = async (postId, body) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.comments.create({
        post_id: postId,
        body,
      });

      comments.value.push(response);
      return response;
    } catch (err) {
      error.value = err.message || 'Failed to create comment';
      console.error('Error creating comment:', err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Update a comment
   */
  const updateComment = async (id, body) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.comments.update(id, { body });

      // Update in local array
      const index = comments.value.findIndex((c) => c.id === id);
      if (index !== -1) {
        comments.value[index] = response;
      }

      currentComment.value = response;
      return response;
    } catch (err) {
      error.value = err.message || `Failed to update comment ${id}`;
      console.error(`Error updating comment ${id}:`, err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Delete a comment
   */
  const deleteComment = async (id) => {
    isLoading.value = true;
    error.value = null;

    try {
      await gatewayApi.comments.delete(id);

      // Remove from local array
      comments.value = comments.value.filter((c) => c.id !== id);

      if (currentComment.value?.id === id) {
        currentComment.value = null;
      }

      return true;
    } catch (err) {
      error.value = err.message || `Failed to delete comment ${id}`;
      console.error(`Error deleting comment ${id}:`, err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Clear error
   */
  const clearError = () => {
    error.value = null;
  };

  return {
    comments,
    currentComment,
    isLoading,
    error,
    commentCount,
    hasComments,
    fetchComments,
    fetchCommentsByPost,
    fetchCommentById,
    createComment,
    updateComment,
    deleteComment,
    clearError,
  };
};
