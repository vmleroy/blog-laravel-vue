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
import { usePosts } from '../../composables/usePosts.js';

const props = defineProps({
  post: Object,
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close', 'saved']);

const { success, error } = useNotification();
const { createPost, updatePost } = usePosts();
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
    let response;
    if (props.isEditing) {
      response = await updatePost(props.post.id, formData.value);
    } else {
      response = await createPost(formData.value.title, formData.value.content);
    }

    success(props.isEditing ? 'Post updated successfully!' : 'Post created successfully!');
    emit('saved', response);
  } catch (err) {
    error(err.message || (props.isEditing ? 'Error updating post' : 'Error creating post'));
  } finally {
    loading.value = false;
  }
};
</script>
