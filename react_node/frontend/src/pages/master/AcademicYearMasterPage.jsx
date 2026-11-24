import React, { useState, useEffect } from 'react';
import masterService from '../../services/masterService';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiCalendar,
  FiPlus,
  FiEdit2,
  FiCheckCircle,
  FiAlertCircle,
  FiStar,
  FiX,
  FiSave,
} from 'react-icons/fi';

export default function AcademicYearMasterPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  const [academicYears, setAcademicYears] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editId, setEditId] = useState(null);

  // Form states
  const [startDate, setStartDate] = useState('');
  const [endDate, setEndDate] = useState('');
  const [yearName, setYearName] = useState('');
  const [notes, setNotes] = useState('');

  useEffect(() => {
    loadAcademicYears();
  }, []);

  const loadAcademicYears = async () => {
    try {
      setLoading(true);
      const res = await masterService.getAcademicYears();
      setAcademicYears(res.data?.data || []);
    } catch (err) {
      console.error('Failed to load academic years:', err);
      setMessage({ text: 'Failed to load academic years.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const handleOpenAdd = () => {
    setEditId(null);
    setStartDate('');
    setEndDate('');
    setYearName('');
    setNotes('');
    setIsModalOpen(true);
  };

  const handleOpenEdit = (yr) => {
    setEditId(yr.id);
    setStartDate(yr.start_date || '');
    setEndDate(yr.end_date || '');
    setYearName(yr.year_name || '');
    setNotes(yr.notes || '');
    setIsModalOpen(true);
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!startDate || !endDate) {
      setMessage({ text: 'Start date and end date are required.', type: 'error' });
      return;
    }

    try {
      setSaving(true);
      setMessage({ text: '', type: '' });

      if (editId) {
        await masterService.updateAcademicYear(editId, {
          start: startDate,
          end: endDate,
          year: yearName,
          notes,
        });
        setMessage({ text: 'Academic year updated successfully!', type: 'success' });
      } else {
        await masterService.createAcademicYear({
          start: startDate,
          end: endDate,
          year: yearName,
          notes,
        });
        setMessage({ text: 'New academic year created successfully!', type: 'success' });
      }

      setIsModalOpen(false);
      loadAcademicYears();
    } catch (err) {
      console.error('Save academic year error:', err);
      setMessage({ text: 'Failed to save academic year.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  const handleSetActive = async (id, yearLabel) => {
    if (!window.confirm(`Are you sure you want to set "${yearLabel}" as the ACTIVE academic session for the entire school?`)) {
      return;
    }

    try {
      await masterService.setActiveAcademicYear(id);
      setMessage({ text: `Academic session "${yearLabel}" is now active!`, type: 'success' });
      loadAcademicYears();
    } catch (err) {
      console.error('Set active session error:', err);
      setMessage({ text: 'Failed to change active session.', type: 'error' });
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-7xl mx-auto space-y-6">
            {/* Page Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                  <FiCalendar className="text-[#1976d2]" />
                  Academic Session Master
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Configure school academic years, term dates, and active school session
                </p>
              </div>

              <button
                onClick={handleOpenAdd}
                className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition cursor-pointer"
              >
                <FiPlus /> New Academic Year
              </button>
            </div>

            {/* Notification alert */}
            {message.text && (
              <div
                className={`p-3.5 rounded-lg text-xs flex items-center gap-2 ${
                  message.type === 'success'
                    ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                    : 'bg-rose-50 text-rose-800 border border-rose-200'
                }`}
              >
                {message.type === 'success' ? <FiCheckCircle /> : <FiAlertCircle />}
                <span>{message.text}</span>
              </div>
            )}

            {/* Academic Years Table */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 className="text-sm font-bold text-slate-800">Registered Academic Years ({academicYears.length})</h2>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                      <th className="py-3 px-4 w-16 text-center">ID</th>
                      <th className="py-3 px-4">Academic Session</th>
                      <th className="py-3 px-4">Session Period</th>
                      <th className="py-3 px-4">Notes / Remarks</th>
                      <th className="py-3 px-4 text-center">Status</th>
                      <th className="py-3 px-4 w-36 text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {academicYears.length === 0 ? (
                      <tr>
                        <td colSpan="6" className="py-8 text-center text-slate-400">
                          {loading ? 'Loading academic sessions...' : 'No academic years registered.'}
                        </td>
                      </tr>
                    ) : (
                      academicYears.map((yr) => (
                        <tr key={yr.id} className="hover:bg-slate-50 transition-colors">
                          <td className="py-3 px-4 text-center font-mono text-slate-400">{yr.id}</td>
                          <td className="py-3 px-4 font-bold text-slate-900 text-sm">
                            {yr.year_name}
                          </td>
                          <td className="py-3 px-4 text-slate-600">
                            {yr.start_date} &bull; {yr.end_date}
                          </td>
                          <td className="py-3 px-4 text-slate-500">{yr.notes || '--'}</td>
                          <td className="py-3 px-4 text-center">
                            {yr.is_active ? (
                              <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <FiStar size={11} className="fill-emerald-600" /> Active Session
                              </span>
                            ) : (
                              <span className="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500">
                                Inactive
                              </span>
                            )}
                          </td>
                          <td className="py-3 px-4 text-center space-x-2">
                            <button
                              onClick={() => handleOpenEdit(yr)}
                              className="px-2.5 py-1 text-[11px] font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-100 border border-slate-300 rounded transition"
                              title="Edit Session Dates"
                            >
                              <FiEdit2 size={12} className="inline mr-1" /> Edit
                            </button>

                            {!yr.is_active && (
                              <button
                                onClick={() => handleSetActive(yr.id, yr.year_name)}
                                className="px-2.5 py-1 text-[11px] font-semibold text-emerald-700 hover:bg-emerald-50 border border-emerald-400 rounded transition"
                              >
                                Set Active
                              </button>
                            )}
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Modal: Add / Edit Academic Year */}
            {isModalOpen && (
              <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
                <div className="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
                  <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                      <FiCalendar className="text-[#1976d2]" />
                      {editId ? 'Edit Academic Year' : 'Add Academic Year'}
                    </h3>
                    <button onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                      <FiX size={18} />
                    </button>
                  </div>

                  <form onSubmit={handleSave} className="p-6 space-y-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Session Start Date
                      </label>
                      <input
                        type="date"
                        value={startDate}
                        onChange={(e) => setStartDate(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Session End Date
                      </label>
                      <input
                        type="date"
                        value={endDate}
                        onChange={(e) => setEndDate(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Session Display Name (Optional)
                      </label>
                      <input
                        type="text"
                        value={yearName}
                        onChange={(e) => setYearName(e.target.value)}
                        placeholder="e.g. 2026-27"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                      />
                      <span className="text-[10px] text-slate-400 mt-0.5 block">
                        Leave blank to auto-generate from dates (e.g. 2026-27).
                      </span>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Notes / Remarks
                      </label>
                      <textarea
                        value={notes}
                        onChange={(e) => setNotes(e.target.value)}
                        rows="2"
                        placeholder="Optional remarks"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                      />
                    </div>

                    <div className="pt-3 border-t border-slate-200 flex justify-end gap-2">
                      <button
                        type="button"
                        onClick={() => setIsModalOpen(false)}
                        className="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        disabled={saving}
                        className="flex items-center gap-1.5 px-5 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50"
                      >
                        <FiSave /> {saving ? 'Saving...' : 'Save Academic Year'}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
