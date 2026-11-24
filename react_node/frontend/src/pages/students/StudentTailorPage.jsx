import React, { useEffect, useState } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import { LuSave, LuCheck, LuSearch, LuLayers } from 'react-icons/lu';
import studentExtrasService from '../../services/studentExtrasService';

export default function StudentTailorPage() {
  const [options, setOptions] = useState({ standards: [], divisions: [] });
  const [standard, setStandard] = useState('');
  const [division, setDivision] = useState('');
  const [rows, setRows] = useState([]);
  const [bulkTailor, setBulkTailor] = useState('');
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState(null);

  const getStdName = (s) => (typeof s === 'string' ? s : s?.standard || s?.name || '');
  const getDivName = (d) => (typeof d === 'string' ? d : d?.division || d?.name || '');

  useEffect(() => {
    (async () => {
      try {
        const res = await studentExtrasService.getOptions();
        if (res.success) {
          setOptions(res.data);
          const rawStds = res.data.standards || [];
          const rawDivs = res.data.divisions || [];
          if (rawStds.length > 0) setStandard(getStdName(rawStds[0]));
          if (rawDivs.length > 0) setDivision(getDivName(rawDivs[0]));
        }
      } catch (err) {
        setMessage({ type: 'error', text: 'Failed to load class options' });
      }
    })();
  }, []);

  const loadRows = async () => {
    if (!standard) return;
    setLoading(true);
    try {
      const res = await studentExtrasService.getTailorStudents({ standard, division });
      if (res.success) setRows(res.data || []);
    } catch (err) {
      setMessage({ type: 'error', text: 'Failed to load tailor records' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (standard) loadRows();
  }, [standard, division]);

  const updateRow = (studentId, field, value) => {
    setRows((prev) =>
      prev.map((r) => (r.student_id === studentId ? { ...r, [field]: value } : r))
    );
  };

  const applyBulkTailor = () => {
    if (!bulkTailor.trim()) return;
    setRows((prev) => prev.map((r) => ({ ...r, tailor_name: bulkTailor.trim() })));
  };

  const handleSave = async () => {
    if (rows.length === 0) return;
    setSaving(true);
    try {
      const payload = rows.map((r) => ({
        student_id: r.student_id,
        student_code: r.student_code,
        student_name: r.student_name,
        standard: r.standard,
        division: r.division,
        tailor_name: r.tailor_name || '',
        is_received: Boolean(r.is_received),
      }));
      const res = await studentExtrasService.saveTailorRecords(payload);
      if (res.success) {
        setMessage({ type: 'success', text: res.message });
        setTimeout(() => setMessage(null), 4000);
        loadRows();
      }
    } catch (err) {
      setMessage({ type: 'error', text: err.response?.data?.message || 'Save failed' });
    } finally {
      setSaving(false);
    }
  };

  const filtered = rows.filter((r) => {
    const q = search.trim().toLowerCase();
    if (!q) return true;
    return (
      (r.student_name || '').toLowerCase().includes(q) ||
      (r.student_code || '').toLowerCase().includes(q) ||
      (r.tailor_name || '').toLowerCase().includes(q)
    );
  });

  const receivedCount = rows.filter((r) => r.is_received).length;

  return (
    <div className="flex min-h-screen bg-[#f7f7f5] text-[#202020]">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0">
        <TopNav />
        <main className="p-4 sm:p-6 lg:p-8 flex-1 space-y-6">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <LuLayers className="text-[#1976d2]" />
                Uniform & Tailor Tracking
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Assign tailors and track uniform distribution status per student ({receivedCount}/{rows.length} received).
              </p>
            </div>

            <button
              onClick={handleSave}
              disabled={saving || rows.length === 0}
              className="inline-flex items-center gap-2 rounded-lg bg-[#1976d2] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1565c0] disabled:opacity-50"
            >
              <LuSave className="w-4 h-4" />
              {saving ? 'Saving...' : 'Save Tailor Details'}
            </button>
          </div>

      {message && (
        <div
          className={`rounded-lg px-4 py-3 text-sm font-medium ${
            message.type === 'error'
              ? 'bg-red-50 text-red-700 border border-red-200'
              : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
          }`}
        >
          {message.text}
        </div>
      )}

      <div className="bg-white rounded-xl border border-slate-200 p-4 flex flex-wrap gap-4 items-end">
        <div>
          <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Standard</label>
          <select
            value={standard}
            onChange={(e) => setStandard(e.target.value)}
            className="rounded-lg border border-slate-300 px-3 py-2 text-sm min-w-[130px]"
          >
            {options.standards.map((s, idx) => {
              const val = getStdName(s);
              return (
                <option key={idx} value={val}>
                  {val}
                </option>
              );
            })}
          </select>
        </div>
        <div>
          <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Division</label>
          <select
            value={division}
            onChange={(e) => setDivision(e.target.value)}
            className="rounded-lg border border-slate-300 px-3 py-2 text-sm min-w-[110px]"
          >
            <option value="">All</option>
            {options.divisions.map((d, idx) => {
              const val = getDivName(d);
              return (
                <option key={idx} value={val}>
                  {val}
                </option>
              );
            })}
          </select>
        </div>
        <div className="flex items-end gap-2">
          <div>
            <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">
              Bulk Assign Tailor
            </label>
            <input
              type="text"
              value={bulkTailor}
              onChange={(e) => setBulkTailor(e.target.value)}
              placeholder="Tailor name..."
              className="rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
          <button
            type="button"
            onClick={applyBulkTailor}
            className="rounded-lg bg-slate-100 border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200"
          >
            Apply to All
          </button>
        </div>
        <div className="flex-1 min-w-[200px]">
          <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Search</label>
          <div className="relative">
            <LuSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Search student or tailor..."
              className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm"
            />
          </div>
        </div>
      </div>

      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-slate-200 text-sm">
            <thead className="bg-slate-50">
              <tr>
                <th className="px-4 py-3 text-left font-semibold text-slate-600">Roll</th>
                <th className="px-4 py-3 text-left font-semibold text-slate-600">Student ID</th>
                <th className="px-4 py-3 text-left font-semibold text-slate-600">Student Name</th>
                <th className="px-4 py-3 text-left font-semibold text-slate-600">Class</th>
                <th className="px-4 py-3 text-left font-semibold text-slate-600">Tailor Name</th>
                <th className="px-4 py-3 text-center font-semibold text-slate-600">Uniform Received</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {loading ? (
                <tr>
                  <td colSpan={6} className="px-4 py-8 text-center text-slate-500">
                    Loading tailor records...
                  </td>
                </tr>
              ) : filtered.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-4 py-8 text-center text-slate-500">
                    No students found for {standard} {division}.
                  </td>
                </tr>
              ) : (
                filtered.map((r) => (
                  <tr key={r.student_id} className="hover:bg-slate-50">
                    <td className="px-4 py-2.5 text-slate-600">{r.roll_no || '—'}</td>
                    <td className="px-4 py-2.5 font-mono text-xs text-slate-700">{r.student_code}</td>
                    <td className="px-4 py-2.5 font-medium text-slate-900">{r.student_name}</td>
                    <td className="px-4 py-2.5 text-slate-600">
                      {r.standard} - {r.division}
                    </td>
                    <td className="px-4 py-2.5">
                      <input
                        type="text"
                        value={r.tailor_name || ''}
                        onChange={(e) => updateRow(r.student_id, 'tailor_name', e.target.value)}
                        placeholder="Enter tailor name..."
                        className="w-56 rounded border border-slate-300 px-2.5 py-1.5 text-sm"
                      />
                    </td>
                    <td className="px-4 py-2.5 text-center">
                      <button
                        type="button"
                        onClick={() => updateRow(r.student_id, 'is_received', !r.is_received)}
                        className={`inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold transition-colors ${
                          r.is_received
                            ? 'bg-emerald-100 text-emerald-700 border border-emerald-300'
                            : 'bg-slate-100 text-slate-600 border border-slate-300'
                        }`}
                      >
                        <LuCheck className="w-3.5 h-3.5" />
                        {r.is_received ? 'Received' : 'Pending'}
                      </button>
                    </td>
                  </tr>
                ))
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

