/**
 * Student Login Page — exact replica of CI3 `student_login.php`
 *
 * Mapped to URLs:
 *   /student_login
 *   /student-login
 *
 * Target: Student_login::validate() → spriden where user_type='st'
 * Supports legacy token auth: /student_login?auth=true&student-id=...&token=...
 */
import React, { useState, useEffect } from 'react';
import { useNavigate, useLocation, useSearchParams, Link } from 'react-router-dom';
import useAuthStore from '../store/useAuthStore.js';
import { fetchAcademicYears } from '../services/authService.js';
import { FiAward, FiCalendar, FiUserCheck } from 'react-icons/fi';

export default function StudentLoginPage() {
  const navigate = useNavigate();
  const location = useLocation();
  const [searchParams] = useSearchParams();
  const { loginStudent, loginStudentWithToken, isLoading, error, clearError, isAuthenticated, user } =
    useAuthStore();

  const defaultDest = user?.user_type === 'st' ? '/profile' : '/dashboard';
  const savedRedirect =
    typeof window !== 'undefined' ? sessionStorage.getItem('redirect_after_login') : null;
  const from =
    savedRedirect ||
    (location.state?.from?.pathname
      ? `${location.state.from.pathname}${location.state.from.search || ''}`
      : defaultDest);

  const [studentId, setStudentId] = useState('');
  const [academicYear, setAcademicYear] = useState('');
  const [academicYears, setAcademicYears] = useState([]);
  const [isLoadingYears, setIsLoadingYears] = useState(false);

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
  }, [isAuthenticated, from, navigate, savedRedirect]);

  // Load academic years (mirrors CI3 Student_login::index)
  useEffect(() => {
    async function loadYears() {
      setIsLoadingYears(true);
      try {
        const res = await fetchAcademicYears();
        if (res.success && res.data) {
          setAcademicYears(res.data.academicYears || []);
          if (res.data.selectedAcademicYear) {
            setAcademicYear(String(res.data.selectedAcademicYear));
          } else if (res.data.academicYears && res.data.academicYears.length > 0) {
            setAcademicYear(String(res.data.academicYears[0].stvacdm_id));
          }
        }
      } catch (err) {
        console.error('Failed to load academic years:', err);
      } finally {
        setIsLoadingYears(false);
      }
    }
    loadYears();
  }, []);

  // Support CI3 token URL auth: ?auth=true&student-id=...&token=...
  useEffect(() => {
    const isAuth = searchParams.get('auth');
    const sId = searchParams.get('student-id') || searchParams.get('studentId');
    const token = searchParams.get('token');

    if (isAuth === 'true' && sId && token) {
      loginStudentWithToken(sId, token).then((ok) => {
        if (ok) {
          navigate('/profile', { replace: true });
        }
      });
    }
  }, [searchParams, loginStudentWithToken, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!studentId.trim()) return;

    const ok = await loginStudent({
      username: studentId.trim(),
      academicYear,
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
                Student Login
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
              {/* Academic Year Dropdown */}
              <div>
                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">
                  Academic Year
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <FiCalendar size={18} />
                  </div>
                  <select
                    name="acdm_year"
                    value={academicYear}
                    onChange={(e) => setAcademicYear(e.target.value)}
                    required
                    disabled={isLoadingYears}
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-4 focus:ring-[#1976d2]/15 outline-none transition-all text-sm text-slate-800 bg-slate-50/50 focus:bg-white cursor-pointer"
                  >
                    <option value="">Select Academic Year</option>
                    {academicYears.map((acdm) => (
                      <option key={acdm.stvacdm_id} value={acdm.stvacdm_id}>
                        {acdm.stvacdm_year}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              {/* Student ID */}
              <div>
                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">
                  Student ID
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <FiAward size={18} />
                  </div>
                  <input
                    type="text"
                    name="username"
                    value={studentId}
                    onChange={(e) => setStudentId(e.target.value)}
                    placeholder="e.g. S20200525"
                    required
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-4 focus:ring-[#1976d2]/15 outline-none transition-all text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white"
                  />
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
                    <span>Checking Profile...</span>
                  </>
                ) : (
                  <>
                    <FiUserCheck size={18} />
                    <span>View Profile</span>
                  </>
                )}
              </button>
            </form>

            {/* Quick Links */}
            <div className="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-600">
              <Link to="/login" className="text-[#1976d2] hover:underline">
                &larr; Admin Login
              </Link>
              <Link to="/teacher_login" className="text-slate-500 hover:text-[#1976d2]">
                Teacher Login &rarr;
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
