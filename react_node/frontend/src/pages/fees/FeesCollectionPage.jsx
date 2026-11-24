import React, { useState, useEffect, useRef } from 'react';
import { useSearchParams } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import feesService from '../../services/feesService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiDollarSign,
  FiSearch,
  FiX,
  FiPhone,
  FiCalendar,
  FiPrinter,
  FiAlertCircle,
  FiCheckCircle,
  FiCreditCard,
  FiClock,
  FiUserCheck,
  FiRefreshCw,
  FiCheck
} from 'react-icons/fi';
import { FaWhatsapp } from 'react-icons/fa';

export default function FeesCollectionPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [searchParams] = useSearchParams();

  // Academic years & options
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedAcademicYear, setSelectedAcademicYear] = useState('');

  // Student search
  const [searchQuery, setSearchQuery] = useState('');
  const [includeInactive, setIncludeInactive] = useState(false);
  const [searchResults, setSearchResults] = useState([]);
  const [isSearching, setIsSearching] = useState(false);
  const [showDropdown, setShowDropdown] = useState(false);

  // Selected student & ledger
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [ledger, setLedger] = useState(null);
  const [loadingLedger, setLoadingLedger] = useState(false);

  // Form inputs
  const [amountPaid, setAmountPaid] = useState('');
  const [feesDate, setFeesDate] = useState(new Date().toISOString().split('T')[0]);
  const [details, setDetails] = useState('-');
  const [concessionAmount, setConcessionAmount] = useState('0');
  const [concessionNote, setConcessionNote] = useState('NA');
  const [dueDate, setDueDate] = useState(() => {
    const d = new Date();
    d.setMonth(d.getMonth() + 1);
    return d.toISOString().split('T')[0];
  });
  const [paymentMode, setPaymentMode] = useState('cash');
  const [submitting, setSubmitting] = useState(false);
  const [submitMessage, setSubmitMessage] = useState(null);

  // Modal states
  const [showPrevBalanceModal, setShowPrevBalanceModal] = useState(false);
  const [showReceiptModal, setShowReceiptModal] = useState(false);

  const searchRef = useRef(null);

  // Close search dropdown when clicking outside
  useEffect(() => {
    function handleClickOutside(e) {
      if (searchRef.current && !searchRef.current.contains(e.target)) {
        setShowDropdown(false);
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Initial load: academic years
  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        const years = res.academicYears || res.data?.academicYears || res.data?.options?.academicYears || [];
        setAcademicYears(years);
        const isPre = searchParams.get('type') === 'pre';
        const targetYear = isPre
          ? years.find((y) => !y.is_active) || years[0]
          : years.find((y) => y.is_active) || years[0];
        if (targetYear) {
          setSelectedAcademicYear(String(targetYear.stvacdm_id || targetYear.id));
        }
      } catch (err) {
        console.error('Failed to load academic years:', err);
      }
    }
    loadOptions();
  }, []);

  // Pre-selected student ID from query param (e.g. ?student_id=123 or ?student-id=123)
  useEffect(() => {
    const queryStudentId = searchParams.get('student_id') || searchParams.get('student-id');
    const queryYear = searchParams.get('academic_year');
    if (queryYear) setSelectedAcademicYear(queryYear);

    if (queryStudentId && selectedAcademicYear) {
      loadLedger(queryStudentId, queryYear || selectedAcademicYear);
    }
  }, [searchParams, selectedAcademicYear]);

  // Live search for students
  useEffect(() => {
    if (!searchQuery || searchQuery.trim().length < 2) {
      setSearchResults([]);
      setShowDropdown(false);
      return;
    }

    const timer = setTimeout(async () => {
      setIsSearching(true);
      try {
        const res = await feesService.searchStudents({
          name: searchQuery,
          academic_year: selectedAcademicYear,
          include_inactive: includeInactive
        });
        if (res.data?.success) {
          setSearchResults(res.data.students || []);
          setShowDropdown(true);
        }
      } catch (err) {
        console.error('Search error:', err);
      } finally {
        setIsSearching(false);
      }
    }, 300);

    return () => clearTimeout(timer);
  }, [searchQuery, selectedAcademicYear, includeInactive]);

  // Load student ledger
  async function loadLedger(studentId, yearId = selectedAcademicYear) {
    setLoadingLedger(true);
    setSubmitMessage(null);
    try {
      const res = await feesService.getStudentLedger(studentId, yearId);
      if (res.data?.success) {
        const data = res.data.ledger;
        setLedger(data);
        setSelectedStudent(data.info);
        setShowDropdown(false);
        setSearchQuery(`${data.info.name} (${data.info.SPRIDEN_student_id})`);

        // If there are previous balances, show the alert/modal
        if (data.prev_balances && data.prev_balances.length > 0) {
          setShowPrevBalanceModal(true);
        }
      }
    } catch (err) {
      console.error('Error loading ledger:', err);
    } finally {
      setLoadingLedger(false);
    }
  }

  // Handle fee collection submit
  async function handleSubmit(e) {
    e.preventDefault();
    if (!selectedStudent) {
      alert('Please search and select a student first');
      return;
    }

    if (!amountPaid && !concessionAmount) {
      alert('Please enter an amount or concession');
      return;
    }

    setSubmitting(true);
    setSubmitMessage(null);
    try {
      const res = await feesService.collectFees({
        student_id: selectedStudent.id,
        standard: selectedStudent.STVCLAS_standard,
        amount: Number(amountPaid) || 0,
        fees_date: feesDate,
        details: details || '-',
        due_date: dueDate,
        academic_year: selectedAcademicYear,
        concession_amount: Number(concessionAmount) || 0,
        concession_note: concessionNote || 'NA',
        payment_mode: paymentMode
      });

      if (res.data?.success) {
        setSubmitMessage({ type: 'success', text: 'Fees collected successfully!' });
        setAmountPaid('');
        setDetails('-');
        setConcessionAmount('0');
        // Refresh ledger
        await loadLedger(selectedStudent.id, selectedAcademicYear);
      }
    } catch (err) {
      console.error('Collection failed:', err);
      setSubmitMessage({
        type: 'error',
        text: err.response?.data?.message || 'Failed to collect fees'
      });
    } finally {
      setSubmitting(false);
    }
  }

  // Print helper for receipt
  function handlePrintReceipt() {
    window.print();
  }

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiDollarSign className="text-[#1976d2]" /> {searchParams.get('type') === 'pre' ? 'Previous Fees Form' : 'Fees Collection'}
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                {searchParams.get('type') === 'pre'
                  ? 'Collection of student pending dues and arrears from previous academic years'
                  : 'Student fee collection, receipt generation, and ledger tracking'}
              </p>
            </div>
            <div className="flex items-center gap-2">
              {selectedStudent && (
                <button
                  onClick={() => setShowReceiptModal(true)}
                  className="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#e3f2fd] text-[#1976d2] hover:bg-[#bbdefb] rounded-lg text-sm font-semibold transition border border-[#bbdefb]"
                >
                  <FiPrinter /> Fee Receipt
                </button>
              )}
            </div>
          </div>

          {/* Search & Academic Year Bar */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200/80 flex flex-col md:flex-row items-center gap-4">
            {/* Search Input */}
            <div className="relative flex-1 w-full" ref={searchRef}>
              <div className="relative">
                <FiSearch className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                <input
                  type="text"
                  placeholder="Search student by name or ID (min 2 letters)..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
                />
                {searchQuery && (
                  <button
                    onClick={() => {
                      setSearchQuery('');
                      setSelectedStudent(null);
                      setLedger(null);
                    }}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                  >
                    <FiX />
                  </button>
                )}
              </div>

              {/* Autocomplete Results Dropdown */}
              {showDropdown && (
                <div className="absolute z-20 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-60 overflow-y-auto divide-y divide-slate-100">
                  {isSearching ? (
                    <div className="p-3 text-center text-xs text-slate-400">Searching...</div>
                  ) : searchResults.length > 0 ? (
                    searchResults.map((s) => (
                      <button
                        key={s.id}
                        onClick={() => loadLedger(s.id, selectedAcademicYear)}
                        className="w-full text-left px-4 py-2.5 hover:bg-[#e3f2fd]/50 flex items-center justify-between transition text-sm group"
                      >
                        <div>
                          <span className="font-semibold text-slate-800 group-hover:text-[#1976d2]">
                            {s.name}
                          </span>
                          <span className="ml-2 text-xs text-slate-500 font-mono">
                            ({s.SPRIDEN_student_id})
                          </span>
                        </div>
                        <span className="px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                          Std: {s.STVCLAS_standard} - {s.STVCLAS_class}
                        </span>
                      </button>
                    ))
                  ) : (
                    <div className="p-3 text-center text-xs text-slate-400">No students found</div>
                  )}
                </div>
              )}
            </div>

            {/* Inactive Checkbox */}
            <label className="flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer whitespace-nowrap">
              <input
                type="checkbox"
                checked={includeInactive}
                onChange={(e) => setIncludeInactive(e.target.checked)}
                className="rounded border-slate-300 text-[#1976d2] focus:ring-[#1976d2]"
              />
              Include Inactive
            </label>

            {/* Academic Year Selector */}
            <div className="flex items-center gap-2 w-full md:w-auto">
              <span className="text-xs font-medium text-slate-500 whitespace-nowrap">Academic Year:</span>
              <select
                value={selectedAcademicYear}
                onChange={(e) => {
                  setSelectedAcademicYear(e.target.value);
                  if (selectedStudent) {
                    loadLedger(selectedStudent.id, e.target.value);
                  }
                }}
                className="w-full md:w-44 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
              >
                {academicYears.map((y) => (
                  <option key={y.stvacdm_id} value={y.stvacdm_id}>
                    {y.stvacdm_year}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* Submission feedback */}
          {submitMessage && (
            <div
              className={`p-3.5 rounded-lg text-sm flex items-center gap-2 ${
                submitMessage.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border border-rose-200'
              }`}
            >
              {submitMessage.type === 'success' ? <FiCheck /> : <FiAlertCircle />}
              {submitMessage.text}
            </div>
          )}

          {/* Previous Balance Alert Banner */}
          {ledger?.prev_balances && ledger.prev_balances.length > 0 && (
            <div className="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                  <FiAlertCircle className="w-5 h-5" />
                </div>
                <div>
                  <h4 className="text-sm font-bold text-amber-900">
                    Previous Outstanding Balance Detected!
                  </h4>
                  <p className="text-xs text-amber-700 mt-0.5">
                    This student has unpaid balances from previous academic terms.
                  </p>
                </div>
              </div>
              <button
                onClick={() => setShowPrevBalanceModal(true)}
                className="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold shadow-sm transition"
              >
                View Balances
              </button>
            </div>
          )}

          {/* Main Card: Student Info + Form */}
          {selectedStudent && ledger ? (
            <div className="space-y-6">
              {/* Student Header Summary Card */}
              <div className="bg-slate-900 text-white p-5 rounded-xl shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                  <div className="flex items-center gap-2">
                    <span className="text-xs font-semibold px-2 py-0.5 rounded bg-[#1976d2]/20 text-[#e3f2fd] border border-[#1976d2]/30">
                      ID: {ledger.info.SPRIDEN_student_id}
                    </span>
                    <span className="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-500/30 text-emerald-200 border border-emerald-400/30">
                      Std: {ledger.info.STVCLAS_standard} - {ledger.info.STVCLAS_class}
                    </span>
                    {ledger.info.rte_status === 'Yes' && (
                      <span className="text-xs font-bold px-2 py-0.5 rounded bg-amber-400 text-amber-950">
                        RTE Student (Free)
                      </span>
                    )}
                  </div>
                  <h2 className="text-xl font-bold mt-1.5">{ledger.info.name}</h2>
                  <div className="flex items-center gap-4 mt-2 text-xs text-slate-300">
                    {ledger.contact_details?.mobile && (
                      <span className="flex items-center gap-1">
                        <FiPhone className="text-slate-400" /> {ledger.contact_details.mobile}
                      </span>
                    )}
                    {ledger.contact_details?.whatsapp && (
                      <span className="flex items-center gap-1 text-emerald-400">
                        <FaWhatsapp /> {ledger.contact_details.whatsapp}
                      </span>
                    )}
                  </div>
                </div>

                {/* Key Balance Figures */}
                <div className="flex items-center gap-6 border-t md:border-t-0 md:border-l border-slate-700 pt-3 md:pt-0 md:pl-6">
                  <div>
                    <div className="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                      Total Fees
                    </div>
                    <div className="text-xl font-bold text-white mt-0.5">
                      ₹{Number(ledger.total_fees).toLocaleString('en-IN')}
                    </div>
                  </div>
                  <div>
                    <div className="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                      Paid
                    </div>
                    <div className="text-xl font-bold text-emerald-400 mt-0.5">
                      ₹{Number(ledger.total_paid).toLocaleString('en-IN')}
                    </div>
                  </div>
                  <div>
                    <div className="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                      Balance
                    </div>
                    <div
                      className={`text-xl font-bold mt-0.5 ${
                        ledger.total_balance > 0 ? 'text-rose-400' : 'text-emerald-400'
                      }`}
                    >
                      {ledger.total_balance > 0
                        ? `₹${Number(ledger.total_balance).toLocaleString('en-IN')}`
                        : 'Full Paid'}
                    </div>
                  </div>
                </div>
              </div>

              {/* RTE Notice if student is RTE */}
              {ledger.info.rte_status === 'Yes' ? (
                <div className="p-8 bg-amber-50 border border-amber-200 rounded-xl text-center">
                  <FiAlertCircle className="w-12 h-12 text-amber-600 mx-auto mb-2" />
                  <h3 className="text-lg font-bold text-amber-900">RTE Student Admission</h3>
                  <p className="text-sm text-amber-700 mt-1 max-w-md mx-auto">
                    This student is admitted under Right to Education (RTE) quota. Standard school fees
                    are subsidized/exempted by government policy.
                  </p>
                </div>
              ) : (
                /* Fee Entry Form */
                <form
                  onSubmit={handleSubmit}
                  className="bg-white p-6 rounded-xl shadow-sm border border-slate-200/80 space-y-6"
                >
                  <h3 className="text-base font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                    <FiCreditCard className="text-[#1976d2]" /> New Payment Transaction
                  </h3>

                  <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {/* Amount to Pay */}
                    <div>
                      <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                        Amount Paid (₹) <span className="text-rose-500">*</span>
                      </label>
                      <input
                        type="number"
                        required
                        min="1"
                        placeholder="e.g. 5000"
                        value={amountPaid}
                        onChange={(e) => setAmountPaid(e.target.value)}
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      />
                    </div>

                    {/* Fees Date */}
                    <div>
                      <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                        Fees Date <span className="text-rose-500">*</span>
                      </label>
                      <input
                        type="date"
                        required
                        value={feesDate}
                        onChange={(e) => setFeesDate(e.target.value)}
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      />
                    </div>

                    {/* Payment Mode */}
                    <div>
                      <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                        Payment Mode <span className="text-rose-500">*</span>
                      </label>
                      <select
                        value={paymentMode}
                        onChange={(e) => setPaymentMode(e.target.value)}
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      >
                        <option value="cash">Cash</option>
                        <option value="online">Online / UPI / Netbanking</option>
                        <option value="cheque">Cheque</option>
                      </select>
                    </div>

                    {/* Next Due Date */}
                    <div>
                      <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                        Next Due Date <span className="text-rose-500">*</span>
                      </label>
                      <input
                        type="date"
                        required
                        value={dueDate}
                        onChange={(e) => setDueDate(e.target.value)}
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      />
                    </div>

                    {/* Concession Amount */}
                    <div>
                      <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                        Concession / Exemption (₹)
                      </label>
                      <input
                        type="number"
                        min="0"
                        value={concessionAmount}
                        onChange={(e) => setConcessionAmount(e.target.value)}
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      />
                    </div>

                    {/* Concession Note */}
                    <div>
                      <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                        Concession Note
                      </label>
                      <input
                        type="text"
                        value={concessionNote}
                        onChange={(e) => setConcessionNote(e.target.value)}
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      />
                    </div>

                    {/* Transaction Note */}
                    <div className="sm:col-span-2">
                      <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                        Transaction Remarks / Note
                      </label>
                      <input
                        type="text"
                        value={details}
                        onChange={(e) => setDetails(e.target.value)}
                        placeholder="Receipt reference, Cheque number, etc."
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      />
                    </div>
                  </div>

                  <div className="pt-2 flex justify-end">
                    <button
                      type="submit"
                      disabled={submitting}
                      className="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-semibold rounded-lg text-sm shadow-sm transition flex items-center gap-2"
                    >
                      {submitting ? (
                        <>
                          <FiRefreshCw className="animate-spin" /> Recording...
                        </>
                      ) : (
                        <>
                          <FiCheckCircle /> Collect & Save Fees
                        </>
                      )}
                    </button>
                  </div>
                </form>
              )}

              {/* Transactions Ledger Table */}
              <div className="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                  <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <FiClock className="text-[#1976d2]" /> Payment History (Current Term)
                  </h3>
                  <span className="text-xs text-slate-500 font-medium">
                    {ledger.receipts?.length || 0} transaction(s) recorded
                  </span>
                </div>

                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                        <th className="p-3">#</th>
                        <th className="p-3">Date</th>
                        <th className="p-3">Standard</th>
                        <th className="p-3">Amount</th>
                        <th className="p-3">Exemption</th>
                        <th className="p-3">Payment Mode</th>
                        <th className="p-3">Collected By</th>
                        <th className="p-3">Notes</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {ledger.receipts?.length > 0 ? (
                        ledger.receipts.map((r, idx) => (
                          <tr key={r.sprfees_pk} className="hover:bg-slate-50/50 transition">
                            <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                            <td className="p-3 font-medium text-slate-800">
                              {r.sprfees_feesdate ? new Date(r.sprfees_feesdate).toLocaleDateString() : '-'}
                            </td>
                            <td className="p-3">{r.sprfees_standard}</td>
                            <td className="p-3 font-semibold text-emerald-600">
                              ₹{Number(r.sprfees_amount).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3 text-amber-600">
                              ₹{Number(r.sprfees_exumption).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3">
                              <span className="px-2 py-0.5 rounded text-[11px] font-semibold uppercase bg-slate-100 text-slate-700">
                                {r.payment_mode || 'Cash'}
                              </span>
                            </td>
                            <td className="p-3 text-slate-600">{r.spruser_name || 'Admin'}</td>
                            <td className="p-3 text-slate-500">{r.sprfees_notes || '-'}</td>
                          </tr>
                        ))
                      ) : (
                        <tr>
                          <td colSpan="8" className="p-6 text-center text-slate-400">
                            No fee payments recorded yet for this academic year.
                          </td>
                        </tr>
                      )}
                    </tbody>
                  </table>
                </div>
              </div>

              {/* All Academic Years Summary Table */}
              {ledger.all_years_summary?.length > 0 && (
                <div className="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
                  <div className="px-5 py-4 border-b border-slate-100">
                    <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                      <FiCalendar className="text-[#1976d2]" /> Multi-Year Historical Fee Ledger
                    </h3>
                  </div>
                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                          <th className="p-3">Academic Year</th>
                          <th className="p-3">Standard</th>
                          <th className="p-3">Total Fees</th>
                          <th className="p-3">Total Paid</th>
                          <th className="p-3">Exempted</th>
                          <th className="p-3">Balance</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100">
                        {ledger.all_years_summary.map((y, idx) => (
                          <tr key={idx} className="hover:bg-slate-50/50 transition">
                            <td className="p-3 font-semibold text-slate-800">{y.stvacdm_year}</td>
                            <td className="p-3">{y.STVCLAS_standard}</td>
                            <td className="p-3">₹{Number(y.class_fees).toLocaleString('en-IN')}</td>
                            <td className="p-3 text-emerald-600 font-semibold">
                              ₹{Number(y.total_paid).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3 text-amber-600">
                              ₹{Number(y.total_exemption).toLocaleString('en-IN')}
                            </td>
                            <td className="p-3 font-bold">
                              <span
                                className={`px-2 py-0.5 rounded ${
                                  y.balance > 0
                                    ? 'bg-rose-50 text-rose-700 font-bold'
                                    : 'bg-emerald-50 text-emerald-700'
                                }`}
                              >
                                {y.balance > 0 ? `₹${Number(y.balance).toLocaleString('en-IN')}` : 'Settled'}
                              </span>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              )}
            </div>
          ) : loadingLedger ? (
            <div className="p-12 text-center text-slate-400 bg-white rounded-xl border border-slate-200">
              <FiRefreshCw className="w-8 h-8 animate-spin mx-auto text-[#1976d2] mb-2" />
              Loading student fee account...
            </div>
          ) : (
            <div className="p-16 text-center text-slate-400 bg-white rounded-xl border border-slate-200/80">
              <FiDollarSign className="w-12 h-12 text-slate-300 mx-auto mb-3" />
              <h3 className="text-base font-semibold text-slate-700">No Student Selected</h3>
              <p className="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                Search student by surname, student name, or admission ID using the search box above to
                load fees account and record collections.
              </p>
            </div>
          )}
        </main>
      </div>

      {/* Previous Balances Modal */}
      {showPrevBalanceModal && ledger?.prev_balances && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
          <div className="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
            <div className="px-5 py-4 bg-amber-500 text-white flex items-center justify-between">
              <h3 className="font-bold flex items-center gap-2 text-sm">
                <FiAlertCircle /> Previous Academic Dues
              </h3>
              <button
                onClick={() => setShowPrevBalanceModal(false)}
                className="text-white/80 hover:text-white"
              >
                <FiX className="w-5 h-5" />
              </button>
            </div>
            <div className="p-5 space-y-4">
              <p className="text-xs text-slate-600">
                The student has unpaid balances in earlier academic years. You can review or switch to
                collect fees for these specific years:
              </p>

              <div className="overflow-x-auto border border-slate-200 rounded-lg">
                <table className="w-full text-left text-xs">
                  <thead className="bg-slate-50 border-b border-slate-200 font-semibold text-slate-600">
                    <tr>
                      <th className="p-2.5">Class</th>
                      <th className="p-2.5">Year</th>
                      <th className="p-2.5">Fees</th>
                      <th className="p-2.5">Paid</th>
                      <th className="p-2.5">Balance</th>
                      <th className="p-2.5">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {ledger.prev_balances.map((item, idx) => (
                      <tr key={idx} className="hover:bg-slate-50">
                        <td className="p-2.5 font-medium">{item.class}</td>
                        <td className="p-2.5">{item.stvacdm_year}</td>
                        <td className="p-2.5">₹{item.total}</td>
                        <td className="p-2.5 text-emerald-600">₹{item.paid}</td>
                        <td className="p-2.5 font-bold text-rose-600">₹{item.balance}</td>
                        <td className="p-2.5">
                          <button
                            onClick={() => {
                              setSelectedAcademicYear(String(item.acdm));
                              loadLedger(selectedStudent.id, String(item.acdm));
                              setShowPrevBalanceModal(false);
                            }}
                            className="px-2 py-1 bg-[#e3f2fd] text-[#1976d2] hover:bg-[#bbdefb] rounded text-[11px] font-semibold transition"
                          >
                            Collect
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
            <div className="px-5 py-3 bg-slate-50 border-t border-slate-100 flex justify-end">
              <button
                onClick={() => setShowPrevBalanceModal(false)}
                className="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-semibold"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Printable Receipt Modal */}
      {showReceiptModal && ledger && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl max-w-2xl w-full p-8 border border-slate-200 my-8">
            {/* Action Bar (hidden in print) */}
            <div className="flex items-center justify-between pb-6 border-b border-slate-200 print:hidden">
              <span className="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                Official Fee Receipt Preview
              </span>
              <div className="flex items-center gap-2">
                <button
                  onClick={handlePrintReceipt}
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm transition"
                >
                  <FiPrinter /> Print Receipt
                </button>
                <button
                  onClick={() => setShowReceiptModal(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition"
                >
                  Close
                </button>
              </div>
            </div>

            {/* Printable Receipt Layout */}
            <div className="pt-6 space-y-6 text-slate-800">
              {/* Header */}
              <div className="text-center space-y-1">
                <img
                  src="https://school.oliveschool.org.in/images/logoNewDev.png"
                  alt="Olive English School"
                  className="h-14 mx-auto mb-2"
                />
                <h2 className="text-xl font-bold tracking-tight text-slate-900">
                  Olive English School & Junior College
                </h2>
                <p className="text-xs text-slate-500 font-medium">
                  Official Fees Transaction & Acknowledgment Receipt
                </p>
                <div className="text-xs text-slate-400 pt-1">
                  Receipt Date: {new Date().toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })}
                </div>
              </div>

              {/* Student Metadata Box */}
              <div className="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-lg border border-slate-200 text-xs">
                <div>
                  <span className="text-slate-400">Student ID:</span>{' '}
                  <span className="font-bold text-slate-900">{ledger.info.SPRIDEN_student_id}</span>
                </div>
                <div>
                  <span className="text-slate-400">Academic Year:</span>{' '}
                  <span className="font-bold text-slate-900">
                    {academicYears.find((y) => String(y.stvacdm_id) === String(selectedAcademicYear))?.stvacdm_year || '-'}
                  </span>
                </div>
                <div>
                  <span className="text-slate-400">Student Name:</span>{' '}
                  <span className="font-bold text-slate-900">{ledger.info.name}</span>
                </div>
                <div>
                  <span className="text-slate-400">Standard & Class:</span>{' '}
                  <span className="font-bold text-slate-900">
                    {ledger.info.STVCLAS_standard} - {ledger.info.STVCLAS_class}
                  </span>
                </div>
              </div>

              {/* Fee Breakdown Table */}
              <div className="border border-slate-200 rounded-lg overflow-hidden">
                <table className="w-full text-left text-xs border-collapse">
                  <thead className="bg-slate-100 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                      <th className="p-2.5">#</th>
                      <th className="p-2.5">Date</th>
                      <th className="p-2.5">Mode</th>
                      <th className="p-2.5">Collected By</th>
                      <th className="p-2.5 text-right">Amount (₹)</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {ledger.receipts?.map((r, i) => (
                      <tr key={r.sprfees_pk}>
                        <td className="p-2.5 text-slate-400">{i + 1}</td>
                        <td className="p-2.5">
                          {r.sprfees_feesdate ? new Date(r.sprfees_feesdate).toLocaleDateString() : '-'}
                        </td>
                        <td className="p-2.5 uppercase font-medium">{r.payment_mode || 'Cash'}</td>
                        <td className="p-2.5 text-slate-600">{r.spruser_name || 'Accounts'}</td>
                        <td className="p-2.5 text-right font-semibold">
                          ₹{Number(r.sprfees_amount).toLocaleString('en-IN')}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                  <tfoot className="bg-slate-50 border-t border-slate-200 font-bold text-xs">
                    <tr>
                      <td colSpan="4" className="p-2.5 text-right text-slate-600">
                        Total Annual Fees:
                      </td>
                      <td className="p-2.5 text-right text-slate-900">
                        ₹{Number(ledger.total_fees).toLocaleString('en-IN')}
                      </td>
                    </tr>
                    <tr>
                      <td colSpan="4" className="p-2.5 text-right text-slate-600">
                        Total Amount Paid:
                      </td>
                      <td className="p-2.5 text-right text-emerald-600">
                        ₹{Number(ledger.total_paid).toLocaleString('en-IN')}
                      </td>
                    </tr>
                    {ledger.total_exemption > 0 && (
                      <tr>
                        <td colSpan="4" className="p-2.5 text-right text-slate-600">
                          Total Concession / Exemption:
                        </td>
                        <td className="p-2.5 text-right text-amber-600">
                          ₹{Number(ledger.total_exemption).toLocaleString('en-IN')}
                        </td>
                      </tr>
                    )}
                    <tr className="border-t-2 border-slate-300">
                      <td colSpan="4" className="p-2.5 text-right text-slate-800 uppercase">
                        Net Outstanding Balance:
                      </td>
                      <td className="p-2.5 text-right text-rose-600 font-extrabold">
                        {ledger.total_balance > 0
                          ? `₹${Number(ledger.total_balance).toLocaleString('en-IN')}`
                          : 'NIL (PAID)'}
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

              {/* Signatures */}
              <div className="pt-10 flex justify-between text-xs text-slate-500 font-medium">
                <div className="text-center">
                  <div className="w-36 border-b border-slate-400 mb-1"></div>
                  Cashier / Accounts Officer
                </div>
                <div className="text-center">
                  <div className="w-36 border-b border-slate-400 mb-1"></div>
                  School Seal & Principal
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
