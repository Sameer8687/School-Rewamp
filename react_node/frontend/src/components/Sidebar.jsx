/**
 * Sidebar Component — Recreates 100% of CI3 Sidebar Navigation Hierarchy in React EduCore Design
 *
 * Content Source: CI3 application/views/sidebar.php & Olive School MySQL Database
 * Design Source: EduCore Dual-Rail Design System (56px Icon Rail + 236px Slide-in Panel)
 *
 * Modules & Hierarchy:
 *   - Administrative Section (Caption)
 *     - Dashboard
 *     - HR Dashboard
 *     - 17 Complete CI3 Modules with 141 Forms:
 *       1. Account Management (User Access, Forms, Moduls, User Report)
 *       2. Administrative (Books & Result, Lesson Management, Lessons, Remarks)
 *       3. Attendance (Student Attendance, Teacher Attendance)
 *       4. Fees (Fees Form, Previous Fees, Balance Report, Comments, Defaulters, Structure, Reconciliation, Visiting...)
 *       5. Fees Reconciliation (Manage Fees Transaction, Upload Bank Transactions)
 *       6. Fees Report (All Previous, Currently Zero Fees Paid, Last Year Balance)
 *       7. Master Forms (Academic Year, City, College, Communication, Driver, Exam, Fees, Grades, Hold, Lesson, Position, School, State, Subject, Vehicle...)
 *       8. New Reports (Absent, Admission, Summary, Balance, Daily, Datewise, Installments, RTE, Books...)
 *       9. Online Application (Junior College, Teacher Application)
 *       10. Parents Forms (Generate Meeting)
 *       11. Public (Student Profile DB)
 *       12. Reports (Aadhar, Absent, Admission, Balance, Daily, Daywise, Exam Alert, LC, Notes, Readmission, RTE...)
 *       13. Section (Create Time, Student Mark, Subject Teacher, Assign Teacher, Create Class, Time Table, Cards, Division, Hall Ticket, Random Class...)
 *       14. Security (Create User, Form Access, Form Access History, Login History)
 *       15. Student (Admission Form, Re-Admission, LC, Bonafide, Report Card, Results, Attendance, Books, Remarks, Promotion, Tailor, Update Student...)
 *       16. Teacher (Account Info, Add Account, Generate Notes, Lesson Management)
 *       17. Tracking (Student Application Tracking, Activity Report, Syllabus, Teacher Activity DB...)
 *     - Change Password
 *     - Log out
 */
import React, { useState, useMemo } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import useAuthStore from '../store/useAuthStore.js';
import useSidebarStore from '../store/useSidebarStore.js';
import { CI3_MODULES } from '../data/ci3SidebarModules.js';
import {
  LuHouse,
  LuLayoutGrid,
  LuSearch,
  LuFolder,
  LuCircleHelp,
  LuLogOut,
  LuChevronDown,
  LuChevronRight,
  LuLayers,
  LuServer,
  LuSquareCheck,
  LuDollarSign,
  LuUsers,
  LuClipboardList,
  LuChartPie,
  LuFileText,
  LuPackage,
  LuAirplay,
  LuList,
  LuShield,
  LuUser,
  LuActivity,
  LuPower,
  LuX,
  LuGraduationCap,
} from 'react-icons/lu';

// Helper to map CI3 icon names to React Lu icons
const getModuleIcon = (iconName) => {
  switch (iconName) {
    case 'layers':
      return LuLayers;
    case 'server':
      return LuServer;
    case 'check-square':
      return LuSquareCheck;
    case 'dollar-sign':
      return LuDollarSign;
    case 'users':
      return LuUsers;
    case 'clipboard':
      return LuClipboardList;
    case 'pie-chart':
      return LuChartPie;
    case 'file-text':
      return LuFileText;
    case 'package':
      return LuPackage;
    case 'airplay':
      return LuAirplay;
    case 'list':
      return LuList;
    case 'shield':
      return LuShield;
    case 'user':
      return LuUser;
    case 'activity':
      return LuActivity;
    case 'grid':
      return LuLayoutGrid;
    default:
      return LuFolder;
  }
};

export default function Sidebar({
  isOpen = false,
  onToggle,
  toggleSidebar,
  setSidebarOpen,
  onToggleSidebar,
  allocatedForms = [],
}) {
  const location = useLocation();
  const navigate = useNavigate();
  const { logout, user } = useAuthStore();
  const {
    isExpanded,
    setIsExpanded,
    panelMode,
    setPanelMode,
    isMobileOpen,
    closeMobile,
  } = useSidebarStore();

  const handleClose = () => {
    closeMobile();
    if (onToggle) onToggle();
    else if (toggleSidebar) toggleSidebar();
    else if (onToggleSidebar) onToggleSidebar();
    else if (setSidebarOpen) setSidebarOpen(false);
  };

  // Determine active module catalog (uses passed allocatedForms or defaults to complete CI3 catalog)
  const modulesCatalog = useMemo(() => {
    if (allocatedForms && allocatedForms.length > 0) {
      return allocatedForms;
    }
    return CI3_MODULES;
  }, [allocatedForms]);

  // Track accordion open/collapsed state for each module
  const [openModules, setOpenModules] = useState(() => {
    const initial = {};
    // Auto-expand module if the current URL matches any of its forms
    modulesCatalog.forEach((mod) => {
      const hasActive = (mod.forms || []).some((f) => {
        const link = f.form_link.startsWith('/') ? f.form_link : `/${f.form_link}`;
        return location.pathname.toLowerCase() === link.split('?')[0].toLowerCase();
      });
      if (hasActive) {
        initial[mod.module_id || mod.module_name] = true;
      }
    });
    return initial;
  });

  const toggleModule = (modKey) => {
    setOpenModules((prev) => ({
      ...prev,
      [modKey]: !prev[modKey],
    }));
  };

  // Search in sidebar
  const [sidebarSearch, setSidebarSearch] = useState('');

  // Flattened navigation catalog for live search across all 141+ forms
  const allNavItems = useMemo(() => {
    const items = [
      { label: 'Dashboard', path: '/dashboard', icon: LuHouse, group: 'Administrative' },
      { label: 'HR Dashboard', path: '/module', icon: LuLayoutGrid, group: 'Administrative' },
    ];

    modulesCatalog.forEach((mod) => {
      const ModIcon = getModuleIcon(mod.icon);
      (mod.forms || []).forEach((fm) => {
        const path = fm.form_link.startsWith('/') ? fm.form_link : `/${fm.form_link}`;
        items.push({
          label: fm.form_name,
          path: path,
          icon: ModIcon,
          group: mod.module_name,
        });
      });
    });

    items.push({ label: 'Change Password', path: '/welcome/change_password', icon: LuAirplay, group: 'Account' });
    return items;
  }, [modulesCatalog]);

  const searchResults = useMemo(() => {
    const q = sidebarSearch.toLowerCase().trim();
    if (!q) return allNavItems;
    return allNavItems.filter(
      (item) =>
        item.label.toLowerCase().includes(q) ||
        item.group.toLowerCase().includes(q) ||
        item.path.toLowerCase().includes(q)
    );
  }, [allNavItems, sidebarSearch]);

  const handleRailClick = (mode) => {
    if (mode === 'home') {
      navigate('/dashboard');
      if (window.innerWidth < 1024) closeMobile();
      return;
    }
    if (mode === 'logout') {
      if (window.confirm('Sign out of Olive English School?')) {
        logout();
        navigate('/login');
      }
      return;
    }

    if (panelMode === mode && isExpanded) {
      setIsExpanded(false);
    } else {
      setPanelMode(mode);
      setIsExpanded(true);
    }
  };

  const displayName = user?.fullName || user?.name || user?.username || 'Administrator';
  const displayRole = user?.role ? user.role.toUpperCase() : 'ADMIN';
  const userInitial = (displayName || 'A').charAt(0).toUpperCase();

  const isFormActive = (link) => {
    const cleanLink = (link.startsWith('/') ? link : `/${link}`).split('?')[0].toLowerCase();
    const current = location.pathname.toLowerCase();
    return current === cleanLink || current === `${cleanLink}/`;
  };

  return (
    <>
      {/* Mobile Backdrop */}
      {isMobileOpen && (
        <div
          className="fixed inset-0 bg-black/40 backdrop-blur-[1px] z-[110] lg:hidden"
          onClick={closeMobile}
          aria-hidden="true"
        />
      )}

      {/* Main Sidebar Wrapper */}
      <aside
        id="sidebar"
        className={`fixed top-0 left-0 bottom-0 z-[120] flex bg-white border-r border-[#e7e7e4] overflow-hidden transition-[width,transform] duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] ${
          isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        }`}
        style={{ width: isExpanded ? '292px' : '56px' }}
      >
        {/* 1. SLIM RAIL (56px) */}
        <div className="sb-rail">
          {/* Brand Badge */}
          <div
            className="sb-brand cursor-pointer hover:opacity-90 transition-opacity"
            title="Olive English School"
            onClick={() => handleRailClick('home')}
          >
            <LuGraduationCap size={20} className="text-white" />
          </div>

          {/* Home / Dashboard */}
          <button
            type="button"
            className={`sb-rail-btn ${location.pathname === '/dashboard' && !isExpanded ? 'active' : ''}`}
            onClick={() => handleRailClick('home')}
            title="Dashboard"
          >
            <LuHouse size={18} />
          </button>

          {/* Menu / Tree */}
          <button
            type="button"
            className={`sb-rail-btn ${isExpanded && panelMode === 'grid' ? 'active' : ''}`}
            onClick={() => handleRailClick('grid')}
            title="Navigation Menu"
          >
            <LuLayoutGrid size={18} />
          </button>

          {/* Search */}
          <button
            type="button"
            className={`sb-rail-btn ${isExpanded && panelMode === 'search' ? 'active' : ''}`}
            onClick={() => handleRailClick('search')}
            title="Search Forms & Modules"
          >
            <LuSearch size={18} />
          </button>

          {/* Folder / Frequent Forms */}
          <button
            type="button"
            className={`sb-rail-btn ${isExpanded && panelMode === 'folder' ? 'active' : ''}`}
            onClick={() => handleRailClick('folder')}
            title="Frequent Forms"
          >
            <LuFolder size={18} />
          </button>

          {/* Help */}
          <button
            type="button"
            className={`sb-rail-btn ${isExpanded && panelMode === 'help' ? 'active' : ''}`}
            onClick={() => handleRailClick('help')}
            title="Help & Shortcuts"
          >
            <LuCircleHelp size={18} />
          </button>

          {/* Spacer */}
          <div className="flex-1" />

          {/* Sign Out */}
          <button
            type="button"
            className="sb-rail-btn text-[#73736f] hover:text-red-600 hover:bg-red-50"
            onClick={() => handleRailClick('logout')}
            title="Sign out"
          >
            <LuLogOut size={18} />
          </button>

          {/* User Initial Avatar */}
          <div className="pt-1 pb-1">
            <button
              type="button"
              className="w-9 h-9 rounded-[10px] flex items-center justify-center font-semibold text-[13px] text-[#1976d2] bg-[#ebf3fa] hover:opacity-90 transition cursor-pointer border-0"
              title={`${displayName} (${displayRole})`}
              onClick={() => handleRailClick('grid')}
            >
              {userInitial}
            </button>
          </div>
        </div>

        {/* 2. EXPANDED FLYOUT PANEL (236px) */}
        {isExpanded && (
          <div className="sb-panel">
            {/* Panel Header */}
            <div className="sb-panel-head flex items-center justify-between">
              <span className="sb-panel-title">
                {panelMode === 'grid' && 'MENU'}
                {panelMode === 'search' && 'SEARCH FORMS'}
                {panelMode === 'folder' && 'FREQUENT FORMS'}
                {panelMode === 'help' && 'HELP & SHORTCUTS'}
              </span>
              <button
                type="button"
                onClick={() => setIsExpanded(false)}
                className="p-1 rounded-md text-[#858580] hover:text-[#202020] hover:bg-[#f5f5f3] cursor-pointer border-0 bg-transparent"
                title="Collapse Panel"
              >
                <LuX size={15} />
              </button>
            </div>

            {/* Panel Body */}
            <div className="flex-1 overflow-y-auto py-2">
              {/* PANE: GRID (COMPLETE CI3 NAVIGATION TREE) */}
              {panelMode === 'grid' && (
                <div className="pb-6">
                  {/* CI3 Caption: Administrative */}
                  <div className="sgroup">Administrative</div>

                  {/* Dashboard */}
                  <Link
                    to="/dashboard"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className={`sitem ${location.pathname === '/dashboard' || location.pathname === '/' ? 'active' : ''}`}
                  >
                    <LuLayoutGrid size={15} />
                    <span className="truncate">Dashboard</span>
                  </Link>

                  {/* HR Dashboard (Conditional in CI3) */}
                  <Link
                    to="/module"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className={`sitem ${location.pathname === '/module' ? 'active' : ''}`}
                  >
                    <LuLayoutGrid size={15} />
                    <span className="truncate">HR Dashboard</span>
                  </Link>

                  {/* All 17 CI3 Modules & Submenus with Exact Hierarchy */}
                  {modulesCatalog.map((module) => {
                    const modKey = module.module_id || module.module_name;
                    const isOpenModule = Boolean(openModules[modKey]);
                    const ModIcon = getModuleIcon(module.icon);
                    const forms = module.forms || [];

                    return (
                      <div key={modKey} className="mt-0.5">
                        {/* Module Accordion Header */}
                        <button
                          type="button"
                          onClick={() => toggleModule(modKey)}
                          className="sitem justify-between w-full"
                          aria-expanded={isOpenModule}
                        >
                          <div className="flex items-center gap-2.5 min-w-0">
                            <ModIcon size={15} className="shrink-0 text-[#1976d2]" />
                            <span className="truncate">{module.module_name}</span>
                          </div>
                          {isOpenModule ? (
                            <LuChevronDown size={14} className="text-[#858580] shrink-0" />
                          ) : (
                            <LuChevronRight size={14} className="text-[#858580] shrink-0" />
                          )}
                        </button>

                        {/* Nested Submenu: Forms under this Module */}
                        {isOpenModule && (
                          <div className="pl-6 pr-2 py-0.5 space-y-0.5">
                            {forms.map((form) => {
                              const formPath = form.form_link.startsWith('/')
                                ? form.form_link
                                : `/${form.form_link}`;
                              const active = isFormActive(formPath);

                              return (
                                <Link
                                  key={form.id || form.form_link}
                                  to={formPath}
                                  onClick={() => window.innerWidth < 1024 && handleClose()}
                                  className={`sitem text-[12px] !py-1.5 ${active ? 'active' : ''}`}
                                  title={form.form_name}
                                >
                                  <span className="truncate">{form.form_name}</span>
                                </Link>
                              );
                            })}
                          </div>
                        )}
                      </div>
                    );
                  })}

                  {/* CI3 Bottom Menu Items: Change Password & Log out */}
                  <div className="sgroup mt-3">Account</div>
                  <Link
                    to="/welcome/change_password"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className={`sitem ${location.pathname === '/welcome/change_password' ? 'active' : ''}`}
                  >
                    <LuAirplay size={15} />
                    <span className="truncate">Change Password</span>
                  </Link>

                  <button
                    type="button"
                    onClick={() => handleRailClick('logout')}
                    className="sitem text-left w-full hover:!text-red-600 hover:!bg-red-50"
                  >
                    <LuPower size={15} />
                    <span className="truncate">Log out</span>
                  </button>
                </div>
              )}

              {/* PANE: SEARCH (INSTANT LIVE FILTER ACROSS ALL 141+ FORMS) */}
              {panelMode === 'search' && (
                <div className="px-3 py-1 space-y-3">
                  <div className="relative">
                    <input
                      type="search"
                      value={sidebarSearch}
                      onChange={(e) => setSidebarSearch(e.target.value)}
                      placeholder="Search 140+ forms..."
                      className="w-full h-9 pl-3 pr-8 rounded-lg border border-[#dededb] text-[12px] text-[#202020] focus:outline-none focus:border-[#1976d2]"
                      autoFocus
                    />
                    {sidebarSearch && (
                      <button
                        type="button"
                        onClick={() => setSidebarSearch('')}
                        className="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#858580] hover:text-[#202020] border-0 bg-transparent cursor-pointer"
                        aria-label="Clear search"
                      >
                        <LuX size={13} />
                      </button>
                    )}
                  </div>

                  <div className="space-y-1 max-h-[calc(100vh-180px)] overflow-y-auto">
                    {searchResults.length > 0 ? (
                      searchResults.map((item) => {
                        const Icon = item.icon || LuFileText;
                        const active = isFormActive(item.path);

                        return (
                          <Link
                            key={`${item.group}-${item.path}-${item.label}`}
                            to={item.path}
                            onClick={() => window.innerWidth < 1024 && handleClose()}
                            className={`sitem text-[12px] !py-2 ${active ? 'active' : ''}`}
                          >
                            <Icon size={14} className="text-[#1976d2] shrink-0" />
                            <div className="min-w-0 flex-1">
                              <p className="font-medium text-[#202020] truncate m-0">
                                {item.label}
                              </p>
                              <p className="text-[10px] text-[#858580] truncate m-0">
                                {item.group} · {item.path}
                              </p>
                            </div>
                          </Link>
                        );
                      })
                    ) : (
                      <p className="text-[12px] text-[#858580] text-center py-4">
                        No forms matching &ldquo;{sidebarSearch}&rdquo;.
                      </p>
                    )}
                  </div>
                </div>
              )}

              {/* PANE: FOLDER (FREQUENT ADMINISTRATIVE FORMS) */}
              {panelMode === 'folder' && (
                <div className="px-1 py-1 space-y-1">
                  <Link
                    to="/admission-form"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className="sb-folder-item"
                  >
                    <div className="sb-folder-icon text-[#1976d2]">
                      <LuUsers size={16} />
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="font-semibold text-[12.5px] text-[#202020] truncate m-0">
                        Student Admission Form
                      </p>
                      <p className="text-[10px] text-[#858580] truncate m-0">
                        Student Module · admission-form
                      </p>
                    </div>
                  </Link>

                  <Link
                    to="/fees"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className="sb-folder-item"
                  >
                    <div className="sb-folder-icon text-[#1976d2]">
                      <LuDollarSign size={16} />
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="font-semibold text-[12.5px] text-[#202020] truncate m-0">
                        Fees Form
                      </p>
                      <p className="text-[10px] text-[#858580] truncate m-0">
                        Fees Module · fees
                      </p>
                    </div>
                  </Link>

                  <Link
                    to="/student-attendance"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className="sb-folder-item"
                  >
                    <div className="sb-folder-icon text-[#1976d2]">
                      <LuSquareCheck size={16} />
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="font-semibold text-[12.5px] text-[#202020] truncate m-0">
                        Student Attendance
                      </p>
                      <p className="text-[10px] text-[#858580] truncate m-0">
                        Attendance Module · student-attendance
                      </p>
                    </div>
                  </Link>

                  <Link
                    to="/view-timetable"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className="sb-folder-item"
                  >
                    <div className="sb-folder-icon text-[#1976d2]">
                      <LuList size={16} />
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="font-semibold text-[12.5px] text-[#202020] truncate m-0">
                        View Time Table
                      </p>
                      <p className="text-[10px] text-[#858580] truncate m-0">
                        Section Module · view-timetable
                      </p>
                    </div>
                  </Link>

                  <Link
                    to="/Marks/Student_marks"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className="sb-folder-item"
                  >
                    <div className="sb-folder-icon text-[#1976d2]">
                      <LuClipboardList size={16} />
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="font-semibold text-[12.5px] text-[#202020] truncate m-0">
                        Student Mark
                      </p>
                      <p className="text-[10px] text-[#858580] truncate m-0">
                        Section Module · Marks/Student_marks
                      </p>
                    </div>
                  </Link>

                  <Link
                    to="/books-and-result"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className="sb-folder-item"
                  >
                    <div className="sb-folder-icon text-[#1976d2]">
                      <LuServer size={16} />
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="font-semibold text-[12.5px] text-[#202020] truncate m-0">
                        Books & Result
                      </p>
                      <p className="text-[10px] text-[#858580] truncate m-0">
                        Administrative · books-and-result
                      </p>
                    </div>
                  </Link>

                  <Link
                    to="/Generate-Meeting"
                    onClick={() => window.innerWidth < 1024 && handleClose()}
                    className="sb-folder-item"
                  >
                    <div className="sb-folder-icon text-[#1976d2]">
                      <LuPackage size={16} />
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="font-semibold text-[12.5px] text-[#202020] truncate m-0">
                        Generate Meeting
                      </p>
                      <p className="text-[10px] text-[#858580] truncate m-0">
                        Parents Forms · Generate-Meeting
                      </p>
                    </div>
                  </Link>
                </div>
              )}

              {/* PANE: HELP (ERP GUIDE & SHORTCUTS) */}
              {panelMode === 'help' && (
                <div className="px-3 py-2 space-y-3 text-[12px] text-[#555]">
                  <div>
                    <p className="font-semibold text-[#202020] m-0 mb-1">
                      Keyboard Shortcuts:
                    </p>
                    <div className="p-2.5 rounded-lg bg-[#f5f5f3] space-y-1.5 text-[11px]">
                      <div className="flex items-center justify-between">
                        <span>Quick Search Forms</span>
                        <kbd className="px-1.5 py-0.5 bg-white border border-[#dededb] rounded font-mono text-[10px]">
                          Ctrl + K
                        </kbd>
                      </div>
                      <div className="flex items-center justify-between">
                        <span>Close Panel / Modal</span>
                        <kbd className="px-1.5 py-0.5 bg-white border border-[#dededb] rounded font-mono text-[10px]">
                          Esc
                        </kbd>
                      </div>
                    </div>
                  </div>

                  <div className="pt-2 border-t border-[#f0f0ee]">
                    <p className="font-semibold text-[#202020] m-0 mb-1">
                      Olive English School ERP
                    </p>
                    <p className="text-[11px] text-[#73736f] m-0 leading-relaxed">
                      Unified management system featuring 17 modules and 140+ specialized forms for school operations.
                    </p>
                  </div>
                </div>
              )}
            </div>

            {/* Panel Footer: User Account Pill */}
            <div className="p-3 border-t border-[#e7e7e4] bg-[#fafaf9] flex items-center justify-between gap-2 shrink-0">
              <div className="flex items-center gap-2 min-w-0">
                <div className="w-8 h-8 rounded-[8px] flex items-center justify-center font-semibold text-[12px] text-[#1976d2] bg-[#ebf3fa] shrink-0">
                  {userInitial}
                </div>
                <div className="min-w-0">
                  <p className="text-[12px] font-semibold text-[#202020] truncate m-0">
                    {displayName}
                  </p>
                  <p className="text-[10px] text-[#858580] uppercase tracking-wider truncate m-0">
                    {displayRole}
                  </p>
                </div>
              </div>
              <button
                type="button"
                onClick={() => handleRailClick('logout')}
                className="p-1.5 rounded-md text-[#73736f] hover:text-red-600 hover:bg-white transition cursor-pointer border-0 bg-transparent"
                title="Sign out"
              >
                <LuLogOut size={15} />
              </button>
            </div>
          </div>
        )}
      </aside>
    </>
  );
}
