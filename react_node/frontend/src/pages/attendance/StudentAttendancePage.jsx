import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import attendanceService from '../../services/attendanceService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiCheckSquare,
  FiCalendar,
  FiUsers,
  FiCheckCircle,
  FiXCircle,
  FiAlertTriangle,
  FiPieChart,
  FiRefreshCw,
  FiPhone,
  FiSave,
  FiCheck,
  FiList
} from 'react-icons/fi';
import { FaWhatsapp } from 'react-icons/fa';

export default function StudentAttendancePage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [activeTab, setActiveTab] = useState('take'); // 'take', 'class-view', 'absentee', 'overview'

  // Master Dropdown Options
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [divisions, setDivisions] = useState(['A', 'B', 'C', 'D']);
  const [selectedYear, setSelectedYear] = useState('');

  // Common Selection Controls
  const today = new Date().toISOString().split('T')[0];
  const [selectedDate, setSelectedDate] = useState(today);
  const [selectedStandard, setSelectedStandard] = useState('');
  const [selectedDivision, setSelectedDivision] = useState('');

  // Tab 1: Take Attendance State
  const [takeStudents, setTakeStudents] = useState([]);
  const [loadingTake, setLoadingTake] = useState(false);
  const [savingAttendance, setSavingAttendance] = useState(false);
  const [takeFeedback, setTakeFeedback] = useState(null);

  // Tab 2: Class Attendance State
  const [classAttendanceReport, setClassAttendanceReport] = useState(null);
  const [loadingClassReport, setLoadingClassReport] = useState(false);

  // Tab 3: Absentee Report State
  const [absenteeRecords, setAbsenteeRecords] = useState([]);
  const [loadingAbsentee, setLoadingAbsentee] = useState(false);

  // Tab 4: School Overview State
  const [overviewData, setOverviewData] = useState(null);
  const [loadingOverview, setLoadingOverview] = useState(false);

  // Load Dropdowns
  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        const years = res.academicYears || res.data?.academicYears || res.data?.options?.academicYears || [];
        const classes = res.classes || res.data?.classes || res.data?.options?.classes || [];
        const divs = res.divisions || res.data?.divisions || res.data?.options?.divisions || ['A', 'B', 'C', 'D'];
        setAcademicYears(years);
        setStandards(classes);
        setDivisions(divs);

        const active = years.find((y) => y.is_active) || years[0];
        if (active) setSelectedYear(String(active.stvacdm_id || active.id));
        if (classes.length > 0) setSelectedStandard(classes[0]);
        if (divs.length > 0) setSelectedDivision(divs[0]);
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  // -------------------------------------------------------------
  // Tab 1: Take Attendance
  // -------------------------------------------------------------
  async function loadStudentsForAttendance() {
    if (!selectedStandard || !selectedDivision) return;
    setLoadingTake(true);
    setTakeFeedback(null);
    try {
      const res = await attendanceService.getStudentsForAttendance({
        standard: selectedStandard,
        division: selectedDivision,
        date: selectedDate,
        academic_year: selectedYear
      });
      if (res.data?.success) {
        // Map status: if already recorded in DB, preserve it; otherwise default to 'P'
        const mapped = (res.data.students || []).map((s) => ({
          ...s,
          currentStatus: s.status === 'A' ? 'A' : 'P'
        }));
        setTakeStudents(mapped);
      }
    } catch (err) {
      console.error('Load students error:', err);
      setTakeFeedback({ type: 'error', text: 'Failed to load students for attendance' });
    } finally {
      setLoadingTake(false);
    }
  }

  function handleStatusChange(studentId, newStatus) {
    setTakeStudents((prev) =>
      prev.map((s) => (s.id === studentId ? { ...s, currentStatus: newStatus } : s))
    );
  }

  function markAll(status) {
    setTakeStudents((prev) => prev.map((s) => ({ ...s, currentStatus: status })));
  }

  async function handleSaveAttendance() {
    if (takeStudents.length === 0) return;
    setSavingAttendance(true);
    setTakeFeedback(null);
    try {
      const payload = {
        date: selectedDate,
        standard: selectedStandard,
        division: selectedDivision,
        academic_year: selectedYear,
        records: takeStudents.map((s) => ({
          student_id: s.id,
          status: s.currentStatus,
          comment: s.comment || ''
        }))
      };

      const res = await attendanceService.saveAttendance(payload);
      if (res.data?.success) {
        setTakeFeedback({
          type: 'success',
          text: `Attendance saved successfully for ${takeStudents.length} students!`
        });
      }
    } catch (err) {
      console.error('Save attendance error:', err);
      setTakeFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to save attendance'
      });
    } finally {
      setSavingAttendance(false);
    }
  }

  // -------------------------------------------------------------
  // Tab 2: Class Daily Report
  // -------------------------------------------------------------
  async function loadClassDailyAttendance() {
    if (!selectedStandard || !selectedDivision) return;
    setLoadingClassReport(true);
    try {
      const res = await attendanceService.getClassAttendance({
        standard: selectedStandard,
        division: selectedDivision,
        date: selectedDate,
        academic_year: selectedYear
      });
      if (res.data?.success) {
        setClassAttendanceReport(res.data);
      }
    } catch (err) {
      console.error('Class attendance error:', err);
    } finally {
      setLoadingClassReport(false);
    }
  }

  // -------------------------------------------------------------
  // Tab 3: Absentee Report
  // -------------------------------------------------------------
  async function loadAbsenteeReport() {
    if (!selectedStandard || !selectedDivision) return;
    setLoadingAbsentee(true);
    try {
      const res = await attendanceService.getAbsenteeReport({
        standard: selectedStandard,
        division: selectedDivision,
        academic_year: selectedYear
      });
      if (res.data?.success) {
        setAbsenteeRecords(res.data.students || []);
      }
    } catch (err) {
      console.error('Absentee report error:', err);
    } finally {
      setLoadingAbsentee(false);
    }
  }

  // -------------------------------------------------------------
  // Tab 4: School Overview
  // -------------------------------------------------------------
  async function loadSchoolOverview() {
    setLoadingOverview(true);
    try {
      const res = await attendanceService.getSchoolOverview({
        date: selectedDate,
        academic_year: selectedYear
      });
      if (res.data?.success) {
        setOverviewData(res.data);
      }
    } catch (err) {
      console.error('School overview error:', err);
    } finally {
      setLoadingOverview(false);
    }
  }

  // -------------------------------------------------------------
  // WhatsApp Alerts for Absent Students (Matching CI3 Attendance.php)
  // -------------------------------------------------------------
  function sendAttendanceWhatsApp(student) {
    const phone = student.whatsapp || student.mobile;
    if (!phone || phone.trim() === '') {
      alert('Parent contact number is not registered for this student.');
      return;
    }
    const dateFormatted = new Date(selectedDate).toLocaleDateString('en-IN', {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    });
    const msg = `Dear Parent,\n    We would like to inform you that your child, ${student.name}, was absent from school today on ${dateFormatted}.\n    We encourage you to ensure that your child catches up on any missed work to stay on track with their studies. Thank you for your attention to this matter.\n\n    आपको इत्तिला दी जाती है के आपका बच्चा, ${student.name}, आज ${dateFormatted} को स्कूल से ग़ैर हाज़िर है।\n    गुजारिश है कि आप ध्यान दें और आज के छूटे हुए सबक को पूरा कराएं।\n    आपकी तवज्जो का शुक्रिया।\n\nBest regards,\nOlive English School\nDhule`;
    const cleanPhone = phone.replace(/[^0-9]/g, '');
    const formattedPhone = cleanPhone.length === 10 ? `91${cleanPhone}` : cleanPhone;
    window.open(`https://wa.me/${formattedPhone}?text=${encodeURIComponent(msg)}`, '_blank');
  }

  function sendExamWhatsApp(student) {
    const phone = student.whatsapp || student.mobile;
    if (!phone || phone.trim() === '') {
      alert('Parent contact number is not registered for this student.');
      return;
    }
    const dateFormatted = new Date(selectedDate).toLocaleDateString('en-IN', {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    });
    const msg = `Dear Parent,\n    We would like to inform you that your child, ${student.name}, was absent from school today on ${dateFormatted}.\n    We encourage you to ensure that your child does not miss any more exams. Thank you for your attention to this matter.\n\nBest regards,\nOlive English School\nDhule`;
    const cleanPhone = phone.replace(/[^0-9]/g, '');
    const formattedPhone = cleanPhone.length === 10 ? `91${cleanPhone}` : cleanPhone;
    window.open(`https://wa.me/${formattedPhone}?text=${encodeURIComponent(msg)}`, '_blank');
  }

  // Auto trigger based on tab switch
  useEffect(() => {
    if (activeTab === 'take' && selectedStandard && selectedDivision) {
      loadStudentsForAttendance();
    } else if (activeTab === 'class-view' && selectedStandard && selectedDivision) {
      loadClassDailyAttendance();
    } else if (activeTab === 'absentee' && selectedStandard && selectedDivision) {
      loadAbsenteeReport();
    } else if (activeTab === 'overview') {
      loadSchoolOverview();
    }
  }, [activeTab, selectedStandard, selectedDivision, selectedDate, selectedYear]);

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} onToggle={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiCheckSquare className="text-[#1976d2]" /> Student Attendance Management
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Daily student roll-call attendance, class-wise verification, and monthly absentee analysis
              </p>
            </div>

            {/* Academic Year Selector */}
            <div className="flex items-center gap-2">
              <span className="text-xs font-semibold text-slate-500">Academic Year:</span>
              <select
                value={selectedYear}
                onChange={(e) => setSelectedYear(e.target.value)}
                className="px-3 py-1.5 bg-white border border-[#E7E7E4] rounded-lg text-xs font-semibold text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
              >
                {academicYears.map((y) => (
                  <option key={y.stvacdm_id} value={y.stvacdm_id}>
                    {y.stvacdm_year}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* Navigation Tabs */}
          <div className="flex flex-wrap gap-2 border-b border-[#E7E7E4] pb-2">
            <button
              onClick={() => setActiveTab('take')}
              className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 cursor-pointer ${
                activeTab === 'take'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-[#E7E7E4]'
              }`}
            >
              <FiCheckSquare /> Take Attendance
            </button>
            <button
              onClick={() => setActiveTab('class-view')}
              className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 cursor-pointer ${
                activeTab === 'class-view'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-[#E7E7E4]'
              }`}
            >
              <FiUsers /> View Class Attendance
            </button>
            <button
              onClick={() => setActiveTab('absentee')}
              className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 cursor-pointer ${
                activeTab === 'absentee'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-[#E7E7E4]'
              }`}
            >
              <FiAlertTriangle /> Absentee & Streaks Report
            </button>
            <button
              onClick={() => setActiveTab('overview')}
              className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 cursor-pointer ${
                activeTab === 'overview'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-[#E7E7E4]'
              }`}
            >
              <FiPieChart /> School-Wide Daily Overview
            </button>
          </div>

          {/* Common Selector Toolbar (For Tabs 1, 2, 3) */}
          {activeTab !== 'overview' && (
            <div className="bg-white p-4 rounded-xl shadow-sm border border-[#E7E7E4] flex flex-wrap items-center gap-4">
              <div className="flex items-center gap-2">
                <span className="text-xs font-semibold text-slate-500">Date:</span>
                <input
                  type="date"
                  value={selectedDate}
                  onChange={(e) => setSelectedDate(e.target.value)}
                  className="px-3 py-1.5 bg-slate-50 border border-[#E7E7E4] rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                />
              </div>

              <div className="flex items-center gap-2">
                <span className="text-xs font-semibold text-slate-500">Standard:</span>
                <select
                  value={selectedStandard}
                  onChange={(e) => setSelectedStandard(e.target.value)}
                  className="px-3 py-1.5 bg-slate-50 border border-[#E7E7E4] rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                >
                  {standards.map((c, i) => (
                    <option key={i} value={c}>
                      {c}
                    </option>
                  ))}
                </select>
              </div>

              <div className="flex items-center gap-2">
                <span className="text-xs font-semibold text-slate-500">Division:</span>
                <select
                  value={selectedDivision}
                  onChange={(e) => setSelectedDivision(e.target.value)}
                  className="px-3 py-1.5 bg-slate-50 border border-[#E7E7E4] rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                >
                  {divisions.map((d, i) => (
                    <option key={i} value={d}>
                      {d}
                    </option>
                  ))}
                </select>
              </div>

              <button
                onClick={() => {
                  if (activeTab === 'take') loadStudentsForAttendance();
                  else if (activeTab === 'class-view') loadClassDailyAttendance();
                  else if (activeTab === 'absentee') loadAbsenteeReport();
                }}
                className="px-4 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm transition"
              >
                <FiRefreshCw /> Load
              </button>
            </div>
          )}

          {/* Feedback Banner */}
          {takeFeedback && (
            <div
              className={`p-3.5 rounded-xl text-sm flex items-center gap-2.5 ${
                takeFeedback.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border border-rose-200'
              }`}
            >
              {takeFeedback.type === 'success' ? <FiCheck /> : <FiXCircle />}
              {takeFeedback.text}
            </div>
          )}

          {/* ═══════════════════════════════════════════════════════════════
              TAB 1: TAKE ATTENDANCE
             ═══════════════════════════════════════════════════════════════ */}
          {activeTab === 'take' && (
            <div className="bg-white rounded-xl shadow-sm border border-[#E7E7E4] overflow-hidden space-y-4 p-5">
              <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-[#E7E7E4]">
                <div className="flex items-center gap-3">
                  <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Students: <span className="text-slate-900">{takeStudents.length}</span>
                  </span>
                  <span className="text-xs font-bold text-emerald-600">
                    Present: {takeStudents.filter((s) => s.currentStatus === 'P').length}
                  </span>
                  <span className="text-xs font-bold text-rose-600">
                    Absent: {takeStudents.filter((s) => s.currentStatus === 'A').length}
                  </span>
                </div>

                <div className="flex items-center gap-2">
                  <button
                    onClick={() => markAll('P')}
                    className="px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold transition"
                  >
                    Mark All Present
                  </button>
                  <button
                    onClick={() => markAll('A')}
                    className="px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold transition"
                  >
                    Mark All Absent
                  </button>
                  <button
                    onClick={handleSaveAttendance}
                    disabled={savingAttendance || takeStudents.length === 0}
                    className="px-4 py-1.5 bg-[#1976d2] hover:bg-[#1565c0] disabled:bg-slate-300 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm transition"
                  >
                    {savingAttendance ? (
                      <>
                        <FiRefreshCw className="animate-spin" /> Saving...
                      </>
                    ) : (
                      <>
                        <FiSave /> Save Attendance
                      </>
                    )}
                  </button>
                </div>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-[#E7E7E4]">
                      <th className="p-3">#</th>
                      <th className="p-3">Student ID</th>
                      <th className="p-3">Student Name</th>
                      <th className="p-3">Contact</th>
                      <th className="p-3 text-center">Status</th>
                      <th className="p-3">Remarks / Note</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {loadingTake ? (
                      <tr>
                        <td colSpan="6" className="p-8 text-center text-slate-400">
                          <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                          Loading students...
                        </td>
                      </tr>
                    ) : takeStudents.length > 0 ? (
                      takeStudents.map((s, idx) => (
                        <tr
                          key={s.id}
                          className={`hover:bg-slate-50/60 transition ${
                            s.currentStatus === 'A' ? 'bg-rose-50/30' : ''
                          }`}
                        >
                          <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                          <td className="p-3 font-mono font-medium text-slate-700">
                            {s.SPRIDEN_student_id}
                          </td>
                          <td className="p-3 font-semibold text-slate-900">
                            {s.name}
                            {s.rte_status === 'Yes' && (
                              <span className="ml-2 text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">
                                RTE
                              </span>
                            )}
                          </td>
                          <td className="p-3">
                            <div className="flex items-center gap-2 text-slate-500">
                              {s.mobile && (
                                <a
                                  href={`tel:${s.mobile}`}
                                  className="hover:text-[#1976d2] flex items-center gap-1"
                                  title="Call Father"
                                >
                                  <FiPhone /> {s.mobile}
                                </a>
                              )}
                              {s.whatsapp && (
                                <a
                                  href={`https://wa.me/91${s.whatsapp}`}
                                  target="_blank"
                                  rel="noreferrer"
                                  className="text-emerald-500 hover:text-emerald-600"
                                  title="WhatsApp Father"
                                >
                                  <FaWhatsapp />
                                </a>
                              )}
                            </div>
                          </td>
                          <td className="p-3 text-center">
                            <div className="inline-flex rounded-lg border border-[#E7E7E4] p-0.5 bg-slate-100">
                              <button
                                type="button"
                                onClick={() => handleStatusChange(s.id, 'P')}
                                className={`px-3 py-1 rounded-md text-xs font-bold transition ${
                                  s.currentStatus === 'P'
                                    ? 'bg-emerald-600 text-white shadow-xs'
                                    : 'text-slate-600 hover:text-slate-900'
                                }`}
                              >
                                Present
                              </button>
                              <button
                                type="button"
                                onClick={() => handleStatusChange(s.id, 'A')}
                                className={`px-3 py-1 rounded-md text-xs font-bold transition ${
                                  s.currentStatus === 'A'
                                    ? 'bg-rose-600 text-white shadow-xs'
                                    : 'text-slate-600 hover:text-slate-900'
                                }`}
                              >
                                Absent
                              </button>
                            </div>
                          </td>
                          <td className="p-3">
                            <input
                              type="text"
                              placeholder="Optional reason..."
                              value={s.comment || ''}
                              onChange={(e) => {
                                const val = e.target.value;
                                setTakeStudents((prev) =>
                                  prev.map((item) =>
                                    item.id === s.id ? { ...item, comment: val } : item
                                  )
                                );
                              }}
                              className="w-full px-2 py-1 bg-white border border-[#E7E7E4] rounded text-xs focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                            />
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="6" className="p-8 text-center text-slate-400">
                          No active students found for Standard {selectedStandard} - {selectedDivision}.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* ═══════════════════════════════════════════════════════════════
              TAB 2: VIEW CLASS DAILY ATTENDANCE
             ═══════════════════════════════════════════════════════════════ */}
          {activeTab === 'class-view' && classAttendanceReport && (
            <div className="space-y-6">
              {/* Metric Cards */}
              <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div className="bg-white p-4 rounded-xl border border-[#E7E7E4] shadow-sm">
                  <div className="text-xs font-bold text-slate-400 uppercase">Total Enrolled</div>
                  <div className="text-2xl font-bold text-slate-900 mt-1">
                    {classAttendanceReport.total}
                  </div>
                </div>
                <div className="bg-white p-4 rounded-xl border border-[#E7E7E4] shadow-sm">
                  <div className="text-xs font-bold text-emerald-600 uppercase">Present Today</div>
                  <div className="text-2xl font-bold text-emerald-600 mt-1">
                    {classAttendanceReport.present}
                  </div>
                </div>
                <div className="bg-white p-4 rounded-xl border border-[#E7E7E4] shadow-sm">
                  <div className="text-xs font-bold text-rose-600 uppercase">Absent Today</div>
                  <div className="text-2xl font-bold text-rose-600 mt-1">
                    {classAttendanceReport.absent}
                  </div>
                </div>
                <div className="bg-white p-4 rounded-xl border border-[#E7E7E4] shadow-sm">
                  <div className="text-xs font-bold text-[#1976d2] uppercase">Attendance Rate</div>
                  <div className="text-2xl font-bold text-[#1976d2] mt-1">
                    {classAttendanceReport.percentage}%
                  </div>
                </div>
              </div>

              {/* Table */}
              <div className="bg-white rounded-xl shadow-sm border border-[#E7E7E4] overflow-hidden">
                <div className="px-5 py-4 border-b border-[#E7E7E4] flex items-center justify-between">
                  <h3 className="text-sm font-bold text-slate-900">
                    Attendance Register: Std {selectedStandard} - {selectedDivision} (
                    {new Date(selectedDate).toLocaleDateString('en-IN')})
                  </h3>
                </div>
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-[#E7E7E4]">
                        <th className="p-3">#</th>
                        <th className="p-3">Student ID</th>
                        <th className="p-3">Student Name</th>
                        <th className="p-3">Contact</th>
                        <th className="p-3 text-center">Status</th>
                        <th className="p-3">Comment</th>
                        <th className="p-3 text-center">WhatsApp Alerts (Absent)</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {classAttendanceReport.students?.map((s, idx) => (
                        <tr
                          key={s.id}
                          className={`hover:bg-slate-50 transition ${
                            s.status === 'A' ? 'bg-rose-50/40' : ''
                          }`}
                        >
                          <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                          <td className="p-3 font-mono font-medium">{s.SPRIDEN_student_id}</td>
                          <td className="p-3 font-semibold text-slate-900">{s.name}</td>
                          <td className="p-3 text-slate-500">
                            {s.mobile ? (
                              <a href={`tel:${s.mobile}`} className="hover:text-[#1976d2]">
                                {s.mobile}
                              </a>
                            ) : (
                              '-'
                            )}
                          </td>
                          <td className="p-3 text-center">
                            <span
                              className={`px-2.5 py-1 rounded text-xs font-bold ${
                                s.status === 'P'
                                  ? 'bg-emerald-100 text-emerald-800'
                                  : s.status === 'A'
                                  ? 'bg-rose-100 text-rose-800'
                                  : 'bg-slate-100 text-slate-600'
                              }`}
                            >
                              {s.status === 'P'
                                ? 'Present'
                                : s.status === 'A'
                                ? 'Absent'
                                : 'Not Marked'}
                            </span>
                          </td>
                          <td className="p-3 text-slate-500">{s.comment || '-'}</td>
                          <td className="p-3 text-center">
                            {s.status === 'A' ? (
                              <div className="flex items-center justify-center gap-1.5">
                                <button
                                  type="button"
                                  onClick={() => sendAttendanceWhatsApp(s)}
                                  className="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded border border-emerald-200 transition cursor-pointer"
                                  title="Send Absent Alert via WhatsApp"
                                >
                                  <FaWhatsapp className="w-3.5 h-3.5 text-emerald-600" /> Attendance
                                </button>
                                <button
                                  type="button"
                                  onClick={() => sendExamWhatsApp(s)}
                                  className="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-semibold text-[#1976d2] bg-[#e3f2fd] hover:bg-[#e3f2fd] rounded border border-[#bbdefb] transition cursor-pointer"
                                  title="Send Exam Absence Alert via WhatsApp"
                                >
                                  <FaWhatsapp className="w-3.5 h-3.5 text-[#1976d2]" /> Exam
                                </button>
                              </div>
                            ) : (
                              <span className="text-slate-400 text-[11px]">-</span>
                            )}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          )}

          {/* ═══════════════════════════════════════════════════════════════
              TAB 3: ABSENTEE & STREAKS REPORT
             ═══════════════════════════════════════════════════════════════ */}
          {activeTab === 'absentee' && (
            <div className="bg-white rounded-xl shadow-sm border border-[#E7E7E4] overflow-hidden">
              <div className="px-5 py-4 border-b border-[#E7E7E4] flex items-center justify-between">
                <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                  <FiAlertTriangle className="text-amber-500" /> Chronic Absenteeism & Consecutive Absence Streaks
                </h3>
                <span className="text-xs text-slate-500">
                  Std {selectedStandard} - {selectedDivision}
                </span>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-[#E7E7E4]">
                      <th className="p-2.5">#</th>
                      <th className="p-2.5">Student ID</th>
                      <th className="p-2.5">Student Name</th>
                      <th className="p-2.5">Contact</th>
                      <th className="p-2 text-center">Jan</th>
                      <th className="p-2 text-center">Feb</th>
                      <th className="p-2 text-center">Mar</th>
                      <th className="p-2 text-center">Apr</th>
                      <th className="p-2 text-center">May</th>
                      <th className="p-2 text-center">Jun</th>
                      <th className="p-2 text-center">Jul</th>
                      <th className="p-2 text-center">Aug</th>
                      <th className="p-2 text-center">Sep</th>
                      <th className="p-2 text-center">Oct</th>
                      <th className="p-2 text-center">Nov</th>
                      <th className="p-2 text-center">Dec</th>
                      <th className="p-2.5 text-center font-bold">Total Absent</th>
                      <th className="p-2.5">Continuous Absent Streaks (2+ Days)</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {loadingAbsentee ? (
                      <tr>
                        <td colSpan="18" className="p-8 text-center text-slate-400">
                          <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                          Analyzing absenteeism records...
                        </td>
                      </tr>
                    ) : absenteeRecords.length > 0 ? (
                      absenteeRecords.map((s, idx) => (
                        <tr key={s.id} className="hover:bg-slate-50 transition">
                          <td className="p-2.5 text-slate-400 font-mono">{idx + 1}</td>
                          <td className="p-2.5 font-mono font-medium">{s.SPRIDEN_student_id}</td>
                          <td className="p-2.5 font-semibold text-slate-900">{s.student_name}</td>
                          <td className="p-2.5 text-slate-500">
                            {s.mobile ? (
                              <a href={`tel:${s.mobile}`} className="hover:text-[#1976d2]">
                                {s.mobile}
                              </a>
                            ) : (
                              '-'
                            )}
                          </td>
                          <td className="p-2 text-center text-slate-600">{s.jan || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.feb || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.mar || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.apr || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.may || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.jun || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.jul || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.aug || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.sep || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.oct || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.nov || '-'}</td>
                          <td className="p-2 text-center text-slate-600">{s.dec || '-'}</td>
                          <td className="p-2.5 text-center font-bold text-rose-600">
                            {s.total_absent || 0}
                          </td>
                          <td className="p-2.5">
                            {s.continuous_absent && s.continuous_absent.length > 0 ? (
                              <div className="flex flex-wrap gap-1">
                                {s.continuous_absent.map((streak, i) => (
                                  <span
                                    key={i}
                                    className="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800"
                                    title={`${new Date(streak.start).toLocaleDateString()} to ${new Date(streak.end).toLocaleDateString()}`}
                                  >
                                    {streak.days} days streak
                                  </span>
                                ))}
                              </div>
                            ) : (
                              <span className="text-slate-400">None</span>
                            )}
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="18" className="p-8 text-center text-slate-400">
                          No student records found.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* ═══════════════════════════════════════════════════════════════
              TAB 4: SCHOOL-WIDE DAILY OVERVIEW
             ═══════════════════════════════════════════════════════════════ */}
          {activeTab === 'overview' && (
            <div className="space-y-6">
              {/* Date Selector for Overview */}
              <div className="bg-white p-4 rounded-xl shadow-sm border border-[#E7E7E4] flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <span className="text-xs font-semibold text-slate-500">Date for Overview:</span>
                  <input
                    type="date"
                    value={selectedDate}
                    onChange={(e) => setSelectedDate(e.target.value)}
                    className="px-3 py-1.5 bg-slate-50 border border-[#E7E7E4] rounded-lg text-xs font-semibold"
                  />
                  <button
                    onClick={loadSchoolOverview}
                    className="px-3 py-1.5 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-xs font-semibold transition"
                  >
                    Refresh
                  </button>
                </div>

                {overviewData?.summary && (
                  <div className="flex items-center gap-4 text-xs">
                    <span>
                      Total: <strong>{overviewData.summary.total_students}</strong>
                    </span>
                    <span className="text-emerald-600 font-bold">
                      Present: {overviewData.summary.present}
                    </span>
                    <span className="text-rose-600 font-bold">
                      Absent: {overviewData.summary.absent}
                    </span>
                    <span className="px-2 py-0.5 rounded bg-[#e3f2fd] text-[#1565c0] font-bold">
                      Rate: {overviewData.summary.percentage}%
                    </span>
                  </div>
                )}
              </div>

              {/* All Classes Grid */}
              <div className="bg-white rounded-xl shadow-sm border border-[#E7E7E4] overflow-hidden">
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-[#E7E7E4]">
                        <th className="p-3">#</th>
                        <th className="p-3">Standard</th>
                        <th className="p-3">Division</th>
                        <th className="p-3 text-right">Total Students</th>
                        <th className="p-3 text-right">Present</th>
                        <th className="p-3 text-right">Absent</th>
                        <th className="p-3 text-right">Attendance %</th>
                        <th className="p-3 text-center">Status</th>
                        <th className="p-3 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {loadingOverview ? (
                        <tr>
                          <td colSpan="9" className="p-8 text-center text-slate-400">
                            <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                            Loading school overview...
                          </td>
                        </tr>
                      ) : overviewData?.classes?.length > 0 ? (
                        overviewData.classes.map((cls, idx) => (
                          <tr key={idx} className="hover:bg-slate-50 transition">
                            <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                            <td className="p-3 font-bold text-slate-900">{cls.STVCLAS_standard}</td>
                            <td className="p-3 font-semibold">{cls.STVCLAS_class}</td>
                            <td className="p-3 text-right font-medium">{cls.total_students}</td>
                            <td className="p-3 text-right font-semibold text-emerald-600">
                              {cls.present_count}
                            </td>
                            <td className="p-3 text-right font-semibold text-rose-600">
                              {cls.absent_count}
                            </td>
                            <td className="p-3 text-right font-bold">
                              <span
                                className={`px-2 py-0.5 rounded ${
                                  cls.percentage >= 80
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : cls.percentage > 0
                                    ? 'bg-amber-50 text-amber-700'
                                    : 'bg-slate-100 text-slate-500'
                                }`}
                              >
                                {cls.percentage}%
                              </span>
                            </td>
                            <td className="p-3 text-center">
                              <span
                                className={`px-2 py-0.5 rounded text-[11px] font-bold ${
                                  cls.attendance_status === 'Marked'
                                    ? 'bg-emerald-100 text-emerald-800'
                                    : 'bg-amber-100 text-amber-800'
                                }`}
                              >
                                {cls.attendance_status}
                              </span>
                            </td>
                            <td className="p-3 text-center">
                              <button
                                onClick={() => {
                                  setSelectedStandard(cls.STVCLAS_standard);
                                  setSelectedDivision(cls.STVCLAS_class);
                                  setActiveTab('take');
                                }}
                                className="px-2.5 py-1 bg-[#e3f2fd] text-[#1976d2] hover:bg-[#e3f2fd] rounded text-xs font-semibold transition"
                              >
                                {cls.attendance_status === 'Marked' ? 'Edit' : 'Take'}
                              </button>
                            </td>
                          </tr>
                        ))
                      ) : (
                        <tr>
                          <td colSpan="9" className="p-8 text-center text-slate-400">
                            No classes found.
                          </td>
                        </tr>
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
