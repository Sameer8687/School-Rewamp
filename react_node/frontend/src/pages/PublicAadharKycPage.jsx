import React, { useState, useEffect } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import api from '../services/api.js';
import {
  FiCheckCircle,
  FiAlertCircle,
  FiUser,
  FiCalendar,
  FiCreditCard,
  FiPhone,
  FiLock,
  FiArrowRight,
  FiLogOut,
  FiInfo,
} from 'react-icons/fi';

export default function PublicAadharKycPage() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();

  const sidParam = searchParams.get('sid') || searchParams.get('student_id') || searchParams.get('id');
  const tokenParam = searchParams.get('token') || '';

  // Auth / Login State if no sid provided
  const [studentIdInput, setStudentIdInput] = useState('');
  const [loginLoading, setLoginLoading] = useState(false);
  const [loginError, setLoginError] = useState('');

  // KYC Form State
  const [studentData, setStudentData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [feedback, setFeedback] = useState(null); // { type: 'success' | 'error', text: '' }

  const [formData, setFormData] = useState({
    stud_name: '',
    stud_dob: '',
    stud_aadahr: '',
    father_name: '',
    father_mobile: '',
    father_dob: '',
    father_aadahr: '',
    mother_name: '',
    mother_dob: '',
    mother_aadahr: '',
  });

  const loadStudentInfo = async (sid, token) => {
    if (!sid) return;
    setLoading(true);
    setFeedback(null);
    try {
      const res = await api.get('/aadhar/public/student-info', {
        params: { sid, token },
      });
      if (res.data?.success && res.data.data) {
        const d = res.data.data;
        setStudentData(d);
        setFormData({
          stud_name: d.aadhar_student_name || d.student_name || '',
          stud_dob: d.aadhar_student_dob ? String(d.aadhar_student_dob).slice(0, 10) : '',
          stud_aadahr: d.aadhar_student_number || '',
          father_name: d.aadhar_father_name || d.SPRIDEN_father_name || '',
          father_mobile: d.aadhar_father_mobile || d.stvpcnf_mobile || '',
          father_dob: d.aadhar_father_dob ? String(d.aadhar_father_dob).slice(0, 10) : '',
          father_aadahr: d.father_aadhar_number || '',
          mother_name: d.aadhar_mother_name || d.SPRIDEN_mother_name || '',
          mother_dob: d.aadhar_mother_dob ? String(d.aadhar_mother_dob).slice(0, 10) : '',
          mother_aadahr: d.mother_aadhar_number || '',
        });
      }
    } catch (err) {
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to load student details. Please verify link.',
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (sidParam) {
      loadStudentInfo(sidParam, tokenParam);
    }
  }, [sidParam, tokenParam]);

  const handleValidateStudent = async (e) => {
    e.preventDefault();
    if (!studentIdInput.trim()) {
      setLoginError('Please enter a valid Student ID.');
      return;
    }
    setLoginLoading(true);
    setLoginError('');
    try {
      const res = await api.post('/aadhar/public/validate', {
        student_id: studentIdInput.trim(),
      });
      if (res.data?.success && res.data.data) {
        const { sid, token } = res.data.data;
        navigate(`/public/student/aadhar_info?sid=${sid}&token=${token}`);
      }
    } catch (err) {
      setLoginError(err.response?.data?.message || 'Invalid Student ID. Details not found.');
    } finally {
      setLoginLoading(false);
    }
  };

  const handleInputChange = (field, val) => {
    setFormData((prev) => ({ ...prev, [field]: val }));
  };

  const validateAadhar = (val) => {
    if (!val) return false;
    return /^\d{12}$/.test(String(val).trim());
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // 12 digit Aadhaar validation
    if (formData.stud_aadahr && !validateAadhar(formData.stud_aadahr)) {
      alert('Invalid Student Aadhar number. It must be exactly 12 digits numeric.');
      return;
    }
    if (formData.father_aadahr && !validateAadhar(formData.father_aadahr)) {
      alert('Invalid Father Aadhar number. It must be exactly 12 digits numeric.');
      return;
    }
    if (formData.mother_aadahr && !validateAadhar(formData.mother_aadahr)) {
      alert('Invalid Mother Aadhar number. It must be exactly 12 digits numeric.');
      return;
    }

    setSubmitting(true);
    setFeedback(null);

    try {
      const payload = {
        student_id: studentData.student_pk || sidParam,
        student_name: formData.stud_name,
        student_dob: formData.stud_dob,
        student_aadhar_number: formData.stud_aadahr,
        father_name: formData.father_name,
        father_dob: formData.father_dob,
        father_aadhar_number: formData.father_aadahr,
        fat_mob_no: formData.father_mobile,
        mother_name: formData.mother_name,
        mother_dob: formData.mother_dob,
        mother_aadhar_number: formData.mother_aadahr,
      };

      const res = await api.post('/aadhar/public/save', payload);
      if (res.data?.success) {
        setFeedback({
          type: 'success',
          text: res.data.message || 'Student and parent Aadhaar KYC details updated successfully!',
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    } catch (err) {
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to save Aadhaar details. Please try again.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  // If no sidParam provided or student not found, render Login / Student ID entry
  if (!sidParam || (!loading && !studentData)) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-[#4a6b8f] via-[#1976d2] to-[#1e293b] flex items-center justify-center p-4 font-sans text-slate-800">
        <div className="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full border border-white/20 backdrop-blur-md">
          <div className="text-center mb-6">
            <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#e3f2fd] border-2 border-blue-100 shadow-inner mb-3">
              <span className="text-3xl font-extrabold text-[#1976d2]">OE</span>
            </div>
            <h1 className="text-xl font-bold text-slate-900 tracking-tight">
              Olive English School
            </h1>
            <p className="text-xs font-semibold text-[#1976d2] uppercase tracking-wider mt-0.5">
              Parent Aadhaar KYC Portal
            </p>
          </div>

          <form onSubmit={handleValidateStudent} className="space-y-4">
            <div>
              <label className="block text-xs font-bold text-slate-600 mb-1.5">
                Enter Student Credentials / Student ID
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <FiUser size={16} />
                </div>
                <input
                  type="text"
                  required
                  value={studentIdInput}
                  onChange={(e) => setStudentIdInput(e.target.value)}
                  placeholder="e.g. S20200525 or GR No."
                  className="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2] focus:border-transparent transition"
                />
              </div>
            </div>

            {loginError && (
              <div className="p-3 rounded-xl bg-rose-50 border border-rose-200 flex items-start gap-2.5 text-xs text-rose-700">
                <FiAlertCircle className="shrink-0 mt-0.5" size={15} />
                <span>{loginError}</span>
              </div>
            )}

            <button
              type="submit"
              disabled={loginLoading}
              className="w-full py-3 bg-[#1976d2] hover:bg-[#47678a] text-white font-bold rounded-xl text-sm transition-all shadow-md hover:shadow-lg disabled:opacity-50 flex items-center justify-center gap-2 cursor-pointer"
            >
              {loginLoading ? 'Verifying...' : 'Access KYC Form'}
              <FiArrowRight size={16} />
            </button>
          </form>

          <div className="mt-6 pt-4 border-t border-slate-100 text-center">
            <p className="text-[11px] text-slate-400">
              For official records update under UDISE+ and Aadhaar Verification Scheme.
            </p>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-[#f4f7f6] text-slate-800 font-sans pb-16">
      {/* Sticky Header */}
      <header className="sticky top-0 z-40 bg-[#1976d2] text-white shadow-md">
        <div className="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center font-black text-sm">
              OE
            </div>
            <div>
              <h1 className="font-bold text-sm tracking-wide leading-tight">
                Olive English School
              </h1>
              <p className="text-[11px] text-blue-100">Parent Aadhaar KYC Update</p>
            </div>
          </div>
          <button
            onClick={() => {
              setStudentData(null);
              navigate('/public/student/aadhar_info');
            }}
            className="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-semibold text-white transition cursor-pointer"
          >
            <FiLogOut size={13} />
            <span>Switch Student</span>
          </button>
        </div>
      </header>

      <div className="max-w-4xl mx-auto px-4 mt-6">
        {/* Student Profile Card */}
        <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
          <div className="h-28 bg-gradient-to-r from-[#1976d2] to-[#1565c0] relative">
            <div className="absolute -bottom-8 left-6">
              <div className="w-20 h-20 rounded-2xl bg-white p-1 shadow-md border border-slate-100">
                <div className="w-full h-full rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xl">
                  {studentData?.student_name ? studentData.student_name[0] : 'S'}
                </div>
              </div>
            </div>
          </div>

          <div className="pt-10 px-6 pb-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <h2 className="text-lg font-extrabold text-slate-900">
                  {studentData?.student_name}
                </h2>
                <div className="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500">
                  <span>
                    <strong>Student ID:</strong> {studentData?.SPRIDEN_student_id}
                  </span>
                  <span>•</span>
                  <span>
                    <strong>Class:</strong>{' '}
                    {studentData?.STVCLAS_standard
                      ? `${studentData.STVCLAS_standard} - ${studentData.STVCLAS_class || ''}`
                      : 'Not Assigned'}
                  </span>
                  <span>•</span>
                  <span>
                    <strong>Roll No:</strong> {studentData?.STVCLAS_rollno || '-'}
                  </span>
                  <span>•</span>
                  <span>
                    <strong>Academic Year:</strong> {studentData?.stvacdm_year || 'Current'}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Feedback Alert */}
        {feedback && (
          <div
            className={`p-4 rounded-xl mb-6 flex items-start gap-3 border ${
              feedback.type === 'success'
                ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                : 'bg-rose-50 border-rose-200 text-rose-800'
            }`}
          >
            {feedback.type === 'success' ? (
              <FiCheckCircle className="shrink-0 mt-0.5 text-emerald-600" size={18} />
            ) : (
              <FiAlertCircle className="shrink-0 mt-0.5 text-rose-600" size={18} />
            )}
            <div className="text-xs font-semibold leading-relaxed">{feedback.text}</div>
          </div>
        )}

        {/* KYC Form */}
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Section 1: Student Information */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="border-b border-slate-100 pb-3 flex items-center justify-between">
              <div>
                <h3 className="font-bold text-slate-900 text-sm">
                  1. Student Aadhaar Information
                </h3>
                <p className="text-[11px] text-[#1976d2] font-medium">
                  बच्चे का नाम और आधार विवरण
                </p>
              </div>
              <span className="text-xs px-2.5 py-1 bg-[#e3f2fd] text-[#1565c0] font-bold rounded-md">
                Student
              </span>
            </div>

            <div>
              <label className="block text-xs font-bold text-slate-700 mb-1">
                Student Name As Per Aadhar :{' '}
                <span className="text-[#1976d2] font-normal">
                  [ बच्चे का नाम आधार कार्ड पर जैसा है ]
                </span>
              </label>
              <input
                type="text"
                required
                value={formData.stud_name}
                onChange={(e) => handleInputChange('stud_name', e.target.value)}
                placeholder="Enter student name exactly as printed on Aadhar"
                className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
              />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Date of Birth :{' '}
                  <span className="text-[#1976d2] font-normal">[ जनम तारीख ]</span>
                </label>
                <input
                  type="date"
                  required
                  value={formData.stud_dob}
                  onChange={(e) => handleInputChange('stud_dob', e.target.value)}
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Aadhar Number :{' '}
                  <span className="text-[#1976d2] font-normal">[ आधार नंबर (12 Digit) ]</span>
                </label>
                <input
                  type="text"
                  maxLength={12}
                  value={formData.stud_aadahr}
                  onChange={(e) => handleInputChange('stud_aadahr', e.target.value)}
                  placeholder="12 digit Aadhaar number"
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>
            </div>
          </div>

          {/* Section 2: Father's Information */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="border-b border-slate-100 pb-3 flex items-center justify-between">
              <div>
                <h3 className="font-bold text-slate-900 text-sm">
                  2. Father's Aadhaar & Contact Details
                </h3>
                <p className="text-[11px] text-[#1976d2] font-medium">
                  बच्चे के अब्बा का नाम और आधार विवरण
                </p>
              </div>
              <span className="text-xs px-2.5 py-1 bg-amber-50 text-amber-700 font-bold rounded-md">
                Father
              </span>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div className="sm:col-span-2">
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Father Name As Per Aadhar :{' '}
                  <span className="text-[#1976d2] font-normal">
                    [ पिता का नाम आधार कार्ड अनुसार ]
                  </span>
                </label>
                <input
                  type="text"
                  required
                  value={formData.father_name}
                  onChange={(e) => handleInputChange('father_name', e.target.value)}
                  placeholder="Enter father's name"
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Mobile Number :{' '}
                  <span className="text-[#1976d2] font-normal">[ मोबाइल नंबर ]</span>
                </label>
                <input
                  type="text"
                  maxLength={10}
                  required
                  value={formData.father_mobile}
                  onChange={(e) => handleInputChange('father_mobile', e.target.value)}
                  placeholder="10 digit mobile"
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Date of Birth :{' '}
                  <span className="text-[#1976d2] font-normal">[ जनम तारीख ]</span>
                </label>
                <input
                  type="date"
                  value={formData.father_dob}
                  onChange={(e) => handleInputChange('father_dob', e.target.value)}
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Father Aadhar Number :{' '}
                  <span className="text-[#1976d2] font-normal">[ आधार नंबर (12 Digit) ]</span>
                </label>
                <input
                  type="text"
                  maxLength={12}
                  value={formData.father_aadahr}
                  onChange={(e) => handleInputChange('father_aadahr', e.target.value)}
                  placeholder="12 digit Aadhaar number"
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>
            </div>
          </div>

          {/* Section 3: Mother's Information */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="border-b border-slate-100 pb-3 flex items-center justify-between">
              <div>
                <h3 className="font-bold text-slate-900 text-sm">
                  3. Mother's Aadhaar Information
                </h3>
                <p className="text-[11px] text-[#1976d2] font-medium">
                  बच्चे की माँ का नाम और आधार विवरण
                </p>
              </div>
              <span className="text-xs px-2.5 py-1 bg-pink-50 text-pink-700 font-bold rounded-md">
                Mother
              </span>
            </div>

            <div>
              <label className="block text-xs font-bold text-slate-700 mb-1">
                Mother Name As Per Aadhar :{' '}
                <span className="text-[#1976d2] font-normal">
                  [ माता का नाम आधार कार्ड अनुसार ]
                </span>
              </label>
              <input
                type="text"
                required
                value={formData.mother_name}
                onChange={(e) => handleInputChange('mother_name', e.target.value)}
                placeholder="Enter mother's name"
                className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
              />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Date of Birth :{' '}
                  <span className="text-[#1976d2] font-normal">[ जनम तारीख ]</span>
                </label>
                <input
                  type="date"
                  value={formData.mother_dob}
                  onChange={(e) => handleInputChange('mother_dob', e.target.value)}
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">
                  Mother Aadhar Number :{' '}
                  <span className="text-[#1976d2] font-normal">[ आधार नंबर (12 Digit) ]</span>
                </label>
                <input
                  type="text"
                  maxLength={12}
                  value={formData.mother_aadahr}
                  onChange={(e) => handleInputChange('mother_aadahr', e.target.value)}
                  placeholder="12 digit Aadhaar number"
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1976d2]"
                />
              </div>
            </div>
          </div>

          {/* Submit Action */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center">
            <button
              type="submit"
              disabled={submitting}
              className="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-all shadow-md hover:shadow-lg disabled:opacity-50 flex items-center justify-center gap-2 mx-auto cursor-pointer"
            >
              {submitting ? 'Saving Details...' : 'Submit Aadhaar Details (सबमिट करें)'}
              <FiCheckCircle size={16} />
            </button>
            <p className="text-[11px] text-slate-400 mt-2">
              All submitted data will be verified against school master records.
            </p>
          </div>
        </form>
      </div>
    </div>
  );
}
