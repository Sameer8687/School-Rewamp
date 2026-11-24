import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import feesService from '../../services/feesService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiLayers,
  FiSave,
  FiCheck,
  FiAlertCircle,
  FiRefreshCw,
  FiDollarSign,
  FiEdit3
} from 'react-icons/fi';

export default function FeeStructurePage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [standards, setStandards] = useState([]);
  const [structureList, setStructureList] = useState([]);

  // Form input for adding/updating
  const [editStandard, setEditStandard] = useState('');
  const [editFees, setEditFees] = useState('');
  const [saving, setSaving] = useState(false);
  const [feedback, setFeedback] = useState(null);

  // Load academic years and standard options
  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          const years = res.data.options.academicYears || [];
          const classes = res.data.options.classes || [];
          setAcademicYears(years);
          setStandards(classes);

          const active = years.find((y) => y.is_active) || years[0];
          if (active) {
            setSelectedYear(String(active.stvacdm_id));
          }
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  // Load fee structure when academic year changes
  useEffect(() => {
    if (!selectedYear) return;
    loadStructure();
  }, [selectedYear]);

  async function loadStructure() {
    setLoading(true);
    setFeedback(null);
    try {
      const res = await feesService.getFeeStructure(selectedYear);
      if (res.data?.success) {
        setStructureList(res.data.structure || []);
      }
    } catch (err) {
      console.error('Failed to load fee structure:', err);
    } finally {
      setLoading(false);
    }
  }

  async function handleSave(e) {
    e.preventDefault();
    if (!editStandard || editFees === '') {
      alert('Please select class and enter fee amount');
      return;
    }

    setSaving(true);
    setFeedback(null);
    try {
      const res = await feesService.saveFeeStructure({
        standard: editStandard,
        fees: Number(editFees) || 0,
        academic_year: selectedYear
      });

      if (res.data?.success) {
        setFeedback({
          type: 'success',
          text: `Fee structure for Standard ${editStandard} updated successfully!`
        });
        setEditStandard('');
        setEditFees('');
        await loadStructure();
      }
    } catch (err) {
      console.error('Save failed:', err);
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to update fee structure'
      });
    } finally {
      setSaving(false);
    }
  }

  function handleEditClick(item) {
    setEditStandard(item.stvfstr_std);
    setEditFees(String(item.stvfstr_fees));
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav toggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiLayers className="text-[#1976d2]" /> Fee Structure Master
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Configure standard-wise annual tuition and school fees per academic year
              </p>
            </div>

            {/* Academic Year Selector */}
            <div className="flex items-center gap-2">
              <span className="text-xs font-semibold text-slate-500">Academic Year:</span>
              <select
                value={selectedYear}
                onChange={(e) => setSelectedYear(e.target.value)}
                className="px-3.5 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
              >
                {academicYears.map((y) => (
                  <option key={y.stvacdm_id} value={y.stvacdm_id}>
                    {y.stvacdm_year}
                  </option>
                ))}
              </select>
            </div>
          </div>

          {/* Feedback Banner */}
          {feedback && (
            <div
              className={`p-4 rounded-xl text-sm flex items-center gap-2.5 ${
                feedback.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border border-rose-200'
              }`}
            >
              {feedback.type === 'success' ? <FiCheck /> : <FiAlertCircle />}
              {feedback.text}
            </div>
          )}

          {/* Add / Edit Form */}
          <form
            onSubmit={handleSave}
            className="bg-white p-6 rounded-xl shadow-sm border border-slate-200/80 space-y-4"
          >
            <h3 className="text-base font-bold text-slate-900 flex items-center gap-2">
              <FiEdit3 className="text-[#1976d2]" /> Set / Update Standard Fee
            </h3>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Standard / Class <span className="text-rose-500">*</span>
                </label>
                <select
                  required
                  value={editStandard}
                  onChange={(e) => setEditStandard(e.target.value)}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                >
                  <option value="">Select Class</option>
                  {standards.map((c, i) => (
                    <option key={i} value={c}>
                      {c}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Annual Fee Amount (₹) <span className="text-rose-500">*</span>
                </label>
                <input
                  type="number"
                  required
                  min="0"
                  placeholder="e.g. 12000"
                  value={editFees}
                  onChange={(e) => setEditFees(e.target.value)}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#1976d2]/20 focus:border-[#1976d2]"
                />
              </div>

              <div className="flex items-end">
                <button
                  type="submit"
                  disabled={saving}
                  className="w-full py-2 px-4 bg-[#1976d2] hover:bg-[#1565c0] disabled:bg-slate-300 text-white rounded-lg text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition"
                >
                  {saving ? (
                    <>
                      <FiRefreshCw className="animate-spin" /> Saving...
                    </>
                  ) : (
                    <>
                      <FiSave /> Save Fee Structure
                    </>
                  )}
                </button>
              </div>
            </div>
          </form>

          {/* Configured Fee Structure Table */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                <FiDollarSign className="text-emerald-600" /> Active Fee Configurations
              </h3>
              <span className="text-xs text-slate-500 font-medium">
                {structureList.length} classes configured
              </span>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                    <th className="p-3">#</th>
                    <th className="p-3">Standard / Class</th>
                    <th className="p-3">Academic Term</th>
                    <th className="p-3 text-right">Annual Fees (₹)</th>
                    <th className="p-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan="5" className="p-8 text-center text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                        Loading fee structure...
                      </td>
                    </tr>
                  ) : structureList.length > 0 ? (
                    structureList.map((item, idx) => (
                      <tr key={item.stvfstr_id} className="hover:bg-slate-50/60 transition">
                        <td className="p-3 text-slate-400 font-mono">{idx + 1}</td>
                        <td className="p-3 font-bold text-slate-900">{item.stvfstr_std}</td>
                        <td className="p-3 text-slate-600">{item.stvacdm_year || selectedYear}</td>
                        <td className="p-3 text-right font-bold text-emerald-600 text-sm">
                          ₹{Number(item.stvfstr_fees).toLocaleString('en-IN')}
                        </td>
                        <td className="p-3 text-center">
                          <button
                            onClick={() => handleEditClick(item)}
                            className="px-3 py-1 bg-[#e3f2fd] text-[#1976d2] hover:bg-[#bbdefb] rounded text-xs font-semibold transition"
                          >
                            Edit
                          </button>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="5" className="p-8 text-center text-slate-400">
                        No fee structures defined yet for this academic year.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
