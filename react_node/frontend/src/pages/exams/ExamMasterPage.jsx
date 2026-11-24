import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import examService from '../../services/examService';
import { fetchStudentOptions } from '../../services/studentService.js';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiPlus,
  FiSave,
  FiCheckCircle,
  FiAlertCircle,
  FiSettings,
  FiBookOpen,
  FiList,
} from 'react-icons/fi';

const CLASSES = ['NURSERY', 'JR.KG', 'SR.KG', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

export default function ExamMasterPage() {
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [activeTab, setActiveTab] = useState('exams'); // 'exams' | 'setup'

  // Auto-switch tab based on CI3 route
  useEffect(() => {
    const p = (location.pathname || '').toLowerCase();
    if (p.includes('examsetup')) {
      setActiveTab('setup');
    } else {
      setActiveTab('exams');
    }
  }, [location.pathname]);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  // Academic years & exams
  const [academicYears, setAcademicYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState('');
  const [exams, setExams] = useState([]);

  // Create exam modal state
  const [newExamName, setNewExamName] = useState('');

  // Setup tab state
  const [selectedClass, setSelectedClass] = useState('I');
  const [selectedExamForSetup, setSelectedExamForSetup] = useState('');
  const [subjectsSetup, setSubjectsSetup] = useState([]);
  const [setupForm, setSetupForm] = useState({}); // { [subject_id]: { totalMarks, passingMarks } }

  useEffect(() => {
    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    try {
      setLoading(true);
      const optRes = await fetchStudentOptions();
      const yrList = optRes.data?.options?.academicYears || [];
      if (yrList.length > 0) {
        setAcademicYears(yrList);
        const active = yrList.find((y) => y.is_active || y.status === 'A') || yrList[0];
        setSelectedYear(active.stvacdm_id);
      }

      await loadExams();
    } catch (err) {
      console.error('Failed to load initial data:', err);
    } finally {
      setLoading(false);
    }
  };

  const loadExams = async () => {
    try {
      const res = await examService.getExamsList();
      const list = res.data?.data || [];
      setExams(list);
      if (list.length > 0 && !selectedExamForSetup) {
        setSelectedExamForSetup(list[0].id);
      }
    } catch (err) {
      console.error('Failed to load exams:', err);
    }
  };

  const handleCreateExam = async (e) => {
    e.preventDefault();
    if (!newExamName.trim()) return;

    try {
      setSaving(true);
      setMessage({ text: '', type: '' });
      await examService.createExam({
        name: newExamName.trim(),
        year: selectedYear,
      });

      setMessage({ text: 'Examination created successfully!', type: 'success' });
      setNewExamName('');
      loadExams();
    } catch (err) {
      console.error('Create exam error:', err);
      setMessage({ text: 'Failed to create exam.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  // Load subject setup for the selected class & exam
  const handleLoadSetup = async () => {
    if (!selectedClass || !selectedExamForSetup) return;

    try {
      setLoading(true);
      setMessage({ text: '', type: '' });

      // Fetch all subjects for this class
      const subjRes = await examService.getSubjects({ standard: selectedClass });
      const allSubjs = subjRes.data?.data || [];

      // Fetch existing setup
      const setupRes = await examService.getExamSetup({
        year: selectedYear,
        standard: selectedClass,
        examId: selectedExamForSetup,
      });
      const existingSetup = setupRes.data?.data || [];

      // Map into form state
      const form = {};
      allSubjs.forEach((sub) => {
        const found = existingSetup.find((item) => Number(item.subject_id) === Number(sub.id));
        form[sub.id] = {
          totalMarks: found ? found.total_marks : 100,
          passingMarks: found ? found.passing_marks : 35,
        };
      });

      setSubjectsSetup(allSubjs);
      setSetupForm(form);
    } catch (err) {
      console.error('Failed to load exam setup:', err);
      setMessage({ text: 'Failed to load exam setup.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (activeTab === 'setup' && selectedClass && selectedExamForSetup) {
      handleLoadSetup();
    }
  }, [activeTab, selectedClass, selectedExamForSetup]);

  const handleSetupChange = (subjectId, field, val) => {
    setSetupForm((prev) => ({
      ...prev,
      [subjectId]: {
        ...prev[subjectId],
        [field]: val,
      },
    }));
  };

  const handleSaveSingleSetup = async (subjectId) => {
    const config = setupForm[subjectId];
    if (!config) return;

    try {
      await examService.saveExamSetup({
        examId: selectedExamForSetup,
        subjectId,
        standard: selectedClass,
        totalMarks: config.totalMarks,
        passingMarks: config.passingMarks,
        year: selectedYear,
      });
      setMessage({ text: 'Subject criteria saved successfully!', type: 'success' });
    } catch (err) {
      console.error('Save setup error:', err);
      setMessage({ text: 'Failed to save subject criteria.', type: 'error' });
    }
  };

  const handleSaveAllSetup = async () => {
    try {
      setSaving(true);
      setMessage({ text: '', type: '' });

      for (const sub of subjectsSetup) {
        const config = setupForm[sub.id];
        if (config) {
          await examService.saveExamSetup({
            examId: selectedExamForSetup,
            subjectId: sub.id,
            standard: selectedClass,
            totalMarks: config.totalMarks,
            passingMarks: config.passingMarks,
            year: selectedYear,
          });
        }
      }

      setMessage({ text: 'All subject criteria saved successfully!', type: 'success' });
    } catch (err) {
      console.error('Save all setup error:', err);
      setMessage({ text: 'Failed to save all subject criteria.', type: 'error' });
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
          <div className="max-w-7xl mx-auto space-y-6">
            {/* Page Header */}
            <div>
              <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <FiSettings className="text-[#1976d2]" />
                Exam & Subject Master Setup
              </h1>
              <p className="text-xs text-slate-500 mt-1">
                Configure examination titles, subject weights, maximum marks, and passing thresholds
              </p>
            </div>

            {/* Alert Notification */}
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

            {/* Tab navigation */}
            <div className="flex border-b border-slate-200 gap-4">
              <button
                onClick={() => setActiveTab('exams')}
                className={`pb-3 text-xs font-semibold flex items-center gap-2 border-b-2 transition ${
                  activeTab === 'exams'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-slate-500 hover:text-slate-800'
                }`}
              >
                <FiList /> Examinations Directory
              </button>
              <button
                onClick={() => setActiveTab('setup')}
                className={`pb-3 text-xs font-semibold flex items-center gap-2 border-b-2 transition ${
                  activeTab === 'setup'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-slate-500 hover:text-slate-800'
                }`}
              >
                <FiBookOpen /> Subject-Wise Exam Marks Setup
              </button>
            </div>

            {/* TAB 1: Examinations Directory */}
            {activeTab === 'exams' && (
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {/* Create Exam Form */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 h-fit">
                  <h2 className="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <FiPlus className="text-[#1976d2]" /> Add New Examination
                  </h2>
                  <form onSubmit={handleCreateExam} className="space-y-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Academic Year
                      </label>
                      <select
                        value={selectedYear}
                        onChange={(e) => setSelectedYear(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        {academicYears.map((yr) => (
                          <option key={yr.stvacdm_id} value={yr.stvacdm_id}>
                            {yr.stvacdm_year}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Exam Name / Title
                      </label>
                      <input
                        type="text"
                        value={newExamName}
                        onChange={(e) => setNewExamName(e.target.value)}
                        placeholder="e.g. Unit Test 1, Term 1, Final Exam"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <button
                      type="submit"
                      disabled={saving}
                      className="w-full py-2.5 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50 transition"
                    >
                      {saving ? 'Creating...' : 'Create Examination'}
                    </button>
                  </form>
                </div>

                {/* Exams List Table */}
                <div className="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                  <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 className="text-sm font-bold text-slate-800">Available Examinations ({exams.length})</h2>
                  </div>

                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                          <th className="py-3 px-4 w-16 text-center">ID</th>
                          <th className="py-3 px-4">Exam Name</th>
                          <th className="py-3 px-4 w-32">Academic Year</th>
                          <th className="py-3 px-4 w-32">Added By</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100">
                        {exams.length === 0 ? (
                          <tr>
                            <td colSpan="4" className="py-8 text-center text-slate-400">
                              No examinations configured yet.
                            </td>
                          </tr>
                        ) : (
                          exams.map((ex) => (
                            <tr key={ex.id} className="hover:bg-slate-50 transition-colors">
                              <td className="py-3 px-4 text-center font-mono text-slate-400">{ex.id}</td>
                              <td className="py-3 px-4 font-semibold text-slate-800">{ex.name}</td>
                              <td className="py-3 px-4 text-slate-600">
                                {academicYears.find((y) => String(y.stvacdm_id) === String(ex.academic_year))
                                  ?.stvacdm_year || ex.academic_year || 'All Years'}
                              </td>
                              <td className="py-3 px-4 text-slate-500 capitalize">{ex.added_by || 'Admin'}</td>
                            </tr>
                          ))
                        )}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            )}

            {/* TAB 2: Subject Marks Setup */}
            {activeTab === 'setup' && (
              <div className="space-y-6">
                {/* Setup Filters */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Academic Year
                      </label>
                      <select
                        value={selectedYear}
                        onChange={(e) => setSelectedYear(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        {academicYears.map((yr) => (
                          <option key={yr.stvacdm_id} value={yr.stvacdm_id}>
                            {yr.stvacdm_year}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Class / Standard
                      </label>
                      <select
                        value={selectedClass}
                        onChange={(e) => setSelectedClass(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        {CLASSES.map((cls) => (
                          <option key={cls} value={cls}>
                            Std {cls}
                          </option>
                        ))}
                      </select>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Examination
                      </label>
                      <select
                        value={selectedExamForSetup}
                        onChange={(e) => setSelectedExamForSetup(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        {exams.map((ex) => (
                          <option key={ex.id} value={ex.id}>
                            {ex.name}
                          </option>
                        ))}
                      </select>
                    </div>
                  </div>
                </div>

                {/* Setup Table */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                  <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                      <h2 className="text-sm font-bold text-slate-800">
                        Subject Criteria: Std {selectedClass} &bull;{' '}
                        {exams.find((e) => String(e.id) === String(selectedExamForSetup))?.name}
                      </h2>
                      <p className="text-[11px] text-slate-400">
                        Specify the maximum score and minimum passing mark for each subject
                      </p>
                    </div>

                    {subjectsSetup.length > 0 && (
                      <button
                        onClick={handleSaveAllSetup}
                        disabled={saving}
                        className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50 transition"
                      >
                        <FiSave /> {saving ? 'Saving...' : 'Save All Subjects'}
                      </button>
                    )}
                  </div>

                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                          <th className="py-3 px-4 w-12 text-center">#</th>
                          <th className="py-3 px-4">Subject Name</th>
                          <th className="py-3 px-4 w-40 text-center">Total / Max Marks</th>
                          <th className="py-3 px-4 w-40 text-center">Passing Marks</th>
                          <th className="py-3 px-4 w-32 text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100">
                        {subjectsSetup.length === 0 ? (
                          <tr>
                            <td colSpan="5" className="py-8 text-center text-slate-400">
                              {loading ? 'Loading subjects...' : 'No subjects found for this standard.'}
                            </td>
                          </tr>
                        ) : (
                          subjectsSetup.map((sub, idx) => {
                            const config = setupForm[sub.id] || { totalMarks: 100, passingMarks: 35 };
                            return (
                              <tr key={sub.id} className="hover:bg-slate-50 transition-colors">
                                <td className="py-3 px-4 text-center text-slate-400">{idx + 1}</td>
                                <td className="py-3 px-4 font-semibold text-slate-800">{sub.name}</td>
                                <td className="py-2 px-4 text-center">
                                  <input
                                    type="number"
                                    min="1"
                                    max="500"
                                    value={config.totalMarks}
                                    onChange={(e) => handleSetupChange(sub.id, 'totalMarks', e.target.value)}
                                    className="w-24 text-center py-1.5 px-2 rounded border border-slate-300 text-xs font-bold outline-none focus:border-[#1976d2]"
                                  />
                                </td>
                                <td className="py-2 px-4 text-center">
                                  <input
                                    type="number"
                                    min="1"
                                    max="500"
                                    value={config.passingMarks}
                                    onChange={(e) => handleSetupChange(sub.id, 'passingMarks', e.target.value)}
                                    className="w-24 text-center py-1.5 px-2 rounded border border-slate-300 text-xs font-bold outline-none focus:border-[#1976d2]"
                                  />
                                </td>
                                <td className="py-3 px-4 text-center">
                                  <button
                                    onClick={() => handleSaveSingleSetup(sub.id)}
                                    className="px-3 py-1 text-[11px] font-semibold text-[#1976d2] hover:text-white hover:bg-[#1976d2] border border-[#1976d2] rounded transition"
                                  >
                                    Save
                                  </button>
                                </td>
                              </tr>
                            );
                          })
                        )}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
