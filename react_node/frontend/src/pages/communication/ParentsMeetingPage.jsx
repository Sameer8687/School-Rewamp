import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import meetingService from '../../services/meetingService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiUsers,
  FiCalendar,
  FiClock,
  FiMapPin,
  FiPlus,
  FiTrash2,
  FiRefreshCw,
  FiFileText,
} from 'react-icons/fi';

export default function ParentsMeetingPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(true);
  const [meetings, setMeetings] = useState([]);

  const [academicYears, setAcademicYears] = useState([]);
  const [standards, setStandards] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');

  // Modal
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    prameet_date: new Date().toISOString().slice(0, 10),
    prameet_time: '10:00',
    prameet_vanue: 'School Auditorium',
    prameet_agenda: '',
    prameet_class: 'ALL',
  });

  const loadMeetings = async () => {
    setLoading(true);
    try {
      const res = await meetingService.getMeetings(selectedYear);
      if (res.data?.success) {
        setMeetings(res.data.meetings || []);
      }
    } catch (err) {
      console.error('Failed to load meetings:', err);
    } finally {
      setLoading(false);
    }
  };

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
          if (active) setSelectedYear(String(active.stvacdm_id));
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  useEffect(() => {
    if (selectedYear) {
      loadMeetings();
    }
  }, [selectedYear]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.prameet_agenda) {
      alert('Please enter meeting agenda');
      return;
    }
    try {
      const res = await meetingService.createMeeting({
        ...formData,
        prameet_academic_year: selectedYear,
      });
      if (res.data?.success) {
        alert('Parents meeting scheduled successfully.');
        setIsModalOpen(false);
        setFormData({
          prameet_date: new Date().toISOString().slice(0, 10),
          prameet_time: '10:00',
          prameet_vanue: 'School Auditorium',
          prameet_agenda: '',
          prameet_class: 'ALL',
        });
        loadMeetings();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to schedule meeting');
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to cancel and delete this meeting?')) return;
    try {
      const res = await meetingService.deleteMeeting(id);
      if (res.data?.success) {
        loadMeetings();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to delete meeting');
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-4 md:p-6 lg:p-8 space-y-6">
          {/* Header */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 rounded-xl bg-gradient-to-tr from-[#1976d2] to-[#1565c0] flex items-center justify-center text-white shadow-md">
                <FiUsers size={24} />
              </div>
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                  Parent-Teacher Meetings (PTM)
                </h1>
                <p className="text-xs text-slate-500">
                  Organize, schedule, and communicate official meetings and agendas with parents
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2.5">
              <button
                onClick={() => setIsModalOpen(true)}
                className="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] hover:bg-[#1565c0] rounded-lg shadow-sm transition-all cursor-pointer"
              >
                <FiPlus size={15} />
                <span>Schedule Meeting</span>
              </button>
            </div>
          </div>

          {/* Academic Year Filter */}
          <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between gap-3">
            <div className="flex items-center gap-2">
              <span className="text-xs font-bold text-slate-600">Session Filter:</span>
              <select
                value={selectedYear}
                onChange={(e) => setSelectedYear(e.target.value)}
                className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 cursor-pointer"
              >
                {academicYears.map((y) => (
                  <option key={y.stvacdm_id} value={y.stvacdm_id}>
                    Session {y.stvacdm_year || y.stvacdm_id}
                  </option>
                ))}
              </select>
            </div>
            <div className="text-xs text-slate-500 font-medium">
              {meetings.length} scheduled meeting{meetings.length === 1 ? '' : 's'}
            </div>
          </div>

          {/* Meeting Cards / List */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {loading ? (
              <div className="col-span-full py-20 flex flex-col items-center justify-center text-slate-400">
                <FiRefreshCw className="animate-spin mb-3 text-[#1976d2]" size={28} />
                <p className="text-sm font-medium">Loading scheduled meetings...</p>
              </div>
            ) : meetings.length === 0 ? (
              <div className="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 text-slate-400 text-xs">
                No parents meetings scheduled for this session yet.
              </div>
            ) : (
              meetings.map((m) => (
                <div
                  key={m.id}
                  className="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow"
                >
                  <div className="space-y-3">
                    <div className="flex items-center justify-between">
                      <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#e3f2fd] text-[#1565c0] border border-[#bbdefb]">
                        Target Class: {m.class_name || 'All Classes'}
                      </span>
                      <button
                        onClick={() => handleDelete(m.id)}
                        className="p-1 text-slate-400 hover:text-rose-600 transition-colors cursor-pointer"
                        title="Delete Meeting"
                      >
                        <FiTrash2 size={14} />
                      </button>
                    </div>

                    <h3 className="font-bold text-slate-900 text-sm">{m.agenda}</h3>

                    <div className="space-y-1.5 text-xs text-slate-600 pt-1">
                      <div className="flex items-center gap-2">
                        <FiCalendar className="text-[#1976d2]" size={14} />
                        <span>Date: {m.meeting_date ? m.meeting_date.slice(0, 10) : '-'}</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <FiClock className="text-[#1976d2]" size={14} />
                        <span>Time: {m.meeting_time || '10:00 AM'}</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <FiMapPin className="text-[#1976d2]" size={14} />
                        <span>Venue: {m.venue || 'School Campus'}</span>
                      </div>
                    </div>
                  </div>

                  <div className="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 mt-4">
                    <span>Session {m.stvacdm_year || m.academic_year}</span>
                    <span className="font-semibold text-emerald-600">Confirmed</span>
                  </div>
                </div>
              ))
            )}
          </div>
        </main>
      </div>

      {/* Schedule Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <h3 className="font-bold text-slate-900 text-base">Schedule Parents Meeting</h3>
              <button
                onClick={() => setIsModalOpen(false)}
                className="text-slate-400 hover:text-slate-700 text-lg cursor-pointer"
              >
                ✕
              </button>
            </div>

            <form onSubmit={handleSubmit} className="space-y-3 text-xs">
              <div className="grid grid-cols-2 gap-2.5">
                <div>
                  <label className="block font-bold text-slate-700 mb-1">
                    Meeting Date <span className="text-rose-500">*</span>:
                  </label>
                  <input
                    type="date"
                    required
                    value={formData.prameet_date}
                    onChange={(e) => setFormData({ ...formData, prameet_date: e.target.value })}
                    className="w-full p-2 border border-slate-200 rounded-lg"
                  />
                </div>
                <div>
                  <label className="block font-bold text-slate-700 mb-1">
                    Meeting Time <span className="text-rose-500">*</span>:
                  </label>
                  <input
                    type="time"
                    required
                    value={formData.prameet_time}
                    onChange={(e) => setFormData({ ...formData, prameet_time: e.target.value })}
                    className="w-full p-2 border border-slate-200 rounded-lg"
                  />
                </div>
              </div>

              <div>
                <label className="block font-bold text-slate-700 mb-1">Target Standard / Class:</label>
                <select
                  value={formData.prameet_class}
                  onChange={(e) => setFormData({ ...formData, prameet_class: e.target.value })}
                  className="w-full p-2 border border-slate-200 rounded-lg"
                >
                  <option value="ALL">All Classes & Sections</option>
                  {standards.map((c) => (
                    <option key={c.STVCLAS_standard || c.id} value={c.STVCLAS_standard}>
                      Std {c.STVCLAS_standard}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block font-bold text-slate-700 mb-1">Meeting Venue:</label>
                <input
                  type="text"
                  value={formData.prameet_vanue}
                  onChange={(e) => setFormData({ ...formData, prameet_vanue: e.target.value })}
                  placeholder="e.g. Main Auditorium / Room 102"
                  className="w-full p-2 border border-slate-200 rounded-lg"
                />
              </div>

              <div>
                <label className="block font-bold text-slate-700 mb-1">
                  Meeting Agenda / Purpose <span className="text-rose-500">*</span>:
                </label>
                <textarea
                  rows="3"
                  required
                  value={formData.prameet_agenda}
                  onChange={(e) => setFormData({ ...formData, prameet_agenda: e.target.value })}
                  placeholder="e.g. Discussion on Term 1 academic progress, fee schedules, and sports day preparations."
                  className="w-full p-2 border border-slate-200 rounded-lg"
                />
              </div>

              <div className="flex items-center justify-end gap-2 pt-3 border-t border-slate-200">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg cursor-pointer"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white font-semibold rounded-lg cursor-pointer"
                >
                  Schedule PTM
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
