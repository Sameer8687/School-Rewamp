import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import masterService from '../../services/masterService';
import {
  FiFileText,
  FiPlus,
  FiSearch,
  FiEdit2,
  FiTrash2,
  FiCheckCircle,
  FiAlertCircle,
  FiGrid,
  FiLock,
  FiBarChart2,
  FiFilter,
  FiX,
  FiExternalLink,
} from 'react-icons/fi';

export default function FormsManagementPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [forms, setForms] = useState([]);
  const [modules, setModules] = useState([]);
  const [loading, setLoading] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedModuleFilter, setSelectedModuleFilter] = useState('all');
  const [feedback, setFeedback] = useState(null);

  // Modal State
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingForm, setEditingForm] = useState(null);
  const [formData, setFormData] = useState({
    form_name: '',
    form_link: '',
    module_id: '',
    form_code: '',
    form_type: 'General',
  });

  const loadData = async () => {
    try {
      setLoading(true);
      const [fRes, mRes] = await Promise.all([
        masterService.getForms(),
        masterService.getModules(),
      ]);
      if (fRes.data?.success) setForms(fRes.data.data || []);
      if (mRes.data?.success) setModules(mRes.data.data || []);
    } catch (err) {
      console.error('Failed to load forms data:', err);
      setFeedback({ type: 'error', text: 'Failed to load system forms.' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const openAddModal = () => {
    setEditingForm(null);
    setFormData({
      form_name: '',
      form_link: '',
      module_id: modules.length > 0 ? String(modules[0].id) : '',
      form_code: '',
      form_type: 'General',
    });
    setIsModalOpen(true);
  };

  const openEditModal = (form) => {
    setEditingForm(form);
    setFormData({
      form_name: form.form_name,
      form_link: form.form_link || form.form_url || '',
      module_id: String(form.module_id),
      form_code: form.form_code || '',
      form_type: form.form_type || 'General',
    });
    setIsModalOpen(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.form_name.trim() || !formData.module_id) return;

    try {
      if (editingForm) {
        await masterService.updateForm(editingForm.id, formData);
        setFeedback({ type: 'success', text: 'Form updated successfully.' });
      } else {
        await masterService.createForm(formData);
        setFeedback({ type: 'success', text: 'Form created successfully.' });
      }
      setIsModalOpen(false);
      loadData();
    } catch (err) {
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Operation failed. Please try again.',
      });
    }
  };

  const handleDelete = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete form "${name}"?`)) {
      return;
    }
    try {
      await masterService.deleteForm(id);
      setFeedback({ type: 'success', text: `Form "${name}" deleted successfully.` });
      loadData();
    } catch (err) {
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to delete form.',
      });
    }
  };

  const filteredForms = forms.filter((f) => {
    const matchesSearch =
      f.form_name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
      f.form_link?.toLowerCase().includes(searchQuery.toLowerCase()) ||
      f.module_name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
      f.form_code?.toLowerCase().includes(searchQuery.toLowerCase());

    const matchesModule =
      selectedModuleFilter === 'all' || String(f.module_id) === String(selectedModuleFilter);

    return matchesSearch && matchesModule;
  });

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
                <FiFileText className="text-[#1976d2]" />
                Form Management
              </h1>
              <p className="text-xs text-slate-500 mt-0.5">
                Register, map, and organize ERP screens, route endpoints, and permissions
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
                className="px-3 py-1.5 rounded-lg bg-[#1976d2] text-white shadow-sm flex items-center gap-1.5"
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
            <div className="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
              {/* Search */}
              <div className="relative w-full sm:w-64">
                <FiSearch className="absolute left-3.5 top-3 text-slate-400" size={15} />
                <input
                  type="text"
                  placeholder="Search forms or links..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              {/* Module Filter */}
              <div className="flex items-center gap-2 w-full sm:w-auto">
                <FiFilter className="text-slate-400 shrink-0" size={14} />
                <select
                  value={selectedModuleFilter}
                  onChange={(e) => setSelectedModuleFilter(e.target.value)}
                  className="w-full sm:w-48 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="all">All Modules ({forms.length})</option>
                  {modules.map((m) => (
                    <option key={m.id} value={m.id}>
                      {m.module_name}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            <button
              onClick={openAddModal}
              className="w-full sm:w-auto px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center justify-center gap-2 cursor-pointer"
            >
              <FiPlus size={15} />
              <span>Add Form</span>
            </button>
          </div>

          {/* Forms Table */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <th className="py-3 px-4 w-14 text-center">Sr.</th>
                    <th className="py-3 px-4">Form Name</th>
                    <th className="py-3 px-4">Route / URL Link</th>
                    <th className="py-3 px-4">Module</th>
                    <th className="py-3 px-4 text-center">Form Code</th>
                    <th className="py-3 px-4 text-center w-28">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium">
                  {loading ? (
                    <tr>
                      <td colSpan="6" className="py-8 text-center text-slate-400">
                        Loading forms...
                      </td>
                    </tr>
                  ) : filteredForms.length === 0 ? (
                    <tr>
                      <td colSpan="6" className="py-8 text-center text-slate-400">
                        No forms found matching your filters.
                      </td>
                    </tr>
                  ) : (
                    filteredForms.map((f, idx) => (
                      <tr key={f.id} className="hover:bg-slate-50/80 transition-colors">
                        <td className="py-3 px-4 text-center font-mono text-slate-500">
                          {idx + 1}
                        </td>
                        <td className="py-3 px-4 font-bold text-slate-900">
                          {f.form_name}
                        </td>
                        <td className="py-3 px-4 font-mono text-[11px] text-[#1565c0]">
                          <Link
                            to={`/${f.form_link || ''}`}
                            target="_blank"
                            className="inline-flex items-center gap-1 hover:underline"
                          >
                            <span>/{f.form_link || '-'}</span>
                            <FiExternalLink size={11} className="text-slate-400" />
                          </Link>
                        </td>
                        <td className="py-3 px-4">
                          <span className="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            {f.module_name || `Module #${f.module_id}`}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center font-mono text-slate-500">
                          {f.form_code || '—'}
                        </td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1.5">
                            <button
                              onClick={() => openEditModal(f)}
                              className="p-1.5 text-slate-500 hover:text-[#1976d2] hover:bg-slate-100 rounded-md transition cursor-pointer"
                              title="Edit Form"
                            >
                              <FiEdit2 size={14} />
                            </button>
                            <button
                              onClick={() => handleDelete(f.id, f.form_name)}
                              className="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-md transition cursor-pointer"
                              title="Delete Form"
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

      {/* Add / Edit Form Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <h3 className="font-bold text-slate-900 text-sm flex items-center gap-2">
                <FiFileText className="text-[#1976d2]" />
                <span>{editingForm ? 'Edit Form' : 'Register New Form'}</span>
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
                  Form Name <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  placeholder="e.g. Student Admission Form, Fees Collection"
                  value={formData.form_name}
                  onChange={(e) => setFormData({ ...formData, form_name: e.target.value })}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Module Category <span className="text-rose-500">*</span>
                </label>
                <select
                  required
                  value={formData.module_id}
                  onChange={(e) => setFormData({ ...formData, module_id: e.target.value })}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="" disabled>
                    -- Select Module --
                  </option>
                  {modules.map((m) => (
                    <option key={m.id} value={m.id}>
                      {m.module_name}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Route / URL Link <span className="text-rose-500">*</span>
                </label>
                <div className="flex items-center">
                  <span className="px-3 py-2 bg-slate-100 border border-r-0 border-slate-200 rounded-l-xl text-xs text-slate-500 font-mono">
                    /
                  </span>
                  <input
                    type="text"
                    required
                    placeholder="admission-form, fees, etc."
                    value={formData.form_link}
                    onChange={(e) => setFormData({ ...formData, form_link: e.target.value })}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-r-xl text-xs font-mono font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">
                    Form Code
                  </label>
                  <input
                    type="text"
                    placeholder="e.g. STVADM"
                    value={formData.form_code}
                    onChange={(e) => setFormData({ ...formData, form_code: e.target.value })}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                  />
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">
                    Form Type
                  </label>
                  <select
                    value={formData.form_type}
                    onChange={(e) => setFormData({ ...formData, form_type: e.target.value })}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                  >
                    <option value="General">General</option>
                    <option value="Master">Master</option>
                    <option value="Transaction">Transaction</option>
                    <option value="Report">Report</option>
                  </select>
                </div>
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
                  {editingForm ? 'Save Changes' : 'Register Form'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
