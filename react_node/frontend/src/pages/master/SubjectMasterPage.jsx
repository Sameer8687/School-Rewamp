import React, { useState, useEffect } from 'react';
import masterService from '../../services/masterService';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiBook,
  FiPlus,
  FiEdit2,
  FiTrash2,
  FiCheckCircle,
  FiAlertCircle,
  FiFilter,
  FiX,
  FiSave,
} from 'react-icons/fi';

const CLASSES = ['NURSERY', 'JR.KG', 'SR.KG', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

export default function SubjectMasterPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  // Filter
  const [selectedClass, setSelectedClass] = useState('I');
  const [subjects, setSubjects] = useState([]);

  // Modal State
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editId, setEditId] = useState(null);
  const [subjectName, setSubjectName] = useState('');
  const [modalStandard, setModalStandard] = useState('I');

  useEffect(() => {
    loadSubjects(selectedClass);
  }, [selectedClass]);

  const loadSubjects = async (std) => {
    try {
      setLoading(true);
      const res = await masterService.getSubjects({ standard: std });
      setSubjects(res.data?.data || []);
    } catch (err) {
      console.error('Failed to load subjects:', err);
      setMessage({ text: 'Failed to load subjects.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const handleOpenAdd = () => {
    setEditId(null);
    setSubjectName('');
    setModalStandard(selectedClass);
    setIsModalOpen(true);
  };

  const handleOpenEdit = (sub) => {
    setEditId(sub.id);
    setSubjectName(sub.name);
    setModalStandard(sub.standard);
    setIsModalOpen(true);
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!subjectName.trim() || !modalStandard) return;

    try {
      setSaving(true);
      setMessage({ text: '', type: '' });

      if (editId) {
        await masterService.updateSubject(editId, {
          name: subjectName.trim(),
          standard: modalStandard,
        });
        setMessage({ text: 'Subject updated successfully!', type: 'success' });
      } else {
        await masterService.createSubject({
          name: subjectName.trim(),
          standard: modalStandard,
        });
        setMessage({ text: 'Subject created successfully!', type: 'success' });
      }

      setIsModalOpen(false);
      loadSubjects(selectedClass);
    } catch (err) {
      console.error('Save subject error:', err);
      setMessage({ text: 'Failed to save subject.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete subject "${name}"?`)) return;

    try {
      await masterService.deleteSubject(id);
      setMessage({ text: `Subject "${name}" deleted successfully!`, type: 'success' });
      loadSubjects(selectedClass);
    } catch (err) {
      console.error('Delete subject error:', err);
      setMessage({ text: 'Failed to delete subject.', type: 'error' });
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-7xl mx-auto space-y-6">
            {/* Page Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                  <FiBook className="text-[#1976d2]" />
                  Curriculum Subject Master
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Manage academic subjects, syllabus courses, and class mappings
                </p>
              </div>

              <button
                onClick={handleOpenAdd}
                className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition cursor-pointer"
              >
                <FiPlus /> Add Subject
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

            {/* Filter Section */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
              <div className="max-w-xs">
                <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                  Filter by Standard / Class
                </label>
                <select
                  value={selectedClass}
                  onChange={(e) => setSelectedClass(e.target.value)}
                  className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                >
                  {CLASSES.map((cls) => (
                    <option key={cls} value={cls}>
                      Std {cls}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            {/* Subjects Table */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 className="text-sm font-bold text-slate-800">
                  Subjects for Std {selectedClass} ({subjects.length})
                </h2>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                      <th className="py-3 px-4 w-16 text-center">#</th>
                      <th className="py-3 px-4">Subject Name</th>
                      <th className="py-3 px-4 w-32">Class / Standard</th>
                      <th className="py-3 px-4 w-32">Added By</th>
                      <th className="py-3 px-4 w-32 text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {subjects.length === 0 ? (
                      <tr>
                        <td colSpan="5" className="py-8 text-center text-slate-400">
                          {loading ? 'Loading subjects...' : 'No subjects assigned to this standard.'}
                        </td>
                      </tr>
                    ) : (
                      subjects.map((sub, idx) => (
                        <tr key={sub.id} className="hover:bg-slate-50 transition-colors">
                          <td className="py-3 px-4 text-center font-mono text-slate-400">{idx + 1}</td>
                          <td className="py-3 px-4 font-bold text-slate-800 text-sm">{sub.name}</td>
                          <td className="py-3 px-4 font-semibold text-slate-600">Std {sub.standard}</td>
                          <td className="py-3 px-4 text-slate-500 capitalize">{sub.added_by || 'Admin'}</td>
                          <td className="py-3 px-4 text-center space-x-2">
                            <button
                              onClick={() => handleOpenEdit(sub)}
                              className="p-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition"
                              title="Edit Subject"
                            >
                              <FiEdit2 size={13} />
                            </button>
                            <button
                              onClick={() => handleDelete(sub.id, sub.name)}
                              className="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition"
                              title="Delete Subject"
                            >
                              <FiTrash2 size={13} />
                            </button>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Modal: Add / Edit Subject */}
            {isModalOpen && (
              <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
                <div className="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
                  <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                      <FiBook className="text-[#1976d2]" />
                      {editId ? 'Edit Subject' : 'Add New Subject'}
                    </h3>
                    <button onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                      <FiX size={18} />
                    </button>
                  </div>

                  <form onSubmit={handleSave} className="p-6 space-y-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Class / Standard
                      </label>
                      <select
                        value={modalStandard}
                        onChange={(e) => setModalStandard(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                        required
                      >
                        {CLASSES.map((cls) => (
                          <option key={cls} value={cls}>
                            Std {cls}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Subject Name
                      </label>
                      <input
                        type="text"
                        value={subjectName}
                        onChange={(e) => setSubjectName(e.target.value)}
                        placeholder="e.g. Mathematics, English Literature, Science"
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
                        <FiSave /> {saving ? 'Saving...' : 'Save Subject'}
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
