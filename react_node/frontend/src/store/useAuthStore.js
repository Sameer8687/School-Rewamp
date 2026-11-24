/**
 * Zustand auth store.
 * Replaces CI3's session userdata:
 *   oliveSchoolDashboardUser (admin)
 *   oliveMobileSystemUser   (teacher)
 *   StudentProfileSession   (student)
 */
import { create } from 'zustand';
import {
  loginAdmin as apiLoginAdmin,
  loginTeacher as apiLoginTeacher,
  loginStudent as apiLoginStudent,
  loginStudentWithToken as apiLoginStudentWithToken,
  loginUser,
  getMe,
  logoutUser,
} from '../services/authService.js';

function handleAuthSuccess(res, set) {
  if (res.success && res.token && res.user) {
    localStorage.setItem('olive_token', res.token);
    try {
      localStorage.setItem('olive_user', JSON.stringify(res.user));
    } catch {
      // ignore
    }
    set({
      user: res.user,
      token: res.token,
      isAuthenticated: true,
      isLoading: false,
      error: null,
    });
    return true;
  } else {
    set({ isLoading: false, error: res.message || 'Login failed.' });
    return false;
  }
}

function handleAuthError(err, set) {
  const msg = err.response?.data?.message || err.message || 'Network error.';
  set({ isLoading: false, error: msg });
  return false;
}

const getStoredUser = () => {
  try {
    const raw = localStorage.getItem('olive_user');
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
};

const initialToken = typeof window !== 'undefined' ? localStorage.getItem('olive_token') : null;
const initialUser = typeof window !== 'undefined' ? getStoredUser() : null;

const useAuthStore = create((set) => ({
  user: initialUser,
  token: initialToken,
  isAuthenticated: Boolean(initialToken),
  isLoading: false,
  error: null,

  loginAdmin: async (credentials) => {
    set({ isLoading: true, error: null });
    try {
      const res = await apiLoginAdmin(credentials);
      return handleAuthSuccess(res, set);
    } catch (err) {
      return handleAuthError(err, set);
    }
  },

  loginTeacher: async (credentials) => {
    set({ isLoading: true, error: null });
    try {
      const res = await apiLoginTeacher(credentials);
      return handleAuthSuccess(res, set);
    } catch (err) {
      return handleAuthError(err, set);
    }
  },

  loginStudent: async (credentials) => {
    set({ isLoading: true, error: null });
    try {
      const res = await apiLoginStudent(credentials);
      return handleAuthSuccess(res, set);
    } catch (err) {
      return handleAuthError(err, set);
    }
  },

  loginStudentWithToken: async (studentId, token) => {
    set({ isLoading: true, error: null });
    try {
      const res = await apiLoginStudentWithToken(studentId, token);
      return handleAuthSuccess(res, set);
    } catch (err) {
      return handleAuthError(err, set);
    }
  },

  login: async (payload) => {
    set({ isLoading: true, error: null });
    try {
      const res = await loginUser(payload);
      return handleAuthSuccess(res, set);
    } catch (err) {
      return handleAuthError(err, set);
    }
  },

  checkAuth: async () => {
    const token = localStorage.getItem('olive_token');
    if (!token) {
      localStorage.removeItem('olive_user');
      set({ isAuthenticated: false, user: null, token: null, isLoading: false });
      return;
    }
    const hasCachedUser = Boolean(localStorage.getItem('olive_user'));
    if (!hasCachedUser) {
      set({ isLoading: true });
    }
    try {
      const res = await getMe();
      if (res.success && res.user) {
        localStorage.setItem('olive_user', JSON.stringify(res.user));
        set({ user: res.user, token, isAuthenticated: true, isLoading: false });
      } else {
        localStorage.removeItem('olive_token');
        localStorage.removeItem('olive_user');
        set({ isAuthenticated: false, user: null, token: null, isLoading: false });
      }
    } catch (err) {
      if (err.response?.status === 401) {
        localStorage.removeItem('olive_token');
        localStorage.removeItem('olive_user');
        set({ isAuthenticated: false, user: null, token: null, isLoading: false });
      } else {
        // Keep active session on transient network errors
        set({ isLoading: false });
      }
    }
  },

  logout: async () => {
    try {
      await logoutUser();
    } catch {
      // Proceed even if server call fails
    }
    localStorage.removeItem('olive_token');
    localStorage.removeItem('olive_user');
    set({ user: null, token: null, isAuthenticated: false, isLoading: false });
  },

  clearError: () => set({ error: null }),
}));

export default useAuthStore;
