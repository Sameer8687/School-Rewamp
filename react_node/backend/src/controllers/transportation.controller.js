/**
 * Transportation Controller — Handlers for Olive English School Bus & Transportation
 */
import TransportationModel from '../models/Transportation.model.js';

// ─── Drivers ───
export const getDrivers = async (_req, res) => {
  try {
    const list = await TransportationModel.getDrivers();
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getDrivers error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createDriver = async (req, res) => {
  try {
    const { name, whatsapp, address, vehicle } = req.body;
    const mobile = req.body.mobile || req.body.phone;
    if (!name || !mobile) {
      return res.status(400).json({ success: false, message: 'Driver name and mobile number are required' });
    }
    const result = await TransportationModel.createDriver({ name, mobile, whatsapp, address, vehicle });
    res.status(201).json({ success: true, message: 'Driver registered successfully', data: result });
  } catch (error) {
    console.error('createDriver error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateDriver = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, mobile, whatsapp, address, vehicle } = req.body;
    const result = await TransportationModel.updateDriver(Number(id), { name, mobile, whatsapp, address, vehicle });
    res.json({ success: true, message: 'Driver updated successfully', data: result });
  } catch (error) {
    console.error('updateDriver error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteDriver = async (req, res) => {
  try {
    const { id } = req.params;
    const success = await TransportationModel.deleteDriver(Number(id));
    if (!success) {
      return res.status(404).json({ success: false, message: 'Driver not found' });
    }
    res.json({ success: true, message: 'Driver deleted successfully' });
  } catch (error) {
    console.error('deleteDriver error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Vehicles ───
export const getVehicles = async (_req, res) => {
  try {
    const list = await TransportationModel.getVehicles();
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getVehicles error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createVehicle = async (req, res) => {
  try {
    const name = req.body.name || req.body.vehicle_name || req.body.route_name || 'School Bus';
    const vehicle_no = req.body.vehicle_no || req.body.vehicle_number || req.body.registration_number || req.body.registration_no;
    const wheel_type = req.body.wheel_type || 'Bus';
    const seats = req.body.seats || req.body.capacity || 30;

    if (!name || !vehicle_no) {
      return res.status(400).json({ success: false, message: 'Vehicle name and registration number are required' });
    }
    const result = await TransportationModel.createVehicle({
      name,
      wheel_type,
      vehicle_no,
      seats: Number(seats) || 30,
    });
    res.status(201).json({ success: true, message: 'Vehicle registered successfully', data: result });
  } catch (error) {
    console.error('createVehicle error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateVehicle = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, wheel_type, vehicle_no, seats } = req.body;
    const result = await TransportationModel.updateVehicle(Number(id), {
      name,
      wheel_type,
      vehicle_no,
      seats: seats ? Number(seats) : 30,
    });
    res.json({ success: true, message: 'Vehicle updated successfully', data: result });
  } catch (error) {
    console.error('updateVehicle error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteVehicle = async (req, res) => {
  try {
    const { id } = req.params;
    const success = await TransportationModel.deleteVehicle(Number(id));
    if (!success) {
      return res.status(404).json({ success: false, message: 'Vehicle not found' });
    }
    res.json({ success: true, message: 'Vehicle deleted successfully' });
  } catch (error) {
    console.error('deleteVehicle error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Student Transport Mappings ───
export const getStudentTransport = async (req, res) => {
  try {
    const standard = req.query.standard || req.query.std || 'all';
    const division = req.query.division || req.query.div || 'all';
    const list = await TransportationModel.getStudentTransport(standard, division);
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getStudentTransport error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const assignStudentTransport = async (req, res) => {
  try {
    const studentId = req.body.studentId || req.body.student_id;
    const driverId = req.body.driverId || req.body.driver_id || req.body.vehicle_id;
    if (!studentId) {
      return res.status(400).json({ success: false, message: 'Student ID is required' });
    }
    const result = await TransportationModel.assignStudentTransport({
      studentId: Number(studentId),
      driverId: driverId ? Number(driverId) : null,
      user: req.user?.username || 'admin',
    });
    res.json({ success: true, message: 'Student transport updated', data: result });
  } catch (error) {
    console.error('assignStudentTransport error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};
