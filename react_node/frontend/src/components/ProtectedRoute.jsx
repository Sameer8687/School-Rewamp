/**
 * Protected Route wrapper.
 * Replaces CI3's constructor-level session checks like:
 *   if (!$this->session->userdata('oliveSchoolDashboardUser')) redirect('login');
 */
import React, { useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import useAuthStore from '../store/useAuthStore.js';

export default function ProtectedRoute({ children, allowedRoles }) {
  const navigate = useNavigate();
  const location = useLocation();
  const { isAuthenticated, isLoading, user, checkAuth, token } = useAuthStore();

  useEffect(() => {
    if (token && !user) {
      checkAuth();
    }
  }, [token, user, checkAuth]);

  useEffect(() => {
    // Only redirect if no token exists in storage and checkAuth is not running
    if (!isLoading && !isAuthenticated && !token) {
      navigate('/login', { replace: true, state: { from: location } });
    }
  }, [isLoading, isAuthenticated, token, navigate, location]);

  // Loading spinner while verifying token
  if (isLoading || (!isAuthenticated && token)) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#f4f7fb]">
        <div className="olive-spinner !border-[#1976d2] !border-t-[#1976d2]/20 w-10 h-10" />
      </div>
    );
  }

  if (!isAuthenticated) {
    return null;
  }

  // Role gate
  if (allowedRoles && user && !allowedRoles.includes(user.role)) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[#f4f7fb]">
        <div className="bg-white rounded-2xl shadow-lg p-8 max-w-md text-center">
          <h2 className="text-xl font-bold text-[#1e293b] mb-2">Access Denied</h2>
          <p className="text-[#64748b]">You do not have permission to view this page.</p>
        </div>
      </div>
    );
  }

  return <>{children}</>;
}
