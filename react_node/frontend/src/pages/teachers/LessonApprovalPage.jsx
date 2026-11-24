import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import lessonApprovalService from '../../services/lessonApprovalService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiCheckCircle,
  FiXCircle,
  FiClock,
  FiFileText,
  FiBookOpen,
  FiFilter,
  FiRefreshCw,
  FiEye,
  FiCheck,
  FiX,
  FiHelpCircle,
} from 'react-icons/fi';

export default function LessonApprovalPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);

  // Filters
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [subjects, setSubjects] = useState([]);

  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStandard, setSelectedStandard] = useState('all');
  const [selectedDivision, setSelectedDivision] = useState('all');
  const [selectedSubject, setSelectedSubject] = useState('all');
  const [selectedStatus, setSelectedStatus] = useState('all');

  // Lessons list
  const [lessons, setLessons] = useState([]);

  // Preview Modal
  const [previewModal, setPreviewModal] = useState({
    isOpen: false,
    title: '',
    content: '',
    type: 'text', // 'text' | 'pdf'
  });

  // Action loading state per item
  const [updatingKey, setUpdatingKey] = useState(null);

  // Load initial options
  useEffect(() => {
    async function init() {
      try {
        const res = await fetchStudentOptions();
        const years = res.academicYears || res.data?.academicYears || res.data?.options?.academicYears || [];
        const classes = res.classes || res.data?.classes || res.data?.options?.classes || [];
        if (years.length > 0) {
          setAcademicYears(years);
          const active = years.find((y) => y.is_active) || years[0];
          if (active) setSelectedYear(String(active.stvacdm_id || active.id));
        }
        if (classes.length > 0) {
          setStandards(classes);
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    init();
  }, []);

  // Load subjects when standard or year changes
  useEffect(() => {
    async function loadSubjects() {
      if (!selectedYear) return;
      try {
        const res = await lessonApprovalService.getSubjects({
          academicYear: selectedYear,
          standard: selectedStandard,
        });
        if (res.data?.success) {
          setSubjects(res.data.subjects || []);
        }
      } catch (err) {
        console.error('Failed to load subjects:', err);
      }
    }
    loadSubjects();
  }, [selectedYear, selectedStandard]);

  // Load lessons list
  const loadLessons = async () => {
    setLoading(true);
    try {
      const res = await lessonApprovalService.getLessons({
        class: selectedStandard,
        division: selectedDivision,
        subject: selectedSubject,
        status: selectedStatus,
      });
      if (res.data?.success) {
        setLessons(res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load lessons for approval:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadLessons();
  }, [selectedStandard, selectedDivision, selectedSubject, selectedStatus]);

  // Handle status update
  const handleUpdateStatus = async (lessonId, columnName, newStatus) => {
    const key = `${lessonId}-${columnName}`;
    setUpdatingKey(key);
    try {
      const res = await lessonApprovalService.updateStatus({
        lesson_id: lessonId,
        column_name: columnName,
        column_value: newStatus,
      });
      if (res.data?.success) {
        setLessons((prev) =>
          prev.map((item) =>
            item.lesson_id === lessonId ? { ...item, [columnName]: newStatus } : item
          )
        );
      }
    } catch (err) {
      alert('Failed to update status: ' + (err.response?.data?.message || err.message));
    } finally {
      setUpdatingKey(null);
    }
  };

  const renderStatusCell = (lesson, columnName, label, docContent, isPdf = false) => {
    const status = lesson[columnName] || 'Pending';
    const key = `${lesson.lesson_id}-${columnName}`;
    const isUpdating = updatingKey === key;

    const isApproved = status.toLowerCase() === 'approved';
    const isRejected = status.toLowerCase() === 'rejected';

    return (
      <div className="flex flex-col items-center gap-1.5 p-1">
        <div className="flex items-center gap-1">
          <span
            className={`px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase ${
              isApproved
                ? 'bg-[#e8f5e9] text-[#2e7d32] border border-[#c8e6c9]'
                : isRejected
                ? 'bg-[#ffebee] text-[#c62828] border border-[#ffcdd2]'
                : 'bg-[#fff3e0] text-[#e65100] border border-[#ffe082]'
            }`}
          >
            {status}
          </span>
          {docContent && (
            <button
              onClick={() =>
                setPreviewModal({
                  isOpen: true,
                  title: `${label} - ${lesson.lesson}`,
                  content: docContent,
                  type: isPdf ? 'pdf' : 'text',
                })
              }
              className="p-1 text-[#73736F] hover:text-[#1976d2] rounded cursor-pointer"
              title="Preview Material"
            >
              <FiEye className="w-3.5 h-3.5" />
            </button>
          )}
        </div>

        {/* Quick action buttons */}
        <div className="flex items-center gap-1">
          <button
            disabled={isUpdating || isApproved}
            onClick={() => handleUpdateStatus(lesson.lesson_id, columnName, 'Approved')}
            className={`p-1 rounded text-xs transition cursor-pointer ${
              isApproved
                ? 'opacity-30 cursor-not-allowed text-[#2e7d32]'
                : 'text-[#2e7d32] hover:bg-[#e8f5e9]'
            }`}
            title="Approve"
          >
            <FiCheck className="w-3.5 h-3.5" />
          </button>
          <button
            disabled={isUpdating || isRejected}
            onClick={() => handleUpdateStatus(lesson.lesson_id, columnName, 'Rejected')}
            className={`p-1 rounded text-xs transition cursor-pointer ${
              isRejected
                ? 'opacity-30 cursor-not-allowed text-[#c62828]'
                : 'text-[#c62828] hover:bg-[#ffebee]'
            }`}
            title="Reject"
          >
            <FiX className="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    );
  };

  return (
    <div className="flex h-screen bg-[#F7F7F5] font-sans text-[#202020] antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#E7E7E4]">
            <div>
              <div className="flex items-center gap-2.5">
                <span className="p-2.5 bg-[#e3f2fd] text-[#1976d2] rounded-xl shadow-sm">
                  <FiCheckCircle className="w-5 h-5" />
                </span>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight text-[#202020]">
                  Lesson Plan & Curriculum Approval
                </h1>
              </div>
              <p className="text-xs sm:text-sm text-[#73736F] mt-1">
                Academic coordinator review console for teacher lesson plans, worksheets, quizzes, and learning outcomes
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={loadLessons}
                className="p-2.5 rounded-xl border border-[#E7E7E4] bg-white hover:bg-[#F5F5F3] text-[#73736F] hover:text-[#202020] transition cursor-pointer shadow-sm"
                title="Refresh List"
              >
                <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin text-[#1976d2]' : ''}`} />
              </button>
            </div>
          </div>

          {/* Filters Bar */}
          <div className="bg-white p-4 rounded-2xl border border-[#E7E7E4] shadow-sm flex flex-wrap gap-4 items-center">
            <div className="w-full sm:w-40">
              <label className="block text-xs font-semibold text-[#73736F] mb-1">Standard / Class</label>
              <select
                value={selectedStandard}
                onChange={(e) => setSelectedStandard(e.target.value)}
                className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
              >
                <option value="all">All Standards</option>
                {standards.map((s, idx) => (
                  <option key={idx} value={s.stvfees_class}>
                    {s.stvfees_class}
                  </option>
                ))}
              </select>
            </div>

            <div className="w-full sm:w-28">
              <label className="block text-xs font-semibold text-[#73736F] mb-1">Division</label>
              <select
                value={selectedDivision}
                onChange={(e) => setSelectedDivision(e.target.value)}
                className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
              >
                <option value="all">All</option>
                <option value="A">Div A</option>
                <option value="B">Div B</option>
                <option value="C">Div C</option>
              </select>
            </div>

            <div className="w-full sm:w-48">
              <label className="block text-xs font-semibold text-[#73736F] mb-1">Subject</label>
              <select
                value={selectedSubject}
                onChange={(e) => setSelectedSubject(e.target.value)}
                className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
              >
                <option value="all">All Subjects</option>
                {subjects.map((sub) => (
                  <option key={sub.stvsubj_id} value={sub.stvsubj_id}>
                    {sub.stvsubj_subject}
                  </option>
                ))}
              </select>
            </div>

            <div className="w-full sm:w-36">
              <label className="block text-xs font-semibold text-[#73736F] mb-1">Approval Status</label>
              <select
                value={selectedStatus}
                onChange={(e) => setSelectedStatus(e.target.value)}
                className="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
              >
                <option value="all">All Statuses</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
              </select>
            </div>
          </div>

          {/* Lessons Approval Matrix */}
          <div className="bg-white rounded-2xl border border-[#E7E7E4] shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-[#F7F7F5] border-b border-[#E7E7E4] text-[11px] font-bold text-[#73736F] uppercase tracking-wider">
                    <th className="py-3 px-4">Standard</th>
                    <th className="py-3 px-4">Subject</th>
                    <th className="py-3 px-4">Lesson Title</th>
                    <th className="py-3 px-4 text-center">Lesson Plan</th>
                    <th className="py-3 px-4 text-center">Worksheet</th>
                    <th className="py-3 px-4 text-center">Quiz</th>
                    <th className="py-3 px-4 text-center">Outcome</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-[#E7E7E4] font-medium text-[#202020]">
                  {loading ? (
                    <tr>
                      <td colSpan="7" className="py-12 text-center text-[#73736F]">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-2" />
                        Loading approval matrix...
                      </td>
                    </tr>
                  ) : lessons.length === 0 ? (
                    <tr>
                      <td colSpan="7" className="py-12 text-center text-[#73736F]">
                        No lessons pending review for selected filters.
                      </td>
                    </tr>
                  ) : (
                    lessons.map((item) => (
                      <tr key={item.lesson_id} className="hover:bg-[#F5F5F3] transition-colors">
                        <td className="py-3 px-4 whitespace-nowrap">
                          <span className="px-2 py-0.5 rounded text-[11px] font-bold bg-[#F1F1EF] text-[#202020]">
                            {item.class} - {item.div}
                          </span>
                        </td>
                        <td className="py-3 px-4 whitespace-nowrap">
                          <div className="font-bold text-xs text-[#1976d2]">
                            {item.subject_name || item.subject}
                          </div>
                        </td>
                        <td className="py-3 px-4 min-w-[200px]">
                          <span className="font-semibold text-[#202020]">
                            {item.lesson}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center">
                          {renderStatusCell(
                            item,
                            'lesson_plan_status',
                            'Lesson Plan',
                            item.lesson_plan_doc,
                            true
                          )}
                        </td>
                        <td className="py-3 px-4 text-center">
                          {renderStatusCell(
                            item,
                            'worksheet_status',
                            'Worksheet',
                            item.worksheet,
                            true
                          )}
                        </td>
                        <td className="py-3 px-4 text-center">
                          {renderStatusCell(
                            item,
                            'quiz_status',
                            'Quiz / Test',
                            item.quiz,
                            true
                          )}
                        </td>
                        <td className="py-3 px-4 text-center">
                          {renderStatusCell(
                            item,
                            'learning_outcome_status',
                            'Learning Outcome',
                            item.learning_outcome,
                            false
                          )}
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

      {/* Preview Modal */}
      {previewModal.isOpen && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[1px]">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#E7E7E4] max-h-[85vh] overflow-y-auto">
            <div className="flex items-center justify-between pb-3 border-b border-[#E7E7E4]">
              <h3 className="font-bold text-base text-[#202020] flex items-center gap-2">
                <FiFileText className="text-[#1976d2]" />
                <span>{previewModal.title}</span>
              </h3>
              <button
                onClick={() => setPreviewModal({ ...previewModal, isOpen: false })}
                className="text-[#73736F] hover:text-[#202020] cursor-pointer"
              >
                <FiX className="w-5 h-5" />
              </button>
            </div>

            <div className="mt-4">
              {previewModal.type === 'pdf' ? (
                <div className="text-center py-6 space-y-3">
                  <FiFileText className="w-12 h-12 text-[#1976d2] mx-auto" />
                  <p className="text-xs text-[#73736F]">
                    File: <strong className="text-[#202020]">{previewModal.content}</strong>
                  </p>
                  <a
                    href={`/uploads/${previewModal.content}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-xl text-xs sm:text-sm font-semibold transition shadow-sm"
                  >
                    <span>Open Attachment</span>
                  </a>
                </div>
              ) : (
                <div className="p-3 bg-[#F7F7F5] rounded-xl border border-[#E7E7E4] text-xs text-[#202020] whitespace-pre-wrap font-sans">
                  {previewModal.content || 'No details provided.'}
                </div>
              )}
            </div>

            <div className="flex justify-end pt-3 border-t border-[#E7E7E4] mt-4">
              <button
                onClick={() => setPreviewModal({ ...previewModal, isOpen: false })}
                className="px-4 py-2 border border-[#E7E7E4] text-[#73736F] hover:text-[#202020] rounded-xl text-xs sm:text-sm font-semibold hover:bg-[#F5F5F3] cursor-pointer"
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
