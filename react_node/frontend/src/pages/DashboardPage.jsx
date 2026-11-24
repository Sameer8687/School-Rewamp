/**
 * Admin Dashboard Page — Matched to OliSave English School reference design
 *
 * - Clean white cards (#ffffff, 1px solid #e7e7e4, 14px radius) on #f7f7f5 background
 * - 4-column KPI row: Active Students, RTE Students, Teachers, Current Login
 * - 12-column grid:
 *   - 8 cols: Students by class bar chart (#1976d2 bars, interactive tooltip)
 *   - 4 cols: Quick access (Reports dropdown + Open selected report + workspace buttons)
 *   - 12 cols: Task manager (Create, Filter, Update Status, Delete)
 * - Preserves Due Date Fees modal, TopNav, and Sidebar without modifying TopNav/Sidebar
 */
import React, { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import Sidebar from '../components/Sidebar.jsx';
import TopNav from '../components/TopNav.jsx';
import useAuthStore from '../store/useAuthStore.js';
import useSidebarStore from '../store/useSidebarStore.js';
import {
  getDashboardStats,
  fetchTasks,
  createTask,
  updateTaskStatus,
  deleteTask,
} from '../services/authService.js';
import {
  LuDollarSign,
  LuPhone,
  LuX,
  LuFilter,
  LuTrash2,
} from 'react-icons/lu';

export default function DashboardPage() {
  const navigate = useNavigate();
  const { user } = useAuthStore();
  const { isExpanded } = useSidebarStore();

  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedReportUrl, setSelectedReportUrl] = useState('');
  const [showDueModal, setShowDueModal] = useState(false);
  const [hoveredBar, setHoveredBar] = useState(null);
  const [chartAnimated, setChartAnimated] = useState(false);

  // Task manager state
  const [tasks, setTasks] = useState([]);
  const [tasksLoading, setTasksLoading] = useState(false);
  const [showTaskFilters, setShowTaskFilters] = useState(false);
  const [filterTitle, setFilterTitle] = useState('');
  const [filterStatus, setFilterStatus] = useState('all');
  const [taskTitle, setTaskTitle] = useState('');
  const [taskStartDate, setTaskStartDate] = useState('');
  const [taskReminderDate, setTaskReminderDate] = useState('');
  const [taskSaving, setTaskSaving] = useState(false);

  const loadData = async (params = {}) => {
    try {
      setLoading(true);
      const res = await getDashboardStats(params);
      if (res.success && res.data) {
        setStats(res.data);
      }
    } catch (err) {
      console.error('Failed to load dashboard data:', err);
    } finally {
      setLoading(false);
    }
  };

  const loadTasks = async (filter = {}) => {
    try {
      setTasksLoading(true);
      const res = await fetchTasks(filter);
      if (res.success) {
        setTasks(res.data || []);
      }
    } catch (err) {
      console.error('Failed to load tasks:', err);
    } finally {
      setTasksLoading(false);
    }
  };

  useEffect(() => {
    loadData();
    loadTasks();
  }, []);

  // Trigger dynamic rising animation on page load/reload
  useEffect(() => {
    if (!loading && stats?.chartData && stats.chartData.length > 0) {
      setChartAnimated(false);
      const timer = setTimeout(() => {
        setChartAnimated(true);
      }, 80);
      return () => clearTimeout(timer);
    }
  }, [loading, stats]);

  const handleOpenReport = () => {
    if (!selectedReportUrl) return;
    navigate(selectedReportUrl);
  };

  const handleCreateTask = async (e) => {
    e.preventDefault();
    if (!taskTitle.trim()) return;
    try {
      setTaskSaving(true);
      const res = await createTask({
        title: taskTitle.trim(),
        start_date: taskStartDate || null,
        reminder_date: taskReminderDate || null,
      });
      if (res.success) {
        setTaskTitle('');
        setTaskStartDate('');
        setTaskReminderDate('');
        loadTasks({
          title: filterTitle || undefined,
          status: filterStatus !== 'all' ? filterStatus : undefined,
        });
      }
    } catch (err) {
      console.error('Failed to create task:', err);
    } finally {
      setTaskSaving(false);
    }
  };

  const handleTaskStatusChange = async (id, status) => {
    try {
      await updateTaskStatus(id, status);
      loadTasks({
        title: filterTitle || undefined,
        status: filterStatus !== 'all' ? filterStatus : undefined,
      });
    } catch (err) {
      console.error('Failed to update task status:', err);
    }
  };

  const handleDeleteTask = async (id) => {
    if (!window.confirm('Delete this task?')) return;
    try {
      await deleteTask(id);
      setTasks((prev) => prev.filter((t) => (t.id || t.stvtask_id) !== id));
    } catch (err) {
      console.error('Failed to delete task:', err);
    }
  };

  const metrics = stats?.metrics || {};
  const currentLogin = stats?.currentLogin || {};
  const dueDateRecords = stats?.dueDateRecords || [];
  const allocatedForms = stats?.allocatedForms || [];
  const formsList = stats?.formsList || [];
  const chartData = stats?.chartData || [];

  const displayName = user?.fullName || user?.name || user?.username || 'Developer';

  const todayFormatted = new Date().toLocaleDateString('en-GB', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });

  const todayModalFormatted = new Date()
    .toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })
    .replace(/ /g, '-');

  const loginDateStr = currentLogin.stvlgin_date
    ? new Date(currentLogin.stvlgin_date).toLocaleDateString('en-GB', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
    })
    : todayFormatted;

  const loginTimeStr = currentLogin.stvlgin_time || '';
  let formattedLoginTime = '—';
  if (loginTimeStr) {
    try {
      const [hh, mm] = loginTimeStr.split(':');
      const d = new Date();
      d.setHours(Number(hh || 0), Number(mm || 0));
      formattedLoginTime = d.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
      });
    } catch {
      formattedLoginTime = loginTimeStr;
    }
  } else {
    formattedLoginTime = new Date().toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  }

  const activeStudents = Number(
    metrics.activeStudents !== undefined
      ? metrics.activeStudents
      : metrics.active?.active_student || 0
  );
  const rteStudents = Number(
    metrics.rteStudents !== undefined ? metrics.rteStudents : metrics.rte?.rte || 0
  );
  const activeTeachers = Number(
    metrics.activeTeachers !== undefined
      ? metrics.activeTeachers
      : metrics.count?.teacher_total || 0
  );

  // Chart scaling
  const maxStudentCount = Math.max(
    160,
    ...chartData.map((d) => Number(d.TOTAL_STUDENTS || 0))
  );
  const yTicks = [0, 0.25, 0.5, 0.75, 1].map((f) => Math.round(maxStudentCount * f));

  return (
    <div className="min-h-screen bg-[#f7f7f5] flex flex-col font-sans text-[#202020]">
      {/* Due Date Fees Modal */}
      {showDueModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[1px]">
          <div className="relative w-full max-w-4xl rounded-[14px] border border-[#e7e7e4] bg-white max-h-[88vh] flex flex-col overflow-hidden shadow-2xl">
            <div className="flex items-center justify-between px-5 py-4 border-b border-[#e7e7e4]">
              <div>
                <h3 className="text-[16px] font-semibold text-[#202020] flex items-center gap-2 m-0">
                  <LuDollarSign className="text-[#1976d2]" />
                  <span>Student fees due today · {todayModalFormatted}</span>
                </h3>
                <p className="text-[12px] text-[#73736f] mt-0.5 mb-0">
                  Accounts requiring follow-up
                </p>
              </div>
              <button
                type="button"
                onClick={() => setShowDueModal(false)}
                className="p-1.5 rounded-lg text-[#73736f] hover:bg-[#f5f5f3] hover:text-[#202020] cursor-pointer"
              >
                <LuX size={18} />
              </button>
            </div>

            <div className="p-4 overflow-y-auto max-h-[60vh]">
              <table className="w-full border-collapse text-[12px]">
                <thead>
                  <tr>
                    <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580]">#</th>
                    <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580]">Student ID</th>
                    <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580]">Name</th>
                    <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580]">Class</th>
                    <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580]">Total fees</th>
                    <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580]">Paid</th>
                    <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-center text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580]">Contact</th>
                  </tr>
                </thead>
                <tbody>
                  {dueDateRecords.length > 0 ? (
                    dueDateRecords.map((rec, idx) => (
                      <tr key={idx} className="border-b border-[#f0f0ee] hover:bg-[#fafaf8]">
                        <td className="py-2.5 px-2.5 text-[#73736f] font-mono">{idx + 1}</td>
                        <td className="py-2.5 px-2.5 font-mono font-semibold text-[#1976d2]">
                          {rec.SPRIDEN_student_id}
                        </td>
                        <td className="py-2.5 px-2.5">
                          <Link
                            to={`/fees?source=due-date&student-id=${rec.id}`}
                            className="font-medium text-[#202020] hover:text-[#1976d2]"
                          >
                            {rec.name}
                          </Link>
                        </td>
                        <td className="py-2.5 px-2.5">{rec.STVCLAS_standard}</td>
                        <td className="py-2.5 px-2.5 font-medium">₹{rec.class_fees || '-'}</td>
                        <td className="py-2.5 px-2.5 text-emerald-700 font-medium">₹{rec.total_paid || 0}</td>
                        <td className="py-2.5 px-2.5 text-center">
                          {rec.mobile ? (
                            <a
                              href={`tel:+91${rec.mobile}`}
                              className="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100"
                              title={`Call +91 ${rec.mobile}`}
                            >
                              <LuPhone size={13} />
                            </a>
                          ) : (
                            <span className="text-[#858580]">—</span>
                          )}
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="7" className="p-8 text-center text-[#73736f]">
                        No records due today.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* Top Navigation Bar */}
      <TopNav
        dueDateCount={dueDateRecords.length}
        onOpenDueModal={() => setShowDueModal(true)}
        formsList={formsList}
      />

      {/* Sidebar */}
      <Sidebar allocatedForms={allocatedForms} />

      {/* Main Content */}
      <div className="flex-1 flex flex-col min-w-0 transition-all duration-300">
        <main className="flex-1 w-full max-w-[1500px] mx-auto px-4 sm:px-[25px] py-[25px]">
          {loading ? (
            <div className="bg-white border border-[#e7e7e4] rounded-[14px] py-20 flex flex-col items-center justify-center gap-3 text-center shadow-[0_1px_2px_rgba(32,32,32,0.035)]">
              <div className="olive-spinner w-8 h-8" />
              <p className="text-[13px] font-medium text-[#73736f]">
                Loading Dashboard...
              </p>
            </div>
          ) : (
            <div className="space-y-5">
              {/* Page Header */}
              <header className="mb-[22px]">
                <p className="text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580] m-0 mb-[7px]">
                  School overview
                </p>
                <h1 className="m-0 text-[25px] font-bold tracking-[-0.035em] text-[#202020] leading-tight">
                  Dashboard
                </h1>
                <p className="m-0 mt-[7px] text-[12.5px] text-[#73736f]">
                  Welcome back, {displayName}. Here is your school at a glance.
                </p>
              </header>

              {/* 12-Column Grid */}
              <section className="grid grid-cols-12 gap-4" aria-label="School overview">
                {/* 4 KPI Cards */}
                <article className="col-span-12 sm:col-span-6 lg:col-span-3 min-w-0 bg-white border border-[#e7e7e4] rounded-[14px] p-[19px] min-h-[126px] shadow-[0_1px_2px_rgba(32,32,32,0.035)]">
                  <div className="text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580]">
                    Active students
                  </div>
                  <div className="mt-[9px] text-[27px] font-semibold tracking-[-0.04em] leading-[1.1] text-[#202020]">
                    {activeStudents.toLocaleString()}
                  </div>
                  <div className="mt-2 text-[12px] text-[#73736f]">
                    As of {todayFormatted}
                  </div>
                </article>

                <article className="col-span-12 sm:col-span-6 lg:col-span-3 min-w-0 bg-white border border-[#e7e7e4] rounded-[14px] p-[19px] min-h-[126px] shadow-[0_1px_2px_rgba(32,32,32,0.035)]">
                  <div className="text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580]">
                    RTE students
                  </div>
                  <div className="mt-[9px] text-[27px] font-semibold tracking-[-0.04em] leading-[1.1] text-[#202020]">
                    {rteStudents.toLocaleString()}
                  </div>
                  <div className="mt-2 text-[12px] text-[#73736f]">
                    Current school records
                  </div>
                </article>

                <article className="col-span-12 sm:col-span-6 lg:col-span-3 min-w-0 bg-white border border-[#e7e7e4] rounded-[14px] p-[19px] min-h-[126px] shadow-[0_1px_2px_rgba(32,32,32,0.035)]">
                  <div className="text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580]">
                    Teachers
                  </div>
                  <div className="mt-[9px] text-[27px] font-semibold tracking-[-0.04em] leading-[1.1] text-[#202020]">
                    {activeTeachers.toLocaleString()}
                  </div>
                  <div className="mt-2 text-[12px] text-[#73736f]">
                    Teacher accounts
                  </div>
                </article>

                <article className="col-span-12 sm:col-span-6 lg:col-span-3 min-w-0 bg-white border border-[#e7e7e4] rounded-[14px] p-[19px] min-h-[126px] shadow-[0_1px_2px_rgba(32,32,32,0.035)]">
                  <div className="text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580]">
                    Current login
                  </div>
                  <div className="mt-[9px] text-[19px] font-semibold tracking-[-0.03em] leading-[1.2] text-[#202020]">
                    {loginDateStr}
                  </div>
                  <div className="mt-2 text-[12px] text-[#73736f]">
                    {formattedLoginTime} ·{' '}
                    <Link to="/stvlogh" className="text-[#1976d2] hover:underline no-underline">
                      View login history
                    </Link>
                  </div>
                </article>

                {/* Students by Class Bar Chart (8 cols) */}
                <article className="col-span-12 lg:col-span-8 min-w-0 bg-white border border-[#e7e7e4] rounded-[14px] p-[19px] shadow-[0_1px_2px_rgba(32,32,32,0.035)] flex flex-col">
                  <div className="mb-4">
                    <h2 className="m-0 text-[15px] font-semibold tracking-[-0.01em] text-[#202020]">
                      Students by class
                    </h2>
                    <p className="m-0 mt-[5px] text-[12px] text-[#73736f]">
                      Enrolled student count for each class
                    </p>
                  </div>

                  {chartData.length === 0 ? (
                    <div className="flex-1 min-h-[280px] flex items-center justify-center text-[12px] text-[#73736f]">
                      No class enrollment data available.
                    </div>
                  ) : (
                    <div className="relative flex-1 min-h-[290px] pt-2 pb-1 flex flex-col justify-end">
                      {/* Tooltip */}
                      {hoveredBar && (
                        <div className="absolute top-2 right-2 z-10 rounded-lg border border-[#e7e7e4] bg-white px-3 py-1.5 shadow-sm text-[12px]">
                          <span className="font-semibold text-[#202020]">
                            {hoveredBar.STVCLAS_standard}
                          </span>
                          <span className="text-[#73736f]"> · </span>
                          <span className="font-semibold text-[#1976d2]">
                            {hoveredBar.TOTAL_STUDENTS} students
                          </span>
                        </div>
                      )}

                      <div className="flex gap-3 h-[250px]">
                        {/* Y-axis */}
                        <div className="flex flex-col justify-between items-end pr-1 text-[11px] text-[#73736f] select-none shrink-0 w-8">
                          {[...yTicks].reverse().map((t, i) => (
                            <span key={i}>{t}</span>
                          ))}
                        </div>

                        {/* Plot Area */}
                        <div className="relative flex-1 flex flex-col">
                          {/* Grid lines */}
                          <div className="absolute inset-0 flex flex-col justify-between pointer-events-none">
                            {yTicks.map((_, i) => (
                              <div key={i} className="border-t border-[#efefed] w-full" />
                            ))}
                          </div>

                          {/* Bars */}
                          <div className="relative z-10 flex-1 flex items-end justify-around gap-1.5 sm:gap-2.5 px-2">
                            {chartData.map((row, idx) => {
                              const count = Number(row.TOTAL_STUDENTS || 0);
                              const pct = Math.max(2, (count / maxStudentCount) * 100);
                              const isHovered = hoveredBar?.STVCLAS_standard === row.STVCLAS_standard;
                              return (
                                <div
                                  key={idx}
                                  onMouseEnter={() => setHoveredBar(row)}
                                  onMouseLeave={() => setHoveredBar(null)}
                                  className="flex-1 max-w-[36px] h-full flex flex-col justify-end items-center group cursor-pointer"
                                  title={`${row.STVCLAS_standard}: ${count} students`}
                                >
                                  <div
                                    className="w-full rounded-t-[5px] shadow-sm"
                                    style={{
                                      height: chartAnimated ? `${pct}%` : '0%',
                                      backgroundColor: isHovered ? '#1565c0' : '#1976d2',
                                      transition: `height 0.85s cubic-bezier(0.16, 1, 0.3, 1) ${idx * 40}ms, background-color 0.2s ease, filter 0.2s ease`,
                                      filter: isHovered ? 'brightness(1.1)' : 'none',
                                      transformOrigin: 'bottom',
                                    }}
                                  />
                                </div>
                              );
                            })}
                          </div>
                        </div>
                      </div>

                      {/* X-axis Labels */}
                      <div className="flex gap-3 mt-2">
                        <div className="w-8 shrink-0" />
                        <div className="flex-1 flex justify-around gap-1.5 sm:gap-2.5 px-2">
                          {chartData.map((row, idx) => (
                            <div
                              key={idx}
                              className="flex-1 max-w-[36px] text-center text-[10.5px] text-[#73736f] truncate"
                              title={row.STVCLAS_standard}
                            >
                              {row.STVCLAS_standard}
                            </div>
                          ))}
                        </div>
                      </div>
                    </div>
                  )}
                </article>

                {/* Quick Access Card (4 cols) */}
                <article className="col-span-12 lg:col-span-4 min-w-0 bg-white border border-[#e7e7e4] rounded-[14px] p-[19px] shadow-[0_1px_2px_rgba(32,32,32,0.035)]">
                  <div className="mb-4">
                    <h2 className="m-0 text-[15px] font-semibold tracking-[-0.01em] text-[#202020]">
                      Quick access
                    </h2>
                    <p className="m-0 mt-[5px] text-[12px] text-[#73736f]">
                      Open a report or school workspace
                    </p>
                  </div>

                  <label
                    htmlFor="form_links_report"
                    className="block text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580]"
                  >
                    Reports
                  </label>
                  <select
                    id="form_links_report"
                    value={selectedReportUrl}
                    onChange={(e) => setSelectedReportUrl(e.target.value)}
                    className="w-full min-h-[38px] mt-[7px] mb-[10px] px-[11px] py-2 border border-[#dededb] rounded-lg bg-white text-[#202020] text-[12px] focus:outline-none focus:border-[#1976d2] focus:ring-3 focus:ring-[#1976d2]/15"
                  >
                    <option value="">Choose a report</option>
                    <option value="/fees-report">Fees Report</option>
                    <option value="/fees/dashboard">Executive Fees Dashboard</option>
                    <option value="/reports/students">Student Reports</option>
                    <option value="/students">Update Student Report</option>
                    <option value="/aadhar-report">Aadhar Details Report</option>
                    <option value="/reports/teacher-activity">Teacher Activity Audit</option>
                    <option value="/student-attendance">Student Attendance</option>
                  </select>

                  <button
                    type="button"
                    onClick={handleOpenReport}
                    className="w-full min-h-[36px] inline-flex items-center justify-center rounded-lg border border-[#1976d2] bg-[#1976d2] hover:bg-[#1d679f] text-white text-[12px] font-medium cursor-pointer transition-colors"
                  >
                    Open selected report
                  </button>

                  <div className="grid gap-2 mt-[17px]">
                    <Link
                      to="/fees/dashboard"
                      className="min-h-[36px] px-3.5 inline-flex items-center justify-center rounded-lg border border-[#dededb] bg-white hover:bg-[#f5f5f3] text-[#333] text-[12px] font-medium no-underline transition-colors"
                    >
                      Fees dashboard
                    </Link>
                    <Link
                      to="/attendance"
                      className="min-h-[36px] px-3.5 inline-flex items-center justify-center rounded-lg border border-[#dededb] bg-white hover:bg-[#f5f5f3] text-[#333] text-[12px] font-medium no-underline transition-colors"
                    >
                      Attendance dashboard
                    </Link>
                    <Link
                      to="/students/admission"
                      className="min-h-[36px] px-3.5 inline-flex items-center justify-center rounded-lg border border-[#dededb] bg-white hover:bg-[#f5f5f3] text-[#333] text-[12px] font-medium no-underline transition-colors"
                    >
                      Admission form
                    </Link>
                    <Link
                      to="/fees"
                      className="min-h-[36px] px-3.5 inline-flex items-center justify-center rounded-lg border border-[#dededb] bg-white hover:bg-[#f5f5f3] text-[#333] text-[12px] font-medium no-underline transition-colors"
                    >
                      Collect student fees
                    </Link>
                    <Link
                      to="/students/lc"
                      className="min-h-[36px] px-3.5 inline-flex items-center justify-center rounded-lg border border-[#dededb] bg-white hover:bg-[#f5f5f3] text-[#333] text-[12px] font-medium no-underline transition-colors"
                    >
                      LC certificate
                    </Link>
                  </div>
                </article>

                {/* Task Manager Card (12 cols) */}
                <article className="col-span-12 min-w-0 bg-white border border-[#e7e7e4] rounded-[14px] p-[19px] shadow-[0_1px_2px_rgba(32,32,32,0.035)]">
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                      <h2 className="m-0 text-[15px] font-semibold tracking-[-0.01em] text-[#202020]">
                        Task manager
                      </h2>
                      <p className="m-0 mt-[5px] text-[12px] text-[#73736f]">
                        Create and track school tasks
                      </p>
                    </div>
                    <button
                      type="button"
                      onClick={() => setShowTaskFilters((v) => !v)}
                      className="inline-flex items-center justify-center gap-1.5 min-h-[36px] px-3.5 rounded-lg border border-[#dededb] bg-white hover:bg-[#f5f5f3] text-[#333] text-[12px] font-medium cursor-pointer transition-colors"
                    >
                      <LuFilter size={14} />
                      <span>Filter tasks</span>
                    </button>
                  </div>

                  {showTaskFilters && (
                    <div className="grid grid-cols-12 gap-2.5 items-end mb-4 p-3 rounded-lg bg-[#fafaf8] border border-[#efefed]">
                      <div className="col-span-12 sm:col-span-5">
                        <label className="block text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580] mb-1">
                          Task name
                        </label>
                        <input
                          type="search"
                          value={filterTitle}
                          onChange={(e) => setFilterTitle(e.target.value)}
                          placeholder="Search tasks"
                          className="w-full min-h-[36px] px-2.5 border border-[#dededb] rounded-lg bg-white text-[12px]"
                        />
                      </div>
                      <div className="col-span-12 sm:col-span-4">
                        <label className="block text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580] mb-1">
                          Status
                        </label>
                        <select
                          value={filterStatus}
                          onChange={(e) => setFilterStatus(e.target.value)}
                          className="w-full min-h-[36px] px-2.5 border border-[#dededb] rounded-lg bg-white text-[12px]"
                        >
                          <option value="all">All</option>
                          <option value="pending">Pending</option>
                          <option value="complete">Complete</option>
                          <option value="close">Close</option>
                          <option value="cancelled">Cancelled</option>
                        </select>
                      </div>
                      <div className="col-span-12 sm:col-span-3">
                        <button
                          type="button"
                          onClick={() =>
                            loadTasks({
                              title: filterTitle || undefined,
                              status: filterStatus !== 'all' ? filterStatus : undefined,
                            })
                          }
                          className="w-full min-h-[36px] rounded-lg border border-[#1976d2] bg-[#1976d2] hover:bg-[#1d679f] text-white text-[12px] font-medium cursor-pointer"
                        >
                          Apply filters
                        </button>
                      </div>
                    </div>
                  )}

                  {/* Create Task Form */}
                  <form
                    onSubmit={handleCreateTask}
                    className="grid grid-cols-12 gap-2.5 items-end mb-[18px]"
                  >
                    <div className="col-span-12 md:col-span-6">
                      <label className="block text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580] mb-1">
                        Task title
                      </label>
                      <input
                        type="text"
                        value={taskTitle}
                        onChange={(e) => setTaskTitle(e.target.value)}
                        placeholder="Enter task title"
                        className="w-full min-h-[38px] px-[11px] py-2 border border-[#dededb] rounded-lg bg-white text-[12px] focus:outline-none focus:border-[#1976d2]"
                      />
                    </div>
                    <div className="col-span-6 md:col-span-2">
                      <label className="block text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580] mb-1">
                        Start date
                      </label>
                      <input
                        type="date"
                        value={taskStartDate}
                        onChange={(e) => setTaskStartDate(e.target.value)}
                        className="w-full min-h-[38px] px-[11px] py-2 border border-[#dededb] rounded-lg bg-white text-[12px] focus:outline-none focus:border-[#1976d2]"
                      />
                    </div>
                    <div className="col-span-6 md:col-span-2">
                      <label className="block text-[10px] font-bold tracking-[0.12em] uppercase text-[#858580] mb-1">
                        Reminder date
                      </label>
                      <input
                        type="date"
                        value={taskReminderDate}
                        onChange={(e) => setTaskReminderDate(e.target.value)}
                        className="w-full min-h-[38px] px-[11px] py-2 border border-[#dededb] rounded-lg bg-white text-[12px] focus:outline-none focus:border-[#1976d2]"
                      />
                    </div>
                    <div className="col-span-12 md:col-span-2">
                      <button
                        type="submit"
                        disabled={taskSaving || !taskTitle.trim()}
                        className="w-full min-h-[38px] rounded-lg border border-[#1976d2] bg-[#1976d2] hover:bg-[#1d679f] text-white text-[12px] font-medium cursor-pointer disabled:opacity-50 transition-colors"
                      >
                        {taskSaving ? 'Saving...' : 'Save task'}
                      </button>
                    </div>
                  </form>

                  {/* Task Table */}
                  <div className="w-full overflow-x-auto">
                    <table className="w-full border-collapse text-[12px]">
                      <thead>
                        <tr>
                          <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580] whitespace-nowrap">#</th>
                          <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580] whitespace-nowrap">Task name</th>
                          <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580] whitespace-nowrap">Status</th>
                          <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580] whitespace-nowrap">Start date</th>
                          <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-left text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580] whitespace-nowrap">Reminder</th>
                          <th className="py-2.5 px-2.5 border-b border-[#e7e7e4] text-right text-[10px] font-semibold uppercase tracking-[0.06em] text-[#858580] whitespace-nowrap">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        {tasksLoading ? (
                          <tr>
                            <td colSpan={6} className="py-6 text-center text-[#73736f]">
                              Loading tasks...
                            </td>
                          </tr>
                        ) : tasks.length === 0 ? (
                          <tr>
                            <td colSpan={6} className="py-6 text-center text-[#73736f]">
                              No tasks found. Create a task above to get started.
                            </td>
                          </tr>
                        ) : (
                          tasks.map((t, idx) => {
                            const id = t.id || t.stvtask_id;
                            const title = t.title || t.stvtask_title;
                            const status = (t.status || t.stvtask_status || 'pending').toLowerCase();
                            const start = t.start_date || t.stvtask_start_date || '—';
                            const reminder = t.reminder_date || t.stvtask_reminder_date || '—';
                            return (
                              <tr key={id || idx} className="border-b border-[#f0f0ee] hover:bg-[#fafaf8]">
                                <td className="py-2.5 px-2.5 text-[#73736f] font-mono">{idx + 1}</td>
                                <td className="py-2.5 px-2.5 font-medium text-[#202020]">{title}</td>
                                <td className="py-2.5 px-2.5">
                                  <select
                                    value={status}
                                    onChange={(e) => handleTaskStatusChange(id, e.target.value)}
                                    className="rounded border border-[#dededb] bg-white px-2 py-1 text-[11px] capitalize"
                                  >
                                    <option value="pending">Pending</option>
                                    <option value="complete">Complete</option>
                                    <option value="close">Close</option>
                                    <option value="cancelled">Cancelled</option>
                                  </select>
                                </td>
                                <td className="py-2.5 px-2.5 text-[#73736f] whitespace-nowrap">
                                  {String(start).slice(0, 10)}
                                </td>
                                <td className="py-2.5 px-2.5 text-[#73736f] whitespace-nowrap">
                                  {String(reminder).slice(0, 10)}
                                </td>
                                <td className="py-2.5 px-2.5 text-right">
                                  <button
                                    type="button"
                                    onClick={() => handleDeleteTask(id)}
                                    className="inline-flex items-center gap-1 text-red-600 hover:text-red-800 text-[11px] font-medium cursor-pointer"
                                  >
                                    <LuTrash2 size={13} />
                                    <span>Delete</span>
                                  </button>
                                </td>
                              </tr>
                            );
                          })
                        )}
                      </tbody>
                    </table>
                  </div>
                </article>
              </section>
            </div>
          )}
        </main>
      </div>
    </div>
  );
}
