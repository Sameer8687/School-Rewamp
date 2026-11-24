import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import teacherService from '../../services/teacherService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiUserCheck,
  FiSave,
  FiRefreshCw,
  FiCheckCircle,
  FiAlertCircle,
  FiCalendar,
  FiEdit2
} from 'react-icons/fi';

export default function TeacherAssignmentPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);

  // Dropdown options
  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [divisions, setDivisions] = useState(['A', 'B', 'C', 'D']);
  const [teachers, setTeachers] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');

  // Form inputs
  const [standard, setStandard] = useState('');
  const [division, setDivision] = useState('');
  const [teacherEmpId, setTeacherEmpId] = useState('');
  const [assigning, setAssigning] = useState(false);
  const [feedback, setFeedback] = useState(null);

  // Current Assignments
  const [assignments, setAssignments] = useState([]);

  useEffect(() => {
    async function init() {
      try {
        const [optRes, teacherRes] = await Promise.all([
          fetchStudentOptions(),
          teacherService.getTeachers({ status: 'A' })
        ]);

        const years = optRes.academicYears || optRes.data?.academicYears || optRes.data?.options?.academicYears || [];
        const classes = optRes.classes || optRes.data?.classes || optRes.data?.options?.classes || [];
        const divs = optRes.divisions || optRes.data?.divisions || optRes.data?.options?.divisions || ['A', 'B', 'C', 'D'];
        setAcademicYears(years);
        setStandards(classes);
        setDivisions(divs);

        const active = years.find((y) => y.is_active) || years[0];
        if (active) setSelectedYear(String(active.stvacdm_id || active.id));
        if (classes.length > 0) setStandard(classes[0]);
        if (divs.length > 0) setDivision(divs[0]);

        if (teacherRes.data?.success) {
          setTeachers(teacherRes.data.teachers || []);
        }
      } catch (err) {
        console.error('Init error:', err);
      }
    }
    init();
  }, []);

  useEffect(() => {
    if (!selectedYear) return;
    loadAssignments();
  }, [selectedYear]);

  async function loadAssignments() {
    setLoading(true);
    setFeedback(null);
    try {
      const res = await teacherService.getAssignments(selectedYear);
      if (res.data?.success) {
        setAssignments(res.data.assignments || []);
      }
    } catch (err) {
      console.error('Failed to load assignments:', err);
    } finally {
      setLoading(false);
    }
  }

  async function handleAssign(e) {
    e.preventDefault();
    if (!standard || !division || !teacherEmpId) {
      alert('Standard, Division, and Teacher are required');
      return;
    }

    setAssigning(true);
    setFeedback(null);
    try {
      const res = await teacherService.assignClassTeacher({
        standard,
        division,
        teacher_emp_id: teacherEmpId,
        academic_year_id: selectedYear
      });

      if (res.data?.success) {
        setFeedback({
          type: 'success',
          text: `Class Teacher assigned successfully for Std ${standard} - ${division}!`
        });
        await loadAssignments();
      }
    } catch (err) {
      console.error('Assignment failed:', err);
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to assign class teacher'
      });
    } finally {
      setAssigning(false);
    }
  }

  function handleEditAssignment(a) {
    setStandard(a.SPATESD_std);
    setDivision(a.SPATESD_div);
    setTeacherEmpId(a.SPATESD_teacher_id);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiUserCheck className="text-[#1976d2]" /> Class Teacher Assignment
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Assign official class teachers to standards and divisions for attendance and report cards
              </p>
            </div>

            {/* Academic Year Selector */}
            <div className="flex items-center gap-2">
              <span className="text-xs font-semibold text-slate-500">Academic Year:</span>
              <select
                value={selectedYear}
                onChange={(e) => setSelectedYear(e.target.value)}
                className="px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
              >
                {academicYears.map((y) => (
                  <option key={y.stvacdm_id} value={y.stvacdm_id}>
                    {y.stvacdm_year}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* Feedback */}
          {feedback && (
            <div
              className={`p-3.5 rounded-xl text-sm flex items-center gap-2.5 ${
                feedback.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border border-rose-200'
              }`}
            >
              {feedback.type === 'success' ? <FiCheckCircle /> : <FiAlertCircle />}
              {feedback.text}
            </div>
          )}

          {/* Assignment Form */}
          <form
            onSubmit={handleAssign}
            className="bg-white p-6 rounded-xl shadow-sm border border-slate-200/80 space-y-4"
          >
            <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
              <FiEdit2 className="text-[#1976d2]" /> Assign or Reassign Class Teacher
            </h3>

            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Standard / Class <span className="text-rose-500">*</span>
                </label>
                <select
                  required
                  value={standard}
                  onChange={(e) => setStandard(e.target.value)}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                >
                  {standards.map((c, i) => (
                    <option key={i} value={c}>
                      {c}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Division <span className="text-rose-500">*</span>
                </label>
                <select
                  required
                  value={division}
                  onChange={(e) => setDivision(e.target.value)}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                >
                  {divisions.map((d, i) => (
                    <option key={i} value={d}>
                      {d}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Teacher Faculty <span className="text-rose-500">*</span>
                </label>
                <select
                  required
                  value={teacherEmpId}
                  onChange={(e) => setTeacherEmpId(e.target.value)}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                >
                  <option value="">Select Faculty</option>
                  {teachers.map((t) => (
                    <option key={t.id} value={t.teacher_id}>
                      {t.name} ({t.teacher_id})
                    </option>
                  ))}
                </select>
              </div>

              <div className="flex items-end">
                <button
                  type="submit"
                  disabled={assigning}
                  className="w-full py-2 px-4 bg-[#1976d2] hover:bg-[#1565c0] disabled:bg-slate-300 text-white rounded-lg text-xs font-bold flex items-center justify-center gap-2 shadow-sm transition"
                >
                  {assigning ? (
                    <>
                      <FiRefreshCw className="animate-spin" /> Assigning...
                    </>
                  ) : (
                    <>
                      <FiSave /> Save Assignment
                    </>
                  )}
                </button>
              </div>
            </div>
          </form>

          {/* Assignments Table */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
              <h3 className="text-sm font-bold text-slate-900">
                Class Teachers Roster ({assignments.length} assigned)
              </h3>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                    <th className="p-3">#</th>
                    <th className="p-3">Standard</th>
                    <th className="p-3">Division</th>
                    <th className="p-3">Teacher Employee ID</th>
                    <th className="p-3">Teacher Full Name</th>
                    <th className="p-3">Assigned Date</th>
                    <th className="p-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan="7" className="p-8 text-center text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                        Loading assignments...
                      </td>
                    </tr>
                  ) : assignments.length > 0 ? (
                    assignments.map((a, idx) => (
                      <tr key={a.SPATESD_id} className="hover:bg-slate-50 transition">
                        <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                        <td className="p-3 font-bold text-slate-900">{a.SPATESD_std}</td>
                        <td className="p-3 font-semibold">{a.SPATESD_div}</td>
                        <td className="p-3 font-mono font-medium text-[#1976d2]">
                          {a.SPATESD_teacher_id}
                        </td>
                        <td className="p-3 font-semibold text-slate-900">
                          {a.teacher_name || 'Assigned'}
                        </td>
                        <td className="p-3 text-slate-500">
                          {a.SPATESD_assigned_date
                            ? new Date(a.SPATESD_assigned_date).toLocaleDateString()
                            : '-'}
                        </td>
                        <td className="p-3 text-center">
                          <button
                            onClick={() => handleEditAssignment(a)}
                            className="px-2.5 py-1 bg-[#e3f2fd] text-[#1976d2] hover:bg-[#bbdefb] rounded text-xs font-semibold transition"
                          >
                            Reassign
                          </button>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="7" className="p-8 text-center text-slate-400">
                        No class teachers assigned yet for this academic year.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
