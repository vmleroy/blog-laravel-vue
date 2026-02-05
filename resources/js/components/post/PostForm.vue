<template>
  <form id="post-form" @submit.prevent="handleSubmit" class="space-y-4">
    <Input
      id="title"
      v-model="formData.title"
      label="Title"
      type="text"
      placeholder="Post title"
      :error="errors.title"
      required
    />

    <Input
      id="content"
      v-model="formData.content"
      label="Content"
      type="textarea"
      placeholder="Post content (minimum 10 characters)"
      :error="errors.content"
      :hint="`${formData.content.length} characters`"
      :rows="6"
      required
    />
  </form>
</template>

<script setup>
import { ref, reactive } from 'vue';
import Input from '../ui/Input.vue';
import { useNotification } from '../../composables/useNotification.js';

const props = defineProps({
  post: Object,
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'saved']);

const { success, error } = useNotification();
const loading = ref(false);
const errors = reactive({});

const formData = ref({
  title: props.post?.title || '',
  content: props.post?.content || '',
});

const handleSubmit = async () => {
  // Reset errors
  Object.keys(errors).forEach((key) => {
    delete errors[key];
  });

  loading.value = true;

  try {
    const url = props.isEditing ? `/api/v1/posts/${props.post.id}` : '/api/v1/posts';
    const method = props.isEditing ? 'put' : 'post';

    const response = await window.axios[method](url, formData.value);

    success(props.isEditing ? 'Post updated successfully!' : 'Post created successfully!');
    emit('saved', response.data);
  } catch (err) {
    if (err.response?.data?.errors) {
      // Laravel returns errors as arrays, extract first message for each field
      const validationErrors = err.response.data.errors;
      Object.keys(validationErrors).forEach((key) => {
        errors[key] = Array.isArray(validationErrors[key]) 
          ? validationErrors[key][0] 
          : validationErrors[key];
      });
    } else {
      error(err.response?.data?.error || 'Error saving post');
    }
  } finally {
    loading.value = false;
  }
};
</script>
