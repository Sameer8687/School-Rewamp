import React, { useState, useEffect, useMemo } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import randomClassService from '../../services/randomClassService.js';
import {
  FiShuffle,
  FiUsers,
  FiUserCheck,
  FiSave,
  FiRefreshCw,
  FiAlertCircle,
  FiCheckCircle,
  FiFilter,
  FiX
} from 'react-icons/fi';

export default function RandomClassAllocationPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [loadingStudents, setLoadingStudents] = useState(false);

  // Dropdown options
  const [standards, setStandards] = useState([]);
  const [teachers, setTeachers] = useState([]);

  // Selected filters
  const [selectedStandard, setSelectedStandard] = useState('');
  const [bulkTeacher, setBulkTeacher] = useState('');

  // Student rows with their allocation state
  const [students, setStudents] = useState([]);
  const [submitting, setSubmitting] = useState(false);
  const [feedback, setFeedback] = useState({ type: '', message: '' });

  // Load initial options
  const loadOptions = async () => {
    setLoading(true);
    try {
      const res = await randomClassService.getOptions();
      if (res.data?.success) {
        const stds = res.data.data.standards || [];
        const tchs = res.data.data.teachers || [];
        setStandards(stds);
        setTeachers(tchs);
        if (stds.length > 0 && !selectedStandard) {
          setSelectedStandard(stds[0]);
        }
      }
    } catch (err) {
      console.error('Failed to load allocation options:', err);
      setFeedback({ type: 'error', message: 'Failed to load standards and teachers.' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadOptions();
  }, []);

  // Load students whenever selectedStandard changes
  const loadClassStudents = async (standard) => {
    if (!standard) return;
    setLoadingStudents(true);
    setFeedback({ type: '', message: '' });
    try {
      const res = await randomClassService.getStudentsByClass(standard);
      if (res.data?.success) {
        // Map students into editable state
        const mapped = (res.data.data || []).map(st => ({
          student_id: st.student_id,
          student_code: st.student_code,
          student_name: st.student_name,
          standard: st.standard,
          division: st.division,
          random_id: st.stvrndm_id || null,
          teacher_id: st.teacher_code || '',
          status: st.status === '1' || st.status === 1 || Boolean(st.stvrndm_id && st.status !== '0')
        }));
        setStudents(mapped);
      }
    } catch (err) {
      console.error('Failed to load class students:', err);
      setFeedback({ type: 'error', message: 'Failed to load students for class ' + standard });
    } finally {
      setLoadingStudents(false);
    }
  };

  useEffect(() => {
    if (selectedStandard) {
      loadClassStudents(selectedStandard);
    }
  }, [selectedStandard]);

  // Bulk assign teacher to active rows
  const handleApplyBulkTeacher = () => {
    if (!bulkTeacher) {
      setFeedback({ type: 'error', message: 'Please select a teacher to bulk assign.' });
      return;
    }
    setStudents(prev =>
      prev.map(st => ({
        ...st,
        teacher_id: bulkTeacher,
        status: true
      }))
    );
    setFeedback({ type: 'success', message: `Assigned selected teacher to all ${students.length} students.` });
  };

  // Toggle status for individual student
  const handleToggleStatus = (index) => {
    setStudents(prev => {
      const copy = [...prev];
      copy[index] = { ...copy[index], status: !copy[index].status };
      return copy;
    });
  };

  // Change teacher for individual student
  const handleTeacherChange = (index, teacherCode) => {
    setStudents(prev => {
      const copy = [...prev];
      copy[index] = {
        ...copy[index],
        teacher_id: teacherCode,
        status: teacherCode ? true : copy[index].status
      };
      return copy;
    });
  };

  // Save assignments
  const handleSave = async () => {
    if (!selectedStandard) return;
    setSubmitting(true);
    setFeedback({ type: '', message: '' });

    try {
      const payload = {
        standard: selectedStandard,
        assignments: students.map(s => ({
          student_id: s.student_id,
          random_id: s.random_id,
          teacher_id: s.teacher_id,
          status: s.status ? '1' : '0'
        }))
      };

      const res = await randomClassService.saveAssignments(payload);
      if (res.data?.success) {
        setFeedback({ type: 'success', message: 'Class allocations saved successfully.' });
        loadClassStudents(selectedStandard);
      }
    } catch (err) {
      console.error('Error saving assignments:', err);
      setFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Failed to save class allocation.'
      });
    } finally {
      setSubmitting(false);
    }
  };

  // Stats
  const totalCount = students.length;
  const activeCount = students.filter(s => s.status && s.teacher_id).length;
  const pendingCount = totalCount - activeCount;

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
                  <FiShuffle className="w-6 h-6" />
                </span>
                <h1 className="text-2xl font-bold tracking-tight text-slate-900">
                  Random Class & Student-Teacher Allocation
                </h1>
              </div>
              <p className="text-sm text-slate-500 mt-1">
                Assign class students to teachers for inspection, mentoring, and academic follow-up (stvrndm registry).
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={() => loadClassStudents(selectedStandard)}
                disabled={loadingStudents || !selectedStandard}
                className="flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 shadow-sm transition disabled:opacity-50"
              >
                <FiRefreshCw className={`w-4 h-4 ${loadingStudents ? 'animate-spin' : ''}`} />
                Reload
              </button>
              <button
                onClick={handleSave}
                disabled={submitting || loadingStudents || students.length === 0}
                className="flex items-center gap-1.5 px-4 py-2 bg-[#1976d2] text-white text-sm font-medium rounded-lg hover:bg-[#1565c0] shadow-sm transition disabled:opacity-50"
              >
                <FiSave className="w-4 h-4" />
                {submitting ? 'Saving...' : 'Save Allocations'}
              </button>
            </div>
          </div>

          {/* Feedback Banner */}
          {feedback.message && (
            <div className={`p-4 mb-6 rounded-lg flex items-center gap-3 text-sm ${
              feedback.type === 'success'
                ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                : 'bg-rose-50 text-rose-800 border border-rose-200'
            }`}>
              {feedback.type === 'success' ? <FiCheckCircle className="w-5 h-5 shrink-0" /> : <FiAlertCircle className="w-5 h-5 shrink-0" />}
              <span>{feedback.message}</span>
              <button onClick={() => setFeedback({ type: '', message: '' })} className="ml-auto text-slate-400 hover:text-slate-600">
                <FiX className="w-4 h-4" />
              </button>
            </div>
          )}

          {/* Filter & Bulk Control Card */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
            <div className="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
              {/* Select Standard */}
              <div className="md:col-span-4">
                <label className="block text-xs font-semibold text-slate-700 uppercase mb-1.5 flex items-center gap-1">
                  <FiFilter className="w-3.5 h-3.5 text-[#1976d2]" />
                  Select Class / Standard <span className="text-rose-500">*</span>
                </label>
                <select
                  value={selectedStandard}
                  onChange={(e) => setSelectedStandard(e.target.value)}
                  className="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="">-- Choose Class --</option>
                  {standards.map((std) => (
                    <option key={std} value={std}>Class {std}</option>
                  ))}
                </select>
              </div>

              {/* Bulk Teacher Selector */}
              <div className="md:col-span-5">
                <label className="block text-xs font-semibold text-slate-700 uppercase mb-1.5 flex items-center gap-1">
                  <FiUserCheck className="w-3.5 h-3.5 text-[#1976d2]" />
                  Bulk Assign Teacher (Quick Apply)
                </label>
                <select
                  value={bulkTeacher}
                  onChange={(e) => setBulkTeacher(e.target.value)}
                  className="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="">-- Select Teacher to Apply to All --</option>
                  {teachers.map((tch) => (
                    <option key={tch.id} value={tch.teacher_code}>
                      {tch.name} ({tch.teacher_code})
                    </option>
                  ))}
                </select>
              </div>

              {/* Apply Button */}
              <div className="md:col-span-3">
                <button
                  type="button"
                  onClick={handleApplyBulkTeacher}
                  disabled={!bulkTeacher || students.length === 0}
                  className="w-full py-2 px-3 bg-slate-800 text-white text-xs font-medium rounded-lg hover:bg-slate-900 transition disabled:opacity-40"
                >
                  Apply to All Rows
                </button>
              </div>
            </div>

            {/* Quick Summary Pill Row */}
            <div className="flex flex-wrap items-center gap-3 mt-4 pt-3 border-t border-slate-100 text-xs">
              <span className="text-slate-500">Summary:</span>
              <span className="px-2.5 py-1 bg-slate-100 text-slate-700 font-medium rounded-full">
                Total Students: <strong className="text-slate-900">{totalCount}</strong>
              </span>
              <span className="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-medium rounded-full">
                Assigned: <strong className="text-emerald-900">{activeCount}</strong>
              </span>
              <span className="px-2.5 py-1 bg-amber-50 text-amber-700 font-medium rounded-full">
                Unassigned: <strong className="text-amber-900">{pendingCount}</strong>
              </span>
            </div>
          </div>

          {/* Allocation Table */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                  <tr>
                    <th className="px-4 py-3 w-12 text-center">#</th>
                    <th className="px-4 py-3 w-24 text-center">Status</th>
                    <th className="px-4 py-3">Student Code</th>
                    <th className="px-4 py-3">Student Full Name</th>
                    <th className="px-4 py-3">Class / Div</th>
                    <th className="px-4 py-3">Assigned Teacher</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loadingStudents ? (
                    <tr>
                      <td colSpan="6" className="text-center py-12 text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                        Loading students for class {selectedStandard}...
                      </td>
                    </tr>
                  ) : students.length === 0 ? (
                    <tr>
                      <td colSpan="6" className="text-center py-12 text-slate-400">
                        {selectedStandard
                          ? `No active students found in Class ${selectedStandard}.`
                          : 'Please select a class standard above to view students.'}
                      </td>
                    </tr>
                  ) : (
                    students.map((st, idx) => (
                      <tr key={st.student_id} className={`hover:bg-slate-50/70 transition ${st.status ? 'bg-[#e3f2fd]/20' : ''}`}>
                        <td className="px-4 py-3 text-center text-slate-400 font-mono">
                          {idx + 1}
                        </td>
                        <td className="px-4 py-3 text-center">
                          <button
                            type="button"
                            onClick={() => handleToggleStatus(idx)}
                            className={`px-2 py-0.5 rounded text-[11px] font-semibold transition ${
                              st.status
                                ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200'
                                : 'bg-slate-100 text-slate-500 hover:bg-slate-200'
                            }`}
                          >
                            {st.status ? 'Active' : 'Inactive'}
                          </button>
                        </td>
                        <td className="px-4 py-3 font-mono font-medium text-slate-700">
                          {st.student_code}
                        </td>
                        <td className="px-4 py-3 font-semibold text-slate-900">
                          {st.student_name}
                        </td>
                        <td className="px-4 py-3 text-slate-600">
                          {st.standard} - {st.division}
                        </td>
                        <td className="px-4 py-3">
                          <select
                            value={st.teacher_id}
                            onChange={(e) => handleTeacherChange(idx, e.target.value)}
                            className="w-full max-w-xs px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                          >
                            <option value="">-- Unassigned --</option>
                            {teachers.map((tch) => (
                              <option key={tch.id} value={tch.teacher_code}>
                                {tch.name} ({tch.teacher_code})
                              </option>
                            ))}
                          </select>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>

            {/* Table Footer */}
            <div className="p-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex justify-between items-center">
              <span>Class {selectedStandard || '-'} has {students.length} students enrolled</span>
              <button
                onClick={handleSave}
                disabled={submitting || students.length === 0}
                className="px-4 py-1.5 bg-[#1976d2] text-white text-xs font-medium rounded-lg hover:bg-[#1565c0] transition disabled:opacity-50"
              >
                {submitting ? 'Saving...' : 'Save Allocations'}
              </button>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}

