import React, { useEffect, useState } from 'react';
import { LuMessageSquare, LuPlus, LuTrash2, LuCheck, LuSearch, LuRefreshCw } from 'react-icons/lu';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import studentExtrasService from '../../services/studentExtrasService';
import { fetchStudents } from '../../services/studentService';

export default function StudentRemarksPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [activeTab, setActiveTab] = useState('teacher'); // 'teacher' | 'admin'
  const [options, setOptions] = useState({ standards: [], divisions: [], academic_years: [] });
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [standardFilter, setStandardFilter] = useState('all');
  const [teacherRemarks, setTeacherRemarks] = useState([]);
  const [adminNotes, setAdminNotes] = useState([]);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(null);

  // Helper to safely get standard label
  const getStdName = (s) => (typeof s === 'string' ? s : s?.standard || s?.name || '');

  // Student search autocomplete
  const [studentQuery, setStudentQuery] = useState('');
  const [studentResults, setStudentResults] = useState([]);
  const [selectedStudent, setSelectedStudent] = useState(null);

  // Teacher remark form
  const [remarkText, setRemarkText] = useState('');
  const [remarkStd, setRemarkStd] = useState('');
  const [replyDrafts, setReplyDrafts] = useState({});

  // Admin note form
  const [dueDate, setDueDate] = useState('');
  const [noteText, setNoteText] = useState('');

  useEffect(() => {
    (async () => {
      try {
        const res = await studentExtrasService.getOptions();
        if (res.success) {
          setOptions(res.data);
          const rawStds = res.data.standards || [];
          if (rawStds.length > 0) {
            setRemarkStd(getStdName(rawStds[0]));
          }
          const years = res.data.academic_years || [];
          setAcademicYears(years);
          const actYear = res.data.active_year || (years[0]?.id ? String(years[0].id) : '');
          setSelectedYear(String(actYear));
          loadRemarks(standardFilter, actYear);
        } else {
          loadRemarks();
        }
      } catch (err) {
        loadRemarks();
      }
    })();
  }, []);

  const showMessage = (type, text) => {
    setMessage({ type, text });
    setTimeout(() => setMessage(null), 4000);
  };

  const loadRemarks = async (std = standardFilter, yr = selectedYear) => {
    setLoading(true);
    try {
      const res = await studentExtrasService.getRemarksAndNotes({
        standard: std,
        academic_year: yr || undefined,
      });
      if (res.success) {
        setTeacherRemarks(res.data.teacher_remarks || []);
        setAdminNotes(res.data.admin_notes || []);
      }
    } catch (err) {
      showMessage('error', 'Failed to load remarks');
    } finally {
      setLoading(false);
    }
  };

  const handleStudentSearch = async (val) => {
    setStudentQuery(val);
    if (val.trim().length < 2) {
      setStudentResults([]);
      return;
    }
    try {
      const res = await fetchStudents({ search: val, limit: 8 });
      const list = res.students || res.data?.students || (Array.isArray(res.data) ? res.data : (res.data?.data || []));
      setStudentResults(list);
    } catch (err) {
      setStudentResults([]);
    }
  };

  const pickStudent = (s) => {
    const id = s.id || s.student_id;
    const name =
      s.student_name ||
      [s.SPRIDEN_surname, s.SPRIDEN_student_name, s.SPRIDEN_father_name].filter(Boolean).join(' ');
    setSelectedStudent({
      id,
      name,
      code: s.SPRIDEN_student_id || s.student_code || '',
      standard: s.SPRIDEN_admission_class || s.standard || remarkStd,
    });
    if (s.SPRIDEN_admission_class || s.standard) {
      setRemarkStd(s.SPRIDEN_admission_class || s.standard);
    }
    setStudentQuery(`${name} (${s.SPRIDEN_student_id || id})`);
    setStudentResults([]);
  };

  const handleCreateRemark = async (e) => {
    e.preventDefault();
    if (!selectedStudent || !remarkText.trim()) {
      showMessage('error', 'Please select a student and enter a remark.');
      return;
    }
    try {
      const res = await studentExtrasService.createRemark({
        student_id: selectedStudent.id,
        standard: remarkStd,
        remark: remarkText.trim(),
        academic_year: selectedYear,
      });
      if (res.success) {
        showMessage('success', 'Student remark added successfully.');
        setRemarkText('');
        loadRemarks(standardFilter, selectedYear);
      }
    } catch (err) {
      showMessage('error', err.response?.data?.message || 'Failed to add remark');
    }
  };

  const handleReplyRemark = async (id) => {
    const reply = replyDrafts[id];
    if (!reply || !reply.trim()) return;
    try {
      const res = await studentExtrasService.replyRemark(id, reply.trim());
      if (res.success) {
        showMessage('success', 'Parent reply saved.');
        setReplyDrafts((prev) => ({ ...prev, [id]: '' }));
        loadRemarks();
      }
    } catch (err) {
      showMessage('error', 'Failed to save reply');
    }
  };

  const handleDeleteRemark = async (id) => {
    if (!window.confirm('Delete this remark?')) return;
    try {
      const res = await studentExtrasService.deleteRemark(id);
      if (res.success) {
        showMessage('success', 'Remark deleted.');
        loadRemarks();
      }
    } catch (err) {
      showMessage('error', 'Failed to delete remark');
    }
  };

  const handleCreateAdminNote = async (e) => {
    e.preventDefault();
    if (!selectedStudent || !noteText.trim()) {
      showMessage('error', 'Please select a student and enter a note.');
      return;
    }
    try {
      const res = await studentExtrasService.createAdminNote({
        student_id: selectedStudent.id,
        student_name: selectedStudent.name,
        due_date: dueDate,
        note: noteText.trim(),
      });
      if (res.success) {
        showMessage('success', 'Administrative note saved.');
        setNoteText('');
        setDueDate('');
        loadRemarks();
      }
    } catch (err) {
      showMessage('error', err.response?.data?.message || 'Failed to save note');
    }
  };

  const handleDeleteAdminNote = async (id) => {
    if (!window.confirm('Delete this administrative note?')) return;
    try {
      const res = await studentExtrasService.deleteAdminNote(id);
      if (res.success) {
        showMessage('success', 'Administrative note deleted.');
        loadRemarks();
      }
    } catch (err) {
      showMessage('error', 'Failed to delete note');
    }
  };

  return (
    <div className="flex h-screen bg-[#F7F7F5] text-[#202020] font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-7xl mx-auto space-y-6">
            {/* Page Header */}
            <div>
              <div className="flex items-center gap-2.5">
                <span className="p-2.5 bg-[#e3f2fd] text-[#1976d2] rounded-xl shadow-sm">
                  <LuMessageSquare className="w-5 h-5" />
                </span>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight text-[#202020]">
                  Student Remarks & Administrative Comments
                </h1>
              </div>
              <p className="text-xs sm:text-sm text-[#73736F] mt-1">
                Manage teacher-student remarks with parent replies, and administrative fee/due notes
              </p>
            </div>

            {message && (
              <div
                className={`rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold border ${
                  message.type === 'error'
                    ? 'bg-[#ffebee] text-[#c62828] border-[#ffcdd2]'
                    : 'bg-[#e8f5e9] text-[#2e7d32] border-[#c8e6c9]'
                }`}
              >
                {message.text}
              </div>
            )}

            {/* Tabs */}
            <div className="border-b border-[#E7E7E4] flex gap-2">
              <button
                onClick={() => setActiveTab('teacher')}
                className={`px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 transition-colors cursor-pointer ${
                  activeTab === 'teacher'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-[#73736F] hover:text-[#202020]'
                }`}
              >
                Teacher Remarks ({teacherRemarks.length})
              </button>
              <button
                onClick={() => setActiveTab('admin')}
                className={`px-4 py-2.5 text-xs sm:text-sm font-semibold border-b-2 transition-colors cursor-pointer ${
                  activeTab === 'admin'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-[#73736F] hover:text-[#202020]'
                }`}
              >
                Administrative Notes ({adminNotes.length})
              </button>
            </div>

            {/* Shared Student Picker & Form Card */}
            <div className="bg-white rounded-2xl border border-[#E7E7E4] shadow-sm p-5 space-y-4">
              <div className="relative max-w-lg">
                <label className="block text-xs font-semibold text-[#73736F] uppercase tracking-wider mb-1">
                  Select Student (Search by Name / ID)
                </label>
                <div className="relative">
                  <LuSearch className="absolute left-3.5 top-3 text-[#73736F] w-4 h-4" />
                  <input
                    type="text"
                    value={studentQuery}
                    onChange={(e) => handleStudentSearch(e.target.value)}
                    placeholder="Type student name or ID..."
                    className="w-full pl-10 pr-4 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2] transition"
                  />
                </div>

                {studentResults.length > 0 && (
                  <div className="absolute left-0 right-0 mt-1 max-h-56 overflow-y-auto bg-white border border-[#E7E7E4] rounded-xl shadow-xl z-30 divide-y divide-[#E7E7E4]">
                    {studentResults.map((s) => {
                      const id = s.id || s.student_id;
                      const name =
                        s.student_name ||
                        [s.SPRIDEN_surname, s.SPRIDEN_student_name, s.SPRIDEN_father_name]
                          .filter(Boolean)
                          .join(' ');
                      return (
                        <div
                          key={id}
                          onClick={() => pickStudent(s)}
                          className="px-3.5 py-2 hover:bg-[#e3f2fd] cursor-pointer text-xs transition"
                        >
                          <div className="font-bold text-[#202020]">{name}</div>
                          <div className="text-[11px] text-[#73736F]">
                            ID: {s.SPRIDEN_student_id || s.student_code || id} • Std:{' '}
                            {s.SPRIDEN_admission_class || s.standard || '—'}
                          </div>
                        </div>
                      );
                    })}
                  </div>
                )}

                {selectedStudent && (
                  <div className="mt-2 text-xs font-semibold text-[#1976d2] bg-[#e3f2fd] px-3 py-1.5 rounded-xl border border-[#bbdefb] inline-flex items-center gap-2">
                    <span>
                      Selected: <b>{selectedStudent.name}</b> ({selectedStudent.code || selectedStudent.id})
                    </span>
                    <button
                      type="button"
                      onClick={() => {
                        setSelectedStudent(null);
                        setStudentQuery('');
                      }}
                      className="text-[#c62828] hover:underline font-bold ml-1 cursor-pointer"
                    >
                      Clear
                    </button>
                  </div>
                )}
              </div>

              {/* Form by Tab */}
              {activeTab === 'teacher' ? (
                <form onSubmit={handleCreateRemark} className="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                  <div>
                    <label className="block text-xs font-semibold text-[#73736F] uppercase tracking-wider mb-1">
                      Standard
                    </label>
                    <select
                      value={remarkStd}
                      onChange={(e) => setRemarkStd(e.target.value)}
                      className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                    >
                      {options.standards.map((s, idx) => {
                        const val = getStdName(s);
                        return (
                          <option key={idx} value={val}>
                            {val}
                          </option>
                        );
                      })}
                    </select>
                  </div>
                  <div className="md:col-span-2">
                    <label className="block text-xs font-semibold text-[#73736F] uppercase tracking-wider mb-1">
                      Remark
                    </label>
                    <input
                      type="text"
                      value={remarkText}
                      onChange={(e) => setRemarkText(e.target.value)}
                      placeholder="Enter remark for student / parent..."
                      className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                    />
                  </div>
                  <button
                    type="submit"
                    className="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1976d2] hover:bg-[#1565c0] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-sm transition cursor-pointer"
                  >
                    <LuPlus className="w-4 h-4" /> Add Remark
                  </button>
                </form>
              ) : (
                <form onSubmit={handleCreateAdminNote} className="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                  <div>
                    <label className="block text-xs font-semibold text-[#73736F] uppercase tracking-wider mb-1">
                      Due Date
                    </label>
                    <input
                      type="date"
                      value={dueDate}
                      onChange={(e) => setDueDate(e.target.value)}
                      className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                    />
                  </div>
                  <div className="md:col-span-2">
                    <label className="block text-xs font-semibold text-[#73736F] uppercase tracking-wider mb-1">
                      Administrative Note
                    </label>
                    <input
                      type="text"
                      value={noteText}
                      onChange={(e) => setNoteText(e.target.value)}
                      placeholder="Fee due / follow-up comment..."
                      className="w-full px-3.5 py-2 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs sm:text-sm text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                    />
                  </div>
                  <button
                    type="submit"
                    className="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1976d2] hover:bg-[#1565c0] px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-sm transition cursor-pointer"
                  >
                    <LuPlus className="w-4 h-4" /> Add Note
                  </button>
                </form>
              )}
            </div>

            {/* Table Section */}
            {activeTab === 'teacher' ? (
              <div className="bg-white rounded-2xl border border-[#E7E7E4] shadow-sm p-5 space-y-4">
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                  <h2 className="text-sm font-bold text-[#202020]">Teacher Remarks Log</h2>
                  <div className="flex flex-wrap items-center gap-2.5">
                    {academicYears.length > 0 && (
                      <div className="flex items-center gap-1.5">
                        <span className="text-xs text-[#73736F] font-semibold">Year:</span>
                        <select
                          value={selectedYear}
                          onChange={(e) => {
                            setSelectedYear(e.target.value);
                            loadRemarks(standardFilter, e.target.value);
                          }}
                          className="px-3 py-1.5 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs font-medium text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                        >
                          <option value="all">All Years</option>
                          {academicYears.map((y) => (
                            <option key={y.id || y.stvacdm_id} value={y.id || y.stvacdm_id}>
                              {y.year || y.stvacdm_year}
                            </option>
                          ))}
                        </select>
                      </div>
                    )}
                    <div className="flex items-center gap-1.5">
                      <span className="text-xs text-[#73736F] font-semibold">Std:</span>
                      <select
                        value={standardFilter}
                        onChange={(e) => {
                          setStandardFilter(e.target.value);
                          loadRemarks(e.target.value, selectedYear);
                        }}
                        className="px-3 py-1.5 bg-[#F7F7F5] border border-[#E7E7E4] rounded-xl text-xs font-medium text-[#202020] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                      >
                        <option value="all">All Standards</option>
                        {options.standards.map((s, idx) => {
                          const val = getStdName(s);
                          return (
                            <option key={idx} value={val}>
                              {val}
                            </option>
                          );
                        })}
                      </select>
                    </div>
                  </div>
                </div>

                <div className="overflow-x-auto rounded-xl border border-[#E7E7E4]">
                  <table className="w-full border-collapse text-xs">
                    <thead>
                      <tr className="bg-[#F7F7F5] border-b border-[#E7E7E4] text-[11px] font-bold text-[#73736F] uppercase tracking-wider">
                        <th className="py-3 px-4 text-left">Date</th>
                        <th className="py-3 px-4 text-left">Student</th>
                        <th className="py-3 px-4 text-left">Std</th>
                        <th className="py-3 px-4 text-left">Remark</th>
                        <th className="py-3 px-4 text-left">Parent Reply</th>
                        <th className="py-3 px-4 text-left">Status</th>
                        <th className="py-3 px-4 text-right">Action</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-[#E7E7E4] font-medium text-[#202020]">
                      {loading ? (
                        <tr>
                          <td colSpan={7} className="py-12 text-center text-[#73736F]">
                            <LuRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-2" />
                            Loading remarks...
                          </td>
                        </tr>
                      ) : teacherRemarks.length === 0 ? (
                        <tr>
                          <td colSpan={7} className="py-12 text-center text-[#73736F]">
                            No remarks found.
                          </td>
                        </tr>
                      ) : (
                        teacherRemarks.map((r) => (
                          <tr key={r.id} className="hover:bg-[#F5F5F3] transition-colors">
                            <td className="py-3 px-4 text-[11px] text-[#73736F] whitespace-nowrap">
                              {r.remark_date}
                            </td>
                            <td className="py-3 px-4 font-bold text-[#202020]">
                              {r.student_name || r.student_code || r.student_id}
                            </td>
                            <td className="py-3 px-4 text-[#73736F]">{r.standard}</td>
                            <td className="py-3 px-4 text-[#202020]">{r.remark}</td>
                            <td className="py-3 px-4">
                              {r.reply ? (
                                <span className="text-[#202020]">{r.reply}</span>
                              ) : (
                                <div className="flex gap-1.5">
                                  <input
                                    type="text"
                                    value={replyDrafts[r.id] || ''}
                                    onChange={(e) =>
                                      setReplyDrafts((d) => ({ ...d, [r.id]: e.target.value }))
                                    }
                                    placeholder="Add reply..."
                                    className="rounded-lg border border-[#E7E7E4] px-2.5 py-1 text-xs w-36 focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                                  />
                                  <button
                                    onClick={() => handleReplyRemark(r.id)}
                                    className="rounded-lg bg-[#1976d2] text-white px-2.5 py-1 text-xs font-semibold hover:bg-[#1565c0] cursor-pointer"
                                  >
                                    <LuCheck className="w-3.5 h-3.5" />
                                  </button>
                                </div>
                              )}
                            </td>
                            <td className="py-3 px-4">
                              <span
                                className={`inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold ${
                                  r.status === 'Replied'
                                    ? 'bg-[#e8f5e9] text-[#2e7d32] border border-[#c8e6c9]'
                                    : 'bg-[#fff3e0] text-[#e65100] border border-[#ffe082]'
                                }`}
                              >
                                {r.status || 'Pending'}
                              </span>
                            </td>
                            <td className="py-3 px-4 text-right">
                              <button
                                onClick={() => handleDeleteRemark(r.id)}
                                className="p-1.5 text-[#c62828] hover:bg-[#ffebee] rounded-lg transition cursor-pointer"
                                title="Delete"
                              >
                                <LuTrash2 className="w-3.5 h-3.5" />
                              </button>
                            </td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            ) : (
              <div className="bg-white rounded-2xl border border-[#E7E7E4] shadow-sm p-5 space-y-4">
                <h2 className="text-sm font-bold text-[#202020]">Administrative Notes Log</h2>
                <div className="overflow-x-auto rounded-xl border border-[#E7E7E4]">
                  <table className="w-full border-collapse text-xs">
                    <thead>
                      <tr className="bg-[#F7F7F5] border-b border-[#E7E7E4] text-[11px] font-bold text-[#73736F] uppercase tracking-wider">
                        <th className="py-3 px-4 text-left">Student ID</th>
                        <th className="py-3 px-4 text-left">Student Name</th>
                        <th className="py-3 px-4 text-left">Due Date</th>
                        <th className="py-3 px-4 text-left">Note</th>
                        <th className="py-3 px-4 text-right">Action</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-[#E7E7E4] font-medium text-[#202020]">
                      {adminNotes.length === 0 ? (
                        <tr>
                          <td colSpan={5} className="py-12 text-center text-[#73736F]">
                            No administrative notes found.
                          </td>
                        </tr>
                      ) : (
                        adminNotes.map((n) => (
                          <tr key={n.id} className="hover:bg-[#F5F5F3] transition-colors">
                            <td className="py-3 px-4 font-mono font-bold text-[#1976d2]">
                              {n.student_code || n.student_id}
                            </td>
                            <td className="py-3 px-4 font-bold text-[#202020]">{n.student_name}</td>
                            <td className="py-3 px-4 font-semibold text-[#e65100]">{n.due_date || '—'}</td>
                            <td className="py-3 px-4 text-[#202020]">{n.note}</td>
                            <td className="py-3 px-4 text-right">
                              <button
                                onClick={() => handleDeleteAdminNote(n.id)}
                                className="p-1.5 text-[#c62828] hover:bg-[#ffebee] rounded-lg transition cursor-pointer"
                                title="Delete"
                              >
                                <LuTrash2 className="w-3.5 h-3.5" />
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
          </div>
        </main>
      </div>
    </div>
  );
}
