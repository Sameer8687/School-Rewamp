import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import masterService from '../../services/masterService';
import {
  FiGrid,
  FiPlus,
  FiSearch,
  FiEdit2,
  FiTrash2,
  FiCheckCircle,
  FiAlertCircle,
  FiFolder,
  FiFileText,
  FiUsers,
  FiLock,
  FiBarChart2,
  FiX,
} from 'react-icons/fi';

export default function ModulesManagementPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [modules, setModules] = useState([]);
  const [loading, setLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [feedback, setFeedback] = useState(null);

  // Modal State
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingModule, setEditingModule] = useState(null);
  const [formData, setFormData] = useState({
    module_name: '',
    icon: 'folder',
    module_description: '',
  });

  const loadModules = async () => {
    try {
      setLoading(true);
      const res = await masterService.getModules();
      if (res.data?.success) {
        setModules(res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load modules:', err);
      setFeedback({ type: 'error', text: 'Failed to load system modules.' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadModules();
  }, []);

  const openAddModal = () => {
    setEditingModule(null);
    setFormData({ module_name: '', icon: 'folder', module_description: '' });
    setIsModalOpen(true);
  };

  const openEditModal = (mod) => {
    setEditingModule(mod);
    setFormData({
      module_name: mod.module_name,
      icon: mod.icon || 'folder',
      module_description: mod.module_description || '',
    });
    setIsModalOpen(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.module_name.trim()) return;

    try {
      if (editingModule) {
        await masterService.updateModule(editingModule.id, formData);
        setFeedback({ type: 'success', text: 'Module updated successfully.' });
      } else {
        await masterService.createModule(formData);
        setFeedback({ type: 'success', text: 'Module created successfully.' });
      }
      setIsModalOpen(false);
      loadModules();
    } catch (err) {
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Operation failed. Please try again.',
      });
    }
  };

  const handleDelete = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete module "${name}" and all its linked forms?`)) {
      return;
    }
    try {
      await masterService.deleteModule(id);
      setFeedback({ type: 'success', text: `Module "${name}" deleted successfully.` });
      loadModules();
    } catch (err) {
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to delete module.',
      });
    }
  };

  const filteredModules = modules.filter((m) =>
    m.module_name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
    m.module_description?.toLowerCase().includes(searchQuery.toLowerCase())
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
                <FiGrid className="text-[#1976d2]" />
                Module Management
              </h1>
              <p className="text-xs text-slate-500 mt-0.5">
                Configure ERP top-level application modules and icon representations
              </p>
            </div>

            {/* Sub-nav quick tabs */}
            <div className="flex flex-wrap items-center gap-1.5 sm:gap-2 bg-white p-1 rounded-xl border border-slate-200 shadow-sm text-xs font-semibold">
              <Link
                to="/master/modules"
                className="px-3 py-1.5 rounded-lg bg-[#1976d2] text-white shadow-sm flex items-center gap-1.5"
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
                className="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-1.5"
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

          {/* Action & Filter Bar */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div className="relative w-full sm:w-72">
              <FiSearch className="absolute left-3.5 top-3 text-slate-400" size={15} />
              <input
                type="text"
                placeholder="Search modules..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
              />
            </div>

            <button
              onClick={openAddModal}
              className="w-full sm:w-auto px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2 cursor-pointer"
            >
              <FiPlus size={15} />
              <span>Add Module</span>
            </button>
          </div>

          {/* Modules Table */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <th className="py-3 px-4 w-16 text-center">Sr. No.</th>
                    <th className="py-3 px-4">Module Name</th>
                    <th className="py-3 px-4">Icon Name</th>
                    <th className="py-3 px-4">Description</th>
                    <th className="py-3 px-4 text-center">Linked Forms</th>
                    <th className="py-3 px-4 text-center w-28">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium">
                  {loading ? (
                    <tr>
                      <td colSpan="6" className="py-8 text-center text-slate-400">
                        Loading modules...
                      </td>
                    </tr>
                  ) : filteredModules.length === 0 ? (
                    <tr>
                      <td colSpan="6" className="py-8 text-center text-slate-400">
                        No modules found matching your search.
                      </td>
                    </tr>
                  ) : (
                    filteredModules.map((m, idx) => (
                      <tr key={m.id} className="hover:bg-slate-50/80 transition-colors">
                        <td className="py-3 px-4 text-center font-mono text-slate-500">
                          {idx + 1}
                        </td>
                        <td className="py-3 px-4 font-bold text-slate-900 flex items-center gap-2">
                          <span className="w-7 h-7 rounded-lg bg-[#e3f2fd] text-[#1976d2] flex items-center justify-center font-bold text-xs">
                            <FiFolder size={14} />
                          </span>
                          <span>{m.module_name}</span>
                        </td>
                        <td className="py-3 px-4 font-mono text-[11px] text-slate-600">
                          <span className="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-slate-700">
                            {m.icon || 'folder'}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-slate-500 max-w-md truncate">
                          {m.module_description || '—'}
                        </td>
                        <td className="py-3 px-4 text-center">
                          <span className="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#e3f2fd] text-[#1565c0] border border-[#bbdefb]">
                            {m.form_count ?? 0} forms
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1.5">
                            <button
                              onClick={() => openEditModal(m)}
                              className="p-1.5 text-slate-500 hover:text-[#1976d2] hover:bg-slate-100 rounded-md transition cursor-pointer"
                              title="Edit Module"
                            >
                              <FiEdit2 size={14} />
                            </button>
                            <button
                              onClick={() => handleDelete(m.id, m.module_name)}
                              className="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-md transition cursor-pointer"
                              title="Delete Module"
                            >
                              <FiTrash2 size={14} />
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

      {/* Add / Edit Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <h3 className="font-bold text-slate-900 text-sm flex items-center gap-2">
                <FiFolder className="text-[#1976d2]" />
                <span>{editingModule ? 'Edit Module' : 'Create New Module'}</span>
              </h3>
              <button
                onClick={() => setIsModalOpen(false)}
                className="text-slate-400 hover:text-slate-700 cursor-pointer"
              >
                <FiX size={16} />
              </button>
            </div>

            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Module Name <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  placeholder="e.g. Student, Fees, Examinations"
                  value={formData.module_name}
                  onChange={(e) => setFormData({ ...formData, module_name: e.target.value })}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Icon Identifier
                </label>
                <input
                  type="text"
                  placeholder="e.g. users, dollar-sign, book, file-text"
                  value={formData.icon}
                  onChange={(e) => setFormData({ ...formData, icon: e.target.value })}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
                <span className="text-[10px] text-slate-400 block mt-1">
                  Feather icon name for sidebar display
                </span>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Description
                </label>
                <textarea
                  rows="3"
                  placeholder="Briefly describe what forms this module contains..."
                  value={formData.module_description}
                  onChange={(e) =>
                    setFormData({ ...formData, module_description: e.target.value })
                  }
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div className="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-xl text-xs font-bold cursor-pointer transition shadow-sm"
                >
                  {editingModule ? 'Save Changes' : 'Create Module'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
