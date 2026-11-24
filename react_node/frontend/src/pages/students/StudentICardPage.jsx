import React, { useEffect, useState } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import { LuCreditCard, LuPrinter, LuUpload, LuSearch } from 'react-icons/lu';
import studentExtrasService from '../../services/studentExtrasService';

export default function StudentICardPage() {
  const [options, setOptions] = useState({ standards: [], divisions: [] });
  const [standard, setStandard] = useState('');
  const [division, setDivision] = useState('');
  const [students, setStudents] = useState([]);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(false);
  const [uploadingId, setUploadingId] = useState(null);
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
        setMessage({ type: 'error', text: 'Failed to load standards & divisions' });
      }
    })();
  }, []);

  const loadStudents = async () => {
    if (!standard) return;
    setLoading(true);
    try {
      const res = await studentExtrasService.getICardStudents({ standard, division });
      if (res.success) setStudents(res.data || []);
    } catch (err) {
      setMessage({ type: 'error', text: 'Failed to load ID card students' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (standard) loadStudents();
  }, [standard, division]);

  const handlePhotoUpload = async (studentId, file) => {
    if (!file) return;
    setUploadingId(studentId);
    try {
      const res = await studentExtrasService.uploadICardPhoto(studentId, file);
      if (res.success) {
        setStudents((prev) =>
          prev.map((s) =>
            s.student_id === studentId ? { ...s, photo_url: res.data.photo_url } : s
          )
        );
        setMessage({ type: 'success', text: 'Photo updated successfully.' });
        setTimeout(() => setMessage(null), 3000);
      }
    } catch (err) {
      setMessage({ type: 'error', text: err.response?.data?.message || 'Photo upload failed' });
    } finally {
      setUploadingId(null);
    }
  };

  const filtered = students.filter((s) => {
    const q = search.trim().toLowerCase();
    if (!q) return true;
    return (
      (s.student_name || '').toLowerCase().includes(q) ||
      (s.student_code || '').toLowerCase().includes(q) ||
      (s.gr_number || '').toLowerCase().includes(q)
    );
  });

  return (
    <div className="flex min-h-screen bg-[#f7f7f5] text-[#202020]">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0">
        <TopNav />
        <main className="p-4 sm:p-6 lg:p-8 flex-1 space-y-6">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 print:hidden">
            <div>
              <h1 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <LuCreditCard className="text-[#1976d2]" />
                Student ID Cards & Photo Registry
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Upload student ID card photos and print class-wise student identity cards.
              </p>
            </div>

            <button
              onClick={() => window.print()}
              className="inline-flex items-center gap-2 rounded-lg bg-[#1976d2] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1565c0]"
            >
              <LuPrinter className="w-4 h-4" />
              Print ID Cards
            </button>
          </div>

      {message && (
        <div
          className={`rounded-lg px-4 py-3 text-sm font-medium print:hidden ${
            message.type === 'error'
              ? 'bg-red-50 text-red-700 border border-red-200'
              : 'bg-emerald-50 text-emerald-700 border border-emerald-200'
          }`}
        >
          {message.text}
        </div>
      )}

      {/* Filters */}
      <div className="bg-white rounded-xl border border-slate-200 p-4 flex flex-wrap gap-4 items-end print:hidden">
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
        <div className="flex-1 min-w-[200px]">
          <label className="block text-xs font-semibold text-slate-600 uppercase mb-1">Search</label>
          <div className="relative">
            <LuSearch className="absolute left-3 top-2.5 text-slate-400 w-4 h-4" />
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Search student name, ID, GR No..."
              className="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm"
            />
          </div>
        </div>
      </div>

      {/* Cards Grid */}
      {loading ? (
        <div className="py-12 text-center text-slate-500">Loading students...</div>
      ) : filtered.length === 0 ? (
        <div className="py-12 text-center text-slate-500 bg-white rounded-xl border border-slate-200">
          No students found for {standard} {division}.
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 print:grid-cols-2 print:gap-4">
          {filtered.map((s) => (
            <div
              key={s.student_id}
              className="bg-white rounded-xl border-2 border-[#bbdefb] shadow-sm overflow-hidden flex flex-col justify-between break-inside-avoid"
            >
              <div className="bg-[#1976d2] text-white px-4 py-2.5 text-center">
                <div className="text-xs uppercase tracking-wider font-bold">Olive School</div>
                <div className="text-[11px] text-[#e3f2fd]">Student Identity Card</div>
              </div>

              <div className="p-4 flex gap-4 items-center">
                <div className="flex flex-col items-center gap-2 shrink-0">
                  <div className="w-24 h-28 rounded-lg border border-slate-200 bg-slate-100 overflow-hidden flex items-center justify-center">
                    {s.photo_url ? (
                      <img
                        src={s.photo_url}
                        alt={s.student_name}
                        className="w-full h-full object-cover"
                      />
                    ) : (
                      <span className="text-xs text-slate-400 text-center px-1">No Photo</span>
                    )}
                  </div>
                  <label className="cursor-pointer inline-flex items-center gap-1 text-xs font-medium text-[#1976d2] hover:text-[#1565c0] print:hidden">
                    <LuUpload className="w-3.5 h-3.5" />
                    {uploadingId === s.student_id ? 'Uploading...' : 'Upload Photo'}
                    <input
                      type="file"
                      accept="image/*"
                      className="hidden"
                      onChange={(e) => handlePhotoUpload(s.student_id, e.target.files?.[0])}
                    />
                  </label>
                </div>

                <div className="text-xs space-y-1.5 flex-1 min-w-0">
                  <div className="font-bold text-sm text-slate-900 truncate">{s.student_name}</div>
                  <div className="text-slate-600">
                    <span className="font-semibold">ID:</span> {s.student_code}
                  </div>
                  <div className="text-slate-600">
                    <span className="font-semibold">Class:</span> {s.standard} - {s.division}{' '}
                    {s.roll_no ? `(Roll ${s.roll_no})` : ''}
                  </div>
                  <div className="text-slate-600">
                    <span className="font-semibold">GR No:</span> {s.gr_number || '—'}
                  </div>
                  <div className="text-slate-600">
                    <span className="font-semibold">DOB:</span> {s.dob || '—'}
                  </div>
                  <div className="text-slate-600">
                    <span className="font-semibold">Blood Group:</span> {s.blood_group || '—'}
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
        </main>
      </div>
    </div>
  );
}

