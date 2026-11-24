import React, { useState, useEffect } from 'react';
import masterService from '../../services/masterService';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiSliders,
  FiEye,
  FiEyeOff,
  FiSave,
  FiCheckCircle,
  FiAlertCircle,
  FiClock,
  FiUser,
} from 'react-icons/fi';

export default function WebAppSettingsPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  const [settings, setSettings] = useState({
    student_profile: 'Y',
    marks_visibility: 'Y',
    attendance_visibility: 'Y',
    last_action: 'admin',
    last_action_on: '',
  });

  useEffect(() => {
    loadSettings();
  }, []);

  const loadSettings = async () => {
    try {
      setLoading(true);
      const res = await masterService.getVisibilitySettings();
      if (res.data?.data) {
        setSettings(res.data.data);
      }
    } catch (err) {
      console.error('Failed to load visibility settings:', err);
      setMessage({ text: 'Failed to load system settings.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  const handleToggle = (field) => {
    setSettings((prev) => ({
      ...prev,
      [field]: prev[field] === 'Y' ? 'N' : 'Y',
    }));
  };

  const handleSave = async (e) => {
    e.preventDefault();
    try {
      setSaving(true);
      setMessage({ text: '', type: '' });
      await masterService.updateVisibilitySettings(settings);
      setMessage({ text: 'Web application visibility settings saved successfully!', type: 'success' });
      loadSettings();
    } catch (err) {
      console.error('Save visibility error:', err);
      setMessage({ text: 'Failed to save visibility settings.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-4xl mx-auto space-y-6">
            {/* Page Header */}
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiSliders className="text-[#1976d2]" />
                Web App & Portal Visibility Controls
              </h1>
              <p className="text-xs text-slate-500 mt-1">
                Configure features visible to students and parents across the online portal
              </p>
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

            {/* Controls Card */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8">
              <form onSubmit={handleSave} className="space-y-6">
                <div className="divide-y divide-slate-100">
                  {/* 1. Student Profile */}
                  <div className="py-4 flex items-center justify-between">
                    <div>
                      <h3 className="text-sm font-bold text-slate-900">Student Profile Visibility</h3>
                      <p className="text-xs text-slate-500 mt-0.5">
                        Allow students and parents to view bio, parent details, and enrolled standards
                      </p>
                    </div>

                    <button
                      type="button"
                      onClick={() => handleToggle('student_profile')}
                      className={`relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none ${
                        settings.student_profile === 'Y' ? 'bg-[#1976d2]' : 'bg-slate-200'
                      }`}
                    >
                      <span
                        className={`pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${
                          settings.student_profile === 'Y' ? 'translate-x-5' : 'translate-x-0'
                        }`}
                      />
                    </button>
                  </div>

                  {/* 2. Marks & Results */}
                  <div className="py-4 flex items-center justify-between">
                    <div>
                      <h3 className="text-sm font-bold text-slate-900">Marks & Examination Results</h3>
                      <p className="text-xs text-slate-500 mt-0.5">
                        Allow online access to terminal marksheets, grades, and academic report cards
                      </p>
                    </div>

                    <button
                      type="button"
                      onClick={() => handleToggle('marks_visibility')}
                      className={`relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none ${
                        settings.marks_visibility === 'Y' ? 'bg-[#1976d2]' : 'bg-slate-200'
                      }`}
                    >
                      <span
                        className={`pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${
                          settings.marks_visibility === 'Y' ? 'translate-x-5' : 'translate-x-0'
                        }`}
                      />
                    </button>
                  </div>

                  {/* 3. Daily Attendance */}
                  <div className="py-4 flex items-center justify-between">
                    <div>
                      <h3 className="text-sm font-bold text-slate-900">Daily Attendance Tracker</h3>
                      <p className="text-xs text-slate-500 mt-0.5">
                        Enable daily presence/absence records and monthly percentage visibility
                      </p>
                    </div>

                    <button
                      type="button"
                      onClick={() => handleToggle('attendance_visibility')}
                      className={`relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none ${
                        settings.attendance_visibility === 'Y' ? 'bg-[#1976d2]' : 'bg-slate-200'
                      }`}
                    >
                      <span
                        className={`pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out ${
                          settings.attendance_visibility === 'Y' ? 'translate-x-5' : 'translate-x-0'
                        }`}
                      />
                    </button>
                  </div>
                </div>

                {/* Audit trail */}
                {settings.last_action_on && (
                  <div className="text-[11px] text-slate-400 flex items-center gap-4 bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <span className="flex items-center gap-1">
                      <FiUser /> Last modified by: <strong>{settings.last_action}</strong>
                    </span>
                    <span className="flex items-center gap-1">
                      <FiClock /> On: {String(settings.last_action_on).substring(0, 10)}
                    </span>
                  </div>
                )}

                <div className="pt-4 border-t border-slate-100 flex justify-end">
                  <button
                    type="submit"
                    disabled={saving}
                    className="flex items-center gap-1.5 px-6 py-2.5 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50 transition cursor-pointer"
                  >
                    <FiSave /> {saving ? 'Saving...' : 'Save Visibility Settings'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
