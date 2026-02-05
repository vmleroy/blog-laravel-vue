import { ref, computed } from 'vue';

const user = ref(null);
const token = ref(null);

export const useAuth = () => {
  const isAuthenticated = computed(() => !!token.value);
  const currentUser = computed(() => user.value);

  const initializeAuth = () => {
    const storedToken = localStorage.getItem('token');
    const storedUser = localStorage.getItem('user');

    if (storedToken && storedUser) {
      token.value = storedToken;
      user.value = JSON.parse(storedUser);
      window.axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
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
    localStorage.setItem('token', authData.token);
    localStorage.setItem('user', JSON.stringify(user.value));
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${authData.token}`;
  };

  const logout = async () => {
    try {
      await window.axios.post('/api/v1/auth/logout', {
        token: token.value,
      });
    } catch (err) {
      console.error('Erro ao fazer logout:', err);
    }

    user.value = null;
    token.value = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    delete window.axios.defaults.headers.common['Authorization'];
  };

  return {
    isAuthenticated,
    currentUser,
    user,
    token,
    initializeAuth,
    setAuth,
    logout,
  };
};
