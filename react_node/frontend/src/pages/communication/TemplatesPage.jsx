import React, { useState, useEffect } from 'react';
import communicationService from '../../services/communicationService';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiMessageSquare,
  FiPlus,
  FiEdit2,
  FiTrash2,
  FiSave,
  FiCheckCircle,
  FiAlertCircle,
  FiCopy,
  FiX,
} from 'react-icons/fi';

export default function TemplatesPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  const [templates, setTemplates] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editId, setEditId] = useState(null);
  const [templateText, setTemplateText] = useState('');

  useEffect(() => {
    loadTemplates();
  }, []);

  const loadTemplates = async () => {
    try {
      setLoading(true);
      const res = await communicationService.getTemplates();
      setTemplates(res.data?.data || []);
    } catch (err) {
      console.error('Failed to load templates:', err);
      setMessage({ text: 'Failed to load message templates.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const handleOpenAdd = () => {
    setEditId(null);
    setTemplateText('');
    setIsModalOpen(true);
  };

  const handleOpenEdit = (t) => {
    setEditId(t.id);
    setTemplateText(t.text);
    setIsModalOpen(true);
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!templateText.trim()) return;

    try {
      setSaving(true);
      setMessage({ text: '', type: '' });
      if (editId) {
        await communicationService.updateTemplate(editId, { text: templateText.trim() });
        setMessage({ text: 'Template updated successfully!', type: 'success' });
      } else {
        await communicationService.createTemplate({ text: templateText.trim() });
        setMessage({ text: 'New message template created!', type: 'success' });
      }
      setIsModalOpen(false);
      loadTemplates();
    } catch (err) {
      console.error('Save template error:', err);
      setMessage({ text: 'Failed to save template.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this message template?')) return;
    try {
      await communicationService.deleteTemplate(id);
      setMessage({ text: 'Template deleted successfully!', type: 'success' });
      loadTemplates();
    } catch (err) {
      console.error('Delete template error:', err);
      setMessage({ text: 'Failed to delete template.', type: 'error' });
    }
  };

  const handleCopy = (text) => {
    navigator.clipboard.writeText(text);
    alert('Template text copied to clipboard!');
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-6xl mx-auto space-y-6">
            {/* Page Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                  <FiMessageSquare className="text-[#1976d2]" />
                  SMS & WhatsApp Notification Templates
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Manage standard message drafts for fee alerts, attendance notifications, and announcements
                </p>
              </div>

              <button
                onClick={handleOpenAdd}
                className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition"
              >
                <FiPlus /> New Template
              </button>
            </div>

            {/* Notification alert */}
            {message.text && (
              <div
                className={`p-3.5 rounded-lg text-xs flex items-center gap-2 ${
                  message.type === 'success'
                    ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                    : 'bg-rose-50 text-rose-800 border border-rose-200'
                }`}
              >
                {message.type === 'success' ? <FiCheckCircle /> : <FiAlertCircle />}
                <span>{message.text}</span>
              </div>
            )}

            {/* Templates Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
              {templates.length === 0 ? (
                <div className="col-span-2 bg-white rounded-xl border border-slate-200 p-12 text-center text-slate-400">
                  {loading ? 'Loading message templates...' : 'No templates created yet.'}
                </div>
              ) : (
                templates.map((t) => (
                  <div
                    key={t.id}
                    className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between"
                  >
                    <div>
                      <div className="flex items-center justify-between pb-2 mb-2 border-b border-slate-100 text-[11px] text-slate-400">
                        <span className="font-mono">Template #{t.id}</span>
                        <span>{t.created_by || 'Admin'}</span>
                      </div>
                      <p className="text-xs text-slate-800 whitespace-pre-wrap leading-relaxed">{t.text}</p>
                    </div>

                    <div className="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                      <button
                        onClick={() => handleCopy(t.text)}
                        className="text-xs text-[#1976d2] hover:underline flex items-center gap-1 font-medium"
                      >
                        <FiCopy size={12} /> Copy Text
                      </button>

                      <div className="space-x-1">
                        <button
                          onClick={() => handleOpenEdit(t)}
                          className="p-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition"
                          title="Edit Template"
                        >
                          <FiEdit2 size={13} />
                        </button>
                        <button
                          onClick={() => handleDelete(t.id)}
                          className="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition"
                          title="Delete Template"
                        >
                          <FiTrash2 size={13} />
                        </button>
                      </div>
                    </div>
                  </div>
                ))
              )}
            </div>

            {/* Modal: Add / Edit Template */}
            {isModalOpen && (
              <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
                <div className="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden">
                  <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                      <FiMessageSquare className="text-[#1976d2]" />
                      {editId ? 'Edit Message Template' : 'Create Message Template'}
                    </h3>
                    <button onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                      <FiX size={18} />
                    </button>
                  </div>

                  <form onSubmit={handleSave} className="p-6 space-y-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Message Content / Text
                      </label>
                      <textarea
                        value={templateText}
                        onChange={(e) => setTemplateText(e.target.value)}
                        rows="5"
                        placeholder="e.g. Dear Parent, this is to notify that the school will remain closed tomorrow on account of..."
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div className="pt-3 border-t border-slate-200 flex justify-end gap-2">
                      <button
                        type="button"
                        onClick={() => setIsModalOpen(false)}
                        className="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        disabled={saving}
                        className="flex items-center gap-1.5 px-5 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50"
                      >
                        <FiSave /> {saving ? 'Saving...' : 'Save Template'}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
