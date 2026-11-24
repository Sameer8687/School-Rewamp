import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import quizService from '../../services/quizService.js';
import {
  FiCheckSquare,
  FiAward,
  FiSearch,
  FiRefreshCw,
  FiCheckCircle,
  FiXCircle,
  FiEye,
  FiX,
  FiBookOpen,
  FiCalendar
} from 'react-icons/fi';

export default function QuizReviewPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [submissions, setSubmissions] = useState([]);
  const [quizzes, setQuizzes] = useState([]);

  // Filters
  const [selectedStandard, setSelectedStandard] = useState('all');
  const [selectedQuiz, setSelectedQuiz] = useState('all');
  const [searchTerm, setSearchTerm] = useState('');

  // Active Review Modal
  const [activeReview, setActiveReview] = useState(null);
  const [loadingReview, setLoadingReview] = useState(false);

  // Load quizzes and submissions
  const loadData = async () => {
    setLoading(true);
    try {
      const [qRes, subRes] = await Promise.all([
        quizService.getQuizzes(),
        quizService.getSubmissions()
      ]);
      if (qRes.data?.success) setQuizzes(qRes.data.data || []);
      if (subRes.data?.success) setSubmissions(subRes.data.data || []);
    } catch (err) {
      console.error('Failed to load quiz data:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  // Open review modal
  const openReview = async (quizId, studentId) => {
    setLoadingReview(true);
    try {
      const res = await quizService.getQuizReview(quizId, studentId);
      if (res.data?.success) {
        setActiveReview(res.data.data);
      }
    } catch (err) {
      console.error('Failed to load review:', err);
    } finally {
      setLoadingReview(false);
    }
  };

  const closeReview = () => {
    setActiveReview(null);
  };

  // Filtered submissions
  const filteredSubmissions = submissions.filter(s => {
    const matchSearch = searchTerm === '' ||
      s.student_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      s.student_code.toLowerCase().includes(searchTerm.toLowerCase()) ||
      s.quiz_name.toLowerCase().includes(searchTerm.toLowerCase());

    const matchStd = selectedStandard === 'all' || s.standard === selectedStandard;
    const matchQuiz = selectedQuiz === 'all' || String(s.quiz_id) === String(selectedQuiz);

    return matchSearch && matchStd && matchQuiz;
  });

  // Unique standards
  const standardsList = Array.from(new Set(quizzes.map(q => q.standard).filter(Boolean))).sort();

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} setIsOpen={setSidebarOpen} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          {/* Header */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
              <div className="flex items-center gap-2">
                <span className="p-2 bg-[#e3f2fd] text-[#1976d2] rounded-lg">
                  <FiCheckSquare className="w-6 h-6" />
                </span>
                <h1 className="text-2xl font-bold tracking-tight text-slate-900">
                  Online Quiz & Assessment Results Review
                </h1>
              </div>
              <p className="text-sm text-slate-500 mt-1">
                View student online quiz submissions, scores, and question-by-question answer reviews.
              </p>
            </div>

            <button
              onClick={loadData}
              className="flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 shadow-sm transition self-start md:self-auto"
            >
              <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
              Refresh
            </button>
          </div>

          {/* Quick Stats Cards */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="text-xs font-medium text-slate-500 uppercase tracking-wider">Available Quizzes</div>
              <div className="text-2xl font-bold text-slate-900 mt-1">{quizzes.length}</div>
              <div className="text-xs text-slate-400 mt-0.5">Created across curriculum</div>
            </div>
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Submissions</div>
              <div className="text-2xl font-bold text-[#1976d2] mt-1">{submissions.length}</div>
              <div className="text-xs text-slate-400 mt-0.5">Completed assessments</div>
            </div>
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="text-xs font-medium text-slate-500 uppercase tracking-wider">Average Score</div>
              <div className="text-2xl font-bold text-emerald-600 mt-1">
                {submissions.length > 0
                  ? (submissions.reduce((a, b) => a + (parseFloat(b.percentage) || 0), 0) / submissions.length).toFixed(1)
                  : 0}%
              </div>
              <div className="text-xs text-slate-400 mt-0.5">Overall student performance</div>
            </div>
          </div>

          {/* Table Card */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            {/* Table Filters */}
            <div className="p-4 border-b border-slate-100 flex flex-col md:flex-row gap-3 items-center justify-between">
              <div className="relative w-full md:w-72">
                <FiSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Search student, quiz name..."
                  className="w-full pl-9 pr-3 py-1.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div className="flex items-center gap-2 w-full md:w-auto">
                <select
                  value={selectedStandard}
                  onChange={(e) => setSelectedStandard(e.target.value)}
                  className="px-3 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="all">All Standards</option>
                  {standardsList.map(std => (
                    <option key={std} value={std}>Class {std}</option>
                  ))}
                </select>

                <select
                  value={selectedQuiz}
                  onChange={(e) => setSelectedQuiz(e.target.value)}
                  className="px-3 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2] max-w-xs truncate"
                >
                  <option value="all">All Quizzes</option>
                  {quizzes.map(q => (
                    <option key={q.quiz_id} value={q.quiz_id}>{q.quiz_name}</option>
                  ))}
                </select>
              </div>
            </div>

            {/* Submissions Table */}
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                  <tr>
                    <th className="px-4 py-3">#</th>
                    <th className="px-4 py-3">Student Name</th>
                    <th className="px-4 py-3">Code</th>
                    <th className="px-4 py-3">Quiz Name</th>
                    <th className="px-4 py-3">Subject</th>
                    <th className="px-4 py-3">Class</th>
                    <th className="px-4 py-3">Score</th>
                    <th className="px-4 py-3">Percentage</th>
                    <th className="px-4 py-3">Date</th>
                    <th className="px-4 py-3 text-right">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan="10" className="text-center py-10 text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                        Loading quiz submissions...
                      </td>
                    </tr>
                  ) : filteredSubmissions.length === 0 ? (
                    <tr>
                      <td colSpan="10" className="text-center py-10 text-slate-400">
                        No quiz submissions found matching criteria.
                      </td>
                    </tr>
                  ) : (
                    filteredSubmissions.map((s, idx) => (
                      <tr key={s.result_id} className="hover:bg-slate-50/70 transition">
                        <td className="px-4 py-3 text-slate-400">{idx + 1}</td>
                        <td className="px-4 py-3 font-semibold text-slate-900">{s.student_name}</td>
                        <td className="px-4 py-3 font-mono text-slate-500">{s.student_code}</td>
                        <td className="px-4 py-3 font-medium text-slate-800">{s.quiz_name}</td>
                        <td className="px-4 py-3 text-slate-600">{s.subject_name || '-'}</td>
                        <td className="px-4 py-3 text-slate-600">{s.standard} - {s.division}</td>
                        <td className="px-4 py-3 font-bold text-slate-900">
                          {s.marks_scored} / {s.total_questions}
                        </td>
                        <td className="px-4 py-3">
                          <span className={`px-2 py-0.5 rounded text-[11px] font-bold ${
                            s.percentage >= 80 ? 'bg-emerald-100 text-emerald-800' :
                            s.percentage >= 50 ? 'bg-[#e3f2fd] text-[#1976d2]' : 'bg-rose-100 text-rose-800'
                          }`}>
                            {s.percentage}%
                          </span>
                        </td>
                        <td className="px-4 py-3 text-slate-400">{s.exam_date || '-'}</td>
                        <td className="px-4 py-3 text-right">
                          <button
                            onClick={() => openReview(s.quiz_id, s.student_id)}
                            className="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] bg-[#e3f2fd] text-[#1976d2] font-medium rounded hover:bg-[#bbdefb] transition"
                          >
                            <FiEye className="w-3.5 h-3.5" />
                            Review
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>

            {/* Table Footer */}
            <div className="p-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500">
              Showing {filteredSubmissions.length} of {submissions.length} submissions
            </div>
          </div>

          {/* Detailed Question by Question Review Modal */}
          {(activeReview || loadingReview) && (
            <div className="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
              <div className="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                {/* Header */}
                <div className="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                  <div>
                    <h3 className="text-base font-bold text-slate-900">
                      Quiz Submission Review
                    </h3>
                    <p className="text-xs text-slate-500">
                      {activeReview?.header?.student_name} ({activeReview?.header?.student_code}) • {activeReview?.header?.quiz_name}
                    </p>
                  </div>
                  <button onClick={closeReview} className="text-slate-400 hover:text-slate-600 p-1">
                    <FiX className="w-5 h-5" />
                  </button>
                </div>

                {/* Body */}
                <div className="p-5 overflow-y-auto space-y-4">
                  {loadingReview ? (
                    <div className="text-center py-12 text-slate-400">
                      <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                      Loading quiz question review...
                    </div>
                  ) : !activeReview ? (
                    <div className="text-center py-12 text-slate-400">No review data found.</div>
                  ) : (
                    <>
                      {/* Summary Banner */}
                      <div className="p-4 bg-[#e3f2fd] rounded-xl border border-[#bbdefb] grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs">
                        <div>
                          <div className="text-[#1976d2] font-semibold uppercase text-[10px]">Quiz Subject</div>
                          <div className="font-bold text-slate-900 text-sm mt-0.5">{activeReview.header?.subject_name || '-'}</div>
                        </div>
                        <div>
                          <div className="text-[#1976d2] font-semibold uppercase text-[10px]">Class & Div</div>
                          <div className="font-bold text-slate-900 text-sm mt-0.5">{activeReview.header?.standard} - {activeReview.header?.division}</div>
                        </div>
                        <div>
                          <div className="text-[#1976d2] font-semibold uppercase text-[10px]">Marks Scored</div>
                          <div className="font-bold text-[#1976d2] text-sm mt-0.5">{activeReview.header?.marks_scored} / {activeReview.header?.total_questions}</div>
                        </div>
                        <div>
                          <div className="text-[#1976d2] font-semibold uppercase text-[10px]">Percentage</div>
                          <div className="font-bold text-emerald-700 text-sm mt-0.5">{activeReview.header?.percentage}%</div>
                        </div>
                      </div>

                      {/* Questions List */}
                      <div className="space-y-4">
                        <h4 className="text-xs font-semibold uppercase text-slate-500 tracking-wider">
                          Questions & Answers Breakdown ({activeReview.questions.length} Questions)
                        </h4>

                        {activeReview.questions.map((q, idx) => (
                          <div key={q.question_id || idx} className="p-4 rounded-lg border border-slate-200 bg-white shadow-xs">
                            <div className="flex items-start justify-between gap-3 mb-3">
                              <div className="text-xs font-semibold text-slate-900">
                                <span className="text-[#1976d2] font-bold mr-1.5">Q{q.question_no || idx + 1}.</span>
                                {q.question_text}
                              </div>
                              <span className={`px-2 py-0.5 rounded text-[10px] font-bold shrink-0 flex items-center gap-1 ${
                                q.is_correct ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'
                              }`}>
                                {q.is_correct ? <FiCheckCircle className="w-3 h-3" /> : <FiXCircle className="w-3 h-3" />}
                                {q.is_correct ? 'Correct' : 'Incorrect'}
                              </span>
                            </div>

                            {/* Options */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                              {q.options.map((opt) => (
                                <div
                                  key={opt.option_id}
                                  className={`p-2.5 rounded-lg text-xs border flex items-center justify-between ${
                                    opt.is_correct
                                      ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-medium'
                                      : opt.is_selected
                                      ? 'bg-rose-50 border-rose-300 text-rose-900'
                                      : 'bg-slate-50 border-slate-100 text-slate-600'
                                  }`}
                                >
                                  <span>{opt.text}</span>
                                  {opt.is_correct && (
                                    <span className="text-[10px] font-bold text-emerald-700 ml-2">✓ Correct Answer</span>
                                  )}
                                  {!opt.is_correct && opt.is_selected && (
                                    <span className="text-[10px] font-bold text-rose-600 ml-2">✗ Selected</span>
                                  )}
                                </div>
                              ))}
                            </div>
                          </div>
                        ))}
                      </div>
                    </>
                  )}
                </div>

                {/* Footer */}
                <div className="p-3 bg-slate-50 border-t border-slate-100 flex justify-end">
                  <button
                    onClick={closeReview}
                    className="px-4 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-lg hover:bg-slate-900 transition"
                  >
                    Close Review
                  </button>
                </div>
              </div>
            </div>
          )}
        </main>
      </div>
    </div>
  );
}

