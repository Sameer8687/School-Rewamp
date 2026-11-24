import pool from '../config/db.js';

class AadharModel {
  /**
   * Fetch Aadhar verification report matching CI3 Welcome::get_aadhar_data & aadhar-info-report.php
   */
  static async getAadharReport({ standard, division, search } = {}) {
    let query = `
      SELECT 
        st.id AS aadhar_record_id,
        s.id AS student_pk,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_rollno,
        COALESCE(st.student_name, CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, ''))) AS student_name,
        COALESCE(st.student_aadhar_number, s.SPRIDEN_aadhar_no) AS student_aadhar_number,
        COALESCE(st.student_dob, s.SPRIDEN_birthdate) AS student_dob,
        COALESCE(st.father_name, s.SPRIDEN_father_name) AS father_name,
        st.father_aadhar_number,
        st.father_dob,
        COALESCE(st.mother_name, s.SPRIDEN_mother_name) AS mother_name,
        st.mother_aadhar_number,
        st.mother_dob,
        COALESCE(st.fat_mob_no, (SELECT stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1), (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1)) AS fat_mob_no,
        st.updated_at
      FROM spriden s
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id
      LEFT JOIN stu_par_aadhar_details st ON st.student_id = s.id
      WHERE s.spriden_student_status = 'A' AND s.SPRIDEN_user_type = 'st'
    `;
    const params = [];

    if (standard && standard !== 'all') {
      query += ` AND c.STVCLAS_standard = ?`;
      params.push(standard);
    }
    if (division && division !== 'all') {
      query += ` AND c.STVCLAS_class = ?`;
      params.push(division);
    }
    if (search && search.trim()) {
      query += ` AND (
        s.SPRIDEN_student_id LIKE ? 
        OR s.spriden_gr_number LIKE ?
        OR s.SPRIDEN_student_name LIKE ?
        OR s.SPRIDEN_surname LIKE ?
        OR st.student_aadhar_number LIKE ?
        OR st.father_aadhar_number LIKE ?
      )`;
      const q = `%${search.trim()}%`;
      params.push(q, q, q, q, q, q);
    }

    query += ` ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED) ASC, s.SPRIDEN_student_id ASC LIMIT 250`;

    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Get single student Aadhar details
   */
  static async getStudentAadhar(studentId) {
    const query = `
      SELECT 
        s.id AS student_pk,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.SPRIDEN_birthdate,
        s.SPRIDEN_aadhar_no,
        st.*
      FROM spriden s
      LEFT JOIN stu_par_aadhar_details st ON st.student_id = s.id
      WHERE (s.id = ? OR s.SPRIDEN_student_id = ?)
      LIMIT 1
    `;
    const [rows] = await pool.query(query, [studentId, studentId]);
    return rows[0] || null;
  }

  /**
   * Get public student information for parent KYC form (CI3: public/Student.php)
   */
  static async getPublicStudentInfo(sid, token) {
    let query = `
      SELECT 
        s.id AS student_pk,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.SPRIDEN_father_name,
        s.SPRIDEN_mother_name,
        s.SPRIDEN_birthdate,
        s.SPRIDEN_photo_path,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_rollno,
        a.stvacdm_year,
        p.stvpcnf_mobile,
        st.id AS aadhar_id,
        COALESCE(st.student_name, CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, ''))) AS aadhar_student_name,
        COALESCE(st.student_aadhar_number, s.SPRIDEN_aadhar_no) AS aadhar_student_number,
        COALESCE(st.student_dob, s.SPRIDEN_birthdate) AS aadhar_student_dob,
        COALESCE(st.father_name, s.SPRIDEN_father_name) AS aadhar_father_name,
        st.father_aadhar_number,
        st.father_dob AS aadhar_father_dob,
        COALESCE(st.fat_mob_no, p.stvpcnf_mobile) AS aadhar_father_mobile,
        COALESCE(st.mother_name, s.SPRIDEN_mother_name) AS aadhar_mother_name,
        st.mother_aadhar_number,
        st.mother_dob AS aadhar_mother_dob
      FROM spriden s
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = (SELECT max(STVCLAS_acdm) FROM stvclas WHERE STVCLAS_student_id = s.id)
      LEFT JOIN stvacdm a ON a.stvacdm_id = c.STVCLAS_acdm
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      LEFT JOIN stu_par_aadhar_details st ON st.student_id = s.id
      WHERE (s.id = ? OR s.SPRIDEN_student_id = ?)
    `;
    const params = [sid, sid];
    if (token) {
      query += ` AND s.spriden_auth_token = ?`;
      params.push(token);
    }
    query += ` LIMIT 1`;
    const [rows] = await pool.query(query, params);
    return rows[0] || null;
  }

  /**
   * Validate student credential (CI3: public/Student.php validate)
   */
  static async validateStudentId(studentId) {
    const [rows] = await pool.query(
      `SELECT id, SPRIDEN_student_id, spriden_auth_token 
       FROM spriden 
       WHERE (SPRIDEN_student_id = ? OR id = ?) AND spriden_student_status = 'A'
       LIMIT 1`,
      [studentId, studentId]
    );
    return rows[0] || null;
  }

  /**
   * Save or update student & parent Aadhar details
   */
  static async saveStudentAadhar(arg1, arg2) {
    let studentPk;
    let data;
    if (typeof arg1 === 'object' && arg1 !== null) {
      data = arg1;
      studentPk = arg1.student_id || arg1.studentId || arg1.id;
    } else {
      studentPk = arg1;
      data = arg2 || {};
    }

    const {
      student_aadhar_number,
      student_name,
      student_dob,
      father_aadhar_number,
      father_name,
      father_dob,
      mother_aadhar_number,
      mother_name,
      mother_dob,
      fat_mob_no,
    } = data;

    const cleanStudentAadhar = student_aadhar_number ? String(student_aadhar_number).trim() : '';
    const cleanStudentName = student_name ? String(student_name).trim() : '';
    const cleanStudentDob = student_dob ? String(student_dob).substring(0, 10) : '2000-01-01';
    const cleanFatherAadhar = father_aadhar_number ? String(father_aadhar_number).trim() : '';
    const cleanFatherName = father_name ? String(father_name).trim() : '';
    const cleanFatherDob = father_dob ? String(father_dob).substring(0, 10) : '1980-01-01';
    const cleanMotherAadhar = mother_aadhar_number ? String(mother_aadhar_number).trim() : '';
    const cleanMotherName = mother_name ? String(mother_name).trim() : '';
    const cleanMotherDob = mother_dob ? String(mother_dob).substring(0, 10) : '1985-01-01';
    const cleanMob = fat_mob_no ? String(fat_mob_no).trim() : '';

    // Check if record exists
    const [existing] = await pool.query(
      `SELECT id, update_count FROM stu_par_aadhar_details WHERE student_id = ? LIMIT 1`,
      [studentPk]
    );

    if (existing.length > 0) {
      const newCount = (Number(existing[0].update_count) || 0) + 1;
      await pool.query(
        `UPDATE stu_par_aadhar_details
         SET 
           student_aadhar_number = ?,
           student_name = ?,
           student_dob = ?,
           father_aadhar_number = ?,
           father_name = ?,
           father_dob = ?,
           mother_aadhar_number = ?,
           mother_name = ?,
           mother_dob = ?,
           fat_mob_no = ?,
           updated_at = NOW(),
           update_count = ?
         WHERE student_id = ?`,
        [
          cleanStudentAadhar,
          cleanStudentName,
          cleanStudentDob,
          cleanFatherAadhar,
          cleanFatherName,
          cleanFatherDob,
          cleanMotherAadhar,
          cleanMotherName,
          cleanMotherDob,
          cleanMob,
          newCount,
          studentPk,
        ]
      );
    } else {
      await pool.query(
        `INSERT INTO stu_par_aadhar_details (
           student_id,
           student_aadhar_number,
           student_name,
           student_dob,
           father_aadhar_number,
           father_name,
           father_dob,
           mother_aadhar_number,
           mother_name,
           mother_dob,
           fat_mob_no,
           created_at,
           updated_at,
           update_count
         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1)`,
        [
          studentPk,
          cleanStudentAadhar,
          cleanStudentName,
          cleanStudentDob,
          cleanFatherAadhar,
          cleanFatherName,
          cleanFatherDob,
          cleanMotherAadhar,
          cleanMotherName,
          cleanMotherDob,
          cleanMob,
        ]
      );
    }

    // Also update spriden.SPRIDEN_aadhar_no if provided
    if (student_aadhar_number) {
      await pool.query(`UPDATE spriden SET SPRIDEN_aadhar_no = ? WHERE id = ?`, [
        student_aadhar_number,
        studentPk,
      ]);
    }

    return { success: true };
  }
}

export default AadharModel;
