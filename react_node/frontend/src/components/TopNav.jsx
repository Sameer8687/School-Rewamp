/**
 * TopNav Component — Matched to OliSave English School reference design
 *
 * - Clean white top bar with subtle bottom border
 * - Left: Mobile drawer toggle + "OliSave English School" serif brand title (#1976d2)
 * - Right: Optional due reminders pill, Dashboard button, Fullscreen toggle, Search forms button + modal
 */
import React, { useState, useEffect, useRef } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import useAuthStore from '../store/useAuthStore.js';
import useSidebarStore from '../store/useSidebarStore.js';
import {
  LuMenu,
  LuHouse,
  LuMaximize,
  LuMinimize,
  LuSearch,
  LuBell,
  LuX,
} from 'react-icons/lu';

export default function TopNav({
  dueDateCount = 0,
  onOpenDueModal,
  formsList = [],
  onToggleSidebar,
  toggleSidebar,
  setSidebarOpen,
  onToggle,
}) {
  const navigate = useNavigate();
  const { user } = useAuthStore();
  const { isExpanded, toggleExpanded, toggleMobile } = useSidebarStore();

  const [isFullscreen, setIsFullscreen] = useState(false);
  const [showSearchModal, setShowSearchModal] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const searchInputRef = useRef(null);

  const handleToggle = () => {
    if (window.innerWidth >= 1024) {
      toggleExpanded();
    } else {
      toggleMobile();
    }
    if (onToggleSidebar) onToggleSidebar();
    else if (toggleSidebar) toggleSidebar();
    else if (onToggle) onToggle();
    else if (setSidebarOpen) setSidebarOpen((prev) => !prev);
  };

  useEffect(() => {
    const onFsChange = () => {
      setIsFullscreen(Boolean(document.fullscreenElement));
    };
    document.addEventListener('fullscreenchange', onFsChange);
    return () => document.removeEventListener('fullscreenchange', onFsChange);
  }, []);

  useEffect(() => {
    const onKeyDown = (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        setShowSearchModal(true);
      } else if (e.key === 'Escape' && showSearchModal) {
        setShowSearchModal(false);
      }
    };
    document.addEventListener('keydown', onKeyDown);
    return () => document.removeEventListener('keydown', onKeyDown);
  }, [showSearchModal]);

  useEffect(() => {
    if (showSearchModal) {
      setTimeout(() => searchInputRef.current?.focus(), 50);
    }
  }, [showSearchModal]);

  const handleToggleFullscreen = () => {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen().catch(() => { });
      setIsFullscreen(true);
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen().catch(() => { });
      }
      setIsFullscreen(false);
    }
  };

  const dashboardPath =
    user?.role === 'student' ? '/profile' : '/dashboard';

  const filteredForms = (formsList || []).filter((f) => {
    const q = searchQuery.toLowerCase().trim();
    if (!q) return true;
    return (
      (f.stvfrms_form_name || '').toLowerCase().includes(q) ||
      (f.stvfrms_url_name || '').toLowerCase().includes(q)
    );
  });

  return (
    <>
      <header
        id="topbar"
        className={`sticky top-0 z-40 h-14 bg-white border-b border-[#e7e7e4] flex items-center justify-between gap-3.5 pr-3 sm:pr-[18px] pl-3 sm:pl-[18px] transition-[padding-left] duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] print:hidden ${
          isExpanded ? 'lg:pl-[332px]' : 'lg:pl-[80px]'
        }`}
      >
        {/* Left: Mobile toggle + Brand Title */}
        <div className="flex items-center gap-3 shrink-0">
          <button
            type="button"
            onClick={handleToggle}
            className="lg:hidden inline-flex items-center justify-center h-[34px] w-[34px] rounded-lg border border-[#e7e7e4] bg-white hover:bg-[#f5f5f3] text-[#555] hover:text-[#202020] shrink-0 cursor-pointer transition-colors"
            aria-label="Open navigation menu"
          >
            <LuMenu size={16} />
          </button>

          <span
            className="hidden sm:inline-block text-[22px] md:text-[25px] font-semibold tracking-[-0.01em] text-[#1976d2] whitespace-nowrap select-none shrink-0"
            style={{ fontFamily: "'Playfair Display', Georgia, 'Times New Roman', serif" }}
          >
            Olive English School
          </span>
        </div>

        {/* Right: Action Buttons matching reference */}
        <div className="flex items-center gap-2 ml-auto shrink-0">
          {dueDateCount > 0 && onOpenDueModal && (
            <button
              type="button"
              onClick={onOpenDueModal}
              aria-label="View fee due reminders"
              className="inline-flex items-center justify-center gap-1.5 min-h-[34px] px-2.5 rounded-lg border border-[#e7e7e4] bg-white hover:bg-[#f5f5f3] text-[#555] hover:text-[#202020] text-[12px] font-medium cursor-pointer transition-colors"
            >
              <LuBell size={15} />
              <span className="hidden sm:inline">{dueDateCount} due</span>
            </button>
          )}

          <Link
            to={dashboardPath}
            aria-label="Back to dashboard"
            className="inline-flex items-center justify-center gap-1.5 min-h-[34px] px-2.5 sm:px-3 rounded-lg border border-[#e7e7e4] bg-white hover:bg-[#f5f5f3] text-[#555] hover:text-[#202020] text-[12px] font-medium no-underline transition-colors"
          >
            <LuHouse size={15} />
            <span className="hidden sm:inline">Dashboard</span>
          </Link>

          <button
            type="button"
            onClick={handleToggleFullscreen}
            aria-label="Toggle fullscreen"
            title={isFullscreen ? 'Exit Fullscreen' : 'Fullscreen'}
            className="inline-flex items-center justify-center min-h-[34px] w-[34px] rounded-lg border border-[#e7e7e4] bg-white hover:bg-[#f5f5f3] text-[#555] hover:text-[#202020] cursor-pointer transition-colors"
          >
            {isFullscreen ? <LuMinimize size={15} /> : <LuMaximize size={15} />}
          </button>

          <button
            type="button"
            onClick={() => setShowSearchModal(true)}
            aria-label="Search forms"
            className="inline-flex items-center justify-center gap-1.5 min-h-[34px] px-2.5 sm:px-3 rounded-lg border border-[#e7e7e4] bg-white hover:bg-[#f5f5f3] text-[#555] hover:text-[#202020] text-[12px] font-medium cursor-pointer transition-colors"
          >
            <LuSearch size={15} />
            <span className="hidden sm:inline">Search forms</span>
          </button>
        </div>
      </header>

      {/* Search School Forms Modal (matching CI3 topNav modal) */}
      {showSearchModal && (
        <div
          className="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-[1px] p-4"
          onClick={() => setShowSearchModal(false)}
        >
          <div
            className="w-full max-w-lg rounded-[14px] border border-[#e7e7e4] bg-white p-5 sm:p-6 shadow-xl"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="flex items-center justify-between mb-3.5">
              <h2 className="text-[16px] font-semibold text-[#202020] m-0">
                Search school forms
              </h2>
              <button
                type="button"
                onClick={() => setShowSearchModal(false)}
                aria-label="Close"
                className="p-1 rounded-md text-[#858580] hover:text-[#202020] hover:bg-[#f5f5f3] cursor-pointer"
              >
                <LuX size={18} />
              </button>
            </div>

            <div className="relative">
              <input
                ref={searchInputRef}
                type="search"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Type a form name"
                autoComplete="off"
                className="w-full h-10 pl-3 pr-9 rounded-lg border border-[#dededb] text-[13px] text-[#202020] focus:outline-none focus:border-[#1976d2]"
              />
              {searchQuery && (
                <button
                  type="button"
                  onClick={() => setSearchQuery('')}
                  aria-label="Clear search"
                  className="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#858580] hover:text-[#202020] cursor-pointer"
                >
                  <LuX size={15} />
                </button>
              )}
            </div>

            <div className="mt-3 max-h-[360px] overflow-y-auto rounded-lg border border-[#efefed] divide-y divide-[#f2f2ef]">
              {filteredForms.length > 0 ? (
                filteredForms.slice(0, 40).map((f) => (
                  <button
                    key={f.stvfrms_id || f.stvfrms_url_name}
                    type="button"
                    onClick={() => {
                      setShowSearchModal(false);
                      setSearchQuery('');
                      navigate(`/${f.stvfrms_url_name}`);
                    }}
                    className="w-full text-left px-3.5 py-2.5 text-[12.5px] text-[#454542] hover:bg-[#f5f5f3] hover:text-[#202020] flex items-center justify-between gap-3 cursor-pointer transition-colors"
                  >
                    <span className="font-medium truncate">{f.stvfrms_form_name}</span>
                    <span className="text-[11px] font-mono text-[#858580] shrink-0">
                      /{f.stvfrms_url_name}
                    </span>
                  </button>
                ))
              ) : (
                <div className="px-4 py-6 text-center text-[12.5px] text-[#858580]">
                  No matching forms found for &ldquo;{searchQuery}&rdquo;.
                </div>
              )}
            </div>
          </div>
        </div>
      )}
    </>
  );
}
