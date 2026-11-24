import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import teacherService from '../../services/teacherService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import lessonApprovalService from '../../services/lessonApprovalService.js';
import {
  FiBookOpen,
  FiPlus,
  FiTrash2,
  FiEdit2,
  FiSave,
  FiRefreshCw,
  FiCheckCircle,
  FiAlertCircle,
  FiX,
} from 'react-icons/fi';

export default function LessonPlanPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);

  // Filters
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStandard, setSelectedStandard] = useState('all');
  const [selectedSubject, setSelectedSubject] = useState('all');

  // Helper for standard names
  const getStdName = (s) => (typeof s === 'string' ? s : s?.standard || s?.name || s?.stvfees_class || '');

  // Lessons list
  const [lessons, setLessons] = useState([]);

  // Modal / Form state for Add/Edit
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formStandard, setFormStandard] = useState('');
  const [formSubject, setFormSubject] = useState('');
  const [formLessonNo, setFormLessonNo] = useState('');
  const [formLessonName, setFormLessonName] = useState('');
  const [formType, setFormType] = useState('Theory');
  const [subjectSuggestions, setSubjectSuggestions] = useState([]);
  const [saving, setSaving] = useState(false);
  const [feedback, setFeedback] = useState(null);

  useEffect(() => {
    async function loadFormSubjects() {
      if (!formStandard) return;
      try {
        const res = await lessonApprovalService.getSubjects({
          class: formStandard,
          academicYear: selectedYear,
        });
        const list = res.data?.subjects || res.subjects || [];
        setSubjectSuggestions(list.map((s) => s.stvsubj_subject).filter(Boolean));
      } catch (err) {
        console.error('Failed to load subject suggestions:', err);
      }
    }
    loadFormSubjects();
  }, [formStandard, selectedYear]);

  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        const years = res.academicYears || res.data?.academicYears || res.data?.options?.academicYears || [];
        const classes = res.classes || res.data?.classes || res.data?.options?.classes || [];
        setAcademicYears(years);
        setStandards(classes);

        const active = years.find((y) => y.is_active) || years[0];
        if (active) setSelectedYear(String(active.stvacdm_id || active.id));
        if (classes.length > 0) setFormStandard(getStdName(classes[0]));
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  useEffect(() => {
    if (!selectedYear) return;
    loadLessons();
  }, [selectedYear, selectedStandard, selectedSubject]);

  async function loadLessons() {
    setLoading(true);
    setFeedback(null);
    try {
      const res = await teacherService.getLessons({
        academic_year: selectedYear !== 'all' ? selectedYear : undefined,
        standard: selectedStandard !== 'all' ? selectedStandard : undefined,
        subject: selectedSubject !== 'all' ? selectedSubject : undefined,
      });
      if (res.data?.success) {
        setLessons(res.data.lessons || []);
      }
    } catch (err) {
      console.error('Failed to load lessons:', err);
    } finally {
      setLoading(false);
    }
  }

  const availableSubjects = [
    ...new Set([
      ...lessons.map((l) => l.subject).filter(Boolean),
      ...subjectSuggestions,
    ]),
  ];

  function handleOpenCreate() {
    setEditingId(null);
    setFormLessonNo('');
    setFormLessonName('');
    setFormSubject('');
    setFormType('Theory');
    setShowModal(true);
  }

  function handleEditLesson(l) {
    setEditingId(l.id);
    setFormStandard(l.standard);
    setFormSubject(l.subject);
    setFormLessonNo(String(l.lesson_no));
    setFormLessonName(l.lesson_name);
    setFormType(l.type || 'Theory');
    setShowModal(true);
  }

  async function handleSaveLesson(e) {
    e.preventDefault();
    if (!formStandard || !formSubject || !formLessonNo || !formLessonName) {
      alert('All fields are required');
      return;
    }

    setSaving(true);
    setFeedback(null);
    try {
      const res = await teacherService.saveLesson({
        id: editingId,
        academic_year: selectedYear,
        standard: formStandard,
        subject: formSubject,
        lesson_no: formLessonNo,
        lesson_name: formLessonName,
        type: formType,
      });

      if (res.data?.success) {
        setFeedback({
          type: 'success',
          text: editingId ? 'Lesson plan updated successfully!' : 'Lesson plan added successfully!',
        });
        setShowModal(false);
        await loadLessons();
      }
    } catch (err) {
      console.error('Failed to save lesson:', err);
      setFeedback({ type: 'error', text: 'Failed to save lesson' });
    } finally {
      setSaving(false);
    }
  }

  async function handleDeleteLesson(id) {
    if (!window.confirm('Are you sure you want to delete this lesson plan?')) return;
    try {
      const res = await teacherService.deleteLesson(id);
      if (res.data?.success) {
        setFeedback({ type: 'success', text: 'Lesson deleted successfully' });
        await loadLessons();
      }
    } catch (err) {
      console.error('Delete error:', err);
      setFeedback({ type: 'error', text: 'Failed to delete lesson' });
    }
  }

  return (
    <div className="flex h-screen bg-[#F7F7F5] font-sans text-[#202020] antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#E7E7E4]">
            <div>
              <div className="flex items-center gap-2.5">
                <span className="p-2.5 bg-[#e3f2fd] text-[#1976d2] rounded-xl shadow-sm">
                  <FiBookOpen className="w-5 h-5" />
                </span>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight text-[#202020]">
                  Curriculum & Lesson Plans
                </h1>
              </div>
              <p className="text-xs sm:text-sm text-[#73736F] mt-1">
                Manage syllabus progress, chapters, and subject lesson plans for each class
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={loadLessons}
                className="p-2.5 rounded-xl border border-[#E7E7E4] bg-white hover:bg-[#F5F5F3] text-[#73736F] hover:text-[#202020] transition cursor-pointer shadow-sm"
                title="Refresh"
              >
                <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin text-[#1976d2]' : ''}`} />
              </button>
              <button
                onClick={handleOpenCreate}
                className="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1976d2] hover:bg-[#1565c0] active:bg-[#0d47a1] text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
              >
                <FiPlus className="w-4 h-4" />
                <span>Add Lesson</span>
              </button>
            </div>
          </div>

          {/* Feedback */}
          {feedback && (
            <div
              className={`p-3.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2.5 border ${
                feedback.type === 'success'
                  ? 'bg-[#e8f5e9] text-[#2e7d32] border-[#c8e6c9]'
                  : 'bg-[#ffebee] text-[#c62828] border-[#ffcdd2]'
              }`}
            >
              {feedback.type === 'success' ? (
                <FiCheckCircle className="w-4 h-4 text-[#2e7d32] shrink-0" />
              ) : (
                <FiAlertCircle className="w-4 h-4 text-[#c62828] shrink-0" />
              )}
              <span>{feedback.text}</span>
            </div>
          )}

          {/* Filters Bar */}
          <div className="bg-white p-4 rounded-2xl shadow-sm border border-[#E7E7E4] flex flex-wrap items-center gap-3 sm:gap-4">
            <div className="flex items-center gap-2">
              <span className="text-xs font-semibold text-[#73736F]">Academic Year:</span>
              <select
                value={selectedYear}
                onChange={(e) => setSelectedYear(e.target.value)}
                className="px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
              >
                {academicYears.map((y) => (
                  <option key={y.stvacdm_id || y.id} value={y.stvacdm_id || y.id}>
                    {y.stvacdm_year || y.year}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex items-center gap-2">
              <span className="text-xs font-semibold text-[#73736F]">Standard:</span>
              <select
                value={selectedStandard}
                onChange={(e) => setSelectedStandard(e.target.value)}
                className="px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
              >
                <option value="all">All Standards</option>
                {standards.map((c, i) => {
                  const val = getStdName(c);
                  return (
                    <option key={i} value={val}>
                      {val}
                    </option>
                  );
                })}
              </select>
            </div>

            {availableSubjects.length > 0 && (
              <div className="flex items-center gap-2">
                <span className="text-xs font-semibold text-[#73736F]">Subject:</span>
                <select
                  value={selectedSubject}
                  onChange={(e) => setSelectedSubject(e.target.value)}
                  className="px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
                >
                  <option value="all">All Subjects</option>
                  {availableSubjects.map((sub, i) => (
                    <option key={i} value={sub}>
                      {sub}
                    </option>
                  ))}
                </select>
              </div>
            )}

            <button
              onClick={loadLessons}
              className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-xl text-xs sm:text-sm font-semibold transition cursor-pointer shadow-sm"
            >
              Filter
            </button>
          </div>

          {/* Lessons Table */}
          <div className="bg-white rounded-2xl shadow-sm border border-[#E7E7E4] overflow-hidden">
            <div className="px-5 py-4 border-b border-[#E7E7E4] flex items-center justify-between">
              <h3 className="text-sm font-bold text-[#202020]">
                Lesson Plan Records ({lessons.length} plans)
              </h3>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-[#F7F7F5] border-b border-[#E7E7E4] text-[11px] font-bold text-[#73736F] uppercase tracking-wider">
                    <th className="py-3 px-4">#</th>
                    <th className="py-3 px-4">Standard</th>
                    <th className="py-3 px-4">Subject</th>
                    <th className="py-3 px-4">Lesson No</th>
                    <th className="py-3 px-4">Chapter / Topic Title</th>
                    <th className="py-3 px-4">Type</th>
                    <th className="py-3 px-4 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-[#E7E7E4] font-medium text-[#202020]">
                  {loading ? (
                    <tr>
                      <td colSpan="7" className="py-12 text-center text-[#73736F]">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-2" />
                        Loading curriculum lessons...
                      </td>
                    </tr>
                  ) : lessons.length > 0 ? (
                    lessons.map((l, idx) => (
                      <tr key={l.id} className="hover:bg-[#F5F5F3] transition-colors">
                        <td className="py-3 px-4 text-[#73736F] font-mono">{idx + 1}</td>
                        <td className="py-3 px-4 font-bold text-[#202020]">{l.standard}</td>
                        <td className="py-3 px-4 font-bold text-[#1976d2]">{l.subject}</td>
                        <td className="py-3 px-4 font-mono font-bold">{l.lesson_no}</td>
                        <td className="py-3 px-4 font-medium text-[#202020]">{l.lesson_name}</td>
                        <td className="py-3 px-4">
                          <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F1F1EF] text-[#202020] border border-[#E7E7E4]">
                            {l.type || 'Theory'}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1.5">
                            <button
                              onClick={() => handleEditLesson(l)}
                              className="p-1.5 bg-[#e3f2fd] text-[#1976d2] hover:bg-[#bbdefb] rounded-lg text-xs font-semibold transition cursor-pointer"
                              title="Edit"
                            >
                              <FiEdit2 className="w-3.5 h-3.5" />
                            </button>
                            <button
                              onClick={() => handleDeleteLesson(l.id)}
                              className="p-1.5 bg-[#ffebee] text-[#c62828] hover:bg-[#ffcdd2] rounded-lg text-xs font-semibold transition cursor-pointer"
                              title="Delete"
                            >
                              <FiTrash2 className="w-3.5 h-3.5" />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="7" className="py-12 text-center text-[#73736F]">
                        No lesson plans configured for the selected filters.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>

      {/* Add / Edit Lesson Modal */}
      {showModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[1px]">
          <div className="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-[#E7E7E4]">
            <div className="flex items-center justify-between pb-3 border-b border-[#E7E7E4]">
              <h3 className="font-bold text-base text-[#202020] flex items-center gap-2">
                <FiBookOpen className="text-[#1976d2]" />
                <span>{editingId ? 'Edit Lesson Plan' : 'Add New Lesson Plan'}</span>
              </h3>
              <button
                onClick={() => setShowModal(false)}
                className="text-[#73736F] hover:text-[#202020] cursor-pointer"
              >
                <FiX className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSaveLesson} className="space-y-4 pt-4">
              <div>
                <label className="block text-xs font-semibold text-[#73736F] mb-1">Standard</label>
                <select
                  value={formStandard}
                  onChange={(e) => setFormStandard(e.target.value)}
                  className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                >
                  {standards.map((c, i) => {
                    const val = getStdName(c);
                    return (
                      <option key={i} value={val}>
                        {val}
                      </option>
                    );
                  })}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#73736F] mb-1">Subject</label>
                <input
                  type="text"
                  list="form-subject-list"
                  required
                  placeholder="e.g. Mathematics, Science, English"
                  value={formSubject}
                  onChange={(e) => setFormSubject(e.target.value)}
                  className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                />
                <datalist id="form-subject-list">
                  {subjectSuggestions.map((subj, idx) => (
                    <option key={idx} value={subj} />
                  ))}
                </datalist>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">
                    Lesson / Chapter No.
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. 1"
                    value={formLessonNo}
                    onChange={(e) => setFormLessonNo(e.target.value)}
                    className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#73736F] mb-1">Type</label>
                  <select
                    value={formType}
                    onChange={(e) => setFormType(e.target.value)}
                    className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                  >
                    <option value="Theory">Theory</option>
                    <option value="Practical">Practical</option>
                    <option value="Activity">Activity</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#73736F] mb-1">
                  Lesson / Chapter Name
                </label>
                <input
                  type="text"
                  required
                  placeholder="e.g. Linear Equations in Two Variables"
                  value={formLessonName}
                  onChange={(e) => setFormLessonName(e.target.value)}
                  className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                />
              </div>

              <div className="flex justify-end gap-2.5 pt-3 border-t border-[#E7E7E4]">
                <button
                  type="button"
                  onClick={() => setShowModal(false)}
                  className="px-4 py-2 border border-[#E7E7E4] text-[#73736F] hover:text-[#202020] rounded-xl text-xs sm:text-sm font-semibold hover:bg-[#F5F5F3] cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={saving}
                  className="px-5 py-2 bg-[#1976d2] hover:bg-[#1565c0] disabled:bg-slate-300 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer"
                >
                  {saving ? 'Saving...' : 'Save Lesson'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
