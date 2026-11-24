import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import notesService from '../../services/notesService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiBookOpen,
  FiPlus,
  FiSearch,
  FiDownload,
  FiTrash2,
  FiFileText,
  FiRefreshCw,
  FiCheckCircle,
  FiFilter,
} from 'react-icons/fi';

export default function NotesManagementPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [notes, setNotes] = useState([]);

  // Filter options
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStandard, setSelectedStandard] = useState('all');
  const [selectedDivision, setSelectedDivision] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');

  // Form modal
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    std_filter: 'I',
    div_filter: 'A',
    subject: '',
    doc_title: '',
    doc_type: 'pdf',
    downloadable: 1,
    attachment: '',
  });

  const loadNotes = async () => {
    setLoading(true);
    try {
      const res = await notesService.getNotes({
        academicYear: selectedYear,
        standard: selectedStandard,
        division: selectedDivision,
        search: searchQuery,
      });
      if (res.data?.success) {
        setNotes(res.data.notes || []);
      }
    } catch (err) {
      console.error('Failed to load notes:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          const years = res.data.options.academicYears || [];
          const classes = res.data.options.classes || [];
          setAcademicYears(years);
          setStandards(classes);

          const active = years.find((y) => y.is_active) || years[0];
          if (active) setSelectedYear(String(active.stvacdm_id));
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  useEffect(() => {
    if (selectedYear) {
      loadNotes();
    }
  }, [selectedYear, selectedStandard, selectedDivision]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.doc_title || !formData.subject) {
      alert('Please fill required fields (Title & Subject)');
      return;
    }
    try {
      const res = await notesService.createNote({
        ...formData,
        cur_acdm: selectedYear,
      });
      if (res.data?.success) {
        alert('Study note added successfully.');
        setIsModalOpen(false);
        setFormData({
          std_filter: 'I',
          div_filter: 'A',
          subject: '',
          doc_title: '',
          doc_type: 'pdf',
          downloadable: 1,
          attachment: '',
        });
        loadNotes();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to add note');
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this study note?')) return;
    try {
      const res = await notesService.deleteNote(id);
      if (res.data?.success) {
        loadNotes();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to delete note');
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 md:p-6 lg:p-8 space-y-6">
          {/* Header */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 rounded-xl bg-gradient-to-tr from-[#1976d2] to-[#1565c0] flex items-center justify-center text-white shadow-md">
                <FiBookOpen size={24} />
              </div>
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                  Academic Notes & Study Materials
                </h1>
                <p className="text-xs text-slate-500">
                  Upload, organize, and publish class-wise study materials, worksheets & syllabi
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2.5">
              <button
                onClick={() => setIsModalOpen(true)}
                className="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] hover:bg-[#1565c0] rounded-lg shadow-sm transition-all cursor-pointer"
              >
                <FiPlus size={15} />
                <span>Upload New Note</span>
              </button>
            </div>
          </div>

          {/* Filters & Search */}
          <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-3">
            <div className="flex items-center flex-wrap gap-2.5">
              <select
                value={selectedYear}
                onChange={(e) => setSelectedYear(e.target.value)}
                className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 cursor-pointer"
              >
                {academicYears.map((y) => (
                  <option key={y.stvacdm_id} value={y.stvacdm_id}>
                    Session {y.stvacdm_year || y.stvacdm_id}
                  </option>
                ))}
              </select>

              <select
                value={selectedStandard}
                onChange={(e) => setSelectedStandard(e.target.value)}
                className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 cursor-pointer"
              >
                <option value="all">All Classes</option>
                {standards.map((c) => (
                  <option key={c.STVCLAS_standard || c.id} value={c.STVCLAS_standard}>
                    Std {c.STVCLAS_standard}
                  </option>
                ))}
              </select>

              <select
                value={selectedDivision}
                onChange={(e) => setSelectedDivision(e.target.value)}
                className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 cursor-pointer"
              >
                <option value="all">All Divisions</option>
                {['A', 'B', 'C', 'D', 'E'].map((d) => (
                  <option key={d} value={d}>
                    Div {d}
                  </option>
                ))}
              </select>
            </div>

            <div className="relative min-w-[220px]">
              <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={14} />
              <input
                type="text"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                onKeyDown={(e) => e.key === 'Enter' && loadNotes()}
                placeholder="Search note by title or subject..."
                className="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
              />
            </div>
          </div>

          {/* Table */}
          <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {loading ? (
              <div className="py-20 flex flex-col items-center justify-center text-slate-400">
                <FiRefreshCw className="animate-spin mb-3 text-[#1976d2]" size={28} />
                <p className="text-sm font-medium">Loading study notes...</p>
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                      <th className="py-3 px-4">Document Title</th>
                      <th className="py-3 px-4">Subject</th>
                      <th className="py-3 px-4">Standard & Div</th>
                      <th className="py-3 px-4">Format</th>
                      <th className="py-3 px-4">Upload Date</th>
                      <th className="py-3 px-4 text-center">Downloadable</th>
                      <th className="py-3 px-4 text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {notes.length === 0 ? (
                      <tr>
                        <td colSpan="7" className="py-8 text-center text-slate-400">
                          No notes uploaded yet for this class or subject.
                        </td>
                      </tr>
                    ) : (
                      notes.map((note) => (
                        <tr key={note.id} className="hover:bg-slate-50/80 transition-colors">
                          <td className="py-3 px-4 font-bold text-slate-900 flex items-center gap-2">
                            <FiFileText className="text-[#1976d2]" size={16} />
                            <span>{note.doc_title}</span>
                          </td>
                          <td className="py-3 px-4 font-semibold text-slate-700">{note.subject}</td>
                          <td className="py-3 px-4">
                            Std {note.std_filter} {note.div_filter ? `(${note.div_filter})` : ''}
                          </td>
                          <td className="py-3 px-4 uppercase text-slate-500 font-mono text-[10px]">
                            {note.doc_type || 'PDF'}
                          </td>
                          <td className="py-3 px-4 text-slate-500 text-[11px]">
                            {note.created_at ? note.created_at.slice(0, 10) : '-'}
                          </td>
                          <td className="py-3 px-4 text-center">
                            {note.downloadable ? (
                              <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Yes
                              </span>
                            ) : (
                              <span className="text-slate-400 text-[10px]">No</span>
                            )}
                          </td>
                          <td className="py-3 px-4 text-center">
                            <div className="flex items-center justify-center gap-1.5">
                              {note.attachment && (
                                <a
                                  href={note.attachment}
                                  target="_blank"
                                  rel="noreferrer"
                                  className="p-1.5 text-[#1976d2] hover:bg-slate-100 rounded-md transition-colors"
                                  title="Download File"
                                >
                                  <FiDownload size={15} />
                                </a>
                              )}
                              <button
                                onClick={() => handleDelete(note.id)}
                                className="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-md transition-colors cursor-pointer"
                                title="Delete"
                              >
                                <FiTrash2 size={15} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </main>
      </div>

      {/* Upload Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <h3 className="font-bold text-slate-900 text-base">Upload Study Note</h3>
              <button
                onClick={() => setIsModalOpen(false)}
                className="text-slate-400 hover:text-slate-700 text-lg cursor-pointer"
              >
                ✕
              </button>
            </div>

            <form onSubmit={handleSubmit} className="space-y-3 text-xs">
              <div>
                <label className="block font-bold text-slate-700 mb-1">
                  Document Title <span className="text-rose-500">*</span>:
                </label>
                <input
                  type="text"
                  required
                  value={formData.doc_title}
                  onChange={(e) => setFormData({ ...formData, doc_title: e.target.value })}
                  placeholder="e.g. Chapter 4 Science Question Bank"
                  className="w-full p-2 border border-slate-200 rounded-lg"
                />
              </div>

              <div className="grid grid-cols-2 gap-2.5">
                <div>
                  <label className="block font-bold text-slate-700 mb-1">Standard:</label>
                  <select
                    value={formData.std_filter}
                    onChange={(e) => setFormData({ ...formData, std_filter: e.target.value })}
                    className="w-full p-2 border border-slate-200 rounded-lg"
                  >
                    {standards.map((c) => (
                      <option key={c.STVCLAS_standard || c.id} value={c.STVCLAS_standard}>
                        Std {c.STVCLAS_standard}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block font-bold text-slate-700 mb-1">Division:</label>
                  <select
                    value={formData.div_filter}
                    onChange={(e) => setFormData({ ...formData, div_filter: e.target.value })}
                    className="w-full p-2 border border-slate-200 rounded-lg"
                  >
                    {['A', 'B', 'C', 'D'].map((d) => (
                      <option key={d} value={d}>
                        Div {d}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <label className="block font-bold text-slate-700 mb-1">
                  Subject <span className="text-rose-500">*</span>:
                </label>
                <input
                  type="text"
                  required
                  value={formData.subject}
                  onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                  placeholder="e.g. Mathematics, English, History"
                  className="w-full p-2 border border-slate-200 rounded-lg"
                />
              </div>

              <div>
                <label className="block font-bold text-slate-700 mb-1">Document Attachment URL / Path:</label>
                <input
                  type="text"
                  value={formData.attachment}
                  onChange={(e) => setFormData({ ...formData, attachment: e.target.value })}
                  placeholder="e.g. /uploads/notes/maths_ch4.pdf"
                  className="w-full p-2 border border-slate-200 rounded-lg"
                />
              </div>

              <div className="flex items-center gap-2 pt-1">
                <input
                  type="checkbox"
                  id="dlCheck"
                  checked={formData.downloadable === 1}
                  onChange={(e) => setFormData({ ...formData, downloadable: e.target.checked ? 1 : 0 })}
                  className="rounded text-[#1976d2]"
                />
                <label htmlFor="dlCheck" className="text-xs text-slate-700 font-semibold cursor-pointer">
                  Allow students to download PDF
                </label>
              </div>

              <div className="flex items-center justify-end gap-2 pt-3 border-t border-slate-200">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white font-semibold rounded-lg cursor-pointer"
                >
                  Save Note
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
