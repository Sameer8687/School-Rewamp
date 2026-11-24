import React, { useState, useEffect, useMemo } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import { fetchAadharReport, saveStudentAadhar } from '../../services/aadharService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiShield,
  FiSearch,
  FiFilter,
  FiEdit,
  FiDownload,
  FiRefreshCw,
  FiCheckCircle,
  FiAlertCircle,
  FiUser,
  FiPhone,
  FiCalendar,
  FiExternalLink,
} from 'react-icons/fi';

export default function AadharManagementPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(false);

  // Filter options
  const [classes, setClasses] = useState([]);
  const [selectedClass, setSelectedClass] = useState('');
  const [selectedDiv, setSelectedDiv] = useState('');
  const [searchQuery, setSearchQuery] = useState('');

  // Data
  const [records, setRecords] = useState([]);

  // Edit Modal
  const [editModalOpen, setEditModalOpen] = useState(false);
  const [editingStudent, setEditingStudent] = useState(null);
  const [formData, setFormData] = useState({
    student_aadhar_number: '',
    student_name: '',
    student_dob: '',
    father_aadhar_number: '',
    father_name: '',
    father_dob: '',
    mother_aadhar_number: '',
    mother_name: '',
    mother_dob: '',
    fat_mob_no: '',
  });
  const [submitting, setSubmitting] = useState(false);
  const [modalFeedback, setModalFeedback] = useState({ type: '', message: '' });

  // Initial load: options
  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        const cls = res.classes || res.data?.classes || res.data?.options?.classes || [];
        setClasses(cls);
      } catch (err) {
        console.error('Failed to load class options:', err);
      }
    }
    loadOptions();
  }, []);

  // Fetch aadhar report data
  const loadData = async () => {
    setLoading(true);
    try {
      const res = await fetchAadharReport({
        standard: selectedClass || undefined,
        division: selectedDiv || undefined,
        search: searchQuery || undefined,
      });
      if (res.success && res.data) {
        setRecords(res.data.records || []);
      }
    } catch (err) {
      console.error('Failed to load aadhar records:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, [selectedClass, selectedDiv]);

  // Handle Search submit
  const handleSearchSubmit = (e) => {
    e.preventDefault();
    loadData();
  };

  // Open Edit Modal
  const handleOpenEdit = (rec) => {
    setEditingStudent(rec);
    setFormData({
      student_aadhar_number: rec.student_aadhar_number || '',
      student_name: rec.student_name || rec.student_name_master || '',
      student_dob: rec.student_dob ? rec.student_dob.substring(0, 10) : '',
      father_aadhar_number: rec.father_aadhar_number || '',
      father_name: rec.father_name || '',
      father_dob: rec.father_dob ? rec.father_dob.substring(0, 10) : '',
      mother_aadhar_number: rec.mother_aadhar_number || '',
      mother_name: rec.mother_name || '',
      mother_dob: rec.mother_dob ? rec.mother_dob.substring(0, 10) : '',
      fat_mob_no: rec.fat_mob_no || '',
    });
    setModalFeedback({ type: '', message: '' });
    setEditModalOpen(true);
  };

  // Save Modal
  const handleSaveModal = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setModalFeedback({ type: '', message: '' });
    try {
      const res = await saveStudentAadhar({
        student_id: editingStudent.student_id || editingStudent.id,
        ...formData,
      });
      if (res.success) {
        setModalFeedback({ type: 'success', message: 'Aadhaar details saved successfully!' });
        setTimeout(() => {
          setEditModalOpen(false);
          loadData();
        }, 1000);
      } else {
        setModalFeedback({ type: 'error', message: res.message || 'Failed to save details.' });
      }
    } catch (err) {
      setModalFeedback({
        type: 'error',
        message: err.response?.data?.message || 'Error occurred while saving.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  // Export to CSV
  const exportToCSV = () => {
    if (!records || records.length === 0) return;
    const headers = [
      '#',
      'Student ID',
      'Standard',
      'Division',
      'Student Name',
      'Student Aadhaar',
      'Student DOB',
      'Father Name',
      'Father Aadhaar',
      'Father DOB',
      'Mother Name',
      'Mother Aadhaar',
      'Mother DOB',
      'Mobile Phone',
    ];

    const rows = records.map((r, idx) => [
      idx + 1,
      r.SPRIDEN_student_id || '',
      r.STVCLAS_standard || '',
      r.STVCLAS_class || '',
      `"${r.student_name || r.student_name_master || ''}"`,
      r.student_aadhar_number || '',
      r.student_dob || '',
      `"${r.father_name || ''}"`,
      r.father_aadhar_number || '',
      r.father_dob || '',
      `"${r.mother_name || ''}"`,
      r.mother_aadhar_number || '',
      r.mother_dob || '',
      r.fat_mob_no || '',
    ]);

    const csvContent =
      'data:text/csv;charset=utf-8,' +
      [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `Student_Aadhar_Report_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  // KPI Calculations
  const stats = useMemo(() => {
    const total = records.length;
    const withStudentAadhar = records.filter(
      (r) => r.student_aadhar_number && r.student_aadhar_number.trim().length >= 10
    ).length;
    const withFatherAadhar = records.filter(
      (r) => r.father_aadhar_number && r.father_aadhar_number.trim().length >= 10
    ).length;
    const withMotherAadhar = records.filter(
      (r) => r.mother_aadhar_number && r.mother_aadhar_number.trim().length >= 10
    ).length;
    const missingBoth = total - withStudentAadhar;
    return {
      total,
      withStudentAadhar,
      withFatherAadhar,
      withMotherAadhar,
      missingBoth,
      studentRate: total > 0 ? Math.round((withStudentAadhar / total) * 100) : 0,
    };
  }, [records]);

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 sm:p-6 space-y-6 max-w-7xl mx-auto w-full">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
            <div>
              <div className="flex items-center gap-2">
                <div className="p-2 rounded-xl bg-[#e3f2fd] text-[#1976d2]">
                  <FiShield size={22} />
                </div>
                <div>
                  <h1 className="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                    Student & Parent Aadhaar KYC Report
                  </h1>
                  <p className="text-xs text-slate-500 font-medium">
                    Verified UIDAI Aadhaar, DOB, and parent contact registry (CI3: Aadhar-Report / Welcome::get_aadhar_data)
                  </p>
                </div>
              </div>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={loadData}
                disabled={loading}
                className="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition shadow-sm cursor-pointer"
              >
                <FiRefreshCw size={13} className={loading ? 'animate-spin' : ''} />
                <span>Refresh</span>
              </button>

              <button
                onClick={exportToCSV}
                disabled={records.length === 0}
                className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition disabled:opacity-50 cursor-pointer"
              >
                <FiDownload size={13} />
                <span>Export CSV</span>
              </button>
            </div>
          </div>

          {/* Stats Bar */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80">
              <span className="text-xs text-slate-400 font-medium block">Total Students</span>
              <h3 className="text-xl font-bold text-slate-800 mt-1">{stats.total}</h3>
              <p className="text-[11px] text-slate-500 mt-0.5">Enrolled in selected scope</p>
            </div>

            <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80">
              <span className="text-xs text-slate-400 font-medium block">Student Aadhaar Verified</span>
              <h3 className="text-xl font-bold text-emerald-600 mt-1">
                {stats.withStudentAadhar}{' '}
                <span className="text-xs font-semibold text-emerald-700">({stats.studentRate}%)</span>
              </h3>
              <p className="text-[11px] text-slate-500 mt-0.5">Valid UIDAI linked</p>
            </div>

            <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80">
              <span className="text-xs text-slate-400 font-medium block">Father Aadhaar Linked</span>
              <h3 className="text-xl font-bold text-[#1976d2] mt-1">{stats.withFatherAadhar}</h3>
              <p className="text-[11px] text-slate-500 mt-0.5">Guardian records updated</p>
            </div>

            <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80">
              <span className="text-xs text-slate-400 font-medium block">Mother Aadhaar Linked</span>
              <h3 className="text-xl font-bold text-[#1976d2] mt-1">{stats.withMotherAadhar}</h3>
              <p className="text-[11px] text-slate-500 mt-0.5">Guardian records updated</p>
            </div>
          </div>

          {/* Filter Bar */}
          <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80">
            <form onSubmit={handleSearchSubmit} className="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
              {/* Class Dropdown */}
              <div className="sm:col-span-3">
                <label className="block text-xs font-semibold text-slate-600 mb-1">Standard / Class</label>
                <select
                  value={selectedClass}
                  onChange={(e) => setSelectedClass(e.target.value)}
                  className="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-2 focus:ring-[#1976d2]/10 outline-none bg-slate-50/50"
                >
                  <option value="">All Standards</option>
                  {classes.map((c) => (
                    <option key={c} value={c}>
                      {c}
                    </option>
                  ))}
                </select>
              </div>

              {/* Division Dropdown */}
              <div className="sm:col-span-2">
                <label className="block text-xs font-semibold text-slate-600 mb-1">Division</label>
                <select
                  value={selectedDiv}
                  onChange={(e) => setSelectedDiv(e.target.value)}
                  className="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-2 focus:ring-[#1976d2]/10 outline-none bg-slate-50/50"
                >
                  <option value="">All Divisions</option>
                  <option value="A">Division A</option>
                  <option value="B">Division B</option>
                  <option value="C">Division C</option>
                  <option value="D">Division D</option>
                </select>
              </div>

              {/* Search Box */}
              <div className="sm:col-span-5">
                <label className="block text-xs font-semibold text-slate-600 mb-1">Search Student / Aadhaar</label>
                <div className="relative">
                  <FiSearch className="absolute left-3 top-2.5 text-slate-400" size={14} />
                  <input
                    type="text"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    placeholder="Search by student name, ID, or Aadhaar #..."
                    className="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 focus:border-[#1976d2] focus:ring-2 focus:ring-[#1976d2]/10 outline-none bg-slate-50/50"
                  />
                </div>
              </div>

              {/* Submit Button */}
              <div className="sm:col-span-2">
                <button
                  type="submit"
                  className="w-full py-2 px-3 rounded-xl bg-[#1976d2] hover:bg-[#436181] text-white text-xs font-semibold transition shadow-sm cursor-pointer"
                >
                  Filter Records
                </button>
              </div>
            </form>
          </div>

          {/* Records Table */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div className="p-4 border-b border-slate-100 flex items-center justify-between">
              <h3 className="font-bold text-sm text-slate-800">
                Aadhaar Registry Table ({records.length} Students)
              </h3>
            </div>

            {loading ? (
              <div className="p-12 text-center text-slate-500">
                <div className="w-8 h-8 border-3 border-[#1976d2]/30 border-t-[#1976d2] rounded-full animate-spin mx-auto mb-2" />
                <span className="text-xs font-medium">Loading Aadhaar data...</span>
              </div>
            ) : records.length === 0 ? (
              <div className="p-12 text-center text-slate-400 text-xs">
                No students found matching current filters.
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-xs text-left">
                  <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 text-[11px] uppercase tracking-wider">
                    <tr>
                      <th className="px-3.5 py-3">#</th>
                      <th className="px-3.5 py-3">Student Details</th>
                      <th className="px-3.5 py-3">Class</th>
                      <th className="px-3.5 py-3">Student Aadhaar</th>
                      <th className="px-3.5 py-3">Father KYC</th>
                      <th className="px-3.5 py-3">Mother KYC</th>
                      <th className="px-3.5 py-3">Mobile</th>
                      <th className="px-3.5 py-3 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 text-slate-700">
                    {records.map((r, idx) => {
                      const hasStudAadhar =
                        r.student_aadhar_number && r.student_aadhar_number.trim().length >= 10;
                      return (
                        <tr key={r.id || idx} className="hover:bg-slate-50/70 transition">
                          <td className="px-3.5 py-3 font-semibold text-slate-400">{idx + 1}</td>
                          <td className="px-3.5 py-3">
                            <div className="font-bold text-slate-900">
                              {r.student_name || r.student_name_master}
                            </div>
                            <div className="flex items-center gap-1.5 text-[11px] text-slate-400 mt-0.5">
                              <span>ID: {r.SPRIDEN_student_id}</span>
                              {r.spriden_gr_number && <span>• GR: {r.spriden_gr_number}</span>}
                            </div>
                          </td>
                          <td className="px-3.5 py-3 whitespace-nowrap">
                            <span className="px-2 py-0.5 rounded-full bg-slate-100 font-semibold text-slate-800 text-[11px]">
                              {r.STVCLAS_standard || 'N/A'}{r.STVCLAS_class ? ` - ${r.STVCLAS_class}` : ''}
                            </span>
                          </td>
                          <td className="px-3.5 py-3 whitespace-nowrap">
                            {hasStudAadhar ? (
                              <div>
                                <span className="font-mono font-bold text-slate-900 tracking-wider">
                                  {r.student_aadhar_number}
                                </span>
                                {r.student_dob && (
                                  <div className="text-[10px] text-slate-400 mt-0.5">
                                    DOB: {new Date(r.student_dob).toLocaleDateString()}
                                  </div>
                                )}
                              </div>
                            ) : (
                              <span className="text-rose-500 font-semibold inline-flex items-center gap-1 text-[11px]">
                                <FiAlertCircle size={12} /> Pending
                              </span>
                            )}
                          </td>
                          <td className="px-3.5 py-3">
                            <div className="font-semibold text-slate-800 text-xs">
                              {r.father_name || '-'}
                            </div>
                            {r.father_aadhar_number && (
                              <div className="font-mono text-[11px] text-slate-500">
                                {r.father_aadhar_number}
                              </div>
                            )}
                          </td>
                          <td className="px-3.5 py-3">
                            <div className="font-semibold text-slate-800 text-xs">
                              {r.mother_name || '-'}
                            </div>
                            {r.mother_aadhar_number && (
                              <div className="font-mono text-[11px] text-slate-500">
                                {r.mother_aadhar_number}
                              </div>
                            )}
                          </td>
                          <td className="px-3.5 py-3 whitespace-nowrap">
                            {r.fat_mob_no ? (
                              <span className="font-medium text-slate-700">{r.fat_mob_no}</span>
                            ) : (
                              <span className="text-slate-400 italic">None</span>
                            )}
                          </td>
                          <td className="px-3.5 py-3 text-right whitespace-nowrap">
                            <div className="inline-flex items-center gap-1">
                              <button
                                onClick={() => handleOpenEdit(r)}
                                className="p-1.5 rounded-lg text-[#1976d2] hover:bg-sky-50 transition"
                                title="Edit Aadhaar KYC"
                              >
                                <FiEdit size={14} />
                              </button>
                              <Link
                                to={`/profile?studentId=${r.student_id || r.id}`}
                                className="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
                                title="Open Student Portal"
                              >
                                <FiExternalLink size={14} />
                              </Link>
                            </div>
                          </td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </main>
      </div>

      {/* Edit Aadhaar KYC Modal */}
      {editModalOpen && (
        <div className="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full p-6 animate-scaleIn">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
              <div className="flex items-center gap-2">
                <FiShield className="text-[#1976d2]" size={20} />
                <h3 className="font-bold text-base text-slate-800">
                  Update Aadhaar & KYC Details
                </h3>
              </div>
              <button
                onClick={() => setEditModalOpen(false)}
                className="text-slate-400 hover:text-slate-600 text-lg font-bold p-1"
              >
                &times;
              </button>
            </div>

            {modalFeedback.message && (
              <div
                className={`p-3 mb-4 rounded-xl text-xs font-semibold border ${
                  modalFeedback.type === 'success'
                    ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                    : 'bg-rose-50 border-rose-200 text-rose-800'
                }`}
              >
                {modalFeedback.message}
              </div>
            )}

            <form onSubmit={handleSaveModal} className="space-y-4 text-xs">
              {/* Student Information */}
              <div className="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2.5">
                <h4 className="font-bold text-slate-700 flex items-center gap-1.5">
                  <FiUser size={14} /> Student UIDAI Information
                </h4>
                <div>
                  <label className="block text-slate-500 font-medium mb-1">Student Aadhaar Number</label>
                  <input
                    type="text"
                    maxLength={12}
                    value={formData.student_aadhar_number}
                    onChange={(e) =>
                      setFormData({ ...formData, student_aadhar_number: e.target.value })
                    }
                    placeholder="12-digit Aadhaar Number"
                    className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white font-mono"
                  />
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Student Name</label>
                    <input
                      type="text"
                      value={formData.student_name}
                      onChange={(e) => setFormData({ ...formData, student_name: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white"
                    />
                  </div>
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Student DOB</label>
                    <input
                      type="date"
                      value={formData.student_dob}
                      onChange={(e) => setFormData({ ...formData, student_dob: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white"
                    />
                  </div>
                </div>
              </div>

              {/* Father Information */}
              <div className="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2.5">
                <h4 className="font-bold text-slate-700">Father Information</h4>
                <div>
                  <label className="block text-slate-500 font-medium mb-1">Father Aadhaar Number</label>
                  <input
                    type="text"
                    maxLength={12}
                    value={formData.father_aadhar_number}
                    onChange={(e) =>
                      setFormData({ ...formData, father_aadhar_number: e.target.value })
                    }
                    placeholder="12-digit Aadhaar Number"
                    className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white font-mono"
                  />
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Father Name</label>
                    <input
                      type="text"
                      value={formData.father_name}
                      onChange={(e) => setFormData({ ...formData, father_name: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white"
                    />
                  </div>
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Father Mobile</label>
                    <input
                      type="text"
                      maxLength={10}
                      value={formData.fat_mob_no}
                      onChange={(e) => setFormData({ ...formData, fat_mob_no: e.target.value })}
                      placeholder="10-digit Mobile"
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white"
                    />
                  </div>
                </div>
              </div>

              {/* Mother Information */}
              <div className="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2.5">
                <h4 className="font-bold text-slate-700">Mother Information</h4>
                <div>
                  <label className="block text-slate-500 font-medium mb-1">Mother Aadhaar Number</label>
                  <input
                    type="text"
                    maxLength={12}
                    value={formData.mother_aadhar_number}
                    onChange={(e) =>
                      setFormData({ ...formData, mother_aadhar_number: e.target.value })
                    }
                    placeholder="12-digit Aadhaar Number"
                    className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white font-mono"
                  />
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Mother Name</label>
                    <input
                      type="text"
                      value={formData.mother_name}
                      onChange={(e) => setFormData({ ...formData, mother_name: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white"
                    />
                  </div>
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Mother DOB</label>
                    <input
                      type="date"
                      value={formData.mother_dob}
                      onChange={(e) => setFormData({ ...formData, mother_dob: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none bg-white"
                    />
                  </div>
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setEditModalOpen(false)}
                  className="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-medium"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={submitting}
                  className="px-4 py-2 rounded-xl bg-[#1976d2] hover:bg-[#1565c0] text-white font-medium shadow-md transition disabled:opacity-60"
                >
                  {submitting ? 'Saving...' : 'Save Aadhaar Record'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
