/**
 * Student Re-Admission Page — 100% CI3 Functional Parity.
 * Direct conversion of `Student/readmission.php`.
 */
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  fetchStudentOptions,
  fetchStudents,
  fetchReadmissionDetails,
  submitReadmission,
} from '../../services/studentService.js';
import {
  FiRefreshCw,
  FiSearch,
  FiArrowLeft,
  FiSave,
  FiUserCheck,
  FiClock,
  FiDollarSign,
  FiBook,
} from 'react-icons/fi';

export default function StudentReadmissionPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [options, setOptions] = useState({ academicYears: [], classes: [], divisions: [] });

  // Student search
  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState([]);
  const [searching, setSearching] = useState(false);

  // Selected student data
  const [selectedStudentId, setSelectedStudentId] = useState(null);
  const [studentDetails, setStudentDetails] = useState(null);

  // Re-admission target form
  const [targetAcdm, setTargetAcdm] = useState('');
  const [targetStandard, setTargetStandard] = useState('');
  const [targetDivision, setTargetDivision] = useState('A');
  const [targetRollNo, setTargetRollNo] = useState('0');
  const [note, setNote] = useState('');

  // Load dropdown options
  useEffect(() => {
    async function init() {
      try {
        const res = await fetchStudentOptions();
        if (res.success && res.data) {
          setOptions(res.data);
          const years = res.data.academicYears || [];
          setTargetAcdm(String(res.data.currentAcdmId || years[0]?.stvacdm_id || ''));
          setTargetStandard(res.data.classes?.[0] || '1');
        }
      } catch (err) {
        console.error('Failed to init options:', err);
      }
    }
    init();
  }, []);

  // Search students
  const handleSearch = async (e) => {
    e.preventDefault();
    if (!searchQuery.trim()) return;
    try {
      setSearching(true);
      const res = await fetchStudents({
        status: 'ALL',
        search: searchQuery,
      });
      if (res.success) {
        setSearchResults(res.data?.students || res.students || (Array.isArray(res.data) ? res.data : []));
      }
    } catch (err) {
      console.error('Search failed:', err);
    } finally {
      setSearching(false);
    }
  };

  // Select student & load history
  const handleSelectStudent = async (st) => {
    try {
      setLoading(true);
      setSelectedStudentId(st.id);
      const res = await fetchReadmissionDetails(st.id, targetAcdm);
      if (res.success && res.data) {
        setStudentDetails(res.data);
        const lastClass = res.data.classes?.[0];
        if (lastClass?.STVCLAS_standard) {
          setTargetStandard(lastClass.STVCLAS_standard);
          setTargetDivision(lastClass.STVCLAS_class || 'A');
          setTargetRollNo(String(lastClass.STVCLAS_rollno || '0'));
        }
      }
    } catch (err) {
      console.error('Failed to load readmission details:', err);
    } finally {
      setLoading(false);
    }
  };

  // Submit re-admission
  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!selectedStudentId) {
      alert('Please search and select a student first.');
      return;
    }
    if (!targetAcdm || !targetStandard || !targetDivision) {
      alert('Target academic year, standard, and division are required.');
      return;
    }

    try {
      setSubmitting(true);
      const res = await submitReadmission({
        studentId: selectedStudentId,
        targetAcdm,
        targetStandard,
        targetDivision,
        rollNo: targetRollNo,
        note,
      });

      if (res.success) {
        alert('Student Re-Admission Completed Successfully!');
        // Refresh student details
        handleSelectStudent({ id: selectedStudentId });
      }
    } catch (err) {
      alert('Re-admission failed: ' + (err.response?.data?.message || err.message));
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#f4f7fa] flex flex-col font-sans">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} isSidebarOpen={sidebarOpen} />

      <main className="flex-1 p-4 md:p-6 transition-all duration-300">
        <div className="max-w-6xl mx-auto space-y-4">

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
                  <FiRefreshCw className="text-[#1976d2]" />
                  Student Re-Admission
                </h1>
                <p className="text-xs text-slate-500">
                  Search existing/returning student, review previous academic/fees, and re-admit into active year
                </p>
              </div>
            </div>

            <Link
              to="/students"
              className="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg"
            >
              Back to Directory
            </Link>
          </div>

          {/* Search Box */}
          <div className="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <h3 className="text-xs font-bold uppercase tracking-wider text-[#1976d2] mb-2">
              1. Search Returning Student
            </h3>
            <form onSubmit={handleSearch} className="flex gap-2">
              <input
                type="text"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Search by student full name, GR number, or Student ID..."
                className="flex-1 text-xs border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2] focus:outline-none"
              />
              <button
                type="submit"
                disabled={searching}
                className="px-5 py-2.5 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow"
              >
                <FiSearch size={14} />
                <span>{searching ? 'Searching...' : 'Search'}</span>
              </button>
            </form>

            {/* Results pills */}
            {searchResults.length > 0 && (
              <div className="mt-3 pt-3 border-t border-slate-100">
                <span className="text-[11px] text-slate-500 font-semibold block mb-2">
                  Search Results ({searchResults.length}): Click a student to load re-admission form:
                </span>
                <div className="flex flex-wrap gap-2 max-h-48 overflow-y-auto">
                  {searchResults.map((st) => (
                    <button
                      key={st.id}
                      type="button"
                      onClick={() => handleSelectStudent(st)}
                      className={`px-3 py-1.5 rounded-lg text-xs font-medium border text-left transition-all ${
                        selectedStudentId === st.id
                          ? 'bg-[#1976d2] text-white border-[#1976d2] shadow'
                          : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'
                      }`}
                    >
                      <span className="font-bold">{st.name || st.SPRIDEN_student_name}</span>{' '}
                      <span className="opacity-80 font-mono text-[11px]">(GR: {st.spriden_gr_number || 'N/A'} | {st.STVCLAS_standard})</span>
                    </button>
                  ))}
                </div>
              </div>
            )}
          </div>

          {/* Student Profile & Re-Admission Form */}
          {loading ? (
            <div className="bg-white rounded-xl p-12 text-center text-slate-500 text-xs">
              <div className="inline-block animate-spin w-6 h-6 border-2 border-slate-300 border-t-[#1976d2] rounded-full mb-2"></div>
              <div>Loading student past records...</div>
            </div>
          ) : studentDetails ? (
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">

              {/* Left Column: Student Past History */}
              <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
                <div className="flex items-center gap-2 pb-2 border-b border-slate-100">
                  <FiUserCheck size={18} className="text-[#1976d2]" />
                  <div>
                    <h3 className="text-sm font-bold text-slate-900">
                      {studentDetails.student.full_name}
                    </h3>
                    <p className="text-[11px] text-slate-500">
                      ID: {studentDetails.student.SPRIDEN_student_id} | GR: {studentDetails.student.spriden_gr_number || 'N/A'}
                    </p>
                  </div>
                </div>

                {/* Academic History */}
                <div>
                  <h4 className="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 flex items-center gap-1.5">
                    <FiBook size={13} />
                    Past Class Allocations
                  </h4>
                  <div className="space-y-1.5 text-xs">
                    {studentDetails.classes?.map((c, i) => (
                      <div key={i} className="p-2 bg-slate-50 border border-slate-100 rounded-lg flex justify-between">
                        <span className="font-semibold text-slate-800">{c.stvacdm_year || `Year #${c.STVCLAS_acdm}`}</span>
                        <span className="text-slate-600">{c.STVCLAS_standard} {c.STVCLAS_class ? `- ${c.STVCLAS_class}` : ''} (Roll: {c.STVCLAS_rollno})</span>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Fees History */}
                <div>
                  <h4 className="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 flex items-center gap-1.5">
                    <FiDollarSign size={13} />
                    Past Fees History
                  </h4>
                  <div className="space-y-1.5 text-xs max-h-48 overflow-y-auto">
                    {studentDetails.fees?.length === 0 ? (
                      <p className="text-slate-400 text-[11px]">No fee records.</p>
                    ) : (
                      studentDetails.fees?.map((f) => (
                        <div key={f.sprfees_pk || f.sprfees_id} className="p-2 bg-slate-50 border border-slate-100 rounded-lg flex justify-between text-[11px]">
                          <div>
                            <span className="font-bold text-slate-800">₹{f.sprfees_amount}</span>{' '}
                            <span className="text-slate-500">({f.stvacdm_year || f.academic_year})</span>
                          </div>
                          <span className="text-slate-400">{f.sprfees_feesdate}</span>
                        </div>
                      ))
                    )}
                  </div>
                </div>
              </div>

              {/* Right Column: Re-Admission Target Assignment */}
              <div className="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                <div className="pb-3 border-b border-slate-100">
                  <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <FiRefreshCw className="text-[#1976d2]" />
                    2. Re-Admission Class & Academic Assignment
                  </h3>
                  <p className="text-xs text-slate-500 mt-0.5">
                    Allocate student into target academic year and class
                  </p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-4 text-xs">
                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                      <label className="block font-semibold text-slate-700 mb-1">
                        Target Academic Year *
                      </label>
                      <select
                        value={targetAcdm}
                        onChange={(e) => setTargetAcdm(e.target.value)}
                        className="w-full border border-slate-300 rounded-lg p-2.5 font-bold bg-[#e3f2fd] text-[#0d47a1] focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
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
                        Standard *
                      </label>
                      <select
                        value={targetStandard}
                        onChange={(e) => setTargetStandard(e.target.value)}
                        className="w-full border border-slate-300 rounded-lg p-2.5 font-bold text-slate-800 focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
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
                        Division *
                      </label>
                      <select
                        value={targetDivision}
                        onChange={(e) => setTargetDivision(e.target.value)}
                        className="w-full border border-slate-300 rounded-lg p-2.5 font-bold text-slate-800 focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                        required
                      >
                        <option value="A">Division A</option>
                        <option value="B">Division B</option>
                        <option value="C">Division C</option>
                      </select>
                    </div>

                    <div>
                      <label className="block font-semibold text-slate-700 mb-1">
                        Roll No
                      </label>
                      <input
                        type="number"
                        value={targetRollNo}
                        onChange={(e) => setTargetRollNo(e.target.value)}
                        className="w-full border border-slate-300 rounded-lg p-2.5 font-bold text-slate-800 focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                      />
                    </div>

                    <div className="sm:col-span-2">
                      <label className="block font-semibold text-slate-700 mb-1">
                        Re-Admission Remarks / Note
                      </label>
                      <input
                        type="text"
                        value={note}
                        onChange={(e) => setNote(e.target.value)}
                        placeholder="e.g. Cleared pending dues, re-admitted in Std 4..."
                        className="w-full border border-slate-300 rounded-lg p-2.5 focus:ring-2 focus:ring-[#1976d2] focus:border-[#1976d2]"
                      />
                    </div>
                  </div>

                  <div className="pt-4 border-t border-slate-100 flex justify-end">
                    <button
                      type="submit"
                      disabled={submitting}
                      className="px-6 py-2.5 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg font-bold shadow flex items-center gap-2"
                    >
                      <FiSave size={15} />
                      <span>{submitting ? 'Processing...' : 'Confirm Re-Admission'}</span>
                    </button>
                  </div>
                </form>
              </div>

            </div>
          ) : (
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center text-slate-400 text-xs">
              Search for a student above to load their records for re-admission.
            </div>
          )}

        </div>
      </main>
    </div>
  );
}
