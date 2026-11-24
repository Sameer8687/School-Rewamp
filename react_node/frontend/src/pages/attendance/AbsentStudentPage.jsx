import React, { useState, useEffect, useMemo } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import attendanceService from '../../services/attendanceService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiUsers,
  FiCalendar,
  FiSearch,
  FiRefreshCw,
  FiAlertTriangle,
  FiPhone,
  FiDownload,
  FiCopy,
  FiPrinter,
  FiX,
  FiChevronLeft,
  FiChevronRight,
  FiSend,
  FiCheckCircle
} from 'react-icons/fi';
import { FaWhatsapp } from 'react-icons/fa';

const MONTHS = [
  { key: 'jan', name: 'Jan', num: 1 },
  { key: 'feb', name: 'Feb', num: 2 },
  { key: 'mar', name: 'Mar', num: 3 },
  { key: 'apr', name: 'Apr', num: 4 },
  { key: 'may', name: 'May', num: 5 },
  { key: 'jun', name: 'Jun', num: 6 },
  { key: 'jul', name: 'Jul', num: 7 },
  { key: 'aug', name: 'Aug', num: 8 },
  { key: 'sep', name: 'Sep', num: 9 },
  { key: 'oct', name: 'Oct', num: 10 },
  { key: 'nov', name: 'Nov', num: 11 },
  { key: 'dec', name: 'Dec', num: 12 }
];

export default function AbsentStudentPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  // Filter Dropdown Options
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([
    'Nursery', 'Jr.Kg', 'Sr.Kg',
    'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
  ]);
  const [divisions, setDivisions] = useState(['A', 'B', 'C', 'D']);

  // Filter selections
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStd, setSelectedStd] = useState('');
  const [selectedDiv, setSelectedDiv] = useState('');

  // Table Data State
  const [students, setStudents] = useState([]);
  const [loading, setLoading] = useState(false);
  const [searched, setSearched] = useState(false);
  const [errorMsg, setErrorMsg] = useState('');
  const [searchQuery, setSearchQuery] = useState('');
  const [copyFeedback, setCopyFeedback] = useState(false);

  // Modal / Calendar State
  const [activeStudent, setActiveStudent] = useState(null);
  const [modalOpen, setModalOpen] = useState(false);
  const [studentAbsentDates, setStudentAbsentDates] = useState([]);
  const [loadingModal, setLoadingModal] = useState(false);
  const [activeCalendarMonth, setActiveCalendarMonth] = useState(new Date().getMonth());
  const [activeCalendarYear, setActiveCalendarYear] = useState(new Date().getFullYear());
  const [customMsg, setCustomMsg] = useState('');

  // 1. Initial Load: Academic Years and Options, Restore from localStorage
  useEffect(() => {
    async function initOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          const yrs = res.data.options?.academicYears || [];
          const classes = res.data.options?.classes || [];
          const divs = res.data.options?.divisions || ['A', 'B', 'C', 'D'];

          setAcademicYears(yrs);
          if (classes.length > 0) setStandards(classes);
          if (divs.length > 0) setDivisions(divs);

          // Restore saved filters if available
          const saved = localStorage.getItem('absentFilters');
          if (saved) {
            try {
              const obj = JSON.parse(saved);
              if (obj.year) setSelectedYear(String(obj.year));
              if (obj.std) setSelectedStd(obj.std);
              if (obj.div) setSelectedDiv(obj.div);

              if (obj.data && Array.isArray(obj.data)) {
                setStudents(obj.data);
                setSearched(true);
              }
            } catch (e) {
              console.error('Error parsing stored filters:', e);
            }
          } else {
            // Default active academic year
            const activeYr = yrs.find((y) => y.is_active) || yrs[0];
            if (activeYr) setSelectedYear(String(activeYr.stvacdm_id));
          }
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    initOptions();
  }, []);

  // 2. Fetch Absentee Matrix
  const handleSearch = async (e) => {
    if (e) e.preventDefault();
    if (!selectedYear || !selectedStd || !selectedDiv) {
      setErrorMsg('Please select Academic Year, Class, and Division.');
      return;
    }

    setErrorMsg('');
    setLoading(true);
    setSearched(true);

    try {
      const res = await attendanceService.getAbsenteeReport({
        standard: selectedStd,
        division: selectedDiv,
        academic_year: selectedYear
      });

      const list = res.data?.students || (Array.isArray(res.data) ? res.data : []);
      setStudents(list);

      // Save to localStorage for parity with CI3
      localStorage.setItem(
        'absentFilters',
        JSON.stringify({
          year: selectedYear,
          std: selectedStd,
          div: selectedDiv,
          data: list
        })
      );
    } catch (err) {
      console.error('Search error:', err);
      setErrorMsg('Failed to fetch absentee data. Please verify your connection.');
      setStudents([]);
    } finally {
      setLoading(false);
    }
  };

  // 3. Open Detail Modal / Calendar for a Student
  const handleOpenStudentModal = async (student) => {
    setActiveStudent(student);
    setModalOpen(true);
    setLoadingModal(true);
    setStudentAbsentDates([]);
    setCustomMsg('');

    try {
      const res = await attendanceService.getDailyAbsentDates(student.id, selectedYear);
      const dates = res.data?.dates || (Array.isArray(res.data) ? res.data : []);
      setStudentAbsentDates(dates);

      // Default custom message to the most recent absence or general notice
      const phone = dates.length > 0 ? dates[0].phone : student.whatsapp || student.mobile || '';
      const recentDate = dates.length > 0 ? dates[dates.length - 1].date : '';
      if (recentDate) {
        setCustomMsg(
          `Hello, this is Olive English School informing you that ${student.student_name} was absent on ${recentDate}. Please ensure regular attendance.`
        );
      } else {
        setCustomMsg(
          `Hello, this is Olive English School regarding the attendance of ${student.student_name}.`
        );
      }
    } catch (err) {
      console.error('Modal error:', err);
    } finally {
      setLoadingModal(false);
    }
  };

  // 4. Calculate Current Month Continuous Streaks for Badges (Matching CI3 logic)
  const getCurrentMonthStreaks = (streaks) => {
    if (!streaks || streaks.length === 0) return null;
    const now = new Date();
    const currM = now.getMonth();
    const currY = now.getFullYear();

    const activeStreaks = [];
    streaks.forEach((st) => {
      if (st.dates && Array.isArray(st.dates)) {
        const filtered = st.dates.filter((dt) => {
          const d = new Date(dt);
          return d.getMonth() === currM && d.getFullYear() === currY;
        });
        if (filtered.length >= 2) {
          activeStreaks.push(filtered.length);
        }
      } else if (st.days >= 2) {
        // Fallback if start/end matches
        const d = new Date(st.start || st.end);
        if (d.getMonth() === currM && d.getFullYear() === currY) {
          activeStreaks.push(st.days);
        }
      }
    });

    return activeStreaks;
  };

  // 5. Filter students by search term
  const filteredStudents = useMemo(() => {
    if (!searchQuery.trim()) return students;
    const q = searchQuery.toLowerCase();
    return students.filter(
      (s) =>
        (s.student_name && s.student_name.toLowerCase().includes(q)) ||
        (s.SPRIDEN_student_id && s.SPRIDEN_student_id.toLowerCase().includes(q))
    );
  }, [students, searchQuery]);

  // 6. WhatsApp Trigger with Continuous Range
  const handleSendWhatsApp = (dateOrStreak) => {
    if (!activeStudent) return;
    const phone =
      (studentAbsentDates.length > 0 && studentAbsentDates[0].phone) ||
      activeStudent.whatsapp ||
      activeStudent.mobile ||
      '';

    if (!phone || phone.trim() === '') {
      alert('Parent WhatsApp contact number is not registered for this student.');
      return;
    }

    let text = '';
    if (typeof dateOrStreak === 'string') {
      text = `Hello, this is to inform you that ${activeStudent.student_name} was absent on ${dateOrStreak}.`;
    } else if (dateOrStreak && dateOrStreak.start) {
      if (dateOrStreak.start === dateOrStreak.end) {
        text = `Hello, this is to inform you that ${activeStudent.student_name} was absent on ${dateOrStreak.start}.`;
      } else {
        text = `Hello, this is to inform you that ${activeStudent.student_name} was absent continuously from ${dateOrStreak.start} to ${dateOrStreak.end} (${dateOrStreak.days} days).`;
      }
    } else if (customMsg) {
      text = customMsg;
    } else {
      text = `Hello, this is to inform you about the attendance of ${activeStudent.student_name}.`;
    }

    const cleanPhone = phone.replace(/[^0-9]/g, '');
    const formattedPhone = cleanPhone.length === 10 ? `91${cleanPhone}` : cleanPhone;
    const url = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
  };

  // 7. Export Table to CSV
  const handleExportCSV = () => {
    if (filteredStudents.length === 0) return;
    const headers = [
      '#',
      'Student ID',
      'Student Name',
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'May',
      'Jun',
      'Jul',
      'Aug',
      'Sep',
      'Oct',
      'Nov',
      'Dec',
      'Yearly Absentee',
      'Current Month Streaks'
    ];

    const rows = filteredStudents.map((s, idx) => {
      const streaks = getCurrentMonthStreaks(s.continuous_absent) || [];
      const streakStr = streaks.length > 0 ? streaks.map((d) => `${d} days`).join(', ') : '-';
      return [
        idx + 1,
        `"${s.SPRIDEN_student_id || ''}"`,
        `"${s.student_name || ''}"`,
        s.jan || 0,
        s.feb || 0,
        s.mar || 0,
        s.apr || 0,
        s.may || 0,
        s.jun || 0,
        s.jul || 0,
        s.aug || 0,
        s.sep || 0,
        s.oct || 0,
        s.nov || 0,
        s.dec || 0,
        s.total_absent || 0,
        `"${streakStr}"`
      ];
    });

    const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `Absentee_Report_${selectedStd}_${selectedDiv}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  // 8. Copy Table Data to Clipboard
  const handleCopyTable = () => {
    if (filteredStudents.length === 0) return;
    const header = ['#', 'ID', 'Name', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total'].join('\t');
    const rows = filteredStudents.map((s, idx) =>
      [
        idx + 1,
        s.SPRIDEN_student_id,
        s.student_name,
        s.jan || 0,
        s.feb || 0,
        s.mar || 0,
        s.apr || 0,
        s.may || 0,
        s.jun || 0,
        s.jul || 0,
        s.aug || 0,
        s.sep || 0,
        s.oct || 0,
        s.nov || 0,
        s.dec || 0,
        s.total_absent || 0
      ].join('\t')
    );

    navigator.clipboard.writeText([header, ...rows].join('\n'));
    setCopyFeedback(true);
    setTimeout(() => setCopyFeedback(false), 2000);
  };

  // Calendar Helpers for Active Student Modal
  const absentDateSet = useMemo(() => {
    const set = new Set();
    studentAbsentDates.forEach((d) => {
      if (d.date) set.add(d.date.split('T')[0]);
    });
    return set;
  }, [studentAbsentDates]);

  // Grouped Ranges for Active Student
  const studentStreaks = useMemo(() => {
    if (!studentAbsentDates || studentAbsentDates.length === 0) return [];
    const formatted = studentAbsentDates.map((d) => d.date.split('T')[0]).sort();

    const ranges = [];
    if (formatted.length === 0) return ranges;

    let start = formatted[0];
    let prev = formatted[0];

    for (let i = 1; i <= formatted.length; i++) {
      const current = formatted[i];
      const prevDate = new Date(prev);
      prevDate.setDate(prevDate.getDate() + 1);
      const expectedNext = prevDate.toISOString().split('T')[0];

      if (current !== expectedNext) {
        const startDate = new Date(start);
        const endDate = new Date(prev);
        const diffDays = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
        ranges.push({ start, end: prev, days: diffDays });
        start = current;
      }
      prev = current;
    }
    return ranges;
  }, [studentAbsentDates]);

  // Calendar Days generator for the month view
  const calendarDays = useMemo(() => {
    const year = activeCalendarYear;
    const month = activeCalendarMonth;
    const firstDayIndex = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const days = [];
    // Padding before 1st of month
    for (let i = 0; i < firstDayIndex; i++) {
      days.push({ dayNumber: null, dateStr: null });
    }
    // Days in current month
    for (let d = 1; d <= daysInMonth; d++) {
      const mm = String(month + 1).padStart(2, '0');
      const dd = String(d).padStart(2, '0');
      const dateStr = `${year}-${mm}-${dd}`;
      days.push({
        dayNumber: d,
        dateStr,
        isAbsent: absentDateSet.has(dateStr)
      });
    }
    return days;
  }, [activeCalendarYear, activeCalendarMonth, absentDateSet]);

  return (
    <div className="flex h-screen bg-[#F7F7F5] text-[#202020] font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Page Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#E7E7E4]">
            <div>
              <div className="flex items-center gap-2">
                <span className="p-2 rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
                  <FiAlertTriangle className="w-5 h-5" />
                </span>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                  Students Absentee Report
                </h1>
              </div>
              <p className="text-xs sm:text-sm text-slate-500 mt-1">
                Month-by-month attendance matrix, continuous absence streaks tracking, and WhatsApp parent notifications.
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                type="button"
                onClick={handleCopyTable}
                disabled={filteredStudents.length === 0}
                className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-[#E7E7E4] bg-white text-slate-700 hover:bg-slate-50 shadow-sm transition disabled:opacity-50"
              >
                {copyFeedback ? (
                  <>
                    <FiCheckCircle className="text-emerald-500" /> Copied!
                  </>
                ) : (
                  <>
                    <FiCopy /> Copy
                  </>
                )}
              </button>
              <button
                type="button"
                onClick={handleExportCSV}
                disabled={filteredStudents.length === 0}
                className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-[#E7E7E4] bg-white text-slate-700 hover:bg-slate-50 shadow-sm transition disabled:opacity-50"
              >
                <FiDownload /> Excel / CSV
              </button>
              <button
                type="button"
                onClick={() => window.print()}
                className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-[#E7E7E4] bg-white text-slate-700 hover:bg-slate-50 shadow-sm transition"
              >
                <FiPrinter /> Print
              </button>
            </div>
          </div>

          {/* Filter Bar (Matching CI3 Absent_student view) */}
          <div className="bg-white rounded-xl shadow-sm border border-[#E7E7E4] p-4 sm:p-5">
            <form onSubmit={handleSearch} className="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
              {/* Academic Year */}
              <div>
                <label className="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                  Academic Year <span className="text-rose-500">*</span>
                </label>
                <select
                  value={selectedYear}
                  onChange={(e) => setSelectedYear(e.target.value)}
                  className="w-full px-3 py-2 text-sm bg-slate-50 border border-[#E7E7E4] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:bg-white transition"
                  required
                >
                  <option value="">Select Academic Year</option>
                  {academicYears.map((y) => (
                    <option key={y.stvacdm_id} value={y.stvacdm_id}>
                      {y.stvacdm_year}
                    </option>
                  ))}
                </select>
              </div>

              {/* Class / Standard */}
              <div>
                <label className="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                  Class <span className="text-rose-500">*</span>
                </label>
                <select
                  value={selectedStd}
                  onChange={(e) => setSelectedStd(e.target.value)}
                  className="w-full px-3 py-2 text-sm bg-slate-50 border border-[#E7E7E4] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:bg-white transition"
                  required
                >
                  <option value="">Select Class</option>
                  {standards.map((cls) => (
                    <option key={cls} value={cls}>
                      {cls}
                    </option>
                  ))}
                </select>
              </div>

              {/* Division */}
              <div>
                <label className="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                  Division <span className="text-rose-500">*</span>
                </label>
                <select
                  value={selectedDiv}
                  onChange={(e) => setSelectedDiv(e.target.value)}
                  className="w-full px-3 py-2 text-sm bg-slate-50 border border-[#E7E7E4] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:bg-white transition"
                  required
                >
                  <option value="">Select Division</option>
                  {divisions.map((d) => (
                    <option key={d} value={d}>
                      {d}
                    </option>
                  ))}
                </select>
              </div>

              {/* Action Buttons */}
              <div className="flex items-center gap-2">
                <button
                  type="submit"
                  disabled={loading}
                  className="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#1976d2] hover:bg-[#1565c0] rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer"
                >
                  {loading ? (
                    <>
                      <FiRefreshCw className="w-4 h-4 animate-spin" /> Searching...
                    </>
                  ) : (
                    <>
                      <FiSearch className="w-4 h-4" /> Search
                    </>
                  )}
                </button>
              </div>
            </form>

            {errorMsg && (
              <div className="mt-3 p-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                {errorMsg}
              </div>
            )}
          </div>

          {/* Quick Filter Search & Stats Bar */}
          {searched && (
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-[#E7E7E4] shadow-sm">
              <div className="flex items-center gap-4 text-xs font-medium text-slate-600">
                <span>
                  Total Enrolled: <strong className="text-slate-900">{students.length}</strong>
                </span>
                <span>
                  With Absences:{' '}
                  <strong className="text-rose-600">
                    {students.filter((s) => Number(s.total_absent) > 0).length}
                  </strong>
                </span>
                <span>
                  Consecutive Streaks (2+ Days):{' '}
                  <strong className="text-amber-600">
                    {
                      students.filter(
                        (s) => s.continuous_absent && s.continuous_absent.length > 0
                      ).length
                    }
                  </strong>
                </span>
              </div>

              <div className="relative">
                <FiSearch className="absolute left-3 top-2.5 text-slate-400 w-3.5 h-3.5" />
                <input
                  type="text"
                  placeholder="Filter student name or ID..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="w-full sm:w-64 pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-[#E7E7E4] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:bg-white transition"
                />
              </div>
            </div>
          )}

          {/* Absentee Matrix Table */}
          <div className="bg-white rounded-xl shadow-sm border border-[#E7E7E4] overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-100/80 text-slate-700 font-bold border-b border-[#E7E7E4] text-[11px] uppercase tracking-wider">
                    <th className="p-2.5 text-center w-10">#</th>
                    <th className="p-2.5 min-w-[180px]">Student Name</th>
                    <th className="p-2 text-center w-10">Jan</th>
                    <th className="p-2 text-center w-10">Feb</th>
                    <th className="p-2 text-center w-10">Mar</th>
                    <th className="p-2 text-center w-10">Apr</th>
                    <th className="p-2 text-center w-10">May</th>
                    <th className="p-2 text-center w-10">Jun</th>
                    <th className="p-2 text-center w-10">Jul</th>
                    <th className="p-2 text-center w-10">Aug</th>
                    <th className="p-2 text-center w-10">Sep</th>
                    <th className="p-2 text-center w-10">Oct</th>
                    <th className="p-2 text-center w-10">Nov</th>
                    <th className="p-2 text-center w-10">Dec</th>
                    <th className="p-2.5 text-center font-bold text-slate-900 bg-slate-200/50 w-24">
                      Yearly Absentee
                    </th>
                    <th className="p-2.5 text-center min-w-[150px]">
                      Absentee Current Month (Streaks)
                    </th>
                    <th className="p-2.5 text-center w-24">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan="17" className="p-12 text-center text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-2" />
                        Analyzing attendance records and calculating streaks...
                      </td>
                    </tr>
                  ) : !searched ? (
                    <tr>
                      <td colSpan="17" className="p-10 text-center text-slate-400">
                        Please select Academic Year, Class, and Division above and click <strong>Search</strong>.
                      </td>
                    </tr>
                  ) : filteredStudents.length === 0 ? (
                    <tr>
                      <td colSpan="17" className="p-10 text-center text-slate-400 font-medium">
                        No attendance records found for this class & division.
                      </td>
                    </tr>
                  ) : (
                    filteredStudents.map((s, idx) => {
                      const currentMonthStreaks = getCurrentMonthStreaks(s.continuous_absent);
                      const totalAbs =
                        Number(s.total_absent) ||
                        MONTHS.reduce((acc, m) => acc + (parseInt(s[m.key], 10) || 0), 0);

                      return (
                        <tr
                          key={s.id || idx}
                          onClick={() => handleOpenStudentModal(s)}
                          className="hover:bg-[#e3f2fd]/40 transition cursor-pointer group"
                        >
                          <td className="p-2.5 text-center text-slate-400 font-mono text-[11px]">
                            {idx + 1}
                          </td>
                          <td className="p-2.5">
                            <div className="font-semibold text-slate-900 group-hover:text-[#1976d2] transition">
                              {s.student_name || 'Unknown Student'}
                            </div>
                            <div className="text-[10px] text-slate-400 font-mono">
                              ID: {s.SPRIDEN_student_id}
                            </div>
                          </td>

                          {/* 12 Months Columns */}
                          {MONTHS.map((m) => {
                            const val = parseInt(s[m.key], 10) || 0;
                            return (
                              <td
                                key={m.key}
                                className={`p-2 text-center text-[11px] font-medium ${
                                  val > 0
                                    ? 'text-rose-600 font-bold bg-rose-50/30'
                                    : 'text-slate-400'
                                }`}
                              >
                                {val > 0 ? val : '-'}
                              </td>
                            );
                          })}

                          {/* Yearly Absentee */}
                          <td className="p-2.5 text-center font-bold text-slate-900 bg-slate-100/50">
                            {totalAbs > 0 ? (
                              <span className="text-rose-600 font-bold">{totalAbs}</span>
                            ) : (
                              <span className="text-slate-400">0</span>
                            )}
                          </td>

                          {/* Absentee Current Month Streaks (Matching CI3 badge) */}
                          <td className="p-2.5 text-center">
                            {currentMonthStreaks && currentMonthStreaks.length > 0 ? (
                              <div className="flex flex-wrap items-center justify-center gap-1">
                                {currentMonthStreaks.map((days, sIdx) => (
                                  <span
                                    key={sIdx}
                                    className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white shadow-xs"
                                    title={`${days} consecutive days absent in current month`}
                                  >
                                    {days} days
                                  </span>
                                ))}
                              </div>
                            ) : (
                              <span className="text-slate-400">-</span>
                            )}
                          </td>

                          {/* Action Button */}
                          <td className="p-2.5 text-center" onClick={(e) => e.stopPropagation()}>
                            <button
                              type="button"
                              onClick={() => handleOpenStudentModal(s)}
                              className="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold text-[#1976d2] bg-[#e3f2fd] hover:bg-[#e3f2fd] rounded-md transition"
                            >
                              <FiCalendar className="w-3 h-3" /> Calendar
                            </button>
                          </td>
                        </tr>
                      );
                    })
                  )}
                </tbody>
              </table>
            </div>

            {filteredStudents.length > 0 && (
              <div className="p-3 bg-slate-50 border-t border-[#E7E7E4] text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                  Showing <strong>{filteredStudents.length}</strong> of <strong>{students.length}</strong> students.
                  Click on any row to open the interactive attendance calendar and send WhatsApp alerts.
                </div>
              </div>
            )}
          </div>
        </main>
      </div>

      {/* ═══════════════════════════════════════════════════════════════
          INTERACTIVE ATTENDANCE CALENDAR & WHATSAPP MODAL
          Matching CI3 #studentModal & datepicker functionality
         ═══════════════════════════════════════════════════════════════ */}
      {modalOpen && activeStudent && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
          <div className="bg-white rounded-2xl shadow-2xl border border-[#E7E7E4] max-w-2xl w-full overflow-hidden flex flex-col max-h-[92vh]">
            {/* Modal Header */}
            <div className="px-6 py-4 bg-gradient-to-r from-slate-800 to-[#1565c0] text-white flex items-center justify-between">
              <div>
                <h3 className="text-lg font-bold tracking-tight">
                  Attendance - {activeStudent.student_name}
                </h3>
                <p className="text-xs text-slate-300 mt-0.5 font-mono">
                  Student ID: {activeStudent.SPRIDEN_student_id} &bull; Std {selectedStd} - {selectedDiv}
                </p>
              </div>
              <button
                type="button"
                onClick={() => setModalOpen(false)}
                className="p-1 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition"
              >
                <FiX className="w-5 h-5" />
              </button>
            </div>

            {/* Modal Body */}
            <div className="p-6 overflow-y-auto space-y-6 flex-1">
              {loadingModal ? (
                <div className="p-12 text-center text-slate-400">
                  <FiRefreshCw className="w-8 h-8 animate-spin mx-auto text-[#1976d2] mb-2" />
                  Loading student calendar & absence dates...
                </div>
              ) : (
                <>
                  {/* Summary Metric Strip */}
                  <div className="grid grid-cols-3 gap-3">
                    <div className="p-3 rounded-xl bg-slate-50 border border-[#E7E7E4]">
                      <div className="text-[10px] font-bold text-slate-400 uppercase">
                        Total Absences
                      </div>
                      <div className="text-xl font-bold text-rose-600 mt-0.5">
                        {studentAbsentDates.length} days
                      </div>
                    </div>
                    <div className="p-3 rounded-xl bg-slate-50 border border-[#E7E7E4]">
                      <div className="text-[10px] font-bold text-slate-400 uppercase">
                        Consecutive Streaks
                      </div>
                      <div className="text-xl font-bold text-amber-600 mt-0.5">
                        {studentStreaks.filter((st) => st.days >= 2).length}
                      </div>
                    </div>
                    <div className="p-3 rounded-xl bg-slate-50 border border-[#E7E7E4]">
                      <div className="text-[10px] font-bold text-slate-400 uppercase">
                        Contact Number
                      </div>
                      <div className="text-sm font-semibold text-slate-900 mt-1 truncate">
                        {studentAbsentDates.length > 0 && studentAbsentDates[0].phone
                          ? studentAbsentDates[0].phone
                          : activeStudent.whatsapp || activeStudent.mobile || 'Not available'}
                      </div>
                    </div>
                  </div>

                  {/* Calendar Month Navigation */}
                  <div className="border border-[#E7E7E4] rounded-xl p-4 bg-white shadow-xs">
                    <div className="flex items-center justify-between mb-3 pb-2 border-b border-[#E7E7E4]">
                      <h4 className="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                        <FiCalendar className="text-[#1976d2]" />
                        {new Date(activeCalendarYear, activeCalendarMonth).toLocaleString('default', {
                          month: 'long',
                          year: 'numeric'
                        })}
                      </h4>

                      <div className="flex items-center gap-1">
                        <button
                          type="button"
                          onClick={() => {
                            if (activeCalendarMonth === 0) {
                              setActiveCalendarMonth(11);
                              setActiveCalendarYear((y) => y - 1);
                            } else {
                              setActiveCalendarMonth((m) => m - 1);
                            }
                          }}
                          className="p-1.5 rounded-lg border border-[#E7E7E4] hover:bg-slate-100 transition"
                        >
                          <FiChevronLeft className="w-4 h-4 text-slate-600" />
                        </button>
                        <button
                          type="button"
                          onClick={() => {
                            if (activeCalendarMonth === 11) {
                              setActiveCalendarMonth(0);
                              setActiveCalendarYear((y) => y + 1);
                            } else {
                              setActiveCalendarMonth((m) => m + 1);
                            }
                          }}
                          className="p-1.5 rounded-lg border border-[#E7E7E4] hover:bg-slate-100 transition"
                        >
                          <FiChevronRight className="w-4 h-4 text-slate-600" />
                        </button>
                      </div>
                    </div>

                    {/* Calendar Grid */}
                    <div className="grid grid-cols-7 gap-1 text-center text-xs">
                      {['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map((d) => (
                        <div key={d} className="font-bold text-slate-400 py-1 text-[11px]">
                          {d}
                        </div>
                      ))}

                      {calendarDays.map((item, idx) => {
                        if (!item.dayNumber) {
                          return <div key={idx} className="h-9" />;
                        }

                        const isAbsent = item.isAbsent;

                        return (
                          <button
                            key={idx}
                            type="button"
                            onClick={() => {
                              if (isAbsent) {
                                handleSendWhatsApp(item.dateStr);
                              }
                            }}
                            className={`h-9 flex flex-col items-center justify-center rounded-lg font-medium transition text-xs relative ${
                              isAbsent
                                ? 'bg-rose-500 text-white font-bold hover:bg-rose-600 shadow-xs cursor-pointer'
                                : 'bg-slate-50 text-slate-700 hover:bg-slate-100'
                            }`}
                            title={
                              isAbsent
                                ? `Absent on ${item.dateStr} - Click to notify parent on WhatsApp`
                                : item.dateStr
                            }
                          >
                            <span>{item.dayNumber}</span>
                            {isAbsent && (
                              <span className="w-1.5 h-1.5 bg-white rounded-full mt-0.5" />
                            )}
                          </button>
                        );
                      })}
                    </div>

                    <div className="mt-3 pt-2 border-t border-[#E7E7E4] flex items-center justify-between text-[11px] text-slate-500">
                      <div className="flex items-center gap-3">
                        <span className="flex items-center gap-1">
                          <span className="w-3 h-3 rounded bg-rose-500 inline-block" /> Absent Day
                        </span>
                        <span className="flex items-center gap-1">
                          <span className="w-3 h-3 rounded bg-slate-100 border border-[#E7E7E4] inline-block" /> Present / Normal
                        </span>
                      </div>
                      <span className="text-slate-400">Click any absent day to send WhatsApp alert</span>
                    </div>
                  </div>

                  {/* Continuous Streaks Breakdown (Matching CI3 calculation) */}
                  <div className="space-y-2">
                    <h4 className="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                      <FiAlertTriangle className="text-amber-500" /> Continuous Absence Streaks
                    </h4>

                    {studentStreaks.length === 0 ? (
                      <p className="text-xs text-slate-400 italic">No absence streaks recorded for this student.</p>
                    ) : (
                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-36 overflow-y-auto">
                        {studentStreaks.map((st, i) => (
                          <div
                            key={i}
                            className={`p-2.5 rounded-lg border flex items-center justify-between text-xs ${
                              st.days >= 2
                                ? 'bg-amber-50/60 border-amber-200 text-amber-900'
                                : 'bg-slate-50 border-[#E7E7E4] text-slate-700'
                            }`}
                          >
                            <div>
                              <div className="font-semibold">
                                {st.days} {st.days === 1 ? 'day' : 'consecutive days'}
                              </div>
                              <div className="text-[11px] text-slate-500">
                                {st.start} {st.start !== st.end ? `to ${st.end}` : ''}
                              </div>
                            </div>

                            <button
                              type="button"
                              onClick={() => handleSendWhatsApp(st)}
                              className="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 rounded-md transition"
                              title="Send WhatsApp notification for this streak"
                            >
                              <FaWhatsapp className="w-3 h-3" /> Notify
                            </button>
                          </div>
                        ))}
                      </div>
                    )}
                  </div>

                  {/* Parent WhatsApp Notification Composer */}
                  <div className="p-4 rounded-xl bg-slate-50 border border-[#E7E7E4] space-y-2">
                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                      <span>WhatsApp Alert Message</span>
                      <span className="text-[11px] text-emerald-600 font-medium flex items-center gap-1">
                        <FaWhatsapp /> Target Phone:{' '}
                        {studentAbsentDates.length > 0 && studentAbsentDates[0].phone
                          ? studentAbsentDates[0].phone
                          : activeStudent.whatsapp || activeStudent.mobile || 'None'}
                      </span>
                    </label>
                    <textarea
                      value={customMsg}
                      onChange={(e) => setCustomMsg(e.target.value)}
                      rows={2}
                      className="w-full p-2.5 text-xs bg-white border border-[#E7E7E4] rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                      placeholder="Enter WhatsApp notification message..."
                    />
                    <div className="flex justify-end">
                      <button
                        type="button"
                        onClick={() => handleSendWhatsApp()}
                        className="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition"
                      >
                        <FaWhatsapp className="w-4 h-4" /> Send WhatsApp Message
                      </button>
                    </div>
                  </div>
                </>
              )}
            </div>

            {/* Modal Footer */}
            <div className="px-6 py-3 bg-slate-50 border-t border-[#E7E7E4] flex items-center justify-end">
              <button
                type="button"
                onClick={() => setModalOpen(false)}
                className="px-4 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 transition"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
