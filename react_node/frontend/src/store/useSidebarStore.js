import { create } from 'zustand';

const getInitialExpanded = () => {
  if (typeof window === 'undefined') return true;
  const saved = localStorage.getItem('olive_sidebar_expanded');
  if (saved !== null) return saved === 'true';
  return window.innerWidth >= 1024;
};

const applySidebarWidth = (expanded) => {
  if (typeof document !== 'undefined') {
    document.documentElement.style.setProperty('--sb-width', expanded ? '292px' : '56px');
    document.documentElement.setAttribute('data-sidebar-expanded', expanded ? 'true' : 'false');
  }
};

const initialExpanded = getInitialExpanded();
applySidebarWidth(initialExpanded);

export const useSidebarStore = create((set, get) => ({
  isExpanded: initialExpanded,
  panelMode: 'grid', // 'grid' | 'search' | 'folder' | 'help'

  toggleExpanded: () => {
    const next = !get().isExpanded;
    try {
      localStorage.setItem('olive_sidebar_expanded', String(next));
    } catch (e) {}
    applySidebarWidth(next);
    set({ isExpanded: next });
  },

  setIsExpanded: (val) => {
    try {
      localStorage.setItem('olive_sidebar_expanded', String(val));
    } catch (e) {}
    applySidebarWidth(val);
    set({ isExpanded: val });
  },

  setPanelMode: (mode) => set({ panelMode: mode }),

  // Mobile drawer state
  isMobileOpen: false,
  toggleMobile: () => set((state) => ({ isMobileOpen: !state.isMobileOpen })),
  setMobileOpen: (open) => set({ isMobileOpen: open }),
  closeMobile: () => set({ isMobileOpen: false }),
}));

export default useSidebarStore;
