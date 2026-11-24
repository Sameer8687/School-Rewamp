import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import teacherService from '../../services/teacherService.js';
import {
  FiUsers,
  FiUserPlus,
  FiSearch,
  FiFilter,
  FiEye,
  FiRefreshCw,
  FiCheckCircle,
  FiXCircle,
  FiX,
  FiBookOpen,
  FiCalendar,
  FiAward
} from 'react-icons/fi';

export default function TeacherListPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [teachers, setTeachers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [statusFilter, setStatusFilter] = useState('A'); // 'A', 'I', 'ALL'
  const [searchQuery, setSearchQuery] = useState('');

  // Profile Modal State
  const [selectedTeacher, setSelectedTeacher] = useState(null);
  const [loadingProfile, setLoadingProfile] = useState(false);
  const [actionFeedback, setActionFeedback] = useState(null);

  useEffect(() => {
    loadTeachers();
  }, [statusFilter]);

  async function loadTeachers() {
    setLoading(true);
    setActionFeedback(null);
    try {
      const res = await teacherService.getTeachers({
        status: statusFilter,
        search: searchQuery
      });
      if (res.data?.success) {
        setTeachers(res.data.teachers || []);
      }
    } catch (err) {
      console.error('Failed to load teachers:', err);
    } finally {
      setLoading(false);
    }
  }

  async function handleViewProfile(id) {
    setLoadingProfile(true);
    try {
      const res = await teacherService.getTeacherById(id);
      if (res.data?.success) {
        setSelectedTeacher(res.data.teacher);
      }
    } catch (err) {
      console.error('Failed to fetch profile:', err);
    } finally {
      setLoadingProfile(false);
    }
  }

  async function handleToggleStatus(teacher) {
    const newStatus = teacher.status === 'A' ? 'I' : 'A';
    const confirmMsg = `Are you sure you want to change status of ${teacher.name} to ${
      newStatus === 'A' ? 'Active' : 'Inactive'
    }?`;
    if (!window.confirm(confirmMsg)) return;

    try {
      const res = await teacherService.updateTeacherStatus(teacher.id, newStatus);
      if (res.data?.success) {
        setActionFeedback({
          type: 'success',
          text: `Status updated for ${teacher.name}`
        });
        await loadTeachers();
        if (selectedTeacher && selectedTeacher.id === teacher.id) {
          setSelectedTeacher({ ...selectedTeacher, spriden_student_status: newStatus });
        }
      }
    } catch (err) {
      console.error('Status toggle failed:', err);
      setActionFeedback({ type: 'error', text: 'Failed to update teacher status' });
    }
  }

  const filteredTeachers = teachers.filter((t) => {
    if (!searchQuery) return true;
    const q = searchQuery.toLowerCase();
    return (
      t.name?.toLowerCase().includes(q) ||
      t.teacher_id?.toLowerCase().includes(q) ||
      t.qualification?.toLowerCase().includes(q)
    );
  });

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
                <FiUsers className="text-[#1976d2]" /> Teacher & Faculty Directory
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Manage teaching faculty, employment records, qualifications, and class assignments
              </p>
            </div>
            <div className="flex items-center gap-2">
              <Link
                to="/teachers/create"
                className="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-sm font-semibold transition shadow-sm"
              >
                <FiUserPlus /> Register Teacher
              </Link>
            </div>
          </div>

          {/* Feedback */}
          {actionFeedback && (
            <div
              className={`p-3.5 rounded-xl text-sm flex items-center gap-2 ${
                actionFeedback.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border border-rose-200'
              }`}
            >
              {actionFeedback.type === 'success' ? <FiCheckCircle /> : <FiXCircle />}
              {actionFeedback.text}
            </div>
          )}

          {/* Filters & Search Toolbar */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div className="flex items-center gap-2 w-full sm:w-auto">
              <span className="text-xs font-semibold text-slate-500">Status:</span>
              <div className="inline-flex rounded-lg border border-slate-200 p-0.5 bg-slate-100">
                <button
                  onClick={() => setStatusFilter('A')}
                  className={`px-3 py-1 rounded-md text-xs font-bold transition ${
                    statusFilter === 'A'
                      ? 'bg-white text-[#1976d2] shadow-xs'
                      : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  Active
                </button>
                <button
                  onClick={() => setStatusFilter('I')}
                  className={`px-3 py-1 rounded-md text-xs font-bold transition ${
                    statusFilter === 'I'
                      ? 'bg-white text-[#1976d2] shadow-xs'
                      : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  Inactive
                </button>
                <button
                  onClick={() => setStatusFilter('ALL')}
                  className={`px-3 py-1 rounded-md text-xs font-bold transition ${
                    statusFilter === 'ALL'
                      ? 'bg-white text-[#1976d2] shadow-xs'
                      : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  All Faculty
                </button>
              </div>
            </div>

            {/* Live Search */}
            <div className="relative w-full sm:w-72">
              <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-3.5 h-3.5" />
              <input
                type="text"
                placeholder="Search teacher by name or ID..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
              />
            </div>
          </div>

          {/* Teachers Table */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                    <th className="p-3">#</th>
                    <th className="p-3">Employee ID</th>
                    <th className="p-3">Teacher Name</th>
                    <th className="p-3">Gender</th>
                    <th className="p-3">Qualification</th>
                    <th className="p-3">Address</th>
                    <th className="p-3 text-center">Status</th>
                    <th className="p-3 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan="8" className="p-8 text-center text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                        Loading faculty directory...
                      </td>
                    </tr>
                  ) : filteredTeachers.length > 0 ? (
                    filteredTeachers.map((t, idx) => (
                      <tr key={t.id} className="hover:bg-slate-50 transition">
                        <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                        <td className="p-3 font-mono font-bold text-[#1976d2]">
                          {t.teacher_id}
                        </td>
                        <td className="p-3">
                          <div className="font-bold text-slate-900">{t.name}</div>
                          {t.aadhar_number && (
                            <div className="text-[11px] text-slate-400">
                              Aadhar: {t.aadhar_number}
                            </div>
                          )}
                        </td>
                        <td className="p-3 text-slate-600">{t.gender || '-'}</td>
                        <td className="p-3 text-slate-700 font-medium">
                          {t.qualification || 'Graduate'}
                        </td>
                        <td className="p-3 text-slate-500 max-w-xs truncate" title={t.address}>
                          {t.address || '-'}
                        </td>
                        <td className="p-3 text-center">
                          <span
                            className={`px-2 py-0.5 rounded text-[11px] font-bold ${
                              t.status === 'A'
                                ? 'bg-emerald-100 text-emerald-800'
                                : 'bg-rose-100 text-rose-800'
                            }`}
                          >
                            {t.status === 'A' ? 'Active' : 'Inactive'}
                          </span>
                        </td>
                        <td className="p-3 text-center">
                          <div className="flex items-center justify-center gap-1.5">
                            <button
                              onClick={() => handleViewProfile(t.id)}
                              className="px-2.5 py-1 bg-[#e3f2fd] text-[#1976d2] hover:bg-[#bbdefb] rounded text-xs font-semibold flex items-center gap-1 transition"
                            >
                              <FiEye /> View
                            </button>
                            <button
                              onClick={() => handleToggleStatus(t)}
                              className={`px-2 py-1 rounded text-[11px] font-semibold transition ${
                                t.status === 'A'
                                  ? 'bg-rose-50 text-rose-700 hover:bg-rose-100'
                                  : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'
                              }`}
                            >
                              {t.status === 'A' ? 'Deactivate' : 'Activate'}
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="8" className="p-8 text-center text-slate-400">
                        No teachers found matching criteria.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>

      {/* Teacher Profile Modal */}
      {selectedTeacher && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl max-w-2xl w-full p-6 border border-slate-200 my-8 space-y-6">
            <div className="flex items-center justify-between pb-4 border-b border-slate-100">
              <div>
                <span className="text-xs font-bold text-[#1976d2] tracking-wider uppercase">
                  Faculty Profile
                </span>
                <h3 className="text-lg font-bold text-slate-900 mt-0.5">
                  {selectedTeacher.SPRIDEN_surname} {selectedTeacher.SPRIDEN_student_name}{' '}
                  {selectedTeacher.SPRIDEN_father_name}
                </h3>
              </div>
              <button
                onClick={() => setSelectedTeacher(null)}
                className="text-slate-400 hover:text-slate-600"
              >
                <FiX className="w-5 h-5" />
              </button>
            </div>

            {/* Summary Box */}
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs">
              <div>
                <span className="text-slate-400">Employee ID:</span>
                <div className="font-bold text-slate-900 font-mono mt-0.5">
                  {selectedTeacher.SPRIDEN_student_id}
                </div>
              </div>
              <div>
                <span className="text-slate-400">Gender:</span>
                <div className="font-semibold text-slate-800 mt-0.5">
                  {selectedTeacher.SPRIDEN_gender || '-'}
                </div>
              </div>
              <div>
                <span className="text-slate-400">Status:</span>
                <div className="mt-0.5">
                  <span
                    className={`px-2 py-0.5 rounded text-[11px] font-bold ${
                      selectedTeacher.spriden_student_status === 'A'
                        ? 'bg-emerald-100 text-emerald-800'
                        : 'bg-rose-100 text-rose-800'
                    }`}
                  >
                    {selectedTeacher.spriden_student_status === 'A' ? 'Active' : 'Inactive'}
                  </span>
                </div>
              </div>
              <div>
                <span className="text-slate-400">Date of Birth:</span>
                <div className="font-semibold text-slate-800 mt-0.5">
                  {selectedTeacher.SPRIDEN_birthdate
                    ? new Date(selectedTeacher.SPRIDEN_birthdate).toLocaleDateString()
                    : '-'}
                </div>
              </div>
              <div>
                <span className="text-slate-400">Mother's Tongue:</span>
                <div className="font-semibold text-slate-800 mt-0.5">
                  {selectedTeacher.SPRIDEN_mother_toungue || 'Urdu'}
                </div>
              </div>
              <div>
                <span className="text-slate-400">Aadhar Card:</span>
                <div className="font-mono text-slate-800 mt-0.5">
                  {selectedTeacher.SPRIDEN_aadhar_no || '-'}
                </div>
              </div>
            </div>

            {/* Academic Qualifications Table */}
            <div>
              <h4 className="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5 flex items-center gap-1.5">
                <FiAward className="text-[#1976d2]" /> Educational Qualifications
              </h4>
              <div className="border border-slate-200 rounded-lg overflow-hidden">
                <table className="w-full text-left text-xs border-collapse">
                  <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                      <th className="p-2.5">Level</th>
                      <th className="p-2.5">College / Institution</th>
                      <th className="p-2.5">Board / University</th>
                      <th className="p-2.5 text-center">Passing Year</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    <tr>
                      <td className="p-2.5 font-bold">10th SSC</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_ten_school || '-'}</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_ten_board || '-'}</td>
                      <td className="p-2.5 text-center">{selectedTeacher.SPRIDEN_ten_year || '-'}</td>
                    </tr>
                    <tr>
                      <td className="p-2.5 font-bold">12th HSC</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_twelve_college || '-'}</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_twelve_board || '-'}</td>
                      <td className="p-2.5 text-center">{selectedTeacher.SPRIDEN_twelve_year || '-'}</td>
                    </tr>
                    <tr>
                      <td className="p-2.5 font-bold">Graduation (UG)</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_ug_college || '-'}</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_ug_uni || '-'}</td>
                      <td className="p-2.5 text-center">{selectedTeacher.SPRIDEN_ug_year || '-'}</td>
                    </tr>
                    <tr>
                      <td className="p-2.5 font-bold">Post-Grad (PG)</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_pg_college || '-'}</td>
                      <td className="p-2.5">{selectedTeacher.SPRIDEN_pg_uni || '-'}</td>
                      <td className="p-2.5 text-center">{selectedTeacher.SPRIDEN_pg_year || '-'}</td>
                    </tr>
                    {selectedTeacher.SPRIDEN_phd_college && (
                      <tr>
                        <td className="p-2.5 font-bold">Doctorate (PhD)</td>
                        <td className="p-2.5">{selectedTeacher.SPRIDEN_phd_college}</td>
                        <td className="p-2.5">{selectedTeacher.SPRIDEN_phd_uni || '-'}</td>
                        <td className="p-2.5 text-center">{selectedTeacher.SPRIDEN_phd_year || '-'}</td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Address */}
            <div className="p-3.5 bg-slate-50 border border-slate-200 rounded-lg text-xs space-y-1">
              <span className="text-slate-400 font-semibold uppercase">Residential Address:</span>
              <p className="text-slate-700">{selectedTeacher.SPRIDEN_main_address || '-'}</p>
            </div>

            <div className="flex justify-end pt-2">
              <button
                onClick={() => setSelectedTeacher(null)}
                className="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-semibold"
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
