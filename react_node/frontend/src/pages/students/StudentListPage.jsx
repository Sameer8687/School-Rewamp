/**
 * Student Directory / List Page — 100% CI3 Functional Parity.
 * Direct conversion of `Student/student_list.php` and `Student::student_list()`.
 *
 * Features:
 *   - Academic Year dropdown
 *   - Status filter: Active ('A'), Inactive ('I'), No Show ('N'), All ('ALL')
 *   - Tabs: "Nursery to SSC" vs "Junior College (XI-XII)"
 *   - Live search by Name, GR number, Student ID, or Reg number
 *   - Status Change Modal with reason logging (into student_status_activity)
 *   - Actions: View/Edit Profile, Quick Status, Print List
 */
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  fetchStudents,
  fetchStudentOptions,
  updateStudentStatus,
} from '../../services/studentService.js';
import {
  FiUsers,
  FiUserPlus,
  FiSearch,
  FiFilter,
  FiPrinter,
  FiEdit,
  FiCheckCircle,
  FiAlertCircle,
  FiLayers,
  FiArrowRight,
  FiRefreshCw,
  FiX,
  FiInfo,
} from 'react-icons/fi';

export default function StudentListPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [students, setStudents] = useState([]);
  const [options, setOptions] = useState({ academicYears: [], classes: [], divisions: [] });

  // Filters
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStatus, setSelectedStatus] = useState('A');
  const [selectedTab, setSelectedTab] = useState('ssc'); // 'ssc' or 'jc'
  const [searchQuery, setSearchQuery] = useState('');

  // Status Modal State
  const [statusModalStudent, setStatusModalStudent] = useState(null);
  const [newStatus, setNewStatus] = useState('A');
  const [statusReason, setStatusReason] = useState('');
  const [savingStatus, setSavingStatus] = useState(false);

  // Load initial dropdown options
  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.success && res.data) {
          setOptions(res.data);
          if (res.data.currentAcdmId) {
            setSelectedYear(String(res.data.currentAcdmId));
          }
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  // Load students list
  const loadStudents = async () => {
    if (!selectedYear) return;
    try {
      setLoading(true);
      const res = await fetchStudents({
        acdm_year: selectedYear,
        status: selectedStatus,
        type: selectedTab,
        search: searchQuery,
      });
      if (res.success) {
        setStudents(res.data?.students || res.students || (Array.isArray(res.data) ? res.data : []));
      }
    } catch (err) {
      console.error('Failed to fetch students:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadStudents();
  }, [selectedYear, selectedStatus, selectedTab, searchQuery]);

  // Handle Quick Status Change
  const handleOpenStatusModal = (student) => {
    setStatusModalStudent(student);
    setNewStatus(student.spriden_student_status || 'A');
    setStatusReason('');
  };

  const handleSaveStatus = async (e) => {
    e.preventDefault();
    if (!statusModalStudent) return;
    try {
      setSavingStatus(true);
      const res = await updateStudentStatus(statusModalStudent.id, {
        status: newStatus,
        reason: statusReason,
      });
      if (res.success) {
        setStatusModalStudent(null);
        loadStudents();
      }
    } catch (err) {
      alert('Failed to update status: ' + (err.response?.data?.message || err.message));
    } finally {
      setSavingStatus(false);
    }
  };

  // Status Badges
  const renderStatusBadge = (status) => {
    switch (status) {
      case 'A':
        return (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
            Active
          </span>
        );
      case 'I':
        return (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-800">
            Inactive
          </span>
        );
      case 'n':
      case 'N':
        return (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
            No Show
          </span>
        );
      default:
        return (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-800">
            {status || 'Unknown'}
          </span>
        );
    }
  };

  return (
    <div className="min-h-screen bg-[#f4f7fa] flex flex-col font-sans">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} isSidebarOpen={sidebarOpen} />

      <main className="flex-1 p-4 md:p-6 transition-all duration-300">
        <div className="max-w-7xl mx-auto space-y-4">

          {/* ─── Breadcrumb & Action Header ─── */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <div className="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                <Link to="/dashboard" className="hover:text-[#1976d2]">Dashboard</Link>
                <span>/</span>
                <span className="text-slate-800">Student Directory</span>
              </div>
              <h1 className="text-xl md:text-2xl font-bold text-slate-900 mt-1 flex items-center gap-2">
                <FiUsers className="text-[#1976d2]" />
                Student Directory & Management
              </h1>
            </div>

            {/* Quick module action buttons */}
            <div className="flex flex-wrap items-center gap-2">
              <Link
                to="/students/admission"
                className="inline-flex items-center gap-2 px-3 py-2 bg-[#1976d2] text-white text-xs font-semibold rounded-lg hover:bg-[#1565c0] shadow-sm transition-all"
              >
                <FiUserPlus size={14} />
                <span>New Admission</span>
              </Link>
              <Link
                to="/students/promotion"
                className="inline-flex items-center gap-2 px-3 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 shadow-sm transition-all"
              >
                <FiLayers size={14} />
                <span>Promote Class</span>
              </Link>
              <Link
                to="/students/readmission"
                className="inline-flex items-center gap-2 px-3 py-2 bg-[#1976d2] text-white text-xs font-semibold rounded-lg hover:bg-[#1565c0] shadow-sm transition-all"
              >
                <FiRefreshCw size={14} />
                <span>Re-Admission</span>
              </Link>
              <Link
                to="/students/lc"
                className="inline-flex items-center gap-2 px-3 py-2 bg-slate-700 text-white text-xs font-semibold rounded-lg hover:bg-slate-800 shadow-sm transition-all"
              >
                <FiArrowRight size={14} />
                <span>Issue LC</span>
              </Link>
              <button
                onClick={() => window.print()}
                className="inline-flex items-center gap-2 px-3 py-2 bg-white text-slate-700 border border-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-50 shadow-sm transition-all no-print"
                title="Print this list"
              >
                <FiPrinter size={14} />
                <span>Print</span>
              </button>
            </div>
          </div>

          {/* ─── Filters & Search Bar ─── */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
              {/* Academic Year */}
              <div>
                <label className="block text-xs font-semibold text-slate-600 mb-1">
                  Academic Year
                </label>
                <select
                  value={selectedYear}
                  onChange={(e) => setSelectedYear(e.target.value)}
                  className="w-full text-xs border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:outline-none bg-slate-50"
                >
                  {options.academicYears.map((yr) => (
                    <option key={yr.stvacdm_id} value={yr.stvacdm_id}>
                      {yr.stvacdm_year}
                    </option>
                  ))}
                </select>
              </div>

              {/* Status Filter */}
              <div>
                <label className="block text-xs font-semibold text-slate-600 mb-1">
                  Student Status
                </label>
                <select
                  value={selectedStatus}
                  onChange={(e) => setSelectedStatus(e.target.value)}
                  className="w-full text-xs border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:outline-none bg-slate-50"
                >
                  <option value="A">Active (A)</option>
                  <option value="I">Inactive (I)</option>
                  <option value="N">No Show (N)</option>
                  <option value="ALL">All Statuses</option>
                </select>
              </div>

              {/* Search Bar */}
              <div className="lg:col-span-2">
                <label className="block text-xs font-semibold text-slate-600 mb-1">
                  Search by Name, GR No, ID or Reg No
                </label>
                <div className="relative">
                  <FiSearch className="absolute left-3 top-3 text-slate-400" size={14} />
                  <input
                    type="text"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    placeholder="Type to filter immediately..."
                    className="w-full text-xs pl-9 pr-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                  {searchQuery && (
                    <button
                      onClick={() => setSearchQuery('')}
                      className="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600"
                    >
                      <FiX size={14} />
                    </button>
                  )}
                </div>
              </div>
            </div>

            {/* Section Tabs (Nursery-SSC vs Junior College) */}
            <div className="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
              <div className="flex gap-2">
                <button
                  onClick={() => setSelectedTab('ssc')}
                  className={`px-4 py-2 rounded-lg text-xs font-semibold transition-all ${
                    selectedTab === 'ssc'
                      ? 'bg-[#1976d2] text-white shadow'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                  }`}
                >
                  Nursery to SSC (Std 1 - 10)
                </button>
                <button
                  onClick={() => setSelectedTab('jc')}
                  className={`px-4 py-2 rounded-lg text-xs font-semibold transition-all ${
                    selectedTab === 'jc'
                      ? 'bg-[#1976d2] text-white shadow'
                      : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                  }`}
                >
                  Junior College (Std 11 & 12)
                </button>
              </div>

              <div className="text-xs text-slate-500 font-medium">
                Found <span className="font-bold text-slate-800">{students.length}</span> students
              </div>
            </div>
          </div>

          {/* ─── Students Data Table ─── */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            {loading ? (
              <div className="py-16 text-center text-slate-500 text-xs">
                <div className="inline-block animate-spin w-6 h-6 border-2 border-slate-300 border-t-[#1976d2] rounded-full mb-2"></div>
                <div>Loading student records...</div>
              </div>
            ) : students.length === 0 ? (
              <div className="py-16 text-center text-slate-500 text-xs">
                <FiInfo className="mx-auto mb-2 text-slate-400" size={24} />
                <p className="font-medium text-slate-700">No students found matching your criteria.</p>
                <p className="text-slate-400 mt-1">Try adjusting your filters or academic year.</p>
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                      <th className="py-3 px-3 w-12 text-center">#</th>
                      <th className="py-3 px-3">GR No</th>
                      <th className="py-3 px-3">Student ID</th>
                      <th className="py-3 px-3">Student Full Name</th>
                      <th className="py-3 px-3">Class & Div</th>
                      <th className="py-3 px-3">Roll No</th>
                      <th className="py-3 px-3">Parent Mobile</th>
                      <th className="py-3 px-3 text-center">Status</th>
                      <th className="py-3 px-3 text-center">LC Status</th>
                      <th className="py-3 px-3 text-center no-print">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {students.map((st, idx) => (
                      <tr
                        key={st.id}
                        className="hover:bg-slate-50/80 transition-colors"
                      >
                        <td className="py-2.5 px-3 text-center text-slate-400 font-mono text-[11px]">
                          {idx + 1}
                        </td>
                        <td className="py-2.5 px-3 font-semibold text-slate-800">
                          {st.spriden_gr_number || '—'}
                        </td>
                        <td className="py-2.5 px-3 font-mono text-slate-600">
                          {st.SPRIDEN_student_id}
                        </td>
                        <td className="py-2.5 px-3">
                          <Link
                            to={`/students/${st.id}`}
                            className="font-semibold text-[#1976d2] hover:text-[#0d47a1] hover:underline"
                          >
                            {st.name || `${st.SPRIDEN_surname || ''} ${st.SPRIDEN_student_name || ''} ${st.SPRIDEN_father_name || ''}`}
                          </Link>
                          {st.rte_status === 'Yes' && (
                            <span className="ml-1.5 px-1.5 py-0.2 text-[9px] bg-sky-100 text-sky-800 font-bold rounded">
                              RTE
                            </span>
                          )}
                        </td>
                        <td className="py-2.5 px-3 font-medium text-slate-700">
                          {st.STVCLAS_standard} {st.STVCLAS_class ? `- ${st.STVCLAS_class}` : ''}
                        </td>
                        <td className="py-2.5 px-3 text-slate-600">
                          {st.STVCLAS_rollno || '0'}
                        </td>
                        <td className="py-2.5 px-3 text-slate-600">
                          {st.stvpcnf_mobile || '—'}
                        </td>
                        <td className="py-2.5 px-3 text-center">
                          {renderStatusBadge(st.spriden_student_status)}
                        </td>
                        <td className="py-2.5 px-3 text-center">
                          {st.lc_status ? (
                            <span className="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800">
                              {st.lc_status}
                            </span>
                          ) : (
                            <span className="text-slate-400 text-[11px]">—</span>
                          )}
                        </td>
                        <td className="py-2.5 px-3 text-center no-print">
                          <div className="flex items-center justify-center gap-1.5">
                            <Link
                              to={`/students/${st.id}`}
                              className="p-1.5 text-slate-600 hover:text-[#1976d2] hover:bg-slate-100 rounded transition-colors"
                              title="View & Edit Full Profile"
                            >
                              <FiEdit size={14} />
                            </Link>
                            <button
                              onClick={() => handleOpenStatusModal(st)}
                              className="p-1.5 text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded transition-colors"
                              title="Change Status"
                            >
                              <FiAlertCircle size={14} />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>

        </div>
      </main>

      {/* ─── Change Status Modal ─── */}
      {statusModalStudent && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-xl shadow-xl max-w-md w-full p-6 animate-scale-in">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                <FiAlertCircle className="text-amber-500" />
                Change Student Status
              </h3>
              <button
                onClick={() => setStatusModalStudent(null)}
                className="text-slate-400 hover:text-slate-600"
              >
                <FiX size={16} />
              </button>
            </div>

            <form onSubmit={handleSaveStatus} className="mt-4 space-y-4 text-xs">
              <div>
                <p className="text-slate-600">
                  Updating status for{' '}
                  <span className="font-bold text-slate-900">
                    {statusModalStudent.name}
                  </span>{' '}
                  (GR: {statusModalStudent.spriden_gr_number || 'N/A'})
                </p>
              </div>

              <div>
                <label className="block font-semibold text-slate-700 mb-1">
                  Select New Status
                </label>
                <select
                  value={newStatus}
                  onChange={(e) => setNewStatus(e.target.value)}
                  className="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                >
                  <option value="A">Active (A)</option>
                  <option value="I">Inactive (I)</option>
                  <option value="N">No Show (N)</option>
                </select>
              </div>

              <div>
                <label className="block font-semibold text-slate-700 mb-1">
                  Reason for Status Change (will be logged in activity history)
                </label>
                <textarea
                  rows="3"
                  value={statusReason}
                  onChange={(e) => setStatusReason(e.target.value)}
                  placeholder="e.g. Left school, fee default, family relocation..."
                  className="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  required
                />
              </div>

              <div className="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setStatusModalStudent(null)}
                  className="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={savingStatus}
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg font-semibold flex items-center gap-1.5 shadow cursor-pointer"
                >
                  {savingStatus ? 'Saving...' : 'Update Status'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
