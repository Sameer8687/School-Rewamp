/**
 * Transportation Model — Database operations for Olive English School Bus & Transportation
 * Matches CI3 tables: driver_master, vehicle_master, driver_details
 */
import pool from '../config/db.js';

class TransportationModel {
  // ─── 1. Drivers Master (driver_master) ───
  static async getDrivers() {
    const [rows] = await pool.query('SELECT * FROM driver_master ORDER BY sr_no DESC');
    return rows.map((r) => ({
      id: r.sr_no,
      name: r.Driver_name,
      mobile: r.Mobile_no || r.mobile,
      whatsapp: r.Whatsapp_no,
      address: r.Address,
      vehicle: r.Vehicle,
    }));
  }

  static async createDriver({ name, mobile, whatsapp, address, vehicle }) {
    const [res] = await pool.query(
      `INSERT INTO driver_master (Driver_name, Mobile_no, mobile, Whatsapp_no, Address, Vehicle) 
       VALUES (?, ?, ?, ?, ?, ?)`,
      [name, mobile, mobile, whatsapp || mobile, address || '', vehicle || '']
    );
    return { id: res.insertId, name };
  }

  static async updateDriver(id, { name, mobile, whatsapp, address, vehicle }) {
    await pool.query(
      `UPDATE driver_master 
       SET Driver_name = ?, Mobile_no = ?, mobile = ?, Whatsapp_no = ?, Address = ?, Vehicle = ?
       WHERE sr_no = ?`,
      [name, mobile, mobile, whatsapp || mobile, address || '', vehicle || '', id]
    );
    return { id, updated: true };
  }

  static async deleteDriver(id) {
    const [res] = await pool.query('DELETE FROM driver_master WHERE sr_no = ?', [id]);
    return res.affectedRows > 0;
  }

  // ─── 2. Vehicles Master (vehicle_master) ───
  static async getVehicles() {
    const [rows] = await pool.query('SELECT * FROM vehicle_master ORDER BY sr_no DESC');
    return rows.map((r) => ({
      id: r.sr_no,
      name: r.Vehicle_name,
      wheel_type: r.Wheel_type,
      vehicle_no: r.Vehicle,
      seats: r.no_seat,
    }));
  }

  static async createVehicle({ name, wheel_type, vehicle_no, seats }) {
    const [res] = await pool.query(
      `INSERT INTO vehicle_master (Vehicle_name, Wheel_type, Vehicle, no_seat) 
       VALUES (?, ?, ?, ?)`,
      [name, wheel_type || '4 Wheeler', vehicle_no, seats || 30]
    );
    return { id: res.insertId, name };
  }

  static async updateVehicle(id, { name, wheel_type, vehicle_no, seats }) {
    await pool.query(
      `UPDATE vehicle_master 
       SET Vehicle_name = ?, Wheel_type = ?, Vehicle = ?, no_seat = ?
       WHERE sr_no = ?`,
      [name, wheel_type, vehicle_no, seats, id]
    );
    return { id, updated: true };
  }

  static async deleteVehicle(id) {
    const [res] = await pool.query('DELETE FROM vehicle_master WHERE sr_no = ?', [id]);
    return res.affectedRows > 0;
  }

  // ─── 3. Student Transport Mapping (driver_details) ───
  static async getStudentTransport(standard, division) {
    const sql = `
      SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS enrollment_no,
        IFNULL(c.STVCLAS_rollno, '') AS roll_no,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        dd.id AS detail_id,
        dd.driver_id,
        dm.Driver_name AS driver_name,
        dm.Vehicle AS driver_vehicle
      FROM spriden s
      INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
      LEFT JOIN driver_details dd ON s.id = dd.spriden_id
      LEFT JOIN driver_master dm ON dd.driver_id = dm.sr_no
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.spriden_student_status = 'A'
        AND (c.STVCLAS_standard = ? OR ? = 'all')
        AND (c.STVCLAS_class = ? OR ? = 'all')
      ORDER BY 
        CASE 
          WHEN c.STVCLAS_rollno IS NOT NULL AND c.STVCLAS_rollno != '' 
          THEN CAST(c.STVCLAS_rollno AS UNSIGNED) 
          ELSE 999999 
        END ASC,
        student_name ASC
    `;
    const [rows] = await pool.query(sql, [standard, standard, division, division]);
    return rows;
  }

  static async assignStudentTransport({ studentId, driverId, user = 'admin' }) {
    const now = new Date();
    // Check if student already has a mapping in driver_details
    const [existing] = await pool.query(
      'SELECT id FROM driver_details WHERE spriden_id = ? LIMIT 1',
      [studentId]
    );

    if (existing.length > 0) {
      if (!driverId) {
        // Unassign
        await pool.query('DELETE FROM driver_details WHERE id = ?', [existing[0].id]);
        return { unassigned: true };
      } else {
        await pool.query(
          'UPDATE driver_details SET driver_id = ?, updated_at = ?, updated_by = ? WHERE id = ?',
          [driverId, now, user, existing[0].id]
        );
        return { updated: true };
      }
    } else {
      if (driverId) {
        await pool.query(
          `INSERT INTO driver_details (spriden_id, student_id, driver_id, created_at) 
           VALUES (?, ?, ?, ?)`,
          [studentId, studentId, driverId, now]
        );
        return { created: true };
      }
    }
    return { ok: true };
  }
}

export default TransportationModel;
