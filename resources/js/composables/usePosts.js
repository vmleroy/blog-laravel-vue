/**
 * Posts Composable
 * Handles all posts-related API calls via the gateway
 */

import { ref, computed } from 'vue';
import gatewayApi from '../api/gatewayApi.js';

export const usePosts = () => {
  const posts = ref([]);
  const currentPost = ref(null);
  const isLoading = ref(false);
  const error = ref(null);

  const postCount = computed(() => posts.value.length);
  const hasPosts = computed(() => posts.value.length > 0);

  /**
   * Fetch all posts
   */
  const fetchPosts = async () => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.posts.getAll();
      posts.value = Array.isArray(response) ? response : [];
      return posts.value;
    } catch (err) {
      error.value = err.message || 'Failed to fetch posts';
      console.error('Error fetching posts:', err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Fetch a single post by ID
   */
  const fetchPostById = async (id) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.posts.getById(id);
      currentPost.value = response;
      return response;
    } catch (err) {
      error.value = err.message || `Failed to fetch post ${id}`;
      console.error(`Error fetching post ${id}:`, err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Create a new post
   */
  const createPost = async (title, content) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.posts.create({ title, content });
      posts.value.push(response);
      return response;
    } catch (err) {
      error.value = err.message || 'Failed to create post';
      console.error('Error creating post:', err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Update a post
   */
  const updatePost = async (id, data) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.posts.update(id, data);

      // Update in local array
      const index = posts.value.findIndex((p) => p.id === id);
      if (index !== -1) {
        posts.value[index] = response;
      }

      currentPost.value = response;
      return response;
    } catch (err) {
      error.value = err.message || `Failed to update post ${id}`;
      console.error(`Error updating post ${id}:`, err);
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  /**
   * Delete a post
   */
  const deletePost = async (id) => {
    isLoading.value = true;
    error.value = null;

    try {
      await gatewayApi.posts.delete(id);

      // Remove from local array
      posts.value = posts.value.filter((p) => p.id !== id);

      if (currentPost.value?.id === id) {
        currentPost.value = null;
      }

      return true;
    } catch (err) {
      error.value = err.message || `Failed to delete post ${id}`;
      console.error(`Error deleting post ${id}:`, err);
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
    posts,
    currentPost,
    isLoading,
    error,
    postCount,
    hasPosts,
    fetchPosts,
    fetchPostById,
    createPost,
    updatePost,
    deletePost,
    clearError,
  };
};
