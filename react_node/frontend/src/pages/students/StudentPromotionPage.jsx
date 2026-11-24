/**
 * Student Promotion Page — 100% Parity with CI3 `Student/promotion.php` & `Student::promoteStudents()`.
 */
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  fetchStudentOptions,
  fetchPromotionCandidates,
  submitPromotions,
} from '../../services/studentService.js';
import {
  FiLayers,
  FiArrowRight,
  FiArrowLeft,
  FiCheckSquare,
  FiSquare,
  FiSearch,
  FiSave,
  FiAlertCircle,
  FiCheckCircle,
} from 'react-icons/fi';

export default function StudentPromotionPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [options, setOptions] = useState({ academicYears: [], classes: [], divisions: [] });

  // Source filters
  const [sourceAcdm, setSourceAcdm] = useState('');
  const [sourceStandard, setSourceStandard] = useState('');
  const [sourceDivision, setSourceDivision] = useState('');

  // Target assignment
  const [targetAcdm, setTargetAcdm] = useState('');
  const [targetStandard, setTargetStandard] = useState('');
  const [targetDivision, setTargetDivision] = useState('A');

  // Students list and selection
  const [students, setStudents] = useState([]);
  const [selectedMap, setSelectedMap] = useState({});
  const [rollNoMap, setRollNoMap] = useState({});

  // Result summary banner
  const [resultSummary, setResultSummary] = useState(null);

  // Load dropdown options
  useEffect(() => {
    async function init() {
      try {
        const res = await fetchStudentOptions();
        if (res.success && res.data) {
          setOptions(res.data);
          const years = res.data.academicYears || [];
          const curId = String(res.data.currentAcdmId || years[0]?.stvacdm_id || '');
          setSourceAcdm(curId);
          if (years.length > 1) {
            setTargetAcdm(String(years[0]?.stvacdm_id));
          } else {
            setTargetAcdm(curId);
          }

          if (res.data.classes?.length > 0) {
            setSourceStandard(res.data.classes[0]);
            setTargetStandard(res.data.classes[1] || res.data.classes[0]);
          }
        }
      } catch (err) {
        console.error('Failed to load promotion options:', err);
      }
    }
    init();
  }, []);

  // Fetch candidates
  const handleFetchCandidates = async (e) => {
    if (e) e.preventDefault();
    if (!sourceAcdm || !sourceStandard) {
      alert('Please select Source Academic Year and Standard.');
      return;
    }

    try {
      setLoading(true);
      setResultSummary(null);
      const res = await fetchPromotionCandidates({
        acdm_year: sourceAcdm,
        standard: sourceStandard,
        division: sourceDivision,
      });

      if (res.success && res.data) {
        setStudents(res.data);
        const sel = {};
        const rolls = {};
        res.data.forEach((st, idx) => {
          sel[st.id] = true;
          rolls[st.id] = idx + 1;
        });
        setSelectedMap(sel);
        setRollNoMap(rolls);
      }
    } catch (err) {
      console.error('Failed to fetch candidates:', err);
    } finally {
      setLoading(false);
    }
  };

  // Toggle single student
  const handleToggleStudent = (id) => {
    setSelectedMap((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  // Toggle all
  const handleToggleAll = () => {
    const allSelected = students.every((st) => selectedMap[st.id]);
    const nextMap = {};
    students.forEach((st) => {
      nextMap[st.id] = !allSelected;
    });
    setSelectedMap(nextMap);
  };

  // Submit Promotion
  const handlePromote = async () => {
    const selectedIds = students.filter((st) => selectedMap[st.id]).map((st) => st.id);
    if (selectedIds.length === 0) {
      alert('Please select at least one student to promote.');
      return;
    }

    if (!targetAcdm || !targetStandard || !targetDivision) {
      alert('Please select target academic year, standard, and division.');
      return;
    }

    if (sourceAcdm === targetAcdm && sourceStandard === targetStandard && sourceDivision === targetDivision) {
      if (!window.confirm('Target class, division, and academic year are identical to source. Proceed?')) {
        return;
      }
    }

    try {
      setSubmitting(true);
      const rollnos = selectedIds.map((id) => rollNoMap[id] || 0);

      const res = await submitPromotions({
        students: selectedIds,
        rollnos,
        targetAcdm,
        targetStandard,
        targetDivision,
      });

      if (res.success) {
        setResultSummary(res.data);
        alert(res.message);
        handleFetchCandidates();
      }
    } catch (err) {
      alert('Promotion failed: ' + (err.response?.data?.message || err.message));
    } finally {
      setSubmitting(false);
    }
  };

  const selectedCount = students.filter((st) => selectedMap[st.id]).length;

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
                  <FiLayers className="text-emerald-600" />
                  Student Class Promotion
                </h1>
                <p className="text-xs text-slate-500">
                  Bulk promote students from current class into next standard & academic year
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2">
              <Link
                to="/students"
                className="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg"
              >
                Back to Directory
              </Link>
            </div>
          </div>

          {/* Result Banner if any */}
          {resultSummary && (
            <div className="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs text-emerald-800">
              <div className="flex items-center gap-2">
                <FiCheckCircle size={18} className="text-emerald-600 shrink-0" />
                <span>
                  <strong>Promotion Complete!</strong> Promoted:{' '}
                  <span className="font-bold">{resultSummary.promoted}</span> students | Skipped (already enrolled):{' '}
                  <span className="font-bold">{resultSummary.skipped}</span>
                </span>
              </div>
            </div>
          )}

          {/* Source & Target Configuration Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">

            {/* Source Class Box */}
            <div className="bg-white p-5 rounded-xl shadow-sm border border-slate-200 space-y-3">
              <div className="flex items-center gap-2 pb-2 border-b border-slate-100 text-xs font-bold text-slate-800 uppercase tracking-wider">
                <span className="w-2.5 h-2.5 rounded-full bg-[#e3f2fd]0"></span>
                <span>1. Source Class (Promote From)</span>
              </div>

              <div className="grid grid-cols-3 gap-3 text-xs">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Academic Year</label>
                  <select
                    value={sourceAcdm}
                    onChange={(e) => setSourceAcdm(e.target.value)}
                    className="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2]"
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
                    value={sourceStandard}
                    onChange={(e) => setSourceStandard(e.target.value)}
                    className="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2]"
                  >
                    {options.classes.map((cls) => (
                      <option key={cls} value={cls}>
                        {cls}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Division</label>
                  <select
                    value={sourceDivision}
                    onChange={(e) => setSourceDivision(e.target.value)}
                    className="w-full border border-slate-300 rounded-lg p-2 bg-slate-50 focus:ring-2 focus:ring-[#1976d2]"
                  >
                    <option value="">All Divisions</option>
                    <option value="A">Division A</option>
                    <option value="B">Division B</option>
                    <option value="C">Division C</option>
                  </select>
                </div>
              </div>

              <button
                type="button"
                onClick={handleFetchCandidates}
                disabled={loading}
                className="w-full mt-2 py-2 bg-[#1976d2] hover:bg-[#436284] text-white rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 shadow"
              >
                <FiSearch size={14} />
                <span>{loading ? 'Fetching Students...' : 'Load Students List'}</span>
              </button>
            </div>

            {/* Target Class Box */}
            <div className="bg-white p-5 rounded-xl shadow-sm border border-slate-200 space-y-3">
              <div className="flex items-center gap-2 pb-2 border-b border-slate-100 text-xs font-bold text-slate-800 uppercase tracking-wider">
                <span className="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span>2. Target Class (Promote Into)</span>
              </div>

              <div className="grid grid-cols-3 gap-3 text-xs">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Target Academic Year</label>
                  <select
                    value={targetAcdm}
                    onChange={(e) => setTargetAcdm(e.target.value)}
                    className="w-full border border-slate-300 rounded-lg p-2 bg-emerald-50/50 font-bold text-emerald-900 border-emerald-300 focus:ring-2 focus:ring-emerald-500"
                  >
                    {options.academicYears.map((yr) => (
                      <option key={yr.stvacdm_id} value={yr.stvacdm_id}>
                        {yr.stvacdm_year}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Target Standard</label>
                  <select
                    value={targetStandard}
                    onChange={(e) => setTargetStandard(e.target.value)}
                    className="w-full border border-slate-300 rounded-lg p-2 font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500"
                  >
                    {options.classes.map((cls) => (
                      <option key={cls} value={cls}>
                        {cls}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Target Division</label>
                  <select
                    value={targetDivision}
                    onChange={(e) => setTargetDivision(e.target.value)}
                    className="w-full border border-slate-300 rounded-lg p-2 font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500"
                  >
                    <option value="A">Division A</option>
                    <option value="B">Division B</option>
                    <option value="C">Division C</option>
                  </select>
                </div>
              </div>

              <div className="text-[11px] text-slate-500 pt-2 flex items-center justify-between">
                <span>Selected: <strong className="text-emerald-700 font-bold">{selectedCount}</strong> / {students.length} students</span>
                <span className="text-slate-400">Target: {targetStandard}-{targetDivision}</span>
              </div>
            </div>

          </div>

          {/* Students List Table */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between flex-wrap gap-2 text-xs">
              <div className="flex items-center gap-3">
                <button
                  type="button"
                  onClick={handleToggleAll}
                  className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-300 rounded-lg font-semibold text-slate-700 hover:bg-slate-100 shadow-sm"
                >
                  <FiCheckSquare size={14} />
                  <span>Select / Deselect All</span>
                </button>
                <span className="font-semibold text-slate-700">
                  {students.length} students found
                </span>
              </div>

              <button
                type="button"
                onClick={handlePromote}
                disabled={submitting || selectedCount === 0}
                className="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white rounded-lg font-bold shadow flex items-center gap-2"
              >
                <FiSave size={14} />
                <span>{submitting ? 'Promoting...' : `Confirm & Promote (${selectedCount}) Students`}</span>
              </button>
            </div>

            {loading ? (
              <div className="py-16 text-center text-slate-500 text-xs">
                <div className="inline-block animate-spin w-6 h-6 border-2 border-slate-300 border-t-emerald-600 rounded-full mb-2"></div>
                <div>Fetching students for promotion...</div>
              </div>
            ) : students.length === 0 ? (
              <div className="py-16 text-center text-slate-400 text-xs">
                No students loaded yet. Configure source class and click "Load Students List".
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-100 border-b border-slate-200 text-slate-700 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-10 text-center">Select</th>
                      <th className="py-2.5 px-3 w-12 text-center">#</th>
                      <th className="py-2.5 px-3">GR No</th>
                      <th className="py-2.5 px-3">Student Name</th>
                      <th className="py-2.5 px-3">Current Std & Div</th>
                      <th className="py-2.5 px-3">Current Roll</th>
                      <th className="py-2.5 px-3 w-32">Target Roll No</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {students.map((st, i) => (
                      <tr
                        key={st.id}
                        className={`hover:bg-slate-50 transition-colors ${
                          selectedMap[st.id] ? 'bg-emerald-50/20' : ''
                        }`}
                      >
                        <td className="py-2 px-3 text-center">
                          <input
                            type="checkbox"
                            checked={!!selectedMap[st.id]}
                            onChange={() => handleToggleStudent(st.id)}
                            className="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 cursor-pointer"
                          />
                        </td>
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
                        <td className="py-2 px-3 text-slate-600">
                          {st.STVCLAS_rollno || 0}
                        </td>
                        <td className="py-2 px-3">
                          <input
                            type="number"
                            value={rollNoMap[st.id] || ''}
                            onChange={(e) =>
                              setRollNoMap((prev) => ({
                                ...prev,
                                [st.id]: e.target.value,
                              }))
                            }
                            className="w-20 border border-slate-300 rounded px-2 py-1 text-center font-bold text-slate-800 focus:ring-1 focus:ring-emerald-500"
                          />
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>

        </div>
      </main>
    </div>
  );
}
