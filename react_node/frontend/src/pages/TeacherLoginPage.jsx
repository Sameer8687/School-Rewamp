/**
 * Teacher Login Page — exact replica of CI3 `teacher_login.php`
 *
 * Mapped to URLs:
 *   /teacher_login
 *   /teacher-login
 *
 * Target: Teacher_login::validate() → spriden where user_type='tc'
 * AJAX: Teacher_login::getAssignedClasses() → spatesd
 */
import React, { useState, useEffect } from 'react';
import { useNavigate, useLocation, Link } from 'react-router-dom';
import useAuthStore from '../store/useAuthStore.js';
import { fetchTeacherClasses } from '../services/authService.js';
import { FiAward, FiLock, FiCheckSquare, FiLogIn } from 'react-icons/fi';

export default function TeacherLoginPage() {
  const navigate = useNavigate();
  const location = useLocation();
  const { loginTeacher, isLoading, error, clearError, isAuthenticated } = useAuthStore();

  const savedRedirect =
    typeof window !== 'undefined' ? sessionStorage.getItem('redirect_after_login') : null;
  const from =
    savedRedirect ||
    (location.state?.from?.pathname
      ? `${location.state.from.pathname}${location.state.from.search || ''}`
      : '/dashboard');

  const [teacherId, setTeacherId] = useState('');
  const [password, setPassword] = useState('');
  const [classType, setClassType] = useState('');
  const [classes, setClasses] = useState([]);
  const [isLoadingClasses, setIsLoadingClasses] = useState(false);

  // Clear errors on mount
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

  // Fetch teacher assigned classes on blur
  const handleTeacherIdBlur = async () => {
    const trimmed = teacherId.trim();
    if (!trimmed) {
      setClasses([]);
      return;
    }

    setIsLoadingClasses(true);
    try {
      const res = await fetchTeacherClasses(trimmed);
      if (res.status && Array.isArray(res.data)) {
        setClasses(res.data);
        if (res.data.length > 0) {
          // Pre-select first class if not already set
          const firstVal = `${res.data[0].SPATESD_std}-${res.data[0].SPATESD_div}`;
          setClassType(firstVal);
        } else {
          setClassType('Assigned');
        }
      }
    } catch (err) {
      console.error('Failed to load teacher classes:', err);
    } finally {
      setIsLoadingClasses(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!teacherId.trim() || !password) return;

    const ok = await loginTeacher({
      username: teacherId.trim(),
      password,
      classType: classType || 'Assigned',
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
            <div className="text-center mb-6">
              <div className="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-tr from-[#1976d2] to-[#1565c0] shadow-lg shadow-sky-900/20 text-white mb-4">
                <span className="text-3xl font-bold tracking-tight">OES</span>
              </div>
              <h1 className="text-2xl font-bold text-slate-800 tracking-tight">
                Olive English School
              </h1>
              <h2 className="text-sm font-semibold uppercase tracking-wider text-[#1976d2] mt-2">
                Teacher Login
              </h2>
            </div>

            {/* Error Banner */}
            {error && (
              <div
                className="mb-6 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-sm flex items-start gap-3 animate-shake"
                role="alert"
              >
                <div className="w-2 h-2 rounded-full bg-red-500 mt-1.5 shrink-0" />
                <span className="flex-1 font-medium">{error}</span>
              </div>
            )}

            {/* Form */}
            <form onSubmit={handleSubmit} className="space-y-4">
              {/* Teacher ID */}
              <div>
                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">
                  Teacher ID
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <FiAward size={18} />
                  </div>
                  <input
                    type="text"
                    name="username"
                    value={teacherId}
                    onChange={(e) => setTeacherId(e.target.value)}
                    onBlur={handleTeacherIdBlur}
                    placeholder="e.g. E20250017"
                    required
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-4 focus:ring-[#1976d2]/15 outline-none transition-all text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white"
                  />
                </div>
              </div>

              {/* Password */}
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
                    required
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-4 focus:ring-[#1976d2]/15 outline-none transition-all text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white"
                  />
                </div>
              </div>

              {/* Select Class (Dynamic Dropdown) */}
              <div>
                <div className="flex items-center justify-between mb-1.5">
                  <label className="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                    Select Class
                  </label>
                  {isLoadingClasses && (
                    <span className="text-xs text-[#1976d2] animate-pulse">Loading classes...</span>
                  )}
                </div>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <FiCheckSquare size={18} />
                  </div>
                  <select
                    name="class_type"
                    value={classType}
                    onChange={(e) => setClassType(e.target.value)}
                    required
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-4 focus:ring-[#1976d2]/15 outline-none transition-all text-sm text-slate-800 bg-slate-50/50 focus:bg-white cursor-pointer"
                  >
                    <option value="">Select Class</option>
                    {classes.map((cls) => {
                      const val = `${cls.SPATESD_std}-${cls.SPATESD_div}`;
                      return (
                        <option key={cls.SPATESD_id} value={val}>
                          Class {cls.SPATESD_std} - {cls.SPATESD_div}
                        </option>
                      );
                    })}
                    {classes.length === 0 && (
                      <option value="Assigned">Assigned Class</option>
                    )}
                  </select>
                </div>
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={isLoading}
                className="w-full mt-2 py-3 px-4 rounded-xl text-white font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-60 flex items-center justify-center gap-2 cursor-pointer"
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

            {/* Quick Links */}
            <div className="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-600">
              <Link to="/login" className="text-[#1976d2] hover:underline">
                &larr; Admin Login
              </Link>
              <Link to="/student_login" className="text-slate-500 hover:text-[#1976d2]">
                Student Login &rarr;
              </Link>
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
