<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-8 w-full max-w-md">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Laravel Blog</h1>
      <button
        @click="$emit('close')"
        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-4 mb-6">
      <button
        @click="isLogin = true"
        :class="isLogin ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
        class="flex-1 py-2 rounded-lg font-medium transition"
      >
        Sign In
      </button>
      <button
        @click="isLogin = false"
        :class="!isLogin ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
        class="flex-1 py-2 rounded-lg font-medium transition"
      >
        Register
      </button>
    </div>

    <!-- Login Form -->
    <form v-if="isLogin" @submit.prevent="handleLogin" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
        <input
          v-model="loginForm.email"
          type="email"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="your@email.com"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
        <input
          v-model="loginForm.password"
          type="password"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="••••••••"
          required
        />
      </div>

      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white py-2 rounded-lg font-medium transition"
      >
        {{ loading ? 'Signing in...' : 'Sign In' }}
      </button>
    </form>

    <!-- Register Form -->
    <form v-else @submit.prevent="handleRegister" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
        <input
          v-model="registerForm.name"
          type="text"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Your name"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
        <input
          v-model="registerForm.email"
          type="email"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="your@email.com"
          required
        />
      </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
          <input
            v-model="registerForm.password"
            type="password"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="••••••••"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
          <input
            v-model="registerForm.password_confirmation"
            type="password"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="••••••••"
            required
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white py-2 rounded-lg font-medium transition"
        >
          {{ loading ? 'Registering...' : 'Register' }}
        </button>
      </form>

      <!-- Error Message -->
      <div v-if="error" class="mt-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg text-sm">
        {{ error }}
      </div>

      <!-- Success Message -->
      <div v-if="success" class="mt-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg text-sm">
        {{ success }}
      </div>

      <!-- Demo Credentials Info -->
      <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg text-sm text-blue-700 dark:text-blue-200">
        <p class="font-medium mb-2">Demo Credentials:</p>
        <ul class="space-y-1 text-xs">
          <li><strong>Admin:</strong> admin@example.com / password123</li>
          <li><strong>User:</strong> joao@example.com / password123</li>
          <li><strong>User:</strong> maria@example.com / password123</li>
        </ul>
      </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['login']);

const isLogin = ref(true);
const loading = ref(false);
const error = ref('');
const success = ref('');

const loginForm = ref({
  email: 'admin@example.com',
  password: 'password123',
});

const registerForm = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const handleLogin = async () => {
  error.value = '';
  success.value = '';
  loading.value = true;

  try {
    const response = await window.axios.post('/api/v1/auth/login', loginForm.value);

    // Save token and user data
    localStorage.setItem('token', response.data.token);
    localStorage.setItem('user', JSON.stringify({
      id: response.data.user_id,
      name: response.data.name,
      email: response.data.email,
      role: response.data.role,
    }));

    // Add token to axios headers
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

    emit('login', response.data);
  } catch (err) {
    error.value = err.response?.data?.error || 'Error signing in. Please check your credentials.';
  } finally {
    loading.value = false;
  }
};

const handleRegister = async () => {
  error.value = '';
  success.value = '';
  loading.value = true;

  try {
    const response = await window.axios.post('/api/v1/auth/register', registerForm.value);

    // Save token and user data
    localStorage.setItem('token', response.data.token);
    localStorage.setItem('user', JSON.stringify({
      id: response.data.user_id,
      name: response.data.name,
      email: response.data.email,
      role: response.data.role,
    }));

    // Add token to axios headers
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

    emit('login', response.data);
  } catch (err) {
    error.value = err.response?.data?.error || 'Error registering. Try again.';
  } finally {
    loading.value = false;
  }
};
</script>
