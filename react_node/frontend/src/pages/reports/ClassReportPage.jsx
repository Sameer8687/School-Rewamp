import React, { useEffect, useState } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  LuUsers,
  LuSearch,
  LuBookOpen,
  LuCalendar,
  LuMessageSquare,
  LuAward,
  LuPhone,
  LuChevronRight,
  LuPrinter,
} from 'react-icons/lu';
import classReportService from '../../services/classReportService';

export default function ClassReportPage() {
  const [options, setOptions] = useState({ active_year: '', standards: [], divisions: [] });
  const [standard, setStandard] = useState('');
  const [division, setDivision] = useState('');
  const [students, setStudents] = useState([]);
  const [selectedStudentId, setSelectedStudentId] = useState(null);
  const [profileData, setProfileData] = useState(null);
  const [search, setSearch] = useState('');
  const [loadingList, setLoadingList] = useState(false);
  const [loadingProfile, setLoadingProfile] = useState(false);
  const [activeTab, setActiveTab] = useState('overview'); // 'overview' | 'marks' | 'attendance' | 'remarks'

  useEffect(() => {
    (async () => {
      try {
        const res = await classReportService.getOptions();
        if (res.success) {
          setOptions(res.data);
          if (res.data.standards.length > 0) {
            setStandard(res.data.standards[0].standard);
          }
        }
      } catch (err) {
        console.error('Failed to load class report options:', err);
      }
    })();
  }, []);

  const loadStudents = async () => {
    if (!standard) return;
    setLoadingList(true);
    try {
      const res = await classReportService.getClassStudents({
        standard,
        division: division || undefined,
      });
      if (res.success) {
        const list = res.data || [];
        setStudents(list);
        if (list.length > 0) {
          loadStudentProfile(list[0].student_id);
        } else {
          setSelectedStudentId(null);
          setProfileData(null);
        }
      }
    } catch (err) {
      console.error('Failed to load class students:', err);
    } finally {
      setLoadingList(false);
    }
  };

  useEffect(() => {
    if (standard) {
      loadStudents();
    }
  }, [standard, division]);

  const loadStudentProfile = async (id) => {
    setSelectedStudentId(id);
    setLoadingProfile(true);
    try {
      const res = await classReportService.getStudentProfileData(id);
      if (res.success) {
        setProfileData(res.data);
      }
    } catch (err) {
      console.error('Failed to load student profile:', err);
    } finally {
      setLoadingProfile(false);
    }
  };

  const filteredStudents = students.filter((s) => {
    const q = search.trim().toLowerCase();
    if (!q) return true;
    return (
      (s.student_name || '').toLowerCase().includes(q) ||
      (s.student_code || '').toLowerCase().includes(q) ||
      (s.gr_number || '').toLowerCase().includes(q)
    );
  });

  const info = profileData?.info;
  const marks = profileData?.marks || [];
  const attendance = profileData?.attendance || [];
  const summary = profileData?.attendance_summary || { present: 0, absent: 0, other: 0 };
  const remarks = profileData?.remarks || [];

  return (
    <div className="flex min-h-screen bg-[#f7f7f5] text-[#202020]">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0">
        <TopNav />
        <main className="p-4 sm:p-6 lg:p-8 flex-1 space-y-6">
          {/* Header */}
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 print:hidden">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <LuUsers className="text-[#1976d2]" />
                Student Profile DB & Class Report
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Browse class rosters and inspect full student profiles with academic marks, daily attendance, and teacher remarks.
              </p>
            </div>

            <button
              onClick={() => window.print()}
              className="inline-flex items-center gap-2 rounded-lg bg-[#1976d2] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1565c0]"
            >
              <LuPrinter className="w-4 h-4" />
              Print Profile
            </button>
          </div>

      {/* Class Selection Bar */}
      <div className="bg-white rounded-xl border border-slate-200 p-4 flex flex-wrap gap-4 items-end print:hidden">
        <div>
          <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Standard</label>
          <select
            value={standard}
            onChange={(e) => setStandard(e.target.value)}
            className="rounded-lg border border-slate-300 px-3 py-2 text-sm min-w-[140px]"
          >
            {options.standards.map((s) => (
              <option key={s.standard} value={s.standard}>
                {s.standard}
              </option>
            ))}
          </select>
        </div>
        <div>
          <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Division</label>
          <select
            value={division}
            onChange={(e) => setDivision(e.target.value)}
            className="rounded-lg border border-slate-300 px-3 py-2 text-sm min-w-[120px]"
          >
            <option value="">All Divisions</option>
            {options.divisions.map((d) => (
              <option key={d.division} value={d.division}>
                {d.division}
              </option>
            ))}
          </select>
        </div>
        <div className="flex-1 min-w-[200px]">
          <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Search in Class</label>
          <div className="relative">
            <LuSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Name, ID, GR No..."
              className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm"
            />
          </div>
        </div>
      </div>

      {/* 2-Column Explorer: Left Roster List (4 cols), Right Profile (8 cols) */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {/* Student List */}
        <div className="lg:col-span-4 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col max-h-[700px] print:hidden">
          <div className="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <span className="text-xs font-semibold uppercase text-slate-600 tracking-wider">
              Enrolled Students ({filteredStudents.length})
            </span>
          </div>

          <div className="overflow-y-auto divide-y divide-slate-100 flex-1">
            {loadingList ? (
              <div className="p-8 text-center text-sm text-slate-500">Loading students...</div>
            ) : filteredStudents.length === 0 ? (
              <div className="p-8 text-center text-sm text-slate-500">
                No active students found in this class.
              </div>
            ) : (
              filteredStudents.map((s) => {
                const isSelected = selectedStudentId === s.student_id;
                return (
                  <button
                    key={s.student_id}
                    type="button"
                    onClick={() => loadStudentProfile(s.student_id)}
                    className={`w-full text-left px-4 py-3 transition-colors flex items-center justify-between cursor-pointer ${
                      isSelected
                        ? 'bg-[#e3f2fd]/80 border-l-4 border-[#1976d2]'
                        : 'hover:bg-slate-50'
                    }`}
                  >
                    <div className="min-w-0 pr-2">
                      <div className="font-semibold text-sm text-slate-900 truncate">
                        {s.student_name}
                      </div>
                      <div className="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                        <span className="font-mono">{s.student_code}</span>
                        {s.roll_no && <span>• Roll {s.roll_no}</span>}
                        {s.division && <span>• Div {s.division}</span>}
                      </div>
                    </div>
                    <LuChevronRight
                      className={`w-4 h-4 shrink-0 ${
                        isSelected ? 'text-[#1976d2]' : 'text-slate-400'
                      }`}
                    />
                  </button>
                );
              })
            )}
          </div>
        </div>

        {/* Profile Card */}
        <div className="lg:col-span-8 bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
          {loadingProfile ? (
            <div className="py-20 text-center text-slate-500">Loading student profile...</div>
          ) : !info ? (
            <div className="py-20 text-center text-slate-500">
              Select a student from the list to view their complete profile.
            </div>
          ) : (
            <>
              {/* Profile Header */}
              <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-5 border-b border-slate-200">
                <div>
                  <h2 className="text-xl font-bold text-slate-900">{info.student_name}</h2>
                  <div className="flex flex-wrap items-center gap-3 text-xs text-slate-600 mt-1">
                    <span className="font-semibold text-[#1976d2] bg-[#e3f2fd] px-2 py-0.5 rounded">
                      ID: {info.student_code}
                    </span>
                    <span>GR No: {info.gr_number || '—'}</span>
                    <span>Class: {info.standard} - {info.division || 'A'}</span>
                    {info.roll_no && <span>Roll: {info.roll_no}</span>}
                  </div>
                </div>

                {info.contact && (
                  <a
                    href={`tel:+91${info.contact}`}
                    className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition-colors"
                  >
                    <LuPhone className="w-3.5 h-3.5" />
                    Call Parent
                  </a>
                )}
              </div>

              {/* Bio Grid */}
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div className="p-3 bg-slate-50 rounded-lg border border-slate-100">
                  <div className="text-slate-500 font-semibold uppercase">Father's Name</div>
                  <div className="text-slate-900 font-medium mt-1 truncate">{info.father_name || '—'}</div>
                </div>
                <div className="p-3 bg-slate-50 rounded-lg border border-slate-100">
                  <div className="text-slate-500 font-semibold uppercase">Date of Birth</div>
                  <div className="text-slate-900 font-medium mt-1">{info.dob || '—'}</div>
                </div>
                <div className="p-3 bg-slate-50 rounded-lg border border-slate-100">
                  <div className="text-slate-500 font-semibold uppercase">Blood Group</div>
                  <div className="text-slate-900 font-medium mt-1">{info.blood_group || '—'}</div>
                </div>
                <div className="p-3 bg-slate-50 rounded-lg border border-slate-100">
                  <div className="text-slate-500 font-semibold uppercase">Attendance</div>
                  <div className="text-emerald-700 font-semibold mt-1">
                    {summary.present} P / {summary.absent} A
                  </div>
                </div>
              </div>

              {/* Tabs */}
              <div className="border-b border-slate-200 flex gap-2 print:hidden">
                <button
                  type="button"
                  onClick={() => setActiveTab('overview')}
                  className={`px-4 py-2 text-xs font-semibold border-b-2 transition-colors ${
                    activeTab === 'overview'
                      ? 'border-[#1976d2] text-[#1976d2]'
                      : 'border-transparent text-slate-500 hover:text-slate-700'
                  }`}
                >
                  Marks & Performance ({marks.length})
                </button>
                <button
                  type="button"
                  onClick={() => setActiveTab('attendance')}
                  className={`px-4 py-2 text-xs font-semibold border-b-2 transition-colors ${
                    activeTab === 'attendance'
                      ? 'border-[#1976d2] text-[#1976d2]'
                      : 'border-transparent text-slate-500 hover:text-slate-700'
                  }`}
                >
                  Attendance History ({attendance.length})
                </button>
                <button
                  type="button"
                  onClick={() => setActiveTab('remarks')}
                  className={`px-4 py-2 text-xs font-semibold border-b-2 transition-colors ${
                    activeTab === 'remarks'
                      ? 'border-[#1976d2] text-[#1976d2]'
                      : 'border-transparent text-slate-500 hover:text-slate-700'
                  }`}
                >
                  Teacher Remarks ({remarks.length})
                </button>
              </div>

              {/* Tab 1: Marks */}
              {activeTab === 'overview' && (
                <div className="space-y-3">
                  <h3 className="text-sm font-semibold text-slate-800 flex items-center gap-1.5">
                    <LuAward className="text-[#1976d2] w-4 h-4" /> Examination Marks
                  </h3>
                  {marks.length === 0 ? (
                    <div className="p-6 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs text-slate-500">
                      No exam marks records recorded for this academic year.
                    </div>
                  ) : (
                    <div className="overflow-x-auto border border-slate-200 rounded-lg">
                      <table className="min-w-full divide-y divide-slate-200 text-xs">
                        <thead className="bg-slate-50">
                          <tr>
                            <th className="px-3 py-2 text-left font-semibold text-slate-600">Exam</th>
                            <th className="px-3 py-2 text-left font-semibold text-slate-600">Subject</th>
                            <th className="px-3 py-2 text-right font-semibold text-slate-600">Obtained</th>
                            <th className="px-3 py-2 text-right font-semibold text-slate-600">Max</th>
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                          {marks.map((m) => (
                            <tr key={m.id} className="hover:bg-slate-50">
                              <td className="px-3 py-2 font-medium text-slate-800">{m.exam_name}</td>
                              <td className="px-3 py-2 text-slate-700">{m.subject}</td>
                              <td className="px-3 py-2 text-right font-bold text-[#1976d2]">
                                {m.obtained_marks}
                              </td>
                              <td className="px-3 py-2 text-right text-slate-500">{m.max_marks}</td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  )}
                </div>
              )}

              {/* Tab 2: Attendance */}
              {activeTab === 'attendance' && (
                <div className="space-y-3">
                  <h3 className="text-sm font-semibold text-slate-800 flex items-center gap-1.5">
                    <LuCalendar className="text-[#1976d2] w-4 h-4" /> Recent Attendance Days
                  </h3>
                  {attendance.length === 0 ? (
                    <div className="p-6 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs text-slate-500">
                      No attendance days found.
                    </div>
                  ) : (
                    <div className="overflow-x-auto border border-slate-200 rounded-lg max-h-80">
                      <table className="min-w-full divide-y divide-slate-200 text-xs">
                        <thead className="bg-slate-50 sticky top-0">
                          <tr>
                            <th className="px-3 py-2 text-left font-semibold text-slate-600">Date</th>
                            <th className="px-3 py-2 text-left font-semibold text-slate-600">Status</th>
                            <th className="px-3 py-2 text-left font-semibold text-slate-600">Comment</th>
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                          {attendance.map((a, i) => (
                            <tr key={i} className="hover:bg-slate-50">
                              <td className="px-3 py-2 font-medium text-slate-800">{a.date}</td>
                              <td className="px-3 py-2">
                                <span
                                  className={`inline-block px-2 py-0.5 rounded text-[11px] font-semibold ${
                                    a.status === 'P'
                                      ? 'bg-emerald-50 text-emerald-700'
                                      : 'bg-red-50 text-red-700'
                                  }`}
                                >
                                  {a.status === 'P' ? 'Present' : a.status === 'A' ? 'Absent' : a.status}
                                </span>
                              </td>
                              <td className="px-3 py-2 text-slate-500">{a.comment || '—'}</td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  )}
                </div>
              )}

              {/* Tab 3: Remarks */}
              {activeTab === 'remarks' && (
                <div className="space-y-3">
                  <h3 className="text-sm font-semibold text-slate-800 flex items-center gap-1.5">
                    <LuMessageSquare className="text-[#1976d2] w-4 h-4" /> Remarks Log
                  </h3>
                  {remarks.length === 0 ? (
                    <div className="p-6 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs text-slate-500">
                      No teacher remarks recorded for this student.
                    </div>
                  ) : (
                    <div className="space-y-2">
                      {remarks.map((r) => (
                        <div
                          key={r.id}
                          className="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-1.5 text-xs"
                        >
                          <div className="flex justify-between items-center text-slate-500">
                            <span className="font-semibold text-slate-700">
                              {r.teacher_name || 'Teacher'}
                            </span>
                            <span>{r.date}</span>
                          </div>
                          <div className="text-slate-800 font-medium">{r.remark}</div>
                          {r.reply && (
                            <div className="mt-1 pl-3 border-l-2 border-[#1976d2] text-[#0d47a1] bg-[#e3f2fd]/50 p-1.5 rounded">
                              <span className="font-semibold text-[#1976d2]">Parent Reply: </span>
                              {r.reply}
                            </div>
                          )}
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              )}
            </>
          )}
        </div>
      </div>
        </main>
      </div>
    </div>
  );
}

