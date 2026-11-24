import React, { useState, useEffect } from 'react';
import { Link, useLocation, useSearchParams } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import feesService from '../../services/feesService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiFileText,
  FiSearch,
  FiFilter,
  FiDownload,
  FiDollarSign,
  FiUsers,
  FiCheckCircle,
  FiAlertCircle,
  FiRefreshCw,
  FiArrowRight,
  FiCreditCard,
  FiMessageSquare,
  FiPhone,
  FiCalendar,
  FiClock,
  FiTrendingUp,
} from 'react-icons/fi';
import { FaWhatsapp } from 'react-icons/fa';

export default function FeesReportPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const location = useLocation();
  const [searchParams, setSearchParams] = useSearchParams();

  // Determine initial mode based on route/query
  const getInitialTab = () => {
    const p = location.pathname.toLowerCase();
    if (p.includes('fees_comment')) return 'comments';
    if (p.includes('current_zero_paid')) return 'zero-paid';
    if (p.includes('all_previous_fees')) return 'all-previous';
    if (p.includes('last_year_fees') || p.includes('last_year_balance')) return 'last-year';
    if (p.includes('daily') || p.includes('date_wise') || p.includes('monthly_fees') || p.includes('fees_instment')) return 'daily';
    if (p.includes('balance') || p.includes('balance_rep')) return 'balance';
    return searchParams.get('tab') || 'report';
  };

  const [activeTab, setActiveTab] = useState(getInitialTab);
  const [loading, setLoading] = useState(true);

  // Global Filter options
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [divisions, setDivisions] = useState(['A', 'B', 'C', 'D']);
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStandard, setSelectedStandard] = useState('all');
  const [selectedDivision, setSelectedDivision] = useState('all');
  const [statusFilter, setStatusFilter] = useState('all'); // all, paid, partial, unpaid
  const [searchQuery, setSearchQuery] = useState('');

  // Tab 1: Current Fee Report records & summary
  const [records, setRecords] = useState([]);
  const [summary, setSummary] = useState({
    total_students: 0,
    total_billed: 0,
    total_collected: 0,
    total_exemptions: 0,
    total_balance: 0,
  });

  // Tab 2: Comments records
  const [comments, setComments] = useState([]);
  const [loadingComments, setLoadingComments] = useState(false);

  // Tab 3: Previous Fees records (CI3: Report::all_previous_fees_report)
  const [previousReportData, setPreviousReportData] = useState({ academic_years: [], records: [], summary: {} });
  const [loadingPrevious, setLoadingPrevious] = useState(false);

  // Tab 4: Last Year Fees records (CI3: Report::last_year_fees_report)
  const [lastYearData, setLastYearData] = useState({ records: [], summary: {} });
  const [loadingLastYear, setLoadingLastYear] = useState(false);

  // Tab 5: Daily / Datewise Collections (CI3: Report::daily_fees_report)
  const todayStr = new Date().toISOString().slice(0, 10);
  const [dailyFromDate, setDailyFromDate] = useState(todayStr);
  const [dailyToDate, setDailyToDate] = useState(todayStr);
  const [dailyOnlyNewAdmission, setDailyOnlyNewAdmission] = useState(false);
  const [dailyData, setDailyData] = useState({ records: [], summary: {} });
  const [loadingDaily, setLoadingDaily] = useState(false);

  // Load dropdown options
  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          const years = res.data.options.academicYears || [];
          const classes = res.data.options.classes || [];
          const divs = res.data.options.divisions || ['A', 'B', 'C', 'D'];
          setAcademicYears(years);
          setStandards(classes);
          setDivisions(divs);

          const active = years.find((y) => y.is_active) || years[0];
          if (active) {
            setSelectedYear(String(active.stvacdm_id));
          }
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  // Sync route changes
  useEffect(() => {
    const p = location.pathname.toLowerCase();
    if (p.includes('fees_comment')) setActiveTab('comments');
    else if (p.includes('current_zero_paid') || p.includes('fees_alert')) {
      setActiveTab('report');
      setStatusFilter('unpaid');
    } else if (p.includes('all_previous_fees')) setActiveTab('all-previous');
    else if (p.includes('last_year_fees') || p.includes('last_year_balance')) setActiveTab('last-year');
    else if (p.includes('daily') || p.includes('date_wise') || p.includes('monthly_fees') || p.includes('fees_instment')) setActiveTab('daily');
    else if (p.includes('balance') || p.includes('balance_rep')) {
      setActiveTab('report');
      setStatusFilter('partial');
    } else if (p.includes('current_year_fees') || p.includes('fees_report')) {
      setActiveTab('report');
    }
  }, [location.pathname]);

  // Load data based on active tab
  useEffect(() => {
    if (!selectedYear && activeTab !== 'all-previous') return;

    if (activeTab === 'report' || activeTab === 'zero-paid' || activeTab === 'balance') {
      loadReport();
    } else if (activeTab === 'comments') {
      loadComments();
    } else if (activeTab === 'all-previous') {
      loadPreviousFees();
    } else if (activeTab === 'last-year') {
      loadLastYearFees();
    } else if (activeTab === 'daily') {
      loadDailyFees();
    }
  }, [selectedYear, selectedStandard, selectedDivision, activeTab, statusFilter]);

  async function loadReport() {
    setLoading(true);
    try {
      const res = await feesService.getFeesReport({
        academic_year: selectedYear,
        standard: selectedStandard,
      });
      if (res.data?.success) {
        setRecords(res.data.records || []);
        setSummary(res.data.summary || {});
      }
    } catch (err) {
      console.error('Failed to fetch fees report:', err);
    } finally {
      setLoading(false);
    }
  }

  async function loadComments() {
    setLoadingComments(true);
    try {
      const res = await feesService.getFeesComments({
        academic_year: selectedYear,
      });
      if (res.data?.success) {
        setComments(res.data.comments || []);
      }
    } catch (err) {
      console.error('Failed to fetch fees comments:', err);
    } finally {
      setLoadingComments(false);
    }
  }

  async function loadPreviousFees() {
    setLoadingPrevious(true);
    try {
      const res = await feesService.getAllPreviousFeesReport();
      if (res.data?.success) {
        setPreviousReportData(res.data);
      }
    } catch (err) {
      console.error('Failed to fetch all previous fees report:', err);
    } finally {
      setLoadingPrevious(false);
    }
  }

  async function loadLastYearFees() {
    setLoadingLastYear(true);
    try {
      const res = await feesService.getLastYearFeesReport({
        academic_year: selectedYear,
      });
      if (res.data?.success) {
        setLastYearData(res.data);
      }
    } catch (err) {
      console.error('Failed to fetch last year fees report:', err);
    } finally {
      setLoadingLastYear(false);
    }
  }

  async function loadDailyFees() {
    setLoadingDaily(true);
    try {
      const res = await feesService.getDailyFeesReport({
        fromDate: dailyFromDate,
        toDate: dailyToDate,
        academicYear: selectedYear,
        standard: selectedStandard,
        division: selectedDivision,
        new_admission: dailyOnlyNewAdmission ? '1' : '',
      });
      if (res.data?.success) {
        setDailyData(res.data);
      }
    } catch (err) {
      console.error('Failed to fetch daily fees report:', err);
    } finally {
      setLoadingDaily(false);
    }
  }

  // Filter records based on status & search
  const filteredRecords = records.filter((r) => {
    if (searchQuery) {
      const query = searchQuery.toLowerCase();
      const matchName = r.name?.toLowerCase().includes(query);
      const matchId = r.SPRIDEN_student_id?.toLowerCase().includes(query);
      if (!matchName && !matchId) return false;
    }

    if (activeTab === 'zero-paid' || statusFilter === 'unpaid') {
      return Number(r.total_paid) === 0;
    }
    if (activeTab === 'balance' || statusFilter === 'partial') {
      return Number(r.balance) > 0;
    }
    if (statusFilter === 'paid') {
      return Number(r.balance) <= 0 && Number(r.total_paid) > 0;
    }

    return true;
  });

  // Filter comments
  const filteredComments = comments.filter((c) => {
    if (!searchQuery) return true;
    const q = searchQuery.toLowerCase();
    return (
      (c.student_name || '').toLowerCase().includes(q) ||
      (c.student_id || '').toLowerCase().includes(q) ||
      (c.last_payment_notes || '').toLowerCase().includes(q)
    );
  });

  // Filter previous fees records
  const filteredPreviousRecords = (previousReportData.records || []).filter((r) => {
    if (!searchQuery) return true;
    const q = searchQuery.toLowerCase();
    return (
      (r.name || '').toLowerCase().includes(q) ||
      (r.SPRIDEN_student_id || '').toLowerCase().includes(q) ||
      (r.MobileNumber || '').includes(q)
    );
  });

  // Filter last year fees records
  const filteredLastYearRecords = (lastYearData.records || []).filter((r) => {
    if (!searchQuery) return true;
    const q = searchQuery.toLowerCase();
    return (
      (r.name || '').toLowerCase().includes(q) ||
      (r.SPRIDEN_student_id || '').toLowerCase().includes(q) ||
      (r.MobileNumber || '').includes(q)
    );
  });

  // Filter daily fees transactions
  const filteredDailyRecords = (dailyData.records || []).filter((r) => {
    if (!searchQuery) return true;
    const q = searchQuery.toLowerCase();
    return (
      (r.student_name || '').toLowerCase().includes(q) ||
      (r.student_id || '').toLowerCase().includes(q) ||
      String(r.receipt_no || '').includes(q) ||
      (r.received_by || '').toLowerCase().includes(q)
    );
  });

  // Export to CSV
  function exportCSV() {
    if (activeTab === 'report' || activeTab === 'zero-paid' || activeTab === 'balance') {
      if (filteredRecords.length === 0) return;
      const headers = [
        'Student ID',
        'Name',
        'Standard',
        'Division',
        'Total Fees',
        'Paid',
        'Exemption',
        'Balance',
        'Due Date',
        'Received By',
      ];
      const rows = filteredRecords.map((r) => [
        r.SPRIDEN_student_id,
        `"${r.name}"`,
        r.STVCLAS_standard,
        r.STVCLAS_class,
        r.total_fees,
        r.total_paid,
        r.total_exemption,
        r.balance,
        r.due_date ? new Date(r.due_date).toLocaleDateString() : '',
        `"${r.received_by || ''}"`,
      ]);
      downloadCSV(headers, rows, `Fees_Report_${selectedYear}.csv`);
    } else if (activeTab === 'all-previous') {
      if (filteredPreviousRecords.length === 0) return;
      const yearHeaders = (previousReportData.academic_years || []).map((y) => y.academic_year_name);
      const headers = ['#', 'Student ID', 'Student Name', 'Mobile No.', ...yearHeaders, 'Total Outstanding'];
      const rows = filteredPreviousRecords.map((r, idx) => {
        const yearCols = (previousReportData.academic_years || []).map((y) => r[`Pending_${y.academic_year_id}`] || 0);
        return [idx + 1, r.SPRIDEN_student_id, `"${r.name}"`, r.MobileNumber || '', ...yearCols, r.TotalPending];
      });
      downloadCSV(headers, rows, `All_Previous_Fees_Report.csv`);
    } else if (activeTab === 'last-year') {
      if (filteredLastYearRecords.length === 0) return;
      const headers = ['#', 'Student ID', 'Student Name', 'Mobile', 'Standard', 'Division', 'Total Fee', 'Paid', 'Exempted', 'Pending'];
      const rows = filteredLastYearRecords.map((r, idx) => [
        idx + 1,
        r.SPRIDEN_student_id,
        `"${r.name}"`,
        r.MobileNumber || '',
        r.Standard,
        r.Division,
        r.Total_fee,
        r.Total_paid,
        r.Total_exumpted,
        r.Pending_fees,
      ]);
      downloadCSV(headers, rows, `Last_Year_Fees_Report.csv`);
    } else if (activeTab === 'daily') {
      if (filteredDailyRecords.length === 0) return;
      const headers = ['Receipt No', 'Date', 'Student ID', 'Student Name', 'Class', 'Division', 'Amount', 'Payment Mode', 'Received By', 'Notes'];
      const rows = filteredDailyRecords.map((r) => [
        r.receipt_no,
        r.fee_date ? new Date(r.fee_date).toLocaleDateString() : '',
        r.student_id,
        `"${r.student_name}"`,
        r.standard,
        r.division,
        r.amount,
        r.payment_mode || 'Cash',
        `"${r.received_by || ''}"`,
        `"${r.notes || ''}"`,
      ]);
      downloadCSV(headers, rows, `Daily_Fees_Report_${dailyFromDate}_to_${dailyToDate}.csv`);
    } else {
      if (filteredComments.length === 0) return;
      const headers = [
        'Student ID',
        'Name',
        'Class',
        'Division',
        'Total Fee',
        'Paid',
        'Exempted',
        'Balance',
        'Last Paid Date',
        'Last Paid Amount',
        'Comment/Notes',
        'WhatsApp Number',
      ];
      const rows = filteredComments.map((c) => [
        c.student_id,
        `"${c.student_name}"`,
        c.class_name,
        c.division,
        c.total_fee,
        c.total_paid,
        c.total_exempted,
        c.balance_fee,
        c.last_paid_date ? new Date(c.last_paid_date).toLocaleDateString() : '',
        c.last_paid_amount,
        `"${c.last_payment_notes || ''}"`,
        c.father_whatsapp_number || '',
      ]);
      downloadCSV(headers, rows, `Fees_Comments_${selectedYear}.csv`);
    }
  }

  function downloadCSV(headers, rows, filename) {
    const csvContent =
      'data:text/csv;charset=utf-8,' +
      [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  const getPageTitle = () => {
    if (activeTab === 'comments') return 'Fees Comments & WhatsApp Notifications';
    if (activeTab === 'zero-paid') return 'Currently Zero Fees Paid Report';
    if (activeTab === 'balance') return 'Pending Balance Fees Report';
    if (activeTab === 'all-previous') return 'All Previous Academic Years Balance Report';
    if (activeTab === 'last-year') return 'Last Academic Year Fees Balance Report';
    if (activeTab === 'daily') return 'Daily & Datewise Fees Collection Report';
    return 'Fees Report & Ledger Tracking';
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar
        isOpen={sidebarOpen}
        toggleSidebar={() => setSidebarOpen(!sidebarOpen)}
        onToggle={() => setSidebarOpen(!sidebarOpen)}
      />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav
          toggleSidebar={() => setSidebarOpen(!sidebarOpen)}
          onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
        />
        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiFileText className="text-[#1976d2]" /> {getPageTitle()}
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                {activeTab === 'comments'
                  ? 'Audit student fee payment notes and broadcast instant WhatsApp reminders'
                  : activeTab === 'all-previous'
                  ? 'Comprehensive audit of student fee arrears across all past academic sessions'
                  : activeTab === 'last-year'
                  ? 'Detailed breakdown of uncollected fees from the immediately preceding session'
                  : activeTab === 'daily'
                  ? 'Day-by-day and range-based cashier collection logs with payment mode receipts'
                  : 'Standard-wise fee billing, payment reconciliation, and balance collection tracking'}
              </p>
            </div>
            <div className="flex items-center gap-2">
              <button
                onClick={exportCSV}
                className="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-lg text-sm font-semibold transition shadow-sm cursor-pointer"
              >
                <FiDownload /> Export CSV
              </button>
              <Link
                to="/fees"
                className="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-sm font-semibold transition shadow-sm cursor-pointer"
              >
                <FiDollarSign /> Collect Fees
              </Link>
            </div>
          </div>

          {/* Module Navigation Tabs (Matching 100% CI3 fees_report_navbar.php) */}
          <div className="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
            <button
              onClick={() => {
                setActiveTab('report');
                setStatusFilter('all');
              }}
              className={`px-3.5 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer ${
                activeTab === 'report' && statusFilter === 'all'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
              }`}
            >
              All Fees Report
            </button>
            <button
              onClick={() => {
                setActiveTab('zero-paid');
                setStatusFilter('unpaid');
              }}
              className={`px-3.5 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer ${
                activeTab === 'zero-paid'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
              }`}
            >
              Currently Zero Paid
            </button>
            <button
              onClick={() => {
                setActiveTab('balance');
                setStatusFilter('partial');
              }}
              className={`px-3.5 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer ${
                activeTab === 'balance'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
              }`}
            >
              Pending Balances
            </button>
            <button
              onClick={() => setActiveTab('all-previous')}
              className={`px-3.5 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer ${
                activeTab === 'all-previous'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
              }`}
            >
              Previous Fees (All Years)
            </button>
            <button
              onClick={() => setActiveTab('last-year')}
              className={`px-3.5 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer ${
                activeTab === 'last-year'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
              }`}
            >
              Last Year Fees
            </button>
            <button
              onClick={() => setActiveTab('daily')}
              className={`px-3.5 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer ${
                activeTab === 'daily'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
              }`}
            >
              Daily & Range Collections
            </button>
            <button
              onClick={() => setActiveTab('comments')}
              className={`px-3.5 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer flex items-center gap-1.5 ${
                activeTab === 'comments'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'
              }`}
            >
              <FiMessageSquare /> Fees Comments & WhatsApp
            </button>
          </div>

          {/* Metric Summary Cards */}
          {(activeTab === 'report' || activeTab === 'zero-paid' || activeTab === 'balance') && (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div className="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                  <span>Students</span>
                  <FiUsers className="text-slate-400 w-4 h-4" />
                </div>
                <div className="text-2xl font-bold text-slate-900 mt-2">
                  {filteredRecords.length}
                </div>
              </div>

              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div className="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                  <span>Total Demand</span>
                  <FiDollarSign className="text-[#1976d2] w-4 h-4" />
                </div>
                <div className="text-2xl font-bold text-slate-900 mt-2">
                  ₹{filteredRecords.reduce((acc, r) => acc + Number(r.total_fees || 0), 0).toLocaleString('en-IN')}
                </div>
              </div>

              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div className="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                  <span>Total Collected</span>
                  <FiCheckCircle className="text-emerald-500 w-4 h-4" />
                </div>
                <div className="text-2xl font-bold text-emerald-600 mt-2">
                  ₹{filteredRecords.reduce((acc, r) => acc + Number(r.total_paid || 0), 0).toLocaleString('en-IN')}
                </div>
              </div>

              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div className="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                  <span>Concessions</span>
                  <FiAlertCircle className="text-amber-500 w-4 h-4" />
                </div>
                <div className="text-2xl font-bold text-amber-600 mt-2">
                  ₹{filteredRecords.reduce((acc, r) => acc + Number(r.total_exemption || 0), 0).toLocaleString('en-IN')}
                </div>
              </div>

              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <div className="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                  <span>Net Outstanding</span>
                  <FiCreditCard className="text-rose-500 w-4 h-4" />
                </div>
                <div className="text-2xl font-bold text-rose-600 mt-2">
                  ₹{filteredRecords.reduce((acc, r) => acc + Number(r.balance || 0), 0).toLocaleString('en-IN')}
                </div>
              </div>
            </div>
          )}

          {activeTab === 'all-previous' && (
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Defaulter Students</span>
                <div className="text-2xl font-bold text-slate-900 mt-1">
                  {previousReportData.summary?.students || 0}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Total Outstanding Arrears</span>
                <div className="text-2xl font-bold text-rose-600 mt-1">
                  ₹{Number(previousReportData.summary?.totalPending || 0).toLocaleString('en-IN')}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Average Per Student</span>
                <div className="text-2xl font-bold text-[#1976d2] mt-1">
                  ₹{Number(previousReportData.summary?.averagePending || 0).toLocaleString('en-IN')}
                </div>
              </div>
            </div>
          )}

          {activeTab === 'last-year' && (
            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Pending Students</span>
                <div className="text-2xl font-bold text-slate-900 mt-1">
                  {lastYearData.summary?.students || 0}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Total Pending</span>
                <div className="text-2xl font-bold text-rose-600 mt-1">
                  ₹{Number(lastYearData.summary?.totalPending || 0).toLocaleString('en-IN')}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Last Year Paid</span>
                <div className="text-2xl font-bold text-emerald-600 mt-1">
                  ₹{Number(lastYearData.summary?.totalPaid || 0).toLocaleString('en-IN')}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Total Exemptions</span>
                <div className="text-2xl font-bold text-amber-600 mt-1">
                  ₹{Number(lastYearData.summary?.totalExempted || 0).toLocaleString('en-IN')}
                </div>
              </div>
            </div>
          )}

          {activeTab === 'daily' && (
            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Transactions</span>
                <div className="text-2xl font-bold text-slate-900 mt-1">
                  {dailyData.summary?.total_transactions || 0}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Total Collected</span>
                <div className="text-2xl font-bold text-[#1976d2] mt-1">
                  ₹{Number(dailyData.summary?.total_collected || 0).toLocaleString('en-IN')}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Cash Collected</span>
                <div className="text-2xl font-bold text-emerald-600 mt-1">
                  ₹{Number(dailyData.summary?.cash_total || 0).toLocaleString('en-IN')}
                </div>
              </div>
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span className="text-xs font-semibold text-slate-500 uppercase">Online / Bank Paid</span>
                <div className="text-2xl font-bold text-[#1976d2] mt-1">
                  ₹{Number(dailyData.summary?.online_total || 0).toLocaleString('en-IN')}
                </div>
              </div>
            </div>
          )}

          {/* Filters Bar */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <div className="flex flex-wrap items-center gap-3 w-full md:w-auto">
              {/* Academic Year (For tabs that use it) */}
              {activeTab !== 'all-previous' && (
                <div className="flex items-center gap-2">
                  <span className="text-xs font-semibold text-slate-500">Year:</span>
                  <select
                    value={selectedYear}
                    onChange={(e) => setSelectedYear(e.target.value)}
                    className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  >
                    {academicYears.map((y) => (
                      <option key={y.stvacdm_id} value={y.stvacdm_id}>
                        {y.stvacdm_year}
                      </option>
                    ))}
                  </select>
                </div>
              )}

              {/* Standard & Division Filters */}
              {(activeTab === 'report' || activeTab === 'zero-paid' || activeTab === 'balance' || activeTab === 'daily') && (
                <>
                  <div className="flex items-center gap-2">
                    <span className="text-xs font-semibold text-slate-500">Standard:</span>
                    <select
                      value={selectedStandard}
                      onChange={(e) => setSelectedStandard(e.target.value)}
                      className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                    >
                      <option value="all">All Standards</option>
                      {standards.map((c, idx) => (
                        <option key={idx} value={c}>
                          {c}
                        </option>
                      ))}
                    </select>
                  </div>

                  {activeTab === 'daily' && (
                    <div className="flex items-center gap-2">
                      <span className="text-xs font-semibold text-slate-500">Division:</span>
                      <select
                        value={selectedDivision}
                        onChange={(e) => setSelectedDivision(e.target.value)}
                        className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      >
                        <option value="all">All Divisions</option>
                        {divisions.map((d) => (
                          <option key={d} value={d}>
                            {d}
                          </option>
                        ))}
                      </select>
                    </div>
                  )}
                </>
              )}

              {/* Date Pickers for Daily Report */}
              {activeTab === 'daily' && (
                <>
                  <div className="flex items-center gap-1.5">
                    <span className="text-xs font-semibold text-slate-500">From:</span>
                    <input
                      type="date"
                      value={dailyFromDate}
                      onChange={(e) => setDailyFromDate(e.target.value)}
                      className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                    />
                  </div>
                  <div className="flex items-center gap-1.5">
                    <span className="text-xs font-semibold text-slate-500">To:</span>
                    <input
                      type="date"
                      value={dailyToDate}
                      onChange={(e) => setDailyToDate(e.target.value)}
                      className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                    />
                  </div>
                  <button
                    onClick={loadDailyFees}
                    className="px-3 py-1.5 bg-[#1976d2] text-white rounded-lg text-xs font-semibold hover:bg-[#1565c0] transition"
                  >
                    Filter
                  </button>
                </>
              )}
            </div>

            {/* Live Search Input */}
            <div className="relative w-full md:w-64">
              <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-3.5 h-3.5" />
              <input
                type="text"
                placeholder="Search student or ID..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
              />
            </div>
          </div>

          {/* TAB 1: Main Fee Report Table (All, Zero Paid, Pending Balances) */}
          {(activeTab === 'report' || activeTab === 'zero-paid' || activeTab === 'balance') && (
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                      <th className="p-3">#</th>
                      <th className="p-3">Student ID</th>
                      <th className="p-3">Student Name</th>
                      <th className="p-3">Class</th>
                      <th className="p-3 text-right">Total Fees</th>
                      <th className="p-3 text-right">Paid</th>
                      <th className="p-3 text-right">Exemption</th>
                      <th className="p-3 text-right">Balance</th>
                      <th className="p-3">Last Payment</th>
                      <th className="p-3 text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {loading ? (
                      <tr>
                        <td colSpan="10" className="p-8 text-center text-slate-400">
                          <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                          Generating fee report...
                        </td>
                      </tr>
                    ) : filteredRecords.length > 0 ? (
                      filteredRecords.map((r, idx) => (
                        <tr key={r.id} className="hover:bg-slate-50 transition">
                          <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                          <td className="p-3 font-mono font-medium text-slate-700">
                            {r.SPRIDEN_student_id}
                          </td>
                          <td className="p-3 font-semibold text-slate-900">
                            {r.name}
                            {r.rte_status === 'Yes' && (
                              <span className="ml-2 text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">
                                RTE
                              </span>
                            )}
                          </td>
                          <td className="p-3">
                            <span className="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                              {r.STVCLAS_standard} - {r.STVCLAS_class}
                            </span>
                          </td>
                          <td className="p-3 text-right text-slate-700 font-medium">
                            ₹{Number(r.total_fees).toLocaleString('en-IN')}
                          </td>
                          <td className="p-3 text-right font-semibold text-emerald-600">
                            ₹{Number(r.total_paid).toLocaleString('en-IN')}
                          </td>
                          <td className="p-3 text-right text-amber-600">
                            ₹{Number(r.total_exemption).toLocaleString('en-IN')}
                          </td>
                          <td className="p-3 text-right font-bold">
                            <span
                              className={`px-2 py-0.5 rounded ${
                                r.balance > 0
                                  ? 'bg-rose-50 text-rose-700'
                                  : 'bg-emerald-50 text-emerald-700'
                              }`}
                            >
                              {r.balance > 0 ? `₹${Number(r.balance).toLocaleString('en-IN')}` : 'PAID'}
                            </span>
                          </td>
                          <td className="p-3 text-slate-500">
                            {r.last_payment_date
                              ? new Date(r.last_payment_date).toLocaleDateString()
                              : '-'}
                          </td>
                          <td className="p-3 text-center">
                            <Link
                              to={`/fees?student_id=${r.id}&academic_year=${selectedYear}`}
                              className="inline-flex items-center gap-1 px-2.5 py-1 bg-[#e3f2fd] hover:bg-[#bbdefb] text-[#1976d2] font-semibold rounded text-[11px] transition"
                            >
                              Pay / Ledger <FiArrowRight />
                            </Link>
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="10" className="p-8 text-center text-slate-400">
                          No student fee records found matching criteria.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 2: All Previous Fees Report (CI3 Report::all_previous_fees_report) */}
          {activeTab === 'all-previous' && (
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                      <th className="p-3">#</th>
                      <th className="p-3">Student ID</th>
                      <th className="p-3">Student Name</th>
                      <th className="p-3">Mobile No.</th>
                      {(previousReportData.academic_years || []).map((y) => (
                        <th key={y.academic_year_id} className="p-3 text-right">
                          {y.academic_year_name}
                        </th>
                      ))}
                      <th className="p-3 text-right font-bold text-rose-600">Total Outstanding</th>
                      <th className="p-3 text-center">WhatsApp</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {loadingPrevious ? (
                      <tr>
                        <td colSpan={(previousReportData.academic_years?.length || 0) + 6} className="p-8 text-center text-slate-400">
                          <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                          Loading all previous fees arrears...
                        </td>
                      </tr>
                    ) : filteredPreviousRecords.length > 0 ? (
                      filteredPreviousRecords.map((r, idx) => {
                        const studentName = r.name || '';
                        const mobile = (r.MobileNumber || '').replace(/\D/g, '');
                        const cleanPhone = mobile.length === 10 ? `91${mobile}` : mobile;

                        let pendingDetails = '';
                        (previousReportData.academic_years || []).forEach((y) => {
                          const pAmt = Number(r[`Pending_${y.academic_year_id}`] || 0);
                          if (pAmt > 0) {
                            pendingDetails += `Academic Year: ${y.academic_year_name}\nPending Fees: ₹${pAmt.toLocaleString('en-IN')}\n-----------------------------\n`;
                          }
                        });

                        const msg = `Assalamualaikum\n\nDear Parent,\n\nThis is to inform you that previous academic year fees for your child ${studentName} are still pending.\n\nPending Fees Details\n=============================\n\n${pendingDetails}\nTotal Outstanding: ₹${Number(r.TotalPending).toLocaleString('en-IN')}\n\nKindly visit the school office and clear the pending fees at the earliest.\n\nआपके बच्चे ${studentName} की पिछली कक्षाओं की फीस अभी बाकी है।\nकुल बकाया फीस: ₹${Number(r.TotalPending).toLocaleString('en-IN')}\nकृपया स्कूल ऑफिस में आकर बाकी फीस जल्द से जल्द जमा करें।\n\nRegards,\nOlive English School\nDhule`;
                        const waUrl = cleanPhone ? `https://wa.me/${cleanPhone}?text=${encodeURIComponent(msg)}` : null;

                        return (
                          <tr key={r.id || idx} className="hover:bg-slate-50 transition">
                            <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                            <td className="p-3 font-mono font-medium text-slate-700">{r.SPRIDEN_student_id}</td>
                            <td className="p-3 font-semibold text-slate-900">{r.name}</td>
                            <td className="p-3 text-slate-600 font-mono">{r.MobileNumber || '-'}</td>
                            {(previousReportData.academic_years || []).map((y) => {
                              const amt = Number(r[`Pending_${y.academic_year_id}`] || 0);
                              return (
                                <td key={y.academic_year_id} className="p-3 text-right">
                                  {amt > 0 ? (
                                    <span className="font-semibold text-rose-600">₹{amt.toLocaleString('en-IN')}</span>
                                  ) : (
                                    <span className="text-slate-300">-</span>
                                  )}
                                </td>
                              );
                            })}
                            <td className="p-3 text-right font-bold text-rose-700">
                              ₹{Number(r.TotalPending || 0).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3 text-center">
                              {waUrl ? (
                                <a
                                  href={waUrl}
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  className="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold rounded text-[11px] transition border border-emerald-200"
                                >
                                  <FaWhatsapp className="text-emerald-600" /> WhatsApp
                                </a>
                              ) : (
                                <span className="text-slate-300 text-[10px]">No Mobile</span>
                              )}
                            </td>
                          </tr>
                        );
                      })
                    ) : (
                      <tr>
                        <td colSpan={(previousReportData.academic_years?.length || 0) + 6} className="p-8 text-center text-slate-400">
                          No previous academic year arrears found.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 3: Last Year Fees Report (CI3 Report::last_year_fees_report) */}
          {activeTab === 'last-year' && (
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                      <th className="p-3">#</th>
                      <th className="p-3">Student ID</th>
                      <th className="p-3">Student Name</th>
                      <th className="p-3">Mobile No.</th>
                      <th className="p-3">Class</th>
                      <th className="p-3 text-right">Total Fee</th>
                      <th className="p-3 text-right">Paid</th>
                      <th className="p-3 text-right">Exempted</th>
                      <th className="p-3 text-right font-bold text-rose-600">Pending</th>
                      <th className="p-3 text-center">WhatsApp</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {loadingLastYear ? (
                      <tr>
                        <td colSpan="10" className="p-8 text-center text-slate-400">
                          <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                          Loading last year pending fees...
                        </td>
                      </tr>
                    ) : filteredLastYearRecords.length > 0 ? (
                      filteredLastYearRecords.map((r, idx) => {
                        const studentName = r.name || '';
                        const mobile = (r.MobileNumber || '').replace(/\D/g, '');
                        const cleanPhone = mobile.length === 10 ? `91${mobile}` : mobile;
                        const msg = `Assalamualaikum\n\nDear Parent,\n\nThis is to inform you that the last academic year (${r.Academic_year}) fees for your child ${studentName} is still pending.\n\nPending Amount: ₹${Number(r.Pending_fees).toLocaleString('en-IN')}\n\nKindly visit the school office and clear the pending fees at the earliest.\n\nRegards,\nOlive English School\nDhule`;
                        const waUrl = cleanPhone ? `https://wa.me/${cleanPhone}?text=${encodeURIComponent(msg)}` : null;

                        return (
                          <tr key={r.id || idx} className="hover:bg-slate-50 transition">
                            <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                            <td className="p-3 font-mono font-medium text-slate-700">{r.SPRIDEN_student_id}</td>
                            <td className="p-3 font-semibold text-slate-900">{r.name}</td>
                            <td className="p-3 text-slate-600 font-mono">{r.MobileNumber || '-'}</td>
                            <td className="p-3">
                              <span className="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                                {r.Standard} - {r.Division}
                              </span>
                            </td>
                            <td className="p-3 text-right text-slate-700 font-medium">₹{Number(r.Total_fee).toLocaleString('en-IN')}</td>
                            <td className="p-3 text-right text-emerald-600 font-medium">₹{Number(r.Total_paid).toLocaleString('en-IN')}</td>
                            <td className="p-3 text-right text-amber-600 font-medium">₹{Number(r.Total_exumpted).toLocaleString('en-IN')}</td>
                            <td className="p-3 text-right font-bold text-rose-600">₹{Number(r.Pending_fees).toLocaleString('en-IN')}</td>
                            <td className="p-3 text-center">
                              {waUrl ? (
                                <a
                                  href={waUrl}
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  className="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold rounded text-[11px] transition border border-emerald-200"
                                >
                                  <FaWhatsapp className="text-emerald-600" /> WhatsApp
                                </a>
                              ) : (
                                <span className="text-slate-300 text-[10px]">No Mobile</span>
                              )}
                            </td>
                          </tr>
                        );
                      })
                    ) : (
                      <tr>
                        <td colSpan="10" className="p-8 text-center text-slate-400">
                          No last year pending fees records found.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 4: Daily & Datewise Collections (CI3 Report::daily_fees_report) */}
          {activeTab === 'daily' && (
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                      <th className="p-3">Receipt #</th>
                      <th className="p-3">Date</th>
                      <th className="p-3">Student ID</th>
                      <th className="p-3">Student Name</th>
                      <th className="p-3">Class</th>
                      <th className="p-3">Payment Mode</th>
                      <th className="p-3 text-right">Amount</th>
                      <th className="p-3">Received By</th>
                      <th className="p-3">Notes</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {loadingDaily ? (
                      <tr>
                        <td colSpan="9" className="p-8 text-center text-slate-400">
                          <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                          Loading daily collection records...
                        </td>
                      </tr>
                    ) : filteredDailyRecords.length > 0 ? (
                      filteredDailyRecords.map((r, idx) => (
                        <tr key={r.id || idx} className="hover:bg-slate-50 transition">
                          <td className="p-3 font-mono font-bold text-[#1976d2]">#{r.receipt_no}</td>
                          <td className="p-3 text-slate-600">{r.fee_date ? new Date(r.fee_date).toLocaleDateString() : '-'}</td>
                          <td className="p-3 font-mono font-medium text-slate-700">{r.student_id}</td>
                          <td className="p-3 font-semibold text-slate-900">{r.student_name}</td>
                          <td className="p-3">
                            <span className="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                              {r.standard} - {r.division}
                            </span>
                          </td>
                          <td className="p-3">
                            <span
                              className={`px-2 py-0.5 rounded text-[11px] font-semibold ${
                                (r.payment_mode || '').toLowerCase() === 'cash'
                                  ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                  : 'bg-blue-50 text-blue-700 border border-blue-200'
                              }`}
                            >
                              {r.payment_mode || 'Cash'}
                            </span>
                          </td>
                          <td className="p-3 text-right font-bold text-slate-900">
                            ₹{Number(r.amount).toLocaleString('en-IN')}
                          </td>
                          <td className="p-3 text-slate-600 font-medium">{r.received_by || '-'}</td>
                          <td className="p-3 text-slate-500 max-w-xs truncate" title={r.notes}>{r.notes || '-'}</td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="9" className="p-8 text-center text-slate-400">
                          No collection records found for the selected date range.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 5: Fees Comments & WhatsApp Notifications Table (CI3: Report::Fees_comment) */}
          {activeTab === 'comments' && (
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                      <th className="p-3">#</th>
                      <th className="p-3">Student ID</th>
                      <th className="p-3">Student Name</th>
                      <th className="p-3">Class</th>
                      <th className="p-3 text-right">Total Fee</th>
                      <th className="p-3 text-right">Paid</th>
                      <th className="p-3 text-right">Balance</th>
                      <th className="p-3">Last Paid</th>
                      <th className="p-3">Fee Comment / Notes</th>
                      <th className="p-3">WhatsApp / Mobile</th>
                      <th className="p-3 text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {loadingComments ? (
                      <tr>
                        <td colSpan="11" className="p-8 text-center text-slate-400">
                          <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                          Loading fees comments and notes...
                        </td>
                      </tr>
                    ) : filteredComments.length > 0 ? (
                      filteredComments.map((c, idx) => {
                        const whatsappMsg = `Hi ${c.student_name},\n\nFees Comment: ${c.last_payment_notes}\nPending Balance: ₹${c.balance_fee}\n\nFrom Olive English School`;
                        const whatsappUrl = c.father_whatsapp_number
                          ? `https://api.whatsapp.com/send?phone=91${c.father_whatsapp_number.replace(/\D/g, '')}&text=${encodeURIComponent(whatsappMsg)}`
                          : null;

                        return (
                          <tr key={c.id || idx} className="hover:bg-slate-50 transition">
                            <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                            <td className="p-3 font-mono font-medium text-slate-700">
                              {c.student_id}
                            </td>
                            <td className="p-3 font-semibold text-slate-900">
                              {c.student_name}
                            </td>
                            <td className="p-3">
                              <span className="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                                {c.class_name} - {c.division}
                              </span>
                            </td>
                            <td className="p-3 text-right text-slate-700 font-medium">
                              ₹{Number(c.total_fee || 0).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3 text-right font-semibold text-emerald-600">
                              ₹{Number(c.total_paid || 0).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3 text-right font-bold text-rose-600">
                              ₹{Number(c.balance_fee || 0).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3 text-slate-500">
                              <div>
                                {c.last_paid_date
                                  ? new Date(c.last_paid_date).toLocaleDateString()
                                  : '-'}
                              </div>
                              {c.last_paid_amount > 0 && (
                                <div className="text-[10px] text-emerald-600 font-medium">
                                  ₹{Number(c.last_paid_amount).toLocaleString('en-IN')}
                                </div>
                              )}
                            </td>
                            <td className="p-3">
                              <span className="inline-block px-2 py-1 rounded bg-[#e3f2fd] text-[#1976d2] font-medium text-[11px] max-w-xs truncate" title={c.last_payment_notes}>
                                {c.last_payment_notes}
                              </span>
                            </td>
                            <td className="p-3 text-slate-600 font-mono text-[11px]">
                              {c.father_whatsapp_number || c.father_mobile_number || '-'}
                            </td>
                            <td className="p-3 text-center">
                              {whatsappUrl ? (
                                <a
                                  href={whatsappUrl}
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  className="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold rounded text-[11px] transition border border-emerald-200"
                                  title="Send WhatsApp Reminder"
                                >
                                  <FaWhatsapp size={13} className="text-emerald-600" />
                                  <span>WhatsApp</span>
                                </a>
                              ) : (
                                <span className="text-slate-300 text-[10px]">No Mobile</span>
                              )}
                            </td>
                          </tr>
                        );
                      })
                    ) : (
                      <tr>
                        <td colSpan="11" className="p-8 text-center text-slate-400">
                          No student fee comments or notes recorded.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}
        </main>
      </div>
    </div>
  );
}
