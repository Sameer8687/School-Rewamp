/**
 * Leaving Certificate (LC) & Bonafide Management Page.
 * Direct conversion of CI3 `Report::report_stats_lc()` and `all_reports/lc.php`.
 */
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  fetchStudentOptions,
  fetchLcRecords,
  issueLeavingCertificate,
} from '../../services/studentService.js';
import {
  FiFileText,
  FiArrowLeft,
  FiPrinter,
  FiSearch,
  FiCheckCircle,
  FiX,
  FiSave,
} from 'react-icons/fi';

export default function StudentLcPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [options, setOptions] = useState({ academicYears: [], classes: [] });

  // Filters
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedStandard, setSelectedStandard] = useState('');
  const [records, setRecords] = useState([]);

  // Modal State
  const [modalStudent, setModalStudent] = useState(null);
  const [issueForm, setIssueForm] = useState({
    leaving_date: new Date().toISOString().slice(0, 10),
    leaving_reason: 'Parent request / Relocation',
    conduct: 'Good',
    progress: 'Satisfactory',
    lc_date: new Date().toISOString().slice(0, 10),
    remark: '',
  });
  const [issuing, setIssuing] = useState(false);

  // Print Preview Student
  const [printStudent, setPrintStudent] = useState(null);

  // Load dropdown options
  useEffect(() => {
    async function init() {
      try {
        const res = await fetchStudentOptions();
        if (res.success && res.data) {
          setOptions(res.data);
          const curId = String(res.data.currentAcdmId || res.data.academicYears[0]?.stvacdm_id || '');
          setSelectedYear(curId);
          setSelectedStandard(res.data.classes?.[0] || '');
        }
      } catch (err) {
        console.error('Failed to init LC options:', err);
      }
    }
    init();
  }, []);

  // Load records
  const loadRecords = async () => {
    if (!selectedYear) return;
    try {
      setLoading(true);
      const res = await fetchLcRecords({
        acdm_year: selectedYear,
        standard: selectedStandard,
      });
      if (res.success && res.data) {
        setRecords(res.data);
      }
    } catch (err) {
      console.error('Failed to load LC records:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (selectedYear && selectedStandard) {
      loadRecords();
    }
  }, [selectedYear, selectedStandard]);

  // Open Issue Modal
  const handleOpenIssue = (st) => {
    setModalStudent(st);
    setIssueForm({
      leaving_date: st.leaving_date ? st.leaving_date.slice(0, 10) : new Date().toISOString().slice(0, 10),
      leaving_reason: st.leaving_reason || 'Parent request / Relocation',
      conduct: st.conduct || 'Good',
      progress: st.progress || 'Satisfactory',
      lc_date: st.lc_date ? st.lc_date.slice(0, 10) : new Date().toISOString().slice(0, 10),
      remark: st.remark || '',
    });
  };

  // Submit Issue LC
  const handleIssueSubmit = async (e) => {
    e.preventDefault();
    if (!modalStudent) return;
    try {
      setIssuing(true);
      const res = await issueLeavingCertificate(modalStudent.id, issueForm);
      if (res.success) {
        alert('Leaving Certificate Issued Successfully!');
        setModalStudent(null);
        loadRecords();
      }
    } catch (err) {
      alert('Failed to issue LC: ' + (err.response?.data?.message || err.message));
    } finally {
      setIssuing(false);
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
                  <FiFileText className="text-slate-700" />
                  Leaving Certificate (LC) Management
                </h1>
                <p className="text-xs text-slate-500">
                  Issue, update and generate Leaving Certificates for students
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

          {/* Filter Bar */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-wrap gap-4 text-xs items-end no-print">
            <div>
              <label className="block font-semibold text-slate-700 mb-1">Academic Year</label>
              <select
                value={selectedYear}
                onChange={(e) => setSelectedYear(e.target.value)}
                className="border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2]"
              >
                {options.academicYears.map((yr) => (
                  <option key={yr.stvacdm_id} value={yr.stvacdm_id}>
                    {yr.stvacdm_year}
                  </option>
                ))}
              </select>
            </div>

            <div>
              <label className="block font-semibold text-slate-700 mb-1">Standard</label>
              <select
                value={selectedStandard}
                onChange={(e) => setSelectedStandard(e.target.value)}
                className="border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2]"
              >
                <option value="ALL">All Standards</option>
                {options.classes.map((cls) => (
                  <option key={cls} value={cls}>
                    {cls}
                  </option>
                ))}
              </select>
            </div>

            <button
              onClick={loadRecords}
              className="px-4 py-2 bg-[#1976d2] text-white rounded-lg font-bold shadow hover:bg-[#1565c0] flex items-center gap-1.5 cursor-pointer"
            >
              <FiSearch size={14} />
              <span>Filter</span>
            </button>
          </div>

          {/* Records Table */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden no-print">
            {loading ? (
              <div className="py-16 text-center text-slate-500 text-xs">
                <div className="inline-block animate-spin w-6 h-6 border-2 border-slate-300 border-t-slate-700 rounded-full mb-2"></div>
                <div>Loading candidates...</div>
              </div>
            ) : records.length === 0 ? (
              <div className="py-16 text-center text-slate-400 text-xs">
                No students found for LC issue in selected criteria.
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-12 text-center">#</th>
                      <th className="py-2.5 px-3">GR No</th>
                      <th className="py-2.5 px-3">Student Full Name</th>
                      <th className="py-2.5 px-3">Standard</th>
                      <th className="py-2.5 px-3">LC Status</th>
                      <th className="py-2.5 px-3">Leaving Date</th>
                      <th className="py-2.5 px-3 text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {records.map((st, i) => (
                      <tr key={st.id} className="hover:bg-slate-50 transition-colors">
                        <td className="py-2 px-3 text-center text-slate-400 font-mono text-[11px]">
                          {i + 1}
                        </td>
                        <td className="py-2 px-3 font-semibold text-slate-800">
                          {st.spriden_gr_number || '—'}
                        </td>
                        <td className="py-2 px-3 font-semibold text-slate-800">
                          {st.name}
                        </td>
                        <td className="py-2 px-3 text-slate-600">
                          {st.STVCLAS_standard} {st.STVCLAS_class ? `- ${st.STVCLAS_class}` : ''}
                        </td>
                        <td className="py-2 px-3">
                          {st.lc_status === 'Issued' ? (
                            <span className="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                              Issued
                            </span>
                          ) : (
                            <span className="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                              Pending
                            </span>
                          )}
                        </td>
                        <td className="py-2 px-3 text-slate-600">
                          {st.leaving_date ? st.leaving_date.slice(0, 10) : '—'}
                        </td>
                        <td className="py-2 px-3 text-center">
                          <div className="flex items-center justify-center gap-2">
                            <button
                              onClick={() => handleOpenIssue(st)}
                              className="px-2.5 py-1 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded text-[11px] font-semibold cursor-pointer"
                            >
                              {st.lc_status === 'Issued' ? 'Edit LC' : 'Issue LC'}
                            </button>
                            {st.lc_status === 'Issued' && (
                              <button
                                onClick={() => setPrintStudent(st)}
                                className="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-[11px] font-semibold flex items-center gap-1"
                              >
                                <FiPrinter size={12} />
                                <span>Certificate</span>
                              </button>
                            )}
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>

          {/* Printable Leaving Certificate View */}
          {printStudent && (
            <div className="bg-white p-8 rounded-xl border-2 border-slate-800 space-y-6 shadow-lg text-slate-900">
              <div className="flex justify-between items-center no-print pb-4 border-b">
                <span className="font-bold text-xs uppercase text-slate-500">Certificate Print Preview</span>
                <div className="flex gap-2">
                  <button
                    onClick={() => window.print()}
                    className="px-3 py-1.5 bg-[#1976d2] text-white rounded text-xs font-bold flex items-center gap-1"
                  >
                    <FiPrinter size={14} />
                    <span>Print Document</span>
                  </button>
                  <button
                    onClick={() => setPrintStudent(null)}
                    className="px-3 py-1.5 bg-slate-200 text-slate-800 rounded text-xs font-bold"
                  >
                    Close
                  </button>
                </div>
              </div>

              {/* Certificate Layout */}
              <div className="text-center space-y-2 border-b-2 border-slate-900 pb-4">
                <h2 className="text-2xl font-black tracking-tight">OLIVE ENGLISH HIGH SCHOOL</h2>
                <p className="text-xs">Kalyan Road, Bhiwandi, Dist. Thane | Maharashtra</p>
                <h3 className="text-lg font-bold underline uppercase tracking-widest pt-2">
                  SCHOOL LEAVING CERTIFICATE
                </h3>
              </div>

              <div className="grid grid-cols-2 gap-4 text-xs leading-relaxed">
                <div><strong>General Register No (GR):</strong> {printStudent.spriden_gr_number || 'N/A'}</div>
                <div><strong>Student ID:</strong> {printStudent.SPRIDEN_student_id}</div>
                <div><strong>Full Name of Pupil:</strong> {printStudent.name}</div>
                <div><strong>Gender:</strong> {printStudent.SPRIDEN_gender}</div>
                <div><strong>Date of Birth:</strong> {printStudent.SPRIDEN_birthdate?.slice(0, 10)}</div>
                <div><strong>DOB in Words:</strong> {printStudent.birthdate_words || '—'}</div>
                <div><strong>Standard in which Studying:</strong> {printStudent.STVCLAS_standard}</div>
                <div><strong>Date of Leaving School:</strong> {printStudent.leaving_date?.slice(0, 10)}</div>
                <div><strong>Reason for Leaving:</strong> {printStudent.leaving_reason || 'Parent Request'}</div>
                <div><strong>Conduct:</strong> {printStudent.conduct || 'Good'}</div>
                <div><strong>Progress:</strong> {printStudent.progress || 'Satisfactory'}</div>
                <div><strong>Date of Issue:</strong> {printStudent.lc_date?.slice(0, 10) || new Date().toISOString().slice(0, 10)}</div>
              </div>

              <div className="pt-16 grid grid-cols-3 text-center text-xs font-bold">
                <div>Class Teacher</div>
                <div>Clerk</div>
                <div>Head Master / Principal</div>
              </div>
            </div>
          )}

        </div>
      </main>

      {/* ─── Issue LC Modal ─── */}
      {modalStudent && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-xl shadow-xl max-w-lg w-full p-6 animate-scale-in">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                <FiFileText className="text-[#1976d2]" />
                Issue School Leaving Certificate
              </h3>
              <button onClick={() => setModalStudent(null)} className="text-slate-400 hover:text-slate-600">
                <FiX size={16} />
              </button>
            </div>

            <form onSubmit={handleIssueSubmit} className="mt-4 space-y-4 text-xs">
              <div className="p-3 bg-slate-50 rounded-lg text-slate-700">
                Pupil: <strong>{modalStudent.name}</strong> (GR: {modalStudent.spriden_gr_number || 'N/A'}, Std: {modalStudent.STVCLAS_standard})
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Leaving Date *</label>
                  <input
                    type="date"
                    value={issueForm.leaving_date}
                    onChange={(e) => setIssueForm({ ...issueForm, leaving_date: e.target.value })}
                    className="w-full border border-slate-300 rounded-lg p-2"
                    required
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Certificate Date *</label>
                  <input
                    type="date"
                    value={issueForm.lc_date}
                    onChange={(e) => setIssueForm({ ...issueForm, lc_date: e.target.value })}
                    className="w-full border border-slate-300 rounded-lg p-2"
                    required
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Conduct</label>
                  <select
                    value={issueForm.conduct}
                    onChange={(e) => setIssueForm({ ...issueForm, conduct: e.target.value })}
                    className="w-full border border-slate-300 rounded-lg p-2"
                  >
                    <option value="Good">Good</option>
                    <option value="Very Good">Very Good</option>
                    <option value="Excellent">Excellent</option>
                    <option value="Satisfactory">Satisfactory</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Progress</label>
                  <select
                    value={issueForm.progress}
                    onChange={(e) => setIssueForm({ ...issueForm, progress: e.target.value })}
                    className="w-full border border-slate-300 rounded-lg p-2"
                  >
                    <option value="Satisfactory">Satisfactory</option>
                    <option value="Good">Good</option>
                    <option value="Very Good">Very Good</option>
                  </select>
                </div>

                <div className="col-span-2">
                  <label className="block font-semibold text-slate-700 mb-1">Reason for Leaving School *</label>
                  <input
                    type="text"
                    value={issueForm.leaving_reason}
                    onChange={(e) => setIssueForm({ ...issueForm, leaving_reason: e.target.value })}
                    placeholder="e.g. Parents Transfer, Relocation..."
                    className="w-full border border-slate-300 rounded-lg p-2"
                    required
                  />
                </div>

                <div className="col-span-2">
                  <label className="block font-semibold text-slate-700 mb-1">Additional Remarks</label>
                  <input
                    type="text"
                    value={issueForm.remark}
                    onChange={(e) => setIssueForm({ ...issueForm, remark: e.target.value })}
                    className="w-full border border-slate-300 rounded-lg p-2"
                  />
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalStudent(null)}
                  className="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={issuing}
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-lg font-bold shadow flex items-center gap-1.5 cursor-pointer"
                >
                  <FiSave size={14} />
                  <span>{issuing ? 'Saving...' : 'Issue Leaving Certificate'}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
