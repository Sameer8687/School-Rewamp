import React, { useState, useEffect, useMemo } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import tcmService from '../../services/tcmService.js';
import {
  FiPhoneCall,
  FiUserCheck,
  FiSearch,
  FiPlus,
  FiTrash2,
  FiRefreshCw,
  FiAlertCircle,
  FiCheckCircle,
  FiMapPin,
  FiCalendar,
  FiDollarSign,
  FiX
} from 'react-icons/fi';

export default function TeacherCallingPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [records, setRecords] = useState([]);
  const [teachers, setTeachers] = useState([]);

  // Search & Filters for table
  const [searchTerm, setSearchTerm] = useState('');
  const [filterTeacher, setFilterTeacher] = useState('all');
  const [filterStd, setFilterStd] = useState('all');

  // Form State
  const [showAddForm, setShowAddForm] = useState(false);
  const [studentQuery, setStudentQuery] = useState('');
  const [searchingStudents, setSearchingStudents] = useState(false);
  const [searchResults, setSearchResults] = useState([]);
  const [selectedStudent, setSelectedStudent] = useState(null);

  const [formData, setFormData] = useState({
    teacher_id: '',
    landmark: '',
    notes: ''
  });

  const [submitting, setSubmitting] = useState(false);
  const [feedback, setFeedback] = useState({ type: '', message: '' });

  // Load records and teachers
  const loadData = async () => {
    setLoading(true);
    try {
      const [recRes, tchRes] = await Promise.all([
        tcmService.getAllRecords(),
        tcmService.getTeachers()
      ]);
      if (recRes.data?.success) setRecords(recRes.data.data || []);
      if (tchRes.data?.success) setTeachers(tchRes.data.data || []);
    } catch (err) {
      console.error('Failed to load TCM records:', err);
      setFeedback({ type: 'error', message: 'Failed to fetch calling records from server.' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  // Search students for form
  useEffect(() => {
    if (!studentQuery || studentQuery.trim().length < 2) {
      setSearchResults([]);
      return;
    }
    const timer = setTimeout(async () => {
      setSearchingStudents(true);
      try {
        const res = await tcmService.searchStudents(studentQuery);
        if (res.data?.success) {
          setSearchResults(res.data.data || []);
        }
      } catch (err) {
        console.error('Student search error:', err);
      } finally {
        setSearchingStudents(false);
      }
    }, 300);

    return () => clearTimeout(timer);
  }, [studentQuery]);

  const handleSelectStudent = (st) => {
    setSelectedStudent(st);
    setSearchResults([]);
    setStudentQuery(`${st.full_name} (${st.student_code})`);
  };

  const handleClearStudent = () => {
    setSelectedStudent(null);
    setStudentQuery('');
    setSearchResults([]);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!selectedStudent) {
      setFeedback({ type: 'error', message: 'Please select a student from search.' });
      return;
    }
    if (!formData.teacher_id) {
      setFeedback({ type: 'error', message: 'Please assign a teacher.' });
      return;
    }

    setSubmitting(true);
    setFeedback({ type: '', message: '' });

    try {
      const res = await tcmService.createRecord({
        student_id: selectedStudent.id,
        teacher_id: formData.teacher_id,
        landmark: formData.landmark,
        notes: formData.notes
      });

      if (res.data?.success) {
        setFeedback({ type: 'success', message: 'Calling record saved successfully.' });
        setSelectedStudent(null);
        setStudentQuery('');
        setFormData({ teacher_id: '', landmark: '', notes: '' });
        setShowAddForm(false);
        loadData();
      }
    } catch (err) {
      console.error('Error creating record:', err);
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Failed to save record.'
      });
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this calling record?')) return;
    try {
      const res = await tcmService.deleteRecord(id);
      if (res.data?.success) {
        setRecords(prev => prev.filter(r => r.id !== id));
        setFeedback({ type: 'success', message: 'Record deleted.' });
      }
    } catch (err) {
      console.error('Delete error:', err);
      setFeedback({ type: 'error', message: 'Failed to delete record.' });
    }
  };

  // Filtered records
  const filteredRecords = useMemo(() => {
    return records.filter(r => {
      const matchSearch = searchTerm === '' ||
        (r.student_name && r.student_name.toLowerCase().includes(searchTerm.toLowerCase())) ||
        (r.student_code && r.student_code.toLowerCase().includes(searchTerm.toLowerCase())) ||
        (r.phone && r.phone.includes(searchTerm)) ||
        (r.landmark && r.landmark.toLowerCase().includes(searchTerm.toLowerCase()));

      const matchTeacher = filterTeacher === 'all' || r.teacher_code === filterTeacher;
      const matchStd = filterStd === 'all' || r.standard === filterStd;

      return matchSearch && matchTeacher && matchStd;
    });
  }, [records, searchTerm, filterTeacher, filterStd]);

  // Unique standards in records
  const standardsList = useMemo(() => {
    const set = new Set();
    records.forEach(r => { if (r.standard) set.add(r.standard); });
    return Array.from(set).sort();
  }, [records]);

  // KPI stats
  const totalCalls = records.length;
  const totalBalanceTracked = records.reduce((sum, r) => sum + (parseFloat(r.balance_fees) || 0), 0);
  const uniqueTeachers = new Set(records.map(r => r.teacher_code)).size;

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} setIsOpen={setSidebarOpen} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          {/* Header */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
              <div className="flex items-center gap-2">
                <span className="p-2 bg-[#e3f2fd] text-[#1976d2] rounded-lg">
                  <FiPhoneCall className="w-6 h-6" />
                </span>
                <h1 className="text-2xl font-bold tracking-tight text-slate-900">
                  Teacher Calling & Follow-up Log (TCM)
                </h1>
              </div>
              <p className="text-sm text-slate-500 mt-1">
                Assign teachers to follow up on student fee recovery, log parent communications, and track landmark notes.
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={loadData}
                className="flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 shadow-sm transition"
              >
                <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
                Refresh
              </button>
              <button
                onClick={() => setShowAddForm(!showAddForm)}
                className="flex items-center gap-1.5 px-4 py-2 bg-[#1976d2] text-white text-sm font-medium rounded-lg hover:bg-[#1565c0] shadow-sm transition"
              >
                <FiPlus className="w-4 h-4" />
                {showAddForm ? 'Close Form' : 'New Calling Entry'}
              </button>
            </div>
          </div>

          {/* Feedback message */}
          {feedback.message && (
            <div className={`p-4 mb-6 rounded-lg flex items-center gap-3 text-sm ${
              feedback.type === 'success'
                ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                : 'bg-rose-50 text-rose-800 border border-rose-200'
            }`}>
              {feedback.type === 'success' ? <FiCheckCircle className="w-5 h-5 shrink-0" /> : <FiAlertCircle className="w-5 h-5 shrink-0" />}
              <span>{feedback.message}</span>
              <button onClick={() => setFeedback({ type: '', message: '' })} className="ml-auto text-slate-400 hover:text-slate-600">
                <FiX className="w-4 h-4" />
              </button>
            </div>
          )}

          {/* KPI Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Follow-ups</div>
              <div className="text-2xl font-bold text-slate-900 mt-1">{totalCalls}</div>
              <div className="text-xs text-slate-400 mt-0.5">Calling records logged</div>
            </div>
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="text-xs font-medium text-slate-500 uppercase tracking-wider">Pending Balance Tracked</div>
              <div className="text-2xl font-bold text-amber-600 mt-1">₹{totalBalanceTracked.toLocaleString('en-IN')}</div>
              <div className="text-xs text-slate-400 mt-0.5">Across active records</div>
            </div>
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="text-xs font-medium text-slate-500 uppercase tracking-wider">Assigned Teachers</div>
              <div className="text-2xl font-bold text-[#1976d2] mt-1">{uniqueTeachers}</div>
              <div className="text-xs text-slate-400 mt-0.5">Faculty participating</div>
            </div>
          </div>

          {/* New Entry Collapsible Form */}
          {showAddForm && (
            <div className="bg-white rounded-xl border border-[#bbdefb] shadow-sm p-5 mb-6">
              <h2 className="text-base font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <FiPlus className="w-4 h-4 text-[#1976d2]" />
                Register Teacher Calling & Fee Recovery Entry
              </h2>

              <form onSubmit={handleSubmit} className="space-y-4">
                {/* Student Search */}
                <div className="relative">
                  <label className="block text-xs font-medium text-slate-700 mb-1">
                    Search Student <span className="text-rose-500">*</span>
                  </label>
                  <div className="relative">
                    <FiSearch className="absolute left-3 top-3 text-slate-400 w-4 h-4" />
                    <input
                      type="text"
                      value={studentQuery}
                      onChange={(e) => {
                        setStudentQuery(e.target.value);
                        if (selectedStudent) setSelectedStudent(null);
                      }}
                      placeholder="Type student name or ID..."
                      className="w-full pl-9 pr-10 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                    />
                    {studentQuery && (
                      <button
                        type="button"
                        onClick={handleClearStudent}
                        className="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600"
                      >
                        <FiX className="w-4 h-4" />
                      </button>
                    )}
                  </div>

                  {/* Autocomplete Dropdown */}
                  {searchingStudents && (
                    <div className="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg p-3 text-xs text-slate-500 text-center">
                      Searching students...
                    </div>
                  )}

                  {!searchingStudents && searchResults.length > 0 && !selectedStudent && (
                    <div className="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100">
                      {searchResults.map((st) => (
                        <div
                          key={st.id}
                          onClick={() => handleSelectStudent(st)}
                          className="p-3 hover:bg-[#e3f2fd] cursor-pointer transition flex items-center justify-between text-xs"
                        >
                          <div>
                            <span className="font-semibold text-slate-800">{st.full_name}</span>
                            <span className="ml-2 text-slate-400">({st.student_code})</span>
                            <div className="text-slate-500 mt-0.5">
                              Std: {st.standard} - Div: {st.division} | Phone: {st.phone || 'N/A'}
                            </div>
                          </div>
                          <div className="text-right">
                            <span className="px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-100 text-amber-800">
                              Bal: ₹{st.balance_fees}
                            </span>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </div>

                {/* Selected Student Card */}
                {selectedStudent && (
                  <div className="bg-slate-50 border border-slate-200 rounded-lg p-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                      <span className="text-slate-500">Student Name:</span>
                      <p className="font-semibold text-slate-800 mt-0.5">{selectedStudent.full_name}</p>
                    </div>
                    <div>
                      <span className="text-slate-500">Class & Division:</span>
                      <p className="font-semibold text-slate-800 mt-0.5">{selectedStudent.standard} - {selectedStudent.division}</p>
                    </div>
                    <div>
                      <span className="text-slate-500">Contact Number:</span>
                      <p className="font-semibold text-slate-800 mt-0.5">{selectedStudent.phone || 'Not available'}</p>
                    </div>
                    <div>
                      <span className="text-slate-500">Fee Balance:</span>
                      <p className="font-bold text-amber-600 mt-0.5">₹{selectedStudent.balance_fees} (Paid: ₹{selectedStudent.fees_paid})</p>
                    </div>
                  </div>
                )}

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  {/* Teacher Assignment */}
                  <div>
                    <label className="block text-xs font-medium text-slate-700 mb-1">
                      Assign Teacher <span className="text-rose-500">*</span>
                    </label>
                    <select
                      value={formData.teacher_id}
                      onChange={(e) => setFormData({ ...formData, teacher_id: e.target.value })}
                      required
                      className="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2] bg-white"
                    >
                      <option value="">-- Select Teacher --</option>
                      {teachers.map((tch) => (
                        <option key={tch.id} value={tch.teacher_code}>
                          {tch.name} ({tch.teacher_code})
                        </option>
                      ))}
                    </select>
                  </div>

                  {/* Landmark */}
                  <div>
                    <label className="block text-xs font-medium text-slate-700 mb-1">
                      Landmark / Area
                    </label>
                    <div className="relative">
                      <FiMapPin className="absolute left-3 top-3 text-slate-400 w-4 h-4" />
                      <input
                        type="text"
                        value={formData.landmark}
                        onChange={(e) => setFormData({ ...formData, landmark: e.target.value })}
                        placeholder="e.g. Near Jama Masjid, Millat Nagar"
                        className="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                      />
                    </div>
                  </div>
                </div>

                {/* Notes */}
                <div>
                  <label className="block text-xs font-medium text-slate-700 mb-1">
                    Follow-up Notes & Remarks
                  </label>
                  <textarea
                    rows={2}
                    value={formData.notes}
                    onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                    placeholder="Enter call outcome, father's response, promised payment date, etc."
                    className="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                  />
                </div>

                {/* Submit buttons */}
                <div className="flex justify-end gap-2 pt-2">
                  <button
                    type="button"
                    onClick={() => setShowAddForm(false)}
                    className="px-4 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    disabled={submitting}
                    className="px-5 py-2 bg-[#1976d2] text-white text-sm font-medium rounded-lg hover:bg-[#1565c0] transition disabled:opacity-50 flex items-center gap-1.5"
                  >
                    {submitting ? 'Saving...' : 'Save Calling Record'}
                  </button>
                </div>
              </form>
            </div>
          )}

          {/* Table Card */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            {/* Table Filters */}
            <div className="p-4 border-b border-slate-100 flex flex-col md:flex-row gap-3 items-center justify-between">
              <div className="relative w-full md:w-72">
                <FiSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Search student, mobile, landmark..."
                  className="w-full pl-9 pr-3 py-1.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div className="flex items-center gap-2 w-full md:w-auto">
                {/* Standard Filter */}
                <select
                  value={filterStd}
                  onChange={(e) => setFilterStd(e.target.value)}
                  className="px-3 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="all">All Standards</option>
                  {standardsList.map((std) => (
                    <option key={std} value={std}>Std {std}</option>
                  ))}
                </select>

                {/* Teacher Filter */}
                <select
                  value={filterTeacher}
                  onChange={(e) => setFilterTeacher(e.target.value)}
                  className="px-3 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="all">All Teachers</option>
                  {teachers.map((tch) => (
                    <option key={tch.id} value={tch.teacher_code}>{tch.name}</option>
                  ))}
                </select>
              </div>
            </div>

            {/* Table content */}
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                  <tr>
                    <th className="px-4 py-3">#</th>
                    <th className="px-4 py-3">Student Info</th>
                    <th className="px-4 py-3">Std / Div</th>
                    <th className="px-4 py-3">Phone</th>
                    <th className="px-4 py-3">Fees Paid</th>
                    <th className="px-4 py-3">Balance</th>
                    <th className="px-4 py-3">Assigned Teacher</th>
                    <th className="px-4 py-3">Landmark</th>
                    <th className="px-4 py-3">Notes</th>
                    <th className="px-4 py-3">Date</th>
                    <th className="px-4 py-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan="11" className="text-center py-10 text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                        Loading calling records...
                      </td>
                    </tr>
                  ) : filteredRecords.length === 0 ? (
                    <tr>
                      <td colSpan="11" className="text-center py-10 text-slate-400">
                        No teacher calling records found. Click "New Calling Entry" to create one.
                      </td>
                    </tr>
                  ) : (
                    filteredRecords.map((r, idx) => (
                      <tr key={r.id} className="hover:bg-slate-50/70 transition">
                        <td className="px-4 py-3 text-slate-400">{idx + 1}</td>
                        <td className="px-4 py-3">
                          <div className="font-semibold text-slate-800">{r.student_name}</div>
                          <div className="text-[11px] text-slate-400">{r.student_code}</div>
                        </td>
                        <td className="px-4 py-3">
                          <span className="font-medium text-slate-700">{r.standard || '-'}</span>
                          {r.division && <span className="text-slate-400"> ({r.division})</span>}
                        </td>
                        <td className="px-4 py-3 text-slate-600 font-mono text-[11px]">
                          {r.phone || '-'}
                        </td>
                        <td className="px-4 py-3 text-emerald-600 font-semibold">
                          ₹{Number(r.fees_paid).toLocaleString('en-IN')}
                        </td>
                        <td className="px-4 py-3 font-semibold text-amber-600">
                          ₹{Number(r.balance_fees).toLocaleString('en-IN')}
                        </td>
                        <td className="px-4 py-3 text-slate-700 font-medium">
                          {r.teacher_name || r.teacher_code || '-'}
                        </td>
                        <td className="px-4 py-3 text-slate-600 max-w-[150px] truncate" title={r.landmark}>
                          {r.landmark || '-'}
                        </td>
                        <td className="px-4 py-3 text-slate-600 max-w-[200px] truncate" title={r.notes}>
                          {r.notes || '-'}
                        </td>
                        <td className="px-4 py-3 text-slate-400 text-[11px]">
                          {r.created_at || '-'}
                        </td>
                        <td className="px-4 py-3 text-right">
                          <button
                            onClick={() => handleDelete(r.id)}
                            className="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition"
                            title="Delete entry"
                          >
                            <FiTrash2 className="w-4 h-4" />
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>

            {/* Table Footer */}
            <div className="p-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex justify-between items-center">
              <span>Showing {filteredRecords.length} of {records.length} records</span>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}

