import React, { useEffect, useState, useMemo } from 'react';
import { useLocation } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  LuUsers,
  LuBookOpen,
  LuPlus,
  LuTrash2,
  LuSave,
  LuSearch,
  LuLayers,
  LuCheck,
  LuX,
  LuPencil,
} from 'react-icons/lu';
import classManagementService from '../../services/classManagementService';

const getStdValue = (s) => (typeof s === 'object' && s !== null ? (s.standard || s.value || '') : (s || ''));
const getDivValue = (d) => (typeof d === 'object' && d !== null ? (d.division || d.value || '') : (d || ''));

export default function ClassManagementPage() {
  const location = useLocation();
  const [activeTab, setActiveTab] = useState('create'); // 'create' | 'roster' | 'subject-teacher'

  // Auto-switch tab based on CI3 route
  useEffect(() => {
    const p = (location.pathname || '').toLowerCase();
    if (p.includes('subject-teacher') || p.includes('subject_teacher')) {
      setActiveTab('subject-teacher');
    } else if (p.includes('create-class') || p.includes('create_class')) {
      setActiveTab('create');
    } else if (p.includes('/class') || p === 'class') {
      setActiveTab('roster');
    }
  }, [location.pathname]);
  const [options, setOptions] = useState({
    active_year: '',
    years: [],
    standards: [],
    divisions: [],
    subjects: [],
    teachers: [],
  });
  const [academicYear, setAcademicYear] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(null);

  // Tab 1: Create / Assign Class
  const [unassigned, setUnassigned] = useState([]);
  const [unassignedSearch, setUnassignedSearch] = useState('');
  const [targetStd, setTargetStd] = useState('');
  const [targetDiv, setTargetDiv] = useState('');
  const [selectedStudents, setSelectedStudents] = useState({}); // { student_id: roll_no }

  // Tab 2: View / Edit Class Roster
  const [rosterStd, setRosterStd] = useState('');
  const [rosterDiv, setRosterDiv] = useState('');
  const [roster, setRoster] = useState([]);
  const [editingRowId, setEditingRowId] = useState(null);
  const [editValues, setEditValues] = useState({ standard: '', division: '', roll_no: '' });

  // Tab 3: Subject-Teacher Allocation
  const [subjectTeachers, setSubjectTeachers] = useState([]);
  const [editingStId, setEditingStId] = useState(null);
  const [stForm, setStForm] = useState({
    teacher_code: '',
    standard: '',
    division: '',
    subject_id: '',
    academic_year: '',
  });
  const [stSearch, setStSearch] = useState('');

  useEffect(() => {
    loadOptions();
  }, []);

  const loadOptions = async () => {
    try {
      const res = await classManagementService.getOptions();
      if (res.success) {
        const d = res.data;
        setOptions(d);
        const curYear = d.active_year ? String(d.active_year) : '';
        setAcademicYear(curYear);
        if (d.standards && d.standards.length > 0) {
          const firstStd = getStdValue(d.standards[0]);
          setTargetStd(firstStd);
          setRosterStd(firstStd);
          setStForm((prev) => ({ ...prev, standard: firstStd }));
        }
        if (d.divisions && d.divisions.length > 0) {
          const firstDiv = getDivValue(d.divisions[0]);
          setTargetDiv(firstDiv);
          setRosterDiv(firstDiv);
          setStForm((prev) => ({ ...prev, division: firstDiv }));
        }
        if (curYear) {
          setStForm((prev) => ({ ...prev, academic_year: curYear }));
        }
      }
    } catch (err) {
      showMessage('error', err.response?.data?.message || 'Failed to load class options');
    }
  };

  // Dynamically filter subjects matching the selected standard (matches CI3 Teacher::getSubjectByStd)
  const availableSubjects = useMemo(() => {
    if (!stForm.standard) return options.subjects || [];
    const filtered = (options.subjects || []).filter(
      (sub) => !sub.standard || sub.standard.toString().trim().toLowerCase() === stForm.standard.toString().trim().toLowerCase()
    );
    return filtered.length > 0 ? filtered : (options.subjects || []);
  }, [options.subjects, stForm.standard]);

  useEffect(() => {
    if (!academicYear) return;
    if (activeTab === 'create') {
      loadUnassigned();
    } else if (activeTab === 'roster') {
      loadRoster();
    } else if (activeTab === 'subject-teacher') {
      loadSubjectTeachers();
    }
  }, [activeTab, academicYear]);

  const showMessage = (type, text) => {
    setMessage({ type, text });
    setTimeout(() => setMessage(null), 4500);
  };

  const loadUnassigned = async () => {
    setLoading(true);
    try {
      const res = await classManagementService.getUnassignedStudents(academicYear);
      if (res.success) setUnassigned(res.data || []);
    } catch (err) {
      showMessage('error', 'Failed to load unassigned students');
    } finally {
      setLoading(false);
    }
  };

  const loadRoster = async () => {
    setLoading(true);
    try {
      const res = await classManagementService.getClassRoster({
        academic_year: academicYear,
        standard: rosterStd,
        division: rosterDiv,
      });
      if (res.success) setRoster(res.data || []);
    } catch (err) {
      showMessage('error', 'Failed to load class roster');
    } finally {
      setLoading(false);
    }
  };

  const loadSubjectTeachers = async () => {
    setLoading(true);
    try {
      const res = await classManagementService.getSubjectTeachers(academicYear);
      if (res.success) setSubjectTeachers(res.data || []);
    } catch (err) {
      showMessage('error', 'Failed to load subject-teacher allocations');
    } finally {
      setLoading(false);
    }
  };

  const filteredUnassigned = useMemo(() => {
    const q = unassignedSearch.trim().toLowerCase();
    if (!q) return unassigned;
    return unassigned.filter(
      (s) =>
        (s.student_name || '').toLowerCase().includes(q) ||
        (s.student_code || '').toLowerCase().includes(q) ||
        (s.gr_number || '').toLowerCase().includes(q)
    );
  }, [unassigned, unassignedSearch]);

  const toggleStudentSelection = (studentId) => {
    setSelectedStudents((prev) => {
      const next = { ...prev };
      if (next[studentId] !== undefined) {
        delete next[studentId];
      } else {
        next[studentId] = '';
      }
      return next;
    });
  };

  const handleRollChange = (studentId, val) => {
    setSelectedStudents((prev) => ({ ...prev, [studentId]: val }));
  };

  const handleAssignClass = async (e) => {
    e.preventDefault();
    const ids = Object.keys(selectedStudents);
    if (ids.length === 0) {
      showMessage('error', 'Select at least one student to assign.');
      return;
    }
    if (!targetStd || !targetDiv) {
      showMessage('error', 'Please select target Standard and Division.');
      return;
    }
    setLoading(true);
    try {
      const payload = {
        academic_year: academicYear,
        standard: targetStd,
        division: targetDiv,
        students: ids.map((id) => ({
          student_id: Number(id),
          roll_no: selectedStudents[id] || null,
        })),
      };
      const res = await classManagementService.assignClass(payload);
      if (res.success) {
        showMessage('success', res.message);
        setSelectedStudents({});
        loadUnassigned();
      }
    } catch (err) {
      showMessage('error', err.response?.data?.message || 'Assignment failed');
    } finally {
      setLoading(false);
    }
  };

  const startEditRow = (row) => {
    setEditingRowId(row.id);
    setEditValues({
      standard: row.standard || '',
      division: row.division || '',
      roll_no: row.roll_no || '',
    });
  };

  const saveEditRow = async (id) => {
    try {
      const res = await classManagementService.updateClassAssignment(id, editValues);
      if (res.success) {
        showMessage('success', res.message);
        setEditingRowId(null);
        loadRoster();
      }
    } catch (err) {
      showMessage('error', err.response?.data?.message || 'Update failed');
    }
  };

  const handleSaveSubjectTeacher = async (e) => {
    e.preventDefault();
    if (!stForm.teacher_code || !stForm.standard || !stForm.division || !stForm.subject_id) {
      showMessage('error', 'Please select Teacher, Standard, Division, and Subject.');
      return;
    }
    const payload = {
      teacher_code: stForm.teacher_code,
      standard: stForm.standard,
      division: stForm.division,
      subject_id: stForm.subject_id,
      academic_year: stForm.academic_year || academicYear,
    };
    try {
      if (editingStId) {
        const res = await classManagementService.updateSubjectTeacher(editingStId, payload);
        if (res.success) {
          showMessage('success', res.message || 'Subject teacher allocation updated successfully.');
          setEditingStId(null);
          setStForm((prev) => ({ ...prev, subject_id: '' }));
          loadSubjectTeachers();
        }
      } else {
        const res = await classManagementService.createSubjectTeacher(payload);
        if (res.success) {
          showMessage('success', res.message || 'Subject teacher assigned successfully.');
          setStForm((prev) => ({ ...prev, subject_id: '' }));
          loadSubjectTeachers();
        }
      }
    } catch (err) {
      showMessage('error', err.response?.data?.message || 'Failed to save subject teacher');
    }
  };

  const startEditSubjectTeacher = (row) => {
    setEditingStId(row.id);
    setStForm({
      teacher_code: row.teacher_code || '',
      standard: row.standard || '',
      division: row.division || '',
      subject_id: row.subject_id ? String(row.subject_id) : '',
      academic_year: row.academic_year ? String(row.academic_year) : String(academicYear),
    });
  };

  const cancelEditSubjectTeacher = () => {
    setEditingStId(null);
    const firstStd = options.standards.length > 0 ? getStdValue(options.standards[0]) : '';
    const firstDiv = options.divisions.length > 0 ? getDivValue(options.divisions[0]) : 'A';
    setStForm({
      teacher_code: '',
      standard: firstStd,
      division: firstDiv,
      subject_id: '',
      academic_year: academicYear,
    });
  };

  const handleDeleteSubjectTeacher = async (id) => {
    if (!window.confirm('Remove this subject-teacher allocation?')) return;
    try {
      const res = await classManagementService.deleteSubjectTeacher(id);
      if (res.success) {
        showMessage('success', 'Allocation removed.');
        if (editingStId === id) cancelEditSubjectTeacher();
        loadSubjectTeachers();
      }
    } catch (err) {
      showMessage('error', 'Failed to remove allocation');
    }
  };

  const filteredSubjectTeachers = useMemo(() => {
    const q = stSearch.trim().toLowerCase();
    if (!q) return subjectTeachers;
    return subjectTeachers.filter(
      (r) =>
        (r.teacher_name || '').toLowerCase().includes(q) ||
        (r.subject_name || '').toLowerCase().includes(q) ||
        (r.standard || '').toLowerCase().includes(q)
    );
  }, [subjectTeachers, stSearch]);

  return (
    <div className="flex min-h-screen bg-[#f7f7f5] text-[#202020]">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0">
        <TopNav />
        <main className="p-4 sm:p-6 lg:p-8 flex-1 space-y-6">
          {/* Header */}
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <LuLayers className="text-[#1976d2]" />
                Class & Subject-Teacher Management
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Assign unassigned students to classes, manage class rosters & roll numbers, and allocate subject teachers.
              </p>
            </div>

            <div className="flex items-center gap-3">
              <label className="text-sm font-medium text-slate-600">Academic Year:</label>
              <select
                value={academicYear}
                onChange={(e) => setAcademicYear(e.target.value)}
                className="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-[#1976d2] focus:outline-none"
              >
                {options.years.map((y) => (
                  <option key={y.id} value={y.id}>
                    {y.name} {y.is_active ? '(Active)' : ''}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {message && (
            <div
              className={`rounded-lg px-4 py-3 text-sm font-medium ${
                message.type === 'error'
                  ? 'bg-red-50 text-red-700 border border-red-200'
                  : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
              }`}
            >
              {message.text}
            </div>
          )}

          {/* Tabs */}
          <div className="border-b border-slate-200 flex gap-2">
            <button
              onClick={() => setActiveTab('create')}
              className={`px-4 py-2.5 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 ${
                activeTab === 'create'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
              <LuPlus className="w-4 h-4" />
              Assign Class ({unassigned.length} unassigned)
            </button>
            <button
              onClick={() => setActiveTab('roster')}
              className={`px-4 py-2.5 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 ${
                activeTab === 'roster'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
              <LuUsers className="w-4 h-4" />
              View / Edit Class Roster
            </button>
            <button
              onClick={() => setActiveTab('subject-teacher')}
              className={`px-4 py-2.5 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 ${
                activeTab === 'subject-teacher'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-700'
              }`}
            >
              <LuBookOpen className="w-4 h-4" />
              Subject Teachers ({subjectTeachers.length})
        </button>
      </div>

      {/* TAB 1: CREATE / ASSIGN CLASS */}
      {activeTab === 'create' && (
        <form onSubmit={handleAssignClass} className="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-5">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                Target Standard
              </label>
              <select
                value={targetStd}
                onChange={(e) => setTargetStd(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              >
                {options.standards.map((s, idx) => {
                  const val = getStdValue(s);
                  return (
                    <option key={val || idx} value={val}>
                      {val}
                    </option>
                  );
                })}
              </select>
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                Target Division
              </label>
              <select
                value={targetDiv}
                onChange={(e) => setTargetDiv(e.target.value)}
                className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
              >
                {options.divisions.map((d, idx) => {
                  const val = getDivValue(d);
                  return (
                    <option key={val || idx} value={val}>
                      {val}
                    </option>
                  );
                })}
              </select>
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                Search Unassigned Students
              </label>
              <div className="relative">
                <LuSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
                <input
                  type="text"
                  value={unassignedSearch}
                  onChange={(e) => setUnassignedSearch(e.target.value)}
                  placeholder="Name, ID, or GR No..."
                  className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm"
                />
              </div>
            </div>
            <div className="flex justify-end">
              <button
                type="submit"
                disabled={loading || Object.keys(selectedStudents).length === 0}
                className="w-full md:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-[#1976d2] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1565c0] disabled:opacity-50"
              >
                <LuSave className="w-4 h-4" />
                Assign Selected ({Object.keys(selectedStudents).length})
              </button>
            </div>
          </div>

          <div className="overflow-x-auto border border-slate-200 rounded-lg">
            <table className="min-w-full divide-y divide-slate-200 text-sm">
              <thead className="bg-slate-50">
                <tr>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600 w-12">Select</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Student ID</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">GR No</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Student Name</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Admission Class</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600 w-36">Roll No (Optional)</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {loading ? (
                  <tr>
                    <td colSpan={6} className="px-4 py-8 text-center text-slate-500">
                      Loading unassigned students...
                    </td>
                  </tr>
                ) : filteredUnassigned.length === 0 ? (
                  <tr>
                    <td colSpan={6} className="px-4 py-8 text-center text-slate-500">
                      No unassigned active students found for this academic year.
                    </td>
                  </tr>
                ) : (
                  filteredUnassigned.map((s) => {
                    const isChecked = selectedStudents[s.student_id] !== undefined;
                    return (
                      <tr key={s.student_id} className={isChecked ? 'bg-[#e3f2fd]/40' : 'hover:bg-slate-50'}>
                        <td className="px-4 py-2.5">
                          <input
                            type="checkbox"
                            checked={isChecked}
                            onChange={() => toggleStudentSelection(s.student_id)}
                            className="h-4 w-4 rounded border-slate-300 text-[#1976d2]"
                          />
                        </td>
                        <td className="px-4 py-2.5 font-mono text-xs text-slate-700">{s.student_code}</td>
                        <td className="px-4 py-2.5 text-slate-600">{s.gr_number || '—'}</td>
                        <td className="px-4 py-2.5 font-medium text-slate-900">{s.student_name}</td>
                        <td className="px-4 py-2.5 text-slate-600">{s.admission_class || '—'}</td>
                        <td className="px-4 py-2.5">
                          <input
                            type="text"
                            disabled={!isChecked}
                            value={isChecked ? selectedStudents[s.student_id] : ''}
                            onChange={(e) => handleRollChange(s.student_id, e.target.value)}
                            placeholder="Roll #"
                            className="w-24 rounded border border-slate-300 px-2 py-1 text-xs disabled:bg-slate-100"
                          />
                        </td>
                      </tr>
                    );
                  })
                )}
              </tbody>
            </table>
          </div>
        </form>
      )}

      {/* TAB 2: VIEW / EDIT CLASS ROSTER */}
      {activeTab === 'roster' && (
        <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
          <div className="flex flex-wrap items-end gap-4">
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Standard</label>
              <select
                value={rosterStd}
                onChange={(e) => setRosterStd(e.target.value)}
                className="rounded-lg border border-slate-300 px-3 py-2 text-sm min-w-[140px]"
              >
                {options.standards.map((s, idx) => {
                  const val = getStdValue(s);
                  return (
                    <option key={val || idx} value={val}>
                      {val}
                    </option>
                  );
                })}
              </select>
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Division</label>
              <select
                value={rosterDiv}
                onChange={(e) => setRosterDiv(e.target.value)}
                className="rounded-lg border border-slate-300 px-3 py-2 text-sm min-w-[120px]"
              >
                {options.divisions.map((d, idx) => {
                  const val = getDivValue(d);
                  return (
                    <option key={val || idx} value={val}>
                      {val}
                    </option>
                  );
                })}
              </select>
            </div>
            <button
              type="button"
              onClick={loadRoster}
              className="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
            >
              Load Roster
            </button>
          </div>

          <div className="overflow-x-auto border border-slate-200 rounded-lg">
            <table className="min-w-full divide-y divide-slate-200 text-sm">
              <thead className="bg-slate-50">
                <tr>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Roll No</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Student ID</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">GR No</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Student Name</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Standard</th>
                  <th className="px-4 py-3 text-left font-semibold text-slate-600">Division</th>
                  <th className="px-4 py-3 text-right font-semibold text-slate-600">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {loading ? (
                  <tr>
                    <td colSpan={7} className="px-4 py-8 text-center text-slate-500">
                      Loading class roster...
                    </td>
                  </tr>
                ) : roster.length === 0 ? (
                  <tr>
                    <td colSpan={7} className="px-4 py-8 text-center text-slate-500">
                      No students assigned to {rosterStd} - {rosterDiv}.
                    </td>
                  </tr>
                ) : (
                  roster.map((row) => {
                    const isEditing = editingRowId === row.id;
                    return (
                      <tr key={row.id} className="hover:bg-slate-50">
                        <td className="px-4 py-2.5">
                          {isEditing ? (
                            <input
                              type="text"
                              value={editValues.roll_no}
                              onChange={(e) => setEditValues((v) => ({ ...v, roll_no: e.target.value }))}
                              className="w-20 rounded border border-slate-300 px-2 py-1 text-xs"
                            />
                          ) : (
                            row.roll_no || '—'
                          )}
                        </td>
                        <td className="px-4 py-2.5 font-mono text-xs text-slate-700">{row.student_code}</td>
                        <td className="px-4 py-2.5 text-slate-600">{row.gr_number || '—'}</td>
                        <td className="px-4 py-2.5 font-medium text-slate-900">{row.student_name}</td>
                        <td className="px-4 py-2.5">
                          {isEditing ? (
                            <select
                              value={editValues.standard}
                              onChange={(e) => setEditValues((v) => ({ ...v, standard: e.target.value }))}
                              className="rounded border border-slate-300 px-2 py-1 text-xs"
                            >
                              {options.standards.map((s, idx) => {
                                const val = getStdValue(s);
                                return (
                                  <option key={val || idx} value={val}>
                                    {val}
                                  </option>
                                );
                              })}
                            </select>
                          ) : (
                            row.standard
                          )}
                        </td>
                        <td className="px-4 py-2.5">
                          {isEditing ? (
                            <select
                              value={editValues.division}
                              onChange={(e) => setEditValues((v) => ({ ...v, division: e.target.value }))}
                              className="rounded border border-slate-300 px-2 py-1 text-xs"
                            >
                              {options.divisions.map((d, idx) => {
                                const val = getDivValue(d);
                                return (
                                  <option key={val || idx} value={val}>
                                    {val}
                                  </option>
                                );
                              })}
                            </select>
                          ) : (
                            row.division
                          )}
                        </td>
                        <td className="px-4 py-2.5 text-right space-x-2">
                          {isEditing ? (
                            <>
                              <button
                                onClick={() => saveEditRow(row.id)}
                                className="inline-flex items-center gap-1 rounded bg-emerald-600 px-2.5 py-1 text-xs font-medium text-white hover:bg-emerald-700"
                              >
                                <LuCheck className="w-3.5 h-3.5" /> Save
                              </button>
                              <button
                                onClick={() => setEditingRowId(null)}
                                className="inline-flex items-center gap-1 rounded bg-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-300"
                              >
                                <LuX className="w-3.5 h-3.5" /> Cancel
                              </button>
                            </>
                          ) : (
                            <button
                              onClick={() => startEditRow(row)}
                              className="text-[#1976d2] hover:text-[#1565c0] text-xs font-semibold"
                            >
                              Edit
                            </button>
                          )}
                        </td>
                      </tr>
                    );
                  })
                )}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* TAB 3: SUBJECT TEACHER ALLOCATION */}
      {activeTab === 'subject-teacher' && (
        <div className="space-y-5">
          <form
            onSubmit={handleSaveSubjectTeacher}
            className="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4"
          >
            <div className="flex items-center justify-between border-b border-slate-100 pb-3">
              <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wider">
                {editingStId ? 'Update Subject Teacher' : 'Assign Subject Teacher'}
              </h3>
              {editingStId && (
                <span className="text-xs bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full border border-amber-200 font-medium">
                  Editing record #{editingStId}
                </span>
              )}
            </div>

            <div className="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
              <div>
                <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                  Teacher <span className="text-red-500">*</span>
                </label>
                <select
                  value={stForm.teacher_code}
                  onChange={(e) => setStForm((f) => ({ ...f, teacher_code: e.target.value }))}
                  className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]"
                  required
                >
                  <option value="">Select Teacher Name</option>
                  {options.teachers.map((t) => {
                    const tName = t.teacher_name || t.name || t.teacher_code;
                    return (
                      <option key={t.teacher_code || t.id} value={t.teacher_code}>
                        {tName} {t.teacher_code ? `(${t.teacher_code})` : ''}
                      </option>
                    );
                  })}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                  Standard <span className="text-red-500">*</span>
                </label>
                <select
                  value={stForm.standard}
                  onChange={(e) => {
                    const newStd = e.target.value;
                    setStForm((f) => ({ ...f, standard: newStd, subject_id: '' }));
                  }}
                  className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]"
                  required
                >
                  <option value="">Select Standard</option>
                  {options.standards.map((s, idx) => {
                    const val = getStdValue(s);
                    return (
                      <option key={val || idx} value={val}>
                        {val}
                      </option>
                    );
                  })}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                  Division
                </label>
                <select
                  value={stForm.division}
                  onChange={(e) => setStForm((f) => ({ ...f, division: e.target.value }))}
                  className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]"
                >
                  <option value="">Select Division</option>
                  {options.divisions.map((d, idx) => {
                    const val = getDivValue(d);
                    return (
                      <option key={val || idx} value={val}>
                        {val}
                      </option>
                    );
                  })}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                  Subject <span className="text-red-500">*</span>
                </label>
                <select
                  value={stForm.subject_id}
                  onChange={(e) => setStForm((f) => ({ ...f, subject_id: e.target.value }))}
                  className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]"
                  required
                >
                  <option value="">Select Subject</option>
                  {availableSubjects.map((sub) => (
                    <option key={sub.id} value={sub.id}>
                      {sub.name} {sub.standard ? `(${sub.standard})` : ''}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
                  Academic Year <span className="text-red-500">*</span>
                </label>
                <select
                  value={stForm.academic_year || academicYear}
                  onChange={(e) => {
                    const val = e.target.value;
                    setStForm((f) => ({ ...f, academic_year: val }));
                    setAcademicYear(val);
                  }}
                  className="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]"
                  required
                >
                  <option value="">Select Year</option>
                  {options.years.map((y) => (
                    <option key={y.id} value={y.id}>
                      {y.year} {String(y.id) === String(options.active_year) ? '(Current)' : ''}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            <div className="flex items-center justify-end gap-3 pt-2">
              {editingStId && (
                <button
                  type="button"
                  onClick={cancelEditSubjectTeacher}
                  className="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                >
                  <LuX className="w-4 h-4" /> Cancel
                </button>
              )}
              <button
                type="submit"
                className="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1976d2] px-5 py-2 text-sm font-semibold text-white hover:bg-[#1565c0] shadow-sm transition-colors"
              >
                {editingStId ? (
                  <>
                    <LuSave className="w-4 h-4" />
                    Update Data
                  </>
                ) : (
                  <>
                    <LuPlus className="w-4 h-4" />
                    Assign Subject
                  </>
                )}
              </button>
            </div>
          </form>

          <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <h2 className="text-base font-semibold text-slate-800">Assigned Subject Teachers</h2>
                <p className="text-xs text-slate-500 mt-0.5">Click any row or edit button to update allocation</p>
              </div>
              <div className="relative w-full sm:w-72">
                <LuSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
                <input
                  type="text"
                  value={stSearch}
                  onChange={(e) => setStSearch(e.target.value)}
                  placeholder="Filter teacher, subject, class..."
                  className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-1.5 text-sm"
                />
              </div>
            </div>

            <div className="overflow-x-auto border border-slate-200 rounded-lg">
              <table className="min-w-full divide-y divide-slate-200 text-sm">
                <thead className="bg-slate-50">
                  <tr>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600 w-12">#</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Teacher Name</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Standard</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Division</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Subject</th>
                    <th className="px-4 py-3 text-left font-semibold text-slate-600">Academic Year</th>
                    <th className="px-4 py-3 text-right font-semibold text-slate-600 w-28">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {filteredSubjectTeachers.length === 0 ? (
                    <tr>
                      <td colSpan={7} className="px-4 py-8 text-center text-slate-500">
                        No subject-teacher allocations found.
                      </td>
                    </tr>
                  ) : (
                    filteredSubjectTeachers.map((r, idx) => (
                      <tr
                        key={r.id}
                        className={`hover:bg-blue-50/50 cursor-pointer transition-colors ${
                          editingStId === r.id ? 'bg-amber-50/60 font-semibold' : ''
                        }`}
                        onClick={() => startEditSubjectTeacher(r)}
                        title="Click to edit"
                      >
                        <td className="px-4 py-2.5 text-slate-400 font-mono text-xs">{idx + 1}</td>
                        <td className="px-4 py-2.5 font-medium text-slate-900">
                          {r.teacher_name || r.teacher_code}
                        </td>
                        <td className="px-4 py-2.5 text-slate-700">{r.standard}</td>
                        <td className="px-4 py-2.5 text-slate-700">{r.division}</td>
                        <td className="px-4 py-2.5 text-slate-700">{r.subject_name || r.subject_id}</td>
                        <td className="px-4 py-2.5 text-slate-600 text-xs">
                          {r.academic_year_name || r.academic_year || '—'}
                        </td>
                        <td
                          className="px-4 py-2.5 text-right space-x-2"
                          onClick={(e) => e.stopPropagation()}
                        >
                          <button
                            type="button"
                            onClick={() => startEditSubjectTeacher(r)}
                            className="inline-flex items-center gap-1 text-[#1976d2] hover:text-[#1565c0] text-xs font-semibold"
                            title="Edit"
                          >
                            <LuPencil className="w-3.5 h-3.5" /> Edit
                          </button>
                          <button
                            type="button"
                            onClick={() => handleDeleteSubjectTeacher(r.id)}
                            className="inline-flex items-center gap-1 text-red-600 hover:text-red-800 text-xs font-semibold ml-2"
                            title="Delete"
                          >
                            <LuTrash2 className="w-3.5 h-3.5" /> Remove
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}
        </main>
      </div>
    </div>
  );
}

