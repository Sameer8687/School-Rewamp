/**
 * Transportation Routes — Express routing for Olive English School Transportation
 */
import express from 'express';
import {
  getDrivers,
  createDriver,
  updateDriver,
  deleteDriver,
  getVehicles,
  createVehicle,
  updateVehicle,
  deleteVehicle,
  getStudentTransport,
  assignStudentTransport,
} from '../controllers/transportation.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

// ── Drivers ──
router.get('/drivers', getDrivers);
router.post('/drivers', createDriver);
router.put('/drivers/:id', updateDriver);
router.delete('/drivers/:id', deleteDriver);

// ── Vehicles & Routes ──
router.get('/vehicles', getVehicles);
router.get('/routes', getVehicles);
router.get('/options', getVehicles);
router.post('/vehicles', createVehicle);
router.put('/vehicles/:id', updateVehicle);
router.delete('/vehicles/:id', deleteVehicle);

// ── Student Transport Mappings ──
router.get('/students', getStudentTransport);
router.post('/assign-student', assignStudentTransport);
router.post('/assign', assignStudentTransport);

export default router;
