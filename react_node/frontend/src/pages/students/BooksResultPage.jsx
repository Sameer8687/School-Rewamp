import React, { useState, useEffect, useMemo } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import booksResultService from '../../services/booksResultService.js';
import { fetchStudentOptions, fetchStudents } from '../../services/studentService.js';
import {
  FiBook,
  FiAward,
  FiPlus,
  FiSearch,
  FiTrash2,
  FiRefreshCw,
  FiDollarSign,
  FiCheckCircle,
  FiXCircle,
  FiUserCheck,
  FiX,
} from 'react-icons/fi';

export default function BooksResultPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [records, setRecords] = useState([]);

  // Filters
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('all');
  const [standards, setStandards] = useState([]);
  const [filterStd, setFilterStd] = useState('all');
  const [searchTerm, setSearchTerm] = useState('');

  // Helper to safely extract standard string
  const getStdName = (s) => (typeof s === 'string' ? s : s?.stvfees_class || s?.standard || s?.STVCLAS_standard || s?.name || '');

  // Modal
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [studentSearch, setStudentSearch] = useState('');
  const [searchedStudents, setSearchedStudents] = useState([]);
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [formStandard, setFormStandard] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [formError, setFormError] = useState('');

  const [formData, setFormData] = useState({
    STVBOOK_result: 'Yes',
    STVBOOK_books: 'Yes',
    STVBOOK_teacher_name: '',
    STVBOOK_paid_amonut: '0',
    STVBOOK_bal_amount: '0',
    STVBOOK_paid_date: new Date().toISOString().slice(0, 10),
    STVBOOK_due_date: '',
    STVBOOK_details: '',
    STVBOOK_area: '',
  });

  const loadRecords = async (yr = selectedYear, std = filterStd, q = searchTerm) => {
    setLoading(true);
    try {
      const res = await booksResultService.getBooksResult({
        academic_year: yr !== 'all' ? yr : undefined,
        standard: std !== 'all' ? std : undefined,
        search: q || undefined,
      });
      if (res.data?.success) {
        setRecords(res.data.records || res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load books and results:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        const stdList = res.classes || res.data?.classes || res.data?.options?.classes || [];
        const yrList = res.academicYears || res.data?.academicYears || res.data?.options?.academicYears || [];
        setStandards(stdList);
        setAcademicYears(yrList);
      } catch (err) {
        console.error('Failed to load standards & years:', err);
      }
    }
    loadOptions();
  }, []);

  useEffect(() => {
    loadRecords(selectedYear, filterStd, searchTerm);
  }, [selectedYear, filterStd]);

  const handleStudentSearch = async (query) => {
    setStudentSearch(query);
    setFormError('');

    if (!query || query.trim().length < 2) {
      setSearchedStudents([]);
      return;
    }

    try {
      const res = await fetchStudents({ search: query.trim(), limit: 10 });
      const studentsList =
        res.data?.data?.students ||
        res.data?.students ||
        (Array.isArray(res.data?.data) ? res.data.data : []);

      setSearchedStudents(studentsList);
    } catch (err) {
      console.error('Failed to search students:', err);
      setSearchedStudents([]);
    }
  };

  const handleSelectStudent = (stu) => {
    setSelectedStudent(stu);
    setSearchedStudents([]);
    const code = stu.SPRIDEN_student_id || stu.student_code || stu.student_id;
    const name = stu.name || stu.student_name || [stu.SPRIDEN_surname, stu.SPRIDEN_student_name].filter(Boolean).join(' ');
    setStudentSearch(`${name} (${code})`);

    const stuStd = stu.current_standard || stu.STVCLAS_standard || stu.standard || stu.SPRIDEN_admission_class || (standards.length > 0 ? getStdName(standards[0]) : 'I');
    setFormStandard(stuStd);

    // If an existing record exists in loaded records, prefill
    const existing = records.find((r) => String(r.STVBOOK_student_id) === String(code));
    if (existing) {
      setFormData({
        STVBOOK_result: existing.STVBOOK_result || 'Yes',
        STVBOOK_books: existing.STVBOOK_books || 'Yes',
        STVBOOK_teacher_name: existing.STVBOOK_teacher_name || '',
        STVBOOK_paid_amonut: String(existing.STVBOOK_paid_amonut ?? '0'),
        STVBOOK_bal_amount: String(existing.STVBOOK_bal_amount ?? '0'),
        STVBOOK_paid_date: existing.STVBOOK_paid_date ? String(existing.STVBOOK_paid_date).slice(0, 10) : new Date().toISOString().slice(0, 10),
        STVBOOK_due_date: existing.STVBOOK_due_date ? String(existing.STVBOOK_due_date).slice(0, 10) : '',
        STVBOOK_details: existing.STVBOOK_details || '',
        STVBOOK_area: existing.STVBOOK_area || '',
      });
      if (existing.STVBOOK_standard) {
        setFormStandard(existing.STVBOOK_standard);
      }
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!selectedStudent) {
      setFormError('Please select a student first.');
      return;
    }
    setSubmitting(true);
    setFormError('');

    try {
      const code = selectedStudent.SPRIDEN_student_id || selectedStudent.student_code || selectedStudent.student_id;
      const std = formStandard || selectedStudent.current_standard || selectedStudent.STVCLAS_standard || selectedStudent.standard || 'I';

      const payload = {
        student_id: code,
        standard: std,
        result: formData.STVBOOK_result,
        books: formData.STVBOOK_books,
        teacher_name: formData.STVBOOK_teacher_name,
        paid_amount: formData.STVBOOK_paid_amonut || '0',
        bal_amount: formData.STVBOOK_bal_amount || '0',
        paid_date: formData.STVBOOK_paid_date,
        due_date: formData.STVBOOK_due_date || null,
        details: formData.STVBOOK_details,
        area: formData.STVBOOK_area,
      };

      const res = await booksResultService.createBooksResult(payload);
      if (res.data?.success) {
        setIsModalOpen(false);
        setSelectedStudent(null);
        setStudentSearch('');
        setFormData({
          STVBOOK_result: 'Yes',
          STVBOOK_books: 'Yes',
          STVBOOK_teacher_name: '',
          STVBOOK_paid_amonut: '0',
          STVBOOK_bal_amount: '0',
          STVBOOK_paid_date: new Date().toISOString().slice(0, 10),
          STVBOOK_due_date: '',
          STVBOOK_details: '',
          STVBOOK_area: '',
        });
        loadRecords(selectedYear, filterStd, searchTerm);
      } else {
        setFormError(res.data?.message || 'Failed to save record.');
      }
    } catch (err) {
      setFormError(err.response?.data?.message || 'Error occurred while saving record.');
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this distribution record?')) return;
    try {
      const res = await booksResultService.deleteBooksResult(id);
      if (res.data?.success) {
        loadRecords();
      }
    } catch (err) {
      alert('Failed to delete distribution record');
    }
  };

  const stats = useMemo(() => {
    const total = records.length;
    const resultsGiven = records.filter(
      (r) => r.STVBOOK_result === 'Yes' || r.STVBOOK_result === 'Given'
    ).length;
    const booksGiven = records.filter(
      (r) => r.STVBOOK_books === 'Yes' || r.STVBOOK_books === 'Given'
    ).length;
    const totalBalance = records.reduce(
      (sum, r) => sum + (parseFloat(r.STVBOOK_bal_amount) || 0),
      0
    );
    return { total, resultsGiven, booksGiven, totalBalance };
  }, [records]);

  const filteredRecords = records.filter((r) => {
    if (!searchTerm.trim()) return true;
    const q = searchTerm.toLowerCase();
    return (
      (r.student_name || '').toLowerCase().includes(q) ||
      String(r.STVBOOK_student_id || '').toLowerCase().includes(q) ||
      (r.STVBOOK_teacher_name || '').toLowerCase().includes(q)
    );
  });

  return (
    <div className="flex h-screen bg-[#F7F7F5] font-sans text-[#202020] antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <div className="flex items-center gap-2.5">
                <span className="p-2.5 bg-[#e3f2fd] text-[#1976d2] rounded-xl shadow-sm">
                  <FiAward className="w-5 h-5" />
                </span>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight text-[#202020]">
                  Books & Results Distribution
                </h1>
              </div>
              <p className="text-xs sm:text-sm text-[#73736F] mt-1">
                Manage student annual report card handover, textbook kits distribution, and associated dues
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={loadRecords}
                className="p-2.5 rounded-xl border border-[#E7E7E4] bg-white hover:bg-[#F5F5F3] text-[#73736F] hover:text-[#202020] transition cursor-pointer shadow-sm"
                title="Refresh"
              >
                <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin text-[#1976d2]' : ''}`} />
              </button>
              <button
                onClick={() => setIsModalOpen(true)}
                className="flex items-center gap-2 px-4 py-2.5 bg-[#1976d2] hover:bg-[#1565c0] active:bg-[#0d47a1] text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
              >
                <FiPlus className="w-4 h-4" />
                <span>Issue Books / Result</span>
              </button>
            </div>
          </div>

          {/* Metrics */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {/* Total Dispatched */}
            <div className="bg-white p-4.5 rounded-2xl border border-[#E7E7E4] shadow-sm flex items-center justify-between">
              <div>
                <p className="text-[11px] font-semibold text-[#73736F] uppercase tracking-wider">
                  Total Dispatched
                </p>
                <p className="text-2xl font-bold mt-1 text-[#202020]">
                  {stats.total}
                </p>
              </div>
              <div className="p-3 bg-[#e3f2fd] text-[#1976d2] rounded-xl">
                <FiAward className="w-6 h-6" />
              </div>
            </div>

            {/* Results Handed */}
            <div className="bg-white p-4.5 rounded-2xl border border-[#E7E7E4] shadow-sm flex items-center justify-between">
              <div>
                <p className="text-[11px] font-semibold text-[#73736F] uppercase tracking-wider">
                  Results Handed
                </p>
                <p className="text-2xl font-bold mt-1 text-[#2e7d32]">
                  {stats.resultsGiven}
                </p>
              </div>
              <div className="p-3 bg-[#e8f5e9] text-[#2e7d32] rounded-xl">
                <FiCheckCircle className="w-6 h-6" />
              </div>
            </div>

            {/* Books Delivered */}
            <div className="bg-white p-4.5 rounded-2xl border border-[#E7E7E4] shadow-sm flex items-center justify-between">
              <div>
                <p className="text-[11px] font-semibold text-[#73736F] uppercase tracking-wider">
                  Books Delivered
                </p>
                <p className="text-2xl font-bold mt-1 text-[#1976d2]">
                  {stats.booksGiven}
                </p>
              </div>
              <div className="p-3 bg-[#e3f2fd] text-[#1976d2] rounded-xl">
                <FiBook className="w-6 h-6" />
              </div>
            </div>

            {/* Balance Pending */}
            <div className="bg-white p-4.5 rounded-2xl border border-[#E7E7E4] shadow-sm flex items-center justify-between">
              <div>
                <p className="text-[11px] font-semibold text-[#73736F] uppercase tracking-wider">
                  Balance Pending
                </p>
                <p className="text-2xl font-bold mt-1 text-[#c62828]">
                  ₹{stats.totalBalance.toLocaleString('en-IN')}
                </p>
              </div>
              <div className="p-3 bg-[#ffebee] text-[#c62828] rounded-xl">
                <FiDollarSign className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Filter Bar */}
          <div className="bg-white p-4 rounded-2xl border border-[#E7E7E4] shadow-sm flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <div className="flex flex-wrap items-center gap-3">
              <div className="flex items-center gap-1.5">
                <span className="text-xs text-[#73736F] font-semibold">Year:</span>
                <select
                  value={selectedYear}
                  onChange={(e) => setSelectedYear(e.target.value)}
                  className="px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
                >
                  <option value="all">All Academic Years</option>
                  {academicYears.map((y) => (
                    <option key={y.stvacdm_id || y.id} value={y.stvacdm_id || y.id}>
                      {y.stvacdm_year || y.year}
                    </option>
                  ))}
                </select>
              </div>

              <div className="flex items-center gap-1.5">
                <span className="text-xs text-[#73736F] font-semibold">Standard:</span>
                <select
                  value={filterStd}
                  onChange={(e) => setFilterStd(e.target.value)}
                  className="px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
                >
                  <option value="all">All Standards</option>
                  {standards.map((s, idx) => {
                    const val = getStdName(s);
                    return (
                      <option key={idx} value={val}>
                        {val}
                      </option>
                    );
                  })}
                </select>
              </div>

              {(filterStd !== 'all' || selectedYear !== 'all') && (
                <button
                  onClick={() => {
                    setFilterStd('all');
                    setSelectedYear('all');
                  }}
                  className="text-xs font-semibold text-[#1976d2] hover:underline cursor-pointer"
                >
                  Reset Filters
                </button>
              )}
            </div>

            {/* Search */}
            <div className="relative w-full md:w-80">
              <FiSearch className="absolute left-3.5 top-3 text-[#73736F] w-4 h-4" />
              <input
                type="text"
                placeholder="Search student or ID..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full pl-10 pr-4 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
              />
            </div>
          </div>

          {/* Table */}
          <div className="bg-white rounded-2xl border border-[#E7E7E4] shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-[#F7F7F5] border-b border-[#E7E7E4] text-[11px] font-bold text-[#73736F] uppercase tracking-wider">
                    <th className="py-3 px-4">ID</th>
                    <th className="py-3 px-4">Student</th>
                    <th className="py-3 px-4">Standard</th>
                    <th className="py-3 px-4 text-center">Result</th>
                    <th className="py-3 px-4 text-center">Books</th>
                    <th className="py-3 px-4">Teacher</th>
                    <th className="py-3 px-4 text-right">Paid</th>
                    <th className="py-3 px-4 text-right">Balance</th>
                    <th className="py-3 px-4">Handover Date</th>
                    <th className="py-3 px-4">Due Date</th>
                    <th className="py-3 px-4 text-center">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-[#E7E7E4] font-medium text-[#202020]">
                  {loading ? (
                    <tr>
                      <td colSpan="11" className="py-12 text-center text-[#73736F]">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                        Loading distribution records...
                      </td>
                    </tr>
                  ) : filteredRecords.length === 0 ? (
                    <tr>
                      <td colSpan="11" className="py-12 text-center text-[#73736F]">
                        No books & result distribution records found.
                      </td>
                    </tr>
                  ) : (
                    filteredRecords.map((r) => (
                      <tr key={r.id} className="hover:bg-[#F5F5F3] transition-colors">
                        <td className="py-3 px-4 font-mono font-bold text-[#1976d2]">
                          {r.STVBOOK_student_id}
                        </td>
                        <td className="py-3 px-4">
                          <div className="font-semibold text-[#202020]">
                            {r.student_name || '—'}
                          </div>
                          {r.STVBOOK_area && (
                            <div className="text-[10px] text-[#73736F]">{r.STVBOOK_area}</div>
                          )}
                        </td>
                        <td className="py-3 px-4">
                          <span className="px-2 py-0.5 rounded text-[11px] bg-[#F1F1EF] text-[#202020] font-medium">
                            {r.STVBOOK_standard || '—'}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center">
                          {r.STVBOOK_result === 'Yes' || r.STVBOOK_result === 'Given' ? (
                            <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e8f5e9] text-[#2e7d32] border border-[#c8e6c9]">
                              Given
                            </span>
                          ) : (
                            <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F1EF] text-[#73736F]">
                              No
                            </span>
                          )}
                        </td>
                        <td className="py-3 px-4 text-center">
                          {r.STVBOOK_books === 'Yes' || r.STVBOOK_books === 'Given' ? (
                            <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#e3f2fd] text-[#1976d2] border border-[#bbdefb]">
                              Given
                            </span>
                          ) : (
                            <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F1EF] text-[#73736F]">
                              No
                            </span>
                          )}
                        </td>
                        <td className="py-3 px-4 text-[#73736F]">
                          {r.STVBOOK_teacher_name || '—'}
                        </td>
                        <td className="py-3 px-4 text-right font-semibold text-[#2e7d32] whitespace-nowrap">
                          ₹{parseFloat(r.STVBOOK_paid_amonut || 0).toLocaleString('en-IN')}
                        </td>
                        <td className="py-3 px-4 text-right font-semibold text-[#c62828] whitespace-nowrap">
                          ₹{parseFloat(r.STVBOOK_bal_amount || 0).toLocaleString('en-IN')}
                        </td>
                        <td className="py-3 px-4 whitespace-nowrap text-[#73736F]">
                          {r.STVBOOK_paid_date ? r.STVBOOK_paid_date.slice(0, 10) : '—'}
                        </td>
                        <td className="py-3 px-4 whitespace-nowrap text-[11px] font-semibold text-[#e65100]">
                          {r.STVBOOK_due_date ? r.STVBOOK_due_date.slice(0, 10) : '—'}
                        </td>
                        <td className="py-3 px-4 text-center">
                          <button
                            onClick={() => handleDelete(r.id)}
                            className="p-1.5 text-[#c62828] hover:bg-[#ffebee] rounded-lg transition cursor-pointer"
                            title="Delete"
                          >
                            <FiTrash2 className="w-3.5 h-3.5" />
                          </button>
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

      {/* Issue Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black/40 backdrop-blur-[1px] z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#E7E7E4] max-h-[90vh] overflow-y-auto">
            <div className="flex items-center justify-between pb-3 border-b border-[#E7E7E4]">
              <h3 className="font-bold text-base text-[#202020] flex items-center gap-2">
                <FiAward className="text-[#1976d2]" />
                <span>Issue Books & Result</span>
              </h3>
              <button
                onClick={() => setIsModalOpen(false)}
                className="text-[#73736F] hover:text-[#202020] cursor-pointer"
              >
                <FiX className="w-5 h-5" />
              </button>
            </div>

            {formError && (
              <div className="mt-3 p-3 bg-[#ffebee] border border-[#ffcdd2] text-[#c62828] rounded-xl text-xs font-semibold">
                {formError}
              </div>
            )}

            <form onSubmit={handleSubmit} className="mt-4 space-y-4">
              {/* Student Search */}
              <div className="relative">
                <label className="block text-xs font-semibold text-[#73736F] mb-1">
                  Search & Select Student <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  placeholder="Type student name or ID..."
                  value={studentSearch}
                  onChange={(e) => handleStudentSearch(e.target.value)}
                  className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
                />

                {searchedStudents.length > 0 && (
                  <div className="mt-1 max-h-48 overflow-y-auto bg-white border border-[#E7E7E4] rounded-xl shadow-lg z-20">
                    {searchedStudents.map((s) => (
                      <div
                        key={s.SPRIDEN_id || s.SPRIDEN_student_id}
                        onClick={() => handleSelectStudent(s)}
                        className="p-2.5 text-xs hover:bg-[#e3f2fd] cursor-pointer border-b border-[#E7E7E4] last:border-0 transition"
                      >
                        <div className="font-bold text-[#202020]">
                          {s.name || `${s.SPRIDEN_surname || ''} ${s.SPRIDEN_student_name || ''}`}
                        </div>
                        <div className="text-[11px] text-[#73736F]">
                          ID: {s.SPRIDEN_student_id} | Class:{' '}
                          {s.current_standard || s.STVCLAS_standard || 'N/A'}
                        </div>
                      </div>
                    ))}
                  </div>
                )}

                {selectedStudent && (
                  <div className="mt-2 p-3 bg-[#e3f2fd] rounded-xl border border-[#bbdefb] text-xs text-[#1976d2] flex justify-between items-center">
                    <div>
                      <span className="font-bold">Selected:</span>{' '}
                      {selectedStudent.name || selectedStudent.SPRIDEN_student_name}
                      <span className="ml-2 font-mono text-xs text-[#1565c0]">
                        ({selectedStudent.SPRIDEN_student_id})
                      </span>
                    </div>
                    <button
                      type="button"
                      onClick={() => {
                        setSelectedStudent(null);
                        setStudentSearch('');
                      }}
                      className="text-red-500 hover:text-red-700 font-bold ml-2 cursor-pointer"
                    >
                      Change
                    </button>
                  </div>
                )}
              </div>

              {/* Standard Selection */}
              <div>
                <label className="block text-xs font-semibold text-[#73736F] mb-1">
                  Class / Standard <span className="text-red-500">*</span>
                </label>
                <select
                  value={formStandard}
                  onChange={(e) => setFormStandard(e.target.value)}
                  className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                >
                  <option value="">Select Class</option>
                  {standards.map((s, idx) => {
                    const val = getStdName(s);
                    return (
                      <option key={idx} value={val}>
                        {val}
                      </option>
                    );
                  })}
                </select>
              </div>

              {/* Handover Toggles */}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Result Status
                  </label>
                  <select
                    value={formData.STVBOOK_result}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_result: e.target.value })
                    }
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  >
                    <option value="Yes">Given</option>
                    <option value="No">Not Given</option>
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Books Status
                  </label>
                  <select
                    value={formData.STVBOOK_books}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_books: e.target.value })
                    }
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  >
                    <option value="Yes">Given</option>
                    <option value="No">Not Given</option>
                  </select>
                </div>
              </div>

              {/* Teacher & Area */}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Teacher Name
                  </label>
                  <input
                    type="text"
                    value={formData.STVBOOK_teacher_name}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_teacher_name: e.target.value })
                    }
                    placeholder="Enter teacher name"
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">Area</label>
                  <input
                    type="text"
                    value={formData.STVBOOK_area}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_area: e.target.value })
                    }
                    placeholder="Area or locality"
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  />
                </div>
              </div>

              {/* Amounts */}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Paid Amount (₹)
                  </label>
                  <input
                    type="number"
                    value={formData.STVBOOK_paid_amonut}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_paid_amonut: e.target.value })
                    }
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Balance Amount (₹)
                  </label>
                  <input
                    type="number"
                    value={formData.STVBOOK_bal_amount}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_bal_amount: e.target.value })
                    }
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  />
                </div>
              </div>

              {/* Dates */}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Handover Date
                  </label>
                  <input
                    type="date"
                    value={formData.STVBOOK_paid_date}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_paid_date: e.target.value })
                    }
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Due Date
                  </label>
                  <input
                    type="date"
                    value={formData.STVBOOK_due_date}
                    onChange={(e) =>
                      setFormData({ ...formData, STVBOOK_due_date: e.target.value })
                    }
                    className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  />
                </div>
              </div>

              {/* Details / Notes */}
              <div>
                <label className="block text-xs font-semibold text-[#73736F] mb-1">
                  Notes / Details
                </label>
                <textarea
                  rows="2"
                  value={formData.STVBOOK_details}
                  onChange={(e) =>
                    setFormData({ ...formData, STVBOOK_details: e.target.value })
                  }
                  placeholder="Optional distribution notes"
                  className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                />
              </div>

              <div className="flex justify-end gap-3 pt-3 border-t border-[#E7E7E4]">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 border border-[#E7E7E4] text-[#73736F] hover:text-[#202020] rounded-xl text-xs sm:text-sm font-semibold hover:bg-[#F5F5F3] cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={submitting}
                  className="px-5 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm transition disabled:opacity-50 cursor-pointer"
                >
                  {submitting ? 'Saving...' : 'Save Record'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
