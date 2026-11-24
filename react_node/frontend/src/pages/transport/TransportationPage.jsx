import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import transportationService from '../../services/transportationService';
import Sidebar from '../../components/Sidebar.jsx';
import TopNav from '../../components/TopNav.jsx';
import {
  FiTruck,
  FiUser,
  FiUsers,
  FiPlus,
  FiEdit2,
  FiTrash2,
  FiSave,
  FiCheckCircle,
  FiAlertCircle,
  FiFilter,
  FiPhone,
  FiX,
} from 'react-icons/fi';
import { FaWhatsapp } from 'react-icons/fa';

const CLASSES = ['NURSERY', 'JR.KG', 'SR.KG', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
const DIVISIONS = ['A', 'B', 'C', 'D'];

export default function TransportationPage() {
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [activeTab, setActiveTab] = useState('drivers'); // 'drivers' | 'vehicles' | 'students'

  // Auto-switch tab based on CI3 route
  useEffect(() => {
    const p = (location.pathname || '').toLowerCase();
    if (p.includes('vehicle_master') || p.includes('vehicle')) {
      setActiveTab('vehicles');
    } else if (p.includes('student_details') || p.includes('student')) {
      setActiveTab('students');
    } else if (p.includes('driver')) {
      setActiveTab('drivers');
    }
  }, [location.pathname]);
  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState({ text: '', type: '' });

  // Data states
  const [drivers, setDrivers] = useState([]);
  const [vehicles, setVehicles] = useState([]);

  // Modals
  const [isDriverModalOpen, setIsDriverModalOpen] = useState(false);
  const [editDriverId, setEditDriverId] = useState(null);
  const [driverForm, setDriverForm] = useState({ name: '', mobile: '', whatsapp: '', address: '', vehicle: '' });

  const [isVehicleModalOpen, setIsVehicleModalOpen] = useState(false);
  const [editVehicleId, setEditVehicleId] = useState(null);
  const [vehicleForm, setVehicleForm] = useState({ name: '', wheel_type: 'Bus', vehicle_no: '', seats: 30 });

  // Student Mapping Tab
  const [selectedClass, setSelectedClass] = useState('I');
  const [selectedDiv, setSelectedDiv] = useState('A');
  const [studentsList, setStudentsList] = useState([]);

  useEffect(() => {
    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    try {
      setLoading(true);
      const [dRes, vRes] = await Promise.all([
        transportationService.getDrivers(),
        transportationService.getVehicles(),
      ]);
      setDrivers(dRes.data?.data || []);
      setVehicles(vRes.data?.data || []);
    } catch (err) {
      console.error('Failed to load transport data:', err);
    } finally {
      setLoading(false);
    }
  };

  // ─── Driver Handlers ───
  const handleOpenAddDriver = () => {
    setEditDriverId(null);
    setDriverForm({ name: '', mobile: '', whatsapp: '', address: '', vehicle: '' });
    setIsDriverModalOpen(true);
  };

  const handleOpenEditDriver = (d) => {
    setEditDriverId(d.id);
    setDriverForm({
      name: d.name,
      mobile: d.mobile || '',
      whatsapp: d.whatsapp || '',
      address: d.address || '',
      vehicle: d.vehicle || '',
    });
    setIsDriverModalOpen(true);
  };

  const handleSaveDriver = async (e) => {
    e.preventDefault();
    try {
      setSaving(true);
      setMessage({ text: '', type: '' });
      if (editDriverId) {
        await transportationService.updateDriver(editDriverId, driverForm);
        setMessage({ text: 'Driver details updated successfully!', type: 'success' });
      } else {
        await transportationService.createDriver(driverForm);
        setMessage({ text: 'New driver registered successfully!', type: 'success' });
      }
      setIsDriverModalOpen(false);
      const res = await transportationService.getDrivers();
      setDrivers(res.data?.data || []);
    } catch (err) {
      console.error('Save driver error:', err);
      setMessage({ text: 'Failed to save driver.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  const handleDeleteDriver = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete driver "${name}"?`)) return;
    try {
      await transportationService.deleteDriver(id);
      setMessage({ text: 'Driver deleted successfully!', type: 'success' });
      const res = await transportationService.getDrivers();
      setDrivers(res.data?.data || []);
    } catch (err) {
      console.error('Delete driver error:', err);
      setMessage({ text: 'Failed to delete driver.', type: 'error' });
    }
  };

  // ─── Vehicle Handlers ───
  const handleOpenAddVehicle = () => {
    setEditVehicleId(null);
    setVehicleForm({ name: '', wheel_type: 'Bus', vehicle_no: '', seats: 30 });
    setIsVehicleModalOpen(true);
  };

  const handleOpenEditVehicle = (v) => {
    setEditVehicleId(v.id);
    setVehicleForm({
      name: v.name,
      wheel_type: v.wheel_type || 'Bus',
      vehicle_no: v.vehicle_no,
      seats: v.seats || 30,
    });
    setIsVehicleModalOpen(true);
  };

  const handleSaveVehicle = async (e) => {
    e.preventDefault();
    try {
      setSaving(true);
      setMessage({ text: '', type: '' });
      if (editVehicleId) {
        await transportationService.updateVehicle(editVehicleId, vehicleForm);
        setMessage({ text: 'Vehicle updated successfully!', type: 'success' });
      } else {
        await transportationService.createVehicle(vehicleForm);
        setMessage({ text: 'Vehicle registered successfully!', type: 'success' });
      }
      setIsVehicleModalOpen(false);
      const res = await transportationService.getVehicles();
      setVehicles(res.data?.data || []);
    } catch (err) {
      console.error('Save vehicle error:', err);
      setMessage({ text: 'Failed to save vehicle.', type: 'error' });
    } finally {
      setSaving(false);
    }
  };

  const handleDeleteVehicle = async (id, name) => {
    if (!window.confirm(`Are you sure you want to delete vehicle "${name}"?`)) return;
    try {
      await transportationService.deleteVehicle(id);
      setMessage({ text: 'Vehicle deleted successfully!', type: 'success' });
      const res = await transportationService.getVehicles();
      setVehicles(res.data?.data || []);
    } catch (err) {
      console.error('Delete vehicle error:', err);
      setMessage({ text: 'Failed to delete vehicle.', type: 'error' });
    }
  };

  // ─── Student Transport Mapping Handlers ───
  const handleFetchStudents = async () => {
    try {
      setLoading(true);
      setMessage({ text: '', type: '' });
      const res = await transportationService.getStudentTransport({
        standard: selectedClass,
        division: selectedDiv,
      });
      setStudentsList(res.data?.data || []);
    } catch (err) {
      console.error('Failed to load student transport:', err);
      setMessage({ text: 'Failed to load students.', type: 'error' });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (activeTab === 'students' && selectedClass && selectedDiv) {
      handleFetchStudents();
    }
  }, [activeTab, selectedClass, selectedDiv]);

  const handleAssignDriver = async (studentId, driverId) => {
    try {
      await transportationService.assignStudentTransport({
        studentId,
        driverId: driverId ? Number(driverId) : null,
      });
      setStudentsList((prev) =>
        prev.map((s) =>
          s.student_id === studentId
            ? {
                ...s,
                driver_id: driverId ? Number(driverId) : null,
                driver_name: drivers.find((d) => String(d.id) === String(driverId))?.name || '',
              }
            : s
        )
      );
    } catch (err) {
      console.error('Failed to update student transport:', err);
      alert('Failed to update student transport mapping.');
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
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 className="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                  <FiTruck className="text-[#1976d2]" />
                  Transportation & Fleet Management
                </h1>
                <p className="text-xs text-slate-500 mt-1">
                  Manage school bus fleet, driver directory, and student commute routes
                </p>
              </div>

              {activeTab === 'drivers' && (
                <button
                  onClick={handleOpenAddDriver}
                  className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition"
                >
                  <FiPlus /> Add Driver
                </button>
              )}

              {activeTab === 'vehicles' && (
                <button
                  onClick={handleOpenAddVehicle}
                  className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] transition"
                >
                  <FiPlus /> Add Vehicle
                </button>
              )}
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

            {/* Tab navigation */}
            <div className="flex border-b border-slate-200 gap-4">
              <button
                onClick={() => setActiveTab('drivers')}
                className={`pb-3 text-xs font-semibold flex items-center gap-2 border-b-2 transition ${
                  activeTab === 'drivers'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-slate-500 hover:text-slate-800'
                }`}
              >
                <FiUser /> Drivers Directory ({drivers.length})
              </button>
              <button
                onClick={() => setActiveTab('vehicles')}
                className={`pb-3 text-xs font-semibold flex items-center gap-2 border-b-2 transition ${
                  activeTab === 'vehicles'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-slate-500 hover:text-slate-800'
                }`}
              >
                <FiTruck /> Fleet Vehicles ({vehicles.length})
              </button>
              <button
                onClick={() => setActiveTab('students')}
                className={`pb-3 text-xs font-semibold flex items-center gap-2 border-b-2 transition ${
                  activeTab === 'students'
                    ? 'border-[#1976d2] text-[#1976d2]'
                    : 'border-transparent text-slate-500 hover:text-slate-800'
                }`}
              >
                <FiUsers /> Student Commute Allocations
              </button>
            </div>

            {/* TAB 1: Drivers Master */}
            {activeTab === 'drivers' && (
              <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                  <h2 className="text-sm font-bold text-slate-800">Authorized Drivers</h2>
                </div>

                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                        <th className="py-3 px-4 w-12 text-center">#</th>
                        <th className="py-3 px-4">Driver Name</th>
                        <th className="py-3 px-4">Mobile / Contact</th>
                        <th className="py-3 px-4">Assigned Vehicle</th>
                        <th className="py-3 px-4">Address</th>
                        <th className="py-3 px-4 w-28 text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {drivers.length === 0 ? (
                        <tr>
                          <td colSpan="6" className="py-8 text-center text-slate-400">
                            {loading ? 'Loading drivers...' : 'No drivers registered in the fleet.'}
                          </td>
                        </tr>
                      ) : (
                        drivers.map((d, idx) => (
                          <tr key={d.id} className="hover:bg-slate-50 transition-colors">
                            <td className="py-3 px-4 text-center font-mono text-slate-400">{idx + 1}</td>
                            <td className="py-3 px-4 font-bold text-slate-900">{d.name}</td>
                            <td className="py-3 px-4">
                              <div className="flex items-center gap-2">
                                <a
                                  href={`tel:${d.mobile}`}
                                  className="text-slate-700 hover:text-[#1976d2] font-medium flex items-center gap-1"
                                >
                                  <FiPhone size={12} /> {d.mobile || '--'}
                                </a>
                                {d.whatsapp && (
                                  <a
                                    href={`https://wa.me/91${d.whatsapp}`}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="text-emerald-600 hover:text-emerald-700"
                                    title="WhatsApp Driver"
                                  >
                                    <FaWhatsapp size={14} />
                                  </a>
                                )}
                              </div>
                            </td>
                            <td className="py-3 px-4 font-semibold text-slate-700">{d.vehicle || 'Unassigned'}</td>
                            <td className="py-3 px-4 text-slate-500">{d.address || '--'}</td>
                            <td className="py-3 px-4 text-center space-x-2">
                              <button
                                onClick={() => handleOpenEditDriver(d)}
                                className="p-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition"
                                title="Edit Driver"
                              >
                                <FiEdit2 size={13} />
                              </button>
                              <button
                                onClick={() => handleDeleteDriver(d.id, d.name)}
                                className="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition"
                                title="Delete Driver"
                              >
                                <FiTrash2 size={13} />
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

            {/* TAB 2: Vehicles Fleet */}
            {activeTab === 'vehicles' && (
              <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                  <h2 className="text-sm font-bold text-slate-800">Fleet Vehicles List</h2>
                </div>

                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse text-xs">
                    <thead>
                      <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                        <th className="py-3 px-4 w-12 text-center">#</th>
                        <th className="py-3 px-4">Vehicle Model / Name</th>
                        <th className="py-3 px-4">Type</th>
                        <th className="py-3 px-4 font-mono">Reg. Number</th>
                        <th className="py-3 px-4 text-center">Seating Capacity</th>
                        <th className="py-3 px-4 w-28 text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {vehicles.length === 0 ? (
                        <tr>
                          <td colSpan="6" className="py-8 text-center text-slate-400">
                            {loading ? 'Loading vehicles...' : 'No vehicles registered in fleet.'}
                          </td>
                        </tr>
                      ) : (
                        vehicles.map((v, idx) => (
                          <tr key={v.id} className="hover:bg-slate-50 transition-colors">
                            <td className="py-3 px-4 text-center font-mono text-slate-400">{idx + 1}</td>
                            <td className="py-3 px-4 font-bold text-slate-900">{v.name}</td>
                            <td className="py-3 px-4 text-slate-600">{v.wheel_type || 'Bus'}</td>
                            <td className="py-3 px-4 font-mono font-bold text-slate-700">{v.vehicle_no}</td>
                            <td className="py-3 px-4 text-center">
                              <span className="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#e3f2fd] text-[#1565c0]">
                                {v.seats} Seats
                              </span>
                            </td>
                            <td className="py-3 px-4 text-center space-x-2">
                              <button
                                onClick={() => handleOpenEditVehicle(v)}
                                className="p-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition"
                                title="Edit Vehicle"
                              >
                                <FiEdit2 size={13} />
                              </button>
                              <button
                                onClick={() => handleDeleteVehicle(v.id, v.name)}
                                className="p-1.5 text-rose-600 hover:bg-rose-50 rounded transition"
                                title="Delete Vehicle"
                              >
                                <FiTrash2 size={13} />
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

            {/* TAB 3: Student Commute Mappings */}
            {activeTab === 'students' && (
              <div className="space-y-6">
                {/* Filter */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
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
                        Division
                      </label>
                      <select
                        value={selectedDiv}
                        onChange={(e) => setSelectedDiv(e.target.value)}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        {DIVISIONS.map((d) => (
                          <option key={d} value={d}>
                            Div {d}
                          </option>
                        ))}
                      </select>
                    </div>
                  </div>
                </div>

                {/* Table */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                  <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 className="text-sm font-bold text-slate-800">
                      Transport Allocations: Std {selectedClass}-{selectedDiv} ({studentsList.length} Students)
                    </h2>
                  </div>

                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-xs">
                      <thead>
                        <tr className="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                          <th className="py-3 px-4 w-12 text-center">#</th>
                          <th className="py-3 px-4 w-20 text-center">Roll No</th>
                          <th className="py-3 px-4 w-32">Enrollment ID</th>
                          <th className="py-3 px-4">Student Name</th>
                          <th className="py-3 px-4 w-60">Assigned Driver & Bus</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100">
                        {studentsList.length === 0 ? (
                          <tr>
                            <td colSpan="5" className="py-8 text-center text-slate-400">
                              {loading ? 'Loading students...' : 'No students found for this class and division.'}
                            </td>
                          </tr>
                        ) : (
                          studentsList.map((st, idx) => (
                            <tr key={st.student_id} className="hover:bg-slate-50 transition-colors">
                              <td className="py-3 px-4 text-center font-mono text-slate-400">{idx + 1}</td>
                              <td className="py-3 px-4 text-center font-bold text-slate-700">{st.roll_no || '--'}</td>
                              <td className="py-3 px-4 font-mono text-slate-500">{st.enrollment_no}</td>
                              <td className="py-3 px-4 font-semibold text-slate-800">{st.student_name}</td>
                              <td className="py-2 px-4">
                                <select
                                  value={st.driver_id || ''}
                                  onChange={(e) => handleAssignDriver(st.student_id, e.target.value)}
                                  className="w-full text-xs rounded border border-slate-300 p-1.5 bg-white outline-none focus:border-[#1976d2]"
                                >
                                  <option value="">-- No Transport (Self) --</option>
                                  {drivers.map((d) => (
                                    <option key={d.id} value={d.id}>
                                      {d.name} ({d.vehicle || 'Van'})
                                    </option>
                                  ))}
                                </select>
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

            {/* Modal: Add / Edit Driver */}
            {isDriverModalOpen && (
              <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
                <div className="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
                  <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                      <FiUser className="text-[#1976d2]" />
                      {editDriverId ? 'Edit Driver' : 'Register New Driver'}
                    </h3>
                    <button onClick={() => setIsDriverModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                      <FiX size={18} />
                    </button>
                  </div>

                  <form onSubmit={handleSaveDriver} className="p-6 space-y-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Driver Full Name
                      </label>
                      <input
                        type="text"
                        value={driverForm.name}
                        onChange={(e) => setDriverForm({ ...driverForm, name: e.target.value })}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Mobile Number
                      </label>
                      <input
                        type="text"
                        value={driverForm.mobile}
                        onChange={(e) => setDriverForm({ ...driverForm, mobile: e.target.value })}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        WhatsApp Number (Optional)
                      </label>
                      <input
                        type="text"
                        value={driverForm.whatsapp}
                        onChange={(e) => setDriverForm({ ...driverForm, whatsapp: e.target.value })}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Assigned Vehicle / Bus Number
                      </label>
                      <input
                        type="text"
                        value={driverForm.vehicle}
                        onChange={(e) => setDriverForm({ ...driverForm, vehicle: e.target.value })}
                        placeholder="e.g. MH-41-AB-1234 or Bus 1"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Residential Address
                      </label>
                      <textarea
                        value={driverForm.address}
                        onChange={(e) => setDriverForm({ ...driverForm, address: e.target.value })}
                        rows="2"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                      />
                    </div>

                    <div className="pt-3 border-t border-slate-200 flex justify-end gap-2">
                      <button
                        type="button"
                        onClick={() => setIsDriverModalOpen(false)}
                        className="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        disabled={saving}
                        className="flex items-center gap-1.5 px-5 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50"
                      >
                        <FiSave /> {saving ? 'Saving...' : 'Save Driver'}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            )}

            {/* Modal: Add / Edit Vehicle */}
            {isVehicleModalOpen && (
              <div className="fixed inset-0 bg-black/50 z-[100] flex items-center justify-center p-4">
                <div className="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
                  <div className="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                    <h3 className="text-sm font-bold text-slate-800 flex items-center gap-2">
                      <FiTruck className="text-[#1976d2]" />
                      {editVehicleId ? 'Edit Vehicle' : 'Register New Vehicle'}
                    </h3>
                    <button onClick={() => setIsVehicleModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                      <FiX size={18} />
                    </button>
                  </div>

                  <form onSubmit={handleSaveVehicle} className="p-6 space-y-4">
                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Vehicle Name / Model
                      </label>
                      <input
                        type="text"
                        value={vehicleForm.name}
                        onChange={(e) => setVehicleForm({ ...vehicleForm, name: e.target.value })}
                        placeholder="e.g. School Bus 01, Tata Winger"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Wheel Type / Category
                      </label>
                      <select
                        value={vehicleForm.wheel_type}
                        onChange={(e) => setVehicleForm({ ...vehicleForm, wheel_type: e.target.value })}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-[#1976d2] outline-none"
                      >
                        <option value="4 Wheeler Bus">4 Wheeler Bus</option>
                        <option value="Mini-Bus">Mini-Bus</option>
                        <option value="Van">Van / Traveler</option>
                        <option value="Auto">Auto / Other</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Registration Plate Number
                      </label>
                      <input
                        type="text"
                        value={vehicleForm.vehicle_no}
                        onChange={(e) => setVehicleForm({ ...vehicleForm, vehicle_no: e.target.value })}
                        placeholder="e.g. MH-15-EG-4567"
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 font-mono outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-semibold text-slate-600 uppercase mb-1">
                        Seating Capacity
                      </label>
                      <input
                        type="number"
                        min="1"
                        max="100"
                        value={vehicleForm.seats}
                        onChange={(e) => setVehicleForm({ ...vehicleForm, seats: e.target.value })}
                        className="w-full text-xs rounded-lg border border-slate-300 p-2.5 outline-none focus:ring-1 focus:ring-[#1976d2]"
                        required
                      />
                    </div>

                    <div className="pt-3 border-t border-slate-200 flex justify-end gap-2">
                      <button
                        type="button"
                        onClick={() => setIsVehicleModalOpen(false)}
                        className="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        disabled={saving}
                        className="flex items-center gap-1.5 px-5 py-2 text-xs font-semibold text-white bg-[#1976d2] rounded-lg shadow hover:bg-[#1565c0] disabled:opacity-50"
                      >
                        <FiSave /> {saving ? 'Saving...' : 'Save Vehicle'}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
