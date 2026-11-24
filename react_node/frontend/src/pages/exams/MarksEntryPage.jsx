import React, { useState, useEffect } from 'react';
import examService from '../../services/examService';
import { fetchStudentOptions } from '../../services/studentService.js';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiFilter,
  FiSave,
  FiCheckCircle,
  FiAlertCircle,
  FiPrinter,
  FiRefreshCw,
  FiAward,
  FiTrendingUp,
  FiUserCheck,
} from 'react-icons/fi';

const CLASSES = ['NURSERY', 'JR.KG', 'SR.KG', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
const DIVISIONS = ['A', 'B', 'C', 'D'];

export default function MarksEntryPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  // Filter States
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedClass, setSelectedClass] = useState('I');
  const [selectedDiv, setSelectedDiv] = useState('A');
  const [exams, setExams] = useState([]);
  const [selectedExam, setSelectedExam] = useState('');
  const [subjects, setSubjects] = useState([]);
  const [selectedSubject, setSelectedSubject] = useState('');

  // Data States
  const [students, setStudents] = useState([]);
  const [marksData, setMarksData] = useState({}); // { [student_id]: marksValue }
  const [maxMarks, setMaxMarks] = useState(100);
  const [passingMarks, setPassingMarks] = useState(35);

  // Load initial academic years and exams
  useEffect(() => {
    loadInitialData();
  }, []);

  // When class changes, reload subjects
  useEffect(() => {
    if (selectedClass) {
      loadSubjects(selectedClass);
    }
  }, [selectedClass]);

  const loadInitialData = async () => {
    try {
      const optRes = await fetchStudentOptions();
      const yrList = optRes.academicYears || optRes.data?.academicYears || optRes.data?.options?.academicYears || [];
      if (yrList.length > 0) {
        setAcademicYears(yrList);
        const active = yrList.find((y) => y.is_active || y.status === 'A') || yrList[0];
        setSelectedYear(active.stvacdm_id);
      }

      const examRes = await examService.getExamsList();
      if (examRes.data?.data && examRes.data.data.length > 0) {
        setExams(examRes.data.data);
        setSelectedExam(examRes.data.data[0].id);
      }
    } catch (err) {
      console.error('Failed to load initial exam data:', err);
    }
  };

  const loadSubjects = async (std) => {
    try {
      const res = await examService.getSubjects({ standard: std });
      if (res.data?.data && res.data.data.length > 0) {
        setSubjects(res.data.data);
        setSelectedSubject(res.data.data[0].id);
      } else {
        setSubjects([]);
        setSelectedSubject('');
      }
    } catch (err) {
      console.error('Failed to load subjects:', err);
    }
  };

  const handleFetchMarks = async () => {
    if (!selectedClass || !selectedDiv || !selectedExam || !selectedSubject) {
      setMessage({ text: 'Please select class, division, exam, and subject', type: 'error' });
      return;
    }

    setLoading(true);
    setMessage({ text: '', type: '' });

    try {
      const res = await examService.getStudentMarks({
        year: selectedYear,
        standard: selectedClass,
        division: selectedDiv,
        examId: selectedExam,
        subjectId: selectedSubject,
      });

      const list = res.data?.data || [];
      setStudents(list);

      // Initialize marks dictionary
      const initialMarks = {};
      if (list.length > 0) {
        setMaxMarks(list[0].max_marks || 100);
        setPassingMarks(list[0].passing_marks || 35);
      }

      list.forEach((st) => {
        initialMarks[st.student_id] = st.marks !== '' ? st.marks : '';
      });
      setMarksData(initialMarks);

      if (list.length === 0) {
        setMessage({ text: 'No active students found for this class and division.', type: 'info' });
      }
    } catch (err) {
      console.error('Fetch marks error:', err);
      setMessage({ text: 'Failed to fetch student marks.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const handleMarksChange = (studentId, value) => {
    // Allow empty string or numbers
    if (value === '') {
      setMarksData((prev) => ({ ...prev, [studentId]: '' }));
      return;
    }

    const num = Number(value);
    if (isNaN(num) || num < 0) return;
    if (num > maxMarks) {
      alert(`Marks cannot exceed maximum marks (${maxMarks})!`);
      return;
    }

    setMarksData((prev) => ({ ...prev, [studentId]: num }));
  };

  const handleSaveMarks = async () => {
    if (students.length === 0) return;

    setSaving(true);
    setMessage({ text: '', type: '' });

    try {
      const records = students.map((st) => ({
        student_id: st.student_id,
        marks: marksData[st.student_id] !== undefined ? marksData[st.student_id] : '',
      }));

      await examService.saveStudentMarks({
        year: selectedYear,
        standard: selectedClass,
        division: selectedDiv,
        examId: selectedExam,
        subjectId: selectedSubject,
        marksRecords: records,
      });

      setMessage({ text: 'Marks successfully saved for all students!', type: 'success' });
      handleFetchMarks();
    } catch (err) {
      console.error('Failed to save marks:', err);
      setMessage({ text: 'Failed to save marks.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  // Quick helper: auto-fill passing marks for empty students
  const fillPassingMarks = () => {
    const updated = { ...marksData };
    students.forEach((st) => {
      if (updated[st.student_id] === '' || updated[st.student_id] === undefined) {
        updated[st.student_id] = passingMarks;
      }
    });
    setMarksData(updated);
  };

  // Calculations for summary stats
  const totalStudents = students.length;
  const enteredCount = Object.values(marksData).filter((m) => m !== '' && m !== null).length;
  const validMarks = Object.values(marksData)
    .filter((m) => m !== '' && m !== null)
    .map(Number);
  const passCount = validMarks.filter((m) => m >= passingMarks).length;
  const failCount = validMarks.filter((m) => m < passingMarks).length;
  const avgMarks = validMarks.length > 0
    ? (validMarks.reduce((a, b) => a + b, 0) / validMarks.length).toFixed(1)
    : '0';

  const selectedExamObj = exams.find((e) => String(e.id) === String(selectedExam));
  const selectedSubjectObj = subjects.find((s) => String(s.id) === String(selectedSubject));

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-7xl mx-auto space-y-6">
            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                  <FiTrendingUp className="text-[#1976d2]" />
                  Marks Entry & Evaluation
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Enter and evaluate student subject marks for term and periodic tests
                </p>
              </div>

              {students.length > 0 && (
                <div className="flex items-center gap-2">
                  <button
                    onClick={() => window.print()}
                    className="flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 transition"
                  >
                    <FiPrinter /> Print Sheet
                  </button>
                  <button
                    onClick={handleSaveMarks}
                    disabled={saving}
                    className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50 transition"
                  >
                    <FiSave /> {saving ? 'Saving...' : 'Save All Marks'}
                  </button>
                </div>
              )}
            </div>

            {/* Notification alert */}
            {message.text && (
              <div
                className={`p-3.5 rounded-lg text-xs flex items-center gap-2 ${
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

            {/* Filter Card */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                {/* Academic Year */}
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

                {/* Class */}
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

                {/* Division */}
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

                {/* Exam */}
                <div>
                  <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                    Examination
                  </label>
                  <select
                    value={selectedExam}
                    onChange={(e) => setSelectedExam(e.target.value)}
                    className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                  >
                    {exams.map((ex) => (
                      <option key={ex.id} value={ex.id}>
                        {ex.name}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Subject */}
                <div>
                  <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                    Subject
                  </label>
                  <select
                    value={selectedSubject}
                    onChange={(e) => setSelectedSubject(e.target.value)}
                    className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                  >
                    {subjects.length === 0 ? (
                      <option value="">No subjects found</option>
                    ) : (
                      subjects.map((sub) => (
                        <option key={sub.id} value={sub.id}>
                          {sub.name}
                        </option>
                      ))
                    )}
                  </select>
                </div>
              </div>

              <div className="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                <div className="text-xs text-slate-500">
                  {selectedSubjectObj && (
                    <span>
                      Subject: <strong>{selectedSubjectObj.name}</strong> | Max: <strong>{maxMarks}</strong> | Passing: <strong>{passingMarks}</strong>
                    </span>
                  )}
                </div>

                <button
                  onClick={handleFetchMarks}
                  disabled={loading}
                  className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow-sm hover:bg-[#1565c0] disabled:opacity-50 transition"
                >
                  <FiFilter /> {loading ? 'Loading...' : 'Load Students'}
                </button>
              </div>
            </div>

            {/* Performance Stats Cards */}
            {students.length > 0 && (
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-[#e3f2fd] text-[#1976d2] flex items-center justify-center font-bold text-lg">
                    {totalStudents}
                  </div>
                  <div>
                    <div className="text-[11px] font-medium text-slate-500 uppercase">Enrolled</div>
                    <div className="text-xs text-slate-700 font-semibold">{enteredCount} Entered</div>
                  </div>
                </div>

                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    {passCount}
                  </div>
                  <div>
                    <div className="text-[11px] font-medium text-slate-500 uppercase">Passed</div>
                    <div className="text-xs text-emerald-600 font-semibold">
                      {totalStudents > 0 ? ((passCount / totalStudents) * 100).toFixed(0) : 0}% Pass Rate
                    </div>
                  </div>
                </div>

                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                    {failCount}
                  </div>
                  <div>
                    <div className="text-[11px] font-medium text-slate-500 uppercase">Failed</div>
                    <div className="text-xs text-rose-600 font-semibold">Needs Help</div>
                  </div>
                </div>

                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                    {avgMarks}
                  </div>
                  <div>
                    <div className="text-[11px] font-medium text-slate-500 uppercase">Class Avg</div>
                    <div className="text-xs text-slate-600 font-semibold">out of {maxMarks}</div>
                  </div>
                </div>
              </div>
            )}

            {/* Students Marks Sheet */}
            {students.length > 0 && (
              <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                  <div className="flex items-center gap-2">
                    <h2 className="text-sm font-bold text-slate-800">
                      Class Mark Sheet: Std {selectedClass}-{selectedDiv} &bull; {selectedExamObj?.name} &bull; {selectedSubjectObj?.name}
                    </h2>
                  </div>

                  <div className="flex items-center gap-3">
                    <button
                      onClick={fillPassingMarks}
                      className="text-xs text-slate-600 hover:text-slate-900 underline font-medium cursor-pointer"
                    >
                      Fill Empty with Passing ({passingMarks})
                    </button>
                  </div>
                </div>

                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                        <th className="py-3 px-4 w-12 text-center">#</th>
                        <th className="py-3 px-4 w-20 text-center">Roll No</th>
                        <th className="py-3 px-4 w-32">Enrollment ID</th>
                        <th className="py-3 px-4">Student Name</th>
                        <th className="py-3 px-4 w-36 text-center">Marks (Max: {maxMarks})</th>
                        <th className="py-3 px-4 w-28 text-center">Percentage</th>
                        <th className="py-3 px-4 w-28 text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {students.map((st, idx) => {
                        const markVal = marksData[st.student_id];
                        const isEntered = markVal !== '' && markVal !== null && markVal !== undefined;
                        const num = Number(markVal);
                        const isPass = isEntered && num >= passingMarks;
                        const pct = isEntered && maxMarks > 0 ? ((num / maxMarks) * 100).toFixed(1) : '-';

                        return (
                          <tr key={st.student_id} className="hover:bg-slate-50 transition-colors">
                            <td className="py-3 px-4 text-center text-slate-400 font-mono">{idx + 1}</td>
                            <td className="py-3 px-4 text-center font-bold text-slate-700">{st.roll_no || '-'}</td>
                            <td className="py-3 px-4 text-slate-600 font-mono text-[11px]">{st.enrollment_no}</td>
                            <td className="py-3 px-4 font-semibold text-slate-800">{st.student_name}</td>
                            <td className="py-2 px-4 text-center">
                              <input
                                type="number"
                                min="0"
                                max={maxMarks}
                                value={markVal !== undefined ? markVal : ''}
                                onChange={(e) => handleMarksChange(st.student_id, e.target.value)}
                                placeholder="--"
                                className={`w-24 text-center font-bold py-1.5 px-2 rounded border text-xs outline-none transition ${
                                  !isEntered
                                    ? 'border-slate-300 bg-white'
                                    : isPass
                                    ? 'border-emerald-400 bg-emerald-50/40 text-emerald-900'
                                    : 'border-rose-400 bg-rose-50/40 text-rose-900'
                                }`}
                              />
                            </td>
                            <td className="py-3 px-4 text-center font-mono font-medium text-slate-700">
                              {pct !== '-' ? `${pct}%` : '-'}
                            </td>
                            <td className="py-3 px-4 text-center">
                              {!isEntered ? (
                                <span className="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-500">
                                  Pending
                                </span>
                              ) : isPass ? (
                                <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                  Pass
                                </span>
                              ) : (
                                <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                  Fail
                                </span>
                              )}
                            </td>
                          </tr>
                        );
                      })}
                    </tbody>
                  </table>
                </div>

                <div className="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                  <span className="text-xs text-slate-500">
                    Showing {students.length} students enrolled in Std {selectedClass}-{selectedDiv}
                  </span>

                  <button
                    onClick={handleSaveMarks}
                    disabled={saving}
                    className="flex items-center gap-1.5 px-5 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50 transition"
                  >
                    <FiSave /> {saving ? 'Saving...' : 'Save All Marks'}
                  </button>
                </div>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
