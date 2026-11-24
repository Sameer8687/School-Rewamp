/**
 * Student Detail & Edit Profile Page — 100% Parity with CI3 `Student/update-student.php`.
 */
import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  fetchStudentById,
  updateStudent,
  updateStudentStatus,
  fetchStudentOptions,
} from '../../services/studentService.js';
import {
  FiArrowLeft,
  FiEdit,
  FiSave,
  FiPrinter,
  FiCheckCircle,
  FiAlertCircle,
  FiClock,
  FiUser,
  FiPhone,
  FiMapPin,
  FiBook,
  FiDollarSign,
  FiFileText,
  FiX,
  FiUploadCloud,
} from 'react-icons/fi';

export default function StudentDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [student, setStudent] = useState(null);
  const [options, setOptions] = useState({ academicYears: [], classes: [], divisions: [] });

  // Active Tab
  const [activeTab, setActiveTab] = useState('personal'); // 'personal', 'academic', 'fees', 'history'

  // Edit form state
  const [form, setForm] = useState({});
  const [photoFile, setPhotoFile] = useState(null);
  const [photoPreview, setPhotoPreview] = useState(null);

  // Status Modal State
  const [showStatusModal, setShowStatusModal] = useState(false);
  const [newStatus, setNewStatus] = useState('A');
  const [statusReason, setStatusReason] = useState('');
  const [savingStatus, setSavingStatus] = useState(false);

  // Load student & options
  const loadData = async () => {
    try {
      setLoading(true);
      const [stRes, optRes] = await Promise.all([
        fetchStudentById(id),
        fetchStudentOptions(),
      ]);

      if (stRes.success && stRes.data) {
        const s = stRes.data;
        setStudent(s);
        setNewStatus(s.spriden_student_status || 'A');

        // Populate edit form
        setForm({
          gr_number: s.spriden_gr_number || '',
          reg_no: s.SPRIDEN_reg_number || '',
          pan_no: s.SPRIDEN_pan_no || '',
          apaar_no: s.SPRIDEN_apaar_no || '',
          college_gr: s.spriden_college_gr || '',
          surname: s.SPRIDEN_surname || '',
          student_name: s.SPRIDEN_student_name || '',
          father_name: s.SPRIDEN_father_name || '',
          mother_name: s.SPRIDEN_mother_name || '',
          religion: s.SPRIDEN_religion || '',
          gender: s.SPRIDEN_gender || '',
          nationality: s.SPRIDEN_nationality || '',
          mother_tongue: s.SPRIDEN_mother_toungue || '',
          birth_place: s.SPRIDEN_birth_place || '',
          aadhar_number: s.SPRIDEN_aadhar_no || '',
          birthdate: s.SPRIDEN_birthdate ? s.SPRIDEN_birthdate.slice(0, 10) : '',
          birthdate_words: s.birthdate_words || '',
          admission_class: s.SPRIDEN_admission_class || '',
          current_class: s.classes?.[0]?.STVCLAS_standard || '',
          acdm_year: s.classes?.[0]?.STVCLAS_acdm || '',
          last_school: s.SPRIDEN_last_school || '',
          main_address: s.SPRIDEN_main_address || '',
          alt_address: s.SPRIDEN_alt_address || '',
          father_qualification: s.SPRIDEN_father_quali || '',
          mother_qualification: s.SPRIDEN_mother_quali || '',
          rte_status: s.rte_status || 'No',
          caste: s.cast || '',
          sub_caste: s.sub_cast || '',
          taluka: s.taluka || '',
          district: s.district || '',
          state: s.state || 'Maharashtra',
          height: s.height || '',
          weight: s.weight || '',
          blood_group: s.SPRIDEN_BGRP || '',
          student_name_aadhar: s.student_name_aadhar || '',
          father_aadhar_no: s.father_aadhar_no || '',
          father_name_aadhar: s.father_name_aadhar || '',
          mother_name_aadhar: s.mother_name_aadhar || '',
          mother_aadhar_no: s.mother_aadhar_no || '',
          mobile_number1: s.contact?.stvpcnf_mobile || '',
          mobile_number2: s.contact?.stvpcnf_mobile2 || '',
          whatsapp_number: s.contact?.stvpcnf_whatsapp || '',
          sms_number: s.contact?.stvpncf_sms_no || '',
          leaving_date: s.leaving_date ? s.leaving_date.slice(0, 10) : '',
          leaving_reason: s.leaving_reason || '',
          conduct: s.conduct || '',
          progress: s.progress || '',
          remark: s.remark || '',
          since_when: s.since_when || '',
          lc_date: s.lc_date ? s.lc_date.slice(0, 10) : '',
        });

        if (s.student_profile_photo) {
          setPhotoPreview(`/uploads/students/${s.student_profile_photo}`);
        }
      }

      if (optRes.success && optRes.data) {
        setOptions(optRes.data);
      }
    } catch (err) {
      console.error('Failed to load student details:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, [id]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setPhotoFile(file);
      setPhotoPreview(URL.createObjectURL(file));
    }
  };

  // Save changes
  const handleSave = async (e) => {
    e.preventDefault();
    try {
      setSaving(true);
      const data = new FormData();
      Object.keys(form).forEach((k) => data.append(k, form[k] || ''));
      if (photoFile) data.append('photo', photoFile);

      const res = await updateStudent(id, data);
      if (res.success) {
        alert('Student profile updated successfully!');
        loadData();
      }
    } catch (err) {
      alert('Failed to update student: ' + (err.response?.data?.message || err.message));
    } finally {
      setSaving(false);
    }
  };

  // Change Status Handler
  const handleSaveStatus = async (e) => {
    e.preventDefault();
    try {
      setSavingStatus(true);
      const res = await updateStudentStatus(id, {
        status: newStatus,
        reason: statusReason,
      });
      if (res.success) {
        setShowStatusModal(false);
        loadData();
      }
    } catch (err) {
      alert('Failed to change status: ' + (err.response?.data?.message || err.message));
    } finally {
      setSavingStatus(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-[#f4f7fa] flex flex-col font-sans">
        <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} isSidebarOpen={sidebarOpen} />
        <div className="flex-1 flex items-center justify-center p-8 text-slate-500 text-xs">
          <div className="inline-block animate-spin w-6 h-6 border-2 border-slate-300 border-t-[#1976d2] rounded-full mr-3"></div>
          Loading student profile...
        </div>
      </div>
    );
  }

  if (!student) {
    return (
      <div className="min-h-screen bg-[#f4f7fa] flex flex-col font-sans">
        <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} isSidebarOpen={sidebarOpen} />
        <div className="flex-1 p-8 text-center text-xs text-slate-600">
          <p className="text-base font-bold text-slate-800">Student not found</p>
          <Link to="/students" className="mt-3 inline-block text-[#1976d2] hover:underline">
            &larr; Back to Students Directory
          </Link>
        </div>
      </div>
    );
  }

  const currentClassObj = student.classes?.[0] || {};

  return (
    <div className="min-h-screen bg-[#f4f7fa] flex flex-col font-sans">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} isSidebarOpen={sidebarOpen} />

      <main className="flex-1 p-4 md:p-6 transition-all duration-300">
        <div className="max-w-6xl mx-auto space-y-4">

          {/* Action Header */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 no-print">
            <div className="flex items-center gap-3">
              <Link
                to="/students"
                className="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors"
                title="Back to Students Directory"
              >
                <FiArrowLeft size={18} />
              </Link>
              <div>
                <h1 className="text-xl font-bold text-slate-900 flex items-center gap-2">
                  <FiUser className="text-[#1976d2]" />
                  {student.fullName || 'Student Profile'}
                </h1>
                <p className="text-xs text-slate-500">
                  ID: <span className="font-mono font-bold text-slate-700">{student.SPRIDEN_student_id}</span> | GR: <span className="font-bold text-slate-700">{student.spriden_gr_number || 'N/A'}</span>
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2">
              <button
                type="button"
                onClick={() => setShowStatusModal(true)}
                className="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-semibold rounded-lg hover:bg-amber-100 transition-colors"
              >
                <FiAlertCircle size={14} />
                <span>Status: {student.spriden_student_status === 'A' ? 'Active' : student.spriden_student_status === 'I' ? 'Inactive' : student.spriden_student_status}</span>
              </button>
              <button
                type="button"
                onClick={() => window.print()}
                className="inline-flex items-center gap-1.5 px-3 py-2 bg-white text-slate-700 border border-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors"
              >
                <FiPrinter size={14} />
                <span>Print</span>
              </button>
              <button
                type="button"
                onClick={handleSave}
                disabled={saving}
                className="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1976d2] text-white text-xs font-semibold rounded-lg hover:bg-[#436284] shadow-sm transition-all"
              >
                <FiSave size={14} />
                <span>{saving ? 'Saving...' : 'Save Changes'}</span>
              </button>
            </div>
          </div>

          {/* Top Profile Summary Card */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex flex-col md:flex-row items-center gap-6">
            <div className="relative group w-24 h-32 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
              {photoPreview ? (
                <img src={photoPreview} alt="Student" className="w-full h-full object-cover" />
              ) : (
                <FiUser size={36} className="text-slate-300" />
              )}
              <label className="absolute inset-0 bg-black/40 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity text-[10px] font-semibold">
                <FiUploadCloud size={16} />
                <span>Change</span>
                <input type="file" accept="image/*" onChange={handlePhotoChange} className="hidden" />
              </label>
            </div>

            <div className="flex-1 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
              <div>
                <span className="text-slate-400 block text-[11px]">Class & Division</span>
                <span className="font-bold text-slate-800 text-sm">
                  {currentClassObj.STVCLAS_standard || '—'} {currentClassObj.STVCLAS_class ? `- ${currentClassObj.STVCLAS_class}` : ''}
                </span>
                <span className="text-slate-500 block text-[10px]">Roll No: {currentClassObj.STVCLAS_rollno || 0}</span>
              </div>

              <div>
                <span className="text-slate-400 block text-[11px]">Academic Year</span>
                <span className="font-bold text-slate-800 text-sm">
                  {currentClassObj.stvacdm_year || 'Current'}
                </span>
              </div>

              <div>
                <span className="text-slate-400 block text-[11px]">Contact Mobile</span>
                <span className="font-bold text-slate-800 text-sm">
                  {student.contact?.stvpcnf_mobile || '—'}
                </span>
                <span className="text-slate-500 block text-[10px]">WA: {student.contact?.stvpcnf_whatsapp || '—'}</span>
              </div>

              <div>
                <span className="text-slate-400 block text-[11px]">RTE / LC Status</span>
                <div className="flex items-center gap-1.5 mt-0.5">
                  <span className={`px-2 py-0.5 rounded text-[10px] font-bold ${
                    student.rte_status === 'Yes' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-600'
                  }`}>
                    RTE: {student.rte_status || 'No'}
                  </span>
                  {student.lc?.lc_status && (
                    <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                      LC: {student.lc.lc_status}
                    </span>
                  )}
                </div>
              </div>
            </div>
          </div>

          {/* Navigation Tabs */}
          <div className="flex border-b border-slate-200 bg-white rounded-t-xl px-4 gap-4 no-print text-xs font-semibold">
            <button
              onClick={() => setActiveTab('personal')}
              className={`py-3 px-2 border-b-2 transition-all flex items-center gap-1.5 ${
                activeTab === 'personal'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-800'
              }`}
            >
              <FiUser size={14} />
              <span>Personal & Contact Info</span>
            </button>
            <button
              onClick={() => setActiveTab('academic')}
              className={`py-3 px-2 border-b-2 transition-all flex items-center gap-1.5 ${
                activeTab === 'academic'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-800'
              }`}
            >
              <FiBook size={14} />
              <span>Academic History ({student.classes?.length || 0})</span>
            </button>
            <button
              onClick={() => setActiveTab('fees')}
              className={`py-3 px-2 border-b-2 transition-all flex items-center gap-1.5 ${
                activeTab === 'fees'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-800'
              }`}
            >
              <FiDollarSign size={14} />
              <span>Fees Records ({student.fees?.length || 0})</span>
            </button>
            <button
              onClick={() => setActiveTab('history')}
              className={`py-3 px-2 border-b-2 transition-all flex items-center gap-1.5 ${
                activeTab === 'history'
                  ? 'border-[#1976d2] text-[#1976d2]'
                  : 'border-transparent text-slate-500 hover:text-slate-800'
              }`}
            >
              <FiClock size={14} />
              <span>Status Audit Log ({student.activities?.length || 0})</span>
            </button>
          </div>

          {/* ─── TAB 1: Personal & Contact Editor ─── */}
          {activeTab === 'personal' && (
            <form onSubmit={handleSave} className="bg-white rounded-b-xl shadow-sm border border-slate-200 p-6 space-y-6">
              {/* Identification */}
              <div>
                <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3">
                  Identification & Registration
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">GR Number</label>
                    <input
                      type="text"
                      name="gr_number"
                      value={form.gr_number}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 font-bold"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Registration No</label>
                    <input
                      type="text"
                      name="reg_no"
                      value={form.reg_no}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">APAAR Number</label>
                    <input
                      type="text"
                      name="apaar_no"
                      value={form.apaar_no}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 font-mono"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">PAN Number</label>
                    <input
                      type="text"
                      name="pan_no"
                      value={form.pan_no}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 font-mono"
                    />
                  </div>
                </div>
              </div>

              {/* Names */}
              <div>
                <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3">
                  Student & Parent Names
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Surname</label>
                    <input
                      type="text"
                      name="surname"
                      value={form.surname}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 uppercase"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Student Name</label>
                    <input
                      type="text"
                      name="student_name"
                      value={form.student_name}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 font-bold uppercase"
                      required
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Father's Name</label>
                    <input
                      type="text"
                      name="father_name"
                      value={form.father_name}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 uppercase"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Mother's Name</label>
                    <input
                      type="text"
                      name="mother_name"
                      value={form.mother_name}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 uppercase"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Date of Birth</label>
                    <input
                      type="date"
                      name="birthdate"
                      value={form.birthdate}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div className="sm:col-span-2">
                    <label className="block font-semibold text-slate-700 mb-1">DOB in Words</label>
                    <input
                      type="text"
                      name="birthdate_words"
                      value={form.birthdate_words}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Place of Birth</label>
                    <input
                      type="text"
                      name="birth_place"
                      value={form.birth_place}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                </div>
              </div>

              {/* Physical & Aadhar */}
              <div>
                <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3">
                  Physical & Aadhar Details
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-4 text-xs">
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Height (ft.in)</label>
                    <input
                      type="text"
                      name="height"
                      value={form.height}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Weight (kg)</label>
                    <input
                      type="text"
                      name="weight"
                      value={form.weight}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Blood Group</label>
                    <input
                      type="text"
                      name="blood_group"
                      value={form.blood_group}
                      onChange={handleChange}
                      placeholder="e.g. O+ / B+"
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div className="sm:col-span-3">
                    <label className="block font-semibold text-slate-700 mb-1">Student Aadhar & Name</label>
                    <div className="flex gap-2">
                      <input
                        type="text"
                        name="aadhar_number"
                        value={form.aadhar_number}
                        onChange={handleChange}
                        placeholder="Aadhar Number"
                        className="w-1/2 border border-slate-300 rounded-lg p-2 font-mono"
                      />
                      <input
                        type="text"
                        name="student_name_aadhar"
                        value={form.student_name_aadhar}
                        onChange={handleChange}
                        placeholder="Name on Aadhar"
                        className="w-1/2 border border-slate-300 rounded-lg p-2"
                      />
                    </div>
                  </div>
                </div>
              </div>

              {/* Contact & Address */}
              <div>
                <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3">
                  Contact & Residential Address
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Primary Mobile</label>
                    <input
                      type="text"
                      name="mobile_number1"
                      value={form.mobile_number1}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 font-semibold"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">Mobile 2</label>
                    <input
                      type="text"
                      name="mobile_number2"
                      value={form.mobile_number2}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">WhatsApp</label>
                    <input
                      type="text"
                      name="whatsapp_number"
                      value={form.whatsapp_number}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">SMS Alert No</label>
                    <input
                      type="text"
                      name="sms_number"
                      value={form.sms_number}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div className="sm:col-span-2">
                    <label className="block font-semibold text-slate-700 mb-1">Main Address</label>
                    <textarea
                      rows="2"
                      name="main_address"
                      value={form.main_address}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                  <div className="sm:col-span-2">
                    <label className="block font-semibold text-slate-700 mb-1">Alt Address</label>
                    <textarea
                      rows="2"
                      name="alt_address"
                      value={form.alt_address}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2"
                    />
                  </div>
                </div>
              </div>

              {/* Save Bar */}
              <div className="pt-4 border-t border-slate-200 flex justify-end">
                <button
                  type="submit"
                  disabled={saving}
                  className="px-6 py-2.5 bg-[#1976d2] hover:bg-[#436284] text-white rounded-lg text-xs font-bold shadow flex items-center gap-2"
                >
                  <FiSave size={15} />
                  <span>{saving ? 'Saving...' : 'Save Profile Changes'}</span>
                </button>
              </div>
            </form>
          )}

          {/* ─── TAB 2: Academic History ─── */}
          {activeTab === 'academic' && (
            <div className="bg-white rounded-b-xl shadow-sm border border-slate-200 p-6">
              <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-4">
                Academic Year Class Allocations
              </h3>
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3">Academic Year</th>
                      <th className="py-2.5 px-3">Standard</th>
                      <th className="py-2.5 px-3">Division</th>
                      <th className="py-2.5 px-3">Roll No</th>
                      <th className="py-2.5 px-3">Year Period</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {student.classes?.map((c, i) => (
                      <tr key={c.id || i}>
                        <td className="py-2.5 px-3 font-semibold text-slate-800">
                          {c.stvacdm_year || `Year #${c.STVCLAS_acdm}`}
                        </td>
                        <td className="py-2.5 px-3">{c.STVCLAS_standard}</td>
                        <td className="py-2.5 px-3">{c.STVCLAS_class || '—'}</td>
                        <td className="py-2.5 px-3">{c.STVCLAS_rollno || 0}</td>
                        <td className="py-2.5 px-3 text-slate-500">
                          {c.stvacdm_start ? `${c.stvacdm_start} to ${c.stvacdm_end}` : '—'}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* ─── TAB 3: Fees Records ─── */}
          {activeTab === 'fees' && (
            <div className="bg-white rounded-b-xl shadow-sm border border-slate-200 p-6">
              <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-4">
                Past Fees Payment Transactions
              </h3>
              {student.fees?.length === 0 ? (
                <p className="text-slate-500 text-xs text-center py-8">No fee records found for this student.</p>
              ) : (
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase text-[11px]">
                        <th className="py-2.5 px-3">Receipt / Fee Date</th>
                        <th className="py-2.5 px-3">Academic Year</th>
                        <th className="py-2.5 px-3">Amount Paid</th>
                        <th className="py-2.5 px-3">Exemption</th>
                        <th className="py-2.5 px-3">Payment Mode</th>
                        <th className="py-2.5 px-3">Received By</th>
                        <th className="py-2.5 px-3">Notes</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {student.fees?.map((f) => (
                        <tr key={f.sprfees_pk || f.sprfees_id}>
                          <td className="py-2.5 px-3 font-medium text-slate-800">{f.sprfees_feesdate || '—'}</td>
                          <td className="py-2.5 px-3 font-semibold">{f.stvacdm_year || f.academic_year}</td>
                          <td className="py-2.5 px-3 font-bold text-emerald-700">₹{f.sprfees_amount}</td>
                          <td className="py-2.5 px-3 text-amber-700">₹{f.sprfees_exumption || 0}</td>
                          <td className="py-2.5 px-3 uppercase text-[10px]">{f.payment_mode || 'Cash'}</td>
                          <td className="py-2.5 px-3">{f.received_by_name || 'Admin'}</td>
                          <td className="py-2.5 px-3 text-slate-500">{f.sprfees_notes || '—'}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          )}

          {/* ─── TAB 4: Status History & Audit Log ─── */}
          {activeTab === 'history' && (
            <div className="bg-white rounded-b-xl shadow-sm border border-slate-200 p-6 space-y-4">
              <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-4">
                Student Status & Activity Timeline
              </h3>
              {student.activities?.length === 0 ? (
                <p className="text-slate-500 text-xs text-center py-8">No status changes logged yet.</p>
              ) : (
                <div className="space-y-3">
                  {student.activities?.map((act, i) => (
                    <div key={i} className="flex items-start gap-3 p-3 bg-slate-50 border border-slate-100 rounded-lg text-xs">
                      <FiClock className="text-[#1976d2] mt-0.5 shrink-0" size={15} />
                      <div className="text-slate-700 leading-relaxed">{act}</div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

        </div>
      </main>

      {/* ─── Status Change Modal ─── */}
      {showStatusModal && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-xl shadow-xl max-w-md w-full p-6 animate-scale-in">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                <FiAlertCircle className="text-amber-500" />
                Change Student Status
              </h3>
              <button onClick={() => setShowStatusModal(false)} className="text-slate-400 hover:text-slate-600">
                <FiX size={16} />
              </button>
            </div>

            <form onSubmit={handleSaveStatus} className="mt-4 space-y-4 text-xs">
              <div>
                <label className="block font-semibold text-slate-700 mb-1">Select Status</label>
                <select
                  value={newStatus}
                  onChange={(e) => setNewStatus(e.target.value)}
                  className="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                >
                  <option value="A">Active (A)</option>
                  <option value="I">Inactive (I)</option>
                  <option value="N">No Show (N)</option>
                </select>
              </div>

              <div>
                <label className="block font-semibold text-slate-700 mb-1">
                  Reason for Status Change (Recorded in audit history) *
                </label>
                <textarea
                  rows="3"
                  value={statusReason}
                  onChange={(e) => setStatusReason(e.target.value)}
                  placeholder="Reason..."
                  className="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  required
                />
              </div>

              <div className="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setShowStatusModal(false)}
                  className="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={savingStatus}
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#436284] text-white rounded-lg font-semibold shadow"
                >
                  {savingStatus ? 'Saving...' : 'Update Status'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
