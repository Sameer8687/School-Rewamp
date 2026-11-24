import React, { useState, useEffect, useMemo } from 'react';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import feesAlertService from '../../services/feesAlertService.js';
import { communicationService } from '../../services/communicationService.js';
import { fetchStudentOptions } from '../../services/studentService.js';
import {
  FiSend,
  FiMessageSquare,
  FiDollarSign,
  FiUsers,
  FiAlertCircle,
  FiRefreshCw,
  FiCheck,
  FiEye,
  FiPlus,
  FiTrash2,
  FiEdit,
  FiCopy,
} from 'react-icons/fi';

export default function FeesAlertBroadcastPage() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [activeTab, setActiveTab] = useState('fees-alert'); // 'fees-alert' | 'admission-alert' | 'templates'
  const [loading, setLoading] = useState(false);

  // Filter options
  const [academicYears, setAcademicYears] = useState([]);
  const [classes, setClasses] = useState([]);

  // Filter selections
  const [selectedYear, setSelectedYear] = useState('');
  const [selectedClass, setSelectedClass] = useState('');
  const [selectedDiv, setSelectedDiv] = useState('A');

  // Fees Alert Data
  const [feeStudents, setFeeStudents] = useState([]);
  const [templates, setTemplates] = useState([]);
  const [selectedTemplateId, setSelectedTemplateId] = useState('');
  const [customMessage, setCustomMessage] = useState('');

  // Admission Alert Data
  const [admissionStudents, setAdmissionStudents] = useState([]);

  // Student Ledger Detail Modal
  const [isDetailModalOpen, setIsDetailModalOpen] = useState(false);
  const [detailLoading, setDetailLoading] = useState(false);
  const [studentDetails, setStudentDetails] = useState([]);
  const [currentStudentName, setCurrentStudentName] = useState('');

  // Template Management Modal
  const [isTemplateModalOpen, setIsTemplateModalOpen] = useState(false);
  const [templateForm, setTemplateForm] = useState({ id: null, text: '' });
  const [templateSubmitting, setTemplateSubmitting] = useState(false);

  // Load Initial Options & Templates
  useEffect(() => {
    async function init() {
      try {
        const [optRes, tempRes] = await Promise.all([
          fetchStudentOptions(),
          feesAlertService.getMessageTemplates(),
        ]);

        if (optRes.data?.success) {
          const years = optRes.data.options.academicYears || [];
          const cls = optRes.data.options.classes || [];
          setAcademicYears(years);
          setClasses(cls);

          const activeYear = years.find((y) => y.is_active) || years[0];
          if (activeYear) setSelectedYear(String(activeYear.stvacdm_id));
          if (cls.length > 0) setSelectedClass(cls[0].stvfees_class);
        }

        if (tempRes.data?.success) {
          const list = tempRes.data.templates || [];
          setTemplates(list);
          if (list.length > 0) {
            setSelectedTemplateId(String(list[0].id));
            setCustomMessage(list[0].text);
          }
        }
      } catch (err) {
        console.error('Initialization error:', err);
      }
    }
    init();
  }, []);

  // When template dropdown changes
  const handleTemplateChange = (e) => {
    const id = e.target.value;
    setSelectedTemplateId(id);
    const found = templates.find((t) => String(t.id) === String(id));
    if (found) {
      setCustomMessage(found.text);
    }
  };

  // Fetch Fees Reminder Students
  const loadFeeStudents = async () => {
    if (!selectedClass || !selectedDiv || !selectedYear) return;
    setLoading(true);
    try {
      const res = await feesAlertService.getFeesMessageStudents({
        standard: selectedClass,
        division: selectedDiv,
        academicYear: selectedYear,
      });
      if (res.data?.success) {
        setFeeStudents(res.data.data || []);
      }
    } catch (err) {
      console.error('Error fetching fees reminder students:', err);
    } finally {
      setLoading(false);
    }
  };

  // Fetch Admission Confirmation Students
  const loadAdmissionStudents = async () => {
    if (!selectedClass || !selectedDiv || !selectedYear) return;
    setLoading(true);
    try {
      const res = await feesAlertService.getAdmissionConfirmationAlerts({
        standard: selectedClass,
        division: selectedDiv,
        academicYear: selectedYear,
      });
      if (res.data?.success) {
        setAdmissionStudents(res.data.data || []);
      }
    } catch (err) {
      console.error('Error fetching admission confirmation alerts:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (activeTab === 'fees-alert') {
      loadFeeStudents();
    } else if (activeTab === 'admission-alert') {
      loadAdmissionStudents();
    }
  }, [activeTab, selectedYear, selectedClass, selectedDiv]);

  // Open Student Fee Ledger Modal
  const handleOpenStudentDetails = async (studentId, studentName) => {
    setCurrentStudentName(studentName);
    setIsDetailModalOpen(true);
    setDetailLoading(true);
    try {
      const res = await feesAlertService.getStudentFeesHistory(studentId);
      if (res.data?.success) {
        setStudentDetails(res.data.data || []);
      }
    } catch (err) {
      console.error('Failed to load student fees history:', err);
    } finally {
      setDetailLoading(false);
    }
  };

  // Format dynamic WhatsApp message for a fee student
  const generateFeeWhatsAppLink = (student) => {
    const phone = student.stvpcnf_whatsapp || student.stvpcnf_mobile;
    if (!phone) return null;
    const cleanPhone = String(phone).replace(/\D/g, '');
    const recipient = cleanPhone.length === 10 ? `91${cleanPhone}` : cleanPhone;

    let text = customMessage || 'Dear Parent, fee payment reminder for your child.';
    text = text
      .replace(/{name}/gi, student.name)
      .replace(/{balance}/gi, `Rs. ${student.balance}`)
      .replace(/{paid}/gi, `Rs. ${student.paid_amount}`)
      .replace(/{class}/gi, `${student.STVCLAS_standard}-${student.STVCLAS_class}`);

    return `https://api.whatsapp.com/send?phone=${recipient}&text=${encodeURIComponent(text)}`;
  };

  // Format dynamic WhatsApp message for admission confirmation
  const generateAdmissionWhatsAppLink = (student) => {
    const phone = student.stvpcnf_whatsapp || student.stvpcnf_mobile;
    if (!phone) return null;
    const cleanPhone = String(phone).replace(/\D/g, '');
    const recipient = cleanPhone.length === 10 ? `91${cleanPhone}` : cleanPhone;

    let msg = `Assalamualaikum \n`;
    msg += `Dear Parent,\n`;
    msg += `Your child ${student.name} admission for this year is pending.\n`;
    msg += `Kindly visit the school office to confirm admission and clear pending fees.\n\n`;
    msg += `Pending Fees Details:\n`;
    msg += `=============================\n`;

    if (student.balance_history && student.balance_history.length > 0) {
      student.balance_history.forEach((hist) => {
        if (hist.balance > 0) {
          msg += `Academic Year ${hist.academicYearName || hist.academicYearId}: Rs. ${hist.balance}\n`;
        }
      });
    } else {
      msg += `Remaining Balance: Rs. ${student.remaining_fees_to_be_paid}\n`;
    }

    msg += `\nThank you,\nOlive English School Administration`;

    return `https://api.whatsapp.com/send?phone=${recipient}&text=${encodeURIComponent(msg)}`;
  };

  // Save Template (Create or Update)
  const handleSaveTemplate = async (e) => {
    e.preventDefault();
    if (!templateForm.text.trim()) return;
    setTemplateSubmitting(true);
    try {
      if (templateForm.id) {
        await communicationService.updateTemplate(templateForm.id, { text: templateForm.text });
      } else {
        await communicationService.createTemplate({ text: templateForm.text });
      }
      setIsTemplateModalOpen(false);
      setTemplateForm({ id: null, text: '' });
      // Refresh
      const tempRes = await feesAlertService.getMessageTemplates();
      if (tempRes.data?.success) {
        setTemplates(tempRes.data.templates || []);
      }
    } catch (err) {
      alert('Failed to save message template');
    } finally {
      setTemplateSubmitting(false);
    }
  };

  const handleDeleteTemplate = async (id) => {
    if (!window.confirm('Are you sure you want to delete this template?')) return;
    try {
      await communicationService.deleteTemplate(id);
      const tempRes = await feesAlertService.getMessageTemplates();
      if (tempRes.data?.success) {
        setTemplates(tempRes.data.templates || []);
      }
    } catch (err) {
      alert('Failed to delete template');
    }
  };

  // Summary Metrics
  const feeStats = useMemo(() => {
    const totalStudents = feeStudents.length;
    const withBalance = feeStudents.filter((s) => s.balance > 0).length;
    const totalBalance = feeStudents.reduce((sum, s) => sum + (s.balance || 0), 0);
    const withWhatsapp = feeStudents.filter((s) => Boolean(s.stvpcnf_whatsapp || s.stvpcnf_mobile)).length;
    return { totalStudents, withBalance, totalBalance, withWhatsapp };
  }, [feeStudents]);

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
      <Sidebar sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} />
      <div className="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <TopNav setSidebarOpen={setSidebarOpen} />
        <main className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <div className="flex items-center gap-2">
                <span className="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                  <FiSend className="w-5 h-5" />
                </span>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight">
                  Fees & Admission Alerts Broadcast
                </h1>
              </div>
              <p className="text-sm text-slate-500 mt-1">
                Dispatch WhatsApp fee reminders, track pending admissions, and manage communication templates
              </p>
            </div>

            <div className="flex items-center gap-2">
              <button
                onClick={() => {
                  if (activeTab === 'fees-alert') loadFeeStudents();
                  else if (activeTab === 'admission-alert') loadAdmissionStudents();
                }}
                className="p-2 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition"
                title="Refresh Data"
              >
                <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
              </button>
            </div>
          </div>

          {/* Navigation Tabs */}
          <div className="flex items-center gap-2 border-b border-slate-200">
            <button
              onClick={() => setActiveTab('fees-alert')}
              className={`pb-3 px-4 text-sm font-medium transition border-b-2 flex items-center gap-2 ${
                activeTab === 'fees-alert'
                  ? 'border-emerald-600 text-emerald-600 '
                  : 'border-transparent text-slate-500 hover:text-slate-700 '
              }`}
            >
              <FiDollarSign className="w-4 h-4" />
              <span>Fees Reminder Broadcast</span>
            </button>
            <button
              onClick={() => setActiveTab('admission-alert')}
              className={`pb-3 px-4 text-sm font-medium transition border-b-2 flex items-center gap-2 ${
                activeTab === 'admission-alert'
                  ? 'border-emerald-600 text-emerald-600 '
                  : 'border-transparent text-slate-500 hover:text-slate-700 '
              }`}
            >
              <FiAlertCircle className="w-4 h-4" />
              <span>Admission Confirmation Alert</span>
            </button>
            <button
              onClick={() => setActiveTab('templates')}
              className={`pb-3 px-4 text-sm font-medium transition border-b-2 flex items-center gap-2 ${
                activeTab === 'templates'
                  ? 'border-emerald-600 text-emerald-600 '
                  : 'border-transparent text-slate-500 hover:text-slate-700 '
              }`}
            >
              <FiMessageSquare className="w-4 h-4" />
              <span>Message Templates</span>
            </button>
          </div>

          {/* ===================== TAB 1: FEES REMINDER ===================== */}
          {activeTab === 'fees-alert' && (
            <div className="space-y-6">
              {/* Filter Row */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap gap-4 items-center">
                <div className="w-44">
                  <label className="block text-xs font-semibold text-slate-500 mb-1">Standard / Class</label>
                  <select
                    value={selectedClass}
                    onChange={(e) => setSelectedClass(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  >
                    {classes.map((c, i) => (
                      <option key={i} value={c.stvfees_class}>
                        {c.stvfees_class}
                      </option>
                    ))}
                  </select>
                </div>

                <div className="w-32">
                  <label className="block text-xs font-semibold text-slate-500 mb-1">Division</label>
                  <select
                    value={selectedDiv}
                    onChange={(e) => setSelectedDiv(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  >
                    <option value="A">Division A</option>
                    <option value="B">Division B</option>
                    <option value="C">Division C</option>
                  </select>
                </div>

                <div className="w-48">
                  <label className="block text-xs font-semibold text-slate-500 mb-1">Academic Year</label>
                  <select
                    value={selectedYear}
                    onChange={(e) => setSelectedYear(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  >
                    {academicYears.map((y) => (
                      <option key={y.stvacdm_id} value={y.stvacdm_id}>
                        {y.stvacdm_year}
                      </option>
                    ))}
                  </select>
                </div>

                <div className="pt-5">
                  <button
                    onClick={loadFeeStudents}
                    className="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2"
                  >
                    <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
                    <span>Apply Filter</span>
                  </button>
                </div>
              </div>

              {/* Message Composer & Template Selector */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-3">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h3 className="text-sm font-semibold text-slate-900 flex items-center gap-2">
                    <FiMessageSquare className="text-emerald-500" />
                    WhatsApp Reminder Message Template
                  </h3>
                  <div className="flex items-center gap-2">
                    <label className="text-xs text-slate-500">Preset:</label>
                    <select
                      value={selectedTemplateId}
                      onChange={handleTemplateChange}
                      className="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                    >
                      <option value="">-- Choose Template --</option>
                      {templates.map((t) => (
                        <option key={t.id} value={t.id}>
                          {t.text.length > 40 ? t.text.substring(0, 40) + '...' : t.text}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                <textarea
                  rows="3"
                  value={customMessage}
                  onChange={(e) => setCustomMessage(e.target.value)}
                  placeholder="Type WhatsApp reminder template. Supported variables: {name}, {balance}, {paid}, {class}"
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />

                <div className="flex flex-wrap items-center justify-between text-xs text-slate-400 gap-2">
                  <div className="flex items-center gap-2">
                    <span>Variables:</span>
                    <code className="bg-slate-100 px-1 py-0.5 rounded text-[#1976d2]">
                      &#123;name&#125;
                    </code>
                    <code className="bg-slate-100 px-1 py-0.5 rounded text-[#1976d2]">
                      &#123;balance&#125;
                    </code>
                    <code className="bg-slate-100 px-1 py-0.5 rounded text-[#1976d2]">
                      &#123;paid&#125;
                    </code>
                    <code className="bg-slate-100 px-1 py-0.5 rounded text-[#1976d2]">
                      &#123;class&#125;
                    </code>
                  </div>
                  <span>Click WhatsApp icon next to student row to dispatch message.</span>
                </div>
              </div>

              {/* Metrics */}
              <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                  <div>
                    <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                      Total Class Students
                    </p>
                    <p className="text-2xl font-bold mt-1 text-slate-900">
                      {feeStats.totalStudents}
                    </p>
                  </div>
                  <div className="p-3 bg-[#e3f2fd] text-[#1976d2] rounded-xl">
                    <FiUsers className="w-5 h-5" />
                  </div>
                </div>

                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                  <div>
                    <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                      Pending Dues Students
                    </p>
                    <p className="text-2xl font-bold mt-1 text-rose-600">
                      {feeStats.withBalance}
                    </p>
                  </div>
                  <div className="p-3 bg-rose-50 text-rose-600 rounded-xl">
                    <FiAlertCircle className="w-5 h-5" />
                  </div>
                </div>

                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                  <div>
                    <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                      Total Pending Balance
                    </p>
                    <p className="text-2xl font-bold mt-1 text-amber-600">
                      ₹{feeStats.totalBalance.toLocaleString('en-IN')}
                    </p>
                  </div>
                  <div className="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <FiDollarSign className="w-5 h-5" />
                  </div>
                </div>

                <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                  <div>
                    <p className="text-xs font-medium text-slate-500 uppercase tracking-wider">
                      WhatsApp Reachable
                    </p>
                    <p className="text-2xl font-bold mt-1 text-emerald-600">
                      {feeStats.withWhatsapp}
                    </p>
                  </div>
                  <div className="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <FiMessageSquare className="w-5 h-5" />
                  </div>
                </div>
              </div>

              {/* Table */}
              <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse">
                    <thead>
                      <tr className="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th className="py-3 px-4">#</th>
                        <th className="py-3 px-4">Student ID</th>
                        <th className="py-3 px-4">Student Name</th>
                        <th className="py-3 px-4">Class</th>
                        <th className="py-3 px-4 text-right">Balance Due</th>
                        <th className="py-3 px-4 text-right">Total Paid</th>
                        <th className="py-3 px-4 text-right">Total Fees</th>
                        <th className="py-3 px-4">Contact Phone</th>
                        <th className="py-3 px-4 text-center">Ledger</th>
                        <th className="py-3 px-4 text-center">WhatsApp Alert</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 text-sm">
                      {loading ? (
                        <tr>
                          <td colSpan="10" className="py-12 text-center text-slate-400">
                            <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-emerald-500" />
                            Loading student fees records...
                          </td>
                        </tr>
                      ) : feeStudents.length === 0 ? (
                        <tr>
                          <td colSpan="10" className="py-12 text-center text-slate-400">
                            No students found for {selectedClass} - {selectedDiv}.
                          </td>
                        </tr>
                      ) : (
                        feeStudents.map((stu, idx) => {
                          const waLink = generateFeeWhatsAppLink(stu);
                          const hasDue = stu.balance > 0;
                          return (
                            <tr
                              key={stu.id}
                              className="hover:bg-slate-50 transition"
                            >
                              <td className="py-3 px-4 text-xs font-mono text-slate-400">
                                {idx + 1}
                              </td>
                              <td className="py-3 px-4 font-mono text-xs text-slate-600">
                                {stu.SPRIDEN_student_id}
                              </td>
                              <td className="py-3 px-4 font-medium text-slate-900">
                                {stu.name}
                              </td>
                              <td className="py-3 px-4 whitespace-nowrap text-xs">
                                <span className="px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                  {stu.STVCLAS_standard}-{stu.STVCLAS_class}
                                </span>
                              </td>
                              <td className="py-3 px-4 text-right font-medium whitespace-nowrap">
                                <span
                                  className={
                                    hasDue
                                      ? 'text-rose-600  font-bold'
                                      : 'text-slate-400'
                                  }
                                >
                                  ₹{stu.balance.toLocaleString('en-IN')}
                                </span>
                              </td>
                              <td className="py-3 px-4 text-right font-medium text-emerald-600 whitespace-nowrap">
                                ₹{stu.paid_amount.toLocaleString('en-IN')}
                              </td>
                              <td className="py-3 px-4 text-right font-medium text-slate-700 whitespace-nowrap">
                                ₹{stu.total_fees.toLocaleString('en-IN')}
                              </td>
                              <td className="py-3 px-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                                {stu.stvpcnf_whatsapp || stu.stvpcnf_mobile || 'None'}
                              </td>
                              <td className="py-3 px-4 text-center whitespace-nowrap">
                                <button
                                  onClick={() => handleOpenStudentDetails(stu.id, stu.name)}
                                  className="px-2.5 py-1 text-xs border border-[#bbdefb] text-[#1976d2] hover:bg-[#e3f2fd] rounded flex items-center gap-1 mx-auto"
                                >
                                  <FiEye className="w-3.5 h-3.5" />
                                  <span>View</span>
                                </button>
                              </td>
                              <td className="py-3 px-4 text-center whitespace-nowrap">
                                {waLink ? (
                                  <a
                                    href={waLink}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-medium shadow-sm transition"
                                  >
                                    <FiSend className="w-3.5 h-3.5" />
                                    <span>Send WA</span>
                                  </a>
                                ) : (
                                  <span className="text-xs text-slate-400">No Mobile</span>
                                )}
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

          {/* ===================== TAB 2: ADMISSION CONFIRMATION ===================== */}
          {activeTab === 'admission-alert' && (
            <div className="space-y-6">
              {/* Filter */}
              <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap gap-4 items-center">
                <div className="w-44">
                  <label className="block text-xs font-semibold text-slate-500 mb-1">Standard / Class</label>
                  <select
                    value={selectedClass}
                    onChange={(e) => setSelectedClass(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  >
                    {classes.map((c, i) => (
                      <option key={i} value={c.stvfees_class}>
                        {c.stvfees_class}
                      </option>
                    ))}
                  </select>
                </div>

                <div className="w-32">
                  <label className="block text-xs font-semibold text-slate-500 mb-1">Division</label>
                  <select
                    value={selectedDiv}
                    onChange={(e) => setSelectedDiv(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  >
                    <option value="A">Division A</option>
                    <option value="B">Division B</option>
                    <option value="C">Division C</option>
                  </select>
                </div>

                <div className="w-48">
                  <label className="block text-xs font-semibold text-slate-500 mb-1">Academic Year</label>
                  <select
                    value={selectedYear}
                    onChange={(e) => setSelectedYear(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm"
                  >
                    {academicYears.map((y) => (
                      <option key={y.stvacdm_id} value={y.stvacdm_id}>
                        {y.stvacdm_year}
                      </option>
                    ))}
                  </select>
                </div>

                <div className="pt-5">
                  <button
                    onClick={loadAdmissionStudents}
                    className="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2"
                  >
                    <FiRefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
                    <span>Search Pending Admissions</span>
                  </button>
                </div>
              </div>

              {/* Admission Alert List */}
              <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse">
                    <thead>
                      <tr className="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th className="py-3 px-4">Roll</th>
                        <th className="py-3 px-4">Student Name</th>
                        <th className="py-3 px-4">Class</th>
                        <th className="py-3 px-4 text-right">Current Year Total</th>
                        <th className="py-3 px-4 text-right">Current Paid</th>
                        <th className="py-3 px-4 text-right">Current Remaining</th>
                        <th className="py-3 px-4">Previous Years Dues Breakdown</th>
                        <th className="py-3 px-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100 text-sm">
                      {loading ? (
                        <tr>
                          <td colSpan="8" className="py-12 text-center text-slate-400">
                            <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-emerald-500" />
                            Checking admission confirmations...
                          </td>
                        </tr>
                      ) : admissionStudents.length === 0 ? (
                        <tr>
                          <td colSpan="8" className="py-12 text-center text-slate-400">
                            No pending admission students found with unpaid status.
                          </td>
                        </tr>
                      ) : (
                        admissionStudents.map((st) => {
                          const waLink = generateAdmissionWhatsAppLink(st);
                          return (
                            <tr key={st.id} className="hover:bg-slate-50 transition">
                              <td className="py-3 px-4 font-mono text-xs font-semibold text-slate-500">
                                {st.STVCLAS_rollno || '-'}
                              </td>
                              <td className="py-3 px-4">
                                <div className="font-medium text-slate-900">
                                  {st.name}
                                </div>
                                <div className="text-xs text-slate-400 font-mono">
                                  ID: {st.SPRIDEN_student_id} | Mobile: {st.stvpcnf_whatsapp || st.stvpcnf_mobile || 'None'}
                                </div>
                              </td>
                              <td className="py-3 px-4 whitespace-nowrap text-xs">
                                <span className="px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                  {st.STVCLAS_standard}-{st.STVCLAS_class}
                                </span>
                              </td>
                              <td className="py-3 px-4 text-right font-medium whitespace-nowrap">
                                ₹{st.total_fees_to_be_paid.toLocaleString('en-IN')}
                              </td>
                              <td className="py-3 px-4 text-right font-medium text-emerald-600 whitespace-nowrap">
                                ₹{st.total_fees_paid_by_st.toLocaleString('en-IN')}
                              </td>
                              <td className="py-3 px-4 text-right font-bold text-rose-600 whitespace-nowrap">
                                ₹{st.remaining_fees_to_be_paid.toLocaleString('en-IN')}
                              </td>
                              <td className="py-3 px-4 max-w-sm text-xs">
                                {st.balance_history && st.balance_history.length > 0 ? (
                                  <div className="space-y-1">
                                    {st.balance_history.map((bh, idx) => (
                                      <div key={idx} className="flex justify-between text-slate-500">
                                        <span>Year {bh.academicYearName || bh.academicYearId}:</span>
                                        <span className="font-semibold text-rose-500">
                                          ₹{bh.balance.toLocaleString('en-IN')}
                                        </span>
                                      </div>
                                    ))}
                                  </div>
                                ) : (
                                  <span className="text-slate-400">No past dues recorded</span>
                                )}
                              </td>
                              <td className="py-3 px-4 text-center whitespace-nowrap">
                                {waLink ? (
                                  <a
                                    href={waLink}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-medium shadow-sm transition"
                                  >
                                    <FiSend className="w-3.5 h-3.5" />
                                    <span>Send Alert</span>
                                  </a>
                                ) : (
                                  <span className="text-xs text-slate-400">No Mobile</span>
                                )}
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

          {/* ===================== TAB 3: MESSAGE TEMPLATES ===================== */}
          {activeTab === 'templates' && (
            <div className="space-y-4">
              <div className="flex justify-between items-center">
                <p className="text-sm text-slate-500">
                  Manage standard message templates stored in <code className="font-mono text-xs bg-slate-100 px-1 py-0.5 rounded">stvmsgt</code> for fast WhatsApp alert generation.
                </p>
                <button
                  onClick={() => {
                    setTemplateForm({ id: null, text: '' });
                    setIsTemplateModalOpen(true);
                  }}
                  className="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition shadow-sm"
                >
                  <FiPlus className="w-4 h-4" />
                  <span>Create Template</span>
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {templates.map((tmpl) => (
                  <div
                    key={tmpl.id}
                    className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3"
                  >
                    <div className="space-y-2">
                      <div className="flex justify-between items-center text-xs text-slate-400">
                        <span className="font-mono">Template #{tmpl.id}</span>
                        <span>{tmpl.created_at ? new Date(tmpl.created_at).toLocaleDateString() : ''}</span>
                      </div>
                      <p className="text-sm text-slate-800 whitespace-pre-wrap font-sans">
                        {tmpl.text}
                      </p>
                    </div>

                    <div className="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                      <button
                        onClick={() => {
                          setTemplateForm({ id: tmpl.id, text: tmpl.text });
                          setIsTemplateModalOpen(true);
                        }}
                        className="p-1.5 text-[#1976d2] hover:bg-[#e3f2fd] rounded"
                        title="Edit Template"
                      >
                        <FiEdit className="w-4 h-4" />
                      </button>
                      <button
                        onClick={() => handleDeleteTemplate(tmpl.id)}
                        className="p-1.5 text-rose-500 hover:bg-rose-50 rounded"
                        title="Delete Template"
                      >
                        <FiTrash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </main>
      </div>

      {/* Modal: Student Fee Ledger Detail */}
      {isDetailModalOpen && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
          <div className="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-200 max-h-[90vh] overflow-y-auto">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100">
              <div>
                <h3 className="font-semibold text-lg text-slate-900">
                  Fee Ledger History
                </h3>
                <p className="text-xs text-slate-500 mt-0.5">
                  Multi-year breakdown for: <strong className="text-emerald-600">{currentStudentName}</strong>
                </p>
              </div>
              <button
                onClick={() => setIsDetailModalOpen(false)}
                className="text-slate-400 hover:text-slate-600 text-lg"
              >
                ✕
              </button>
            </div>

            <div className="mt-4 space-y-4">
              {detailLoading ? (
                <div className="py-8 text-center text-slate-400">
                  <FiRefreshCw className="w-6 h-6 animate-spin mx-auto mb-2 text-[#1976d2]" />
                  Loading student ledger...
                </div>
              ) : studentDetails.length === 0 ? (
                <div className="py-8 text-center text-slate-400 text-sm">
                  No unpaid fee history recorded for this student.
                </div>
              ) : (
                studentDetails.map((det, idx) => (
                  <div
                    key={idx}
                    className="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3"
                  >
                    <div className="flex justify-between items-center">
                      <div>
                        <span className="font-semibold text-sm text-slate-900">
                          Academic Year: {det.stvacdm_year || det.academic_year}
                        </span>
                        <span className="ml-2 px-2 py-0.5 rounded text-xs bg-slate-200 text-slate-700">
                          Class {det.STVCLAS_standard}-{det.STVCLAS_class}
                        </span>
                      </div>
                      <div className="text-right">
                        <span className="text-xs text-slate-400">Remaining Balance: </span>
                        <span className="font-bold text-sm text-rose-600">
                          ₹{det.balance.toLocaleString('en-IN')}
                        </span>
                      </div>
                    </div>

                    <div className="grid grid-cols-3 gap-2 text-xs bg-white p-2.5 rounded-lg border border-slate-100">
                      <div>
                        <span className="text-slate-400">Total Fees: </span>
                        <span className="font-semibold text-slate-700">
                          ₹{det.stvfstr_fees.toLocaleString('en-IN')}
                        </span>
                      </div>
                      <div>
                        <span className="text-slate-400">Paid: </span>
                        <span className="font-semibold text-emerald-600">
                          ₹{det.total_paid.toLocaleString('en-IN')}
                        </span>
                      </div>
                      <div>
                        <span className="text-slate-400">Due: </span>
                        <span className="font-semibold text-rose-600">
                          ₹{det.balance.toLocaleString('en-IN')}
                        </span>
                      </div>
                    </div>

                    {det.installments_list && det.installments_list.length > 0 && (
                      <div>
                        <p className="text-xs font-semibold text-slate-500 mb-1">Installments Paid:</p>
                        <div className="space-y-1">
                          {det.installments_list.map((ins, iIdx) => (
                            <div
                              key={iIdx}
                              className="flex justify-between text-xs py-1 px-2 rounded bg-white border border-slate-100"
                            >
                              <span>₹{ins.amount.toLocaleString('en-IN')} on {ins.date || 'N/A'}</span>
                              <span className="text-slate-400">Received by: {ins.receivedBy}</span>
                            </div>
                          ))}
                        </div>
                      </div>
                    )}
                  </div>
                ))
              )}
            </div>

            <div className="flex justify-end pt-4 border-t border-slate-100 mt-4">
              <button
                type="button"
                onClick={() => setIsDetailModalOpen(false)}
                className="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm hover:bg-slate-50"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Modal: Create/Edit Template */}
      {isTemplateModalOpen && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200">
            <h3 className="font-semibold text-lg text-slate-900 pb-3 border-b border-slate-100">
              {templateForm.id ? 'Edit Message Template' : 'New Message Template'}
            </h3>

            <form onSubmit={handleSaveTemplate} className="space-y-4 mt-4">
              <div>
                <label className="block text-xs font-medium text-slate-700 mb-1">
                  Template Content *
                </label>
                <textarea
                  rows="4"
                  value={templateForm.text}
                  onChange={(e) => setTemplateForm({ ...templateForm, text: e.target.value })}
                  placeholder="e.g. Assalamu alaikum parent, kindly pay the fee balance of {balance} for {name}..."
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                  required
                />
              </div>

              <div className="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setIsTemplateModalOpen(false)}
                  className="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={templateSubmitting}
                  className="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition disabled:opacity-50"
                >
                  {templateSubmitting ? 'Saving...' : 'Save Template'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
