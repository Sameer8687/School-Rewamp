import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import masterService from '../../services/masterService';
import {
  FiBarChart2,
  FiUser,
  FiEye,
  FiEdit2,
  FiTrash2,
  FiCheckCircle,
  FiAlertCircle,
  FiSearch,
  FiGrid,
  FiFileText,
  FiLock,
  FiX,
  FiFolder,
} from 'react-icons/fi';

export default function UserAccessReportPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const navigate = useNavigate();

  const [reports, setReports] = useState([]);
  const [loading, setLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [feedback, setFeedback] = useState(null);

  // Detail Modal
  const [selectedUser, setSelectedUser] = useState(null);
  const [userAccessList, setUserAccessList] = useState([]);
  const [modalLoading, setModalLoading] = useState(false);

  const loadReports = async () => {
    try {
      setLoading(true);
      const res = await masterService.getUserReports();
      if (res.data?.success) {
        setReports(res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load user access reports:', err);
      setFeedback({ type: 'error', text: 'Failed to load user access reports.' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadReports();
  }, []);

  const handleViewAccess = async (user) => {
    setSelectedUser(user);
    setModalLoading(true);
    try {
      const res = await masterService.getUserAccessList(user.spruser_id);
      if (res.data?.success) {
        setUserAccessList(res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load user access list:', err);
    } finally {
      setModalLoading(false);
    }
  };

  const handleRevoke = async (userId, name) => {
    if (!window.confirm(`Are you sure you want to revoke all access permissions for "${name}"?`)) {
      return;
    }
    try {
      await masterService.deleteUserAccess(userId);
      setFeedback({ type: 'success', text: `Access revoked for "${name}".` });
      loadReports();
    } catch (err) {
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to revoke user access.',
      });
    }
  };

  const filteredReports = reports.filter((r) =>
    r.spruser_name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
    r.spruser_username?.toLowerCase().includes(searchQuery.toLowerCase()) ||
    r.spruser_usertype?.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div className="flex h-screen bg-[#f4f7f6] overflow-hidden text-slate-800 font-sans">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="p-6 max-w-7xl mx-auto w-full">
          {/* Header & Subnav Bar */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
              <h1 className="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <FiBarChart2 className="text-[#1976d2]" />
                User Access Report
              </h1>
              <p className="text-xs text-slate-500 mt-0.5">
                Audit and monitor active screen permissions and form allocations by user
              </p>
            </div>

            {/* Sub-nav quick tabs */}
            <div className="flex flex-wrap items-center gap-1.5 sm:gap-2 bg-white p-1 rounded-xl border border-slate-200 shadow-sm text-xs font-semibold">
              <Link
                to="/master/modules"
                className="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-1.5"
              >
                <FiGrid size={13} />
                <span>Modules</span>
              </Link>
              <Link
                to="/master/forms"
                className="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-1.5"
              >
                <FiFileText size={13} />
                <span>Forms</span>
              </Link>
              <Link
                to="/master/user-access"
                className="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-1.5"
              >
                <FiLock size={13} />
                <span>User Access</span>
              </Link>
              <Link
                to="/master/user-reports"
                className="px-3 py-1.5 rounded-lg bg-[#1976d2] text-white shadow-sm flex items-center gap-1.5"
              >
                <FiBarChart2 size={13} />
                <span>Access Reports</span>
              </Link>
            </div>
          </div>

          {/* Feedback Alert */}
          {feedback && (
            <div
              className={`p-4 rounded-xl mb-6 flex items-start justify-between border ${
                feedback.type === 'success'
                  ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                  : 'bg-rose-50 border-rose-200 text-rose-800'
              }`}
            >
              <div className="flex items-center gap-2 text-xs font-semibold">
                {feedback.type === 'success' ? (
                  <FiCheckCircle className="shrink-0 text-emerald-600" size={16} />
                ) : (
                  <FiAlertCircle className="shrink-0 text-rose-600" size={16} />
                )}
                <span>{feedback.text}</span>
              </div>
              <button
                onClick={() => setFeedback(null)}
                className="text-slate-400 hover:text-slate-600 cursor-pointer"
              >
                <FiX size={14} />
              </button>
            </div>
          )}

          {/* Filter Bar */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div className="relative w-full sm:w-80">
              <FiSearch className="absolute left-3.5 top-3 text-slate-400" size={15} />
              <input
                type="text"
                placeholder="Search staff user by name or role..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
              />
            </div>
            <span className="text-xs font-bold text-slate-500">
              Total Users: {reports.length}
            </span>
          </div>

          {/* User Access Table */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <th className="py-3 px-4 w-16 text-center">Sr. No.</th>
                    <th className="py-3 px-4">User Name</th>
                    <th className="py-3 px-4">Username / Login ID</th>
                    <th className="py-3 px-4">User Type</th>
                    <th className="py-3 px-4 text-center">Allocated Forms</th>
                    <th className="py-3 px-4 text-center w-36">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium">
                  {loading ? (
                    <tr>
                      <td colSpan="6" className="py-8 text-center text-slate-400">
                        Loading access reports...
                      </td>
                    </tr>
                  ) : filteredReports.length === 0 ? (
                    <tr>
                      <td colSpan="6" className="py-8 text-center text-slate-400">
                        No user access records found.
                      </td>
                    </tr>
                  ) : (
                    filteredReports.map((r, idx) => (
                      <tr key={r.spruser_id} className="hover:bg-slate-50/80 transition-colors">
                        <td className="py-3 px-4 text-center font-mono text-slate-500">
                          {idx + 1}
                        </td>
                        <td className="py-3 px-4 font-bold text-slate-900 flex items-center gap-2">
                          <span className="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">
                            <FiUser size={13} />
                          </span>
                          <span>{r.spruser_name}</span>
                        </td>
                        <td className="py-3 px-4 font-mono text-[11px] text-slate-600">
                          {r.spruser_username || '—'}
                        </td>
                        <td className="py-3 px-4">
                          <span
                            className={`px-2 py-0.5 rounded-full text-[10px] font-bold ${
                              r.spruser_usertype === 'Admin'
                                ? 'bg-[#e3f2fd] text-[#1976d2] border border-[#bbdefb]'
                                : 'bg-[#e3f2fd] text-[#1565c0] border border-[#bbdefb]'
                            }`}
                          >
                            {r.spruser_usertype || 'Staff'}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center">
                          <span className="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {r.form_count} forms
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1.5">
                            <button
                              onClick={() => handleViewAccess(r)}
                              className="px-2.5 py-1 bg-[#e3f2fd] hover:bg-blue-100 text-[#1565c0] rounded-lg text-[11px] font-bold flex items-center gap-1 transition cursor-pointer"
                              title="View permitted forms"
                            >
                              <FiEye size={12} />
                              <span>View</span>
                            </button>
                            <button
                              onClick={() =>
                                navigate(`/master/user-access?user_id=${r.spruser_id}`)
                              }
                              className="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold flex items-center gap-1 transition cursor-pointer"
                              title="Edit user permissions"
                            >
                              <FiEdit2 size={12} />
                              <span>Edit</span>
                            </button>
                            <button
                              onClick={() => handleRevoke(r.spruser_id, r.spruser_name)}
                              className="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                              title="Revoke access"
                            >
                              <FiTrash2 size={13} />
                            </button>
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

      {/* View Access Modal */}
      {selectedUser && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <div>
                <h3 className="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <FiLock className="text-[#1976d2]" />
                  <span>Permitted Screens for {selectedUser.spruser_name}</span>
                </h3>
                <span className="text-[11px] text-slate-400">
                  User ID: {selectedUser.spruser_id} • Total Forms: {userAccessList.length}
                </span>
              </div>
              <button
                onClick={() => setSelectedUser(null)}
                className="text-slate-400 hover:text-slate-700 cursor-pointer"
              >
                <FiX size={16} />
              </button>
            </div>

            <div className="max-h-80 overflow-y-auto space-y-2 pr-1">
              {modalLoading ? (
                <div className="py-8 text-center text-slate-400 text-xs">
                  Loading access list...
                </div>
              ) : userAccessList.length === 0 ? (
                <div className="py-8 text-center text-slate-400 text-xs">
                  No specific forms assigned to this user.
                </div>
              ) : (
                userAccessList.map((item, idx) => (
                  <div
                    key={item.access_id || idx}
                    className="p-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs"
                  >
                    <div>
                      <div className="font-bold text-slate-800">{item.form_name}</div>
                      <div className="text-[11px] font-mono text-slate-400">
                        /{item.form_link || '-'}
                      </div>
                    </div>
                    <span className="px-2 py-0.5 bg-[#e3f2fd] text-[#1565c0] font-bold rounded text-[10px]">
                      {item.module_name || 'General'}
                    </span>
                  </div>
                ))
              )}
            </div>

            <div className="flex items-center justify-between pt-3 border-t border-slate-100">
              <button
                onClick={() =>
                  navigate(`/master/user-access?user_id=${selectedUser.spruser_id}`)
                }
                className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-xl text-xs font-bold transition shadow-sm cursor-pointer"
              >
                Modify Permissions
              </button>
              <button
                onClick={() => setSelectedUser(null)}
                className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer"
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
