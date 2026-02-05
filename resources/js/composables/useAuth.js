import { ref, computed } from 'vue';
import gatewayApi from '../api/gatewayApi.js';

const user = ref(null);
const token = ref(null);
const isLoading = ref(false);
const error = ref(null);

export const useAuth = () => {
  const isAuthenticated = computed(() => !!token.value);
  const currentUser = computed(() => user.value);

  const initializeAuth = () => {
    const storedToken = localStorage.getItem('gateway_token');
    const storedUser = localStorage.getItem('gateway_user');

    if (storedToken && storedUser) {
      token.value = storedToken;
      user.value = JSON.parse(storedUser);
      gatewayApi.setToken(storedToken);
    }
  };

  const setAuth = (authData) => {
    user.value = {
      id: authData.user_id,
      name: authData.name,
      email: authData.email,
      role: authData.role,
    };
    token.value = authData.token;
    localStorage.setItem('gateway_token', authData.token);
    localStorage.setItem('gateway_user', JSON.stringify(user.value));
    gatewayApi.setToken(authData.token);
  };

  const login = async (email, password) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.auth.login(email, password);
      setAuth(response);
      return response;
    } catch (err) {
      error.value = err.message || 'Login failed';
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  const register = async (name, email, password) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.auth.register(name, email, password);
      setAuth(response);
      return response;
    } catch (err) {
      error.value = err.message || 'Registration failed';
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  const refreshToken = async () => {
    if (!token.value) {
      throw new Error('No token to refresh');
    }

    isLoading.value = true;
    error.value = null;

    try {
      const response = await gatewayApi.auth.refresh(token.value);
      setAuth(response);
      return response;
    } catch (err) {
      error.value = err.message || 'Token refresh failed';
      // Clear auth on refresh failure
      logout();
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  const logout = async () => {
    try {
      await gatewayApi.auth.logout();
    } catch (err) {
      console.error('Error during logout:', err);
    }

    user.value = null;
    token.value = null;
    localStorage.removeItem('gateway_token');
    localStorage.removeItem('gateway_user');
    gatewayApi.setToken(null);
  };

  return {
    isAuthenticated,
    currentUser,
    user,
    token,
    isLoading,
    error,
    initializeAuth,
    setAuth,
    login,
    register,
    refreshToken,
    logout,
  };
};
