import React, { useState, useEffect } from 'react';
import examService from '../../services/examService';
import { fetchStudentOptions } from '../../services/studentService.js';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiAward,
  FiPrinter,
  FiFilter,
  FiCheckCircle,
  FiAlertCircle,
  FiTrendingUp,
  FiUser,
  FiBookOpen,
  FiChevronLeft,
} from 'react-icons/fi';

const CLASSES = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
const DIVISIONS = ['A', 'B', 'C', 'D'];

export default function ReportCardPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [activeTab, setActiveTab] = useState('summary'); // 'summary' | 'card'
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  // Filters
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedClass, setSelectedClass] = useState('I');
  const [selectedDiv, setSelectedDiv] = useState('A');
  const [exams, setExams] = useState([]);
  const [selectedExam, setSelectedExam] = useState('');

  // Data
  const [classResults, setClassResults] = useState([]);
  const [selectedStudentCard, setSelectedStudentCard] = useState(null);

  useEffect(() => {
    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    try {
      setLoading(true);
      const optRes = await fetchStudentOptions();
      const yrList = optRes.data?.options?.academicYears || [];
      if (yrList.length > 0) {
        setAcademicYears(yrList);
        const active = yrList.find((y) => y.is_active || y.status === 'A') || yrList[0];
        setSelectedYear(active.stvacdm_id);
      }

      const examRes = await examService.getExamsList();
      const list = examRes.data?.data || [];
      setExams(list);
      if (list.length > 0) {
        setSelectedExam(list[0].id);
      }
    } catch (err) {
      console.error('Failed to load initial report card data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleFetchClassResults = async () => {
    if (!selectedClass || !selectedDiv) {
      setMessage({ text: 'Please select class and division', type: 'error' });
      return;
    }

    try {
      setLoading(true);
      setMessage({ text: '', type: '' });

      const res = await examService.getClassResults({
        academicYear: selectedYear,
        standard: selectedClass,
        division: selectedDiv,
        examId: selectedExam || undefined,
      });

      const list = res.data?.data || [];
      setClassResults(list);
      setActiveTab('summary');

      if (list.length === 0) {
        setMessage({ text: 'No marks or evaluations recorded for this class yet.', type: 'info' });
      }
    } catch (err) {
      console.error('Failed to load class results:', err);
      setMessage({ text: 'Failed to load class results.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const handleViewStudentReport = async (studentId) => {
    try {
      setLoading(true);
      setMessage({ text: '', type: '' });

      const res = await examService.getStudentReportCard(studentId, {
        academicYear: selectedYear,
        examId: selectedExam || undefined,
      });

      setSelectedStudentCard(res.data?.data || null);
      setActiveTab('card');
    } catch (err) {
      console.error('Failed to load student report card:', err);
      setMessage({ text: 'Failed to load student report card.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  // Performance calculations
  const totalEvaluated = classResults.length;
  const passResults = classResults.filter((r) => r.result === 'Passed');
  const passRate = totalEvaluated > 0 ? ((passResults.length / totalEvaluated) * 100).toFixed(0) : '0';
  const classAvgPct = totalEvaluated > 0
    ? (classResults.reduce((sum, r) => sum + Number(r.percentage), 0) / totalEvaluated).toFixed(1)
    : '0';
  const classTopper = classResults.length > 0 ? classResults[0] : null;

  const currentExamObj = exams.find((e) => String(e.id) === String(selectedExam));

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-7xl mx-auto space-y-6">
            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 no-print">
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                  <FiAward className="text-[#1976d2]" />
                  Academic Report Card & Results
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Generate student progress reports, calculate grades, rankings, and class result sheets
                </p>
              </div>

              {activeTab === 'card' && (
                <div className="flex items-center gap-2">
                  <button
                    onClick={() => setActiveTab('summary')}
                    className="flex items-center gap-1 px-3 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition"
                  >
                    <FiChevronLeft /> Back to List
                  </button>
                  <button
                    onClick={() => window.print()}
                    className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition cursor-pointer"
                  >
                    <FiPrinter /> Print Report Card
                  </button>
                </div>
              )}
            </div>

            {/* Notification alert */}
            {message.text && (
              <div
                className={`p-3.5 rounded-lg text-xs flex items-center gap-2 no-print ${
                  message.type === 'success'
                    ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                    : message.type === 'error'
                    ? 'bg-rose-50 text-rose-800 border border-rose-200'
                    : 'bg-[#e3f2fd] text-blue-800 border border-[#bbdefb]'
                }`}
              >
                {message.type === 'success' ? <FiCheckCircle /> : <FiAlertCircle />}
                <span>{message.text}</span>
              </div>
            )}

            {/* Filter Section */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 no-print">
              <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                  <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                    Academic Year
                  </label>
                  <select
                    value={selectedYear}
                    onChange={(e) => setSelectedYear(e.target.value)}
                    className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                  >
                    {academicYears.map((yr) => (
                      <option key={yr.stvacdm_id} value={yr.stvacdm_id}>
                        {yr.stvacdm_year}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                    Class / Standard
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

                <div>
                  <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                    Division
                  </label>
                  <select
                    value={selectedDiv}
                    onChange={(e) => setSelectedDiv(e.target.value)}
                    className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                  >
                    {DIVISIONS.map((d) => (
                      <option key={d} value={d}>
                        Div {d}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                    Exam Filter
                  </label>
                  <select
                    value={selectedExam}
                    onChange={(e) => setSelectedExam(e.target.value)}
                    className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                  >
                    <option value="">All Exams / Cumulative</option>
                    {exams.map((ex) => (
                      <option key={ex.id} value={ex.id}>
                        {ex.name}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span className="text-xs text-slate-500">
                  Select criteria and click Load to analyze performance rankings
                </span>

                <button
                  onClick={handleFetchClassResults}
                  disabled={loading}
                  className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow-sm hover:bg-[#1565c0] disabled:opacity-50 transition cursor-pointer"
                >
                  <FiFilter /> {loading ? 'Loading...' : 'Load Results'}
                </button>
              </div>
            </div>

            {/* TAB 1: Class Results & Rankings */}
            {activeTab === 'summary' && classResults.length > 0 && (
              <div className="space-y-6">
                {/* Stats row */}
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                  <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-[#e3f2fd] text-[#1976d2] flex items-center justify-center font-bold text-lg">
                      {totalEvaluated}
                    </div>
                    <div>
                      <div className="text-[11px] font-medium text-slate-500 uppercase">Evaluated</div>
                      <div className="text-xs text-slate-700 font-semibold">Std {selectedClass}-{selectedDiv}</div>
                    </div>
                  </div>

                  <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                      {passRate}%
                    </div>
                    <div>
                      <div className="text-[11px] font-medium text-slate-500 uppercase">Pass Rate</div>
                      <div className="text-xs text-emerald-600 font-semibold">{passResults.length} Passed</div>
                    </div>
                  </div>

                  <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                      {classAvgPct}%
                    </div>
                    <div>
                      <div className="text-[11px] font-medium text-slate-500 uppercase">Average %</div>
                      <div className="text-xs text-slate-600 font-semibold">Class Score</div>
                    </div>
                  </div>

                  <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-[#e3f2fd] text-[#1976d2] flex items-center justify-center font-bold text-sm">
                      #1
                    </div>
                    <div className="overflow-hidden">
                      <div className="text-[11px] font-medium text-slate-500 uppercase">Class Topper</div>
                      <div className="text-xs text-slate-900 font-bold truncate">
                        {classTopper ? `${classTopper.student_name} (${classTopper.percentage}%)` : '--'}
                      </div>
                    </div>
                  </div>
                </div>

                {/* Class Ranking Table */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                  <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 className="text-sm font-bold text-slate-800">
                      Rankings & Performance: Std {selectedClass}-{selectedDiv} &bull;{' '}
                      {currentExamObj ? currentExamObj.name : 'Cumulative'}
                    </h2>
                  </div>

                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                          <th className="py-3 px-4 w-16 text-center">Rank</th>
                          <th className="py-3 px-4 w-20 text-center">Roll No</th>
                          <th className="py-3 px-4">Student Name</th>
                          <th className="py-3 px-4 w-28 text-center">Total Marks</th>
                          <th className="py-3 px-4 w-28 text-center">Percentage</th>
                          <th className="py-3 px-4 w-20 text-center">Grade</th>
                          <th className="py-3 px-4 w-28 text-center">Result</th>
                          <th className="py-3 px-4 w-32 text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100">
                        {classResults.map((st) => (
                          <tr key={st.student_id} className="hover:bg-slate-50 transition-colors">
                            <td className="py-3 px-4 text-center">
                              {st.rank === 1 ? (
                                <span className="inline-block px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-300">
                                  🥇 1st
                                </span>
                              ) : st.rank === 2 ? (
                                <span className="inline-block px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-800">
                                  🥈 2nd
                                </span>
                              ) : st.rank === 3 ? (
                                <span className="inline-block px-2 py-0.5 rounded-full text-[10px] font-black bg-orange-100 text-orange-800">
                                  🥉 3rd
                                </span>
                              ) : (
                                <span className="font-mono text-slate-500">#{st.rank}</span>
                              )}
                            </td>
                            <td className="py-3 px-4 text-center font-bold text-slate-700">{st.roll_no || '--'}</td>
                            <td className="py-3 px-4">
                              <span className="font-bold text-slate-800 block">{st.student_name}</span>
                              <span className="text-[10px] text-slate-400 font-mono">{st.enrollment_no}</span>
                            </td>
                            <td className="py-3 px-4 text-center font-bold text-slate-700">
                              {st.total_scored} / {st.total_max}
                            </td>
                            <td className="py-3 px-4 text-center font-mono font-bold text-[#1976d2]">
                              {st.percentage}%
                            </td>
                            <td className="py-3 px-4 text-center">
                              <span className="font-bold px-2 py-0.5 rounded text-[11px] bg-slate-100 text-slate-800">
                                {st.grade}
                              </span>
                            </td>
                            <td className="py-3 px-4 text-center">
                              {st.result === 'Passed' ? (
                                <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                  Passed
                                </span>
                              ) : (
                                <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                  Needs Imp.
                                </span>
                              )}
                            </td>
                            <td className="py-3 px-4 text-center">
                              <button
                                onClick={() => handleViewStudentReport(st.student_id)}
                                className="px-3 py-1 text-[11px] font-semibold text-[#1976d2] hover:bg-[#1976d2] hover:text-white border border-[#1976d2] rounded transition cursor-pointer"
                              >
                                View Report Card
                              </button>
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            )}

            {/* TAB 2: Individual Student Report Card */}
            {activeTab === 'card' && selectedStudentCard && (
              <div className="max-w-4xl mx-auto bg-white rounded-xl shadow-lg border border-slate-300 p-8 print:p-0 print:border-none print:shadow-none">
                {/* Official Crest & Header */}
                <div className="text-center border-b-2 border-slate-900 pb-4 mb-6">
                  <div className="inline-block px-3 py-1 rounded bg-[#1976d2] text-white font-bold text-xs tracking-wider mb-2 uppercase">
                    Olive English School
                  </div>
                  <h2 className="text-2xl font-black tracking-wide text-slate-900 uppercase">
                    Academic Progress Report Card
                  </h2>
                  <p className="text-xs text-slate-600 font-medium mt-0.5">
                    Affiliated to State Board &bull; Academic Session {selectedStudentCard.student.academic_year_name || '2025-2026'}
                  </p>
                  <p className="text-xs font-bold text-[#1976d2] uppercase mt-1">
                    {currentExamObj ? currentExamObj.name : 'Terminal Assessment & Evaluation'}
                  </p>
                </div>

                {/* Student Details Grid */}
                <div className="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs mb-6">
                  <div>
                    <span className="text-[10px] font-bold text-slate-500 uppercase block">Student Name</span>
                    <span className="font-bold text-slate-900 text-sm">{selectedStudentCard.student.student_name}</span>
                  </div>
                  <div>
                    <span className="text-[10px] font-bold text-slate-500 uppercase block">Enrollment ID</span>
                    <span className="font-mono font-bold text-slate-800">{selectedStudentCard.student.enrollment_no}</span>
                  </div>
                  <div>
                    <span className="text-[10px] font-bold text-slate-500 uppercase block">Class & Division</span>
                    <span className="font-bold text-slate-800">
                      Std {selectedStudentCard.student.standard} - {selectedStudentCard.student.division} (Roll: {selectedStudentCard.student.roll_no || '--'})
                    </span>
                  </div>
                  <div>
                    <span className="text-[10px] font-bold text-slate-500 uppercase block">Mother's Name</span>
                    <span className="font-medium text-slate-700">{selectedStudentCard.student.mother_name || '--'}</span>
                  </div>
                  <div>
                    <span className="text-[10px] font-bold text-slate-500 uppercase block">Date of Birth</span>
                    <span className="font-medium text-slate-700">{selectedStudentCard.student.dob || '--'}</span>
                  </div>
                  <div>
                    <span className="text-[10px] font-bold text-slate-500 uppercase block">Aadhaar Number</span>
                    <span className="font-mono text-slate-700">{selectedStudentCard.student.aadhar_no || '--'}</span>
                  </div>
                </div>

                {/* Marks Table */}
                <div className="mb-6">
                  <table className="w-full text-left border-collapse text-xs border border-slate-300">
                    <thead>
                      <tr className="bg-slate-100 text-slate-800 border-b border-slate-300 font-bold text-[11px] uppercase">
                        <th className="p-3 border-r border-slate-300 w-12 text-center">#</th>
                        <th className="p-3 border-r border-slate-300">Curriculum Subject</th>
                        <th className="p-3 border-r border-slate-300 w-28 text-center">Max Marks</th>
                        <th className="p-3 border-r border-slate-300 w-28 text-center">Passing Marks</th>
                        <th className="p-3 border-r border-slate-300 w-28 text-center">Marks Obtained</th>
                        <th className="p-3 border-r border-slate-300 w-20 text-center">Grade</th>
                        <th className="p-3 w-24 text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-200">
                      {selectedStudentCard.marks.map((m, idx) => (
                        <tr key={idx} className="hover:bg-slate-50">
                          <td className="p-3 border-r border-slate-300 text-center font-mono text-slate-500">{idx + 1}</td>
                          <td className="p-3 border-r border-slate-300 font-semibold text-slate-800">{m.subject_name}</td>
                          <td className="p-3 border-r border-slate-300 text-center font-mono">{m.total_marks}</td>
                          <td className="p-3 border-r border-slate-300 text-center font-mono text-slate-500">{m.passing_marks}</td>
                          <td className="p-3 border-r border-slate-300 text-center font-mono font-bold text-slate-900">
                            {m.marks}
                          </td>
                          <td className="p-3 border-r border-slate-300 text-center font-bold text-slate-700">{m.grade}</td>
                          <td className="p-3 text-center">
                            {m.status === 'Pass' ? (
                              <span className="text-emerald-700 font-bold">Pass</span>
                            ) : (
                              <span className="text-rose-700 font-bold">Fail</span>
                            )}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                    <tfoot>
                      <tr className="bg-slate-100 font-bold border-t-2 border-slate-400 text-slate-900">
                        <td colSpan="2" className="p-3 text-right uppercase tracking-wider">
                          Grand Total & Results
                        </td>
                        <td className="p-3 text-center font-mono">{selectedStudentCard.summary.totalMax}</td>
                        <td className="p-3 text-center">--</td>
                        <td className="p-3 text-center font-mono text-sm text-[#1976d2]">
                          {selectedStudentCard.summary.totalScored}
                        </td>
                        <td className="p-3 text-center text-sm">{selectedStudentCard.summary.overallGrade}</td>
                        <td className="p-3 text-center font-bold text-emerald-700">
                          {selectedStudentCard.summary.overallResult}
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                {/* Summary & Scale Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                  {/* Overall Percentage */}
                  <div className="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <h4 className="text-xs font-bold text-slate-800 uppercase mb-2">Performance Summary</h4>
                    <div className="flex items-center justify-between text-xs py-1 border-b border-slate-200">
                      <span className="text-slate-600">Aggregate Percentage:</span>
                      <span className="font-bold text-base text-[#1976d2] font-mono">
                        {selectedStudentCard.summary.percentage}%
                      </span>
                    </div>
                    <div className="flex items-center justify-between text-xs py-1 border-b border-slate-200">
                      <span className="text-slate-600">Overall Grade Awarded:</span>
                      <span className="font-bold text-slate-900">{selectedStudentCard.summary.overallGrade}</span>
                    </div>
                    <div className="flex items-center justify-between text-xs py-1">
                      <span className="text-slate-600">Final Promotional Status:</span>
                      <span className="font-bold text-emerald-700 uppercase">{selectedStudentCard.summary.overallResult}</span>
                    </div>
                  </div>

                  {/* Grading Key */}
                  <div className="bg-slate-50 p-4 rounded-xl border border-slate-200 text-[10px]">
                    <h4 className="font-bold text-slate-800 uppercase mb-1.5 text-xs">Grading Key Scale</h4>
                    <div className="grid grid-cols-2 gap-1 text-slate-600">
                      <div>A+ : 90% - 100% (Outstanding)</div>
                      <div>A : 80% - 89% (Excellent)</div>
                      <div>B+ : 70% - 79% (Very Good)</div>
                      <div>B : 60% - 69% (Good)</div>
                      <div>C : 50% - 59% (Satisfactory)</div>
                      <div>D : 35% - 49% (Pass)</div>
                    </div>
                  </div>
                </div>

                {/* Signatures */}
                <div className="mt-12 flex justify-between items-end pt-6 border-t border-slate-300 text-xs">
                  <div className="text-center">
                    <div className="w-36 border-b border-slate-400 pb-2 mb-1"></div>
                    <span className="text-[10px] font-bold text-slate-600 uppercase">Class Teacher</span>
                  </div>

                  <div className="text-center">
                    <div className="w-36 border-b border-slate-400 pb-2 mb-1"></div>
                    <span className="text-[10px] font-bold text-slate-600 uppercase">Parent / Guardian</span>
                  </div>

                  <div className="text-center">
                    <div className="w-40 border-b border-slate-400 pb-2 mb-1 font-serif italic text-slate-800">
                      Olive English School
                    </div>
                    <span className="text-[10px] font-bold text-slate-600 uppercase">Principal Signature</span>
                  </div>
                </div>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
