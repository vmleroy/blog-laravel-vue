<template>
  <div>
    <!-- Tab Navigation -->
    <div class="flex gap-4 mb-6">
      <Button
        @click="isLogin = true"
        :variant="isLogin ? 'primary' : 'secondary'"
        full-width
      >
        Sign In
      </Button>
      <Button
        @click="isLogin = false"
        :variant="!isLogin ? 'primary' : 'secondary'"
        full-width
      >
        Register
      </Button>
    </div>

    <!-- Login Form -->
    <form v-if="isLogin" @submit.prevent="handleLogin" class="space-y-4">
      <Input
        id="login-email"
        v-model="loginForm.email"
        label="Email"
        type="email"
        placeholder="your@email.com"
        required
      />

      <Input
        id="login-password"
        v-model="loginForm.password"
        label="Password"
        type="password"
        placeholder="••••••••"
        required
      />

      <!-- Error Message -->
      <div v-if="error" class="p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg text-sm">
        {{ error }}
      </div>

      <!-- Success Message -->
      <div v-if="success" class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg text-sm">
        {{ success }}
      </div>

      <!-- Demo Credentials Info -->
      <div class="p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg text-sm text-blue-700 dark:text-blue-200">
        <p class="font-medium mb-2">Demo Credentials:</p>
        <ul class="space-y-1 text-xs">
          <li><strong>Admin:</strong> admin@example.com / password123</li>
          <li><strong>User:</strong> joao@example.com / password123</li>
          <li><strong>User:</strong> maria@example.com / password123</li>
        </ul>
      </div>

      <Button type="submit" :disabled="loading" full-width>
        {{ loading ? 'Signing in...' : 'Sign In' }}
      </Button>
    </form>

    <!-- Register Form -->
    <form v-else @submit.prevent="handleRegister" class="space-y-4">
      <Input
        id="register-name"
        v-model="registerForm.name"
        label="Full Name"
        type="text"
        placeholder="Your name"
        required
      />

      <Input
        id="register-email"
        v-model="registerForm.email"
        label="Email"
        type="email"
        placeholder="your@email.com"
        required
      />

      <Input
        id="register-password"
        v-model="registerForm.password"
        label="Password"
        type="password"
        placeholder="••••••••"
        required
      />

      <Input
        id="register-password-confirm"
        v-model="registerForm.password_confirmation"
        label="Confirm Password"
        type="password"
        placeholder="••••••••"
        required
      />

      <!-- Error Message -->
      <div v-if="error" class="p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg text-sm">
        {{ error }}
      </div>

      <!-- Success Message -->
      <div v-if="success" class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg text-sm">
        {{ success }}
      </div>

      <!-- Demo Credentials Info -->
      <div class="p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg text-sm text-blue-700 dark:text-blue-200">
        <p class="font-medium mb-2">Demo Credentials:</p>
        <ul class="space-y-1 text-xs">
          <li><strong>Admin:</strong> admin@example.com / password123</li>
          <li><strong>User:</strong> joao@example.com / password123</li>
          <li><strong>User:</strong> maria@example.com / password123</li>
        </ul>
      </div>

      <Button type="submit" :disabled="loading" full-width>
        {{ loading ? 'Registering...' : 'Register' }}
      </Button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Button from '../ui/Button.vue';
import Input from '../ui/Input.vue';
import { useAuth } from '../../composables/useAuth.js';

const emit = defineEmits(['login']);

const { login, register } = useAuth();

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
    const response = await login(loginForm.value.email, loginForm.value.password);
    emit('login', response);
  } catch (err) {
    error.value = err.message || 'Error signing in. Please check your credentials.';
  } finally {
    loading.value = false;
  }
};

const handleRegister = async () => {
  error.value = '';
  success.value = '';
  loading.value = true;

  try {
    const response = await register(
      registerForm.value.name,
      registerForm.value.email,
      registerForm.value.password
    );
    emit('login', response);
  } catch (err) {
    error.value = err.message || 'Error registering. Try again.';
  } finally {
    loading.value = false;
  }
};
</script>
