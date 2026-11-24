import React, { useState, useEffect } from 'react';
import { useLocation, useSearchParams } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import feesService from '../../services/feesService.js';
import {
  FiCheckCircle,
  FiAlertCircle,
  FiCalendar,
  FiRefreshCw,
  FiEdit2,
  FiX,
  FiCreditCard,
  FiArrowRight,
  FiDollarSign,
  FiUploadCloud,
  FiList,
  FiSearch,
  FiFileText,
  FiDownload,
  FiLayers,
} from 'react-icons/fi';

export default function FeesReconciliationPage() {
  const location = useLocation();
  const [searchParams] = useSearchParams();
  const isUploadRoute = location.pathname.toLowerCase().includes('bank-upload');
  const initialTab = searchParams.get('tab') || (isUploadRoute ? 'upload' : 'reconciliation');

  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [activeTab, setActiveTab] = useState(initialTab);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (location.pathname.toLowerCase().includes('bank-upload')) {
      setActiveTab('upload');
    } else if (location.pathname.toLowerCase().includes('transactions') || location.pathname.toLowerCase().includes('reconciliation')) {
      setActiveTab('reconciliation');
    }
  }, [location.pathname]);

  // Tab 1: Reconciliation State
  const today = new Date().toISOString().split('T')[0];
  const [fromDate, setFromDate] = useState(today);
  const [toDate, setToDate] = useState(today);

  const [bankTransactions, setBankTransactions] = useState([]);
  const [bankTotal, setBankTotal] = useState(0);
  const [dailyFees, setDailyFees] = useState([]);
  const [dailyTotal, setDailyTotal] = useState(0);
  const [difference, setDifference] = useState(0);

  // Modal to update fee date
  const [editModalData, setEditModalData] = useState(null);
  const [newDate, setNewDate] = useState('');
  const [updatingDate, setUpdatingDate] = useState(false);
  const [feedback, setFeedback] = useState(null);

  // Tab 2: Bank Statement Upload State
  const [selectedFile, setSelectedFile] = useState(null);
  const [uploading, setUploading] = useState(false);
  const [uploadResult, setUploadResult] = useState(null);
  const [uploadError, setUploadError] = useState('');

  // Tab 3: Bank Transactions Ledger State
  const [ledgerLoading, setLedgerLoading] = useState(false);
  const [allTransactions, setAllTransactions] = useState([]);
  const [ledgerTotalCount, setLedgerTotalCount] = useState(0);
  const [ledgerTotalAmount, setLedgerTotalAmount] = useState(0);
  const [txnSearch, setTxnSearch] = useState('');
  const [ledgerFromDate, setLedgerFromDate] = useState('');
  const [ledgerToDate, setLedgerToDate] = useState('');

  // Tab 4: Upload Batches State
  const [batches, setBatches] = useState([]);
  const [loadingBatches, setLoadingBatches] = useState(false);

  useEffect(() => {
    if (activeTab === 'reconciliation') {
      loadReconciliation();
    } else if (activeTab === 'ledger') {
      loadBankLedger();
    } else if (activeTab === 'batches') {
      loadBatches();
    }
  }, [activeTab]);

  // Load Tab 1 Reconciliation
  async function loadReconciliation() {
    setLoading(true);
    setFeedback(null);
    try {
      const res = await feesService.getReconciliation(fromDate, toDate);
      if (res.data?.success) {
        setBankTransactions(res.data.bank_transactions || []);
        setBankTotal(res.data.bank_total || 0);
        setDailyFees(res.data.daily_fees || []);
        setDailyTotal(res.data.daily_total || 0);
        setDifference(res.data.difference || 0);
      }
    } catch (err) {
      console.error('Failed to load reconciliation:', err);
    } finally {
      setLoading(false);
    }
  }

  // Handle Fee Date Adjustment
  async function handleUpdateDateSubmit(e) {
    e.preventDefault();
    if (!editModalData || !newDate) return;

    setUpdatingDate(true);
    try {
      const res = await feesService.updateFeeDate({
        sprfees_pk: editModalData.sprfees_pk,
        student_id: editModalData.sprfees_studentid,
        update_date: newDate,
        current_date: editModalData.sprfees_feesdate ? editModalData.sprfees_feesdate.split('T')[0] : '',
      });

      if (res.data?.success) {
        setFeedback({ type: 'success', text: 'Fee date adjusted successfully!' });
        setEditModalData(null);
        await loadReconciliation();
      }
    } catch (err) {
      console.error('Update date failed:', err);
      setFeedback({ type: 'error', text: 'Failed to update fee date' });
    } finally {
      setUpdatingDate(false);
    }
  }

  // Handle File Upload
  async function handleFileUpload(e) {
    e.preventDefault();
    if (!selectedFile) {
      setUploadError('Please select an Excel (.xlsx/.xls) or CSV statement file.');
      return;
    }

    setUploading(true);
    setUploadError('');
    setUploadResult(null);

    const formData = new FormData();
    formData.append('bank_file', selectedFile);

    try {
      const res = await feesService.uploadBankStatement(formData);
      if (res.data?.success) {
        setUploadResult(res.data.data);
        setSelectedFile(null);
      } else {
        setUploadError(res.data?.message || 'Upload failed.');
      }
    } catch (err) {
      setUploadError(err.response?.data?.message || 'Error uploading file.');
    } finally {
      setUploading(false);
    }
  }

  // Load Tab 3 Bank Ledger
  async function loadBankLedger() {
    setLedgerLoading(true);
    try {
      const res = await feesService.fetchBankTransactions({
        search: txnSearch || undefined,
        from_date: ledgerFromDate || undefined,
        to_date: ledgerToDate || undefined,
        limit: 100,
      });
      if (res.data?.success) {
        setAllTransactions(res.data.data?.transactions || []);
        setLedgerTotalCount(res.data.data?.total_count || 0);
        setLedgerTotalAmount(res.data.data?.total_amount || 0);
      }
    } catch (err) {
      console.error('Failed to load bank ledger:', err);
    } finally {
      setLedgerLoading(false);
    }
  }

  // Load Tab 4 Batches
  async function loadBatches() {
    setLoadingBatches(true);
    try {
      const res = await feesService.fetchBankGroups();
      if (res.data?.success) {
        setBatches(res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load batches:', err);
    } finally {
      setLoadingBatches(false);
    }
  }

  // Export Ledger to CSV
  const exportLedgerToCSV = () => {
    if (!allTransactions || allTransactions.length === 0) return;
    const headers = [
      '#',
      'Transaction ID',
      'Value Date',
      'Txn Posted Date',
      'Cheque No',
      'Description',
      'Mode',
      'Amount (INR)',
    ];

    const rows = allTransactions.map((t, idx) => [
      idx + 1,
      `"${t.transaction_id || ''}"`,
      t.value_date ? t.value_date.split('T')[0] : '',
      t.txn_posted_date ? t.txn_posted_date.replace('T', ' ').slice(0, 19) : '',
      `"${t.cheque_no || ''}"`,
      `"${(t.description || '').replace(/"/g, '""')}"`,
      t.mode || 'CR',
      t.transaction_amount || 0,
    ]);

    const csvContent =
      'data:text/csv;charset=utf-8,' +
      [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `Bank_Statement_Transactions_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiCreditCard className="text-[#1976d2]" /> Bank Statement & Reconciliation Center
              </h1>
              <p className="text-xs text-slate-500 mt-1">
                Automated bank statement parsing, transaction ledger, and daily school fees reconciliation (CI3: Fees_bank_upload.php)
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={() => {
                  if (activeTab === 'reconciliation') loadReconciliation();
                  if (activeTab === 'ledger') loadBankLedger();
                  if (activeTab === 'batches') loadBatches();
                }}
                disabled={loading || ledgerLoading}
                className="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition shadow-sm cursor-pointer"
              >
                <FiRefreshCw size={13} className={loading || ledgerLoading ? 'animate-spin' : ''} />
                <span>Refresh</span>
              </button>
            </div>
          </div>

          {/* Navigation Tabs */}
          <div className="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto scrollbar-none">
            {[
              { id: 'reconciliation', label: 'Reconciliation Matching', icon: FiCheckCircle },
              { id: 'upload', label: 'Upload Statement (.xlsx / .csv)', icon: FiUploadCloud },
              { id: 'ledger', label: 'Bank Transactions Ledger', icon: FiList },
              { id: 'batches', label: 'Batch Upload History', icon: FiLayers },
            ].map((tab) => {
              const Icon = tab.icon;
              const isActive = activeTab === tab.id;
              return (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-xs whitespace-nowrap transition cursor-pointer ${
                    isActive
                      ? 'bg-[#1976d2] text-white shadow-sm shadow-[#1976d2]/30 font-semibold'
                      : 'bg-white text-slate-600 hover:bg-slate-100 hover:text-slate-900 border border-slate-200/80'
                  }`}
                >
                  <Icon size={14} />
                  <span>{tab.label}</span>
                </button>
              );
            })}
          </div>

          {/* ═════════════════════════════════════════════════════════════════
              TAB 1: DAILY FEES RECONCILIATION
             ═════════════════════════════════════════════════════════════════ */}
          {activeTab === 'reconciliation' && (
            <div className="space-y-6">
              {/* Filter Controls */}
              <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap items-center justify-between gap-4">
                <div className="flex flex-wrap items-center gap-3">
                  <div className="flex items-center gap-2 text-xs">
                    <span className="font-semibold text-slate-600">From:</span>
                    <input
                      type="date"
                      value={fromDate}
                      onChange={(e) => setFromDate(e.target.value)}
                      className="px-3 py-1.5 border border-slate-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                    />
                  </div>
                  <div className="flex items-center gap-2 text-xs">
                    <span className="font-semibold text-slate-600">To:</span>
                    <input
                      type="date"
                      value={toDate}
                      onChange={(e) => setToDate(e.target.value)}
                      className="px-3 py-1.5 border border-slate-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                    />
                  </div>
                  <button
                    onClick={loadReconciliation}
                    className="px-4 py-1.5 bg-[#1976d2] hover:bg-[#436181] text-white rounded-lg text-xs font-semibold shadow-sm transition"
                  >
                    Compare Dates
                  </button>
                </div>

                <div className="text-xs text-slate-500">
                  Daily School Fees vs Uploaded Bank Credits
                </div>
              </div>

              {/* KPI Cards */}
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                  <div>
                    <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                      Bank Transactions Total
                    </span>
                    <h3 className="text-xl font-bold text-slate-900 mt-1">
                      ₹{bankTotal.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                    </h3>
                    <p className="text-[11px] text-slate-400 mt-0.5">{bankTransactions.length} transaction(s)</p>
                  </div>
                  <div className="w-10 h-10 rounded-xl bg-[#e3f2fd] text-[#1976d2] flex items-center justify-center font-bold">
                    <FiCreditCard size={20} />
                  </div>
                </div>

                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                  <div>
                    <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                      Fees Ledger Total
                    </span>
                    <h3 className="text-xl font-bold text-slate-900 mt-1">
                      ₹{dailyTotal.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                    </h3>
                    <p className="text-[11px] text-slate-400 mt-0.5">{dailyFees.length} receipt(s)</p>
                  </div>
                  <div className="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <FiDollarSign size={20} />
                  </div>
                </div>

                <div className={`p-4 rounded-xl border shadow-sm flex items-center justify-between ${
                  Math.abs(difference) < 0.01
                    ? 'bg-emerald-50 border-emerald-200 text-emerald-900'
                    : 'bg-rose-50 border-rose-200 text-rose-900'
                }`}>
                  <div>
                    <span className="text-xs font-semibold uppercase tracking-wider">
                      Reconciliation Difference
                    </span>
                    <h3 className="text-xl font-bold mt-1">
                      ₹{difference.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                    </h3>
                    <p className="text-[11px] mt-0.5">
                      {Math.abs(difference) < 0.01 ? '✓ Perfectly Balanced' : '⚠️ Discrepancy Found'}
                    </p>
                  </div>
                  <div className="w-10 h-10 rounded-xl bg-white/60 flex items-center justify-center font-bold">
                    {Math.abs(difference) < 0.01 ? (
                      <FiCheckCircle size={20} className="text-emerald-600" />
                    ) : (
                      <FiAlertCircle size={20} className="text-rose-600" />
                    )}
                  </div>
                </div>
              </div>

              {/* Side-by-Side Tables */}
              <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Bank Transactions Column */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                  <div className="p-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <h3 className="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                      <FiCreditCard className="text-[#1976d2]" /> Bank Statement Transactions
                    </h3>
                    <span className="text-xs font-bold text-slate-700">
                      ₹{bankTotal.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                    </span>
                  </div>

                  {bankTransactions.length > 0 ? (
                    <div className="overflow-x-auto max-h-[420px] overflow-y-auto">
                      <table className="w-full text-left text-xs border-collapse">
                        <thead className="bg-slate-50 text-slate-500 text-[11px] uppercase sticky top-0 border-b border-slate-200">
                          <tr>
                            <th className="p-2.5">Date</th>
                            <th className="p-2.5">Txn ID</th>
                            <th className="p-2.5">Description</th>
                            <th className="p-2.5 text-right">Amount</th>
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 text-slate-700">
                          {bankTransactions.map((tx, idx) => (
                            <tr key={tx.id || idx} className="hover:bg-slate-50/80 transition">
                              <td className="p-2.5 whitespace-nowrap text-slate-500 font-mono">
                                {tx.value_date ? tx.value_date.split('T')[0] : '-'}
                              </td>
                              <td className="p-2.5 font-mono font-semibold text-slate-900">
                                {tx.transaction_id}
                              </td>
                              <td className="p-2.5 max-w-[200px] truncate" title={tx.description}>
                                {tx.description}
                              </td>
                              <td className="p-2.5 text-right font-bold text-[#1976d2] whitespace-nowrap">
                                ₹{parseFloat(tx.transaction_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                              </td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  ) : (
                    <div className="p-8 text-center text-slate-400 text-xs">
                      No bank transactions found in this date range.
                    </div>
                  )}
                </div>

                {/* Fees Receipts Column */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                  <div className="p-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                    <h3 className="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                      <FiDollarSign className="text-emerald-600" /> System Fees Receipts
                    </h3>
                    <span className="text-xs font-bold text-slate-700">
                      ₹{dailyTotal.toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                    </span>
                  </div>

                  {dailyFees.length > 0 ? (
                    <div className="overflow-x-auto max-h-[420px] overflow-y-auto">
                      <table className="w-full text-left text-xs border-collapse">
                        <thead className="bg-slate-50 text-slate-500 text-[11px] uppercase sticky top-0 border-b border-slate-200">
                          <tr>
                            <th className="p-2.5">Date</th>
                            <th className="p-2.5">Receipt #</th>
                            <th className="p-2.5">Student</th>
                            <th className="p-2.5 text-right">Amount</th>
                            <th className="p-2.5 text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 text-slate-700">
                          {dailyFees.map((df, idx) => (
                            <tr key={df.sprfees_pk || idx} className="hover:bg-slate-50/80 transition">
                              <td className="p-2.5 whitespace-nowrap text-slate-500 font-mono">
                                {df.sprfees_feesdate ? df.sprfees_feesdate.split('T')[0] : '-'}
                              </td>
                              <td className="p-2.5 font-mono font-semibold text-slate-900">
                                #{df.sprfees_pk}
                              </td>
                              <td className="p-2.5">
                                <div className="font-semibold text-slate-800">{df.student_name || 'Student'}</div>
                                <div className="text-[10px] text-slate-400 font-mono">ID: {df.SPRIDEN_student_id}</div>
                              </td>
                              <td className="p-2.5 text-right font-bold text-emerald-700 whitespace-nowrap">
                                ₹{parseFloat(df.sprfees_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                              </td>
                              <td className="p-2.5 text-center whitespace-nowrap">
                                <button
                                  onClick={() => {
                                    setEditModalData(df);
                                    setNewDate(df.sprfees_feesdate ? df.sprfees_feesdate.split('T')[0] : today);
                                  }}
                                  className="p-1.5 rounded-lg text-slate-500 hover:text-[#1976d2] hover:bg-slate-100 transition"
                                  title="Adjust payment date"
                                >
                                  <FiEdit2 size={13} />
                                </button>
                              </td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  ) : (
                    <div className="p-8 text-center text-slate-400 text-xs">
                      No fee receipts recorded in this date range.
                    </div>
                  )}
                </div>
              </div>
            </div>
          )}

          {/* ═════════════════════════════════════════════════════════════════
              TAB 2: UPLOAD BANK STATEMENT (.XLSX / .CSV)
             ═════════════════════════════════════════════════════════════════ */}
          {activeTab === 'upload' && (
            <div className="space-y-6 max-w-3xl mx-auto">
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <div className="text-center mb-6">
                  <div className="w-16 h-16 rounded-2xl bg-[#e3f2fd] text-[#1976d2] flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <FiUploadCloud size={30} />
                  </div>
                  <h3 className="text-lg font-bold text-slate-900">Upload Bank Statement</h3>
                  <p className="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                    Upload official bank statement spreadsheet (.xlsx, .xls, .csv) exported from HDFC, ICICI, Axis, or SBI internet banking.
                  </p>
                </div>

                {uploadError && (
                  <div className="p-4 mb-5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                    <FiAlertCircle size={16} />
                    <span>{uploadError}</span>
                  </div>
                )}

                {uploadResult && (
                  <div className="p-5 mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs space-y-2">
                    <div className="flex items-center gap-2 font-bold text-sm text-emerald-800">
                      <FiCheckCircle size={18} />
                      <span>Upload Completed Successfully!</span>
                    </div>
                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2">
                      <div className="bg-white/80 p-2.5 rounded-lg border border-emerald-100">
                        <span className="text-slate-500 block">Total Read</span>
                        <span className="font-bold text-base text-slate-800">{uploadResult.total_read}</span>
                      </div>
                      <div className="bg-white/80 p-2.5 rounded-lg border border-emerald-100">
                        <span className="text-emerald-600 block">Inserted</span>
                        <span className="font-bold text-base text-emerald-700">{uploadResult.inserted}</span>
                      </div>
                      <div className="bg-white/80 p-2.5 rounded-lg border border-emerald-100">
                        <span className="text-amber-600 block">Duplicates</span>
                        <span className="font-bold text-base text-amber-700">{uploadResult.duplicates}</span>
                      </div>
                      <div className="bg-white/80 p-2.5 rounded-lg border border-emerald-100">
                        <span className="text-rose-600 block">Failed</span>
                        <span className="font-bold text-base text-rose-700">{uploadResult.failed}</span>
                      </div>
                    </div>
                    <p className="text-[11px] text-emerald-700 pt-1 font-mono">
                      Batch ID: {uploadResult.group_id}
                    </p>
                  </div>
                )}

                <form onSubmit={handleFileUpload} className="space-y-4">
                  <div className="border-2 border-dashed border-slate-300 hover:border-[#1976d2] rounded-2xl p-8 text-center transition bg-slate-50/50 cursor-pointer relative">
                    <input
                      type="file"
                      accept=".xlsx,.xls,.csv"
                      onChange={(e) => {
                        if (e.target.files?.[0]) setSelectedFile(e.target.files[0]);
                      }}
                      className="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                    />
                    <FiFileText size={32} className="mx-auto text-slate-400 mb-2" />
                    {selectedFile ? (
                      <div>
                        <p className="text-sm font-bold text-[#1976d2]">{selectedFile.name}</p>
                        <p className="text-xs text-slate-400 mt-1">
                          {(selectedFile.size / 1024).toFixed(1)} KB — Click to change file
                        </p>
                      </div>
                    ) : (
                      <div>
                        <p className="text-sm font-semibold text-slate-700">Click or drag & drop statement file here</p>
                        <p className="text-xs text-slate-400 mt-1">Accepts Excel (.xlsx, .xls) and CSV up to 15MB</p>
                      </div>
                    )}
                  </div>

                  <div className="bg-slate-50 p-4 rounded-xl border border-slate-200/80 text-xs text-slate-600 space-y-1">
                    <p className="font-bold text-slate-700">Auto-detected Columns:</p>
                    <p>• <strong>Transaction ID / Ref No / UTR</strong> (Unique payment transaction identifier)</p>
                    <p>• <strong>Value Date & Posted Date</strong> (Date of credit)</p>
                    <p>• <strong>Description / Particulars</strong> (Narration, student name or roll reference)</p>
                    <p>• <strong>Transaction Amount (INR)</strong> (Net credit value)</p>
                  </div>

                  <button
                    type="submit"
                    disabled={uploading || !selectedFile}
                    className="w-full py-3 rounded-xl bg-[#1976d2] hover:bg-[#1565c0] text-white font-semibold text-sm transition shadow-md disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer"
                  >
                    {uploading ? (
                      <>
                        <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                        <span>Processing Spreadsheet...</span>
                      </>
                    ) : (
                      <>
                        <FiUploadCloud size={18} />
                        <span>Upload & Reconcile Statement</span>
                      </>
                    )}
                  </button>
                </form>
              </div>
            </div>
          )}

          {/* ═════════════════════════════════════════════════════════════════
              TAB 3: BANK TRANSACTIONS LEDGER & AUDIT HISTORY
             ═════════════════════════════════════════════════════════════════ */}
          {activeTab === 'ledger' && (
            <div className="space-y-6">
              {/* Filter Bar */}
              <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap items-center justify-between gap-3">
                <div className="flex flex-wrap items-center gap-2">
                  <div className="relative">
                    <FiSearch className="absolute left-3 top-2.5 text-slate-400" size={13} />
                    <input
                      type="text"
                      placeholder="Search Transaction ID or description..."
                      value={txnSearch}
                      onChange={(e) => setTxnSearch(e.target.value)}
                      className="pl-8 pr-3 py-1.5 border border-slate-300 rounded-lg text-xs w-64 focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                    />
                  </div>
                  <div className="flex items-center gap-1.5 text-xs text-slate-500">
                    <span>From:</span>
                    <input
                      type="date"
                      value={ledgerFromDate}
                      onChange={(e) => setLedgerFromDate(e.target.value)}
                      className="px-2 py-1.5 border border-slate-300 rounded-lg text-xs"
                    />
                  </div>
                  <div className="flex items-center gap-1.5 text-xs text-slate-500">
                    <span>To:</span>
                    <input
                      type="date"
                      value={ledgerToDate}
                      onChange={(e) => setLedgerToDate(e.target.value)}
                      className="px-2 py-1.5 border border-slate-300 rounded-lg text-xs"
                    />
                  </div>
                  <button
                    onClick={loadBankLedger}
                    className="px-3.5 py-1.5 bg-[#1976d2] text-white rounded-lg text-xs font-semibold hover:bg-[#436181] transition"
                  >
                    Filter
                  </button>
                </div>

                <div className="flex items-center gap-3">
                  <div className="text-right">
                    <span className="text-[11px] text-slate-400 block font-medium">Total Transactions</span>
                    <span className="text-xs font-bold text-slate-800">
                      {ledgerTotalCount} records (₹{ledgerTotalAmount.toLocaleString('en-IN')})
                    </span>
                  </div>
                  <button
                    onClick={exportLedgerToCSV}
                    disabled={allTransactions.length === 0}
                    className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition disabled:opacity-50"
                  >
                    <FiDownload size={13} />
                    <span>Export CSV</span>
                  </button>
                </div>
              </div>

              {/* Ledger Table */}
              <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                {ledgerLoading ? (
                  <div className="p-12 text-center text-slate-400 text-xs">
                    <div className="w-8 h-8 border-3 border-[#1976d2]/30 border-t-[#1976d2] rounded-full animate-spin mx-auto mb-2" />
                    <span>Loading bank transaction ledger...</span>
                  </div>
                ) : allTransactions.length > 0 ? (
                  <div className="overflow-x-auto">
                    <table className="w-full text-left text-xs border-collapse">
                      <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                        <tr>
                          <th className="p-3">#</th>
                          <th className="p-3">Transaction ID</th>
                          <th className="p-3">Value Date</th>
                          <th className="p-3">Txn Posted</th>
                          <th className="p-3">Cheque No</th>
                          <th className="p-3">Description / Narration</th>
                          <th className="p-3">Mode</th>
                          <th className="p-3 text-right">Amount (INR)</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100 text-slate-700">
                        {allTransactions.map((tx, idx) => (
                          <tr key={tx.id || idx} className="hover:bg-slate-50/80 transition">
                            <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                            <td className="p-3 font-mono font-bold text-slate-900">{tx.transaction_id}</td>
                            <td className="p-3 whitespace-nowrap text-slate-500 font-mono">
                              {tx.value_date ? tx.value_date.split('T')[0] : '-'}
                            </td>
                            <td className="p-3 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                              {tx.txn_posted_date ? tx.txn_posted_date.replace('T', ' ').slice(0, 19) : '-'}
                            </td>
                            <td className="p-3 font-mono text-slate-500">{tx.cheque_no || '-'}</td>
                            <td className="p-3 max-w-sm truncate" title={tx.description}>
                              {tx.description}
                            </td>
                            <td className="p-3">
                              <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-[#e3f2fd] text-[#1976d2]">
                                {tx.mode || 'CR'}
                              </span>
                            </td>
                            <td className="p-3 text-right font-bold text-emerald-700 whitespace-nowrap">
                              ₹{parseFloat(tx.transaction_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                ) : (
                  <div className="p-12 text-center text-slate-400 text-xs">
                    No transactions found matching criteria.
                  </div>
                )}
              </div>
            </div>
          )}

          {/* ═════════════════════════════════════════════════════════════════
              TAB 4: UPLOAD BATCHES HISTORY
             ═════════════════════════════════════════════════════════════════ */}
          {activeTab === 'batches' && (
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 className="font-bold text-sm text-slate-900">Past Statement Upload Batches</h3>
                <span className="text-xs text-slate-500">{batches.length} batch(es) logged</span>
              </div>

              {loadingBatches ? (
                <div className="p-12 text-center text-slate-400 text-xs">
                  <div className="w-8 h-8 border-3 border-[#1976d2]/30 border-t-[#1976d2] rounded-full animate-spin mx-auto mb-2" />
                  <span>Loading batch history...</span>
                </div>
              ) : batches.length > 0 ? (
                <div className="overflow-x-auto">
                  <table className="w-full text-left text-xs border-collapse">
                    <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                      <tr>
                        <th className="p-3">#</th>
                        <th className="p-3">Batch ID (UUID)</th>
                        <th className="p-3">Upload Timestamp</th>
                        <th className="p-3">Record Count</th>
                        <th className="p-3">Date Span</th>
                        <th className="p-3 text-right">Batch Total</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 text-slate-700">
                      {batches.map((b, idx) => (
                        <tr key={b.group_id || idx} className="hover:bg-slate-50/80 transition">
                          <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                          <td className="p-3 font-mono font-bold text-[#1976d2]">{b.group_id}</td>
                          <td className="p-3 whitespace-nowrap text-slate-500">
                            {b.uploaded_at ? new Date(b.uploaded_at).toLocaleString('en-IN') : '-'}
                          </td>
                          <td className="p-3 font-semibold text-slate-900">{b.record_count} items</td>
                          <td className="p-3 text-slate-500 font-mono">
                            {b.min_date ? b.min_date.split('T')[0] : ''} &rarr; {b.max_date ? b.max_date.split('T')[0] : ''}
                          </td>
                          <td className="p-3 text-right font-bold text-emerald-700 whitespace-nowrap">
                            ₹{parseFloat(b.total_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 })}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <div className="p-12 text-center text-slate-400 text-xs">
                  No statement upload history found.
                </div>
              )}
            </div>
          )}
        </main>
      </div>

      {/* Date Adjustment Modal */}
      {editModalData && (
        <div className="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-sm w-full p-6 animate-scaleIn">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
              <h3 className="font-bold text-sm text-slate-900">Adjust Receipt Date</h3>
              <button
                onClick={() => setEditModalData(null)}
                className="text-slate-400 hover:text-slate-600 text-lg font-bold"
              >
                &times;
              </button>
            </div>

            <form onSubmit={handleUpdateDateSubmit} className="space-y-4 text-xs">
              <div>
                <span className="text-slate-500 block">Receipt: #{editModalData.sprfees_pk}</span>
                <span className="text-slate-700 font-bold block mt-0.5">{editModalData.student_name}</span>
                <span className="text-emerald-700 font-bold block mt-0.5">₹{editModalData.sprfees_amount}</span>
              </div>

              <div>
                <label className="block text-slate-600 font-semibold mb-1">New Payment Date</label>
                <input
                  type="date"
                  value={newDate}
                  onChange={(e) => setNewDate(e.target.value)}
                  className="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                  required
                />
              </div>

              <div className="flex justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setEditModalData(null)}
                  className="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={updatingDate}
                  className="px-4 py-1.5 rounded-lg bg-[#1976d2] hover:bg-[#1565c0] text-white font-semibold shadow-sm transition disabled:opacity-50"
                >
                  {updatingDate ? 'Saving...' : 'Update Date'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
