import React, { useState, useEffect } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import masterService from '../../services/masterService';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiLock,
  FiUser,
  FiMail,
  FiPhone,
  FiTag,
  FiPlus,
  FiSave,
  FiTrash2,
  FiCheckCircle,
  FiAlertCircle,
  FiBarChart2,
  FiGrid,
  FiFileText,
  FiCopy,
  FiArrowRight,
} from 'react-icons/fi';

export default function UserAccessPage() {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [searchParams] = useSearchParams();
  const queryUserId = searchParams.get('user_id');

  // Loading states
  const [pageLoading, setPageLoading] = useState(false);
  const [generating, setGenerating] = useState(false);
  const [saving, setSaving] = useState(false);
  const [feedback, setFeedback] = useState(null);

  // Users & Modules catalogues
  const [sprUsers, setSprUsers] = useState([]);
  const [sameAsUsers, setSameAsUsers] = useState([]);
  const [allModules, setAllModules] = useState([]);
  const [formsByModuleCache, setFormsByModuleCache] = useState({});

  // Selection states
  const [selectedUserId, setSelectedUserId] = useState('');
  const [showSameAs, setShowSameAs] = useState(false);
  const [selectedSameAsUserId, setSelectedSameAsUserId] = useState('');

  // Loaded user & matrix state
  const [activeUser, setActiveUser] = useState(null);
  const [hasGenerated, setHasGenerated] = useState(false);
  const [rows, setRows] = useState([]);
  const [deletedRows, setDeletedRows] = useState([]);

  const todayStr = new Date().toISOString().split('T')[0];

  // Load catalogues on mount
  useEffect(() => {
    loadCatalogues();
  }, []);

  const loadCatalogues = async () => {
    try {
      setPageLoading(true);
      const [uRes, sameRes, mRes] = await Promise.all([
        masterService.getSprUsers(),
        masterService.getUsersWithAccess(),
        masterService.getModules(),
      ]);

      const userList = uRes.data?.data || [];
      const sameList = sameRes.data?.data || [];
      const moduleList = mRes.data?.data || [];

      setSprUsers(userList);
      setSameAsUsers(sameList);
      setAllModules(moduleList);

      // Pre-fetch forms for modules to enable instantaneous dependent dropdowns
      const cache = {};
      await Promise.all(
        moduleList.map(async (m) => {
          try {
            const fRes = await masterService.getFormsByModule(m.id);
            cache[m.id] = fRes.data?.data || [];
          } catch (e) {
            cache[m.id] = [];
          }
        })
      );
      setFormsByModuleCache(cache);

      // If query param ?user_id= is present, auto trigger generation
      if (queryUserId) {
        setSelectedUserId(String(queryUserId));
        triggerGenerate(String(queryUserId), '', cache, userList);
      }
    } catch (err) {
      console.error('Failed to load access catalogues:', err);
      setFeedback({ type: 'error', text: 'Failed to load user access catalogues.' });
    } finally {
      setPageLoading(false);
    }
  };

  // Helper to fetch forms for a module dynamically if not in cache
  const ensureFormsForModule = async (moduleId) => {
    if (!moduleId) return [];
    if (formsByModuleCache[moduleId]) return formsByModuleCache[moduleId];

    try {
      const fRes = await masterService.getFormsByModule(moduleId);
      const forms = fRes.data?.data || [];
      setFormsByModuleCache((prev) => ({ ...prev, [moduleId]: forms }));
      return forms;
    } catch (e) {
      console.error('Failed to load forms for module ' + moduleId, e);
      return [];
    }
  };

  const triggerGenerate = async (targetUserId, targetSameAsUserId, cache = formsByModuleCache, userList = sprUsers) => {
    if (!targetUserId) {
      setFeedback({ type: 'error', text: 'Please select a User Name!' });
      return;
    }

    try {
      setGenerating(true);
      setFeedback(null);

      // 1. Fetch user information for targetUserId
      const uRes = await masterService.getSprUserById(targetUserId);
      const userInfo = uRes.data?.data || userList.find((u) => String(u.spruser_id) === String(targetUserId));
      setActiveUser(userInfo);

      // 2. Determine whose access to load: sameAsUserId if selected, otherwise targetUserId
      const userToFetch = (showSameAs && targetSameAsUserId) ? targetSameAsUserId : targetUserId;
      const aRes = await masterService.getUserAccessMatrix(userToFetch);
      const accessRows = aRes.data?.data || [];

      // Format access rows
      const formatted = accessRows.map((r, idx) => ({
        key: `existing-${r.access_id || idx}-${Date.now()}`,
        access_id: r.access_id,
        module_id: String(r.module_id),
        form_id: String(r.form_id),
        access: r.access || 'Read Write',
        from_date: r.from_date || todayStr,
        to_date: r.to_date || '',
        hold: r.hold || 'Active',
      }));

      // Ensure forms are loaded for any modules present in the user's access list
      const uniqueModuleIds = [...new Set(formatted.map((r) => r.module_id).filter(Boolean))];
      await Promise.all(uniqueModuleIds.map((mid) => ensureFormsForModule(mid)));

      setRows(formatted);
      setDeletedRows([]);
      setHasGenerated(true);

      if (showSameAs && targetSameAsUserId) {
        const sameUserObj = sameAsUsers.find((u) => String(u.spruser_id) === String(targetSameAsUserId));
        setFeedback({
          type: 'success',
          text: `Permissions cloned from "${sameUserObj?.spruser_username || 'template user'}". Review and click Save to assign to ${userInfo?.spruser_username}.`,
        });
      }
    } catch (err) {
      console.error('Failed to generate user access:', err);
      setFeedback({ type: 'error', text: 'Failed to generate user access data.' });
    } finally {
      setGenerating(false);
    }
  };

  const handleGenerateClick = () => {
    triggerGenerate(selectedUserId, selectedSameAsUserId);
  };

  const handleAddRow = () => {
    const newRow = {
      key: `new-${Date.now()}-${Math.random()}`,
      access_id: null,
      module_id: allModules.length > 0 ? String(allModules[0].id) : '',
      form_id: '',
      access: 'Read Write',
      from_date: todayStr,
      to_date: '',
      hold: 'Active',
    };

    if (newRow.module_id) {
      ensureFormsForModule(newRow.module_id);
    }

    setRows([newRow, ...rows]);
  };

  const handleDeleteRow = (index) => {
    const rowToDelete = rows[index];
    if (rowToDelete) {
      if (rowToDelete.module_id && rowToDelete.form_id && activeUser?.spruser_id) {
        setDeletedRows((prev) => [
          ...prev,
          {
            user_id: activeUser.spruser_id,
            module_id: rowToDelete.module_id,
            form_id: rowToDelete.form_id,
          },
        ]);
      }
      setRows((prev) => prev.filter((_, idx) => idx !== index));
    }
  };

  const handleRowChange = async (index, field, value) => {
    const updated = [...rows];
    updated[index] = { ...updated[index], [field]: value };

    // If module changes, reset form_id and load new module forms
    if (field === 'module_id') {
      updated[index].form_id = '';
      await ensureFormsForModule(value);
    }

    setRows(updated);
  };

  const handleSave = async (e) => {
    if (e) e.preventDefault();
    if (!activeUser?.spruser_id) {
      setFeedback({ type: 'error', text: 'Please generate access for a valid user first.' });
      return;
    }

    // Validate that any row with a module has a form selected
    for (let i = 0; i < rows.length; i++) {
      const r = rows[i];
      if (r.module_id && !r.form_id) {
        setFeedback({
          type: 'error',
          text: `Please select a Form for row #${i + 1} or remove the incomplete row.`,
        });
        return;
      }
    }

    try {
      setSaving(true);
      setFeedback(null);

      const payload = {
        userId: activeUser.spruser_id,
        rows: rows.map((r) => ({
          module_id: r.module_id,
          form_id: r.form_id,
          access: r.access,
          from_date: r.from_date || todayStr,
          to_date: r.to_date || null,
          hold: r.hold,
        })),
        deletedRows,
      };

      await masterService.saveUserAccessMatrix(payload);

      setFeedback({
        type: 'success',
        text: `User access permissions for "${activeUser.spruser_username}" saved successfully!`,
      });

      // Refresh matrix to sync state
      await triggerGenerate(String(activeUser.spruser_id), '');
    } catch (err) {
      console.error('Save access matrix error:', err);
      setFeedback({
        type: 'error',
        text: err.response?.data?.message || 'Failed to save user access permissions.',
      });
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="flex h-screen bg-slate-50 text-slate-800">
      <Sidebar isOpen={sidebarOpen} onToggle={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
        <TopNav onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
          <div className="max-w-6xl mx-auto space-y-5">
            {/* CI3 Quick Links Breadcrumb Bar */}
            <div className="flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 px-4 py-2.5 rounded-lg shadow-sm">
              <span className="text-slate-400">|</span>
              <Link
                to="/account-management/user-report"
                className="text-[#1976d2] hover:underline flex items-center gap-1 font-medium"
              >
                <FiBarChart2 className="w-3.5 h-3.5" /> View Report
              </Link>
              <span className="text-slate-400">|</span>
              <Link
                to="/account-management/modules"
                className="text-[#1976d2] hover:underline flex items-center gap-1 font-medium"
              >
                <FiGrid className="w-3.5 h-3.5" /> Add Modules
              </Link>
              <span className="text-slate-400">|</span>
              <Link
                to="/account-management/forms"
                className="text-[#1976d2] hover:underline flex items-center gap-1 font-medium"
              >
                <FiFileText className="w-3.5 h-3.5" /> Add Forms
              </Link>
              <span className="text-slate-400">|</span>
            </div>

            {/* Notification Alert */}
            {feedback && (
              <div
                className={`p-3.5 rounded-lg text-xs font-semibold flex items-center gap-2 border ${
                  feedback.type === 'success'
                    ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                    : 'bg-rose-50 text-rose-800 border-rose-200'
                }`}
              >
                {feedback.type === 'success' ? (
                  <FiCheckCircle className="w-4 h-4 text-emerald-600 flex-shrink-0" />
                ) : (
                  <FiAlertCircle className="w-4 h-4 text-rose-600 flex-shrink-0" />
                )}
                <span>{feedback.text}</span>
              </div>
            )}

            {/* User Access Selection Card */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
              <div className="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <FiLock className="w-5 h-5 text-[#1976d2]" />
                <h2 className="text-base font-bold text-slate-900 tracking-tight">User Access</h2>
              </div>

              {/* Form Controls Row */}
              <div className="mt-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div className="flex flex-wrap items-center gap-3 flex-1">
                  {/* User Name Dropdown */}
                  <label className="text-xs font-semibold text-slate-700 whitespace-nowrap">
                    User Name <span className="text-red-500">*</span>:
                  </label>
                  <select
                    value={selectedUserId}
                    onChange={(e) => {
                      setSelectedUserId(e.target.value);
                      setHasGenerated(false);
                      setActiveUser(null);
                    }}
                    className="text-xs rounded-md border border-slate-300 py-1.5 px-3 bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2] min-w-[200px]"
                  >
                    <option value="">Select</option>
                    {sprUsers.map((u) => (
                      <option key={u.spruser_id} value={u.spruser_id}>
                        {u.spruser_username} ({u.spruser_name || 'No Name'})
                      </option>
                    ))}
                  </select>

                  {/* Same As Button */}
                  <button
                    type="button"
                    onClick={() => setShowSameAs(!showSameAs)}
                    className="text-xs font-semibold text-[#1976d2] hover:underline flex items-center gap-1 px-2 py-1 rounded bg-slate-50 hover:bg-slate-100 transition"
                  >
                    <FiCopy className="w-3.5 h-3.5" />
                    Same As
                  </button>

                  {/* Users (Same As) Dropdown */}
                  {showSameAs && (
                    <div className="flex items-center gap-2">
                      <label className="text-xs font-semibold text-slate-700 whitespace-nowrap">
                        Users:
                      </label>
                      <select
                        value={selectedSameAsUserId}
                        onChange={(e) => setSelectedSameAsUserId(e.target.value)}
                        className="text-xs rounded-md border border-amber-300 bg-amber-50/50 py-1.5 px-3 focus:outline-none focus:ring-1 focus:ring-amber-500 min-w-[200px]"
                      >
                        <option value="">Select Users</option>
                        {sameAsUsers.map((u) => (
                          <option key={u.spruser_id} value={u.spruser_id}>
                            {u.spruser_username} ({u.spruser_name || 'Template'})
                          </option>
                        ))}
                      </select>
                    </div>
                  )}
                </div>

                {/* Right side: Generate Button */}
                <div className="flex-shrink-0">
                  <button
                    type="button"
                    onClick={handleGenerateClick}
                    disabled={generating || pageLoading}
                    className="px-4 py-1.5 text-xs font-bold text-white bg-[#1976d2] hover:bg-[#1565c0] active:bg-[#0d47a1] rounded-md transition flex items-center gap-1.5 shadow-sm disabled:opacity-50"
                  >
                    {generating ? (
                      <>
                        <span className="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        Generating...
                      </>
                    ) : (
                      <>
                        <FiArrowRight className="w-3.5 h-3.5" /> Generate
                      </>
                    )}
                  </button>
                </div>
              </div>

              {/* User Information Display Container */}
              {activeUser && (
                <div className="mt-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                  <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                    <div>
                      <span className="font-bold text-slate-700 flex items-center gap-1.5">
                        <FiUser className="text-[#1976d2]" /> Name:
                      </span>
                      <span className="text-slate-900 ml-4 font-medium">
                        {activeUser.spruser_name || activeUser.spruser_username || 'N/A'}
                      </span>
                    </div>

                    <div>
                      <span className="font-bold text-slate-700 flex items-center gap-1.5">
                        <FiMail className="text-[#1976d2]" /> Email:
                      </span>
                      <span className="text-slate-900 ml-4 font-medium">
                        {activeUser.spruser_email || 'N/A'}
                      </span>
                    </div>

                    <div>
                      <span className="font-bold text-slate-700 flex items-center gap-1.5">
                        <FiPhone className="text-[#1976d2]" /> Mobile:
                      </span>
                      <span className="text-slate-900 ml-4 font-medium">
                        {activeUser.spruser_mobile || 'N/A'}
                      </span>
                    </div>

                    <div>
                      <span className="font-bold text-slate-700 flex items-center gap-1.5">
                        <FiTag className="text-[#1976d2]" /> User Type:
                      </span>
                      <span className="ml-4 inline-block font-semibold px-2 py-0.5 rounded text-[10px] bg-blue-100 text-[#1976d2]">
                        {activeUser.spruser_usertype || 'General'}
                      </span>
                    </div>
                  </div>
                </div>
              )}
            </div>

            {/* Modules and Forms Table Container */}
            {hasGenerated && (
              <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                {/* Table Header Action Buttons */}
                <div className="p-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                  <button
                    type="button"
                    onClick={handleAddRow}
                    className="px-3.5 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-md transition flex items-center gap-1 shadow-sm"
                  >
                    <FiPlus className="w-3.5 h-3.5" /> Add
                  </button>

                  <button
                    type="button"
                    onClick={handleSave}
                    disabled={saving}
                    className="px-4 py-1.5 text-xs font-bold text-white bg-[#1976d2] hover:bg-[#1565c0] active:bg-[#0d47a1] rounded-md transition flex items-center gap-1.5 shadow-sm disabled:opacity-50"
                  >
                    {saving ? (
                      <>
                        <span className="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        Saving...
                      </>
                    ) : (
                      <>
                        <FiSave className="w-3.5 h-3.5" /> Save
                      </>
                    )}
                  </button>
                </div>

                {/* Table */}
                <div className="overflow-x-auto">
                  <table className="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr className="bg-[#1976d2] text-white text-[11px] font-semibold uppercase tracking-wider">
                        <th className="py-2.5 px-3 border border-slate-300 min-w-[200px]">Module Name</th>
                        <th className="py-2.5 px-3 border border-slate-300 min-w-[220px]">Form Name</th>
                        <th className="py-2.5 px-3 border border-slate-300 min-w-[130px]">Access</th>
                        <th className="py-2.5 px-3 border border-slate-300 min-w-[140px]">From Date</th>
                        <th className="py-2.5 px-3 border border-slate-300 min-w-[140px]">To Date</th>
                        <th className="py-2.5 px-3 border border-slate-300 min-w-[120px]">Hold</th>
                        <th className="py-2.5 px-3 border border-slate-300 text-center w-20">Action</th>
                      </tr>
                    </thead>

                    <tbody className="divide-y divide-slate-200">
                      {rows.length === 0 ? (
                        <tr>
                          <td colSpan="7" className="py-6 text-center text-rose-500 font-semibold">
                            Not found any records for selected user name
                          </td>
                        </tr>
                      ) : (
                        rows.map((row, idx) => {
                          const availableForms = formsByModuleCache[row.module_id] || [];
                          return (
                            <tr key={row.key || idx} className="hover:bg-slate-50 transition">
                              {/* Module Select */}
                              <td className="p-1.5 border border-slate-200">
                                <select
                                  value={row.module_id}
                                  onChange={(e) => handleRowChange(idx, 'module_id', e.target.value)}
                                  className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                                  required
                                >
                                  <option value="">Select Module</option>
                                  {allModules.map((m) => (
                                    <option key={m.id} value={m.id}>
                                      {m.module_name}
                                    </option>
                                  ))}
                                </select>
                              </td>

                              {/* Dependent Form Select */}
                              <td className="p-1.5 border border-slate-200">
                                <select
                                  value={row.form_id}
                                  onChange={(e) => handleRowChange(idx, 'form_id', e.target.value)}
                                  className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                                  required
                                >
                                  <option value="">Select Form</option>
                                  {availableForms.map((f) => (
                                    <option key={f.id} value={f.id}>
                                      {f.form_name}
                                    </option>
                                  ))}
                                </select>
                              </td>

                              {/* Access */}
                              <td className="p-1.5 border border-slate-200">
                                <select
                                  value={row.access}
                                  onChange={(e) => handleRowChange(idx, 'access', e.target.value)}
                                  className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                                >
                                  <option value="Read Write">Read Write</option>
                                  <option value="Read Only">Read Only</option>
                                </select>
                              </td>

                              {/* From Date */}
                              <td className="p-1.5 border border-slate-200">
                                <input
                                  type="date"
                                  value={row.from_date || ''}
                                  onChange={(e) => handleRowChange(idx, 'from_date', e.target.value)}
                                  className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                                />
                              </td>

                              {/* To Date */}
                              <td className="p-1.5 border border-slate-200">
                                <input
                                  type="date"
                                  value={row.to_date || ''}
                                  onChange={(e) => handleRowChange(idx, 'to_date', e.target.value)}
                                  className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2]"
                                />
                              </td>

                              {/* Hold */}
                              <td className="p-1.5 border border-slate-200">
                                <select
                                  value={row.hold}
                                  onChange={(e) => handleRowChange(idx, 'hold', e.target.value)}
                                  className={`w-full text-xs font-semibold rounded border border-slate-300 p-1.5 bg-white focus:outline-none focus:ring-1 focus:ring-[#1976d2] ${
                                    row.hold === 'Active' ? 'text-emerald-600' : 'text-rose-600'
                                  }`}
                                >
                                  <option value="Active" className="text-emerald-600">
                                    Active
                                  </option>
                                  <option value="Inactive" className="text-rose-600">
                                    Inactive
                                  </option>
                                </select>
                              </td>

                              {/* Delete Row Action */}
                              <td className="p-1.5 border border-slate-200 text-center">
                                <button
                                  type="button"
                                  onClick={() => handleDeleteRow(idx)}
                                  title="Delete Row"
                                  className="p-1.5 text-white bg-[#dc3545] hover:bg-[#c82333] active:bg-[#bd2130] rounded transition inline-flex items-center justify-center"
                                >
                                  <FiTrash2 className="w-3.5 h-3.5" />
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
          </div>
        </main>
      </div>
    </div>
  );
}
