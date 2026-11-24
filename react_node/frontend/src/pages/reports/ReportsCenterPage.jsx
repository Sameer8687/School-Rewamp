import React, { useState, useEffect, useMemo } from 'react';
import { useLocation } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import reportService from '../../services/reportService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiPieChart,
  FiUsers,
  FiDollarSign,
  FiCheckSquare,
  FiFileText,
  FiActivity,
  FiShield,
  FiSearch,
  FiDownload,
  FiPrinter,
  FiRefreshCw,
  FiFilter,
  FiCalendar,
  FiArrowUpRight,
  FiAlertCircle,
  FiCheckCircle,
  FiChevronDown,
  FiPhone,
  FiClock,
  FiEye,
} from 'react-icons/fi';

export default function ReportsCenterPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const location = useLocation();
  const [activeTab, setActiveTab] = useState('students');
  const [loading, setLoading] = useState(true);

  // Global Filter States
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStandard, setSelectedStandard] = useState('all');
  const [selectedDivision, setSelectedDivision] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');

  // Top KPIs
  const [kpis, setKpis] = useState(null);

  // Tab 1: Student Reports State
  const [studentReportType, setStudentReportType] = useState('all'); // all, new, readmission, rte, summary, balance_student, inactive
  const [studentData, setStudentData] = useState([]);

  // Auto-route detection for CI3 Reports & New Reports links
  useEffect(() => {
    const p = location.pathname.toLowerCase();
    if (p.includes('current_inactive') || p.includes('inactive')) {
      setActiveTab('students');
      setStudentReportType('inactive');
    } else if (p.includes('new-addmission') || p.includes('new_addmission') || p.includes('new-admission')) {
      setActiveTab('students');
      setStudentReportType('new');
    } else if (p.includes('readdmission-report') || p.includes('readmission')) {
      setActiveTab('students');
      setStudentReportType('readmission');
    } else if (p.includes('admission-summary') || p.includes('admission_summary')) {
      setActiveTab('students');
      setStudentReportType('summary');
    } else if (p.includes('balance-student')) {
      setActiveTab('students');
      setStudentReportType('balance_student');
    } else if (p.includes('rte-report') || p.includes('rte_report')) {
      setActiveTab('students');
      setStudentReportType('rte');
    } else if (
      p.includes('addmission-report') ||
      p.includes('admission-report') ||
      p.includes('admission_report') ||
      p.includes('admissionreport') ||
      p.includes('all_reports') ||
      p.includes('report/report') ||
      p.includes('report/reports') ||
      p.includes('all-students') ||
      p.includes('student-report')
    ) {
      setActiveTab('students');
      setStudentReportType('all');
    } else if (p.includes('daily-fees') || p.includes('daily_fees') || p.includes('monthly_fees')) {
      setActiveTab('fees');
      setFeesReportType('daily');
    } else if (p.includes('datewise-fees') || p.includes('date_wise')) {
      setActiveTab('fees');
      setFeesReportType('datewise');
    } else if (p.includes('fees-installment') || p.includes('fees_instment')) {
      setActiveTab('fees');
      setFeesReportType('installments');
    } else if (p.includes('balance-report') || p.includes('balance_rep') || p.includes('balance_fees') || p.includes('current_year_fees')) {
      setActiveTab('fees');
      setFeesReportType('balance');
    } else if (p.includes('exam_alert') || p.includes('exam-alert') || p.includes('fees_alert')) {
      setActiveTab('fees');
      setFeesReportType('zero_paid');
    } else if (p.includes('fees-report') || p.includes('fees_report')) {
      setActiveTab('fees');
    } else if (p.includes('notes-report') || p.includes('notes_report') || p.includes('notes-report')) {
      setActiveTab('activity');
    } else if (p.includes('teacher-activity') || p.includes('teacher_activity')) {
      setActiveTab('activity');
    } else if (p.includes('stvlogh') || p.includes('stvfrmh') || p.includes('login_tracking')) {
      setActiveTab('audit');
    } else if (p.includes('lc_report') || p.includes('lc_isshu') || p.includes('certificates')) {
      setActiveTab('certificates');
    } else if (p.includes('classreport') || p.includes('class_report')) {
      setActiveTab('students');
      setStudentReportType('all');
    }
  }, [location.pathname]);

  // Tab 2: Fees Reports State
  const [feesReportType, setFeesReportType] = useState('daily'); // daily, datewise, balance, zero_paid
  const [feesFromDate, setFeesFromDate] = useState(new Date().toISOString().slice(0, 10));
  const [feesToDate, setFeesToDate] = useState(new Date().toISOString().slice(0, 10));
  const [feesData, setFeesData] = useState([]);
  const [feesTotalAmount, setFeesTotalAmount] = useState(0);

  // Tab 3: Attendance Reports State
  const [attendanceDate, setAttendanceDate] = useState(new Date().toISOString().slice(0, 10));
  const [absentStudents, setAbsentStudents] = useState([]);
  const [classAttendanceSummary, setClassAttendanceSummary] = useState([]);

  // Tab 4: Certificates State
  const [certificateType, setCertificateType] = useState('issued'); // issued, requests
  const [certificateData, setCertificateData] = useState([]);

  // Tab 5: Teacher Activity State
  const [teacherActivityDate, setTeacherActivityDate] = useState(new Date().toISOString().slice(0, 10));
  const [teacherData, setTeacherData] = useState([]);

  // Tab 6: Audit State
  const [auditData, setAuditData] = useState([]);

  // Selected Detail Modal
  const [selectedItem, setSelectedItem] = useState(null);

  // 1. Load initial academic years and class options
  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          const years = res.data.options.academicYears || [];
          const classes = res.data.options.classes || [];
          setAcademicYears(years);
          setStandards(classes);

          const active = years.find((y) => y.is_active) || years[0];
          if (active) {
            setSelectedYear(String(active.stvacdm_id));
          }
        }
      } catch (err) {
        console.error('Failed to load initial options:', err);
      }
    }
    loadOptions();
  }, []);

  // 2. Load KPIs whenever selected year changes
  useEffect(() => {
    if (!selectedYear) return;
    async function fetchKPIs() {
      try {
        const res = await reportService.getKPIs(selectedYear);
        if (res.data?.success) {
          setKpis(res.data.kpis);
        }
      } catch (err) {
        console.error('Failed to fetch report KPIs:', err);
      }
    }
    fetchKPIs();
  }, [selectedYear]);

  // 3. Main Data Fetcher based on Active Tab & Filters
  const fetchTabData = async () => {
    if (!selectedYear) return;
    setLoading(true);
    try {
      if (activeTab === 'students') {
        const res = await reportService.getStudentReports({
          academicYear: selectedYear,
          type: studentReportType,
          standard: selectedStandard,
          division: selectedDivision,
          search: searchQuery,
        });
        if (res.data?.success) {
          setStudentData(res.data.records || []);
        }
      } else if (activeTab === 'fees') {
        const res = await reportService.getFeesReports({
          academicYear: selectedYear,
          type: feesReportType,
          from_date: feesFromDate,
          to_date: feesToDate,
          standard: selectedStandard,
          division: selectedDivision,
        });
        if (res.data?.success) {
          setFeesData(res.data.records || []);
          setFeesTotalAmount(res.data.totalAmount || res.data.totalBalance || 0);
        }
      } else if (activeTab === 'attendance') {
        const res = await reportService.getAttendanceReports({
          academicYear: selectedYear,
          date: attendanceDate,
          standard: selectedStandard,
          division: selectedDivision,
        });
        if (res.data?.success) {
          setAbsentStudents(res.data.absentStudents || []);
          setClassAttendanceSummary(res.data.classSummary || []);
        }
      } else if (activeTab === 'certificates') {
        const res = await reportService.getCertificatesReports({
          type: certificateType,
        });
        if (res.data?.success) {
          setCertificateData(res.data.records || []);
        }
      } else if (activeTab === 'activity') {
        const res = await reportService.getTeacherActivityReports({
          academicYear: selectedYear,
          date: teacherActivityDate,
        });
        if (res.data?.success) {
          setTeacherData(res.data.teachers || []);
        }
      } else if (activeTab === 'audit') {
        const res = await reportService.getAuditReports({ limit: 100 });
        if (res.data?.success) {
          setAuditData(res.data.records || []);
        }
      }
    } catch (err) {
      console.error(`Error fetching tab ${activeTab}:`, err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchTabData();
  }, [
    activeTab,
    selectedYear,
    selectedStandard,
    selectedDivision,
    studentReportType,
    feesReportType,
    feesFromDate,
    feesToDate,
    attendanceDate,
    certificateType,
    teacherActivityDate,
  ]);

  // Handle Search Debounce / Trigger
  const handleSearchSubmit = (e) => {
    e.preventDefault();
    fetchTabData();
  };

  // CSV Export utility
  const exportToCSV = () => {
    let filename = `olive_school_${activeTab}_report.csv`;
    let rows = [];

    if (activeTab === 'students') {
      if (studentReportType === 'summary') {
        rows = studentData.map((r) => ({
          Standard: r.STVCLAS_standard,
          Division: r.STVCLAS_class,
          Total: r.total_students,
          Boys: r.boys,
          Girls: r.girls,
          RTE: r.rte_count,
          Active: r.active_count,
          Inactive: r.inactive_count,
        }));
      } else if (studentReportType === 'balance_student') {
        rows = studentData.map((r) => ({
          StudentID: r.SPRIDEN_student_id,
          GR_Number: r.spriden_gr_number,
          Name: r.student_name,
          Standard: r.STVCLAS_standard,
          Division: r.STVCLAS_class,
          AnnualClassFee: r.total_class_fees || 0,
          TotalPaid: r.total_fees_paid || 0,
          Exemptions: r.total_exemptions || 0,
          BalanceDue: r.balance_amount || 0,
          Mobile: r.stvpcnf_mobile,
        }));
      } else {
        rows = studentData.map((r) => ({
          StudentID: r.SPRIDEN_student_id,
          GR_Number: r.spriden_gr_number,
          Name: r.student_name,
          Standard: r.STVCLAS_standard,
          Division: r.STVCLAS_class,
          Gender: r.SPRIDEN_gender,
          Status: r.spriden_student_status === 'A' ? 'Active' : 'Inactive',
          RTE: r.rte_status === 'Yes' ? 'Yes' : 'No',
          Mobile: r.stvpcnf_mobile,
          FeesPaid: r.total_fees_paid || 0,
        }));
      }
    } else if (activeTab === 'fees') {
      if (feesReportType === 'installments') {
        rows = feesData.map((r) => ({
          StudentID: r.SPRIDEN_student_id,
          Name: r.student_name,
          Class: `${r.STVCLAS_standard || ''}-${r.STVCLAS_class || ''}`,
          AnnualFee: r.total_fee || 0,
          TotalPaid: r.paid_fee || 0,
          InstallmentHistory: r.installments || 'None',
          Mobile: r.stvpcnf_mobile,
        }));
      } else {
        rows = feesData.map((r) => ({
          StudentID: r.SPRIDEN_student_id,
          Name: r.student_name,
          Class: `${r.STVCLAS_standard || ''}-${r.STVCLAS_class || ''}`,
          Amount: r.sprfees_amount || r.balance_fee || 0,
          Date: r.sprfees_feesdate || r.last_payment_date || '-',
          Mode: r.payment_mode || 'Cash',
          Mobile: r.stvpcnf_mobile,
        }));
      }
    } else if (activeTab === 'attendance') {
      rows = absentStudents.map((r) => ({
        StudentID: r.SPRIDEN_student_id,
        Name: r.student_name,
        Standard: r.STVCLAS_standard,
        Division: r.STVCLAS_class,
        RollNo: r.STVCLAS_rollno,
        Date: r.SPRATND_date,
        Status: 'Absent',
        Mobile: r.stvpcnf_mobile,
      }));
    } else if (activeTab === 'certificates') {
      rows = certificateData.map((r) => ({
        StudentID: r.SPRIDEN_student_id,
        Name: r.student_name,
        LeavingDate: r.leaving_date || r.lc_date || '-',
        Reason: r.leaving_reason || '-',
        NextSchool: r.student_next_school || '-',
        Status: r.status || 'Issued',
      }));
    } else if (activeTab === 'activity') {
      rows = teacherData.map((r) => ({
        Code: r.teacher_code,
        Name: r.teacher_name,
        Mobile: r.stvpcnf_mobile,
        AttendanceTakenToday: r.attendance_taken_today > 0 ? 'Yes' : 'No',
        AssignedPeriods: r.assigned_periods,
      }));
    } else if (activeTab === 'audit') {
      rows = auditData.map((r) => ({
        ID: r.id,
        Date: r.log_date,
        Time: r.log_time,
        UserID: r.user_id,
        Name: r.user_name,
        Role: r.role,
        Status: r.status,
      }));
    }

    if (!rows.length) {
      alert('No data available to export.');
      return;
    }

    const headers = Object.keys(rows[0]).join(',');
    const csvContent = [
      headers,
      ...rows.map((row) =>
        Object.values(row)
          .map((val) => `"${String(val ?? '').replace(/"/g, '""')}"`)
          .join(',')
      ),
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  // Handle LC Request status approval
  const handleApproveLcRequest = async (requestId, status) => {
    try {
      const res = await reportService.updateLcRequestStatus(requestId, status);
      if (res.data?.success) {
        alert(`LC Request has been ${status}.`);
        fetchTabData();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to update request');
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 md:p-6 lg:p-8 space-y-6">
          {/* Header Title & Actions */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 rounded-xl bg-gradient-to-tr from-[#1976d2] to-[#1565c0] flex items-center justify-center text-white shadow-md">
                <FiPieChart size={24} />
              </div>
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                  Reports & Analytics Center
                </h1>
                <p className="text-xs text-slate-500">
                  CodeIgniter 3 Unified Reporting Suite — Academic, Financial, Attendance & Operations
                </p>
              </div>
            </div>

            <div className="flex items-center flex-wrap gap-2.5">
              {/* Academic Year Selector */}
              <div className="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700">
                <span>Session:</span>
                <select
                  value={selectedYear}
                  onChange={(e) => setSelectedYear(e.target.value)}
                  className="bg-transparent border-none outline-none font-bold text-[#1976d2] cursor-pointer"
                >
                  {academicYears.map((y) => (
                    <option key={y.stvacdm_id} value={y.stvacdm_id}>
                      {y.stvacdm_year || `Year #${y.stvacdm_id}`} {y.is_active ? '★' : ''}
                    </option>
                  ))}
                </select>
              </div>

              {/* Refresh */}
              <button
                onClick={fetchTabData}
                className="p-2 text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer"
                title="Refresh Report"
              >
                <FiRefreshCw size={16} className={loading ? 'animate-spin' : ''} />
              </button>

              {/* Print */}
              <button
                onClick={() => window.print()}
                className="flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer"
              >
                <FiPrinter size={14} />
                <span>Print</span>
              </button>

              {/* Export CSV */}
              <button
                onClick={exportToCSV}
                className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-all cursor-pointer"
              >
                <FiDownload size={14} />
                <span>Export CSV</span>
              </button>
            </div>
          </div>

          {/* ─── Executive KPI Cards Ribbon ─── */}
          {kpis && (
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
              {/* Active Students */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div className="flex items-center justify-between text-slate-500 mb-1">
                  <span className="text-[11px] font-bold uppercase tracking-wider">Active Students</span>
                  <FiUsers className="text-blue-500" size={16} />
                </div>
                <div className="text-xl font-bold text-slate-900">{kpis.active_students.toLocaleString()}</div>
                <div className="text-[10px] text-slate-400 mt-1">Inactive: {kpis.inactive_students}</div>
              </div>

              {/* RTE Enrolled */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div className="flex items-center justify-between text-slate-500 mb-1">
                  <span className="text-[11px] font-bold uppercase tracking-wider">RTE Students</span>
                  <FiCheckSquare className="text-emerald-500" size={16} />
                </div>
                <div className="text-xl font-bold text-emerald-600">{kpis.rte_students.toLocaleString()}</div>
                <div className="text-[10px] text-slate-400 mt-1">Fee Exempted Quota</div>
              </div>

              {/* Fees Collected */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div className="flex items-center justify-between text-slate-500 mb-1">
                  <span className="text-[11px] font-bold uppercase tracking-wider">Fees Collected</span>
                  <FiDollarSign className="text-emerald-500" size={16} />
                </div>
                <div className="text-xl font-bold text-slate-900">
                  ₹{Number(kpis.fees_total_collected).toLocaleString('en-IN')}
                </div>
                <div className="text-[10px] text-emerald-600 font-semibold mt-1">
                  Today: ₹{Number(kpis.fees_today).toLocaleString('en-IN')}
                </div>
              </div>

              {/* Outstanding Balance */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div className="flex items-center justify-between text-slate-500 mb-1">
                  <span className="text-[11px] font-bold uppercase tracking-wider">Outstanding</span>
                  <FiAlertCircle className="text-amber-500" size={16} />
                </div>
                <div className="text-xl font-bold text-amber-600">
                  ₹{Number(kpis.total_balance_fees).toLocaleString('en-IN')}
                </div>
                <div className="text-[10px] text-slate-400 mt-1">Class Billing Balance</div>
              </div>

              {/* Today's Attendance */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div className="flex items-center justify-between text-slate-500 mb-1">
                  <span className="text-[11px] font-bold uppercase tracking-wider">Daily Absent</span>
                  <FiUsers className="text-rose-500" size={16} />
                </div>
                <div className="text-xl font-bold text-rose-600">{kpis.absent_today}</div>
                <div className="text-[10px] text-slate-400 mt-1">Present: {kpis.present_today}</div>
              </div>

              {/* LCs Issued */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div className="flex items-center justify-between text-slate-500 mb-1">
                  <span className="text-[11px] font-bold uppercase tracking-wider">Total LCs</span>
                  <FiFileText className="text-[#1976d2]" size={16} />
                </div>
                <div className="text-xl font-bold text-slate-900">{kpis.total_lc_issued.toLocaleString()}</div>
                <div className="text-[10px] text-slate-400 mt-1">Historical Issued</div>
              </div>
            </div>
          )}

          {/* ─── Navigation Tabs ─── */}
          <div className="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-2">
            {[
              { id: 'students', label: 'Students & Admissions', icon: FiUsers },
              { id: 'fees', label: 'Financial & Fees', icon: FiDollarSign },
              { id: 'attendance', label: 'Attendance & Absent', icon: FiCheckSquare },
              { id: 'certificates', label: 'Certificates & LC', icon: FiFileText },
              { id: 'activity', label: 'Teacher Activity', icon: FiActivity },
              { id: 'audit', label: 'Audit & Tracking', icon: FiShield },
            ].map((tab) => {
              const Icon = tab.icon;
              const isActive = activeTab === tab.id;
              return (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all cursor-pointer ${
                    isActive
                      ? 'bg-[#1976d2] text-white shadow-sm'
                      : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'
                  }`}
                >
                  <Icon size={15} />
                  <span>{tab.label}</span>
                </button>
              );
            })}
          </div>

          {/* ─── Tab-Specific Controls & Sub-Filters ─── */}
          <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-3">
            {/* Left Sub-filters based on Tab */}
            <div className="flex items-center flex-wrap gap-2.5">
              {/* Tab 1: Students Sub-Type */}
              {activeTab === 'students' && (
                <div className="flex items-center gap-1.5 bg-slate-100 p-1 rounded-lg">
                  {[
                    { id: 'all', label: 'All Students' },
                    { id: 'new', label: 'New Admissions' },
                    { id: 'readmission', label: 'Re-Admissions' },
                    { id: 'rte', label: 'RTE Quota' },
                    { id: 'summary', label: 'Class Summary' },
                    { id: 'balance_student', label: 'Balance Students' },
                    { id: 'inactive', label: 'Left / Inactive' },
                  ].map((sub) => (
                    <button
                      key={sub.id}
                      onClick={() => setStudentReportType(sub.id)}
                      className={`px-2.5 py-1 text-xs rounded-md font-medium transition-colors cursor-pointer ${
                        studentReportType === sub.id
                          ? 'bg-white text-[#1976d2] font-bold shadow-xs'
                          : 'text-slate-600 hover:text-slate-900'
                      }`}
                    >
                      {sub.label}
                    </button>
                  ))}
                </div>
              )}

              {/* Tab 2: Fees Sub-Type */}
              {activeTab === 'fees' && (
                <>
                  <div className="flex items-center gap-1.5 bg-slate-100 p-1 rounded-lg">
                    {[
                      { id: 'daily', label: 'Daily Collection' },
                      { id: 'datewise', label: 'Date Range' },
                      { id: 'balance', label: 'Balance / Defaulters' },
                      { id: 'zero_paid', label: 'Zero Fees Paid' },
                      { id: 'installments', label: 'Fees Installments' },
                    ].map((sub) => (
                      <button
                        key={sub.id}
                        onClick={() => setFeesReportType(sub.id)}
                        className={`px-2.5 py-1 text-xs rounded-md font-medium transition-colors cursor-pointer ${
                          feesReportType === sub.id
                            ? 'bg-white text-[#1976d2] font-bold shadow-xs'
                            : 'text-slate-600 hover:text-slate-900'
                        }`}
                      >
                        {sub.label}
                      </button>
                    ))}
                  </div>

                  {feesReportType === 'daily' && (
                    <input
                      type="date"
                      value={feesFromDate}
                      onChange={(e) => setFeesFromDate(e.target.value)}
                      className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700"
                    />
                  )}

                  {feesReportType === 'datewise' && (
                    <div className="flex items-center gap-1 text-xs">
                      <input
                        type="date"
                        value={feesFromDate}
                        onChange={(e) => setFeesFromDate(e.target.value)}
                        className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700"
                      />
                      <span className="text-slate-400">to</span>
                      <input
                        type="date"
                        value={feesToDate}
                        onChange={(e) => setFeesToDate(e.target.value)}
                        className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700"
                      />
                    </div>
                  )}
                </>
              )}

              {/* Tab 3: Attendance Date */}
              {activeTab === 'attendance' && (
                <div className="flex items-center gap-2">
                  <span className="text-xs font-semibold text-slate-600">Select Date:</span>
                  <input
                    type="date"
                    value={attendanceDate}
                    onChange={(e) => setAttendanceDate(e.target.value)}
                    className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700"
                  />
                </div>
              )}

              {/* Tab 4: Certificates Sub-Type */}
              {activeTab === 'certificates' && (
                <div className="flex items-center gap-1.5 bg-slate-100 p-1 rounded-lg">
                  {[
                    { id: 'issued', label: 'LC Issued Register' },
                    { id: 'requests', label: 'Pending LC Requests' },
                  ].map((sub) => (
                    <button
                      key={sub.id}
                      onClick={() => setCertificateType(sub.id)}
                      className={`px-3 py-1 text-xs rounded-md font-medium transition-colors cursor-pointer ${
                        certificateType === sub.id
                          ? 'bg-white text-[#1976d2] font-bold shadow-xs'
                          : 'text-slate-600 hover:text-slate-900'
                      }`}
                    >
                      {sub.label}
                    </button>
                  ))}
                </div>
              )}

              {/* Class & Division Filters (for applicable tabs) */}
              {(activeTab === 'students' || activeTab === 'fees' || activeTab === 'attendance') && (
                <>
                  <select
                    value={selectedStandard}
                    onChange={(e) => setSelectedStandard(e.target.value)}
                    className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 cursor-pointer"
                  >
                    <option value="all">All Standards</option>
                    {standards.map((c) => (
                      <option key={c.STVCLAS_standard || c.id} value={c.STVCLAS_standard}>
                        Std {c.STVCLAS_standard}
                      </option>
                    ))}
                  </select>

                  <select
                    value={selectedDivision}
                    onChange={(e) => setSelectedDivision(e.target.value)}
                    className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 cursor-pointer"
                  >
                    <option value="all">All Divisions</option>
                    {['A', 'B', 'C', 'D', 'E', 'F'].map((div) => (
                      <option key={div} value={div}>
                        Div {div}
                      </option>
                    ))}
                  </select>
                </>
              )}
            </div>

            {/* Right: Search Box */}
            <form onSubmit={handleSearchSubmit} className="relative min-w-[220px]">
              <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={14} />
              <input
                type="text"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Search by student, ID, GR, or phone..."
                className="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
              />
            </form>
          </div>

          {/* ─── Tab Content Area ─── */}
          <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {loading ? (
              <div className="py-20 flex flex-col items-center justify-center text-slate-400">
                <FiRefreshCw className="animate-spin mb-3 text-[#1976d2]" size={28} />
                <p className="text-sm font-medium">Loading report records...</p>
              </div>
            ) : (
              <>
                {/* ══════════════ TAB 1: STUDENTS & ADMISSIONS ══════════════ */}
                {activeTab === 'students' && (
                  <>
                    {studentReportType === 'summary' ? (
                      <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse text-xs">
                          <thead>
                            <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                              <th className="py-3 px-4">Standard</th>
                              <th className="py-3 px-4">Division</th>
                              <th className="py-3 px-4 text-center">Total Enrolled</th>
                              <th className="py-3 px-4 text-center text-[#1976d2]">Boys</th>
                              <th className="py-3 px-4 text-center text-pink-600">Girls</th>
                              <th className="py-3 px-4 text-center text-emerald-600">RTE Quota</th>
                              <th className="py-3 px-4 text-center">Active</th>
                              <th className="py-3 px-4 text-center text-rose-600">Inactive</th>
                            </tr>
                          </thead>
                          <tbody className="divide-y divide-slate-100 font-medium">
                            {studentData.length === 0 ? (
                              <tr>
                                <td colSpan="8" className="py-8 text-center text-slate-400">
                                  No summary records found for this academic session.
                                </td>
                              </tr>
                            ) : (
                              studentData.map((row, idx) => (
                                <tr key={idx} className="hover:bg-slate-50/80 transition-colors">
                                  <td className="py-3 px-4 font-bold text-slate-900">Std {row.STVCLAS_standard}</td>
                                  <td className="py-3 px-4 font-bold text-slate-600">Div {row.STVCLAS_class}</td>
                                  <td className="py-3 px-4 text-center font-bold text-slate-800 bg-slate-50/50">
                                    {row.total_students}
                                  </td>
                                  <td className="py-3 px-4 text-center font-semibold text-[#1976d2]">{row.boys}</td>
                                  <td className="py-3 px-4 text-center font-semibold text-pink-600">{row.girls}</td>
                                  <td className="py-3 px-4 text-center font-semibold text-emerald-600">
                                    {row.rte_count}
                                  </td>
                                  <td className="py-3 px-4 text-center font-semibold text-emerald-700">
                                    {row.active_count}
                                  </td>
                                  <td className="py-3 px-4 text-center font-semibold text-rose-600">
                                    {row.inactive_count}
                                  </td>
                                </tr>
                              ))
                            )}
                          </tbody>
                        </table>
                      </div>
                    ) : (
                      <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse text-xs">
                          <thead>
                            <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                              <th className="py-3 px-4">Student ID / GR</th>
                              <th className="py-3 px-4">Student Full Name</th>
                              <th className="py-3 px-4">Standard & Div</th>
                              {studentReportType === 'balance_student' ? (
                                <>
                                  <th className="py-3 px-4 text-right">Class Annual Fee</th>
                                  <th className="py-3 px-4 text-right">Paid to Date</th>
                                  <th className="py-3 px-4 text-right text-rose-600">Balance Due</th>
                                  <th className="py-3 px-4">Parent Phone</th>
                                  <th className="py-3 px-4 text-center">Action</th>
                                </>
                              ) : (
                                <>
                                  <th className="py-3 px-4">Gender</th>
                                  <th className="py-3 px-4">Parent Phone</th>
                                  <th className="py-3 px-4 text-center">Status</th>
                                  <th className="py-3 px-4 text-center">RTE</th>
                                  <th className="py-3 px-4 text-right">Fees Paid</th>
                                  <th className="py-3 px-4 text-center">Action</th>
                                </>
                              )}
                            </tr>
                          </thead>
                          <tbody className="divide-y divide-slate-100 font-medium">
                            {studentData.length === 0 ? (
                              <tr>
                                <td colSpan="9" className="py-8 text-center text-slate-400">
                                  No student records found matching the filter criteria.
                                </td>
                              </tr>
                            ) : (
                              studentData.map((s) => (
                                <tr key={s.id} className="hover:bg-slate-50/80 transition-colors">
                                  <td className="py-3 px-4">
                                    <div className="font-bold text-slate-900">{s.SPRIDEN_student_id}</div>
                                    <div className="text-[10px] text-slate-400">GR: {s.spriden_gr_number || '-'}</div>
                                  </td>
                                  <td className="py-3 px-4 font-semibold text-slate-800">
                                    <div>{s.student_name}</div>
                                    <div className="text-[10px] text-slate-400">Father: {s.SPRIDEN_father_name || '-'}</div>
                                  </td>
                                  <td className="py-3 px-4">
                                    <span className="font-semibold text-slate-700">
                                      {s.STVCLAS_standard}-{s.STVCLAS_class}
                                    </span>
                                    {s.STVCLAS_rollno && (
                                      <span className="text-[10px] text-slate-400 ml-1.5">(Roll: {s.STVCLAS_rollno})</span>
                                    )}
                                  </td>

                                  {studentReportType === 'balance_student' ? (
                                    <>
                                      <td className="py-3 px-4 text-right font-medium text-slate-600">
                                        ₹{Number(s.total_class_fees || 0).toLocaleString('en-IN')}
                                      </td>
                                      <td className="py-3 px-4 text-right font-semibold text-emerald-600">
                                        ₹{Number(s.total_fees_paid || 0).toLocaleString('en-IN')}
                                      </td>
                                      <td className="py-3 px-4 text-right font-extrabold text-rose-600">
                                        ₹{Number(s.balance_amount || 0).toLocaleString('en-IN')}
                                      </td>
                                      <td className="py-3 px-4 text-slate-600">
                                        {s.stvpcnf_mobile ? (
                                          <a
                                            href={`tel:${s.stvpcnf_mobile}`}
                                            className="text-[#1976d2] hover:underline flex items-center gap-1"
                                          >
                                            <FiPhone size={11} />
                                            <span>{s.stvpcnf_mobile}</span>
                                          </a>
                                        ) : (
                                          '-'
                                        )}
                                      </td>
                                      <td className="py-3 px-4 text-center">
                                        <button
                                          onClick={() => setSelectedItem({ type: 'student', data: s })}
                                          className="p-1.5 text-slate-600 hover:text-[#1976d2] hover:bg-slate-100 rounded-md transition-colors cursor-pointer"
                                          title="View Details"
                                        >
                                          <FiEye size={15} />
                                        </button>
                                      </td>
                                    </>
                                  ) : (
                                    <>
                                      <td className="py-3 px-4 text-slate-600">{s.SPRIDEN_gender || '-'}</td>
                                      <td className="py-3 px-4 text-slate-600">
                                        {s.stvpcnf_mobile ? (
                                          <a
                                            href={`tel:${s.stvpcnf_mobile}`}
                                            className="text-[#1976d2] hover:underline flex items-center gap-1"
                                          >
                                            <FiPhone size={11} />
                                            <span>{s.stvpcnf_mobile}</span>
                                          </a>
                                        ) : (
                                          '-'
                                        )}
                                      </td>
                                      <td className="py-3 px-4 text-center">
                                        <span
                                          className={`px-2 py-0.5 rounded-full text-[10px] font-bold ${
                                            s.spriden_student_status === 'A'
                                              ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                              : 'bg-rose-50 text-rose-700 border border-rose-200'
                                          }`}
                                        >
                                          {s.spriden_student_status === 'A' ? 'Active' : 'Inactive'}
                                        </span>
                                      </td>
                                      <td className="py-3 px-4 text-center">
                                        {s.rte_status === 'Yes' ? (
                                          <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            RTE
                                          </span>
                                        ) : (
                                          <span className="text-slate-400 text-[10px]">Regular</span>
                                        )}
                                      </td>
                                      <td className="py-3 px-4 text-right font-bold text-slate-900">
                                        ₹{Number(s.total_fees_paid || 0).toLocaleString('en-IN')}
                                      </td>
                                      <td className="py-3 px-4 text-center">
                                        <button
                                          onClick={() => setSelectedItem({ type: 'student', data: s })}
                                          className="p-1.5 text-slate-600 hover:text-[#1976d2] hover:bg-slate-100 rounded-md transition-colors cursor-pointer"
                                          title="View Details"
                                        >
                                          <FiEye size={15} />
                                        </button>
                                      </td>
                                    </>
                                  )}
                                </tr>
                              ))
                            )}
                          </tbody>
                        </table>
                      </div>
                    )}
                  </>
                )}

                {/* ══════════════ TAB 2: FINANCIAL & FEES ══════════════ */}
                {activeTab === 'fees' && (
                  <div className="overflow-x-auto">
                    <div className="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                      <span className="text-xs font-bold text-slate-700">
                        Total {feesReportType.includes('balance') ? 'Outstanding Balance' : 'Collections'}:
                        <span className="ml-1.5 text-emerald-700 font-extrabold text-sm">
                          ₹{Number(feesTotalAmount).toLocaleString('en-IN')}
                        </span>
                      </span>
                      <span className="text-xs text-slate-500 font-medium">
                        Showing {feesData.length} records
                      </span>
                    </div>

                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                          <th className="py-3 px-4">Receipt / ID</th>
                          <th className="py-3 px-4">Student Name</th>
                          <th className="py-3 px-4">Standard & Div</th>
                          {feesReportType === 'installments' ? (
                            <>
                              <th className="py-3 px-4 text-right">Class Annual Fee</th>
                              <th className="py-3 px-4 text-right">Paid to Date</th>
                              <th className="py-3 px-4">Installment Timeline</th>
                            </>
                          ) : feesReportType.includes('balance') ? (
                            <>
                              <th className="py-3 px-4 text-right">Class Annual Fee</th>
                              <th className="py-3 px-4 text-right">Paid to Date</th>
                              <th className="py-3 px-4 text-right text-rose-600">Balance Due</th>
                              <th className="py-3 px-4">Last Payment</th>
                            </>
                          ) : (
                            <>
                              <th className="py-3 px-4">Date</th>
                              <th className="py-3 px-4">Mode</th>
                              <th className="py-3 px-4 text-right">Amount Paid</th>
                              <th className="py-3 px-4">Cashier / Staff</th>
                            </>
                          )}
                          <th className="py-3 px-4">Contact</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100 font-medium">
                        {feesData.length === 0 ? (
                          <tr>
                            <td colSpan="8" className="py-8 text-center text-slate-400">
                              No financial records found for the selected parameters.
                            </td>
                          </tr>
                        ) : (
                          feesData.map((f, idx) => (
                            <tr key={idx} className="hover:bg-slate-50/80 transition-colors">
                              <td className="py-3 px-4">
                                <div className="font-bold text-slate-900">
                                  {f.sprfees_pk ? `#${f.sprfees_pk}` : f.SPRIDEN_student_id}
                                </div>
                                <div className="text-[10px] text-slate-400">ID: {f.SPRIDEN_student_id}</div>
                              </td>
                              <td className="py-3 px-4 font-semibold text-slate-800">{f.student_name}</td>
                              <td className="py-3 px-4 font-semibold text-slate-700">
                                {f.STVCLAS_standard}-{f.STVCLAS_class}
                              </td>

                              {feesReportType === 'installments' ? (
                                <>
                                  <td className="py-3 px-4 text-right font-medium text-slate-600">
                                    ₹{Number(f.total_fee || 0).toLocaleString('en-IN')}
                                  </td>
                                  <td className="py-3 px-4 text-right font-bold text-emerald-600">
                                    ₹{Number(f.paid_fee || 0).toLocaleString('en-IN')}
                                  </td>
                                  <td className="py-3 px-4 text-slate-700 font-medium text-[11px]">
                                    {f.installments || 'No installment records'}
                                  </td>
                                </>
                              ) : feesReportType.includes('balance') ? (
                                <>
                                  <td className="py-3 px-4 text-right font-medium text-slate-600">
                                    ₹{Number(f.total_fee || 0).toLocaleString('en-IN')}
                                  </td>
                                  <td className="py-3 px-4 text-right font-semibold text-emerald-600">
                                    ₹{Number(f.paid_fee || 0).toLocaleString('en-IN')}
                                  </td>
                                  <td className="py-3 px-4 text-right font-bold text-rose-600">
                                    ₹{Number(f.balance_fee || 0).toLocaleString('en-IN')}
                                  </td>
                                  <td className="py-3 px-4 text-slate-500 text-[11px]">
                                    {f.last_payment_date ? f.last_payment_date.slice(0, 10) : 'Never'}
                                  </td>
                                </>
                              ) : (
                                <>
                                  <td className="py-3 px-4 text-slate-600">
                                    {f.sprfees_feesdate ? f.sprfees_feesdate.slice(0, 10) : '-'}
                                  </td>
                                  <td className="py-3 px-4">
                                    <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                      {f.payment_mode || 'Cash'}
                                    </span>
                                  </td>
                                  <td className="py-3 px-4 text-right font-bold text-emerald-700 text-sm">
                                    ₹{Number(f.sprfees_amount || 0).toLocaleString('en-IN')}
                                  </td>
                                  <td className="py-3 px-4 text-slate-500 text-[11px]">{f.sprfees_recivedby || '-'}</td>
                                </>
                              )}

                              <td className="py-3 px-4 text-slate-600">
                                {f.stvpcnf_mobile ? (
                                  <a
                                    href={`tel:${f.stvpcnf_mobile}`}
                                    className="text-[#1976d2] hover:underline flex items-center gap-1"
                                  >
                                    <FiPhone size={11} />
                                    <span>{f.stvpcnf_mobile}</span>
                                  </a>
                                ) : (
                                  '-'
                                )}
                              </td>
                            </tr>
                          ))
                        )}
                      </tbody>
                    </table>
                  </div>
                )}

                {/* ══════════════ TAB 3: ATTENDANCE & ABSENT ══════════════ */}
                {activeTab === 'attendance' && (
                  <div className="space-y-6 p-4">
                    {/* Class Percentage Summary Grid */}
                    <div>
                      <h3 className="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                        Class-by-Class Attendance Rate ({attendanceDate})
                      </h3>
                      <div className="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2.5">
                        {classAttendanceSummary.map((c, i) => {
                          const enrolled = Number(c.total_enrolled || 0);
                          const present = Number(c.present_count || 0);
                          const rate = enrolled > 0 ? Math.round((present / enrolled) * 100) : 0;
                          return (
                            <div
                              key={i}
                              className="p-3 bg-slate-50 rounded-xl border border-slate-200 flex flex-col justify-between"
                            >
                              <div className="flex items-center justify-between text-xs font-bold text-slate-800">
                                <span>
                                  {c.STVCLAS_standard}-{c.STVCLAS_class}
                                </span>
                                <span className={rate >= 80 ? 'text-emerald-600' : 'text-amber-600'}>
                                  {rate}%
                                </span>
                              </div>
                              <div className="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden my-2">
                                <div
                                  className={`h-full ${
                                    rate >= 80 ? 'bg-emerald-500' : rate >= 60 ? 'bg-amber-500' : 'bg-rose-500'
                                  }`}
                                  style={{ width: `${rate}%` }}
                                />
                              </div>
                              <div className="text-[10px] text-slate-500 flex justify-between">
                                <span>P: {present}</span>
                                <span className="text-rose-600 font-bold">A: {c.absent_count}</span>
                              </div>
                            </div>
                          );
                        })}
                      </div>
                    </div>

                    {/* Absent Students Log */}
                    <div>
                      <div className="flex items-center justify-between mb-3">
                        <h3 className="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                          <FiAlertCircle className="text-rose-600" size={14} />
                          <span>Absent Students Register ({absentStudents.length} Students)</span>
                        </h3>
                      </div>

                      <div className="border border-slate-200 rounded-xl overflow-hidden">
                        <table className="w-full text-left border-collapse text-xs">
                          <thead>
                            <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                              <th className="py-3 px-4">Student ID / GR</th>
                              <th className="py-3 px-4">Student Name</th>
                              <th className="py-3 px-4">Class</th>
                              <th className="py-3 px-4 text-center">Roll No</th>
                              <th className="py-3 px-4 text-center">Status</th>
                              <th className="py-3 px-4">Parent Mobile</th>
                              <th className="py-3 px-4">Remarks</th>
                            </tr>
                          </thead>
                          <tbody className="divide-y divide-slate-100 font-medium">
                            {absentStudents.length === 0 ? (
                              <tr>
                                <td colSpan="7" className="py-8 text-center text-slate-400">
                                  No absent student records found for {attendanceDate}.
                                </td>
                              </tr>
                            ) : (
                              absentStudents.map((st) => (
                                <tr key={st.id} className="hover:bg-slate-50/80 transition-colors">
                                  <td className="py-3 px-4 font-bold text-slate-900">{st.SPRIDEN_student_id}</td>
                                  <td className="py-3 px-4 font-semibold text-slate-800">{st.student_name}</td>
                                  <td className="py-3 px-4 font-semibold text-slate-700">
                                    Std {st.STVCLAS_standard}-{st.STVCLAS_class}
                                  </td>
                                  <td className="py-3 px-4 text-center font-bold text-slate-800">
                                    {st.STVCLAS_rollno || '-'}
                                  </td>
                                  <td className="py-3 px-4 text-center">
                                    <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                      Absent
                                    </span>
                                  </td>
                                  <td className="py-3 px-4">
                                    {st.stvpcnf_mobile ? (
                                      <a
                                        href={`tel:${st.stvpcnf_mobile}`}
                                        className="text-[#1976d2] hover:underline flex items-center gap-1 font-semibold"
                                      >
                                        <FiPhone size={11} />
                                        <span>{st.stvpcnf_mobile}</span>
                                      </a>
                                    ) : (
                                      '-'
                                    )}
                                  </td>
                                  <td className="py-3 px-4 text-slate-500 text-[11px]">{st.SPRATND_comment || '-'}</td>
                                </tr>
                              ))
                            )}
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                )}

                {/* ══════════════ TAB 4: CERTIFICATES & LC ══════════════ */}
                {activeTab === 'certificates' && (
                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                          <th className="py-3 px-4">Student ID / GR</th>
                          <th className="py-3 px-4">Student Name</th>
                          <th className="py-3 px-4">Class</th>
                          <th className="py-3 px-4">Issue / Leaving Date</th>
                          <th className="py-3 px-4">Reason for Leaving</th>
                          <th className="py-3 px-4">Next School</th>
                          <th className="py-3 px-4 text-center">Status</th>
                          {certificateType === 'requests' && <th className="py-3 px-4 text-center">Action</th>}
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100 font-medium">
                        {certificateData.length === 0 ? (
                          <tr>
                            <td colSpan="8" className="py-8 text-center text-slate-400">
                              No certificate records found.
                            </td>
                          </tr>
                        ) : (
                          certificateData.map((c, i) => (
                            <tr key={i} className="hover:bg-slate-50/80 transition-colors">
                              <td className="py-3 px-4">
                                <div className="font-bold text-slate-900">{c.SPRIDEN_student_id}</div>
                                <div className="text-[10px] text-slate-400">GR: {c.spriden_gr_number || '-'}</div>
                              </td>
                              <td className="py-3 px-4 font-semibold text-slate-800">{c.student_name}</td>
                              <td className="py-3 px-4 font-semibold text-slate-700">
                                {c.STVCLAS_standard ? `${c.STVCLAS_standard}-${c.STVCLAS_class}` : c.class || '-'}
                              </td>
                              <td className="py-3 px-4 text-slate-600">
                                {c.leaving_date || c.lc_date || (c.requested_at ? c.requested_at.slice(0, 10) : '-')}
                              </td>
                              <td className="py-3 px-4 text-slate-600">{c.leaving_reason || '-'}</td>
                              <td className="py-3 px-4 text-slate-600">{c.student_next_school || '-'}</td>
                              <td className="py-3 px-4 text-center">
                                <span
                                  className={`px-2 py-0.5 rounded-full text-[10px] font-bold ${
                                    c.status === 'Approved' || (!c.status && c.leaving_date)
                                      ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                      : 'bg-amber-50 text-amber-700 border border-amber-200'
                                  }`}
                                >
                                  {c.status || 'Issued'}
                                </span>
                              </td>
                              {certificateType === 'requests' && (
                                <td className="py-3 px-4 text-center">
                                  {c.status !== 'Approved' && (
                                    <div className="flex items-center justify-center gap-1.5">
                                      <button
                                        onClick={() => handleApproveLcRequest(c.id, 'Approved')}
                                        className="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[10px] font-bold cursor-pointer"
                                      >
                                        Approve
                                      </button>
                                      <button
                                        onClick={() => handleApproveLcRequest(c.id, 'Rejected')}
                                        className="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-[10px] font-bold cursor-pointer"
                                      >
                                        Reject
                                      </button>
                                    </div>
                                  )}
                                </td>
                              )}
                            </tr>
                          ))
                        )}
                      </tbody>
                    </table>
                  </div>
                )}

                {/* ══════════════ TAB 5: TEACHER ACTIVITY ══════════════ */}
                {activeTab === 'activity' && (
                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                          <th className="py-3 px-4">Staff Code</th>
                          <th className="py-3 px-4">Teacher Name</th>
                          <th className="py-3 px-4">Contact Phone</th>
                          <th className="py-3 px-4 text-center">Assigned Classes</th>
                          <th className="py-3 px-4 text-center">Attendance Marked Today</th>
                          <th className="py-3 px-4 text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100 font-medium">
                        {teacherData.length === 0 ? (
                          <tr>
                            <td colSpan="6" className="py-8 text-center text-slate-400">
                              No teacher records found.
                            </td>
                          </tr>
                        ) : (
                          teacherData.map((t) => (
                            <tr key={t.id} className="hover:bg-slate-50/80 transition-colors">
                              <td className="py-3 px-4 font-bold text-slate-900">{t.teacher_code}</td>
                              <td className="py-3 px-4 font-semibold text-slate-800">{t.teacher_name}</td>
                              <td className="py-3 px-4 text-slate-600">{t.stvpcnf_mobile || '-'}</td>
                              <td className="py-3 px-4 text-center font-bold text-slate-800">
                                {t.assigned_periods}
                              </td>
                              <td className="py-3 px-4 text-center">
                                {t.attendance_taken_today > 0 ? (
                                  <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Completed
                                  </span>
                                ) : (
                                  <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Pending
                                  </span>
                                )}
                              </td>
                              <td className="py-3 px-4 text-center">
                                <button
                                  onClick={() => setSelectedItem({ type: 'teacher', data: t })}
                                  className="p-1.5 text-slate-600 hover:text-[#1976d2] hover:bg-slate-100 rounded-md transition-colors cursor-pointer"
                                  title="View Details"
                                >
                                  <FiEye size={15} />
                                </button>
                              </td>
                            </tr>
                          ))
                        )}
                      </tbody>
                    </table>
                  </div>
                )}

                {/* ══════════════ TAB 6: AUDIT & TRACKING ══════════════ */}
                {activeTab === 'audit' && (
                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                          <th className="py-3 px-4">Event ID</th>
                          <th className="py-3 px-4">Date & Time</th>
                          <th className="py-3 px-4">User ID / Username</th>
                          <th className="py-3 px-4">Name</th>
                          <th className="py-3 px-4">Portal Role</th>
                          <th className="py-3 px-4 text-center">Session Status</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100 font-medium">
                        {auditData.length === 0 ? (
                          <tr>
                            <td colSpan="6" className="py-8 text-center text-slate-400">
                              No login audit logs available.
                            </td>
                          </tr>
                        ) : (
                          auditData.map((log) => (
                            <tr key={log.id} className="hover:bg-slate-50/80 transition-colors">
                              <td className="py-3 px-4 font-mono font-semibold text-slate-500">#{log.id}</td>
                              <td className="py-3 px-4 text-slate-700">
                                <div className="font-semibold">{log.log_date ? log.log_date.slice(0, 10) : '-'}</div>
                                <div className="text-[10px] text-slate-400">{log.log_time || ''}</div>
                              </td>
                              <td className="py-3 px-4 font-bold text-slate-900">{log.user_id}</td>
                              <td className="py-3 px-4 font-semibold text-slate-800">{log.user_name}</td>
                              <td className="py-3 px-4">
                                <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                  {log.role}
                                </span>
                              </td>
                              <td className="py-3 px-4 text-center">
                                <span
                                  className={`px-2 py-0.5 rounded-full text-[10px] font-bold ${
                                    log.status === 'success' || !log.status
                                      ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                      : 'bg-rose-50 text-rose-700 border border-rose-200'
                                  }`}
                                >
                                  {log.status || 'Active'}
                                </span>
                              </td>
                            </tr>
                          ))
                        )}
                      </tbody>
                    </table>
                  </div>
                )}
              </>
            )}
          </div>
        </main>
      </div>

      {/* ─── Detail Modal ─── */}
      {selectedItem && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <h3 className="font-bold text-slate-900 text-base">Record Details</h3>
              <button
                onClick={() => setSelectedItem(null)}
                className="text-slate-400 hover:text-slate-700 text-lg cursor-pointer"
              >
                ✕
              </button>
            </div>
            <div className="text-xs space-y-2 text-slate-700 max-h-96 overflow-y-auto">
              <pre className="p-3 bg-slate-50 rounded-lg text-slate-800 font-mono text-[11px] whitespace-pre-wrap">
                {JSON.stringify(selectedItem.data, null, 2)}
              </pre>
            </div>
            <div className="text-right pt-2">
              <button
                onClick={() => setSelectedItem(null)}
                className="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg text-xs cursor-pointer"
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
