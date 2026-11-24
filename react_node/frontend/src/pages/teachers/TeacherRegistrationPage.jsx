import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import teacherService from '../../services/teacherService.js';
import {
  FiUserPlus,
  FiArrowLeft,
  FiSave,
  FiRefreshCw,
  FiCheckCircle,
  FiAlertCircle,
  FiAward,
  FiUser,
  FiMapPin
} from 'react-icons/fi';

export default function TeacherRegistrationPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const navigate = useNavigate();

  const [nextId, setNextId] = useState('Loading...');
  const [loadingId, setLoadingId] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [feedback, setFeedback] = useState(null);

  // Form fields
  const [formData, setFormData] = useState({
    surname: '',
    student_name: '',
    father_name: '',
    mother_name: '',
    gender: 'Female',
    religion: 'Islam',
    nationality: 'Indian',
    mother_tongue: 'Urdu',
    birth_place: '',
    aadhar_number: '',
    date_of_birth: '',
    main_address: '',
    alternate_address: '',
    // Qualifications
    ten_school: '',
    ten_board: 'Maharashtra State Board',
    ten_year: '',
    twe_school: '',
    twe_board: 'Maharashtra State Board',
    twe_year: '',
    ug_school: '',
    ug_board: '',
    ug_year: '',
    pg_school: '',
    pg_board: '',
    pg_year: '',
    phd_school: '',
    phd_board: '',
    phd_year: '',
    password: 'Olive@123'
  });

  useEffect(() => {
    async function loadNextId() {
      try {
        const res = await teacherService.getNextTeacherId();
        if (res.data?.success) {
          setNextId(res.data.nextId);
        }
      } catch (err) {
        console.error('Failed to get next ID:', err);
      } finally {
        setLoadingId(false);
      }
    }
    loadNextId();
  }, []);

  function handleChange(e) {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  }

  async function handleSubmit(e) {
    e.preventDefault();
    if (!formData.student_name || !formData.surname) {
      alert('Surname and Teacher Name are required');
      return;
    }

    setSubmitting(true);
    setFeedback(null);
    try {
      const res = await teacherService.createTeacher(formData);
      if (res.data?.success) {
        setFeedback({
          type: 'success',
          text: `Teacher registered successfully with ID: ${res.data.teacher_id}!`
        });
        setTimeout(() => {
          navigate('/teachers');
        }, 1500);
      }
    } catch (err) {
      console.error('Registration failed:', err);
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to register teacher account'
      });
      setSubmitting(false);
    }
  }

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex items-center justify-between pb-4 border-b border-slate-200">
            <div>
              <Link
                to="/teachers"
                className="text-xs font-semibold text-[#1976d2] hover:text-[#1565c0] flex items-center gap-1 mb-1 transition"
              >
                <FiArrowLeft /> Back to Faculty Directory
              </Link>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiUserPlus className="text-[#1976d2]" /> Register Teacher Account
              </h1>
            </div>
            <div className="px-3 py-1.5 bg-[#e3f2fd] border border-[#bbdefb] rounded-lg text-xs font-semibold text-[#1976d2]">
              Assigned ID: <strong className="font-mono text-[#0d47a1]">{nextId}</strong>
            </div>
          </div>

          {/* Feedback */}
          {feedback && (
            <div
              className={`p-4 rounded-xl text-sm flex items-center gap-2.5 ${
                feedback.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border border-rose-200'
              }`}
            >
              {feedback.type === 'success' ? <FiCheckCircle /> : <FiAlertCircle />}
              {feedback.text}
            </div>
          )}

          {/* Form */}
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Section 1: Personal Details */}
            <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-200 space-y-4">
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2 pb-2 border-b border-slate-100">
                <FiUser className="text-[#1976d2]" /> Personal & Identification Details
              </h3>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Surname <span className="text-rose-500">*</span>
                  </label>
                  <input
                    type="text"
                    required
                    name="surname"
                    value={formData.surname}
                    onChange={handleChange}
                    placeholder="e.g. Khan"
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Teacher First Name <span className="text-rose-500">*</span>
                  </label>
                  <input
                    type="text"
                    required
                    name="student_name"
                    value={formData.student_name}
                    onChange={handleChange}
                    placeholder="e.g. Sabiha"
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Father's / Husband's Name
                  </label>
                  <input
                    type="text"
                    name="father_name"
                    value={formData.father_name}
                    onChange={handleChange}
                    placeholder="e.g. Aijaz"
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Mother's Name
                  </label>
                  <input
                    type="text"
                    name="mother_name"
                    value={formData.mother_name}
                    onChange={handleChange}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">Gender</label>
                  <select
                    name="gender"
                    value={formData.gender}
                    onChange={handleChange}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  >
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Date of Birth
                  </label>
                  <input
                    type="date"
                    name="date_of_birth"
                    value={formData.date_of_birth}
                    onChange={handleChange}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">Religion</label>
                  <input
                    type="text"
                    name="religion"
                    value={formData.religion}
                    onChange={handleChange}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Aadhar Number
                  </label>
                  <input
                    type="text"
                    name="aadhar_number"
                    value={formData.aadhar_number}
                    onChange={handleChange}
                    placeholder="12 digit Aadhar"
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Portal Login Password
                  </label>
                  <input
                    type="text"
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>
              </div>
            </div>

            {/* Section 2: Address */}
            <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-200 space-y-4">
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2 pb-2 border-b border-slate-100">
                <FiMapPin className="text-[#1976d2]" /> Residential Address
              </h3>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Permanent / Main Address
                  </label>
                  <textarea
                    rows="3"
                    name="main_address"
                    value={formData.main_address}
                    onChange={handleChange}
                    placeholder="House, Street, Area, City..."
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Alternate Address / Phone
                  </label>
                  <textarea
                    rows="3"
                    name="alternate_address"
                    value={formData.alternate_address}
                    onChange={handleChange}
                    placeholder="Alternate residence or emergency contact..."
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20"
                  />
                </div>
              </div>
            </div>

            {/* Section 3: Academic Qualifications */}
            <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-200 space-y-4">
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2 pb-2 border-b border-slate-100">
                <FiAward className="text-[#1976d2]" /> Academic & Professional Qualifications
              </h3>

              <div className="space-y-4">
                {/* 10th */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-slate-50/60 rounded-lg border border-slate-200">
                  <div className="sm:col-span-3 text-xs font-bold text-slate-700">1. Secondary (10th SSC)</div>
                  <input
                    type="text"
                    name="ten_school"
                    placeholder="School Name"
                    value={formData.ten_school}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="ten_board"
                    placeholder="Board (e.g. State Board)"
                    value={formData.ten_board}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="ten_year"
                    placeholder="Passing Year"
                    value={formData.ten_year}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                </div>

                {/* 12th */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-slate-50/60 rounded-lg border border-slate-200">
                  <div className="sm:col-span-3 text-xs font-bold text-slate-700">2. Higher Secondary (12th HSC)</div>
                  <input
                    type="text"
                    name="twe_school"
                    placeholder="Junior College Name"
                    value={formData.twe_school}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="twe_board"
                    placeholder="Board"
                    value={formData.twe_board}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="twe_year"
                    placeholder="Passing Year"
                    value={formData.twe_year}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                </div>

                {/* UG */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-slate-50/60 rounded-lg border border-slate-200">
                  <div className="sm:col-span-3 text-xs font-bold text-slate-700">3. Graduation (B.A. / B.Sc. / B.Ed.)</div>
                  <input
                    type="text"
                    name="ug_school"
                    placeholder="College / Degree Name"
                    value={formData.ug_school}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="ug_board"
                    placeholder="University"
                    value={formData.ug_board}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="ug_year"
                    placeholder="Passing Year"
                    value={formData.ug_year}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                </div>

                {/* PG */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-slate-50/60 rounded-lg border border-slate-200">
                  <div className="sm:col-span-3 text-xs font-bold text-slate-700">4. Post Graduation (M.A. / M.Sc. / M.Ed.)</div>
                  <input
                    type="text"
                    name="pg_school"
                    placeholder="College / Master's Degree"
                    value={formData.pg_school}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="pg_board"
                    placeholder="University"
                    value={formData.pg_board}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                  <input
                    type="text"
                    name="pg_year"
                    placeholder="Passing Year"
                    value={formData.pg_year}
                    onChange={handleChange}
                    className="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs"
                  />
                </div>
              </div>
            </div>

            {/* Submit Bar */}
            <div className="flex justify-end gap-3 pt-2">
              <Link
                to="/teachers"
                className="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold rounded-lg text-xs transition"
              >
                Cancel
              </Link>
              <button
                type="submit"
                disabled={submitting}
                className="px-6 py-2.5 bg-[#1976d2] hover:bg-[#1565c0] disabled:bg-slate-300 text-white font-semibold rounded-lg text-xs shadow-sm transition flex items-center gap-2"
              >
                {submitting ? (
                  <>
                    <FiRefreshCw className="animate-spin" /> Saving...
                  </>
                ) : (
                  <>
                    <FiSave /> Register & Create Account
                  </>
                )}
              </button>
            </div>
          </form>
        </main>
      </div>
    </div>
  );
}
