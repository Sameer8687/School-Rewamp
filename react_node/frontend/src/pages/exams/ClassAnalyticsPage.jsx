import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import classAnalyticsService from '../../services/classAnalyticsService.js';
import {
  FiBarChart2,
  FiAward,
  FiTrendingUp,
  FiAlertTriangle,
  FiUserCheck,
  FiFilter,
  FiRefreshCw,
  FiSearch,
  FiEye,
  FiX,
  FiCalendar,
  FiCheckCircle
} from 'react-icons/fi';

export default function ClassAnalyticsPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [loadingReport, setLoadingReport] = useState(false);

  // Filter options
  const [years, setYears] = useState([]);
  const [exams, setExams] = useState([]);
  const [standards, setStandards] = useState([]);
  const [divisions, setDivisions] = useState([]);
  const [subjects, setSubjects] = useState([]);

  // Active Filter state
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedExam, setSelectedExam] = useState('');
  const [selectedStd, setSelectedStd] = useState('');
  const [selectedDiv, setSelectedDiv] = useState('');
  const [selectedSubject, setSelectedSubject] = useState('');
  const [searchTerm, setSearchTerm] = useState('');

  // Report Data
  const [reportData, setReportData] = useState(null);

  // Student Deep-dive Modal
  const [selectedStudentId, setSelectedStudentId] = useState(null);
  const [deepDiveData, setDeepDiveData] = useState(null);
  const [loadingDeepDive, setLoadingDeepDive] = useState(false);

  // Load dropdown options
  useEffect(() => {
    const loadOptions = async () => {
      setLoading(true);
      try {
        const res = await classAnalyticsService.getOptions();
        if (res.data?.success) {
          const d = res.data.data;
          setYears(d.years || []);
          setExams(d.exams || []);
          setStandards(d.standards || []);
          setDivisions(d.divisions || []);
          setSubjects(d.subjects || []);

          if (d.active_year) setSelectedYear(String(d.active_year));
          if (d.exams?.length > 0) setSelectedExam(String(d.exams[0].id));
          if (d.standards?.length > 0) setSelectedStd(d.standards[0]);
        }
      } catch (err) {
        console.error('Failed to load filter options:', err);
      } finally {
        setLoading(false);
      }
    };
    loadOptions();
  }, []);

  // Fetch report when filters change
  const fetchReport = async () => {
    if (!selectedStd) return;
    setLoadingReport(true);
    try {
      const res = await classAnalyticsService.getClassAnalytics({
        academic_year: selectedYear,
        exam_id: selectedExam,
        standard: selectedStd,
        division: selectedDiv,
        subject_id: selectedSubject
      });
      if (res.data?.success) {
        setReportData(res.data.data);
      }
    } catch (err) {
      console.error('Failed to load class analytics:', err);
    } finally {
      setLoadingReport(false);
    }
  };

  useEffect(() => {
    if (selectedStd) {
      fetchReport();
    }
  }, [selectedStd, selectedDiv, selectedExam, selectedYear, selectedSubject]);

  // Load student deep-dive modal
  const openStudentModal = async (studentId) => {
    setSelectedStudentId(studentId);
    setLoadingDeepDive(true);
    try {
      const res = await classAnalyticsService.getStudentDeepDive(studentId, {
        academic_year: selectedYear,
        exam_id: selectedExam
      });
      if (res.data?.success) {
        setDeepDiveData(res.data.data);
      }
    } catch (err) {
      console.error('Failed to load student deep dive:', err);
    } finally {
      setLoadingDeepDive(false);
    }
  };

  const closeStudentModal = () => {
    setSelectedStudentId(null);
    setDeepDiveData(null);
  };

  // Filter student ledger rows by search
  const filteredStudents = (reportData?.students_report || []).filter(s =>
    searchTerm === '' ||
    s.student_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    s.student_code.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const overview = reportData?.overview || {};
  const topRankers = overview.top_rankers || [];

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
                  <FiBarChart2 className="w-6 h-6" />
                </span>
                <h1 className="text-2xl font-bold tracking-tight text-slate-900">
                  Class & Student Exam Performance Analytics
                </h1>
              </div>
              <p className="text-sm text-slate-500 mt-1">
                Classroom results, rankers, subject averages, weak subjects, and individual student deep-dive insights.
              </p>
            </div>

            <button
              onClick={fetchReport}
              disabled={loadingReport || !selectedStd}
              className="flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 shadow-sm transition disabled:opacity-50 self-start md:self-auto"
            >
              <FiRefreshCw className={`w-4 h-4 ${loadingReport ? 'animate-spin' : ''}`} />
              Refresh Analytics
            </button>
          </div>

          {/* Filter Bar */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
              {/* Academic Year */}
              <div>
                <label className="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Academic Year</label>
                <select
                  value={selectedYear}
                  onChange={(e) => setSelectedYear(e.target.value)}
                  className="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                >
                  {years.map(y => (
                    <option key={y.id} value={y.id}>{y.year}</option>
                  ))}
                </select>
              </div>

              {/* Exam */}
              <div>
                <label className="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Exam</label>
                <select
                  value={selectedExam}
                  onChange={(e) => setSelectedExam(e.target.value)}
                  className="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                >
                  <option value="">All Exams</option>
                  {exams.map(ex => (
                    <option key={ex.id} value={ex.id}>{ex.name}</option>
                  ))}
                </select>
              </div>

              {/* Standard */}
              <div>
                <label className="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Standard / Class *</label>
                <select
                  value={selectedStd}
                  onChange={(e) => setSelectedStd(e.target.value)}
                  className="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2] font-semibold text-[#1976d2]"
                >
                  {standards.map(s => (
                    <option key={s} value={s}>Class {s}</option>
                  ))}
                </select>
              </div>

              {/* Division */}
              <div>
                <label className="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Division</label>
                <select
                  value={selectedDiv}
                  onChange={(e) => setSelectedDiv(e.target.value)}
                  className="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                >
                  <option value="">All Divisions</option>
                  {divisions.map(d => (
                    <option key={d} value={d}>Div {d}</option>
                  ))}
                </select>
              </div>

              {/* Subject */}
              <div>
                <label className="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Filter Subject</label>
                <select
                  value={selectedSubject}
                  onChange={(e) => setSelectedSubject(e.target.value)}
                  className="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                >
                  <option value="">All Subjects</option>
                  {subjects.map(sub => (
                    <option key={sub.id} value={sub.id}>{sub.name}</option>
                  ))}
                </select>
              </div>
            </div>
          </div>

          {/* Top KPI Metrics Row */}
          <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="flex items-center justify-between text-slate-400">
                <span className="text-xs font-semibold uppercase tracking-wider text-slate-500">Class Enrolled</span>
                <FiUserCheck className="w-4 h-4 text-[#1976d2]" />
              </div>
              <div className="text-2xl font-bold text-slate-900 mt-2">
                {overview.total_students || 0}
              </div>
              <div className="text-[11px] text-slate-400 mt-1">Active class students</div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="flex items-center justify-between text-slate-400">
                <span className="text-xs font-semibold uppercase tracking-wider text-slate-500">Attendance Rate</span>
                <FiTrendingUp className="w-4 h-4 text-emerald-600" />
              </div>
              <div className="text-2xl font-bold text-emerald-600 mt-2">
                {overview.attendance_percentage || 0}%
              </div>
              <div className="text-[11px] text-slate-400 mt-1">Present days percentage</div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="flex items-center justify-between text-slate-400">
                <span className="text-xs font-semibold uppercase tracking-wider text-slate-500">Class Topper</span>
                <FiAward className="w-4 h-4 text-amber-500" />
              </div>
              <div className="text-2xl font-bold text-amber-600 mt-2">
                {overview.top_student ? `${overview.top_student.percentage}%` : 'N/A'}
              </div>
              <div className="text-[11px] text-slate-600 truncate mt-1 font-medium">
                {overview.top_student?.student_name || 'No marks recorded'}
              </div>
            </div>

            <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
              <div className="flex items-center justify-between text-slate-400">
                <span className="text-xs font-semibold uppercase tracking-wider text-slate-500">Weak Subjects</span>
                <FiAlertTriangle className="w-4 h-4 text-rose-500" />
              </div>
              <div className="text-2xl font-bold text-rose-600 mt-2">
                {overview.weak_subjects_count || 0}
              </div>
              <div className="text-[11px] text-slate-400 mt-1">Average score below 45%</div>
            </div>
          </div>

          {/* Top Rankers Podium & Subject Averages Grid */}
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            {/* Rankers Podium */}
            <div className="lg:col-span-5 bg-white rounded-xl border border-slate-200 shadow-sm p-4">
              <h2 className="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
                <FiAward className="w-4 h-4 text-amber-500" />
                Class Rankers (Top 3)
              </h2>

              {topRankers.length === 0 ? (
                <div className="text-center py-8 text-xs text-slate-400">
                  No rankers data for selected exam.
                </div>
              ) : (
                <div className="space-y-3">
                  {topRankers.map((ranker, i) => (
                    <div
                      key={ranker.student_id}
                      className="flex items-center justify-between p-3 rounded-lg bg-slate-50 border border-slate-100"
                    >
                      <div className="flex items-center gap-3">
                        <span className={`w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold ${
                          i === 0 ? 'bg-amber-100 text-amber-800' :
                          i === 1 ? 'bg-slate-200 text-slate-700' : 'bg-orange-100 text-orange-800'
                        }`}>
                          {i + 1}
                        </span>
                        <div>
                          <div className="font-semibold text-xs text-slate-900">{ranker.student_name}</div>
                          <div className="text-[11px] text-slate-400">{ranker.student_code}</div>
                        </div>
                      </div>
                      <div className="text-right">
                        <div className="font-bold text-sm text-[#1976d2]">{ranker.percentage}%</div>
                        <div className="text-[10px] text-slate-500">{ranker.total_obtained} / {ranker.total_max}</div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* Subject Performance Breakdown */}
            <div className="lg:col-span-7 bg-white rounded-xl border border-slate-200 shadow-sm p-4">
              <h2 className="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
                <FiTrendingUp className="w-4 h-4 text-[#1976d2]" />
                Subject Performance Averages
              </h2>

              {(!reportData?.subject_averages || reportData.subject_averages.length === 0) ? (
                <div className="text-center py-8 text-xs text-slate-400">
                  No subject records found.
                </div>
              ) : (
                <div className="space-y-3 max-h-56 overflow-y-auto pr-1">
                  {reportData.subject_averages.map((sub) => (
                    <div key={sub.subject_id} className="text-xs">
                      <div className="flex justify-between items-center mb-1">
                        <span className="font-medium text-slate-800">
                          {sub.subject_name}
                          {sub.is_weak && (
                            <span className="ml-2 px-1.5 py-0.5 rounded text-[10px] bg-rose-100 text-rose-700 font-semibold">
                              Weak &lt; 45%
                            </span>
                          )}
                        </span>
                        <span className="font-mono text-slate-600">
                          Avg: {sub.average_obtained} / {sub.max_marks} ({sub.average_percentage}%)
                        </span>
                      </div>
                      <div className="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div
                          className={`h-full rounded-full transition-all ${
                            sub.average_percentage >= 75 ? 'bg-emerald-500' :
                            sub.average_percentage >= 45 ? 'bg-[#e3f2fd]0' : 'bg-rose-500'
                          }`}
                          style={{ width: `${Math.min(100, sub.average_percentage)}%` }}
                        />
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>

          {/* Student Ledger Table */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div className="p-4 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-3">
              <h2 className="text-sm font-semibold text-slate-900">
                Classroom Student Marks Ledger
              </h2>
              <div className="relative w-full md:w-64">
                <FiSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Filter student name/ID..."
                  className="w-full pl-9 pr-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                />
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                  <tr>
                    <th className="px-4 py-3">#</th>
                    <th className="px-4 py-3">Student Name</th>
                    <th className="px-4 py-3">Code</th>
                    <th className="px-4 py-3">Std / Div</th>
                    <th className="px-4 py-3">Marks Scored</th>
                    <th className="px-4 py-3">Percentage</th>
                    <th className="px-4 py-3">Grade</th>
                    <th className="px-4 py-3 text-right">Details</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loadingReport ? (
                    <tr>
                      <td colSpan="8" className="text-center py-12 text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                        Calculating class analytics...
                      </td>
                    </tr>
                  ) : filteredStudents.length === 0 ? (
                    <tr>
                      <td colSpan="8" className="text-center py-10 text-slate-400">
                        No students found for current filter.
                      </td>
                    </tr>
                  ) : (
                    filteredStudents.map((st, idx) => (
                      <tr key={st.student_id} className="hover:bg-slate-50/70 transition">
                        <td className="px-4 py-3 text-slate-400">{idx + 1}</td>
                        <td className="px-4 py-3 font-semibold text-slate-900">{st.student_name}</td>
                        <td className="px-4 py-3 font-mono text-[11px] text-slate-500">{st.student_code}</td>
                        <td className="px-4 py-3 text-slate-600">{st.standard} - {st.division}</td>
                        <td className="px-4 py-3 font-medium text-slate-800">
                          {st.total_obtained} / {st.total_max}
                        </td>
                        <td className="px-4 py-3 font-bold text-[#1976d2]">
                          {st.percentage}%
                        </td>
                        <td className="px-4 py-3">
                          <span className={`px-2 py-0.5 rounded text-[10px] font-bold ${
                            st.grade === 'A+' || st.grade === 'A' ? 'bg-emerald-100 text-emerald-800' :
                            st.grade === 'B+' || st.grade === 'B' ? 'bg-[#e3f2fd] text-[#1976d2]' :
                            st.grade === 'C' || st.grade === 'D' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800'
                          }`}>
                            {st.grade}
                          </span>
                        </td>
                        <td className="px-4 py-3 text-right">
                          <button
                            onClick={() => openStudentModal(st.student_id)}
                            className="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] bg-slate-100 text-slate-700 font-medium rounded hover:bg-[#e3f2fd] hover:text-[#1976d2] transition"
                          >
                            <FiEye className="w-3.5 h-3.5" />
                            Deep-dive
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>

          {/* Student Deep-Dive Drawer / Modal */}
          {selectedStudentId && (
            <div className="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
              <div className="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                {/* Modal Header */}
                <div className="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                  <div>
                    <h3 className="text-base font-bold text-slate-900">
                      Student Academic & Attendance Deep-Dive
                    </h3>
                    <p className="text-xs text-slate-500">
                      {deepDiveData?.student?.student_name} ({deepDiveData?.student?.student_code})
                    </p>
                  </div>
                  <button onClick={closeStudentModal} className="text-slate-400 hover:text-slate-600 p-1">
                    <FiX className="w-5 h-5" />
                  </button>
                </div>

                {/* Modal Content */}
                <div className="p-5 overflow-y-auto space-y-4">
                  {loadingDeepDive ? (
                    <div className="text-center py-12 text-slate-400">
                      <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                      Loading student examination metrics...
                    </div>
                  ) : !deepDiveData ? (
                    <div className="text-center py-12 text-slate-400">No data found.</div>
                  ) : (
                    <>
                      {/* Attendance Card */}
                      <div className="bg-slate-50 p-3.5 rounded-lg border border-slate-200 grid grid-cols-4 gap-2 text-center text-xs">
                        <div>
                          <div className="text-slate-400 text-[10px] uppercase">Total Days</div>
                          <div className="font-bold text-slate-800 text-sm mt-0.5">{deepDiveData.attendance.total_days}</div>
                        </div>
                        <div>
                          <div className="text-slate-400 text-[10px] uppercase">Present</div>
                          <div className="font-bold text-emerald-600 text-sm mt-0.5">{deepDiveData.attendance.present_days}</div>
                        </div>
                        <div>
                          <div className="text-slate-400 text-[10px] uppercase">Absent</div>
                          <div className="font-bold text-rose-600 text-sm mt-0.5">{deepDiveData.attendance.absent_days}</div>
                        </div>
                        <div>
                          <div className="text-slate-400 text-[10px] uppercase">Attendance %</div>
                          <div className="font-bold text-[#1976d2] text-sm mt-0.5">{deepDiveData.attendance.percentage}%</div>
                        </div>
                      </div>

                      {/* Subject Marks Table */}
                      <div>
                        <h4 className="text-xs font-semibold uppercase text-slate-500 tracking-wider mb-2">
                          Subject Marks & Teacher Feedback
                        </h4>
                        <div className="border border-slate-200 rounded-lg overflow-hidden">
                          <table className="w-full text-left text-xs">
                            <thead className="bg-slate-100 text-slate-600">
                              <tr>
                                <th className="p-2.5">Subject</th>
                                <th className="p-2.5">Scored / Total</th>
                                <th className="p-2.5">Passing</th>
                                <th className="p-2.5">Score %</th>
                                <th className="p-2.5">Subject Teacher</th>
                              </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                              {deepDiveData.academics.subjects.map((sub, i) => (
                                <tr key={i} className="hover:bg-slate-50">
                                  <td className="p-2.5 font-medium text-slate-800">{sub.subject_name}</td>
                                  <td className="p-2.5 font-bold text-slate-900">{sub.obtained_marks} / {sub.total_marks}</td>
                                  <td className="p-2.5 text-slate-500">{sub.passing_marks}</td>
                                  <td className="p-2.5 font-semibold text-[#1976d2]">{sub.percentage}%</td>
                                  <td className="p-2.5 text-slate-600">{sub.teacher_name || '-'}</td>
                                </tr>
                              ))}
                            </tbody>
                          </table>
                        </div>
                      </div>

                      {/* Summary Banner */}
                      <div className="p-3 bg-[#e3f2fd] rounded-lg border border-[#bbdefb] flex justify-between items-center text-xs">
                        <span className="font-medium text-[#0d47a1]">Overall Examination Score</span>
                        <span className="font-bold text-base text-[#1976d2]">
                          {deepDiveData.academics.total_obtained} / {deepDiveData.academics.total_max} ({deepDiveData.academics.percentage}%)
                        </span>
                      </div>
                    </>
                  )}
                </div>

                {/* Modal Footer */}
                <div className="p-3 bg-slate-50 border-t border-slate-100 flex justify-end">
                  <button
                    onClick={closeStudentModal}
                    className="px-4 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-lg hover:bg-slate-900 transition"
                  >
                    Close
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

