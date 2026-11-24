import React, { useState, useEffect } from 'react';
import { Link, useLocation, useSearchParams } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import masterService from '../../services/masterService.js';
import { fetchStudents } from '../../services/studentService.js';
import {
  FiClock,
  FiAward,
  FiBriefcase,
  FiMapPin,
  FiAlertTriangle,
  FiBook,
  FiPlus,
  FiEdit2,
  FiTrash2,
  FiSearch,
  FiCheckCircle,
  FiAlertCircle,
  FiX,
  FiSliders,
  FiGrid,
  FiFileText,
  FiLock,
  FiBarChart2,
} from 'react-icons/fi';

const TABS = [
  { id: 'shifts', label: 'Shifts', icon: FiClock, code: 'stvshif' },
  { id: 'positions', label: 'Positions', icon: FiBriefcase, code: 'stvposn' },
  { id: 'grades', label: 'Grades', icon: FiAward, code: 'stvgrds' },
  { id: 'geo', label: 'States & Cities', icon: FiMapPin, code: 'stvstts' },
  { id: 'hold', label: 'Hold Reasons', icon: FiAlertTriangle, code: 'stvhold' },
  { id: 'hold-students', label: 'Hold Students', icon: FiLock, code: 'sprhold' },
  { id: 'colleges', label: 'Colleges', icon: FiGrid, code: 'stvcolg' },
  { id: 'higher-ed', label: 'Universities & Majors', icon: FiBook, code: 'stvuniv' },
  { id: 'update-std-div', label: 'Update Std / Division', icon: FiSliders, code: 'MasterForm/studentStdDiv' },
];

export default function MasterAuxiliaryPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const location = useLocation();
  const [searchParams, setSearchParams] = useSearchParams();
  const initialTab = searchParams.get('tab') || 'shifts';
  const [activeTab, setActiveTab] = useState(initialTab);

  const [loading, setLoading] = useState(false);
  const [feedback, setFeedback] = useState(null);
  const [searchQuery, setSearchQuery] = useState('');

  // Data states
  const [shifts, setShifts] = useState([]);
  const [positions, setPositions] = useState([]);
  const [grades, setGrades] = useState([]);
  const [states, setStates] = useState([]);
  const [cities, setCities] = useState([]);
  const [holdReasons, setHoldReasons] = useState([]);
  const [studentHolds, setStudentHolds] = useState([]);
  const [colleges, setColleges] = useState([]);
  const [universities, setUniversities] = useState([]);
  const [majors, setMajors] = useState([]);
  const [students, setStudents] = useState([]);

  // Auto-detect route mapping
  useEffect(() => {
    const p = location.pathname.toLowerCase();
    if (p.includes('stvcolg')) handleTabChange('colleges');
    else if (p.includes('sprhold')) handleTabChange('hold-students');
    else if (p.includes('stvcity') || p.includes('stvstts')) handleTabChange('geo');
    else if (p.includes('stvhold')) handleTabChange('hold');
    else if (p.includes('stvgrds')) handleTabChange('grades');
    else if (p.includes('stvposn') || p.includes('stvexpr')) handleTabChange('positions');
    else if (p.includes('stvshif')) handleTabChange('shifts');
    else if (p.includes('stvuniv') || p.includes('stvmajr')) handleTabChange('higher-ed');
    else if (p.includes('studentstddiv')) handleTabChange('update-std-div');
  }, [location.pathname]);

  // Update Class/Division state (CI3: MasterForm/studentStdDiv)
  const [stdDivSearchId, setStdDivSearchId] = useState('');
  const [stdDivStudentName, setStdDivStudentName] = useState('');
  const [stdDivRows, setStdDivRows] = useState([]);
  const [academicYears, setAcademicYears] = useState([]);
  const [stdDivLoading, setStdDivLoading] = useState(false);
  const [updatingRowKey, setUpdatingRowKey] = useState(null);

  useEffect(() => {
    masterService.getAcademicYears().then((res) => {
      if (res.data?.success) setAcademicYears(res.data.data || []);
    }).catch(() => {});
  }, []);

  const handleSearchStdDiv = async (e) => {
    if (e) e.preventDefault();
    if (!stdDivSearchId.trim()) {
      showFeedback('error', 'Please enter a Student ID');
      return;
    }
    setStdDivLoading(true);
    try {
      const res = await masterService.getStdDivDataByID(stdDivSearchId.trim());
      if (res.data?.success) {
        const rows = (res.data.data || []).map((r) => ({
          ...r,
          orig_standard: r.STVCLAS_standard,
          orig_class: r.STVCLAS_class,
          orig_acdm: r.STVCLAS_acdm,
        }));
        setStdDivRows(rows);
        setStdDivStudentName(rows[0]?.name || '');
        if (rows.length === 0) {
          showFeedback('error', `No enrollment records found for Student ID: ${stdDivSearchId}`);
        }
      }
    } catch (err) {
      showFeedback('error', err.response?.data?.message || 'Failed to fetch student details');
    } finally {
      setStdDivLoading(false);
    }
  };

  const handleUpdateStdDivRow = async (row) => {
    setUpdatingRowKey(row.STVCLAS_code);
    try {
      const res = await masterService.updateClassDetails({
        STVCLAS_student_id: row.STVCLAS_student_id,
        STVCLAS_code: row.STVCLAS_code,
        STVCLAS_standard: row.STVCLAS_standard,
        STVCLAS_class: row.STVCLAS_class,
        STVCLAS_acdm: row.STVCLAS_acdm,
        STVCLAS_acdm_old: row.orig_acdm || row.STVCLAS_acdm,
        old_standard: row.orig_standard || row.STVCLAS_standard,
        old_class: row.orig_class || row.STVCLAS_class,
      });
      if (res.data?.status || res.data?.success) {
        showFeedback('success', res.data.message || 'Student updated successfully.');
        setStdDivRows((prev) =>
          prev.map((r) =>
            r.STVCLAS_code === row.STVCLAS_code
              ? {
                  ...r,
                  orig_standard: r.STVCLAS_standard,
                  orig_class: r.STVCLAS_class,
                  orig_acdm: r.STVCLAS_acdm,
                }
              : r
          )
        );
      } else {
        showFeedback('error', res.data?.message || 'Update failed');
      }
    } catch (err) {
      showFeedback('error', err.response?.data?.message || 'Update failed');
    } finally {
      setUpdatingRowKey(null);
    }
  };

  // Sub-filter for cities
  const [selectedStateFilter, setSelectedStateFilter] = useState('');

  // Modal State
  const [modalOpen, setModalOpen] = useState(false);
  const [modalType, setModalType] = useState(''); // 'shift', 'position', 'grade', 'state', 'city', 'hold', 'univ', 'major'
  const [editingItem, setEditingItem] = useState(null);
  const [formData, setFormData] = useState({});

  useEffect(() => {
    loadTabData(activeTab);
  }, [activeTab]);

  const handleTabChange = (tabId) => {
    setActiveTab(tabId);
    setSearchParams({ tab: tabId });
    setSearchQuery('');
    setFeedback(null);
  };

  const loadTabData = async (tab) => {
    setLoading(true);
    try {
      if (tab === 'shifts') {
        const res = await masterService.getShifts();
        setShifts(res.data?.data || []);
      } else if (tab === 'positions') {
        const res = await masterService.getPositions();
        setPositions(res.data?.data || []);
      } else if (tab === 'grades') {
        const res = await masterService.getGrades();
        setGrades(res.data?.data || []);
      } else if (tab === 'geo') {
        const [stRes, ctRes] = await Promise.all([
          masterService.getStates(),
          masterService.getCities(selectedStateFilter || undefined),
        ]);
        setStates(stRes.data?.data || []);
        setCities(ctRes.data?.data || []);
      } else if (tab === 'hold') {
        const res = await masterService.getHoldReasons();
        setHoldReasons(res.data?.data || []);
      } else if (tab === 'higher-ed') {
        const [uRes, mRes] = await Promise.all([
          masterService.getUniversities(),
          masterService.getMajors(),
        ]);
        setUniversities(uRes.data?.data || []);
        setMajors(mRes.data?.data || []);
      } else if (tab === 'colleges') {
        const res = await masterService.getColleges();
        setColleges(res.data?.data || []);
      } else if (tab === 'hold-students') {
        const [hRes, rRes, sRes] = await Promise.all([
          masterService.getStudentHolds(),
          masterService.getHoldReasons(),
          fetchStudents({ limit: 500 }).catch(() => ({ data: [] })),
        ]);
        setStudentHolds(hRes.data?.data || []);
        setHoldReasons(rRes.data?.data || []);
        const sList = sRes.data?.students || sRes.data || (Array.isArray(sRes) ? sRes : []);
        setStudents(Array.isArray(sList) ? sList : []);
      }
    } catch (err) {
      console.error('Failed to load master data:', err);
      setFeedback({ type: 'error', text: 'Failed to load master records.' });
    } finally {
      setLoading(false);
    }
  };

  const openModal = (type, item = null) => {
    setModalType(type);
    setEditingItem(item);
    if (item) {
      setFormData({ ...item });
    } else {
      setFormData({});
    }
    setModalOpen(true);
  };

  const closeModal = () => {
    setModalOpen(false);
    setEditingItem(null);
    setFormData({});
  };

  const handleSave = async (e) => {
    e.preventDefault();
    try {
      if (modalType === 'shift') {
        if (editingItem) {
          await masterService.updateShift(editingItem.id, {
            code: formData.code,
            startTime: formData.start_time,
            endTime: formData.end_time,
          });
        } else {
          await masterService.createShift({
            code: formData.code,
            startTime: formData.start_time,
            endTime: formData.end_time,
          });
        }
      } else if (modalType === 'position') {
        if (editingItem) {
          await masterService.updatePosition(editingItem.id, {
            code: formData.code,
            name: formData.name,
          });
        } else {
          await masterService.createPosition({
            code: formData.code,
            name: formData.name,
          });
        }
      } else if (modalType === 'grade') {
        if (editingItem) {
          await masterService.updateGrade(editingItem.id, {
            grade: formData.grade,
            point: formData.point,
            marksFrom: formData.marks_from,
            marksTo: formData.marks_to,
            notes: formData.notes,
          });
        } else {
          await masterService.createGrade({
            grade: formData.grade,
            point: formData.point,
            marksFrom: formData.marks_from,
            marksTo: formData.marks_to,
            notes: formData.notes,
          });
        }
      } else if (modalType === 'state') {
        if (editingItem) {
          await masterService.updateState(editingItem.id, {
            stateCode: formData.state_code,
            stateName: formData.state_name,
          });
        } else {
          await masterService.createState({
            stateCode: formData.state_code,
            stateName: formData.state_name,
          });
        }
      } else if (modalType === 'city') {
        if (editingItem) {
          await masterService.updateCity(editingItem.id, {
            cityCode: formData.city_code,
            cityName: formData.city_name,
            stateCode: formData.state_code,
          });
        } else {
          await masterService.createCity({
            cityCode: formData.city_code,
            cityName: formData.city_name,
            stateCode: formData.state_code,
          });
        }
      } else if (modalType === 'hold') {
        if (editingItem) {
          await masterService.updateHoldReason(editingItem.id, { type: formData.type });
        } else {
          await masterService.createHoldReason({ type: formData.type });
        }
      } else if (modalType === 'univ') {
        if (editingItem) {
          await masterService.updateUniversity(editingItem.id, formData);
        } else {
          await masterService.createUniversity(formData);
        }
      } else if (modalType === 'major') {
        if (editingItem) {
          await masterService.updateMajor(editingItem.id, formData);
        } else {
          await masterService.createMajor(formData);
        }
      } else if (modalType === 'college') {
        if (editingItem) {
          await masterService.updateCollege(editingItem.id, formData);
        } else {
          await masterService.createCollege(formData);
        }
      } else if (modalType === 'student-hold') {
        await masterService.createStudentHold(formData);
      }

      setFeedback({ type: 'success', text: 'Saved successfully!' });
      closeModal();
      loadTabData(activeTab);
    } catch (err) {
      console.error('Save error:', err);
      setFeedback({ type: 'error', text: err.response?.data?.message || 'Failed to save record.' });
    }
  };

  const handleDelete = async (type, id, label) => {
    if (!window.confirm(`Are you sure you want to delete "${label}"?`)) return;
    try {
      if (type === 'shift') await masterService.deleteShift(id);
      else if (type === 'position') await masterService.deletePosition(id);
      else if (type === 'grade') await masterService.deleteGrade(id);
      else if (type === 'state') await masterService.deleteState(id);
      else if (type === 'city') await masterService.deleteCity(id);
      else if (type === 'hold') await masterService.deleteHoldReason(id);
      else if (type === 'univ') await masterService.deleteUniversity(id);
      else if (type === 'major') await masterService.deleteMajor(id);
      else if (type === 'college') await masterService.deleteCollege(id);
      else if (type === 'student-hold') await masterService.deleteStudentHold(id);

      setFeedback({ type: 'success', text: `Deleted "${label}" successfully.` });
      loadTabData(activeTab);
    } catch (err) {
      setFeedback({ type: 'error', text: 'Failed to delete record.' });
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />
        <main className="p-6 max-w-7xl mx-auto w-full">
          {/* Header & Subnav Bar */}
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
              <h1 className="text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <FiSliders className="text-[#1976d2]" />
                Auxiliary Master Data
              </h1>
              <p className="text-xs text-slate-500 mt-0.5">
                Configure school shifts, teacher designations, grading scales, locations, and university metadata
              </p>
            </div>

            {/* Quick Links Nav */}
            <div className="flex items-center gap-1.5 bg-white p-1 rounded-xl border border-slate-200 shadow-sm text-xs font-semibold">
              <Link
                to="/master/modules"
                className="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-1.5"
              >
                <FiGrid size={13} />
                <span>Modules</span>
              </Link>
              <Link
                to="/master/forms"
                className="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-1.5"
              >
                <FiFileText size={13} />
                <span>Forms</span>
              </Link>
              <Link
                to="/master/user-access"
                className="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 flex items-center gap-1.5"
              >
                <FiLock size={13} />
                <span>User Access</span>
              </Link>
              <Link
                to="/master/auxiliary"
                className="px-3 py-1.5 rounded-lg bg-[#1976d2] text-white shadow-sm flex items-center gap-1.5"
              >
                <FiSliders size={13} />
                <span>Master Data</span>
              </Link>
            </div>
          </div>

          {/* Feedback Alert */}
          {feedback && (
            <div
              className={`p-4 rounded-xl mb-6 flex items-start justify-between border ${
                feedback.type === 'success'
                  ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                  : 'bg-rose-50 border-rose-200 text-rose-800'
              }`}
            >
              <div className="flex items-center gap-2 text-xs font-semibold">
                {feedback.type === 'success' ? (
                  <FiCheckCircle className="shrink-0 text-emerald-600" size={16} />
                ) : (
                  <FiAlertCircle className="shrink-0 text-rose-600" size={16} />
                )}
                <span>{feedback.text}</span>
              </div>
              <button
                onClick={() => setFeedback(null)}
                className="text-slate-400 hover:text-slate-600 cursor-pointer"
              >
                <FiX size={14} />
              </button>
            </div>
          )}

          {/* Tabs Bar */}
          <div className="flex flex-wrap items-center gap-2 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm mb-6">
            {TABS.map((tab) => {
              const Icon = tab.icon;
              const isActive = activeTab === tab.id;
              return (
                <button
                  key={tab.id}
                  onClick={() => handleTabChange(tab.id)}
                  className={`flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer ${
                    isActive
                      ? 'bg-[#1976d2] text-white shadow'
                      : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
                  }`}
                >
                  <Icon size={14} />
                  <span>{tab.label}</span>
                  <span
                    className={`text-[10px] px-1.5 py-0.2 rounded-md font-mono ${
                      isActive ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600'
                    }`}
                  >
                    {tab.code}
                  </span>
                </button>
              );
            })}
          </div>

          {/* TAB 1: SHIFTS */}
          {activeTab === 'shifts' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">Shift Timings Master (stvshif)</h2>
                  <p className="text-xs text-slate-500">Manage daily operational school and faculty shifts</p>
                </div>
                <button
                  onClick={() => openModal('shift')}
                  className="px-3.5 py-2 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={14} /> Add Shift
                </button>
              </div>

              <div className="overflow-x-auto">

                <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <th className="py-3 px-4 w-16 text-center">ID</th>
                    <th className="py-3 px-4">Shift Code / Name</th>
                    <th className="py-3 px-4">Start Time</th>
                    <th className="py-3 px-4">End Time</th>
                    <th className="py-3 px-4 text-center w-28">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium">
                  {loading ? (
                    <tr><td colSpan="5" className="py-8 text-center text-slate-400">Loading shifts...</td></tr>
                  ) : shifts.length === 0 ? (
                    <tr><td colSpan="5" className="py-8 text-center text-slate-400">No shifts recorded yet. Click "Add Shift" to create one.</td></tr>
                  ) : (
                    shifts.map((s) => (
                      <tr key={s.id} className="hover:bg-slate-50/80">
                        <td className="py-3 px-4 text-center font-mono text-slate-500">{s.id}</td>
                        <td className="py-3 px-4 font-bold text-slate-900">{s.code}</td>
                        <td className="py-3 px-4 font-mono text-emerald-700 font-semibold">{s.start_time || '—'}</td>
                        <td className="py-3 px-4 font-mono text-rose-700 font-semibold">{s.end_time || '—'}</td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1">
                            <button
                              onClick={() => openModal('shift', s)}
                              className="p-1.5 text-slate-500 hover:text-[#1976d2] rounded-lg hover:bg-[#e3f2fd] cursor-pointer"
                            >
                              <FiEdit2 size={13} />
                            </button>
                            <button
                              onClick={() => handleDelete('shift', s.id, s.code)}
                              className="p-1.5 text-slate-500 hover:text-rose-600 rounded-lg hover:bg-rose-50 cursor-pointer"
                            >
                              <FiTrash2 size={13} />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>

              </div>
            </div>
          )}

          {/* TAB 2: POSITIONS */}
          {activeTab === 'positions' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">Positions & Designations (stvposn)</h2>
                  <p className="text-xs text-slate-500">Staff roles, administrative positions, and designations</p>
                </div>
                <button
                  onClick={() => openModal('position')}
                  className="px-3.5 py-2 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={14} /> Add Position
                </button>
              </div>

              <div className="overflow-x-auto">

                <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <th className="py-3 px-4 w-16 text-center">ID</th>
                    <th className="py-3 px-4">Position Code</th>
                    <th className="py-3 px-4">Position Name</th>
                    <th className="py-3 px-4 text-center w-28">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium">
                  {loading ? (
                    <tr><td colSpan="4" className="py-8 text-center text-slate-400">Loading positions...</td></tr>
                  ) : positions.length === 0 ? (
                    <tr><td colSpan="4" className="py-8 text-center text-slate-400">No positions configured yet.</td></tr>
                  ) : (
                    positions.map((p) => (
                      <tr key={p.id} className="hover:bg-slate-50/80">
                        <td className="py-3 px-4 text-center font-mono text-slate-500">{p.id}</td>
                        <td className="py-3 px-4 font-mono font-bold text-slate-800">{p.code}</td>
                        <td className="py-3 px-4 font-bold text-slate-900">{p.name}</td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1">
                            <button
                              onClick={() => openModal('position', p)}
                              className="p-1.5 text-slate-500 hover:text-[#1976d2] rounded-lg hover:bg-[#e3f2fd] cursor-pointer"
                            >
                              <FiEdit2 size={13} />
                            </button>
                            <button
                              onClick={() => handleDelete('position', p.id, p.name)}
                              className="p-1.5 text-slate-500 hover:text-rose-600 rounded-lg hover:bg-rose-50 cursor-pointer"
                            >
                              <FiTrash2 size={13} />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>

              </div>
            </div>
          )}

          {/* TAB 3: GRADES */}
          {activeTab === 'grades' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">Grading System & Evaluation (stvgrds)</h2>
                  <p className="text-xs text-slate-500">Define academic letter grades, GPA points, and percentage thresholds</p>
                </div>
                <button
                  onClick={() => openModal('grade')}
                  className="px-3.5 py-2 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={14} /> Add Grade
                </button>
              </div>

              <div className="overflow-x-auto">

                <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <th className="py-3 px-4 w-16 text-center">ID</th>
                    <th className="py-3 px-4">Grade</th>
                    <th className="py-3 px-4 text-center">Grade Point</th>
                    <th className="py-3 px-4 text-center">Marks Range</th>
                    <th className="py-3 px-4">Description / Notes</th>
                    <th className="py-3 px-4 text-center w-28">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium">
                  {loading ? (
                    <tr><td colSpan="6" className="py-8 text-center text-slate-400">Loading grades...</td></tr>
                  ) : grades.length === 0 ? (
                    <tr><td colSpan="6" className="py-8 text-center text-slate-400">No grades registered yet.</td></tr>
                  ) : (
                    grades.map((g) => (
                      <tr key={g.id} className="hover:bg-slate-50/80">
                        <td className="py-3 px-4 text-center font-mono text-slate-500">{g.id}</td>
                        <td className="py-3 px-4 font-extrabold text-slate-900">
                          <span className="px-2.5 py-1 rounded-md bg-[#e3f2fd] text-[#1976d2] border border-[#bbdefb]">
                            {g.grade}
                          </span>
                        </td>
                        <td className="py-3 px-4 text-center font-mono font-bold text-slate-700">{g.point || '—'}</td>
                        <td className="py-3 px-4 text-center font-mono">
                          {g.marks_from} - {g.marks_to} %
                        </td>
                        <td className="py-3 px-4 text-slate-600">{g.notes || '—'}</td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1">
                            <button
                              onClick={() => openModal('grade', g)}
                              className="p-1.5 text-slate-500 hover:text-[#1976d2] rounded-lg hover:bg-[#e3f2fd] cursor-pointer"
                            >
                              <FiEdit2 size={13} />
                            </button>
                            <button
                              onClick={() => handleDelete('grade', g.id, g.grade)}
                              className="p-1.5 text-slate-500 hover:text-rose-600 rounded-lg hover:bg-rose-50 cursor-pointer"
                            >
                              <FiTrash2 size={13} />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>

              </div>
            </div>
          )}

          {/* TAB 4: STATES & CITIES */}
          {activeTab === 'geo' && (
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* States Table */}
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                  <div>
                    <h2 className="text-sm font-extrabold text-slate-900">States Master (stvstts)</h2>
                    <p className="text-xs text-slate-500">{states.length} states registered</p>
                  </div>
                  <button
                    onClick={() => openModal('state')}
                    className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                  >
                    <FiPlus size={13} /> Add State
                  </button>
                </div>
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">ID</th>
                      <th className="py-2.5 px-3">State Code</th>
                      <th className="py-2.5 px-3">State Name</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {states.length === 0 ? (
                      <tr><td colSpan="4" className="py-6 text-center text-slate-400">No states recorded yet.</td></tr>
                    ) : (
                      states.map((st) => (
                        <tr key={st.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{st.id}</td>
                          <td className="py-2.5 px-3 font-mono font-bold text-slate-800">{st.state_code}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{st.state_name}</td>
                          <td className="py-2.5 px-3 text-center">
                            <div className="flex items-center justify-center gap-1">
                              <button
                                onClick={() => openModal('state', st)}
                                className="p-1 text-slate-500 hover:text-[#1976d2] rounded cursor-pointer"
                              >
                                <FiEdit2 size={12} />
                              </button>
                              <button
                                onClick={() => handleDelete('state', st.id, st.state_name)}
                                className="p-1 text-slate-500 hover:text-rose-600 rounded cursor-pointer"
                              >
                                <FiTrash2 size={12} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
                </div>
              </div>

              {/* Cities Table */}
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                  <div>
                    <h2 className="text-sm font-extrabold text-slate-900">Cities Master (stvcity)</h2>
                    <p className="text-xs text-slate-500">{cities.length} cities mapped to states</p>
                  </div>
                  <button
                    onClick={() => openModal('city')}
                    className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                  >
                    <FiPlus size={13} /> Add City
                  </button>
                </div>
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">ID</th>
                      <th className="py-2.5 px-3">City Code</th>
                      <th className="py-2.5 px-3">City Name</th>
                      <th className="py-2.5 px-3">State Code</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {cities.length === 0 ? (
                      <tr><td colSpan="5" className="py-6 text-center text-slate-400">No cities recorded yet.</td></tr>
                    ) : (
                      cities.map((ct) => (
                        <tr key={ct.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{ct.id}</td>
                          <td className="py-2.5 px-3 font-mono font-bold text-slate-800">{ct.city_code}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{ct.city_name}</td>
                          <td className="py-2.5 px-3 font-mono text-slate-600">{ct.state_code}</td>
                          <td className="py-2.5 px-3 text-center">
                            <div className="flex items-center justify-center gap-1">
                              <button
                                onClick={() => openModal('city', ct)}
                                className="p-1 text-slate-500 hover:text-[#1976d2] rounded cursor-pointer"
                              >
                                <FiEdit2 size={12} />
                              </button>
                              <button
                                onClick={() => handleDelete('city', ct.id, ct.city_name)}
                                className="p-1 text-slate-500 hover:text-rose-600 rounded cursor-pointer"
                              >
                                <FiTrash2 size={12} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
                </div>
              </div>
            </div>
          )}

          {/* TAB 7: Colleges (stvcolg / stvcoll) */}
          {activeTab === 'colleges' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">College Master (stvcolg)</h2>
                  <p className="text-xs text-slate-500">Colleges and junior institutions affiliated with Olive School</p>
                </div>
                <button
                  onClick={() => openModal('college')}
                  className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={13} /> Add College
                </button>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">Code</th>
                      <th className="py-2.5 px-3">College Name</th>
                      <th className="py-2.5 px-3">Location</th>
                      <th className="py-2.5 px-3">Contact</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {colleges.length === 0 ? (
                      <tr><td colSpan="5" className="py-6 text-center text-slate-400">No colleges configured yet.</td></tr>
                    ) : (
                      colleges.map((c) => (
                        <tr key={c.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{c.id}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{c.name}</td>
                          <td className="py-2.5 px-3 text-slate-600">{[c.city, c.state].filter(Boolean).join(', ') || '—'}</td>
                          <td className="py-2.5 px-3 text-slate-600 font-mono text-[11px]">{[c.phone, c.email].filter(Boolean).join(' · ') || '—'}</td>
                          <td className="py-2.5 px-3 text-center">
                            <div className="flex items-center justify-center gap-1">
                              <button
                                onClick={() => openModal('college', c)}
                                className="p-1 text-slate-500 hover:text-[#1976d2] rounded cursor-pointer"
                              >
                                <FiEdit2 size={12} />
                              </button>
                              <button
                                onClick={() => handleDelete('college', c.id, c.name)}
                                className="p-1 text-slate-500 hover:text-rose-600 rounded cursor-pointer"
                              >
                                <FiTrash2 size={12} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 8: Student Active Holds (sprhold) */}
          {activeTab === 'hold-students' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">Hold Students (sprhold)</h2>
                  <p className="text-xs text-slate-500">Active administrative and academic holds on student profiles</p>
                </div>
                <button
                  onClick={() => openModal('student-hold')}
                  className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={13} /> Apply Student Hold
                </button>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">#</th>
                      <th className="py-2.5 px-3">Student ID</th>
                      <th className="py-2.5 px-3">Student Name</th>
                      <th className="py-2.5 px-3">Hold Reason</th>
                      <th className="py-2.5 px-3">Hold Period</th>
                      <th className="py-2.5 px-3 text-center">Status</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {studentHolds.length === 0 ? (
                      <tr><td colSpan="7" className="py-6 text-center text-slate-400">No active student holds recorded.</td></tr>
                    ) : (
                      studentHolds.map((h, idx) => (
                        <tr key={h.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{idx + 1}</td>
                          <td className="py-2.5 px-3 font-mono font-bold text-slate-700">{h.SPRIDEN_student_id}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{h.student_name}</td>
                          <td className="py-2.5 px-3">
                            <span className="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold border border-amber-200 text-[11px]">
                              {h.hold_reason || 'Administrative Hold'}
                            </span>
                          </td>
                          <td className="py-2.5 px-3 text-slate-600 text-[11px]">
                            {h.hold_from ? new Date(h.hold_from).toLocaleDateString() : 'Immediate'} &rarr; {h.hold_to ? new Date(h.hold_to).toLocaleDateString() : 'Indefinite'}
                          </td>
                          <td className="py-2.5 px-3 text-center">
                            <span className="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[10px] uppercase">
                              {h.status || 'Active'}
                            </span>
                          </td>
                          <td className="py-2.5 px-3 text-center">
                            <button
                              onClick={() => handleDelete('student-hold', h.id, `${h.student_name} hold`)}
                              className="px-2 py-1 text-rose-600 hover:bg-rose-50 font-bold rounded text-[11px] transition cursor-pointer"
                              title="Release Hold"
                            >
                              Release
                            </button>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 5: HOLD REASONS */}
          {activeTab === 'hold' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">Hold Reasons Master (stvhold)</h2>
                  <p className="text-xs text-slate-500">Predefined categories for placing student admissions or results on administrative hold</p>
                </div>
                <button
                  onClick={() => openModal('hold')}
                  className="px-3.5 py-2 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={14} /> Add Hold Reason
                </button>
              </div>

              <div className="overflow-x-auto">

                <table className="w-full text-left border-collapse text-xs">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                    <th className="py-3 px-4 w-16 text-center">ID</th>
                    <th className="py-3 px-4">Hold Reason Category</th>
                    <th className="py-3 px-4 text-center w-28">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 font-medium">
                  {loading ? (
                    <tr><td colSpan="3" className="py-8 text-center text-slate-400">Loading hold reasons...</td></tr>
                  ) : holdReasons.length === 0 ? (
                    <tr><td colSpan="3" className="py-8 text-center text-slate-400">No hold reasons defined yet.</td></tr>
                  ) : (
                    holdReasons.map((h) => (
                      <tr key={h.id} className="hover:bg-slate-50/80">
                        <td className="py-3 px-4 text-center font-mono text-slate-500">{h.id}</td>
                        <td className="py-3 px-4 font-bold text-slate-900">{h.type}</td>
                        <td className="py-3 px-4 text-center">
                          <div className="flex items-center justify-center gap-1">
                            <button
                              onClick={() => openModal('hold', h)}
                              className="p-1.5 text-slate-500 hover:text-[#1976d2] rounded-lg hover:bg-[#e3f2fd] cursor-pointer"
                            >
                              <FiEdit2 size={13} />
                            </button>
                            <button
                              onClick={() => handleDelete('hold', h.id, h.type)}
                              className="p-1.5 text-slate-500 hover:text-rose-600 rounded-lg hover:bg-rose-50 cursor-pointer"
                            >
                              <FiTrash2 size={13} />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>

              </div>
            </div>
          )}

          {/* TAB 6: UNIVERSITIES & MAJORS */}
          {activeTab === 'higher-ed' && (
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Universities Table */}
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                  <div>
                    <h2 className="text-sm font-extrabold text-slate-900">Universities (stvuniv)</h2>
                    <p className="text-xs text-slate-500">Degree-awarding institutions for staff qualifications</p>
                  </div>
                  <button
                    onClick={() => openModal('univ')}
                    className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                  >
                    <FiPlus size={13} /> Add University
                  </button>
                </div>
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">Code</th>
                      <th className="py-2.5 px-3">University Name</th>
                      <th className="py-2.5 px-3">Location</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {universities.length === 0 ? (
                      <tr><td colSpan="4" className="py-6 text-center text-slate-400">No universities recorded yet.</td></tr>
                    ) : (
                      universities.map((u) => (
                        <tr key={u.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{u.id}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{u.name}</td>
                          <td className="py-2.5 px-3 text-slate-600">{[u.city, u.state].filter(Boolean).join(', ') || '—'}</td>
                          <td className="py-2.5 px-3 text-center">
                            <div className="flex items-center justify-center gap-1">
                              <button
                                onClick={() => openModal('univ', u)}
                                className="p-1 text-slate-500 hover:text-[#1976d2] rounded cursor-pointer"
                              >
                                <FiEdit2 size={12} />
                              </button>
                              <button
                                onClick={() => handleDelete('univ', u.id, u.name)}
                                className="p-1 text-slate-500 hover:text-rose-600 rounded cursor-pointer"
                              >
                                <FiTrash2 size={12} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
                </div>
              </div>

              {/* Majors Table */}
              <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                  <div>
                    <h2 className="text-sm font-extrabold text-slate-900">Majors & Disciplines (stvmajr)</h2>
                    <p className="text-xs text-slate-500">Degree specializations (e.g., Mathematics, B.Ed, M.Sc)</p>
                  </div>
                  <button
                    onClick={() => openModal('major')}
                    className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                  >
                    <FiPlus size={13} /> Add Major
                  </button>
                </div>
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">Code</th>
                      <th className="py-2.5 px-3">Major / Discipline</th>
                      <th className="py-2.5 px-3">Details</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {majors.length === 0 ? (
                      <tr><td colSpan="4" className="py-6 text-center text-slate-400">No majors configured yet.</td></tr>
                    ) : (
                      majors.map((m) => (
                        <tr key={m.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{m.id}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{m.name}</td>
                          <td className="py-2.5 px-3 text-slate-600">{m.details || '—'}</td>
                          <td className="py-2.5 px-3 text-center">
                            <div className="flex items-center justify-center gap-1">
                              <button
                                onClick={() => openModal('major', m)}
                                className="p-1 text-slate-500 hover:text-[#1976d2] rounded cursor-pointer"
                              >
                                <FiEdit2 size={12} />
                              </button>
                              <button
                                onClick={() => handleDelete('major', m.id, m.name)}
                                className="p-1 text-slate-500 hover:text-rose-600 rounded cursor-pointer"
                              >
                                <FiTrash2 size={12} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
                </div>
              </div>
            </div>
          )}

          {/* TAB 7: Colleges (stvcolg / stvcoll) */}
          {activeTab === 'colleges' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">College Master (stvcolg)</h2>
                  <p className="text-xs text-slate-500">Colleges and junior institutions affiliated with Olive School</p>
                </div>
                <button
                  onClick={() => openModal('college')}
                  className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={13} /> Add College
                </button>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">Code</th>
                      <th className="py-2.5 px-3">College Name</th>
                      <th className="py-2.5 px-3">Location</th>
                      <th className="py-2.5 px-3">Contact</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {colleges.length === 0 ? (
                      <tr><td colSpan="5" className="py-6 text-center text-slate-400">No colleges configured yet.</td></tr>
                    ) : (
                      colleges.map((c) => (
                        <tr key={c.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{c.id}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{c.name}</td>
                          <td className="py-2.5 px-3 text-slate-600">{[c.city, c.state].filter(Boolean).join(', ') || '—'}</td>
                          <td className="py-2.5 px-3 text-slate-600 font-mono text-[11px]">{[c.phone, c.email].filter(Boolean).join(' · ') || '—'}</td>
                          <td className="py-2.5 px-3 text-center">
                            <div className="flex items-center justify-center gap-1">
                              <button
                                onClick={() => openModal('college', c)}
                                className="p-1 text-slate-500 hover:text-[#1976d2] rounded cursor-pointer"
                              >
                                <FiEdit2 size={12} />
                              </button>
                              <button
                                onClick={() => handleDelete('college', c.id, c.name)}
                                className="p-1 text-slate-500 hover:text-rose-600 rounded cursor-pointer"
                              >
                                <FiTrash2 size={12} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 8: Student Active Holds (sprhold) */}
          {activeTab === 'hold-students' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">Hold Students (sprhold)</h2>
                  <p className="text-xs text-slate-500">Active administrative and academic holds on student profiles</p>
                </div>
                <button
                  onClick={() => openModal('student-hold')}
                  className="px-3 py-1.5 bg-[#1976d2] text-white rounded-xl text-xs font-bold flex items-center gap-1 shadow hover:bg-[#1565c0] transition cursor-pointer"
                >
                  <FiPlus size={13} /> Apply Student Hold
                </button>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-16 text-center">#</th>
                      <th className="py-2.5 px-3">Student ID</th>
                      <th className="py-2.5 px-3">Student Name</th>
                      <th className="py-2.5 px-3">Hold Reason</th>
                      <th className="py-2.5 px-3">Hold Period</th>
                      <th className="py-2.5 px-3 text-center">Status</th>
                      <th className="py-2.5 px-3 text-center w-24">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {studentHolds.length === 0 ? (
                      <tr><td colSpan="7" className="py-6 text-center text-slate-400">No active student holds recorded.</td></tr>
                    ) : (
                      studentHolds.map((h, idx) => (
                        <tr key={h.id} className="hover:bg-slate-50/80">
                          <td className="py-2.5 px-3 text-center font-mono text-slate-500">{idx + 1}</td>
                          <td className="py-2.5 px-3 font-mono font-bold text-slate-700">{h.SPRIDEN_student_id}</td>
                          <td className="py-2.5 px-3 font-bold text-slate-900">{h.student_name}</td>
                          <td className="py-2.5 px-3">
                            <span className="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold border border-amber-200 text-[11px]">
                              {h.hold_reason || 'Administrative Hold'}
                            </span>
                          </td>
                          <td className="py-2.5 px-3 text-slate-600 text-[11px]">
                            {h.hold_from ? new Date(h.hold_from).toLocaleDateString() : 'Immediate'} &rarr; {h.hold_to ? new Date(h.hold_to).toLocaleDateString() : 'Indefinite'}
                          </td>
                          <td className="py-2.5 px-3 text-center">
                            <span className="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[10px] uppercase">
                              {h.status || 'Active'}
                            </span>
                          </td>
                          <td className="py-2.5 px-3 text-center">
                            <button
                              onClick={() => handleDelete('student-hold', h.id, `${h.student_name} hold`)}
                              className="px-2 py-1 text-rose-600 hover:bg-rose-50 font-bold rounded text-[11px] transition cursor-pointer"
                              title="Release Hold"
                            >
                              Release
                            </button>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* TAB 8: Update Class or Division (CI3: MasterForm/studentStdDiv) */}
          {activeTab === 'update-std-div' && (
            <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden space-y-4 p-5">
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                  <h2 className="text-sm font-extrabold text-slate-900">Update Class or Division (MasterForm/studentStdDiv)</h2>
                  <p className="text-xs text-slate-500">Correct standard, division, or academic session allocations for registered students</p>
                </div>
                {stdDivStudentName && (
                  <div className="px-3 py-1.5 bg-[#e3f2fd] border border-[#bbdefb] text-[#1976d2] rounded-xl text-xs font-bold">
                    Student: {stdDivStudentName}
                  </div>
                )}
              </div>

              {/* Student Lookup Search Bar */}
              <form onSubmit={handleSearchStdDiv} className="flex flex-wrap items-center gap-3">
                <div className="w-full sm:w-72 relative">
                  <input
                    type="text"
                    value={stdDivSearchId}
                    onChange={(e) => setStdDivSearchId(e.target.value)}
                    placeholder="Enter Student ID (e.g. 1001)..."
                    className="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#1976d2] focus:bg-white transition"
                  />
                  <FiSearch className="absolute left-3 top-2.5 text-slate-400" size={14} />
                </div>
                <button
                  type="submit"
                  disabled={stdDivLoading}
                  className="px-4 py-2 bg-[#1976d2] hover:bg-[#1565c0] text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-sm disabled:opacity-50"
                >
                  {stdDivLoading ? 'Searching...' : 'View Allocations'}
                </button>
              </form>

              {/* Allocations Table */}
              <div className="overflow-x-auto rounded-xl border border-slate-200">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px]">
                      <th className="py-2.5 px-3 w-12 text-center">#</th>
                      <th className="py-2.5 px-3 w-28">Student ID</th>
                      <th className="py-2.5 px-3">Standard (Std)</th>
                      <th className="py-2.5 px-3">Division (Div)</th>
                      <th className="py-2.5 px-3">Academic Year</th>
                      <th className="py-2.5 px-3 text-center w-28">Action</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {stdDivRows.length === 0 ? (
                      <tr>
                        <td colSpan="6" className="py-8 text-center text-slate-400">
                          {stdDivLoading ? 'Fetching student records...' : 'Enter a Student ID above and click "View Allocations" to inspect and update class assignments.'}
                        </td>
                      </tr>
                    ) : (
                      stdDivRows.map((r, idx) => {
                        const isStdChanged = r.STVCLAS_standard !== r.orig_standard;
                        const isDivChanged = r.STVCLAS_class !== r.orig_class;
                        const isYearChanged = String(r.STVCLAS_acdm) !== String(r.orig_acdm);
                        const isModified = isStdChanged || isDivChanged || isYearChanged;
                        const isSaving = updatingRowKey === r.STVCLAS_code;

                        return (
                          <tr key={r.STVCLAS_code || idx} className="hover:bg-slate-50/80">
                            <td className="py-2.5 px-3 text-center font-mono text-slate-400">{idx + 1}</td>
                            <td className="py-2.5 px-3 font-mono font-bold text-slate-700">{r.SPRIDEN_student_id}</td>
                            <td className="py-2 px-3">
                              <input
                                type="text"
                                value={r.STVCLAS_standard || ''}
                                onChange={(e) => {
                                  const val = e.target.value;
                                  setStdDivRows((prev) =>
                                    prev.map((row, i) => (i === idx ? { ...row, STVCLAS_standard: val } : row))
                                  );
                                }}
                                className={`w-28 px-2.5 py-1.5 rounded-lg border text-xs font-semibold focus:outline-none ${
                                  isStdChanged ? 'border-[#1976d2] bg-blue-50/50' : 'border-slate-200 bg-white'
                                }`}
                              />
                            </td>
                            <td className="py-2 px-3">
                              <input
                                type="text"
                                value={r.STVCLAS_class || ''}
                                onChange={(e) => {
                                  const val = e.target.value;
                                  setStdDivRows((prev) =>
                                    prev.map((row, i) => (i === idx ? { ...row, STVCLAS_class: val } : row))
                                  );
                                }}
                                className={`w-24 px-2.5 py-1.5 rounded-lg border text-xs font-semibold focus:outline-none ${
                                  isDivChanged ? 'border-[#1976d2] bg-blue-50/50' : 'border-slate-200 bg-white'
                                }`}
                              />
                            </td>
                            <td className="py-2 px-3">
                              <select
                                value={r.STVCLAS_acdm || ''}
                                onChange={(e) => {
                                  const val = e.target.value;
                                  setStdDivRows((prev) =>
                                    prev.map((row, i) => (i === idx ? { ...row, STVCLAS_acdm: val } : row))
                                  );
                                }}
                                className={`px-2.5 py-1.5 rounded-lg border text-xs font-semibold focus:outline-none ${
                                  isYearChanged ? 'border-[#1976d2] bg-blue-50/50' : 'border-slate-200 bg-white'
                                }`}
                              >
                                {academicYears.map((ay) => (
                                  <option key={ay.stvacdm_id} value={ay.stvacdm_id}>
                                    {ay.stvacdm_year}
                                  </option>
                                ))}
                              </select>
                            </td>
                            <td className="py-2 px-3 text-center">
                              <button
                                type="button"
                                disabled={!isModified || isSaving}
                                onClick={() => handleUpdateStdDivRow(r)}
                                className={`px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-sm ${
                                  isModified
                                    ? 'bg-[#1976d2] text-white hover:bg-[#1565c0] cursor-pointer'
                                    : 'bg-slate-100 text-slate-400 cursor-not-allowed'
                                }`}
                              >
                                {isSaving ? 'Updating...' : 'Update'}
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
          )}

          {/* Unified Create / Edit Modal */}
          {modalOpen && (
            <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4">
              <div className="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                <div className="p-5 border-b border-slate-100 flex items-center justify-between">
                  <h3 className="font-extrabold text-sm text-slate-900">
                    {editingItem ? 'Edit' : 'Add New'}{' '}
                    {modalType === 'shift' && 'Shift'}
                    {modalType === 'position' && 'Position'}
                    {modalType === 'grade' && 'Grade'}
                    {modalType === 'state' && 'State'}
                    {modalType === 'city' && 'City'}
                    {modalType === 'hold' && 'Hold Reason'}
                    {modalType === 'univ' && 'University'}
                    {modalType === 'major' && 'Major'}
                    {modalType === 'college' && 'College'}
                    {modalType === 'student-hold' && 'Student Hold'}
                  </h3>
                  <button onClick={closeModal} className="text-slate-400 hover:text-slate-600 cursor-pointer">
                    <FiX size={16} />
                  </button>
                </div>

                <form onSubmit={handleSave} className="p-5 space-y-4 text-xs font-medium">
                  {/* SHIFT FIELDS */}
                  {modalType === 'shift' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">Shift Code / Title *</label>
                        <input
                          type="text"
                          required
                          value={formData.code || ''}
                          onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. Morning Shift"
                        />
                      </div>
                      <div className="grid grid-cols-2 gap-3">
                        <div>
                          <label className="block text-slate-600 mb-1">Start Time</label>
                          <input
                            type="time"
                            value={formData.start_time || ''}
                            onChange={(e) => setFormData({ ...formData, start_time: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          />
                        </div>
                        <div>
                          <label className="block text-slate-600 mb-1">End Time</label>
                          <input
                            type="time"
                            value={formData.end_time || ''}
                            onChange={(e) => setFormData({ ...formData, end_time: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          />
                        </div>
                      </div>
                    </>
                  )}

                  {/* POSITION FIELDS */}
                  {modalType === 'position' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">Position Code *</label>
                        <input
                          type="text"
                          required
                          value={formData.code || ''}
                          onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. TCH"
                        />
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">Position Title *</label>
                        <input
                          type="text"
                          required
                          value={formData.name || ''}
                          onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. Assistant Teacher"
                        />
                      </div>
                    </>
                  )}

                  {/* GRADE FIELDS */}
                  {modalType === 'grade' && (
                    <>
                      <div className="grid grid-cols-2 gap-3">
                        <div>
                          <label className="block text-slate-600 mb-1">Letter Grade *</label>
                          <input
                            type="text"
                            required
                            value={formData.grade || ''}
                            onChange={(e) => setFormData({ ...formData, grade: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. A+"
                          />
                        </div>
                        <div>
                          <label className="block text-slate-600 mb-1">Grade Point</label>
                          <input
                            type="text"
                            value={formData.point || ''}
                            onChange={(e) => setFormData({ ...formData, point: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. 10.0"
                          />
                        </div>
                      </div>
                      <div className="grid grid-cols-2 gap-3">
                        <div>
                          <label className="block text-slate-600 mb-1">Marks From (%)</label>
                          <input
                            type="text"
                            value={formData.marks_from || ''}
                            onChange={(e) => setFormData({ ...formData, marks_from: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. 90"
                          />
                        </div>
                        <div>
                          <label className="block text-slate-600 mb-1">Marks To (%)</label>
                          <input
                            type="text"
                            value={formData.marks_to || ''}
                            onChange={(e) => setFormData({ ...formData, marks_to: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. 100"
                          />
                        </div>
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">Description / Notes</label>
                        <input
                          type="text"
                          value={formData.notes || ''}
                          onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. Outstanding Performance"
                        />
                      </div>
                    </>
                  )}

                  {/* STATE FIELDS */}
                  {modalType === 'state' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">State Code *</label>
                        <input
                          type="text"
                          required
                          value={formData.state_code || ''}
                          onChange={(e) => setFormData({ ...formData, state_code: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. MH"
                        />
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">State Name *</label>
                        <input
                          type="text"
                          required
                          value={formData.state_name || ''}
                          onChange={(e) => setFormData({ ...formData, state_name: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. Maharashtra"
                        />
                      </div>
                    </>
                  )}

                  {/* CITY FIELDS */}
                  {modalType === 'city' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">City Code *</label>
                        <input
                          type="text"
                          required
                          value={formData.city_code || ''}
                          onChange={(e) => setFormData({ ...formData, city_code: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. MUM"
                        />
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">City Name *</label>
                        <input
                          type="text"
                          required
                          value={formData.city_name || ''}
                          onChange={(e) => setFormData({ ...formData, city_name: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. Mumbai"
                        />
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">State Code</label>
                        <input
                          type="text"
                          value={formData.state_code || ''}
                          onChange={(e) => setFormData({ ...formData, state_code: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. MH"
                        />
                      </div>
                    </>
                  )}

                  {/* HOLD FIELDS */}
                  {modalType === 'hold' && (
                    <div>
                      <label className="block text-slate-600 mb-1">Hold Reason Type / Category *</label>
                      <input
                        type="text"
                        required
                        value={formData.type || ''}
                        onChange={(e) => setFormData({ ...formData, type: e.target.value })}
                        className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                        placeholder="e.g. Birth Certificate Pending"
                      />
                    </div>
                  )}

                  {/* UNIVERSITY FIELDS */}
                  {modalType === 'univ' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">University Name *</label>
                        <input
                          type="text"
                          required
                          value={formData.name || ''}
                          onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. University of Mumbai"
                        />
                      </div>
                      <div className="grid grid-cols-2 gap-3">
                        <div>
                          <label className="block text-slate-600 mb-1">City</label>
                          <input
                            type="text"
                            value={formData.city || ''}
                            onChange={(e) => setFormData({ ...formData, city: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          />
                        </div>
                        <div>
                          <label className="block text-slate-600 mb-1">State</label>
                          <input
                            type="text"
                            value={formData.state || ''}
                            onChange={(e) => setFormData({ ...formData, state: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          />
                        </div>
                      </div>
                    </>
                  )}

                  {/* MAJOR FIELDS */}
                  {modalType === 'major' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">Major / Discipline Title *</label>
                        <input
                          type="text"
                          required
                          value={formData.name || ''}
                          onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. Master of Science (Mathematics)"
                        />
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">Details / Notes</label>
                        <input
                          type="text"
                          value={formData.details || ''}
                          onChange={(e) => setFormData({ ...formData, details: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. Post-graduate degree"
                        />
                      </div>
                    </>
                  )}

                  {/* COLLEGE FIELDS */}
                  {modalType === 'college' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">College Name *</label>
                        <input
                          type="text"
                          required
                          value={formData.name || ''}
                          onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. St. Xavier's Junior College"
                        />
                      </div>
                      <div className="grid grid-cols-2 gap-3">
                        <div>
                          <label className="block text-slate-600 mb-1">City</label>
                          <input
                            type="text"
                            value={formData.city || ''}
                            onChange={(e) => setFormData({ ...formData, city: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. Mumbai"
                          />
                        </div>
                        <div>
                          <label className="block text-slate-600 mb-1">State</label>
                          <input
                            type="text"
                            value={formData.state || ''}
                            onChange={(e) => setFormData({ ...formData, state: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. Maharashtra"
                          />
                        </div>
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">Campus Address</label>
                        <input
                          type="text"
                          value={formData.address || ''}
                          onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          placeholder="e.g. 5 Mahapalika Marg, Dhobi Talao"
                        />
                      </div>
                      <div className="grid grid-cols-2 gap-3">
                        <div>
                          <label className="block text-slate-600 mb-1">Email</label>
                          <input
                            type="email"
                            value={formData.email || ''}
                            onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. info@college.edu"
                          />
                        </div>
                        <div>
                          <label className="block text-slate-600 mb-1">Phone</label>
                          <input
                            type="text"
                            value={formData.phone || ''}
                            onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                            placeholder="e.g. 022-22620661"
                          />
                        </div>
                      </div>
                    </>
                  )}

                  {/* STUDENT HOLD FIELDS */}
                  {modalType === 'student-hold' && (
                    <>
                      <div>
                        <label className="block text-slate-600 mb-1">Select Student *</label>
                        <select
                          required
                          value={formData.student_id || ''}
                          onChange={(e) => setFormData({ ...formData, student_id: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                        >
                          <option value="">-- Choose Student --</option>
                          {students.map((st) => (
                            <option key={st.id} value={st.id}>
                              {st.student_id || st.admission_no ? `[${st.student_id || st.admission_no}] ` : ''}
                              {st.student_name || `${st.first_name || ''} ${st.last_name || ''}`.trim() || `Student #${st.id}`}
                              {st.standard ? ` (Std: ${st.standard})` : ''}
                            </option>
                          ))}
                        </select>
                      </div>
                      <div>
                        <label className="block text-slate-600 mb-1">Hold Reason Type *</label>
                        <select
                          required
                          value={formData.hold_id || ''}
                          onChange={(e) => setFormData({ ...formData, hold_id: e.target.value })}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                        >
                          <option value="">-- Choose Reason --</option>
                          {holdReasons.map((hr) => (
                            <option key={hr.id} value={hr.id}>
                              {hr.type || `Hold #${hr.id}`}
                            </option>
                          ))}
                        </select>
                      </div>
                      <div className="grid grid-cols-2 gap-3">
                        <div>
                          <label className="block text-slate-600 mb-1">Effective From Date</label>
                          <input
                            type="date"
                            value={formData.hold_from || ''}
                            onChange={(e) => setFormData({ ...formData, hold_from: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          />
                        </div>
                        <div>
                          <label className="block text-slate-600 mb-1">Release / To Date</label>
                          <input
                            type="date"
                            value={formData.hold_to || ''}
                            onChange={(e) => setFormData({ ...formData, hold_to: e.target.value })}
                            className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#1976d2]"
                          />
                        </div>
                      </div>
                    </>
                  )}

                  <div className="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button
                      type="button"
                      onClick={closeModal}
                      className="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      className="px-5 py-2 bg-[#1976d2] text-white rounded-xl font-bold shadow hover:bg-[#1565c0] transition cursor-pointer"
                    >
                      {editingItem ? 'Save Changes' : 'Create'}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          )}
        </main>
      </div>
    </div>
  );
}
