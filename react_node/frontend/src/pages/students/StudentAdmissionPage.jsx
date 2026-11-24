/**
 * Student Admission Form Page — 100% CI3 Functional & Visual Parity.
 * Direct conversion of `Student/new-form-admission.php` & `Student::add_student_new_data()`.
 */
import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  fetchStudentOptions,
  fetchNextStudentId,
  createStudent,
} from '../../services/studentService.js';
import {
  FiUserPlus,
  FiPrinter,
  FiSave,
  FiArrowLeft,
  FiUploadCloud,
  FiCheckCircle,
  FiAlertCircle,
} from 'react-icons/fi';

export default function StudentAdmissionPage() {
  const navigate = useNavigate();
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [options, setOptions] = useState({ academicYears: [], classes: [], divisions: [] });

  // Photo state
  const [photoFile, setPhotoFile] = useState(null);
  const [photoPreview, setPhotoPreview] = useState(null);

  // Form State matching CI3 `new-form-admission.php` fields
  const [formData, setFormData] = useState({
    // Academic & Identification
    acdm_year: '',
    student_id: '',
    gr_number: '',
    reg_number: '',
    pan_no: '',
    apaar_no: '',
    admission_class: '',
    division: 'A',
    roll_no: '0',
    admission_note: '',

    // Student Names & Personal
    surname: '',
    student_name: '',
    father_name: '',
    mother_name: '',
    gender: 'Male',
    birthdate: '',
    birthdate_words: '',
    birth_place: '',
    religion: 'Islam',
    caste: '',
    sub_caste: '',
    nationality: 'Indian',
    mother_tongue: 'Urdu',

    // Physical Measurements
    height: '', // e.g. "4.2"
    weight: '', // e.g. "25"

    // Aadhar Details
    aadhar_number: '',
    student_name_aadhar: '',
    father_aadhar_no: '',
    father_name_aadhar: '',
    mother_aadhar_no: '',
    mother_name_aadhar: '',

    // Parent Contact & Qualifications
    father_qualification: '',
    mother_qualification: '',
    parents_mobile: '',
    mobile2: '',
    whatsapp_number: '',
    sms_number: '',

    // Address Details
    main_address: '',
    alt_address: '',
    address_landmark: '',
    nearby_masjid: '',
    taluka: 'Bhiwandi',
    district: 'Thane',
    state: 'Maharashtra',

    // Academic History & Documents
    last_school: '',
    rte_status: 'No',
    main_docs: '1',
    student_aadhar: '1',
    father_aadhar: '1',
    passport_photo: '1',
  });

  // Load initial options & next ID
  useEffect(() => {
    async function init() {
      try {
        setLoading(true);
        const optRes = await fetchStudentOptions();
        if (optRes.success && optRes.data) {
          setOptions(optRes.data);
          const defaultAcdm = String(optRes.data.currentAcdmId || optRes.data.academicYears[0]?.stvacdm_id || '');
          setFormData((prev) => ({
            ...prev,
            acdm_year: defaultAcdm,
            admission_class: optRes.data.classes[0] || '1',
          }));

          // Fetch next student ID for this year
          if (defaultAcdm) {
            const idRes = await fetchNextStudentId(defaultAcdm);
            if (idRes.success && idRes.data?.studentId) {
              setFormData((prev) => ({ ...prev, student_id: idRes.data.studentId }));
            }
          }
        }
      } catch (err) {
        console.error('Failed to init admission form:', err);
      } finally {
        setLoading(false);
      }
    }
    init();
  }, []);

  // Update Next ID if Academic Year changes
  const handleAcdmYearChange = async (newAcdmId) => {
    setFormData((prev) => ({ ...prev, acdm_year: newAcdmId }));
    try {
      const res = await fetchNextStudentId(newAcdmId);
      if (res.success && res.data?.studentId) {
        setFormData((prev) => ({ ...prev, student_id: res.data.studentId }));
      }
    } catch (err) {
      console.error('Failed to update student ID:', err);
    }
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: type === 'checkbox' ? (checked ? '1' : '0') : value,
    }));
  };

  // Photo change
  const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setPhotoFile(file);
      setPhotoPreview(URL.createObjectURL(file));
    }
  };

  // Convert height to cm for visual preview
  const getCmPreview = () => {
    const raw = (formData.height || '').trim().replace(/[^0-9.]/g, '');
    if ((raw.match(/\./g) || []).length === 1) {
      const [feet, inches] = raw.split('.').map(n => parseInt(n, 10) || 0);
      const totalInches = (feet * 12) + inches;
      return (totalInches * 2.54).toFixed(1) + ' cm';
    }
    return '';
  };

  // Submit Handler
  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.student_name.trim()) {
      alert('Student First Name is required.');
      return;
    }
    if (!formData.student_id.trim()) {
      alert('Student ID is required.');
      return;
    }

    try {
      setSubmitting(true);
      const data = new FormData();
      Object.keys(formData).forEach((key) => {
        data.append(key, formData[key]);
      });
      if (photoFile) {
        data.append('photo', photoFile);
      }

      const res = await createStudent(data);
      if (res.success) {
        alert('Student Registered Successfully!');
        navigate(`/students/${res.data.id}`);
      }
    } catch (err) {
      alert('Failed to register student: ' + (err.response?.data?.message || err.message));
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#f4f7fa] flex flex-col font-sans">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} isSidebarOpen={sidebarOpen} />

      <main className="flex-1 p-4 md:p-6 transition-all duration-300">
        <div className="max-w-5xl mx-auto space-y-4">

          {/* Action Header */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between no-print">
            <div className="flex items-center gap-3">
              <Link
                to="/students"
                className="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors"
                title="Back to Students"
              >
                <FiArrowLeft size={18} />
              </Link>
              <div>
                <h1 className="text-xl font-bold text-slate-900 flex items-center gap-2">
                  <FiUserPlus className="text-[#1976d2]" />
                  Student Admission Form
                </h1>
                <p className="text-xs text-slate-500">
                  Register new student into Olive English School ERP
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2">
              <button
                type="button"
                onClick={() => window.print()}
                className="inline-flex items-center gap-1.5 px-3 py-2 bg-white text-slate-700 border border-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-50 shadow-sm transition-all"
              >
                <FiPrinter size={14} />
                <span>Print Form</span>
              </button>
              <button
                type="button"
                onClick={handleSubmit}
                disabled={submitting}
                className="inline-flex items-center gap-1.5 px-4 py-2 bg-[#1976d2] text-white text-xs font-semibold rounded-lg hover:bg-[#1565c0] shadow-sm transition-all cursor-pointer"
              >
                <FiSave size={14} />
                <span>{submitting ? 'Submitting...' : 'Submit Admission'}</span>
              </button>
            </div>
          </div>

          {/* Main Admission Document Card */}
          <form onSubmit={handleSubmit} className="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8 space-y-8">

            {/* School Header Branding */}
            <div className="text-center pb-6 border-b border-slate-200">
              <div className="flex items-center justify-center gap-3 mb-2">
                <div className="w-12 h-12 rounded-xl bg-[#1976d2] flex items-center justify-center font-bold text-white text-lg shadow">
                  OES
                </div>
                <div className="text-left">
                  <h2 className="text-xl font-black text-slate-900 tracking-tight">
                    OLIVE ENGLISH HIGH SCHOOL
                  </h2>
                  <p className="text-xs text-slate-500 font-medium">
                    Kalyan Road, Bhiwandi, Dist. Thane | Recognized by Govt. of Maharashtra
                  </p>
                </div>
              </div>
              <div className="inline-block mt-2 px-4 py-1 bg-slate-100 rounded-full text-xs font-bold text-[#1976d2] uppercase tracking-wider">
                Official Student Admission Form
              </div>
            </div>

            {/* ─── SECTION 1: Academic & GR Identification ─── */}
            <div>
              <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3 flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-[#1976d2]"></span>
                1. Academic & Enrollment Details
              </h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Academic Year *
                  </label>
                  <select
                    name="acdm_year"
                    value={formData.acdm_year}
                    onChange={(e) => handleAcdmYearChange(e.target.value)}
                    className="w-full border border-slate-300 rounded-lg p-2 font-semibold bg-slate-50 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    required
                  >
                    {options.academicYears.map((yr) => (
                      <option key={yr.stvacdm_id} value={yr.stvacdm_id}>
                        {yr.stvacdm_year}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Student ID (Generated) *
                  </label>
                  <input
                    type="text"
                    name="student_id"
                    value={formData.student_id}
                    onChange={handleChange}
                    className="w-full border border-slate-300 rounded-lg p-2 font-mono font-bold bg-amber-50 text-amber-900 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    required
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    GR Number
                  </label>
                  <input
                    type="text"
                    name="gr_number"
                    value={formData.gr_number}
                    onChange={handleChange}
                    placeholder="Enter General Register No"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Registration No
                  </label>
                  <input
                    type="text"
                    name="reg_number"
                    value={formData.reg_number}
                    onChange={handleChange}
                    placeholder="Reg No"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Admission Standard *
                  </label>
                  <select
                    name="admission_class"
                    value={formData.admission_class}
                    onChange={handleChange}
                    className="w-full border border-slate-300 rounded-lg p-2 font-semibold bg-slate-50 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    required
                  >
                    {options.classes.map((cls) => (
                      <option key={cls} value={cls}>
                        {cls}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Division
                  </label>
                  <select
                    name="division"
                    value={formData.division}
                    onChange={handleChange}
                    className="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  >
                    <option value="A">Division A (Boys/Standard)</option>
                    <option value="B">Division B (Girls/Standard)</option>
                    <option value="C">Division C</option>
                    <option value="D">Division D</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Roll No
                  </label>
                  <input
                    type="number"
                    name="roll_no"
                    value={formData.roll_no}
                    onChange={handleChange}
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    RTE Student?
                  </label>
                  <select
                    name="rte_status"
                    value={formData.rte_status}
                    onChange={handleChange}
                    className="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  >
                    <option value="No">No</option>
                    <option value="Yes">Yes (Right to Education)</option>
                  </select>
                </div>
              </div>
            </div>

            {/* ─── SECTION 2: Personal & Identity Information ─── */}
            <div>
              <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3 flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-[#1976d2]"></span>
                2. Student Personal Information
              </h3>
              <div className="grid grid-cols-1 md:grid-cols-4 gap-6">

                {/* Photo Upload Column */}
                <div className="flex flex-col items-center justify-center p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 text-center">
                  <div className="w-28 h-36 bg-slate-200 rounded-lg overflow-hidden border border-slate-300 flex items-center justify-center mb-3">
                    {photoPreview ? (
                      <img src={photoPreview} alt="Preview" className="w-full h-full object-cover" />
                    ) : (
                      <div className="text-slate-400 text-[10px] text-center p-2">
                        Passport Size Photo
                      </div>
                    )}
                  </div>
                  <label className="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-700 text-xs font-semibold hover:bg-slate-100 shadow-sm">
                    <FiUploadCloud size={14} />
                    <span>Upload Photo</span>
                    <input
                      type="file"
                      accept="image/*"
                      onChange={handlePhotoChange}
                      className="hidden"
                    />
                  </label>
                </div>

                {/* Names Column */}
                <div className="md:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Surname
                    </label>
                    <input
                      type="text"
                      name="surname"
                      value={formData.surname}
                      onChange={handleChange}
                      placeholder="e.g. Ansari / Khan"
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none uppercase"
                    />
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Student First Name *
                    </label>
                    <input
                      type="text"
                      name="student_name"
                      value={formData.student_name}
                      onChange={handleChange}
                      placeholder="e.g. Mohammad / Ayesha"
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none uppercase font-bold"
                      required
                    />
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Father's Name
                    </label>
                    <input
                      type="text"
                      name="father_name"
                      value={formData.father_name}
                      onChange={handleChange}
                      placeholder="Father Name"
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none uppercase"
                    />
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Mother's Name
                    </label>
                    <input
                      type="text"
                      name="mother_name"
                      value={formData.mother_name}
                      onChange={handleChange}
                      placeholder="Mother Name"
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none uppercase"
                    />
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Gender
                    </label>
                    <select
                      name="gender"
                      value={formData.gender}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    >
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Date of Birth
                    </label>
                    <input
                      type="date"
                      name="birthdate"
                      value={formData.birthdate}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>

                  <div className="sm:col-span-2">
                    <label className="block font-semibold text-slate-700 mb-1">
                      Date of Birth (In Words)
                    </label>
                    <input
                      type="text"
                      name="birthdate_words"
                      value={formData.birthdate_words}
                      onChange={handleChange}
                      placeholder="e.g. Fifth August Two Thousand Fifteen"
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Place of Birth
                    </label>
                    <input
                      type="text"
                      name="birth_place"
                      value={formData.birth_place}
                      onChange={handleChange}
                      placeholder="e.g. Bhiwandi"
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Religion
                    </label>
                    <input
                      type="text"
                      name="religion"
                      value={formData.religion}
                      onChange={handleChange}
                      className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Caste / Sub-Caste
                    </label>
                    <div className="flex gap-2">
                      <input
                        type="text"
                        name="caste"
                        value={formData.caste}
                        onChange={handleChange}
                        placeholder="Caste"
                        className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                      />
                      <input
                        type="text"
                        name="sub_caste"
                        value={formData.sub_caste}
                        onChange={handleChange}
                        placeholder="Sub"
                        className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block font-semibold text-slate-700 mb-1">
                      Nationality & Mother Tongue
                    </label>
                    <div className="flex gap-2">
                      <input
                        type="text"
                        name="nationality"
                        value={formData.nationality}
                        onChange={handleChange}
                        className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                      />
                      <input
                        type="text"
                        name="mother_tongue"
                        value={formData.mother_tongue}
                        onChange={handleChange}
                        className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* ─── SECTION 3: Physical & Aadhar Information ─── */}
            <div>
              <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3 flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-[#1976d2]"></span>
                3. Physical Measurements & Aadhar Information
              </h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Height (feet.inches) {getCmPreview() && <span className="text-[#1976d2]">({getCmPreview()})</span>}
                  </label>
                  <input
                    type="text"
                    name="height"
                    value={formData.height}
                    onChange={handleChange}
                    placeholder="e.g. 4.2"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Weight (kg)
                  </label>
                  <input
                    type="text"
                    name="weight"
                    value={formData.weight}
                    onChange={handleChange}
                    placeholder="e.g. 28"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    APAAR Number
                  </label>
                  <input
                    type="text"
                    name="apaar_no"
                    value={formData.apaar_no}
                    onChange={handleChange}
                    placeholder="12-digit APAAR"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    PAN No
                  </label>
                  <input
                    type="text"
                    name="pan_no"
                    value={formData.pan_no}
                    onChange={handleChange}
                    placeholder="Student PAN"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-700 mb-1">
                    Student Aadhar No & Name on Aadhar
                  </label>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      name="aadhar_number"
                      value={formData.aadhar_number}
                      onChange={handleChange}
                      placeholder="12-digit Aadhar"
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none font-mono"
                    />
                    <input
                      type="text"
                      name="student_name_aadhar"
                      value={formData.student_name_aadhar}
                      onChange={handleChange}
                      placeholder="Exact name on Aadhar"
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-700 mb-1">
                    Father Aadhar No & Name on Aadhar
                  </label>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      name="father_aadhar_no"
                      value={formData.father_aadhar_no}
                      onChange={handleChange}
                      placeholder="Father Aadhar No"
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none font-mono"
                    />
                    <input
                      type="text"
                      name="father_name_aadhar"
                      value={formData.father_name_aadhar}
                      onChange={handleChange}
                      placeholder="Father name on Aadhar"
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-700 mb-1">
                    Mother Aadhar No & Name on Aadhar
                  </label>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      name="mother_aadhar_no"
                      value={formData.mother_aadhar_no}
                      onChange={handleChange}
                      placeholder="Mother Aadhar No"
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none font-mono"
                    />
                    <input
                      type="text"
                      name="mother_name_aadhar"
                      value={formData.mother_name_aadhar}
                      onChange={handleChange}
                      placeholder="Mother name on Aadhar"
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Father Qualification
                  </label>
                  <input
                    type="text"
                    name="father_qualification"
                    value={formData.father_qualification}
                    onChange={handleChange}
                    placeholder="e.g. Graduate"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Mother Qualification
                  </label>
                  <input
                    type="text"
                    name="mother_qualification"
                    value={formData.mother_qualification}
                    onChange={handleChange}
                    placeholder="e.g. SSC / HSC"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>
              </div>
            </div>

            {/* ─── SECTION 4: Contact & Communication ─── */}
            <div>
              <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-3 flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-[#1976d2]"></span>
                4. Parents Contact & Address Details
              </h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Primary Mobile *
                  </label>
                  <input
                    type="tel"
                    name="parents_mobile"
                    value={formData.parents_mobile}
                    onChange={handleChange}
                    placeholder="10-digit primary mobile"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none font-semibold"
                    required
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Alternate Mobile 2
                  </label>
                  <input
                    type="tel"
                    name="mobile2"
                    value={formData.mobile2}
                    onChange={handleChange}
                    placeholder="Optional second number"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    WhatsApp Number
                  </label>
                  <input
                    type="tel"
                    name="whatsapp_number"
                    value={formData.whatsapp_number}
                    onChange={handleChange}
                    placeholder="For homework & notifications"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    SMS Alert Number
                  </label>
                  <input
                    type="tel"
                    name="sms_number"
                    value={formData.sms_number}
                    onChange={handleChange}
                    placeholder="For absence & fee SMS"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-700 mb-1">
                    Main Residential Address *
                  </label>
                  <textarea
                    rows="2"
                    name="main_address"
                    value={formData.main_address}
                    onChange={handleChange}
                    placeholder="Full residential street, room/building address..."
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    required
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-700 mb-1">
                    Alternate / Permanent Address
                  </label>
                  <textarea
                    rows="2"
                    name="alt_address"
                    value={formData.alt_address}
                    onChange={handleChange}
                    placeholder="Alternate address or native place..."
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Landmark
                  </label>
                  <input
                    type="text"
                    name="address_landmark"
                    value={formData.address_landmark}
                    onChange={handleChange}
                    placeholder="Nearby landmark"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Nearby Masjid
                  </label>
                  <input
                    type="text"
                    name="nearby_masjid"
                    value={formData.nearby_masjid}
                    onChange={handleChange}
                    placeholder="Local Masjid name"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Taluka & District
                  </label>
                  <div className="flex gap-2">
                    <input
                      type="text"
                      name="taluka"
                      value={formData.taluka}
                      onChange={handleChange}
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                    <input
                      type="text"
                      name="district"
                      value={formData.district}
                      onChange={handleChange}
                      className="w-1/2 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                    />
                  </div>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">
                    Previous School Attended
                  </label>
                  <input
                    type="text"
                    name="last_school"
                    value={formData.last_school}
                    onChange={handleChange}
                    placeholder="School / KG Name"
                    className="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#1976d2] focus:outline-none"
                  />
                </div>
              </div>
            </div>

            {/* ─── Submit Bar ─── */}
            <div className="pt-6 border-t border-slate-200 flex items-center justify-between no-print">
              <Link
                to="/students"
                className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
              >
                Cancel
              </Link>
              <button
                type="submit"
                disabled={submitting}
                className="px-6 py-2.5 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-xs font-bold shadow-md flex items-center gap-2 cursor-pointer"
              >
                <FiSave size={15} />
                <span>{submitting ? 'Registering Student...' : 'Register Student & Generate Records'}</span>
              </button>
            </div>

          </form>

        </div>
      </main>
    </div>
  );
}
