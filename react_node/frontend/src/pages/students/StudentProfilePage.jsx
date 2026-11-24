import React, { useState, useEffect } from 'react';
import { useParams, useSearchParams, useNavigate } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import useAuthStore from '../../store/useAuthStore.js';
import { fetchStudentPortalProfile } from '../../services/studentProfileService.js';
import { saveStudentAadhar } from '../../services/aadharService.js';
import {
  FiUser,
  FiCalendar,
  FiBookOpen,
  FiCheckCircle,
  FiXCircle,
  FiDollarSign,
  FiPhone,
  FiMessageSquare,
  FiAward,
  FiAlertTriangle,
  FiClock,
  FiEdit3,
  FiLogOut,
  FiExternalLink,
  FiInfo,
  FiFileText,
  FiShield,
  FiLayers,
} from 'react-icons/fi';

export default function StudentProfilePage() {
  const { studentId: paramId } = useParams();
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const { user, logout } = useAuthStore();

  // If URL has ?studentId=... or param :studentId, or defaults to logged-in user's id
  const targetStudentId =
    paramId ||
    searchParams.get('studentId') ||
    searchParams.get('student-id') ||
    (user?.user_type === 'st' ? user.student_id || user.id : null);

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [profileData, setProfileData] = useState(null);
  const [activeTab, setActiveTab] = useState('overview'); // overview, fees, attendance, exams, lessons, remarks, events

  // Aadhar Edit Modal state
  const [showAadharModal, setShowAadharModal] = useState(false);
  const [aadharForm, setAadharForm] = useState({
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
  const [savingAadhar, setSavingAadhar] = useState(false);
  const [aadharSuccess, setAadharSuccess] = useState('');
  const [aadharError, setAadharError] = useState('');

  const loadProfile = async () => {
    setLoading(true);
    setError(null);
    try {
      const res = await fetchStudentPortalProfile(targetStudentId);
      if (res.success && res.data) {
        setProfileData(res.data);
        const s = res.data.student;
        setAadharForm({
          student_aadhar_number: s.SPRIDEN_aadhar_no || '',
          student_name: s.full_name || '',
          student_dob: s.SPRIDEN_birthdate ? s.SPRIDEN_birthdate.substring(0, 10) : '',
          father_aadhar_number: '',
          father_name: s.SPRIDEN_father_name || '',
          father_dob: '',
          mother_aadhar_number: '',
          mother_name: s.SPRIDEN_mother_name || '',
          mother_dob: '',
          fat_mob_no: s.stvpcnf_mobile || '',
        });
      } else {
        setError(res.message || 'Unable to load student profile.');
      }
    } catch (err) {
      console.error('Error fetching student profile:', err);
      setError(err.response?.data?.message || 'Failed to load profile. Please verify login.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProfile();
  }, [targetStudentId]);

  const handleAadharSave = async (e) => {
    e.preventDefault();
    setSavingAadhar(true);
    setAadharSuccess('');
    setAadharError('');
    try {
      const internalId = profileData?.student?.id;
      const res = await saveStudentAadhar({
        student_id: internalId,
        ...aadharForm,
      });
      if (res.success) {
        setAadharSuccess('Aadhaar and KYC details saved successfully!');
        setTimeout(() => {
          setShowAadharModal(false);
          loadProfile();
        }, 1200);
      } else {
        setAadharError(res.message || 'Failed to update Aadhaar details.');
      }
    } catch (err) {
      setAadharError(err.response?.data?.message || 'Error updating Aadhaar record.');
    } finally {
      setSavingAadhar(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-6">
        <div className="w-12 h-12 border-4 border-[#1976d2]/30 border-t-[#1976d2] rounded-full animate-spin mb-4" />
        <p className="text-slate-600 font-medium">Loading Student Profile...</p>
      </div>
    );
  }

  if (error || !profileData) {
    return (
      <div className="min-h-screen bg-slate-50 flex items-center justify-center p-6">
        <div className="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-md w-full p-8 text-center">
          <div className="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <FiAlertTriangle size={32} />
          </div>
          <h2 className="text-xl font-bold text-slate-800 mb-2">Profile Access Error</h2>
          <p className="text-slate-600 text-sm mb-6">{error || 'No student record found.'}</p>
          <div className="flex gap-3 justify-center">
            {user?.user_type === 'st' ? (
              <button
                onClick={() => {
                  logout();
                  navigate('/student_login');
                }}
                className="px-4 py-2 bg-[#1976d2] text-white rounded-xl text-sm font-medium hover:bg-[#436181] transition"
              >
                Back to Student Login
              </button>
            ) : (
              <button
                onClick={() => navigate('/students')}
                className="px-4 py-2 bg-[#1976d2] text-white rounded-xl text-sm font-medium hover:bg-[#436181] transition"
              >
                Go to Students Directory
              </button>
            )}
          </div>
        </div>
      </div>
    );
  }

  const { student, teacher, hold, attendance, fees, exams, subjects, remarks, events, visibility } =
    profileData;

  const isHold = hold?.is_hold;
  const showAttendance = visibility?.attendance_visibility !== 'no';
  const showMarks = visibility?.marks_visibility !== 'no';
  const isStudentRole = user?.user_type === 'st' || user?.role === 'student';

  return (
    <div className="min-h-screen bg-[#f7f7f5] flex flex-col font-sans text-[#202020]">
      {!isStudentRole ? (
        <>
          <TopNav />
          <Sidebar />
        </>
      ) : (
        /* Top Banner / Navigation for Student Portal */
        <header className="bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-slate-200 shadow-sm">
          <div className="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#1976d2] to-[#1565c0] flex items-center justify-center text-white font-bold text-lg shadow-md shadow-sky-900/10">
                OES
              </div>
              <div>
                <h1 className="text-base font-bold text-slate-800 leading-tight">Olive English School</h1>
                <p className="text-xs text-slate-500 font-medium">Student Portal & Academic Record</p>
              </div>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={() => {
                  logout();
                  navigate('/student_login');
                }}
                className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 text-xs font-semibold transition cursor-pointer"
              >
                <FiLogOut size={14} />
                <span>Logout</span>
              </button>
            </div>
          </div>
        </header>
      )}

      {/* Main Container */}
      <div className="flex-1 flex flex-col min-w-0 transition-all duration-300">
        <main className="max-w-6xl mx-auto px-4 sm:px-6 pt-6 pb-16 w-full">
        {/* Hold Alert Banner */}
        {isHold && (
          <div className="mb-6 p-4 sm:p-5 rounded-2xl bg-amber-50 border-2 border-amber-300 text-amber-900 shadow-sm flex items-start gap-4">
            <div className="p-2 rounded-xl bg-amber-100 text-amber-700 shrink-0">
              <FiAlertTriangle size={24} />
            </div>
            <div>
              <h3 className="font-bold text-base text-amber-900">
                Notice: Student Account / Marks On Administrative Hold
              </h3>
              <p className="text-sm text-amber-800 mt-1">
                Your report cards or profile details may be temporarily restricted by administration.
                Please contact the school office or class teacher for assistance.
              </p>
              {hold?.details && (
                <p className="text-xs text-amber-700 font-medium mt-1">
                  Hold Period: {hold.details.sprhold_from || 'Current'} &rarr; {hold.details.sprhold_to || 'Notice'}
                </p>
              )}
            </div>
          </div>
        )}

        {/* Student Profile Identity Card */}
        <div className="bg-white rounded-3xl shadow-md border border-slate-200/90 overflow-hidden mb-6">
          {/* Decorative Cover Gradient */}
          <div className="h-32 sm:h-40 bg-gradient-to-r from-[#1565c0] via-[#1976d2] to-sky-700 relative">
            <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]" />
          </div>

          <div className="px-6 pb-6 pt-0 relative">
            <div className="flex flex-col sm:flex-row items-center sm:items-end justify-between -mt-16 sm:-mt-20 gap-4 mb-4">
              {/* Photo & Basic Info */}
              <div className="flex flex-col sm:flex-row items-center sm:items-end gap-4 text-center sm:text-left">
                <div className="relative">
                  <div className="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl ring-4 ring-white shadow-xl bg-slate-100 overflow-hidden flex items-center justify-center">
                    {student.SPRIDEN_photo_path ? (
                      <img
                        src={student.SPRIDEN_photo_path}
                        alt={student.full_name}
                        className="w-full h-full object-cover"
                        onError={(e) => {
                          e.target.onerror = null;
                          e.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(student.full_name) + '&background=53769c&color=fff&size=128';
                        }}
                      />
                    ) : (
                      <div className="w-full h-full bg-gradient-to-tr from-[#1976d2] to-sky-400 flex items-center justify-center text-white text-3xl font-bold">
                        {student.SPRIDEN_student_name?.[0] || 'S'}
                      </div>
                    )}
                  </div>
                  <span className="absolute bottom-1 right-1 w-4 h-4 bg-emerald-500 ring-2 ring-white rounded-full" title="Active Student" />
                </div>

                <div className="pt-2 sm:pt-0">
                  <h2 className="text-xl sm:text-2xl font-bold text-slate-800">{student.full_name}</h2>
                  <div className="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-1 text-xs text-slate-600">
                    <span className="font-semibold bg-sky-50 text-[#1976d2] px-2.5 py-0.5 rounded-full border border-sky-200">
                      ID: {student.SPRIDEN_student_id}
                    </span>
                    {student.spriden_gr_number && (
                      <span className="font-semibold bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full">
                        GR: {student.spriden_gr_number}
                      </span>
                    )}
                    <span className="font-semibold bg-[#e3f2fd] text-[#1976d2] px-2.5 py-0.5 rounded-full border border-[#bbdefb]">
                      Class: {student.STVCLAS_standard ? `${student.STVCLAS_standard} - ${student.STVCLAS_class}` : 'Not Assigned'}
                    </span>
                    {student.STVCLAS_rollno && (
                      <span className="font-semibold bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full">
                        Roll: {student.STVCLAS_rollno}
                      </span>
                    )}
                    <span className="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full">
                      Year: {student.stvacdm_year || 'Active'}
                    </span>
                  </div>
                </div>
              </div>

              {/* Action Buttons */}
              <div className="flex flex-wrap items-center justify-center gap-2">
                <button
                  onClick={() => setShowAadharModal(true)}
                  className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-md shadow-emerald-700/20 transition cursor-pointer"
                >
                  <FiShield size={14} />
                  <span>Update KYC / Aadhaar</span>
                </button>
              </div>
            </div>

            {/* Quick Details Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-3 border-t border-slate-100 text-xs">
              <div className="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span className="text-slate-400 block font-medium">Parents</span>
                <p className="font-semibold text-slate-800 mt-0.5">
                  F: {student.SPRIDEN_father_name || 'N/A'} <br />
                  M: {student.SPRIDEN_mother_name || 'N/A'}
                </p>
              </div>
              <div className="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span className="text-slate-400 block font-medium">Contact & Mobile</span>
                <p className="font-semibold text-slate-800 mt-0.5">
                  📞 {student.stvpcnf_mobile || 'N/A'} <br />
                  💬 {student.stvpcnf_whatsapp || student.stvpcnf_mobile || 'N/A'}
                </p>
              </div>
              <div className="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span className="text-slate-400 block font-medium">Date of Birth & Gender</span>
                <p className="font-semibold text-slate-800 mt-0.5">
                  🎂 {student.SPRIDEN_birthdate ? new Date(student.SPRIDEN_birthdate).toLocaleDateString() : 'N/A'} <br />
                  👤 {student.SPRIDEN_gender === 'M' ? 'Male' : student.SPRIDEN_gender === 'F' ? 'Female' : student.SPRIDEN_gender || 'N/A'}
                </p>
              </div>
              <div className="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span className="text-slate-400 block font-medium">Class Teacher</span>
                {teacher ? (
                  <div className="mt-0.5">
                    <p className="font-semibold text-slate-800">{teacher.teacher_name}</p>
                    <div className="flex items-center gap-2 mt-1">
                      {teacher.teacher_whatsapp && (
                        <a
                          href={`https://api.whatsapp.com/send?phone=+91${teacher.teacher_whatsapp}`}
                          target="_blank"
                          rel="noreferrer"
                          className="text-emerald-600 hover:text-emerald-700 font-semibold inline-flex items-center gap-0.5"
                        >
                          <FiMessageSquare size={12} /> WhatsApp
                        </a>
                      )}
                      {teacher.teacher_mobile && (
                        <a
                          href={`tel:+91${teacher.teacher_mobile}`}
                          className="text-sky-600 hover:text-sky-700 font-semibold inline-flex items-center gap-0.5"
                        >
                          <FiPhone size={12} /> Call
                        </a>
                      )}
                    </div>
                  </div>
                ) : (
                  <p className="font-medium text-slate-500 mt-0.5">Not Assigned</p>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* Tab Navigation */}
        <div className="flex items-center gap-2 overflow-x-auto pb-2 mb-6 scrollbar-none border-b border-slate-200">
          {[
            { id: 'overview', label: 'Overview', icon: FiLayers },
            { id: 'fees', label: 'Fees Details', icon: FiDollarSign },
            { id: 'attendance', label: 'Attendance', icon: FiCheckCircle },
            { id: 'exams', label: 'Exam Marks', icon: FiAward },
            { id: 'subjects', label: 'Subjects', icon: FiBookOpen },
            { id: 'remarks', label: 'Remarks', icon: FiMessageSquare },
            { id: 'events', label: 'Events & Notices', icon: FiCalendar },
          ].map((tab) => {
            const Icon = tab.icon;
            const isActive = activeTab === tab.id;
            return (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-xs whitespace-nowrap transition cursor-pointer ${
                  isActive
                    ? 'bg-[#1976d2] text-white shadow-sm shadow-[#1976d2]/30'
                    : 'bg-white text-slate-600 hover:bg-slate-100 hover:text-slate-900 border border-slate-200/80'
                }`}
              >
                <Icon size={14} />
                <span>{tab.label}</span>
              </button>
            );
          })}
        </div>

        {/* ── TAB 1: OVERVIEW ── */}
        {activeTab === 'overview' && (
          <div className="space-y-6">
            {/* Quick KPI Stats */}
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
              <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                  <FiCheckCircle size={20} />
                </div>
                <div>
                  <span className="text-xs text-slate-500 font-medium">Attendance</span>
                  <h4 className="text-lg font-bold text-slate-800">
                    {showAttendance ? `${attendance.percentage}%` : 'Restricted'}
                  </h4>
                </div>
              </div>

              <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                  <FiDollarSign size={20} />
                </div>
                <div>
                  <span className="text-xs text-slate-500 font-medium">Fees Paid</span>
                  <h4 className="text-lg font-bold text-slate-800">
                    ₹{fees.total_paid.toLocaleString()}
                  </h4>
                </div>
              </div>

              <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                  <FiAlertTriangle size={20} />
                </div>
                <div>
                  <span className="text-xs text-slate-500 font-medium">Fees Due</span>
                  <h4 className="text-lg font-bold text-rose-600">
                    ₹{fees.net_total_balance.toLocaleString()}
                  </h4>
                </div>
              </div>

              <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-[#e3f2fd] text-[#1976d2] flex items-center justify-center shrink-0">
                  <FiAward size={20} />
                </div>
                <div>
                  <span className="text-xs text-slate-500 font-medium">Exams Taken</span>
                  <h4 className="text-lg font-bold text-slate-800">
                    {showMarks ? exams.length : 'Restricted'}
                  </h4>
                </div>
              </div>
            </div>

            {/* Split Row: Fees Summary & Recent Attendance */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Fees Summary Card */}
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <div className="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                  <h3 className="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <FiDollarSign className="text-emerald-600" />
                    <span>Fees Summary (फीस की जानकारी)</span>
                  </h3>
                  <button
                    onClick={() => setActiveTab('fees')}
                    className="text-xs text-[#1976d2] hover:underline font-semibold"
                  >
                    View Ledger &rarr;
                  </button>
                </div>

                <div className="grid grid-cols-2 gap-3 text-xs">
                  <div className="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <span className="text-slate-500 block">Total Academic Fees</span>
                    <span className="text-base font-bold text-slate-800">
                      ₹{fees.total_class_fees.toLocaleString()}
                    </span>
                  </div>
                  <div className="p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                    <span className="text-emerald-700 block font-medium">Paid Fees</span>
                    <span className="text-base font-bold text-emerald-800">
                      ₹{fees.total_paid.toLocaleString()}
                    </span>
                  </div>
                  <div className="p-3 rounded-xl bg-amber-50 border border-amber-100">
                    <span className="text-amber-700 block font-medium">Current Balance</span>
                    <span className="text-base font-bold text-amber-800">
                      ₹{fees.current_balance.toLocaleString()}
                    </span>
                  </div>
                  <div className="p-3 rounded-xl bg-rose-50 border border-rose-100">
                    <span className="text-rose-700 block font-medium">Total Pending Balance</span>
                    <span className="text-base font-bold text-rose-800">
                      ₹{fees.net_total_balance.toLocaleString()}
                    </span>
                  </div>
                </div>
              </div>

              {/* Attendance Card */}
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <div className="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                  <h3 className="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <FiCheckCircle className="text-sky-600" />
                    <span>Attendance Progress (उपस्थिति)</span>
                  </h3>
                  <button
                    onClick={() => setActiveTab('attendance')}
                    className="text-xs text-[#1976d2] hover:underline font-semibold"
                  >
                    Monthly View &rarr;
                  </button>
                </div>

                {showAttendance ? (
                  <div>
                    <div className="flex items-center justify-between mb-2">
                      <span className="text-xs text-slate-500 font-medium">Annual Percentage</span>
                      <span className="text-sm font-bold text-emerald-600">{attendance.percentage}%</span>
                    </div>
                    {/* Progress Bar */}
                    <div className="w-full h-3 bg-slate-100 rounded-full overflow-hidden mb-4">
                      <div
                        className="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500"
                        style={{ width: `${Math.min(100, attendance.percentage)}%` }}
                      />
                    </div>

                    <div className="grid grid-cols-3 gap-2 text-center text-xs">
                      <div className="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span className="text-slate-400 block font-medium">Working Days</span>
                        <span className="font-bold text-slate-800 text-sm">
                          {attendance.total_working_days}
                        </span>
                      </div>
                      <div className="p-2.5 rounded-xl bg-emerald-50 border border-emerald-100">
                        <span className="text-emerald-700 block font-medium">Present</span>
                        <span className="font-bold text-emerald-800 text-sm">{attendance.present_days}</span>
                      </div>
                      <div className="p-2.5 rounded-xl bg-rose-50 border border-rose-100">
                        <span className="text-rose-700 block font-medium">Absent</span>
                        <span className="font-bold text-rose-800 text-sm">{attendance.absent_days}</span>
                      </div>
                    </div>
                  </div>
                ) : (
                  <p className="text-xs text-slate-500 italic p-4 text-center">
                    Attendance records are not currently published.
                  </p>
                )}
              </div>
            </div>

            {/* School Events & Announcements */}
            {events.length > 0 && (
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <div className="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                  <h3 className="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <FiCalendar className="text-[#1976d2]" />
                    <span>Upcoming School Events & Notices</span>
                  </h3>
                  <button
                    onClick={() => setActiveTab('events')}
                    className="text-xs text-[#1976d2] hover:underline font-semibold"
                  >
                    View All &rarr;
                  </button>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                  {events.slice(0, 3).map((ev) => (
                    <div
                      key={ev.id}
                      className="p-3.5 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition"
                    >
                      <div className="flex items-center gap-1.5 text-[#1976d2] text-xs font-semibold mb-1">
                        <FiClock size={12} />
                        <span>{ev.start_date ? new Date(ev.start_date).toLocaleDateString() : 'Upcoming'}</span>
                      </div>
                      <h4 className="font-bold text-xs text-slate-800 line-clamp-1">{ev.title}</h4>
                      <p className="text-slate-500 text-xs mt-1 line-clamp-2">{ev.details || 'No additional details.'}</p>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        )}

        {/* ── TAB 2: FEES DETAILS ── */}
        {activeTab === 'fees' && (
          <div className="space-y-6">
            {/* Fees Metric Cards */}
            <div className="grid grid-cols-2 sm:grid-cols-5 gap-3">
              <div className="p-3.5 bg-white rounded-2xl shadow-sm border border-slate-200/80">
                <span className="text-xs text-slate-400 block font-medium">Total Fees</span>
                <span className="text-base font-bold text-slate-800">
                  ₹{fees.total_class_fees.toLocaleString()}
                </span>
              </div>
              <div className="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200/80">
                <span className="text-xs text-emerald-700 block font-medium">Paid Fees</span>
                <span className="text-base font-bold text-emerald-800">
                  ₹{fees.total_paid.toLocaleString()}
                </span>
              </div>
              <div className="p-3.5 bg-amber-50 rounded-2xl border border-amber-200/80">
                <span className="text-xs text-amber-700 block font-medium">Current Balance</span>
                <span className="text-base font-bold text-amber-800">
                  ₹{fees.current_balance.toLocaleString()}
                </span>
              </div>
              <div className="p-3.5 bg-rose-50 rounded-2xl border border-rose-200/80">
                <span className="text-xs text-rose-700 block font-medium">Last Year Balance</span>
                <span className="text-base font-bold text-rose-800">
                  ₹{fees.last_year_balance.toLocaleString()}
                </span>
              </div>
              <div className="p-3.5 bg-[#e3f2fd] rounded-2xl border border-[#bbdefb] col-span-2 sm:col-span-1">
                <span className="text-xs text-[#1976d2] block font-medium">Net Due</span>
                <span className="text-base font-bold text-[#0d47a1]">
                  ₹{fees.net_total_balance.toLocaleString()}
                </span>
              </div>
            </div>

            {/* Fees Receipts Table */}
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
              <div className="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 className="font-bold text-sm text-slate-800">
                  Fees Receipts & Payment History
                </h3>
                <span className="text-xs text-slate-500 font-medium">
                  {fees.receipts?.length || 0} receipt(s)
                </span>
              </div>

              {fees.receipts && fees.receipts.length > 0 ? (
                <div className="overflow-x-auto">
                  <table className="w-full text-xs text-left">
                    <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                      <tr>
                        <th className="px-4 py-3">Receipt #</th>
                        <th className="px-4 py-3">Payment Date</th>
                        <th className="px-4 py-3">Amount</th>
                        <th className="px-4 py-3">Mode</th>
                        <th className="px-4 py-3">Details / Head</th>
                        <th className="px-4 py-3">Received By</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 text-slate-700">
                      {fees.receipts.map((rcpt) => (
                        <tr key={rcpt.id} className="hover:bg-slate-50/60 transition">
                          <td className="px-4 py-3 font-semibold text-slate-900">#{rcpt.id}</td>
                          <td className="px-4 py-3">
                            {rcpt.sprfees_feesdate ? new Date(rcpt.sprfees_feesdate).toLocaleDateString() : '-'}
                          </td>
                          <td className="px-4 py-3 font-bold text-emerald-700">
                            ₹{parseFloat(rcpt.sprfees_amount || 0).toLocaleString()}
                          </td>
                          <td className="px-4 py-3 capitalize">{rcpt.payment_mode || 'Cash'}</td>
                          <td className="px-4 py-3">{rcpt.sprfees_details || 'Academic Fees'}</td>
                          <td className="px-4 py-3 text-slate-500">{rcpt.received_by_name || 'Admin Office'}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <div className="p-8 text-center text-slate-400 text-xs">
                  No payment receipts recorded for this academic year yet.
                </div>
              )}
            </div>
          </div>
        )}

        {/* ── TAB 3: ATTENDANCE ── */}
        {activeTab === 'attendance' && (
          <div className="space-y-6">
            {!showAttendance ? (
              <div className="bg-white rounded-2xl p-8 text-center border border-slate-200">
                <FiInfo size={28} className="text-slate-400 mx-auto mb-2" />
                <h4 className="font-bold text-sm text-slate-800">Attendance Restricted</h4>
                <p className="text-xs text-slate-500 mt-1">
                  Attendance visibility is currently turned off by school administrators.
                </p>
              </div>
            ) : (
              <>
                {/* Summary Progress */}
                <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                  <h3 className="font-bold text-sm text-slate-800 mb-3">Overall Annual Attendance</h3>
                  <div className="flex items-center gap-4">
                    <div className="w-20 h-20 rounded-2xl bg-emerald-50 text-emerald-700 flex flex-col items-center justify-center shrink-0 border border-emerald-200">
                      <span className="text-xl font-extrabold">{attendance.percentage}%</span>
                      <span className="text-[10px] font-semibold uppercase tracking-wider">Present</span>
                    </div>
                    <div className="flex-1 space-y-2">
                      <div className="flex justify-between text-xs font-semibold text-slate-600">
                        <span>Total Working Days: {attendance.total_working_days}</span>
                        <span>{attendance.present_days} Present / {attendance.absent_days} Absent</span>
                      </div>
                      <div className="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div
                          className="h-full bg-emerald-500 rounded-full"
                          style={{ width: `${Math.min(100, attendance.percentage)}%` }}
                        />
                      </div>
                    </div>
                  </div>
                </div>

                {/* Monthly Breakdown Table */}
                <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                  <div className="p-4 border-b border-slate-100">
                    <h3 className="font-bold text-sm text-slate-800">Monthly Attendance Breakdown</h3>
                  </div>

                  {attendance.monthly_breakdown && attendance.monthly_breakdown.length > 0 ? (
                    <div className="overflow-x-auto">
                      <table className="w-full text-xs text-left">
                        <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                          <tr>
                            <th className="px-4 py-3">Month</th>
                            <th className="px-4 py-3">Total Working Days</th>
                            <th className="px-4 py-3">Days Present</th>
                            <th className="px-4 py-3">Days Absent</th>
                            <th className="px-4 py-3">Rate</th>
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 text-slate-700">
                          {attendance.monthly_breakdown.map((m) => {
                            const pct =
                              m.total_count > 0 ? Math.round((m.present_count / m.total_count) * 100) : 0;
                            return (
                              <tr key={m.month_key} className="hover:bg-slate-50/60 transition">
                                <td className="px-4 py-3 font-semibold text-slate-900">{m.month_name}</td>
                                <td className="px-4 py-3">{m.total_count}</td>
                                <td className="px-4 py-3 font-bold text-emerald-600">{m.present_count}</td>
                                <td className="px-4 py-3 font-bold text-rose-600">{m.absent_count}</td>
                                <td className="px-4 py-3">
                                  <span
                                    className={`px-2 py-0.5 rounded-full font-semibold text-[11px] ${
                                      pct >= 75
                                        ? 'bg-emerald-50 text-emerald-700'
                                        : 'bg-amber-50 text-amber-700'
                                    }`}
                                  >
                                    {pct}%
                                  </span>
                                </td>
                              </tr>
                            );
                          })}
                        </tbody>
                      </table>
                    </div>
                  ) : (
                    <div className="p-8 text-center text-slate-400 text-xs">
                      No monthly attendance data recorded yet.
                    </div>
                  )}
                </div>
              </>
            )}
          </div>
        )}

        {/* ── TAB 4: EXAMS & MARKS ── */}
        {activeTab === 'exams' && (
          <div className="space-y-6">
            {!showMarks ? (
              <div className="bg-white rounded-2xl p-8 text-center border border-slate-200">
                <FiInfo size={28} className="text-slate-400 mx-auto mb-2" />
                <h4 className="font-bold text-sm text-slate-800">Exam Results Restricted</h4>
                <p className="text-xs text-slate-500 mt-1">
                  Marks analysis is currently restricted by school administration.
                </p>
              </div>
            ) : exams.length === 0 ? (
              <div className="bg-white rounded-2xl p-8 text-center border border-slate-200 text-slate-400 text-xs">
                No examination results uploaded yet for this student.
              </div>
            ) : (
              <div className="space-y-4">
                {exams.map((ex, idx) => (
                  <div key={idx} className="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div className="p-4 bg-gradient-to-r from-slate-50 to-sky-50/50 border-b border-slate-100 flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <FiAward className="text-[#1976d2]" size={18} />
                        <h3 className="font-bold text-sm text-slate-800">{ex.exam_name}</h3>
                      </div>
                      <div className="flex items-center gap-2">
                        <span className="text-xs font-semibold text-slate-600">
                          {ex.total_obtained} / {ex.total_max} Marks
                        </span>
                        <span className="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                          {ex.percentage}%
                        </span>
                      </div>
                    </div>

                    <div className="overflow-x-auto">
                      <table className="w-full text-xs text-left">
                        <thead className="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 text-[11px] uppercase">
                          <tr>
                            <th className="px-4 py-2.5">Subject</th>
                            <th className="px-4 py-2.5">Marks Scored</th>
                            <th className="px-4 py-2.5">Max Marks</th>
                            <th className="px-4 py-2.5">Grade</th>
                          </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100 text-slate-700">
                          {ex.subjects.map((sub, sIdx) => (
                            <tr key={sIdx} className="hover:bg-slate-50/60 transition">
                              <td className="px-4 py-2.5 font-semibold text-slate-900">{sub.subject}</td>
                              <td className="px-4 py-2.5 font-bold text-slate-800">{sub.marks_obtained}</td>
                              <td className="px-4 py-2.5 text-slate-500">{sub.max_marks}</td>
                              <td className="px-4 py-2.5">
                                <span className="px-2 py-0.5 rounded bg-slate-100 font-bold text-slate-800">
                                  {sub.grade || '-'}
                                </span>
                              </td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        )}

        {/* ── TAB 5: SUBJECTS ── */}
        {activeTab === 'subjects' && (
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 className="font-bold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100">
              Assigned Subjects for Standard {student.STVCLAS_standard || ''}
            </h3>
            {subjects && subjects.length > 0 ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                {subjects.map((sub) => (
                  <div
                    key={sub.stvsubj_id}
                    className="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center gap-3"
                  >
                    <div className="w-10 h-10 rounded-xl bg-sky-50 text-[#1976d2] flex items-center justify-center font-bold shrink-0">
                      <FiBookOpen size={18} />
                    </div>
                    <div>
                      <h4 className="font-bold text-xs text-slate-800">{sub.stvsubj_subject}</h4>
                      {sub.stvsubj_code && (
                        <span className="text-[11px] text-slate-400 font-medium">
                          Code: {sub.stvsubj_code}
                        </span>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-xs text-slate-400 p-4 text-center">No subjects mapped to this class.</p>
            )}
          </div>
        )}

        {/* ── TAB 6: REMARKS ── */}
        {activeTab === 'remarks' && (
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 className="font-bold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100">
              Teacher's Remarks & Feedback (टीचर का रिमार्क)
            </h3>
            {remarks && remarks.length > 0 ? (
              <div className="space-y-3">
                {remarks.map((r) => (
                  <div key={r.id} className="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <div className="flex items-center justify-between text-xs text-slate-500 mb-2">
                      <span className="font-bold text-slate-700">{r.teacher_name || 'Class Teacher'}</span>
                      <span>{r.date ? new Date(r.date).toLocaleDateString() : ''}</span>
                    </div>
                    <p className="text-xs text-slate-800 font-medium">{r.remark}</p>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-xs text-slate-400 p-6 text-center">No teacher remarks recorded.</p>
            )}
          </div>
        )}

        {/* ── TAB 7: EVENTS & NOTICES ── */}
        {activeTab === 'events' && (
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 className="font-bold text-sm text-slate-800 mb-4 pb-3 border-b border-slate-100">
              School Events, Notices & Calendar (मैसेज और जानकारी)
            </h3>
            {events && events.length > 0 ? (
              <div className="space-y-3">
                {events.map((ev) => (
                  <div key={ev.id} className="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                    <div className="flex items-center gap-2 text-xs text-[#1976d2] font-semibold mb-1">
                      <FiCalendar size={13} />
                      <span>{ev.start_date ? new Date(ev.start_date).toLocaleDateString() : 'Scheduled'}</span>
                    </div>
                    <h4 className="font-bold text-sm text-slate-800">{ev.title}</h4>
                    <p className="text-xs text-slate-600 mt-1 whitespace-pre-line">{ev.details}</p>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-xs text-slate-400 p-6 text-center">No current events or notices.</p>
            )}
          </div>
        )}
      </main>
      </div>

      {/* Aadhar & KYC Modal */}
      {showAadharModal && (
        <div className="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 max-w-lg w-full p-6 animate-scaleIn">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
              <div className="flex items-center gap-2">
                <FiShield className="text-emerald-600" size={20} />
                <h3 className="font-bold text-base text-slate-800">Update Aadhaar & KYC Details</h3>
              </div>
              <button
                onClick={() => setShowAadharModal(false)}
                className="text-slate-400 hover:text-slate-600 text-lg font-bold p-1"
              >
                &times;
              </button>
            </div>

            {aadharSuccess && (
              <div className="p-3 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {aadharSuccess}
              </div>
            )}
            {aadharError && (
              <div className="p-3 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold">
                {aadharError}
              </div>
            )}

            <form onSubmit={handleAadharSave} className="space-y-4 text-xs">
              {/* Student Aadhaar */}
              <div className="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                <h4 className="font-bold text-slate-700">Student Information</h4>
                <div>
                  <label className="block text-slate-500 font-medium mb-1">Student Aadhaar Number</label>
                  <input
                    type="text"
                    maxLength={12}
                    value={aadharForm.student_aadhar_number}
                    onChange={(e) =>
                      setAadharForm({ ...aadharForm, student_aadhar_number: e.target.value })
                    }
                    placeholder="12-digit Aadhaar Number"
                    className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                  />
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Student Name</label>
                    <input
                      type="text"
                      value={aadharForm.student_name}
                      onChange={(e) => setAadharForm({ ...aadharForm, student_name: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                    />
                  </div>
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Student DOB</label>
                    <input
                      type="date"
                      value={aadharForm.student_dob}
                      onChange={(e) => setAadharForm({ ...aadharForm, student_dob: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                    />
                  </div>
                </div>
              </div>

              {/* Father Aadhaar */}
              <div className="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                <h4 className="font-bold text-slate-700">Father Information</h4>
                <div>
                  <label className="block text-slate-500 font-medium mb-1">Father Aadhaar Number</label>
                  <input
                    type="text"
                    maxLength={12}
                    value={aadharForm.father_aadhar_number}
                    onChange={(e) =>
                      setAadharForm({ ...aadharForm, father_aadhar_number: e.target.value })
                    }
                    placeholder="12-digit Aadhaar Number"
                    className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                  />
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Father Name</label>
                    <input
                      type="text"
                      value={aadharForm.father_name}
                      onChange={(e) => setAadharForm({ ...aadharForm, father_name: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                    />
                  </div>
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Father Mobile</label>
                    <input
                      type="text"
                      maxLength={10}
                      value={aadharForm.fat_mob_no}
                      onChange={(e) => setAadharForm({ ...aadharForm, fat_mob_no: e.target.value })}
                      placeholder="10-digit Mobile"
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                    />
                  </div>
                </div>
              </div>

              {/* Mother Aadhaar */}
              <div className="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                <h4 className="font-bold text-slate-700">Mother Information</h4>
                <div>
                  <label className="block text-slate-500 font-medium mb-1">Mother Aadhaar Number</label>
                  <input
                    type="text"
                    maxLength={12}
                    value={aadharForm.mother_aadhar_number}
                    onChange={(e) =>
                      setAadharForm({ ...aadharForm, mother_aadhar_number: e.target.value })
                    }
                    placeholder="12-digit Aadhaar Number"
                    className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                  />
                </div>
                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Mother Name</label>
                    <input
                      type="text"
                      value={aadharForm.mother_name}
                      onChange={(e) => setAadharForm({ ...aadharForm, mother_name: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                    />
                  </div>
                  <div>
                    <label className="block text-slate-500 font-medium mb-1">Mother DOB</label>
                    <input
                      type="date"
                      value={aadharForm.mother_dob}
                      onChange={(e) => setAadharForm({ ...aadharForm, mother_dob: e.target.value })}
                      className="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-[#1976d2] outline-none"
                    />
                  </div>
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setShowAadharModal(false)}
                  className="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 transition font-medium"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={savingAadhar}
                  className="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium shadow-md transition disabled:opacity-60"
                >
                  {savingAadhar ? 'Saving...' : 'Save Aadhaar KYC'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
