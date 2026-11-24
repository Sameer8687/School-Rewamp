import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import feesExecutiveService from '../../services/feesExecutiveService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiPieChart,
  FiTrendingUp,
  FiDollarSign,
  FiUsers,
  FiCalendar,
  FiRefreshCw,
  FiAward,
  FiPercent,
  FiBarChart2,
  FiCreditCard,
  FiAlertCircle,
  FiCheckCircle,
} from 'react-icons/fi';

export default function FeesExecutiveDashboardPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);

  // Filters
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [fromDate, setFromDate] = useState('');
  const [toDate, setToDate] = useState('');
  const [quickRange, setQuickRange] = useState('all');

  // Dashboard Data
  const [data, setData] = useState(null);

  // Load Academic Years
  useEffect(() => {
    async function init() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          const years = res.data.options.academicYears || [];
          setAcademicYears(years);
          const active = years.find((y) => y.is_active) || years[0];
          if (active) setSelectedYear(String(active.stvacdm_id));
        }
      } catch (err) {
        console.error('Failed to load academic years:', err);
      }
    }
    init();
  }, []);

  // Fetch Dashboard Analytics
  const loadDashboardData = async () => {
    if (!selectedYear) return;
    setLoading(true);
    try {
      const res = await feesExecutiveService.getExecutiveDashboard({
        academicYear: selectedYear,
        fromDate: fromDate || undefined,
        toDate: toDate || undefined,
      });
      if (res.data?.success) {
        setData(res.data);
      }
    } catch (err) {
      console.error('Failed to load fees executive dashboard:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadDashboardData();
  }, [selectedYear, fromDate, toDate]);

  // Quick range helper
  const handleQuickRange = (range) => {
    setQuickRange(range);
    const now = new Date();
    if (range === '1m') {
      const past = new Date();
      past.setMonth(past.getMonth() - 1);
      setFromDate(past.toISOString().slice(0, 10));
      setToDate(now.toISOString().slice(0, 10));
    } else if (range === '6m') {
      const past = new Date();
      past.setMonth(past.getMonth() - 6);
      setFromDate(past.toISOString().slice(0, 10));
      setToDate(now.toISOString().slice(0, 10));
    } else if (range === '1y') {
      const past = new Date();
      past.setFullYear(past.getFullYear() - 1);
      setFromDate(past.toISOString().slice(0, 10));
      setToDate(now.toISOString().slice(0, 10));
    } else {
      setFromDate('');
      setToDate('');
    }
  };

  const summary = data?.summary || {
    total_students: 0,
    target_fees: 0,
    total_paid: 0,
    total_exemption: 0,
    total_balance: 0,
    total_rte: 0,
    collection_percentage: 0,
  };

  const classwise = data?.classwise || [];
  const monthlyTrends = data?.monthly_trends || [];
  const paymentModes = data?.payment_modes || [];

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav setSidebarOpen={setSidebarOpen} />
        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <div className="flex items-center gap-2">
                <span className="p-2 bg-[#e3f2fd] text-[#1976d2] rounded-lg">
                  <FiPieChart className="w-5 h-5" />
                </span>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight">
                  Fees Executive Analytics Dashboard
                </h1>
              </div>
              <p className="text-sm text-slate-500 mt-1">
                Visual fee targets vs realization, month-wise collection trends, RTE scholarships, and standard recovery rates
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={loadDashboardData}
                className="p-2 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition"
                title="Refresh Analytics"
              >
                <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
              </button>
            </div>
          </div>

          {/* Filter Bar */}
          <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap gap-4 items-center justify-between">
            <div className="flex flex-wrap items-center gap-3">
              <div className="w-48">
                <label className="block text-xs font-semibold text-slate-500 mb-1">Academic Year</label>
                <select
                  value={selectedYear}
                  onChange={(e) => setSelectedYear(e.target.value)}
                  className="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                >
                  {academicYears.map((y) => (
                    <option key={y.stvacdm_id} value={y.stvacdm_id}>
                      {y.stvacdm_year}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-500 mb-1">Date Range</label>
                <div className="flex items-center gap-2">
                  <input
                    type="date"
                    value={fromDate}
                    onChange={(e) => {
                      setFromDate(e.target.value);
                      setQuickRange('custom');
                    }}
                    className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                  />
                  <span className="text-slate-400 text-xs">to</span>
                  <input
                    type="date"
                    value={toDate}
                    onChange={(e) => {
                      setToDate(e.target.value);
                      setQuickRange('custom');
                    }}
                    className="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                  />
                </div>
              </div>

              {(fromDate || toDate) && (
                <button
                  onClick={() => handleQuickRange('all')}
                  className="text-xs text-[#1976d2] hover:underline pt-4"
                >
                  Clear Dates
                </button>
              )}
            </div>

            {/* Quick Range Buttons */}
            <div className="flex items-center gap-1.5 bg-slate-100 p-1 rounded-lg border border-slate-200">
              <button
                onClick={() => handleQuickRange('1m')}
                className={`px-3 py-1 rounded text-xs font-medium transition ${
                  quickRange === '1m'
                    ? 'bg-white  text-[#1976d2] shadow-sm'
                    : 'text-slate-600  hover:text-slate-900'
                }`}
              >
                1 Month
              </button>
              <button
                onClick={() => handleQuickRange('6m')}
                className={`px-3 py-1 rounded text-xs font-medium transition ${
                  quickRange === '6m'
                    ? 'bg-white  text-[#1976d2] shadow-sm'
                    : 'text-slate-600  hover:text-slate-900'
                }`}
              >
                6 Months
              </button>
              <button
                onClick={() => handleQuickRange('1y')}
                className={`px-3 py-1 rounded text-xs font-medium transition ${
                  quickRange === '1y'
                    ? 'bg-white  text-[#1976d2] shadow-sm'
                    : 'text-slate-600  hover:text-slate-900'
                }`}
              >
                1 Year
              </button>
              <button
                onClick={() => handleQuickRange('all')}
                className={`px-3 py-1 rounded text-xs font-medium transition ${
                  quickRange === 'all'
                    ? 'bg-white  text-[#1976d2] shadow-sm'
                    : 'text-slate-600  hover:text-slate-900'
                }`}
              >
                All-Time
              </button>
            </div>
          </div>

          {/* Primary KPI Metrics */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
              <div>
                <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                  Target Fee Commitment
                </p>
                <p className="text-2xl font-bold mt-1 text-slate-900">
                  ₹{summary.target_fees.toLocaleString('en-IN')}
                </p>
                <p className="text-xs text-slate-400 mt-0.5">
                  Across {summary.total_students} active students
                </p>
              </div>
              <div className="p-3 bg-[#e3f2fd] text-[#1976d2] rounded-xl">
                <FiUsers className="w-6 h-6" />
              </div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
              <div>
                <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                  Total Fees Realized
                </p>
                <p className="text-2xl font-bold mt-1 text-emerald-600">
                  ₹{summary.total_paid.toLocaleString('en-IN')}
                </p>
                <div className="flex items-center gap-1.5 mt-0.5">
                  <span className="text-xs font-bold text-emerald-600">
                    {summary.collection_percentage}%
                  </span>
                  <span className="text-xs text-slate-400">recovery rate</span>
                </div>
              </div>
              <div className="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <FiDollarSign className="w-6 h-6" />
              </div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
              <div>
                <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                  Outstanding Balance
                </p>
                <p className="text-2xl font-bold mt-1 text-rose-600">
                  ₹{summary.total_balance.toLocaleString('en-IN')}
                </p>
                <p className="text-xs text-slate-400 mt-0.5">
                  Pending recovery deficit
                </p>
              </div>
              <div className="p-3 bg-rose-50 text-rose-600 rounded-xl">
                <FiAlertCircle className="w-6 h-6" />
              </div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
              <div>
                <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                  Exemptions & RTE
                </p>
                <p className="text-2xl font-bold mt-1 text-amber-600">
                  ₹{summary.total_exemption.toLocaleString('en-IN')}
                </p>
                <p className="text-xs text-slate-400 mt-0.5">
                  {summary.total_rte} RTE beneficiaries
                </p>
              </div>
              <div className="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <FiAward className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Overall Realization Progress Bar */}
          <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-2">
            <div className="flex justify-between items-center text-xs">
              <span className="font-semibold text-slate-700">
                Annual Collection Progress Target
              </span>
              <span className="font-bold text-emerald-600 text-sm">
                {summary.collection_percentage}% Collected
              </span>
            </div>
            <div className="w-full bg-slate-100 h-3.5 rounded-full overflow-hidden flex">
              <div
                className="bg-emerald-500 h-full transition-all duration-500"
                style={{ width: `${Math.min(100, summary.collection_percentage)}%` }}
                title={`Collected: ${summary.collection_percentage}%`}
              />
              <div
                className="bg-amber-400 h-full transition-all duration-500"
                style={{
                  width: `${
                    summary.target_fees > 0
                      ? Math.min(100, (summary.total_exemption / summary.target_fees) * 100)
                      : 0
                  }%`,
                }}
                title="Exemptions / Scholarships"
              />
            </div>
            <div className="flex justify-between text-[11px] text-slate-400">
              <span>Realized: ₹{summary.total_paid.toLocaleString('en-IN')}</span>
              <span>Target: ₹{summary.target_fees.toLocaleString('en-IN')}</span>
            </div>
          </div>

          {/* Monthly Trends & Payment Modes */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* Monthly Trend Bars */}
            <div className="lg:col-span-2 bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
              <h3 className="font-semibold text-sm text-slate-900 flex items-center gap-2">
                <FiTrendingUp className="text-[#1976d2]" />
                Monthly Realization Timeline
              </h3>

              {monthlyTrends.length === 0 ? (
                <p className="text-xs text-slate-400 py-6 text-center">
                  No monthly transactions recorded for selected range.
                </p>
              ) : (
                <div className="space-y-3">
                  {monthlyTrends.map((m) => {
                    const maxVal = Math.max(...monthlyTrends.map((t) => t.total_collected), 1);
                    const pct = Math.round((m.total_collected / maxVal) * 100);
                    return (
                      <div key={m.month_key} className="space-y-1">
                        <div className="flex justify-between text-xs">
                          <span className="font-medium text-slate-700">
                            {m.month_name || m.month_key}
                          </span>
                          <span className="font-semibold text-emerald-600">
                            ₹{m.total_collected.toLocaleString('en-IN')}{' '}
                            <span className="text-slate-400 font-normal">
                              ({m.transaction_count} receipts)
                            </span>
                          </span>
                        </div>
                        <div className="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                          <div
                            className="bg-[#1976d2] h-full rounded-full transition-all duration-300"
                            style={{ width: `${pct}%` }}
                          />
                        </div>
                      </div>
                    );
                  })}
                </div>
              )}
            </div>

            {/* Payment Modes */}
            <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
              <h3 className="font-semibold text-sm text-slate-900 flex items-center gap-2">
                <FiCreditCard className="text-[#1976d2]" />
                Payment Channels
              </h3>

              {paymentModes.length === 0 ? (
                <p className="text-xs text-slate-400 py-6 text-center">
                  No channel data recorded.
                </p>
              ) : (
                <div className="space-y-3">
                  {paymentModes.map((pm, i) => (
                    <div
                      key={i}
                      className="p-3 rounded-lg bg-slate-50 border border-slate-100 flex justify-between items-center"
                    >
                      <div>
                        <p className="font-semibold text-xs text-slate-800">
                          {pm.mode}
                        </p>
                        <p className="text-[10px] text-slate-400">
                          {pm.tx_count} transactions
                        </p>
                      </div>
                      <p className="font-bold text-xs text-[#1976d2]">
                        ₹{pm.total_amount.toLocaleString('en-IN')}
                      </p>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>

          {/* Standard-Wise Target Breakdown Table */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden space-y-3 p-4">
            <h3 className="font-semibold text-sm text-slate-900 flex items-center gap-2">
              <FiBarChart2 className="text-emerald-500" />
              Standard-Wise Fee Realization Ledger
            </h3>

            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th className="py-3 px-4">Standard</th>
                    <th className="py-3 px-4 text-center">Active Students</th>
                    <th className="py-3 px-4 text-right">Fee / Student</th>
                    <th className="py-3 px-4 text-right">Target Amount</th>
                    <th className="py-3 px-4 text-right">Collected</th>
                    <th className="py-3 px-4 text-right">Exemptions</th>
                    <th className="py-3 px-4 text-right">Balance Due</th>
                    <th className="py-3 px-4 text-center">Recovery %</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 text-sm">
                  {loading ? (
                    <tr>
                      <td colSpan="8" className="py-12 text-center text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                        Calculating executive realization metrics...
                      </td>
                    </tr>
                  ) : classwise.length === 0 ? (
                    <tr>
                      <td colSpan="8" className="py-12 text-center text-slate-400">
                        No standard fee structures configured for this year.
                      </td>
                    </tr>
                  ) : (
                    classwise.map((row, idx) => (
                      <tr key={idx} className="hover:bg-slate-50 transition">
                        <td className="py-3 px-4 font-semibold text-xs text-slate-900">
                          {row.standard}
                        </td>
                        <td className="py-3 px-4 text-center font-mono text-xs text-slate-600">
                          {row.total_students}
                        </td>
                        <td className="py-3 px-4 text-right text-xs text-slate-600">
                          ₹{row.fee_per_student.toLocaleString('en-IN')}
                        </td>
                        <td className="py-3 px-4 text-right font-medium text-slate-900 text-xs">
                          ₹{row.target_fees.toLocaleString('en-IN')}
                        </td>
                        <td className="py-3 px-4 text-right font-semibold text-emerald-600 text-xs">
                          ₹{row.fees_paid.toLocaleString('en-IN')}
                        </td>
                        <td className="py-3 px-4 text-right text-amber-600 text-xs">
                          ₹{row.exemption.toLocaleString('en-IN')}
                        </td>
                        <td className="py-3 px-4 text-right font-semibold text-rose-600 text-xs">
                          ₹{row.balance.toLocaleString('en-IN')}
                        </td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-2">
                            <span className="text-xs font-bold text-slate-700 w-10 text-right">
                              {row.collection_percentage}%
                            </span>
                            <div className="w-16 bg-slate-100 h-2 rounded-full overflow-hidden">
                              <div
                                className={`h-full rounded-full ${
                                  row.collection_percentage >= 70
                                    ? 'bg-emerald-500'
                                    : row.collection_percentage >= 40
                                    ? 'bg-amber-500'
                                    : 'bg-rose-500'
                                }`}
                                style={{ width: `${Math.min(100, row.collection_percentage)}%` }}
                              />
                            </div>
                          </div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
