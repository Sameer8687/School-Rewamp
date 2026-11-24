/**
 * Axios instance pre-configured for the Express backend.
 * Mirrors the base_url() calls from CI3 views.
 */
import axios from 'axios';

const api = axios.create({
  baseURL: '/api',       // proxied via Vite in dev
  withCredentials: true,  // send HTTP-only cookies
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor: attach Bearer token if stored in localStorage
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('olive_token');
  if (token && config.headers) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor: handle 401 globally
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      const url = error.config?.url || '';
      // Don't treat login form validation failures as global session expiry
      const isLoginAttempt =
        url.includes('/auth/admin-login') ||
        url.includes('/auth/teacher-login') ||
        url.includes('/auth/student-login');

      if (!isLoginAttempt) {
        if (
          typeof window !== 'undefined' &&
          window.location.pathname !== '/login' &&
          window.location.pathname !== '/teacher_login' &&
          window.location.pathname !== '/student_login'
        ) {
          try {
            sessionStorage.setItem(
              'redirect_after_login',
              window.location.pathname + window.location.search
            );
          } catch {
            // ignore
          }
          localStorage.removeItem('olive_token');
          localStorage.removeItem('olive_user');
          window.location.href = '/login';
        } else {
          localStorage.removeItem('olive_token');
          localStorage.removeItem('olive_user');
        }
      }
    }
    return Promise.reject(error);
  }
);

export default api;
