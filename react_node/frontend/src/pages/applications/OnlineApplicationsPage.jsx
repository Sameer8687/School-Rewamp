import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import onlineApplicationService from '../../services/onlineApplicationService.js';
import {
  FiFileText,
  FiSearch,
  FiRefreshCw,
  FiCheckCircle,
  FiXCircle,
  FiClock,
  FiTrash2,
  FiEye,
  FiDownload,
  FiFilter,
  FiPhone,
  FiMail,
} from 'react-icons/fi';

export default function OnlineApplicationsPage() {
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [activeTab, setActiveTab] = useState('junior_college'); // 'junior_college', 'school'
  const [loading, setLoading] = useState(true);

  // Automatically activate correct tab based on CI3 route
  useEffect(() => {
    const p = (location.pathname || '').toLowerCase();
    if (p.includes('list') || p.includes('teacher')) {
      setActiveTab('school');
    } else if (p.includes('junior') || p.includes('clg')) {
      setActiveTab('junior_college');
    }
  }, [location.pathname]);

  const [schoolApps, setSchoolApps] = useState([]);
  const [jrCollegeApps, setJrCollegeApps] = useState([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [statusFilter, setStatusFilter] = useState('all');

  // Detail Modal
  const [selectedApp, setSelectedApp] = useState(null);
  const [modalComments, setModalComments] = useState('');

  const handleViewApp = async (app) => {
    setSelectedApp(app);
    try {
      const res = await onlineApplicationService.getApplicationDetails(app.id, activeTab);
      if (res.data?.success && res.data.data) {
        setSelectedApp(res.data.data);
      }
    } catch (err) {
      console.error('Failed to load application details:', err);
    }
  };



  const loadApplications = async () => {
    setLoading(true);
    try {
      const res = await onlineApplicationService.getApplications();
      if (res.data?.success) {
        setSchoolApps(res.data.schoolApplications || []);
        setJrCollegeApps(res.data.juniorCollegeApplications || []);
      }
    } catch (err) {
      console.error('Failed to load applications:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadApplications();
  }, []);

  const handleUpdateStatus = async (id, status) => {
    try {
      const res = await onlineApplicationService.updateStatus(id, {
        status,
        comments: modalComments,
      });
      if (res.data?.success) {
        alert(`Application status updated to ${status}.`);
        setSelectedApp(null);
        loadApplications();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to update application');
    }
  };

  const handleDelete = async (id, type) => {
    if (!window.confirm('Are you sure you want to delete this application?')) return;
    try {
      const res = await onlineApplicationService.deleteApplication(id, type);
      if (res.data?.success) {
        alert('Application deleted.');
        loadApplications();
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Failed to delete application');
    }
  };

  // Filtered lists
  const currentList = activeTab === 'junior_college' ? jrCollegeApps : schoolApps;
  const filteredList = currentList.filter((item) => {
    const q = searchQuery.toLowerCase();
    const nameMatch =
      item.student_name?.toLowerCase().includes(q) ||
      item.Application_id?.toLowerCase().includes(q) ||
      item.db_stream?.toLowerCase().includes(q) ||
      item.mobile_no?.includes(q);
    return nameMatch;
  });

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
                <FiFileText size={24} />
              </div>
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight">
                  Online Admission Applications
                </h1>
                <p className="text-xs text-slate-500">
                  Manage external portal applications for Junior College & School Admissions
                </p>
              </div>
            </div>

            <div className="flex items-center gap-2.5">
              <button
                onClick={loadApplications}
                className="p-2 text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer"
                title="Refresh"
              >
                <FiRefreshCw size={16} className={loading ? 'animate-spin' : ''} />
              </button>
            </div>
          </div>

          {/* Tabs: Junior College vs School */}
          <div className="flex items-center gap-2 border-b border-slate-200 pb-2">
            <button
              onClick={() => setActiveTab('junior_college')}
              className={`flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all cursor-pointer ${
                activeTab === 'junior_college'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'text-slate-600 hover:bg-slate-200/60'
              }`}
            >
              <span>Junior College Applications</span>
              <span className="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20">
                {jrCollegeApps.length}
              </span>
            </button>

            <button
              onClick={() => setActiveTab('school')}
              className={`flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all cursor-pointer ${
                activeTab === 'school'
                  ? 'bg-[#1976d2] text-white shadow-sm'
                  : 'text-slate-600 hover:bg-slate-200/60'
              }`}
            >
              <span>School Applications</span>
              <span className="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20">
                {schoolApps.length}
              </span>
            </button>
          </div>

          {/* Search Box */}
          <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between gap-3">
            <div className="relative flex-1 max-w-md">
              <FiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={14} />
              <input
                type="text"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Search by student name, application ID, or stream..."
                className="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
              />
            </div>
            <div className="text-xs text-slate-500 font-medium">
              Showing {filteredList.length} applications
            </div>
          </div>

          {/* Table */}
          <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {loading ? (
              <div className="py-20 flex flex-col items-center justify-center text-slate-400">
                <FiRefreshCw className="animate-spin mb-3 text-[#1976d2]" size={28} />
                <p className="text-sm font-medium">Loading applications...</p>
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse text-xs">
                  <thead>
                    <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                      <th className="py-3 px-4">App ID</th>
                      <th className="py-3 px-4">Student Name</th>
                      {activeTab === 'junior_college' ? (
                        <>
                          <th className="py-3 px-4">Stream / Branch</th>
                          <th className="py-3 px-4">Class & Div</th>
                          <th className="py-3 px-4">Medium</th>
                          <th className="py-3 px-4">Selected Subjects</th>
                        </>
                      ) : (
                        <>
                          <th className="py-3 px-4">Father Name</th>
                          <th className="py-3 px-4">Standard Applied</th>
                          <th className="py-3 px-4">Mobile</th>
                          <th className="py-3 px-4 text-center">Status</th>
                        </>
                      )}
                      <th className="py-3 px-4 text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100 font-medium">
                    {filteredList.length === 0 ? (
                      <tr>
                        <td colSpan="8" className="py-8 text-center text-slate-400">
                          No applications found matching the search.
                        </td>
                      </tr>
                    ) : (
                      filteredList.map((app) => (
                        <tr key={app.id} className="hover:bg-slate-50/80 transition-colors">
                          <td className="py-3 px-4 font-mono font-bold text-slate-900">
                            {app.Application_id || `#${app.id}`}
                          </td>
                          <td className="py-3 px-4 font-semibold text-slate-800">
                            {app.student_name}
                          </td>

                          {activeTab === 'junior_college' ? (
                            <>
                              <td className="py-3 px-4">
                                <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-[#e3f2fd] text-[#1565c0] border border-[#bbdefb]">
                                  {app.db_stream || 'General'}
                                </span>
                              </td>
                              <td className="py-3 px-4 text-slate-600">
                                {app.db_class || '11th'} {app.db_division ? `(${app.db_division})` : ''}
                              </td>
                              <td className="py-3 px-4 text-slate-600">{app.db_medium || 'English'}</td>
                              <td className="py-3 px-4 text-slate-500 text-[11px]">
                                {[
                                  app.choice_subj_hin_mar,
                                  app.choice_math_geog_bio,
                                  app.commerce_optional_sub,
                                ]
                                  .filter(Boolean)
                                  .join(', ') || '-'}
                              </td>
                            </>
                          ) : (
                            <>
                              <td className="py-3 px-4 text-slate-600">{app.father_name || '-'}</td>
                              <td className="py-3 px-4 font-semibold text-slate-700">{app.standard_applied}</td>
                              <td className="py-3 px-4 text-slate-600">{app.mobile_no || '-'}</td>
                              <td className="py-3 px-4 text-center">
                                <span
                                  className={`px-2 py-0.5 rounded-full text-[10px] font-bold ${
                                    app.application_status === 'Approved'
                                      ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                      : app.application_status === 'Rejected'
                                      ? 'bg-rose-50 text-rose-700 border border-rose-200'
                                      : 'bg-amber-50 text-amber-700 border border-amber-200'
                                  }`}
                                >
                                  {app.application_status || 'Pending'}
                                </span>
                              </td>
                            </>
                          )}

                          <td className="py-3 px-4 text-center">
                            <div className="flex items-center justify-center gap-1.5">
                              <button
                                onClick={() => handleViewApp(app)}
                                className="p-1.5 text-slate-600 hover:text-[#1976d2] hover:bg-slate-100 rounded-md transition-colors cursor-pointer"
                                title="View Application"
                              >
                                <FiEye size={15} />
                              </button>
                              <button
                                onClick={() => handleDelete(app.id, activeTab)}
                                className="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-md transition-colors cursor-pointer"
                                title="Delete Application"
                              >
                                <FiTrash2 size={15} />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </main>
      </div>

      {/* Application Detail Modal */}
      {selectedApp && (
        <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between border-b border-slate-200 pb-3">
              <div>
                <h3 className="font-bold text-slate-900 text-base">
                  {activeTab === 'junior_college'
                    ? 'Junior College Admission Application'
                    : 'Online Application Details'}
                </h3>
                <span className="text-xs font-mono font-bold text-[#1976d2]">
                  ID: {selectedApp.Application_id || `#${selectedApp.id}`}
                </span>
              </div>
              <button
                onClick={() => setSelectedApp(null)}
                className="text-slate-400 hover:text-slate-700 text-lg cursor-pointer"
              >
                ✕
              </button>
            </div>

            <div className="text-xs space-y-4 text-slate-700 max-h-[70vh] overflow-y-auto pr-1">
              {activeTab === 'junior_college' ? (
                <div className="space-y-4">
                  {/* Basic Preferences */}
                  <div className="p-3 bg-slate-50 rounded-xl border border-slate-200 grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div>
                      <span className="text-[10px] text-slate-400 font-bold uppercase block">Stream</span>
                      <span className="font-bold text-slate-800">{selectedApp.db_stream || '-'}</span>
                    </div>
                    <div>
                      <span className="text-[10px] text-slate-400 font-bold uppercase block">Class & Div</span>
                      <span className="font-bold text-slate-800">
                        {selectedApp.db_class || '11th'} {selectedApp.db_division ? `(${selectedApp.db_division})` : ''}
                      </span>
                    </div>
                    <div>
                      <span className="text-[10px] text-slate-400 font-bold uppercase block">Admission Type</span>
                      <span className="font-bold text-slate-800">{selectedApp.db_admissiontype || '-'}</span>
                    </div>
                    <div>
                      <span className="text-[10px] text-slate-400 font-bold uppercase block">Medium</span>
                      <span className="font-bold text-slate-800">{selectedApp.db_medium || 'English'}</span>
                    </div>
                  </div>

                  {/* Student Details */}
                  <div className="border border-slate-200 rounded-xl p-3 space-y-2">
                    <h4 className="font-bold text-xs text-[#1976d2] uppercase">Student Information</h4>
                    <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
                      <div>
                        <span className="text-[10px] text-slate-400 block">Full Name:</span>
                        <span className="font-semibold text-slate-800">
                          {selectedApp.student_name || `${selectedApp.firstname || ''} ${selectedApp.Middlename || ''} ${selectedApp.lastname || ''}`}
                        </span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 block">Date of Birth:</span>
                        <span className="font-semibold text-slate-800">
                          {selectedApp.date ? String(selectedApp.date).slice(0, 10) : '-'}
                        </span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 block">Gender & Nationality:</span>
                        <span className="font-semibold text-slate-800">
                          {selectedApp.db_gender || '-'}, {selectedApp.db_nationality || 'Indian'}
                        </span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 block">Religion & Caste:</span>
                        <span className="font-semibold text-slate-800">
                          {selectedApp.db_religion || '-'} ({selectedApp.db_caste || '-'})
                        </span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 block">Mother Tongue:</span>
                        <span className="font-semibold text-slate-800">{selectedApp.db_mothertounge || '-'}</span>
                      </div>
                      <div>
                        <span className="text-[10px] text-slate-400 block">Aadhaar Number:</span>
                        <span className="font-semibold text-slate-800">{selectedApp.db_adharnumber || '-'}</span>
                      </div>
                    </div>
                  </div>

                  {/* Parent Details */}
                  {(selectedApp.db_fatherfirstname || selectedApp.db_mothersname) && (
                    <div className="border border-slate-200 rounded-xl p-3 space-y-2">
                      <h4 className="font-bold text-xs text-[#1976d2] uppercase">Parent Details</h4>
                      <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <div>
                          <span className="text-[10px] text-slate-400 block">Father:</span>
                          <span className="font-semibold text-slate-800">
                            {`${selectedApp.db_fatherfirstname || ''} ${selectedApp.db_fathermiddlename || ''} ${selectedApp.db_fatherlastname || ''}`}
                          </span>
                        </div>
                        <div>
                          <span className="text-[10px] text-slate-400 block">Father Occ & Income:</span>
                          <span className="font-semibold text-slate-800">
                            {selectedApp.db_fatheroccupation || '-'} ({selectedApp.db_fathersannualincome || '-'})
                          </span>
                        </div>
                        <div>
                          <span className="text-[10px] text-slate-400 block">Mother:</span>
                          <span className="font-semibold text-slate-800">{selectedApp.db_mothersname || '-'}</span>
                        </div>
                        <div>
                          <span className="text-[10px] text-slate-400 block">Parent Mobile:</span>
                          <span className="font-semibold text-slate-800">{selectedApp.db_parentsmobile || '-'}</span>
                        </div>
                        <div className="sm:col-span-2">
                          <span className="text-[10px] text-slate-400 block">Permanent Address:</span>
                          <span className="font-semibold text-slate-800">
                            {selectedApp.db_permenantaddress || selectedApp.db_pemrenantaddress || '-'} (Landmark: {selectedApp.db_landmark || '-'}, Pincode: {selectedApp.db_pincode || '-'})
                          </span>
                        </div>
                      </div>
                    </div>
                  )}

                  {/* Previous School & Marks */}
                  {(selectedApp.db_lastschool || selectedApp.db_lastmarks) && (
                    <div className="border border-slate-200 rounded-xl p-3 space-y-2">
                      <h4 className="font-bold text-xs text-[#1976d2] uppercase">Previous Academic History</h4>
                      <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <div className="sm:col-span-2">
                          <span className="text-[10px] text-slate-400 block">Previous School:</span>
                          <span className="font-semibold text-slate-800">{selectedApp.db_lastschool || '-'}</span>
                        </div>
                        <div>
                          <span className="text-[10px] text-slate-400 block">Board & Year:</span>
                          <span className="font-semibold text-slate-800">
                            {selectedApp.db_lastboard || '-'} ({selectedApp.db_lastyear || '-'})
                          </span>
                        </div>
                        <div>
                          <span className="text-[10px] text-slate-400 block">Marks & Percentage:</span>
                          <span className="font-bold text-emerald-700">
                            {selectedApp.db_lastmarks || '-'}/{selectedApp.db_lastoutof || '-'} ({selectedApp.db_lastpercentage || '-'}%)
                          </span>
                        </div>
                      </div>
                    </div>
                  )}

                  {/* Chosen Subjects */}
                  <div className="p-3 bg-[#e3f2fd]/50 rounded-xl border border-blue-100 space-y-1">
                    <h4 className="font-bold text-xs text-[#1976d2] uppercase">Selected Subjects</h4>
                    <div className="text-xs font-semibold text-slate-700">
                      {[
                        selectedApp.choice_subj_hin_mar,
                        selectedApp.choice_math_geog_bio,
                        selectedApp.commerce_optional_sub,
                        selectedApp.commerce_sub_h_m,
                      ]
                        .filter(Boolean)
                        .join(' • ') || 'Standard Compulsory Subject Selection'}
                    </div>
                  </div>
                </div>
              ) : (
                <div className="space-y-4">
                  {/* Personal & Application Overview */}
                  {(() => {
                    const h = selectedApp.header || selectedApp;
                    const lines = selectedApp.lines || [];
                    return (
                      <>
                        <div className="p-3 bg-slate-50 rounded-xl border border-slate-200 grid grid-cols-2 sm:grid-cols-4 gap-2">
                          <div>
                            <span className="text-[10px] text-slate-400 font-bold uppercase block">Applicant / Student</span>
                            <span className="font-bold text-slate-800">{h.student_name || h.full_name || '-'}</span>
                          </div>
                          <div>
                            <span className="text-[10px] text-slate-400 font-bold uppercase block">Father / Guardian</span>
                            <span className="font-bold text-slate-800">{h.father_name || '-'}</span>
                          </div>
                          <div>
                            <span className="text-[10px] text-slate-400 font-bold uppercase block">Applied For</span>
                            <span className="font-bold text-[#1976d2]">{h.standard_applied || 'Teacher/Staff'}</span>
                          </div>
                          <div>
                            <span className="text-[10px] text-slate-400 font-bold uppercase block">Status</span>
                            <span className="font-bold text-emerald-700">{h.application_status || 'Pending'}</span>
                          </div>
                        </div>

                        {/* Contact Information */}
                        <div className="border border-slate-200 rounded-xl p-3 space-y-2">
                          <h4 className="font-bold text-xs text-[#1976d2] uppercase">Contact Details</h4>
                          <div className="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div>
                              <span className="text-[10px] text-slate-400 block">Mobile No:</span>
                              <span className="font-semibold text-slate-800">{h.mobile_no || h.phone || '-'}</span>
                            </div>
                            <div>
                              <span className="text-[10px] text-slate-400 block">WhatsApp / Alt Phone:</span>
                              <span className="font-semibold text-slate-800">{h.alt_phone || '-'}</span>
                            </div>
                            <div>
                              <span className="text-[10px] text-slate-400 block">Email Address:</span>
                              <span className="font-semibold text-slate-800">{h.email || '-'}</span>
                            </div>
                            <div className="sm:col-span-3">
                              <span className="text-[10px] text-slate-400 block">Residential Address:</span>
                              <span className="font-semibold text-slate-800">{h.address || '-'}</span>
                            </div>
                          </div>
                        </div>

                        {/* Photo / Resume Attachments (CI3 teacher_details.php) */}
                        {(h.photo || h.resume) && (
                          <div className="border border-slate-200 rounded-xl p-3 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                            {h.photo && (
                              <div className="flex items-center gap-2">
                                <span className="text-xs font-bold text-slate-600">Photo:</span>
                                <a
                                  href={`https://oliveschool.org.in/photos/${h.photo}`}
                                  target="_blank"
                                  rel="noreferrer"
                                  className="text-xs text-[#1976d2] hover:underline font-semibold"
                                >
                                  View Attached Photo
                                </a>
                              </div>
                            )}
                            {h.resume && (
                              <div className="flex items-center gap-2">
                                <span className="text-xs font-bold text-slate-600">Resume / CV:</span>
                                <a
                                  href={`https://oliveschool.org.in/resumes/${h.resume}`}
                                  target="_blank"
                                  rel="noreferrer"
                                  className="px-2.5 py-1 bg-[#1976d2] text-white text-[11px] font-bold rounded hover:bg-[#1565c0] transition-colors"
                                >
                                  Download Resume
                                </a>
                              </div>
                            )}
                          </div>
                        )}

                        {/* Education / Experience Lines (if any) */}
                        {lines.length > 0 && (
                          <div className="border border-slate-200 rounded-xl p-3 space-y-2">
                            <h4 className="font-bold text-xs text-[#1976d2] uppercase">Qualifications & Experience</h4>
                            <div className="overflow-x-auto">
                              <table className="w-full text-left text-xs">
                                <thead>
                                  <tr className="border-b border-slate-200 text-slate-500 font-bold text-[10px]">
                                    <th className="py-1 px-2">Course / Role</th>
                                    <th className="py-1 px-2">Institute / Board</th>
                                    <th className="py-1 px-2">Year</th>
                                    <th className="py-1 px-2">Score / Exp</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  {lines.map((l, idx) => (
                                    <tr key={idx} className="border-b border-slate-100">
                                      <td className="py-1 px-2 font-medium">{l.course_name || l.designation || '-'}</td>
                                      <td className="py-1 px-2 text-slate-600">{l.institution || l.organization || '-'}</td>
                                      <td className="py-1 px-2 text-slate-600">{l.passing_year || l.duration || '-'}</td>
                                      <td className="py-1 px-2 font-bold text-slate-700">{l.percentage || l.experience || '-'}</td>
                                    </tr>
                                  ))}
                                </tbody>
                              </table>
                            </div>
                          </div>
                        )}
                      </>
                    );
                  })()}
                </div>
              )}

              {activeTab === 'school' && (
                <div className="pt-3 border-t border-slate-200">
                  <label className="block text-[11px] font-bold text-slate-600 mb-1">
                    Review Remarks:
                  </label>
                  <textarea
                    value={modalComments}
                    onChange={(e) => setModalComments(e.target.value)}
                    placeholder="Enter review comments or remarks..."
                    className="w-full p-2 border border-slate-200 rounded-lg text-xs"
                    rows="2"
                  />
                  <div className="flex items-center justify-end gap-2 mt-2">
                    <button
                      onClick={() => handleUpdateStatus(selectedApp.id, 'Approved')}
                      className="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs cursor-pointer"
                    >
                      Approve Application
                    </button>
                    <button
                      onClick={() => handleUpdateStatus(selectedApp.id, 'Rejected')}
                      className="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-xs cursor-pointer"
                    >
                      Reject Application
                    </button>
                  </div>
                </div>
              )}
            </div>
            <div className="text-right pt-2 border-t border-slate-100">
              <button
                onClick={() => setSelectedApp(null)}
                className="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-lg text-xs cursor-pointer"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      )}

    </div>
  );
}
