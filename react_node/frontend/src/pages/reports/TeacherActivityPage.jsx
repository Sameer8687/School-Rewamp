import React, { useEffect, useState } from 'react';
import { useLocation, useSearchParams } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  LuActivity,
  LuCalendar,
  LuSearch,
  LuChevronDown,
  LuChevronRight,
  LuUsers,
  LuLogIn,
} from 'react-icons/lu';
import teacherActivityService from '../../services/teacherActivityService';

export default function TeacherActivityPage({ initialTab = 'activity' }) {
  const location = useLocation();
  const [searchParams] = useSearchParams();

  const isLoginRoute =
    location.pathname.toLowerCase().includes('login_tracking') ||
    initialTab === 'login' ||
    searchParams.get('tab') === 'login';

  const [activeTab, setActiveTab] = useState(isLoginRoute ? 'login' : 'activity');
  const [options, setOptions] = useState({ active_year: '', years: [], teachers: [] });
  const [academicYear, setAcademicYear] = useState('');
  const [fromDate, setFromDate] = useState(searchParams.get('from') || '');
  const [toDate, setToDate] = useState(searchParams.get('to') || '');
  const [teacherId, setTeacherId] = useState(
    searchParams.get('teacher_name') || searchParams.get('teacher_id') || 'all'
  );
  const [search, setSearch] = useState('');
  const [report, setReport] = useState({ teachers: [], from_date: '', to_date: '' });
  const [expandedTeachers, setExpandedTeachers] = useState({});
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // Login tracking state
  const rawType = searchParams.get('type') || '';
  const initialUserType =
    rawType.toLowerCase().startsWith('st') ? 'Student' : 'Teacher';
  const [userType, setUserType] = useState(initialUserType);
  const [loginFrom, setLoginFrom] = useState(
    searchParams.get('from') || new Date(Date.now() - 7 * 86400000).toISOString().slice(0, 10)
  );
  const [loginTo, setLoginTo] = useState(
    searchParams.get('to') || new Date().toISOString().slice(0, 10)
  );
  const [loginLogs, setLoginLogs] = useState([]);

  useEffect(() => {
    const isLogin =
      location.pathname.toLowerCase().includes('login_tracking') ||
      initialTab === 'login' ||
      searchParams.get('tab') === 'login';

    setActiveTab(isLogin ? 'login' : 'activity');

    const typeQuery = searchParams.get('type');
    if (typeQuery) {
      setUserType(typeQuery.toLowerCase().startsWith('st') ? 'Student' : 'Teacher');
    }
    const qFrom = searchParams.get('from');
    const qTo = searchParams.get('to');
    if (qFrom) {
      setFromDate(qFrom);
      setLoginFrom(qFrom);
    }
    if (qTo) {
      setToDate(qTo);
      setLoginTo(qTo);
    }
    const qTeacher = searchParams.get('teacher_name') || searchParams.get('teacher_id');
    if (qTeacher) {
      setTeacherId(qTeacher);
    }
  }, [location.pathname, location.search, initialTab]);

  useEffect(() => {
    loadOptions();
    if (isLoginRoute) {
      fetchLoginLogs({
        from_date: searchParams.get('from') || loginFrom,
        to_date: searchParams.get('to') || loginTo,
        user_type: initialUserType,
      });
    }
  }, []);

  const loadOptions = async () => {
    try {
      const res = await teacherActivityService.getOptions();
      if (res.success) {
        const d = res.data;
        setOptions(d);
        const activeId = d.active_year || (d.years[0]?.id ? String(d.years[0].id) : '');
        setAcademicYear(activeId);
        const yearObj = d.years.find((y) => String(y.id) === String(activeId));
        const start = yearObj?.start_date ? String(yearObj.start_date).slice(0, 10) : '';
        const end = yearObj?.end_date ? String(yearObj.end_date).slice(0, 10) : '';
        setFromDate(start);
        setToDate(end);
        fetchReport({ academic_year: activeId, from_date: start, to_date: end, teacher_id: 'all' });
      }
    } catch (err) {
      setError('Failed to load teacher activity options');
    }
  };

  const fetchReport = async (params) => {
    setLoading(true);
    setError('');
    try {
      const res = await teacherActivityService.getActivityReport(params);
      if (res.success) {
        setReport(res.data);
        const expandMap = {};
        (res.data.teachers || []).slice(0, 5).forEach((t) => {
          expandMap[t.teacher_id] = true;
        });
        setExpandedTeachers(expandMap);
      }
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to load activity report');
    } finally {
      setLoading(false);
    }
  };

  const fetchLoginLogs = async (params = { from_date: loginFrom, to_date: loginTo, user_type: userType }) => {
    setLoading(true);
    setError('');
    try {
      const res = await teacherActivityService.getLoginTracking(params);
      if (res.success) {
        setLoginLogs(res.data.logs || []);
      }
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to load login tracking logs');
    } finally {
      setLoading(false);
    }
  };

  const handleYearChange = (newYearId) => {
    setAcademicYear(newYearId);
    const yearObj = options.years.find((y) => String(y.id) === String(newYearId));
    const start = yearObj?.start_date ? String(yearObj.start_date).slice(0, 10) : '';
    const end = yearObj?.end_date ? String(yearObj.end_date).slice(0, 10) : '';
    setFromDate(start);
    setToDate(end);
  };

  const handleFilterSubmit = (e) => {
    e.preventDefault();
    fetchReport({
      academic_year: academicYear,
      from_date: fromDate,
      to_date: toDate,
      teacher_id: teacherId,
    });
  };

  const handleLoginFilterSubmit = (e) => {
    e.preventDefault();
    fetchLoginLogs({ from_date: loginFrom, to_date: loginTo, user_type: userType });
  };

  const toggleTeacher = (id) => {
    setExpandedTeachers((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const filteredTeachers = (report.teachers || []).filter((t) => {
    const q = search.trim().toLowerCase();
    if (!q) return true;
    return (
      (t.teacher_name || '').toLowerCase().includes(q) ||
      (t.teacher_id || '').toLowerCase().includes(q)
    );
  });

  const totalSubmissions = filteredTeachers.reduce((acc, t) => acc + t.activities.length, 0);

  return (
    <div className="flex min-h-screen bg-[#f7f7f5] text-[#202020]">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0">
        <TopNav />
        <main className="p-4 sm:p-6 lg:p-8 flex-1 space-y-6">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <LuActivity className="text-[#1976d2]" />
                Teacher Activity & Login Tracking
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Audit teacher attendance submissions and monitor Teacher/Student app login tracking.
              </p>
            </div>

            <div className="flex items-center gap-3 text-sm">
              <span className="rounded-lg bg-[#e3f2fd] border border-[#bbdefb] px-3 py-1.5 font-semibold text-[#1976d2]">
                {filteredTeachers.length} Active Teachers
              </span>
              <span className="rounded-lg bg-slate-100 border border-slate-200 px-3 py-1.5 font-semibold text-slate-700">
                {totalSubmissions} Activity Logs
              </span>
            </div>
          </div>

          {error && (
            <div className="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
              {error}
            </div>
          )}

          {/* Tabs */}
          <div className="border-b border-slate-200 flex gap-2">
            <button
              onClick={() => setActiveTab('activity')}
              className={`px-4 py-2.5 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 ${
                activeTab === 'activity'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
              <LuActivity className="w-4 h-4" />
              Teacher Activity Audit
            </button>
            <button
              onClick={() => {
                setActiveTab('login');
                if (loginLogs.length === 0) fetchLoginLogs();
              }}
              className={`px-4 py-2.5 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 ${
                activeTab === 'login'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
          <LuLogIn className="w-4 h-4" />
          App Login Tracking ({loginLogs.length})
        </button>
      </div>

      {activeTab === 'activity' ? (
        <>
          <form
            onSubmit={handleFilterSubmit}
            className="bg-white rounded-xl border border-slate-200 p-4 grid grid-cols-1 md:grid-cols-6 gap-4 items-end"
          >
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Academic Year</label>
              <select
                value={academicYear}
                onChange={(e) => handleYearChange(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              >
                {options.years.map((y) => (
                  <option key={y.id} value={y.id}>
                    {y.name}
                  </option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">From Date</label>
              <input
                type="date"
                value={fromDate}
                onChange={(e) => setFromDate(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">To Date</label>
              <input
                type="date"
                value={toDate}
                onChange={(e) => setToDate(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Teacher</label>
              <select
                value={teacherId}
                onChange={(e) => setTeacherId(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              >
                <option value="all">All Teachers</option>
                {options.teachers.map((t) => (
                  <option key={t.teacher_id} value={t.teacher_id}>
                    {t.teacher_name} ({t.teacher_id})
                  </option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Quick Filter</label>
              <div className="relative">
                <LuSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
                <input
                  type="text"
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  placeholder="Teacher name..."
                  className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm"
                />
              </div>
            </div>
            <button
              type="submit"
              disabled={loading}
              className="rounded-lg bg-[#1976d2] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1565c0] disabled:opacity-50"
            >
              {loading ? 'Loading...' : 'Apply Filter'}
            </button>
          </form>

          {loading ? (
            <div className="py-12 text-center text-slate-500">Loading teacher activity logs...</div>
          ) : filteredTeachers.length === 0 ? (
            <div className="py-12 text-center text-slate-500 bg-white rounded-xl border border-slate-200">
              No teacher activities recorded in this date range.
            </div>
          ) : (
            <div className="space-y-3">
              {filteredTeachers.map((t) => {
                const isOpen = Boolean(expandedTeachers[t.teacher_id]);
                return (
                  <div
                    key={t.teacher_id}
                    className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
                  >
                    <button
                      type="button"
                      onClick={() => toggleTeacher(t.teacher_id)}
                      className="w-full px-5 py-3.5 flex items-center justify-between hover:bg-slate-50 transition-colors"
                    >
                      <div className="flex items-center gap-3">
                        {isOpen ? (
                          <LuChevronDown className="w-4 h-4 text-slate-500" />
                        ) : (
                          <LuChevronRight className="w-4 h-4 text-slate-500" />
                        )}
                        <LuUsers className="w-4 h-4 text-[#1976d2]" />
                        <span className="font-semibold text-slate-900">{t.teacher_name}</span>
                        <span className="text-xs font-mono text-slate-500">({t.teacher_id})</span>
                      </div>
                      <span className="rounded-full bg-[#e3f2fd] text-[#1976d2] px-3 py-0.5 text-xs font-semibold">
                        {t.activities.length} submission(s)
                      </span>
                    </button>

                    {isOpen && (
                      <div className="border-t border-slate-200 overflow-x-auto">
                        <table className="min-w-full divide-y divide-slate-200 text-sm">
                          <thead className="bg-slate-50">
                            <tr>
                              <th className="px-4 py-2.5 text-left font-semibold text-slate-600">Activity Date</th>
                              <th className="px-4 py-2.5 text-left font-semibold text-slate-600">Time</th>
                              <th className="px-4 py-2.5 text-left font-semibold text-slate-600">Attendance Date</th>
                              <th className="px-4 py-2.5 text-left font-semibold text-slate-600">Class</th>
                              <th className="px-4 py-2.5 text-left font-semibold text-slate-600">Source</th>
                              <th className="px-4 py-2.5 text-left font-semibold text-slate-600">Present / Absent</th>
                            </tr>
                          </thead>
                          <tbody className="divide-y divide-slate-100">
                            {t.activities.map((a, idx) => (
                              <tr key={idx} className="hover:bg-slate-50">
                                <td className="px-4 py-2 text-slate-700 whitespace-nowrap">
                                  <span className="inline-flex items-center gap-1.5">
                                    <LuCalendar className="w-3.5 h-3.5 text-slate-400" />
                                    {a.activity_date}
                                  </span>
                                </td>
                                <td className="px-4 py-2 text-slate-600 font-mono text-xs">{a.activity_time || '—'}</td>
                                <td className="px-4 py-2 text-slate-700">{a.action_date || '—'}</td>
                                <td className="px-4 py-2 text-slate-700">{a.class_name || '—'}</td>
                                <td className="px-4 py-2 text-slate-600 text-xs">{a.activity_source || '—'}</td>
                                <td className="px-4 py-2">
                                  <span className="inline-flex items-center gap-2 text-xs font-semibold">
                                    <span className="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                      Present: {a.present_count}
                                    </span>
                                    <span className="text-red-700 bg-red-50 px-2 py-0.5 rounded">
                                      Absent: {a.absent_count}
                                    </span>
                                  </span>
                                </td>
                              </tr>
                            ))}
                          </tbody>
                        </table>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
          )}
        </>
      ) : (
        <div className="space-y-4">
          <form
            onSubmit={handleLoginFilterSubmit}
            className="bg-white rounded-xl border border-slate-200 p-4 grid grid-cols-1 md:grid-cols-4 gap-4 items-end"
          >
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">User Type</label>
              <select
                value={userType}
                onChange={(e) => setUserType(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              >
                <option value="Teacher">Teachers</option>
                <option value="Student">Students</option>
              </select>
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">From Date</label>
              <input
                type="date"
                value={loginFrom}
                onChange={(e) => setLoginFrom(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">To Date</label>
              <input
                type="date"
                value={loginTo}
                onChange={(e) => setLoginTo(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              />
            </div>
            <button
              type="submit"
              disabled={loading}
              className="rounded-lg bg-[#1976d2] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1565c0] disabled:opacity-50"
            >
              {loading ? 'Loading...' : 'Fetch Login Logs'}
            </button>
          </form>

          <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-slate-200 text-sm">
                <thead className="bg-slate-50">
                  <tr>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">User ID</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Name</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Login Date</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Login Time</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Last Login</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Login Count</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan={6} className="px-4 py-8 text-center text-slate-500">
                        Loading login tracking logs...
                      </td>
                    </tr>
                  ) : loginLogs.length === 0 ? (
                    <tr>
                      <td colSpan={6} className="px-4 py-8 text-center text-slate-500">
                        No app login logs found in this date range.
                      </td>
                    </tr>
                  ) : (
                    loginLogs.map((l, idx) => (
                      <tr key={idx} className="hover:bg-slate-50">
                        <td className="px-4 py-2.5 font-mono text-xs text-slate-700">
                          {l.user_code || l.user_id}
                        </td>
                        <td className="px-4 py-2.5 font-medium text-slate-900">{l.name}</td>
                        <td className="px-4 py-2.5 text-slate-700">{l.login_date}</td>
                        <td className="px-4 py-2.5 font-mono text-xs text-slate-600">{l.login_time}</td>
                        <td className="px-4 py-2.5 text-slate-600">{l.last_login || '—'}</td>
                        <td className="px-4 py-2.5">
                          <span className="rounded-full bg-[#e3f2fd] text-[#1976d2] px-2.5 py-0.5 text-xs font-semibold">
                            {l.login_count}
                          </span>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}
        </main>
      </div>
    </div>
  );
}
