import React, { useState, useEffect, useMemo } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import visitingService from '../../services/visitingService.js';
import { fetchStudentOptions, fetchStudents } from '../../services/studentService.js';
import {
  FiUsers,
  FiPlus,
  FiCalendar,
  FiDollarSign,
  FiSearch,
  FiTrash2,
  FiRefreshCw,
  FiPhone,
  FiMessageSquare,
  FiBookOpen,
  FiCheckCircle,
} from 'react-icons/fi';

export default function VisitingManagementPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [records, setRecords] = useState([]);

  // Filter options
  const [standards, setStandards] = useState([]);
  const [filterStd, setFilterStd] = useState('all');
  const [fromDate, setFromDate] = useState('');
  const [toDate, setToDate] = useState('');
  const [searchTerm, setSearchTerm] = useState('');

  // Add Visit Modal
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [studentSearch, setStudentSearch] = useState('');
  const [searchedStudents, setSearchedStudents] = useState([]);
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [submitting, setSubmitting] = useState(false);
  const [formError, setFormError] = useState('');

  const [formData, setFormData] = useState({
    visiting_student_fees_paid: '',
    visiting_date: new Date().toISOString().slice(0, 10),
    visiting_fees_status_of: 'General Inquiry / Fees Counter',
    visiting_fees_due_date: '',
    visiting_book_fees: '0',
  });

  const loadVisitingRecords = async () => {
    setLoading(true);
    try {
      const res = await visitingService.getVisiting({
        standard: filterStd,
        fromDate,
        toDate,
        search: searchTerm,
      });
      if (res.data?.success) {
        setRecords(res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load visiting records:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          setStandards(res.data.options.classes || []);
        }
      } catch (err) {
        console.error('Failed to load standards:', err);
      }
    }
    loadOptions();
  }, []);

  useEffect(() => {
    loadVisitingRecords();
  }, [filterStd, fromDate, toDate]);

  // Search students for quick modal selection
  const handleStudentSearch = async (query) => {
    setStudentSearch(query);
    if (!query || query.trim().length < 2) {
      setSearchedStudents([]);
      return;
    }
    try {
      const res = await fetchStudents({ search: query, limit: 10 });
      if (res.data?.success) {
        setSearchedStudents(res.data.students || []);
      }
    } catch (err) {
      console.error('Failed to search students:', err);
    }
  };

  const handleSelectStudent = (stu) => {
    setSelectedStudent(stu);
    setSearchedStudents([]);
    setStudentSearch(`${stu.name || stu.SPRIDEN_student_name || ''} (${stu.SPRIDEN_student_id})`);
  };

  const handleSubmitVisit = async (e) => {
    e.preventDefault();
    if (!selectedStudent) {
      setFormError('Please select a student first.');
      return;
    }
    setSubmitting(true);
    setFormError('');

    try {
      const payload = {
        student_id: selectedStudent.id,
        student_class: selectedStudent.current_standard || selectedStudent.STVCLAS_standard || '',
        fees_paid: formData.visiting_student_fees_paid || '0',
        date: formData.visiting_date,
        fees_status_of: formData.visiting_fees_status_of,
        due_date: formData.visiting_fees_due_date || null,
        book_fees: formData.visiting_book_fees || '0',
      };

      const res = await visitingService.createVisiting(payload);
      if (res.data?.success) {
        setIsModalOpen(false);
        setSelectedStudent(null);
        setStudentSearch('');
        setFormData({
          visiting_student_fees_paid: '',
          visiting_date: new Date().toISOString().slice(0, 10),
          visiting_fees_status_of: 'General Inquiry / Fees Counter',
          visiting_fees_due_date: '',
          visiting_book_fees: '0',
        });
        loadVisitingRecords();
      } else {
        setFormError(res.data?.message || 'Failed to save visit record.');
      }
    } catch (err) {
      setFormError(err.response?.data?.message || 'Error occurred while saving visit.');
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this visit record?')) return;
    try {
      const res = await visitingService.deleteVisiting(id);
      if (res.data?.success) {
        loadVisitingRecords();
      }
    } catch (err) {
      alert('Failed to delete visit record');
    }
  };

  // Stats calculation
  const stats = useMemo(() => {
    const totalVisits = records.length;
    const totalFees = records.reduce(
      (sum, r) => sum + (parseFloat(r.visiting_student_fees_paid) || 0),
      0
    );
    const totalBooks = records.reduce(
      (sum, r) => sum + (parseFloat(r.visiting_book_fees) || 0),
      0
    );
    return { totalVisits, totalFees, totalBooks };
  }, [records]);

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
                  <FiUsers className="w-5 h-5" />
                </span>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight">
                  Visiting & Counter Log
                </h1>
              </div>
              <p className="text-sm text-slate-500 mt-1">
                Track parent office visits, counter fee receipts, inquiries, and book fee records
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={loadVisitingRecords}
                className="p-2 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition"
                title="Refresh"
              >
                <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
              </button>
              <button
                onClick={() => setIsModalOpen(true)}
                className="flex items-center gap-2 px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-sm font-medium shadow-sm transition"
              >
                <FiPlus className="w-4 h-4" />
                <span>Log New Visit</span>
              </button>
            </div>
          </div>

          {/* Quick Metrics */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
              <div>
                <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                  Total Visits
                </p>
                <p className="text-2xl font-bold mt-1 text-slate-900">
                  {stats.totalVisits}
                </p>
              </div>
              <div className="p-3 bg-[#e3f2fd] text-[#1976d2] rounded-xl">
                <FiUsers className="w-6 h-6" />
              </div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
              <div>
                <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                  Counter Fees Logged
                </p>
                <p className="text-2xl font-bold mt-1 text-emerald-600">
                  ₹{stats.totalFees.toLocaleString('en-IN')}
                </p>
              </div>
              <div className="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <FiDollarSign className="w-6 h-6" />
              </div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
              <div>
                <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                  Counter Book Fees
                </p>
                <p className="text-2xl font-bold mt-1 text-amber-600">
                  ₹{stats.totalBooks.toLocaleString('en-IN')}
                </p>
              </div>
              <div className="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <FiBookOpen className="w-6 h-6" />
              </div>
            </div>
          </div>

          {/* Filter Bar */}
          <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <div className="flex flex-wrap items-center gap-3">
              {/* Standard Filter */}
              <select
                value={filterStd}
                onChange={(e) => setFilterStd(e.target.value)}
                className="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
              >
                <option value="all">All Standards</option>
                {standards.map((s, idx) => (
                  <option key={idx} value={s.stvfees_class}>
                    {s.stvfees_class}
                  </option>
                ))}
              </select>

              {/* Date Filters */}
              <div className="flex items-center gap-2">
                <input
                  type="date"
                  value={fromDate}
                  onChange={(e) => setFromDate(e.target.value)}
                  className="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none"
                  placeholder="From Date"
                />
                <span className="text-slate-400 text-xs">to</span>
                <input
                  type="date"
                  value={toDate}
                  onChange={(e) => setToDate(e.target.value)}
                  className="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 focus:outline-none"
                  placeholder="To Date"
                />
              </div>

              {(fromDate || toDate || filterStd !== 'all') && (
                <button
                  onClick={() => {
                    setFilterStd('all');
                    setFromDate('');
                    setToDate('');
                    setSearchTerm('');
                  }}
                  className="text-xs text-[#1976d2] hover:underline"
                >
                  Clear Filters
                </button>
              )}
            </div>

            {/* Search Input */}
            <div className="relative w-full md:w-72">
              <FiSearch className="absolute left-3 top-3 text-slate-400 w-4 h-4" />
              <input
                type="text"
                placeholder="Search student or phone..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                onKeyDown={(e) => e.key === 'Enter' && loadVisitingRecords()}
                className="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
              />
            </div>
          </div>

          {/* Records Table */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th className="py-3 px-4">Visit ID</th>
                    <th className="py-3 px-4">Date</th>
                    <th className="py-3 px-4">Student</th>
                    <th className="py-3 px-4">Class</th>
                    <th className="py-3 px-4 text-right">Counter Paid</th>
                    <th className="py-3 px-4">Status / Purpose</th>
                    <th className="py-3 px-4">Next Due Date</th>
                    <th className="py-3 px-4 text-right">Book Fees</th>
                    <th className="py-3 px-4">Handled By</th>
                    <th className="py-3 px-4 text-center">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 text-sm">
                  {loading ? (
                    <tr>
                      <td colSpan="10" className="py-12 text-center text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                        Loading visiting records...
                      </td>
                    </tr>
                  ) : records.length === 0 ? (
                    <tr>
                      <td colSpan="10" className="py-12 text-center text-slate-400">
                        No visiting records found matching criteria.
                      </td>
                    </tr>
                  ) : (
                    records.map((r) => {
                      const whatsappNum = r.stvpcnf_whatsapp || r.stvpcnf_mobile;
                      const hasWhatsapp = Boolean(whatsappNum && String(whatsappNum).trim());
                      return (
                        <tr key={r.id} className="hover:bg-slate-50 transition">
                          <td className="py-3 px-4 font-mono text-xs text-slate-500">
                            #{r.id}
                          </td>
                          <td className="py-3 px-4 whitespace-nowrap text-xs text-slate-600">
                            {r.visiting_date ? new Date(r.visiting_date).toLocaleDateString() : '-'}
                          </td>
                          <td className="py-3 px-4">
                            <div className="font-medium text-slate-900">
                              {r.student_name || 'Student'}
                            </div>
                            <div className="text-xs text-slate-400 font-mono">
                              ID: {r.SPRIDEN_student_id || r.visiting_student_id} {r.spriden_gr_number ? `| GR: ${r.spriden_gr_number}` : ''}
                            </div>
                          </td>
                          <td className="py-3 px-4 whitespace-nowrap">
                            <span className="px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-700 font-medium">
                              {r.visiting_student_class || r.STVCLAS_standard || '-'} {r.STVCLAS_class || ''}
                            </span>
                          </td>
                          <td className="py-3 px-4 text-right font-medium text-emerald-600 whitespace-nowrap">
                            ₹{Number(r.visiting_student_fees_paid || 0).toLocaleString('en-IN')}
                          </td>
                          <td className="py-3 px-4 max-w-xs truncate text-xs text-slate-600" title={r.visiting_fees_status_of}>
                            {r.visiting_fees_status_of || '-'}
                          </td>
                          <td className="py-3 px-4 whitespace-nowrap text-xs text-amber-600">
                            {r.visiting_fees_due_date ? new Date(r.visiting_fees_due_date).toLocaleDateString() : '-'}
                          </td>
                          <td className="py-3 px-4 text-right whitespace-nowrap text-xs text-slate-700">
                            ₹{Number(r.visiting_book_fees || 0).toLocaleString('en-IN')}
                          </td>
                          <td className="py-3 px-4 whitespace-nowrap text-xs text-slate-500">
                            {r.staff_name || r.visiting_added_by || 'Office Counter'}
                          </td>
                          <td className="py-3 px-4 text-center whitespace-nowrap">
                            <div className="flex items-center justify-center gap-2">
                              {hasWhatsapp && (
                                <a
                                  href={`https://api.whatsapp.com/send?phone=91${whatsappNum.replace(/\D/g, '')}&text=Dear%20Parent,%20thank%20you%20for%20visiting%20Olive%20English%20School%20office.%20Counter%20Fees%20Paid:%20Rs%20${r.visiting_student_fees_paid}.`}
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  className="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded"
                                  title="WhatsApp Parent"
                                >
                                  <FiMessageSquare className="w-4 h-4" />
                                </a>
                              )}
                              <button
                                onClick={() => handleDelete(r.id)}
                                className="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition"
                                title="Delete visit record"
                              >
                                <FiTrash2 className="w-4 h-4" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      );
                    })
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>

      {/* Modal: Log New Visit */}
      {isModalOpen && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 max-h-[90vh] overflow-y-auto">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 className="font-semibold text-lg text-slate-900">
                Log Parent / Counter Visit
              </h3>
              <button
                onClick={() => setIsModalOpen(false)}
                className="text-slate-400 hover:text-slate-600 text-lg"
              >
                ✕
              </button>
            </div>

            {formError && (
              <div className="mt-3 p-3 text-xs bg-rose-50 text-rose-700 rounded-lg border border-rose-200">
                {formError}
              </div>
            )}

            <form onSubmit={handleSubmitVisit} className="space-y-4 mt-4">
              {/* Student Lookup */}
              <div>
                <label className="block text-xs font-medium text-slate-700 mb-1">
                  Find Student (Name / ID / GR) *
                </label>
                <div className="relative">
                  <input
                    type="text"
                    value={studentSearch}
                    onChange={(e) => handleStudentSearch(e.target.value)}
                    placeholder="Type to search student..."
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                    required
                  />
                  {selectedStudent && (
                    <FiCheckCircle className="absolute right-3 top-3 text-emerald-500 w-4 h-4" />
                  )}
                </div>

                {/* Dropdown search suggestions */}
                {searchedStudents.length > 0 && (
                  <div className="mt-1 max-h-48 overflow-y-auto bg-white border border-slate-200 rounded-lg shadow-lg z-20">
                    {searchedStudents.map((st) => (
                      <div
                        key={st.id}
                        onClick={() => handleSelectStudent(st)}
                        className="p-2.5 text-xs hover:bg-[#e3f2fd] cursor-pointer border-b border-slate-100 last:border-0"
                      >
                        <div className="font-medium text-slate-800">
                          {st.name || st.SPRIDEN_student_name} ({st.SPRIDEN_student_id})
                        </div>
                        <div className="text-slate-400">
                          Class: {st.current_standard || st.STVCLAS_standard || 'N/A'} - {st.current_class || st.STVCLAS_class || ''} | Mobile: {st.stvpcnf_mobile || 'N/A'}
                        </div>
                      </div>
                    ))}
                  </div>
                )}
              </div>

              {selectedStudent && (
                <div className="p-3 bg-[#e3f2fd]/60 rounded-lg border border-[#bbdefb] text-xs text-[#0d47a1] flex justify-between items-center">
                  <div>
                    <span className="font-semibold">Selected:</span> {selectedStudent.name || selectedStudent.SPRIDEN_student_name}
                    <span className="ml-2 text-[#1976d2]">
                      [{selectedStudent.current_standard || selectedStudent.STVCLAS_standard} {selectedStudent.current_class || selectedStudent.STVCLAS_class}]
                    </span>
                  </div>
                  <button
                    type="button"
                    onClick={() => {
                      setSelectedStudent(null);
                      setStudentSearch('');
                    }}
                    className="text-xs text-rose-500 hover:underline"
                  >
                    Change
                  </button>
                </div>
              )}

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-medium text-slate-700 mb-1">
                    Visit Date *
                  </label>
                  <input
                    type="date"
                    value={formData.visiting_date}
                    onChange={(e) => setFormData({ ...formData, visiting_date: e.target.value })}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                    required
                  />
                </div>
                <div>
                  <label className="block text-xs font-medium text-slate-700 mb-1">
                    Fees Paid (₹)
                  </label>
                  <input
                    type="number"
                    min="0"
                    placeholder="0"
                    value={formData.visiting_student_fees_paid}
                    onChange={(e) => setFormData({ ...formData, visiting_student_fees_paid: e.target.value })}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-medium text-slate-700 mb-1">
                  Status / Purpose / Notes
                </label>
                <input
                  type="text"
                  placeholder="e.g. Installment 2, LC inquiry, Book kit"
                  value={formData.visiting_fees_status_of}
                  onChange={(e) => setFormData({ ...formData, visiting_fees_status_of: e.target.value })}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-medium text-slate-700 mb-1">
                    Next Due Date
                  </label>
                  <input
                    type="date"
                    value={formData.visiting_fees_due_date}
                    onChange={(e) => setFormData({ ...formData, visiting_fees_due_date: e.target.value })}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  />
                </div>
                <div>
                  <label className="block text-xs font-medium text-slate-700 mb-1">
                    Book Fees (₹)
                  </label>
                  <input
                    type="number"
                    min="0"
                    placeholder="0"
                    value={formData.visiting_book_fees}
                    onChange={(e) => setFormData({ ...formData, visiting_book_fees: e.target.value })}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  />
                </div>
              </div>

              <div className="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm hover:bg-slate-50"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={submitting}
                  className="px-5 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-sm font-medium shadow-sm transition disabled:opacity-50"
                >
                  {submitting ? 'Saving...' : 'Save Visit Record'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
