import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import teacherService from '../../services/teacherService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiClock,
  FiSave,
  FiPlus,
  FiTrash2,
  FiRefreshCw,
  FiCheckCircle,
  FiAlertCircle
} from 'react-icons/fi';

export default function TimetablePage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [feedback, setFeedback] = useState(null);

  const [standards, setStandards] = useState([]);
  const [divisions, setDivisions] = useState(['A', 'B', 'C', 'D']);
  const [selectedStandard, setSelectedStandard] = useState('');
  const [selectedDivision, setSelectedDivision] = useState('');

  // Schedule matrix
  const [schedule, setSchedule] = useState([]);

  useEffect(() => {
    async function loadOptions() {
      try {
        const res = await fetchStudentOptions();
        if (res.data?.success) {
          const classes = res.data.options.classes || [];
          const divs = res.data.options.divisions || ['A', 'B', 'C', 'D'];
          setStandards(classes);
          setDivisions(divs);
          if (classes.length > 0) setSelectedStandard(classes[0]);
          if (divs.length > 0) setSelectedDivision(divs[0]);
        }
      } catch (err) {
        console.error('Failed to load options:', err);
      }
    }
    loadOptions();
  }, []);

  useEffect(() => {
    if (!selectedStandard || !selectedDivision) return;
    loadTimetable();
  }, [selectedStandard, selectedDivision]);

  async function loadTimetable() {
    setLoading(true);
    setFeedback(null);
    try {
      const res = await teacherService.getTimetable(selectedStandard, selectedDivision);
      if (res.data?.success) {
        const entries = res.data.entries || [];
        if (entries.length > 0) {
          setSchedule(
            entries.map((e) => ({
              time_slot: e.stvttbl_time || '',
              monday: e.stvttbl_monday || '',
              tuesday: e.stvttbl_tuesday || '',
              wednesday: e.stvttbl_wednesday || '',
              thursday: e.stvttbl_thurday || '',
              friday: e.stvttbl_friday || '',
              saturday: e.stvttbl_saturday || ''
            }))
          );
        } else {
          // Default empty periods template
          setSchedule([
            { time_slot: '08:00 - 08:45', monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '' },
            { time_slot: '08:45 - 09:30', monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '' },
            { time_slot: '09:30 - 10:15', monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '' },
            { time_slot: '10:15 - 10:45', monday: 'RECESS', tuesday: 'RECESS', wednesday: 'RECESS', thursday: 'RECESS', friday: 'RECESS', saturday: 'RECESS' },
            { time_slot: '10:45 - 11:30', monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '' },
            { time_slot: '11:30 - 12:15', monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '' },
            { time_slot: '12:15 - 01:00', monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '' }
          ]);
        }
      }
    } catch (err) {
      console.error('Failed to load timetable:', err);
    } finally {
      setLoading(false);
    }
  }

  function handleCellChange(index, field, value) {
    setSchedule((prev) => {
      const copy = [...prev];
      copy[index][field] = value;
      return copy;
    });
  }

  function addRow() {
    setSchedule((prev) => [
      ...prev,
      {
        time_slot: '01:00 - 01:45',
        monday: '',
        tuesday: '',
        wednesday: '',
        thursday: '',
        friday: '',
        saturday: ''
      }
    ]);
  }

  function removeRow(index) {
    setSchedule((prev) => prev.filter((_, i) => i !== index));
  }

  async function handleSave() {
    setSaving(true);
    setFeedback(null);
    try {
      const res = await teacherService.saveTimetable({
        standard: selectedStandard,
        division: selectedDivision,
        schedule
      });

      if (res.data?.success) {
        setFeedback({
          type: 'success',
          text: `Timetable for Std ${selectedStandard} - ${selectedDivision} saved successfully!`
        });
      }
    } catch (err) {
      console.error('Failed to save timetable:', err);
      setFeedback({ type: 'error', text: 'Failed to save timetable' });
    } finally {
      setSaving(false);
    }
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
                <FiClock className="text-[#1976d2]" /> Weekly Class Timetable
              </h1>
              <p className="text-sm text-slate-500 mt-1">
                Configure standard and division period schedules across weekdays
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={addRow}
                className="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition"
              >
                <FiPlus /> Add Slot
              </button>
              <button
                onClick={handleSave}
                disabled={saving || schedule.length === 0}
                className="px-4 py-1.5 bg-[#1976d2] hover:bg-[#1565c0] disabled:bg-slate-300 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 shadow-sm transition"
              >
                {saving ? (
                  <>
                    <FiRefreshCw className="animate-spin" /> Saving...
                  </>
                ) : (
                  <>
                    <FiSave /> Save Timetable
                  </>
                )}
              </button>
            </div>
          </div>

          {/* Feedback */}
          {feedback && (
            <div
              className={`p-3.5 rounded-xl text-sm flex items-center gap-2.5 ${
                feedback.type === 'success'
                  ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                  : 'bg-rose-50 text-rose-800 border border-rose-200'
              }`}
            >
              {feedback.type === 'success' ? <FiCheckCircle /> : <FiAlertCircle />}
              {feedback.text}
            </div>
          )}

          {/* Class Toolbar */}
          <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div className="flex items-center gap-2">
              <span className="text-xs font-semibold text-slate-500">Standard:</span>
              <select
                value={selectedStandard}
                onChange={(e) => setSelectedStandard(e.target.value)}
                className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold"
              >
                {standards.map((c, i) => (
                  <option key={i} value={c}>
                    {c}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex items-center gap-2">
              <span className="text-xs font-semibold text-slate-500">Division:</span>
              <select
                value={selectedDivision}
                onChange={(e) => setSelectedDivision(e.target.value)}
                className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold"
              >
                {divisions.map((d, i) => (
                  <option key={i} value={d}>
                    {d}
                  </option>
                ))}
              </select>
            </div>

            <button
              onClick={loadTimetable}
              className="px-3 py-1.5 bg-[#1976d2] text-white hover:bg-[#1565c0] rounded-lg text-xs font-semibold transition cursor-pointer shadow-sm"
            >
              Load Schedule
            </button>
          </div>

          {/* Timetable Matrix */}
          <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                    <th className="p-3 w-36">Time Slot</th>
                    <th className="p-3">Monday</th>
                    <th className="p-3">Tuesday</th>
                    <th className="p-3">Wednesday</th>
                    <th className="p-3">Thursday</th>
                    <th className="p-3">Friday</th>
                    <th className="p-3">Saturday</th>
                    <th className="p-3 text-center w-12">Del</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr>
                      <td colSpan="8" className="p-8 text-center text-slate-400">
                        <FiRefreshCw className="w-6 h-6 animate-spin mx-auto text-[#1976d2] mb-1" />
                        Loading timetable...
                      </td>
                    </tr>
                  ) : schedule.length > 0 ? (
                    schedule.map((row, idx) => (
                      <tr key={idx} className="hover:bg-slate-50/50 transition">
                        <td className="p-2">
                          <input
                            type="text"
                            value={row.time_slot}
                            onChange={(e) => handleCellChange(idx, 'time_slot', e.target.value)}
                            placeholder="e.g. 08:00 - 08:45"
                            className="w-full px-2 py-1.5 font-bold font-mono text-slate-700 bg-slate-50 border border-slate-200 rounded text-xs"
                          />
                        </td>
                        {['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].map(
                          (day) => (
                            <td key={day} className="p-2">
                              <input
                                type="text"
                                value={row[day]}
                                onChange={(e) => handleCellChange(idx, day, e.target.value)}
                                placeholder="Subject / Teacher"
                                className={`w-full px-2 py-1.5 border border-slate-200 rounded text-xs focus:ring-1 focus:ring-[#1976d2] ${
                                  row[day] === 'RECESS'
                                    ? 'bg-amber-50 font-bold text-amber-800 text-center'
                                    : 'bg-white'
                                }`}
                              />
                            </td>
                          )
                        )}
                        <td className="p-2 text-center">
                          <button
                            type="button"
                            onClick={() => removeRow(idx)}
                            className="text-slate-400 hover:text-rose-600 transition"
                            title="Remove Slot"
                          >
                            <FiTrash2 />
                          </button>
                        </td>
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="8" className="p-8 text-center text-slate-400">
                        No schedule entries. Click "Add Slot" to start.
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
