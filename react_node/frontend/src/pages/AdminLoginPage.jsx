/**
 * Admin Login Page — exact replica of CI3 `login.php`
 *
 * Mapped to URLs:
 *   /login
 *   /admin/login
 *   /admin_login
 *
 * Target: Login::validate() → spruser table
 */
import React, { useState, useEffect } from 'react';
import { useNavigate, useLocation, Link } from 'react-router-dom';
import useAuthStore from '../store/useAuthStore.js';
import { FiUser, FiLock, FiLogIn } from 'react-icons/fi';

export default function AdminLoginPage() {
  const navigate = useNavigate();
  const location = useLocation();
  const { loginAdmin, isLoading, error, clearError, isAuthenticated } = useAuthStore();

  const savedRedirect =
    typeof window !== 'undefined' ? sessionStorage.getItem('redirect_after_login') : null;
  const from =
    savedRedirect ||
    (location.state?.from?.pathname
      ? `${location.state.from.pathname}${location.state.from.search || ''}`
      : '/dashboard');

  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [rememberMe, setRememberMe] = useState(true);

  // Clear previous errors on mount
  useEffect(() => {
    clearError();
  }, [clearError]);

  // If already authenticated, redirect to destination
  useEffect(() => {
    if (isAuthenticated) {
      if (savedRedirect) {
        try {
          sessionStorage.removeItem('redirect_after_login');
        } catch {
          // ignore
        }
      }
      navigate(from, { replace: true });
    }
  }, [isAuthenticated, navigate, from, savedRedirect]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!username.trim() || !password) return;

    const ok = await loginAdmin({
      username: username.trim(),
      password,
    });

    if (ok) {
      navigate(from, { replace: true });
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-100 via-slate-50 to-slate-200 px-4 py-8">
      <div className="w-full max-w-md">
        {/* Card */}
        <div className="bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">
          <div className="p-8 sm:p-10">
            {/* School Logo & Title */}
            <div className="text-center mb-8">
              <div className="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-tr from-[#1976d2] to-[#1565c0] shadow-lg shadow-sky-900/20 text-white mb-4">
                <span className="text-3xl font-bold tracking-tight">OES</span>
              </div>
              <h1 className="text-2xl font-bold text-slate-800 tracking-tight">
                Olive English School
              </h1>
              <p className="text-xs font-semibold uppercase tracking-wider text-[#1976d2] mt-1">
                Admin & Staff Portal
              </p>
            </div>

            {/* Error Banner — mirrors CI3 $message flashdata */}
            {error && (
              <div
                className="mb-6 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-sm flex items-start gap-3 animate-shake"
                role="alert"
              >
                <div className="w-2 h-2 rounded-full bg-red-500 mt-1.5 shrink-0" />
                <span className="flex-1 font-medium">{error}</span>
              </div>
            )}

            {/* Login Form */}
            <form onSubmit={handleSubmit} className="space-y-5">
              {/* Username Input */}
              <div>
                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">
                  Username
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <FiUser size={18} />
                  </div>
                  <input
                    type="text"
                    name="username"
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                    placeholder="Enter admin username"
                    autoComplete="username"
                    required
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-4 focus:ring-[#1976d2]/15 outline-none transition-all text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white"
                  />
                </div>
              </div>

              {/* Password Input */}
              <div>
                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">
                  Password
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <FiLock size={18} />
                  </div>
                  <input
                    type="password"
                    name="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    placeholder="Enter password"
                    autoComplete="current-password"
                    required
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-4 focus:ring-[#1976d2]/15 outline-none transition-all text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white"
                  />
                </div>
              </div>

              {/* Remember Me & Reset */}
              <div className="flex items-center justify-between text-xs pt-1">
                <label className="flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                  <input
                    type="checkbox"
                    checked={rememberMe}
                    onChange={(e) => setRememberMe(e.target.checked)}
                    className="w-4 h-4 rounded text-[#1976d2] border-slate-300 focus:ring-[#1976d2]"
                  />
                  <span>Remember Me</span>
                </label>
                <a
                  href="#reset"
                  onClick={(e) => {
                    e.preventDefault();
                    alert('Please contact the system administrator to reset your credentials.');
                  }}
                  className="font-medium text-[#1976d2] hover:underline"
                >
                  Forgot password? Reset
                </a>
              </div>

              {/* Submit Button (CI3 .myLoginButton style) */}
              <button
                type="submit"
                disabled={isLoading}
                className="w-full py-3 px-4 rounded-xl text-white font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-60 flex items-center justify-center gap-2 cursor-pointer"
                style={{ backgroundColor: '#1976d2' }}
                onMouseEnter={(e) => (e.currentTarget.style.backgroundColor = '#47678a')}
                onMouseLeave={(e) => (e.currentTarget.style.backgroundColor = '#1976d2')}
              >
                {isLoading ? (
                  <>
                    <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    <span>Signing in...</span>
                  </>
                ) : (
                  <>
                    <FiLogIn size={18} />
                    <span>Login</span>
                  </>
                )}
              </button>
            </form>

            {/* Role Navigation Links — keeping them strictly separate while easily accessible */}
            <div className="mt-8 pt-6 border-t border-slate-100">
              <p className="text-center text-xs text-slate-500 mb-3 font-medium">
                Looking for another portal?
              </p>
              <div className="grid grid-cols-2 gap-3 text-xs">
                <Link
                  to="/teacher_login"
                  className="py-2.5 px-3 rounded-lg border border-slate-200 hover:border-[#1976d2] bg-slate-50 hover:bg-white text-slate-700 text-center font-medium transition-all"
                >
                  👩‍🏫 Teacher Login
                </Link>
                <Link
                  to="/student_login"
                  className="py-2.5 px-3 rounded-lg border border-slate-200 hover:border-[#1976d2] bg-slate-50 hover:bg-white text-slate-700 text-center font-medium transition-all"
                >
                  🎓 Student Login
                </Link>
              </div>
            </div>
          </div>
        </div>

        <p className="text-center text-xs text-slate-500 mt-6">
          &copy; {new Date().getFullYear()} Olive English School. All rights reserved.
        </p>
      </div>
    </div>
  );
}
