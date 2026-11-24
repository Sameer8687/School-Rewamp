import React, { useState } from 'react';
import api from '../../services/api.js';
import {
  FiCheckCircle,
  FiAlertCircle,
  FiBookOpen,
  FiUser,
  FiHome,
  FiAward,
  FiFileText,
  FiSend,
} from 'react-icons/fi';

export default function PublicJuniorCollegeFormPage() {
  const [stream, setStream] = useState('Science');
  const [submitting, setSubmitting] = useState(false);
  const [successAppId, setSuccessAppId] = useState(null);
  const [errorMessage, setErrorMessage] = useState('');

  // Form State
  const [formData, setFormData] = useState({
    db_branch: 'Olive Junior College',
    db_stream: 'Science',
    db_admissiontype: 'New Admission',
    db_class: '11th',
    db_division: 'A',
    db_medium: 'English',

    // Student
    firstname: '',
    Middlename: '',
    lastname: '',
    date: '',
    db_nationality: 'Indian',
    db_place: '',
    db_gender: 'Male',
    db_mothertounge: 'Urdu',
    db_religion: 'Islam',
    db_caste: '',
    db_adharnumber: '',

    // Parent
    db_fatherfirstname: '',
    db_fathermiddlename: '',
    db_fatherlastname: '',
    db_fatheradharnumber: '',
    db_fatherqualification: '',
    db_fatheroccupation: 'Business',
    db_fathersannualincome: '2 Lakh to 4 Lakh',
    db_mothersname: '',
    db_mothersqualificaiton: '',
    db_mothersoccupation: 'Housewife',
    db_mothersannualincome: '',
    db_parentsmobile: '',

    // Communication
    db_permenantaddress: '',
    db_landmark: '',
    db_pincode: '',
    db_telephonenumber: '-',
    db_mobilenumber: '',
    db_email: '',

    // Previous exam
    db_lastschool: '',
    db_lastudiseno: '',
    student_id: '',
    db_lastclass: '10th',
    db_lastboard: 'SSC',
    db_lastyear: String(new Date().getFullYear()),
    db_lastseatno: '',
    db_lastmarks: '',
    db_lastoutof: '500',
    db_lastpercentage: '',
    db_lastappeared: 'March ' + new Date().getFullYear(),

    // Subjects - Science
    geog_maths_biology_m: 'Mathematics',
    geog_maths_biology_g: '',
    geog_maths_biology_b: 'Biology',
    marathi_hindi: 'Hindi',

    // Subjects - Commerce
    commerce_sub: 'Mathematics',
    commerce_h_m: 'hindi',

    // Checklist
    birth_certificate: 'Photocopy',
    school_leaving: 'Photocopy',
    progress_report: 'Photocopy',
    passport_photo_student: 'Original',
    nationality_domicile: 'Photocopy',
    caste_certificate: 'Photocopy',
    aadhar_card: 'Photocopy',
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => {
      const updated = { ...prev, [name]: value };

      // Calculate percentage if marks or out of changed
      if (name === 'db_lastmarks' || name === 'db_lastoutof') {
        const marks = parseFloat(name === 'db_lastmarks' ? value : prev.db_lastmarks);
        const outOf = parseFloat(name === 'db_lastoutof' ? value : prev.db_lastoutof);
        if (!isNaN(marks) && !isNaN(outOf) && outOf > 0) {
          updated.db_lastpercentage = ((marks / outOf) * 100).toFixed(2);
        }
      }
      return updated;
    });
  };

  const handleStreamChange = (e) => {
    const val = e.target.value;
    setStream(val);
    setFormData((prev) => ({ ...prev, db_stream: val }));
  };

  const handleScienceCheckbox = (field, val) => {
    setFormData((prev) => {
      const currentVal = prev[field];
      const newVal = currentVal ? '' : val;

      // Ensure at most 2 are picked among M, G, B
      const m = field === 'geog_maths_biology_m' ? newVal : prev.geog_maths_biology_m;
      const g = field === 'geog_maths_biology_g' ? newVal : prev.geog_maths_biology_g;
      const b = field === 'geog_maths_biology_b' ? newVal : prev.geog_maths_biology_b;
      const selectedCount = [m, g, b].filter(Boolean).length;

      if (selectedCount > 2) {
        alert('You can select a maximum of 2 optional subjects (Maths, Geography, Biology).');
        return prev;
      }

      return {
        ...prev,
        [field]: newVal,
      };
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setErrorMessage('');

    try {
      const res = await api.post('/online-applications/public/submit-college', formData);
      if (res.data?.success) {
        setSuccessAppId(res.data.applicationId || 'Submitted');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    } catch (err) {
      setErrorMessage(
        err.response?.data?.message || 'Failed to submit admission application. Please check your entries.'
      );
    } finally {
      setSubmitting(false);
    }
  };

  if (successAppId) {
    return (
      <div className="min-h-screen bg-slate-50 flex items-center justify-center p-4 font-sans text-slate-800">
        <div className="bg-white rounded-3xl shadow-xl max-w-lg w-full p-8 text-center border border-slate-100">
          <div className="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-200">
            <FiCheckCircle size={32} />
          </div>
          <h2 className="text-xl font-bold text-slate-900 mb-1">
            Application Submitted Successfully!
          </h2>
          <p className="text-xs text-slate-500 mb-4">
            Thank you for applying to Olive English Junior College, Dhule.
          </p>
          <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 font-mono text-sm mb-6">
            <span className="text-xs font-bold text-slate-400 block uppercase">
              Application ID
            </span>
            <span className="text-xl font-extrabold text-[#1976d2]">{successAppId}</span>
          </div>
          <p className="text-xs text-slate-500 leading-relaxed mb-6">
            Please save this Application ID for future reference and verification during document submission at the college campus.
          </p>
          <button
            onClick={() => {
              setSuccessAppId(null);
              window.location.reload();
            }}
            className="px-6 py-2.5 bg-[#1976d2] hover:bg-[#47678a] text-white font-bold rounded-xl text-xs transition shadow-sm cursor-pointer"
          >
            Submit Another Application
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-[#f4f7f6] text-slate-800 font-sans pb-16">
      {/* College Banner Header */}
      <div className="bg-white border-b border-slate-200 shadow-sm">
        <div className="max-w-4xl mx-auto px-4 py-6 text-center">
          <span className="text-xs font-semibold text-slate-500 tracking-wider uppercase">
            Uttar Maharashtra Educational Welfare Trust Dhule
          </span>
          <h1 className="text-2xl font-black text-[#1976d2] tracking-tight mt-1">
            Olive English School & Jr. College, Dhule
          </h1>
          <p className="text-xs text-slate-500 mt-0.5">
            National Highway No. 3, Near Traffic Police Station, Dhule
          </p>
          <div className="mt-4 inline-block px-5 py-1.5 rounded-full bg-[#1976d2] text-white font-bold text-xs uppercase tracking-wider shadow-sm">
            11th & 12th Admission Application Form
          </div>
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 mt-8">
        {errorMessage && (
          <div className="p-4 rounded-xl mb-6 bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 text-xs font-semibold">
            <FiAlertCircle className="shrink-0 mt-0.5 text-rose-600" size={18} />
            <span>{errorMessage}</span>
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Section 1: Academic Stream & Class */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="flex items-center gap-2 border-b border-slate-100 pb-3">
              <FiBookOpen className="text-[#1976d2]" size={18} />
              <h3 className="font-bold text-slate-900 text-sm">
                1. Admission Preferences
              </h3>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Branch <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_branch"
                  value={formData.db_branch}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="Olive Junior College">Olive Junior College</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Stream <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_stream"
                  value={stream}
                  onChange={handleStreamChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-[#1976d2]"
                >
                  <option value="Science">Science</option>
                  <option value="Commerce">Commerce</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Admission Type <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_admissiontype"
                  value={formData.db_admissiontype}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="New Admission">New Admission</option>
                  <option value="Re-Admission">Re-Admission</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Class <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_class"
                  value={formData.db_class}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="11th">11th</option>
                  <option value="12th">12th</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Division <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_division"
                  value={formData.db_division}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Medium <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_medium"
                  value={formData.db_medium}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="English">English</option>
                </select>
              </div>
            </div>
          </div>

          {/* Section 2: Student Details */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="flex items-center gap-2 border-b border-slate-100 pb-3">
              <FiUser className="text-[#1976d2]" size={18} />
              <h3 className="font-bold text-slate-900 text-sm">
                2. Student's Information
              </h3>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Firstname <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="firstname"
                  value={formData.firstname}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Middlename <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="Middlename"
                  value={formData.Middlename}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Lastname <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="lastname"
                  value={formData.lastname}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Date of Birth <span className="text-rose-500">*</span>
                </label>
                <input
                  type="date"
                  required
                  name="date"
                  value={formData.date}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Nationality <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_nationality"
                  value={formData.db_nationality}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="Indian">Indian</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Place of Birth <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_place"
                  value={formData.db_place}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Gender <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_gender"
                  value={formData.db_gender}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Mother Tongue <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_mothertounge"
                  value={formData.db_mothertounge}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="Urdu">Urdu</option>
                  <option value="Hindi">Hindi</option>
                  <option value="Marathi">Marathi</option>
                  <option value="English">English</option>
                  <option value="Gujarati">Gujarati</option>
                </select>
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Religion <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_religion"
                  value={formData.db_religion}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="Islam">Islam</option>
                  <option value="Hindu">Hindu</option>
                  <option value="Christian">Christian</option>
                  <option value="Sikh">Sikh</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Caste <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_caste"
                  value={formData.db_caste}
                  onChange={handleChange}
                  placeholder="Enter caste"
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Student Aadhar No.
                </label>
                <input
                  type="text"
                  maxLength={12}
                  name="db_adharnumber"
                  value={formData.db_adharnumber}
                  onChange={handleChange}
                  placeholder="12 digit Aadhaar"
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>
            </div>
          </div>

          {/* Section 3: Parent's Information */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="flex items-center gap-2 border-b border-slate-100 pb-3">
              <FiUser className="text-[#1976d2]" size={18} />
              <h3 className="font-bold text-slate-900 text-sm">
                3. Parent's Information
              </h3>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Father's First Name <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_fatherfirstname"
                  value={formData.db_fatherfirstname}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Father's Middle Name <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_fathermiddlename"
                  value={formData.db_fathermiddlename}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Father's Last Name <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_fatherlastname"
                  value={formData.db_fatherlastname}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Father's Aadhar No. <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  maxLength={12}
                  name="db_fatheradharnumber"
                  value={formData.db_fatheradharnumber}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Father's Qualification <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_fatherqualification"
                  value={formData.db_fatherqualification}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Father's Occupation <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_fatheroccupation"
                  value={formData.db_fatheroccupation}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="Government">Government Employee</option>
                  <option value="Semi Government">Semi Government Employee</option>
                  <option value="Private Company Job">Private Company Job</option>
                  <option value="Business">Business</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Father's Annual Income <span className="text-rose-500">*</span>
                </label>
                <select
                  name="db_fathersannualincome"
                  value={formData.db_fathersannualincome}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="1 Lakh to 2 Lakh">1 Lakh to 2 Lakh</option>
                  <option value="2 Lakh to 4 Lakh">2 Lakh to 4 Lakh</option>
                  <option value="4 Lakh to 6 Lakh">4 Lakh to 6 Lakh</option>
                  <option value="6 Lakh to 8 Lakh">6 Lakh to 8 Lakh</option>
                  <option value="8 Lakh to 10 Lakh">8 Lakh to 10 Lakh</option>
                  <option value="10 Lakh to 12 Lakh">10 Lakh to 12 Lakh</option>
                  <option value="12 Lakh & Above">12 Lakh & Above</option>
                </select>
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-2">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Mother's Name <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_mothersname"
                  value={formData.db_mothersname}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Mother's Qualification
                </label>
                <input
                  type="text"
                  name="db_mothersqualificaiton"
                  value={formData.db_mothersqualificaiton}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Mother's Occupation
                </label>
                <select
                  name="db_mothersoccupation"
                  value={formData.db_mothersoccupation}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                >
                  <option value="Housewife">Housewife</option>
                  <option value="Government">Government Employee</option>
                  <option value="Private Company Job">Private Company Job</option>
                  <option value="Business">Business</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Parent's Mobile No. <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  maxLength={10}
                  name="db_parentsmobile"
                  value={formData.db_parentsmobile}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>
            </div>
          </div>

          {/* Section 4: Communication Details */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="flex items-center gap-2 border-b border-slate-100 pb-3">
              <FiHome className="text-[#1976d2]" size={18} />
              <h3 className="font-bold text-slate-900 text-sm">
                4. Communication Details
              </h3>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Permanent Address <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_permenantaddress"
                  value={formData.db_permenantaddress}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Nearest Landmark <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_landmark"
                  value={formData.db_landmark}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Pincode <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_pincode"
                  value={formData.db_pincode}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Telephone No.
                </label>
                <input
                  type="text"
                  name="db_telephonenumber"
                  value={formData.db_telephonenumber}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Student Mobile No. <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  maxLength={10}
                  name="db_mobilenumber"
                  value={formData.db_mobilenumber}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Email ID
                </label>
                <input
                  type="email"
                  name="db_email"
                  value={formData.db_email}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>
            </div>
          </div>

          {/* Section 5: Previous Examination History */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="flex items-center gap-2 border-b border-slate-100 pb-3">
              <FiAward className="text-[#1976d2]" size={18} />
              <h3 className="font-bold text-slate-900 text-sm">
                5. Previous Examination History
              </h3>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Previous School Name & Phone <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  name="db_lastschool"
                  value={formData.db_lastschool}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  UDISE No.
                </label>
                <input
                  type="text"
                  name="db_lastudiseno"
                  value={formData.db_lastudiseno}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-600 mb-1">
                  Student ID (from school)
                </label>
                <input
                  type="text"
                  name="student_id"
                  value={formData.student_id}
                  onChange={handleChange}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium"
                />
              </div>
            </div>

            {/* Exam Table */}
            <div className="overflow-x-auto border border-slate-200 rounded-xl mt-3">
              <table className="w-full text-xs text-left">
                <thead className="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                  <tr>
                    <th className="p-2.5">Exam</th>
                    <th className="p-2.5">Board</th>
                    <th className="p-2.5">Passing Year</th>
                    <th className="p-2.5">Seat No.</th>
                    <th className="p-2.5">Marks</th>
                    <th className="p-2.5">Out Of</th>
                    <th className="p-2.5">Percentage</th>
                    <th className="p-2.5">Appeared</th>
                  </tr>
                </thead>
                <tbody>
                  <tr className="bg-white font-medium">
                    <td className="p-2">
                      <input
                        type="text"
                        name="db_lastclass"
                        value={formData.db_lastclass}
                        onChange={handleChange}
                        className="w-20 px-2 py-1 border border-slate-200 rounded text-xs"
                      />
                    </td>
                    <td className="p-2">
                      <input
                        type="text"
                        name="db_lastboard"
                        value={formData.db_lastboard}
                        onChange={handleChange}
                        className="w-20 px-2 py-1 border border-slate-200 rounded text-xs"
                      />
                    </td>
                    <td className="p-2">
                      <input
                        type="text"
                        name="db_lastyear"
                        value={formData.db_lastyear}
                        onChange={handleChange}
                        className="w-16 px-2 py-1 border border-slate-200 rounded text-xs"
                      />
                    </td>
                    <td className="p-2">
                      <input
                        type="text"
                        name="db_lastseatno"
                        value={formData.db_lastseatno}
                        onChange={handleChange}
                        className="w-24 px-2 py-1 border border-slate-200 rounded text-xs"
                      />
                    </td>
                    <td className="p-2">
                      <input
                        type="number"
                        name="db_lastmarks"
                        value={formData.db_lastmarks}
                        onChange={handleChange}
                        placeholder="Marks"
                        className="w-20 px-2 py-1 border border-slate-200 rounded text-xs font-bold text-slate-800"
                      />
                    </td>
                    <td className="p-2">
                      <input
                        type="number"
                        name="db_lastoutof"
                        value={formData.db_lastoutof}
                        onChange={handleChange}
                        className="w-20 px-2 py-1 border border-slate-200 rounded text-xs"
                      />
                    </td>
                    <td className="p-2">
                      <input
                        type="text"
                        readOnly
                        value={formData.db_lastpercentage ? `${formData.db_lastpercentage}%` : '-'}
                        className="w-20 px-2 py-1 bg-slate-50 font-bold text-[#1565c0] border border-slate-200 rounded text-xs"
                      />
                    </td>
                    <td className="p-2">
                      <input
                        type="text"
                        name="db_lastappeared"
                        value={formData.db_lastappeared}
                        onChange={handleChange}
                        className="w-24 px-2 py-1 border border-slate-200 rounded text-xs"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          {/* Section 6: Subject Selection */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="flex items-center gap-2 border-b border-slate-100 pb-3">
              <FiBookOpen className="text-[#1976d2]" size={18} />
              <h3 className="font-bold text-slate-900 text-sm">
                6. Subjects Offered for {stream} Stream
              </h3>
            </div>

            {stream === 'Science' ? (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                  <h4 className="font-bold text-xs text-slate-700 uppercase">
                    Compulsory Subjects (100 Marks / Grade):
                  </h4>
                  <ul className="text-xs space-y-1 text-slate-600 font-medium">
                    <li>1. English (100)</li>
                    <li>2. Physics (100)</li>
                    <li>3. Chemistry (100)</li>
                    <li>4. Environment Education (Grade)</li>
                    <li>5. Health and Physical Education (Grade)</li>
                  </ul>
                </div>

                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                  <h4 className="font-bold text-xs text-slate-700 uppercase">
                    Optional Subjects (Select up to 2):
                  </h4>
                  <div className="space-y-1.5 text-xs font-semibold">
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={Boolean(formData.geog_maths_biology_m)}
                        onChange={() => handleScienceCheckbox('geog_maths_biology_m', 'Mathematics')}
                        className="rounded text-[#1976d2]"
                      />
                      <span>Mathematics</span>
                    </label>
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={Boolean(formData.geog_maths_biology_g)}
                        onChange={() => handleScienceCheckbox('geog_maths_biology_g', 'Geography')}
                        className="rounded text-[#1976d2]"
                      />
                      <span>Geography</span>
                    </label>
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={Boolean(formData.geog_maths_biology_b)}
                        onChange={() => handleScienceCheckbox('geog_maths_biology_b', 'Biology')}
                        className="rounded text-[#1976d2]"
                      />
                      <span>Biology</span>
                    </label>
                  </div>

                  <h4 className="font-bold text-xs text-slate-700 uppercase pt-2">
                    Language (Choose 1):
                  </h4>
                  <div className="flex gap-4 text-xs font-semibold">
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="radio"
                        name="marathi_hindi"
                        value="Hindi"
                        checked={formData.marathi_hindi === 'Hindi'}
                        onChange={handleChange}
                      />
                      <span>Hindi</span>
                    </label>
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="radio"
                        name="marathi_hindi"
                        value="marathi"
                        checked={formData.marathi_hindi === 'marathi'}
                        onChange={handleChange}
                      />
                      <span>Marathi</span>
                    </label>
                  </div>
                </div>
              </div>
            ) : (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                  <h4 className="font-bold text-xs text-slate-700 uppercase">
                    Compulsory Subjects (100 Marks / Grade):
                  </h4>
                  <ul className="text-xs space-y-1 text-slate-600 font-medium">
                    <li>1. English (100)</li>
                    <li>2. Book-Keeping and Accountancy (100)</li>
                    <li>3. Organization of Commerce & Management (100)</li>
                    <li>4. Economics (100)</li>
                    <li>5. Environment Education (Grade)</li>
                    <li>6. Health and Physical Education (Grade)</li>
                  </ul>
                </div>

                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                  <h4 className="font-bold text-xs text-slate-700 uppercase">
                    Option - 1 (Choose 1):
                  </h4>
                  <div className="flex gap-4 text-xs font-semibold">
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="radio"
                        name="commerce_sub"
                        value="Mathematics"
                        checked={formData.commerce_sub === 'Mathematics'}
                        onChange={handleChange}
                      />
                      <span>Mathematics</span>
                    </label>
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="radio"
                        name="commerce_sub"
                        value="Geography"
                        checked={formData.commerce_sub === 'Geography'}
                        onChange={handleChange}
                      />
                      <span>Geography</span>
                    </label>
                  </div>

                  <h4 className="font-bold text-xs text-slate-700 uppercase pt-2">
                    Option - 2 Language (Choose 1):
                  </h4>
                  <div className="flex gap-4 text-xs font-semibold">
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="radio"
                        name="commerce_h_m"
                        value="hindi"
                        checked={formData.commerce_h_m === 'hindi'}
                        onChange={handleChange}
                      />
                      <span>Hindi</span>
                    </label>
                    <label className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="radio"
                        name="commerce_h_m"
                        value="marathi"
                        checked={formData.commerce_h_m === 'marathi'}
                        onChange={handleChange}
                      />
                      <span>Marathi</span>
                    </label>
                  </div>
                </div>
              </div>
            )}
          </div>

          {/* Section 7: Enclosure Checklist */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div className="flex items-center gap-2 border-b border-slate-100 pb-3">
              <FiFileText className="text-[#1976d2]" size={18} />
              <h3 className="font-bold text-slate-900 text-sm">
                7. Document Enclosure Checklist
              </h3>
            </div>
            <p className="text-[11px] text-slate-400">
              Please declare which documents you will be submitting during campus verification:
            </p>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-semibold">
              {[
                { label: 'Birth Certificate', key: 'birth_certificate' },
                { label: 'School Leaving Certificate', key: 'school_leaving' },
                { label: 'Progress Report Card / Marks Sheet', key: 'progress_report' },
                { label: 'Passport Photo Student', key: 'passport_photo_student' },
                { label: 'Nationality / Domicile Certificate', key: 'nationality_domicile' },
                { label: 'Caste Certificate', key: 'caste_certificate' },
                { label: 'Aadhar Card', key: 'aadhar_card' },
              ].map((doc) => (
                <div
                  key={doc.key}
                  className="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between"
                >
                  <span className="text-slate-700">{doc.label}</span>
                  <div className="flex gap-2">
                    <label className="flex items-center gap-1 text-[11px] font-normal cursor-pointer">
                      <input
                        type="radio"
                        name={doc.key}
                        value="Photocopy"
                        checked={formData[doc.key] === 'Photocopy'}
                        onChange={handleChange}
                      />
                      <span>Photocopy</span>
                    </label>
                    <label className="flex items-center gap-1 text-[11px] font-normal cursor-pointer">
                      <input
                        type="radio"
                        name={doc.key}
                        value="Original"
                        checked={formData[doc.key] === 'Original'}
                        onChange={handleChange}
                      />
                      <span>Original</span>
                    </label>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Submit Action */}
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center">
            <button
              type="submit"
              disabled={submitting}
              className="px-8 py-3 bg-[#1976d2] hover:bg-[#47678a] text-white font-bold rounded-xl text-sm transition-all shadow-md hover:shadow-lg disabled:opacity-50 flex items-center justify-center gap-2 mx-auto cursor-pointer"
            >
              {submitting ? 'Submitting Form...' : 'Submit Admission Application'}
              <FiSend size={16} />
            </button>
            <p className="text-[11px] text-slate-400 mt-2">
              By submitting this form, you certify all information entered is true and accurate.
            </p>
          </div>
        </form>
      </div>
    </div>
  );
}
