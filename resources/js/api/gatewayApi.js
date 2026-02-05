/**
 * API Gateway Client
 * Centralized client for all gateway API calls
 */

class GatewayApi {
  constructor(baseURL = '/api/gateway') {
    this.baseURL = baseURL;
    this.token = this.getStoredToken();
  }

  /**
   * Set authorization token
   */
  setToken(token) {
    this.token = token;
    localStorage.setItem('gateway_token', token);
  }

  /**
   * Get stored token from localStorage
   */
  getStoredToken() {
    return localStorage.getItem('gateway_token');
  }

  /**
   * Get authorization headers
   */
  getHeaders() {
    const headers = {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    };

    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }

    return headers;
  }

  /**
   * Make API request
   */
  async request(method, endpoint, data = null) {
    try {
      const url = `${this.baseURL}${endpoint}`;
      const options = {
        method,
        headers: this.getHeaders(),
      };

      if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
        options.body = JSON.stringify(data);
      }

      const response = await fetch(url, options);

      if (!response.ok) {
        const error = await response.json();
        throw new Error(error.error || `HTTP ${response.status}`);
      }

      // Handle 204 No Content
      if (response.status === 204) {
        return null;
      }

      return await response.json();
    } catch (error) {
      console.error(`API Error [${method} ${endpoint}]:`, error);
      throw error;
    }
  }

  /**
   * GET request
   */
  get(endpoint) {
    return this.request('GET', endpoint);
  }

  /**
   * POST request
   */
  post(endpoint, data) {
    return this.request('POST', endpoint, data);
  }

  /**
   * PUT request
   */
  put(endpoint, data) {
    return this.request('PUT', endpoint, data);
  }

  /**
   * DELETE request
   */
  delete(endpoint) {
    return this.request('DELETE', endpoint);
  }

  /**
   * ===========================
   * AUTH ENDPOINTS
   * ===========================
   */

  auth = {
    login: (email, password) =>
      this.post('/auth/login', { email, password }),

    register: (name, email, password) =>
      this.post('/auth/register', { name, email, password }),

    refresh: (token) =>
      this.post('/auth/refresh', { token }),

    logout: () =>
      this.post('/auth/logout', {}),
  };

  /**
   * ===========================
   * POSTS ENDPOINTS
   * ===========================
   */

  posts = {
    getAll: () =>
      this.get('/posts'),

    getById: (id) =>
      this.get(`/posts/${id}`),

    create: (data) =>
      this.post('/posts', data),

    update: (id, data) =>
      this.put(`/posts/${id}`, data),

    delete: (id) =>
      this.delete(`/posts/${id}`),
  };

  /**
   * ===========================
   * COMMENTS ENDPOINTS
   * ===========================
   */

  comments = {
    getAll: (postId = null) =>
      postId ? this.get(`/comments?post_id=${postId}`) : this.get('/comments'),

    getByPostId: (postId) =>
      this.get(`/comments?post_id=${postId}`),

    getById: (id) =>
      this.get(`/comments/${id}`),

    create: (data) =>
      this.post('/comments', data),

    update: (id, data) =>
      this.put(`/comments/${id}`, data),

    delete: (id) =>
      this.delete(`/comments/${id}`),
  };

  /**
   * ===========================
   * RBAC ENDPOINTS
   * ===========================
   */

  rbac = {
    createRole: (name, description) =>
      this.post('/rbac/roles', { name, description }),

    assignRole: (userId, roleId) =>
      this.post('/rbac/roles/assign', { user_id: userId, role_id: roleId }),

    getUserRole: (userId) =>
      this.get(`/rbac/users/${userId}/role`),

    checkAccess: (userId, resource, action) =>
      this.post('/rbac/check-access', { user_id: userId, resource, action }),
  };
}

// Create and export singleton instance
const gatewayApi = new GatewayApi(
  import.meta.env.VITE_API_GATEWAY_URL || '/api/gateway'
);

export default gatewayApi;
