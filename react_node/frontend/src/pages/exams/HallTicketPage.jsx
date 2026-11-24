import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import examService from '../../services/examService';
import { fetchStudentOptions } from '../../services/studentService.js';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiPlus,
  FiPrinter,
  FiTrash2,
  FiEye,
  FiCheckCircle,
  FiAlertCircle,
  FiCalendar,
  FiFileText,
  FiClock,
  FiCheck,
  FiX,
  FiFilter,
} from 'react-icons/fi';

const CLASSES = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
const DIVISIONS = ['A', 'B', 'C', 'D'];

export default function HallTicketPage() {
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [activeTab, setActiveTab] = useState('schedules'); // 'schedules' | 'print'

  // Auto-switch tab based on CI3 route
  useEffect(() => {
    const p = (location.pathname || '').toLowerCase();
    if (p.includes('report') || p.includes('fetchreport')) {
      setActiveTab('print');
    } else {
      setActiveTab('schedules');
    }
  }, [location.pathname]);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  // Filter & Global Data
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [exams, setExams] = useState([]);

  // Schedules state
  const [schedules, setSchedules] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);

  // Timetable builder modal form
  const [formStandard, setFormStandard] = useState('I');
  const [formDivision, setFormDivision] = useState('A');
  const [formExam, setFormExam] = useState('');
  const [formShift, setFormShift] = useState('Morning');
  const [subjectsList, setSubjectsList] = useState([]);
  const [scheduleLines, setScheduleLines] = useState([
    { subject: '', exam_date: '', time_start: '09:00 AM', time_end: '12:00 PM', exam_type: 'Theory' },
  ]);

  // Print Tab State
  const [printStandard, setPrintStandard] = useState('I');
  const [printDivision, setPrintDivision] = useState('A');
  const [printExam, setPrintExam] = useState('');
  const [feesClearanceOnly, setFeesClearanceOnly] = useState(false);
  const [eligibleStudents, setEligibleStudents] = useState([]);
  const [activeTimetable, setActiveTimetable] = useState(null);

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
        setFormExam(list[0].id);
        setPrintExam(list[0].id);
      }

      await loadSchedules();
    } catch (err) {
      console.error('Failed to load initial hall ticket data:', err);
    } finally {
      setLoading(false);
    }
  };

  const loadSchedules = async () => {
    try {
      const res = await examService.getHallTickets({ academicYear: selectedYear });
      setSchedules(res.data?.data || []);
    } catch (err) {
      console.error('Failed to load schedules:', err);
    }
  };

  // When form class changes, load its subjects
  useEffect(() => {
    if (formStandard) {
      examService.getSubjects({ standard: formStandard }).then((res) => {
        setSubjectsList(res.data?.data || []);
      });
    }
  }, [formStandard]);

  const handleAddLine = () => {
    setScheduleLines((prev) => [
      ...prev,
      { subject: '', exam_date: '', time_start: '09:00 AM', time_end: '12:00 PM', exam_type: 'Theory' },
    ]);
  };

  const handleRemoveLine = (index) => {
    setScheduleLines((prev) => prev.filter((_, i) => i !== index));
  };

  const handleLineChange = (index, field, value) => {
    setScheduleLines((prev) => {
      const updated = [...prev];
      updated[index][field] = value;
      return updated;
    });
  };

  const handleSaveSchedule = async (e) => {
    e.preventDefault();
    if (!formStandard || !formDivision || !formExam || scheduleLines.length === 0) {
      setMessage({ text: 'Please fill all required timetable fields', type: 'error' });
      return;
    }

    try {
      setSaving(true);
      setMessage({ text: '', type: '' });

      await examService.saveHallTicket({
        standard: formStandard,
        division: formDivision,
        examId: formExam,
        academicYear: selectedYear,
        shift: formShift,
        lines: scheduleLines,
      });

      setMessage({ text: 'Hall ticket timetable created successfully!', type: 'success' });
      setIsModalOpen(false);
      loadSchedules();
    } catch (err) {
      console.error('Failed to save timetable:', err);
      setMessage({ text: 'Failed to save timetable schedule.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  const handleDeleteSchedule = async (id) => {
    if (!window.confirm('Are you sure you want to delete this hall ticket timetable?')) return;

    try {
      await examService.deleteHallTicket(id);
      setMessage({ text: 'Timetable deleted successfully!', type: 'success' });
      loadSchedules();
    } catch (err) {
      console.error('Delete schedule error:', err);
      setMessage({ text: 'Failed to delete timetable.', type: 'error' });
    }
  };

  // Load students and timetable for printing
  const handleLoadPrintTickets = async () => {
    if (!printStandard || !printDivision || !printExam) {
      setMessage({ text: 'Please select class, division, and exam', type: 'error' });
      return;
    }

    try {
      setLoading(true);
      setMessage({ text: '', type: '' });

      // 1. Get eligible students
      const stRes = await examService.getHallTicketStudents({
        standard: printStandard,
        division: printDivision,
        academicYear: selectedYear,
        feesClearanceOnly,
      });
      setEligibleStudents(stRes.data?.data || []);

      // 2. Find matching timetable
      const matchingSchedule = schedules.find(
        (s) =>
          s.standard === printStandard &&
          s.division === printDivision &&
          String(s.exam_id) === String(printExam)
      );

      if (matchingSchedule) {
        const detailRes = await examService.getHallTicketById(matchingSchedule.id);
        setActiveTimetable(detailRes.data?.data || null);
      } else {
        setActiveTimetable(null);
      }

      if ((stRes.data?.data || []).length === 0) {
        setMessage({ text: 'No students matching the criteria found.', type: 'info' });
      }
    } catch (err) {
      console.error('Load print tickets error:', err);
      setMessage({ text: 'Failed to load students for hall ticket printing.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const selectedExamName = exams.find((e) => String(e.id) === String(printExam))?.name || 'Examination';

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
                  <FiFileText className="text-[#1976d2]" />
                  Examination Hall Ticket Management
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Schedule examination timetables, verify fee clearance, and generate official admit cards
                </p>
              </div>

              {activeTab === 'schedules' ? (
                <button
                  onClick={() => setIsModalOpen(true)}
                  className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus /> Create Exam Timetable
                </button>
              ) : (
                eligibleStudents.length > 0 && (
                  <button
                    onClick={() => window.print()}
                    className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition cursor-pointer"
                  >
                    <FiPrinter /> Print All Admit Cards
                  </button>
                )
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

            {/* Tab navigation */}
            <div className="flex border-b border-slate-200 gap-4 no-print">
              <button
                onClick={() => setActiveTab('schedules')}
                className={`pb-3 text-xs font-semibold flex items-center gap-2 border-b-2 transition ${
                  activeTab === 'schedules'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-slate-500 hover:text-slate-800'
                }`}
              >
                <FiCalendar /> Timetable Schedules ({schedules.length})
              </button>
              <button
                onClick={() => setActiveTab('print')}
                className={`pb-3 text-xs font-semibold flex items-center gap-2 border-b-2 transition ${
                  activeTab === 'print'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-slate-500 hover:text-slate-800'
                }`}
              >
                <FiPrinter /> Generate & Print Admit Cards
              </button>
            </div>

            {/* TAB 1: Timetables List */}
            {activeTab === 'schedules' && (
              <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                  <h2 className="text-sm font-bold text-slate-800">Class Exam Timetables</h2>
                </div>

                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                        <th className="py-3 px-4 w-16 text-center">ID</th>
                        <th className="py-3 px-4">Class & Division</th>
                        <th className="py-3 px-4">Examination</th>
                        <th className="py-3 px-4">Shift</th>
                        <th className="py-3 px-4 text-center">Subjects Scheduled</th>
                        <th className="py-3 px-4 w-28 text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {schedules.length === 0 ? (
                        <tr>
                          <td colSpan="6" className="py-8 text-center text-slate-400">
                            No exam timetable schedules created yet. Click "Create Exam Timetable" to add one.
                          </td>
                        </tr>
                      ) : (
                        schedules.map((sc) => (
                          <tr key={sc.id} className="hover:bg-slate-50 transition-colors">
                            <td className="py-3 px-4 text-center font-mono text-slate-400">{sc.id}</td>
                            <td className="py-3 px-4 font-bold text-slate-800">
                              Std {sc.standard} - {sc.division}
                            </td>
                            <td className="py-3 px-4 font-semibold text-slate-700">{sc.exam_name}</td>
                            <td className="py-3 px-4 text-slate-600">{sc.shift || 'Morning'}</td>
                            <td className="py-3 px-4 text-center">
                              <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#e3f2fd] text-[#1565c0] border border-[#bbdefb]">
                                {sc.total_subjects} Papers
                              </span>
                            </td>
                            <td className="py-3 px-4 text-center">
                              <button
                                onClick={() => handleDeleteSchedule(sc.id)}
                                className="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition"
                                title="Delete Timetable"
                              >
                                <FiTrash2 size={14} />
                              </button>
                            </td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            )}

            {/* TAB 2: Generate & Print Admit Cards */}
            {activeTab === 'print' && (
              <div className="space-y-6">
                {/* Print Filters Card */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 no-print">
                  <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Class / Standard
                      </label>
                      <select
                        value={printStandard}
                        onChange={(e) => setPrintStandard(e.target.value)}
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
                        value={printDivision}
                        onChange={(e) => setPrintDivision(e.target.value)}
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
                        Examination
                      </label>
                      <select
                        value={printExam}
                        onChange={(e) => setPrintExam(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        {exams.map((ex) => (
                          <option key={ex.id} value={ex.id}>
                            {ex.name}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Fee Clearance Check
                      </label>
                      <div className="flex items-center gap-2 mt-2">
                        <input
                          type="checkbox"
                          id="feesClearance"
                          checked={feesClearanceOnly}
                          onChange={(e) => setFeesClearanceOnly(e.target.checked)}
                          className="w-4 h-4 text-[#1976d2] rounded border-slate-300 cursor-pointer"
                        />
                        <label htmlFor="feesClearance" className="text-xs text-slate-700 font-medium cursor-pointer">
                          Only Dues Cleared / RTE
                        </label>
                      </div>
                    </div>
                  </div>

                  <div className="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span className="text-xs text-slate-500">
                      {activeTimetable
                        ? `Timetable found with ${activeTimetable.lines.length} scheduled exam papers.`
                        : 'No timetable created yet for this class and exam.'}
                    </span>

                    <button
                      onClick={handleLoadPrintTickets}
                      disabled={loading}
                      className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow-sm hover:bg-[#1565c0] disabled:opacity-50 transition"
                    >
                      <FiFilter /> {loading ? 'Loading...' : 'Generate Hall Tickets'}
                    </button>
                  </div>
                </div>

                {/* Printable Hall Tickets Section */}
                {eligibleStudents.length > 0 && (
                  <div className="space-y-8">
                    {eligibleStudents.map((st) => (
                      <div
                        key={st.student_id}
                        className="bg-white rounded-xl shadow-md border border-slate-300 p-6 print:p-0 print:border-none print:shadow-none print:break-after-page max-w-3xl mx-auto"
                      >
                        {/* School Header */}
                        <div className="text-center border-b-2 border-slate-800 pb-3 mb-4">
                          <h2 className="text-lg font-black tracking-wider text-slate-900 uppercase">
                            Olive English School
                          </h2>
                          <p className="text-[11px] text-slate-600 font-medium">
                            Recognized by Government of Maharashtra &bull; Academic Excellence Since 2005
                          </p>
                          <div className="mt-1.5 inline-block px-4 py-1 bg-slate-900 text-white rounded text-[11px] font-bold tracking-widest uppercase">
                            {selectedExamName} — Examination Admit Card / Hall Ticket
                          </div>
                        </div>

                        {/* Student Details Grid */}
                        <div className="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs bg-slate-50 p-3 rounded-lg border border-slate-200 mb-4">
                          <div>
                            <span className="text-slate-500 text-[10px] uppercase font-bold block">Candidate Name</span>
                            <span className="font-bold text-slate-900">{st.student_name}</span>
                          </div>
                          <div>
                            <span className="text-slate-500 text-[10px] uppercase font-bold block">Enrollment ID</span>
                            <span className="font-mono font-bold text-slate-800">{st.enrollment_no}</span>
                          </div>
                          <div>
                            <span className="text-slate-500 text-[10px] uppercase font-bold block">Class & Division</span>
                            <span className="font-bold text-slate-800">
                              Std {st.standard} - {st.division} (Roll: {st.roll_no || '--'})
                            </span>
                          </div>
                          <div>
                            <span className="text-slate-500 text-[10px] uppercase font-bold block">Mother's Name</span>
                            <span className="font-medium text-slate-700">{st.mother_name || '--'}</span>
                          </div>
                          <div>
                            <span className="text-slate-500 text-[10px] uppercase font-bold block">Date of Birth</span>
                            <span className="font-medium text-slate-700">{st.dob || '--'}</span>
                          </div>
                          <div>
                            <span className="text-slate-500 text-[10px] uppercase font-bold block">Fee / RTE Status</span>
                            <span className="font-bold text-emerald-700">
                              {st.rte_status === 'Y' || st.rte_status === '1' ? 'RTE Scholar (Exempt)' : 'Verified & Cleared'}
                            </span>
                          </div>
                        </div>

                        {/* Timetable Table */}
                        <div className="mb-4">
                          <h3 className="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Examination Schedule & Papers
                          </h3>
                          <table className="w-full text-left border-collapse text-xs border border-slate-300">
                            <thead>
                              <tr className="bg-slate-100 text-slate-800 border-b border-slate-300 font-bold text-[11px]">
                                <th className="p-2 border-r border-slate-300 w-12 text-center">#</th>
                                <th className="p-2 border-r border-slate-300">Subject Paper</th>
                                <th className="p-2 border-r border-slate-300 w-28">Date</th>
                                <th className="p-2 border-r border-slate-300 w-44">Time</th>
                                <th className="p-2 w-28 text-center">Invigilator Sign</th>
                              </tr>
                            </thead>
                            <tbody>
                              {activeTimetable && activeTimetable.lines.length > 0 ? (
                                activeTimetable.lines.map((line, idx) => (
                                  <tr key={idx} className="border-b border-slate-200">
                                    <td className="p-2 border-r border-slate-300 text-center font-mono">{idx + 1}</td>
                                    <td className="p-2 border-r border-slate-300 font-semibold text-slate-800">
                                      {line.subject} ({line.exam_type})
                                    </td>
                                    <td className="p-2 border-r border-slate-300 font-medium text-slate-700">
                                      {line.exam_date || 'TBA'}
                                    </td>
                                    <td className="p-2 border-r border-slate-300 text-slate-600">
                                      {line.time_start} - {line.time_end}
                                    </td>
                                    <td className="p-2 border-slate-300 text-center text-slate-300">__________</td>
                                  </tr>
                                ))
                              ) : (
                                <tr>
                                  <td colSpan="5" className="p-3 text-center text-slate-400 italic">
                                    Schedule timetable will be provided by Class Teacher.
                                  </td>
                                </tr>
                              )}
                            </tbody>
                          </table>
                        </div>

                        {/* Candidate Rules */}
                        <div className="text-[10px] text-slate-600 space-y-0.5 border-t border-slate-200 pt-3">
                          <p className="font-bold text-slate-800">Instructions for Candidates:</p>
                          <p>1. Candidates must arrive at the examination hall at least 15 minutes prior to commencement.</p>
                          <p>2. Possession of mobile phones, electronic smart devices, or written materials is strictly prohibited.</p>
                          <p>3. This admit card must be presented upon request during every examination paper.</p>
                        </div>

                        {/* Signatures Footer */}
                        <div className="mt-8 flex justify-between items-end pt-4 border-t border-dashed border-slate-300 text-xs">
                          <div className="text-center">
                            <div className="w-32 border-b border-slate-400 pb-1 mb-1"></div>
                            <span className="text-[10px] font-bold text-slate-600 uppercase">Student's Signature</span>
                          </div>

                          <div className="text-center">
                            <div className="w-32 border-b border-slate-400 pb-1 mb-1"></div>
                            <span className="text-[10px] font-bold text-slate-600 uppercase">Class Teacher</span>
                          </div>

                          <div className="text-center">
                            <div className="w-36 border-b border-slate-400 pb-1 mb-1 font-serif italic text-slate-800">
                              Olive English School
                            </div>
                            <span className="text-[10px] font-bold text-slate-600 uppercase">Principal / Seal</span>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                )}
              </div>
            )}

            {/* Modal: Create Timetable Schedule */}
            {isModalOpen && (
              <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
                <div className="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                  <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                      <FiCalendar className="text-[#1976d2]" /> Create Class Exam Timetable
                    </h3>
                    <button
                      onClick={() => setIsModalOpen(false)}
                      className="text-slate-400 hover:text-slate-600"
                    >
                      <FiX size={18} />
                    </button>
                  </div>

                  <form onSubmit={handleSaveSchedule} className="flex-1 overflow-y-auto p-6 space-y-4">
                    <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
                      <div>
                        <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                          Class
                        </label>
                        <select
                          value={formStandard}
                          onChange={(e) => setFormStandard(e.target.value)}
                          className="w-full text-xs rounded-lg border border-slate-300 p-2"
                        >
                          {CLASSES.map((c) => (
                            <option key={c} value={c}>
                              Std {c}
                            </option>
                          ))}
                        </select>
                      </div>

                      <div>
                        <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                          Division
                        </label>
                        <select
                          value={formDivision}
                          onChange={(e) => setFormDivision(e.target.value)}
                          className="w-full text-xs rounded-lg border border-slate-300 p-2"
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
                          Exam
                        </label>
                        <select
                          value={formExam}
                          onChange={(e) => setFormExam(e.target.value)}
                          className="w-full text-xs rounded-lg border border-slate-300 p-2"
                        >
                          {exams.map((ex) => (
                            <option key={ex.id} value={ex.id}>
                              {ex.name}
                            </option>
                          ))}
                        </select>
                      </div>

                      <div>
                        <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                          Shift
                        </label>
                        <select
                          value={formShift}
                          onChange={(e) => setFormShift(e.target.value)}
                          className="w-full text-xs rounded-lg border border-slate-300 p-2"
                        >
                          <option value="Morning">Morning (09:00 AM)</option>
                          <option value="Afternoon">Afternoon (01:00 PM)</option>
                        </select>
                      </div>
                    </div>

                    {/* Schedule Lines */}
                    <div className="pt-2">
                      <div className="flex items-center justify-between mb-2">
                        <label className="text-xs font-bold text-slate-700">Scheduled Exam Papers</label>
                        <button
                          type="button"
                          onClick={handleAddLine}
                          className="text-xs text-[#1976d2] hover:underline font-semibold flex items-center gap-1"
                        >
                          <FiPlus /> Add Subject Paper
                        </button>
                      </div>

                      <div className="space-y-3">
                        {scheduleLines.map((line, idx) => (
                          <div
                            key={idx}
                            className="grid grid-cols-12 gap-2 items-center bg-slate-50 p-2.5 rounded-lg border border-slate-200"
                          >
                            <div className="col-span-4">
                              <select
                                value={line.subject}
                                onChange={(e) => handleLineChange(idx, 'subject', e.target.value)}
                                className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white"
                                required
                              >
                                <option value="">-- Select Subject --</option>
                                {subjectsList.map((s) => (
                                  <option key={s.id} value={s.name}>
                                    {s.name}
                                  </option>
                                ))}
                              </select>
                            </div>

                            <div className="col-span-3">
                              <input
                                type="date"
                                value={line.exam_date}
                                onChange={(e) => handleLineChange(idx, 'exam_date', e.target.value)}
                                className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white"
                                required
                              />
                            </div>

                            <div className="col-span-2">
                              <input
                                type="text"
                                value={line.time_start}
                                onChange={(e) => handleLineChange(idx, 'time_start', e.target.value)}
                                placeholder="Start Time"
                                className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white"
                              />
                            </div>

                            <div className="col-span-2">
                              <input
                                type="text"
                                value={line.time_end}
                                onChange={(e) => handleLineChange(idx, 'time_end', e.target.value)}
                                placeholder="End Time"
                                className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white"
                              />
                            </div>

                            <div className="col-span-1 text-center">
                              {scheduleLines.length > 1 && (
                                <button
                                  type="button"
                                  onClick={() => handleRemoveLine(idx)}
                                  className="text-rose-500 hover:text-rose-700"
                                >
                                  <FiTrash2 size={14} />
                                </button>
                              )}
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>

                    <div className="pt-4 border-t border-slate-200 flex justify-end gap-2">
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
                        className="px-5 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50"
                      >
                        {saving ? 'Saving...' : 'Save Timetable'}
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
