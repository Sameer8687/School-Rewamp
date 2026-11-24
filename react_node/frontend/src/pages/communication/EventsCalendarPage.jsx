import React, { useState, useEffect } from 'react';
import communicationService from '../../services/communicationService';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiCalendar,
  FiPlus,
  FiEdit2,
  FiTrash2,
  FiSave,
  FiCheckCircle,
  FiAlertCircle,
  FiClock,
  FiUsers,
  FiX,
} from 'react-icons/fi';

const CLASSES = ['ALL', 'NURSERY', 'JR.KG', 'SR.KG', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

export default function EventsCalendarPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  const [events, setEvents] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editId, setEditId] = useState(null);

  const [eventForm, setEventForm] = useState({
    title: '',
    details: '',
    startDate: '',
    endDate: '',
    standard: 'ALL',
  });

  useEffect(() => {
    loadEvents();
  }, []);

  const loadEvents = async () => {
    try {
      setLoading(true);
      const res = await communicationService.getEvents();
      setEvents(res.data?.data || []);
    } catch (err) {
      console.error('Failed to load events:', err);
      setMessage({ text: 'Failed to load school events.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const handleOpenAdd = () => {
    setEditId(null);
    setEventForm({ title: '', details: '', startDate: '', endDate: '', standard: 'ALL' });
    setIsModalOpen(true);
  };

  const handleOpenEdit = (ev) => {
    setEditId(ev.id);
    setEventForm({
      title: ev.title,
      details: ev.details || '',
      startDate: ev.start_date || '',
      endDate: ev.end_date || '',
      standard: ev.standard || 'ALL',
    });
    setIsModalOpen(true);
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!eventForm.title.trim() || !eventForm.startDate) return;

    try {
      setSaving(true);
      setMessage({ text: '', type: '' });
      if (editId) {
        await communicationService.updateEvent(editId, eventForm);
        setMessage({ text: 'Event updated successfully!', type: 'success' });
      } else {
        await communicationService.createEvent(eventForm);
        setMessage({ text: 'Event published successfully!', type: 'success' });
      }
      setIsModalOpen(false);
      loadEvents();
    } catch (err) {
      console.error('Save event error:', err);
      setMessage({ text: 'Failed to save event.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id, title) => {
    if (!window.confirm(`Are you sure you want to delete event "${title}"?`)) return;
    try {
      await communicationService.deleteEvent(id);
      setMessage({ text: 'Event deleted successfully!', type: 'success' });
      loadEvents();
    } catch (err) {
      console.error('Delete event error:', err);
      setMessage({ text: 'Failed to delete event.', type: 'error' });
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-6xl mx-auto space-y-6">
            {/* Page Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                  <FiCalendar className="text-[#1976d2]" />
                  School Calendar & Event Notices
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Schedule academic events, sports meets, holidays, and school celebrations
                </p>
              </div>

              <button
                onClick={handleOpenAdd}
                className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition"
              >
                <FiPlus /> Publish Event
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

            {/* Events List */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
              {events.length === 0 ? (
                <div className="col-span-2 bg-white rounded-xl border border-slate-200 p-12 text-center text-slate-400">
                  {loading ? 'Loading school events...' : 'No upcoming events scheduled.'}
                </div>
              ) : (
                events.map((ev) => (
                  <div
                    key={ev.id}
                    className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex flex-col justify-between hover:shadow-md transition"
                  >
                    <div>
                      <div className="flex items-start justify-between gap-3 mb-2">
                        <h3 className="font-bold text-slate-900 text-sm">{ev.title}</h3>
                        <span className="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#e3f2fd] text-[#1565c0] whitespace-nowrap">
                          {ev.standard === 'ALL' ? 'All Classes' : `Std ${ev.standard}`}
                        </span>
                      </div>

                      <div className="text-[11px] text-[#1976d2] font-semibold flex items-center gap-1.5 mb-2">
                        <FiClock /> {ev.start_date} {ev.end_date && ev.end_date !== ev.start_date ? `to ${ev.end_date}` : ''}
                      </div>

                      <p className="text-xs text-slate-600 line-clamp-3">{ev.details || 'No additional details.'}</p>
                    </div>

                    <div className="pt-3 mt-4 border-t border-slate-100 flex justify-end space-x-1">
                      <button
                        onClick={() => handleOpenEdit(ev)}
                        className="p-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition"
                        title="Edit Event"
                      >
                        <FiEdit2 size={13} />
                      </button>
                      <button
                        onClick={() => handleDelete(ev.id, ev.title)}
                        className="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition"
                        title="Delete Event"
                      >
                        <FiTrash2 size={13} />
                      </button>
                    </div>
                  </div>
                ))
              )}
            </div>

            {/* Modal: Add / Edit Event */}
            {isModalOpen && (
              <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
                <div className="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden">
                  <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                      <FiCalendar className="text-[#1976d2]" />
                      {editId ? 'Edit School Event' : 'Publish School Event'}
                    </h3>
                    <button onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                      <FiX size={18} />
                    </button>
                  </div>

                  <form onSubmit={handleSave} className="p-6 space-y-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Event Title
                      </label>
                      <input
                        type="text"
                        value={eventForm.title}
                        onChange={(e) => setEventForm({ ...eventForm, title: e.target.value })}
                        placeholder="e.g. Annual Sports Day, Independence Day Celebration"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div className="grid grid-cols-2 gap-3">
                      <div>
                        <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                          Start Date
                        </label>
                        <input
                          type="date"
                          value={eventForm.startDate}
                          onChange={(e) => setEventForm({ ...eventForm, startDate: e.target.value })}
                          className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                          required
                        />
                      </div>

                      <div>
                        <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                          End Date (Optional)
                        </label>
                        <input
                          type="date"
                          value={eventForm.endDate}
                          onChange={(e) => setEventForm({ ...eventForm, endDate: e.target.value })}
                          className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        />
                      </div>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Target Audience / Standard
                      </label>
                      <select
                        value={eventForm.standard}
                        onChange={(e) => setEventForm({ ...eventForm, standard: e.target.value })}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        {CLASSES.map((c) => (
                          <option key={c} value={c}>
                            {c === 'ALL' ? 'All Classes (School-wide)' : `Std ${c}`}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Event Description & Notice Details
                      </label>
                      <textarea
                        value={eventForm.details}
                        onChange={(e) => setEventForm({ ...eventForm, details: e.target.value })}
                        rows="3"
                        placeholder="Details of the event, reporting time, dress code, etc."
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
                        <FiSave /> {saving ? 'Saving...' : 'Publish Event'}
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
