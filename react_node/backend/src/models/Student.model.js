/**
 * Student Model — all database operations for Student Module.
 *
 * Direct conversion of CI3:
 *   - Student_model.php
 *   - StudentUpdate_model.php
 *   - MasterForm_model.php
 *   - Report_model.php (LC & Bonafide records)
 */
import pool from '../config/db.js';

// ─────────────────────────────────────────────────────────────────────────────
// 1. STUDENT LIST & FILTERING
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Fetch students list matching CI3 StudentUpdate_model:
 * - Nursery to SSC: STVCLAS_standard not in ('XI','XII')
 * - Junior College: STVCLAS_standard in ('XI','XII')
 */
export async function getStudentsList({ acdm_year, status = 'A', type = 'ssc', search = '' }) {
  // If acdm_year is not provided, get default from stvschool
  let academicYearId = acdm_year;
  if (!academicYearId) {
    const [[school]] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    academicYearId = school?.stvschool_acdm_year || 1;
  }

  const isJuniorCollege = type === 'jc';
  const stdFilter = isJuniorCollege
    ? "cl.STVCLAS_standard IN ('XI', 'XII')"
    : "cl.STVCLAS_standard NOT IN ('XI', 'XII')";

  let statusClause = '';
  const params = [academicYearId];

  if (status && status !== 'ALL') {
    if (status === 'IS_NULL') {
      statusClause = 'AND sp.spriden_student_status IS NULL';
    } else {
      statusClause = 'AND sp.spriden_student_status = ?';
      params.push(status);
    }
  }

  let searchClause = '';
  if (search && search.trim() !== '') {
    searchClause = `AND (
      sp.spriden_gr_number LIKE ? OR
      sp.SPRIDEN_student_id LIKE ? OR
      sp.SPRIDEN_reg_number LIKE ? OR
      sp.SPRIDEN_student_name LIKE ? OR
      sp.SPRIDEN_father_name LIKE ? OR
      sp.SPRIDEN_surname LIKE ? OR
      CONCAT(sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name, ' ', sp.SPRIDEN_surname) LIKE ?
    )`;
    const q = `%${search.trim()}%`;
    params.push(q, q, q, q, q, q, q);
  }

  const sql = `
    SELECT
      sp.id,
      sp.SPRIDEN_student_id,
      sp.spriden_gr_number,
      sp.SPRIDEN_reg_number,
      sp.SPRIDEN_pan_no,
      sp.SPRIDEN_apaar_no,
      sp.spriden_student_status,
      sp.SPRIDEN_student_name,
      sp.SPRIDEN_father_name,
      sp.SPRIDEN_mother_name,
      sp.SPRIDEN_surname,
      CONCAT(sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name, ' ', sp.SPRIDEN_surname) AS name,
      sp.SPRIDEN_gender,
      sp.SPRIDEN_birthdate,
      sp.rte_status,
      sp.student_profile_photo,
      cl.STVCLAS_standard,
      cl.STVCLAS_class,
      cl.STVCLAS_rollno,
      cl.STVCLAS_acdm,
      cnf.stvpcnf_mobile,
      cnf.stvpcnf_whatsapp,
      (SELECT DISTINCT lc_status FROM lc_status WHERE student_id = sp.id LIMIT 1) AS lc_status
    FROM spriden sp
    INNER JOIN stvclas cl ON sp.id = cl.STVCLAS_student_id
    LEFT JOIN stvpcnf cnf ON sp.id = cnf.stvpcnf_student_id
    WHERE sp.SPRIDEN_user_type = 'st'
      AND cl.STVCLAS_acdm = ?
      AND ${stdFilter}
      ${statusClause}
      ${searchClause}
    ORDER BY CAST(sp.spriden_gr_number AS UNSIGNED) ASC, sp.id ASC
  `;

  const [rows] = await pool.query(sql, params);
  return { students: rows, academicYearId };
}

// ─────────────────────────────────────────────────────────────────────────────
// 2. STUDENT DETAILS & ACTIVITY LOG
// ─────────────────────────────────────────────────────────────────────────────

export async function getStudentById(id) {
  // Fetch core spriden record
  const [[student]] = await pool.query(
    `SELECT * FROM spriden WHERE id = ? AND SPRIDEN_user_type = 'st'`,
    [id]
  );
  if (!student) return null;

  // Fetch contact details (stvpcnf)
  const [[contact]] = await pool.query(
    `SELECT * FROM stvpcnf WHERE stvpcnf_student_id = ? LIMIT 1`,
    [id]
  );

  // Fetch current and previous class allocations (stvclas joined with stvacdm)
  const [classes] = await pool.query(
    `SELECT c.*, a.stvacdm_year, a.stvacdm_start, a.stvacdm_end
     FROM stvclas c
     LEFT JOIN stvacdm a ON c.STVCLAS_acdm = a.stvacdm_id
     WHERE c.STVCLAS_student_id = ?
     ORDER BY c.STVCLAS_acdm DESC`,
    [id]
  );

  // Fetch fees summary (sprfees)
  const [fees] = await pool.query(
    `SELECT f.*, a.stvacdm_year, u.spruser_name AS received_by_name
     FROM sprfees f
     LEFT JOIN stvacdm a ON f.academic_year = a.stvacdm_id
     LEFT JOIN spruser u ON f.sprfees_recivedby = u.spruser_id
     WHERE f.sprfees_studentid = ?
     ORDER BY f.sprfees_pk DESC`,
    [id]
  );

  // Fetch student status activity log (student_status_activity)
  const [[activityRow]] = await pool.query(
    `SELECT * FROM student_status_activity WHERE student_id = ? LIMIT 1`,
    [id]
  );
  let activities = [];
  if (activityRow?.activities) {
    try {
      activities = typeof activityRow.activities === 'string'
        ? JSON.parse(activityRow.activities)
        : activityRow.activities;
    } catch {
      activities = [activityRow.activities];
    }
  }

  // Fetch LC Status if any
  const [[lc]] = await pool.query(
    `SELECT * FROM lc_status WHERE student_id = ? LIMIT 1`,
    [id]
  );

  return {
    ...student,
    fullName: `${student.SPRIDEN_surname || ''} ${student.SPRIDEN_student_name || ''} ${student.SPRIDEN_father_name || ''}`.trim(),
    contact: contact || null,
    classes: classes || [],
    fees: fees || [],
    activities,
    lc: lc || null,
  };
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. GENERATE NEXT STUDENT ID
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Replicates CI3 Student::genearetID() logic:
 * Format: S{YearPrefix}{0001}
 * e.g. Year "2026-27" -> acdm_substr "2026" -> maxId "S20260045" -> Next "S20260046"
 */
export async function generateNextStudentId(academicYearId) {
  let yearStr = '';
  if (academicYearId) {
    const [[yr]] = await pool.query(
      `SELECT stvacdm_year FROM stvacdm WHERE stvacdm_id = ? LIMIT 1`,
      [academicYearId]
    );
    yearStr = yr?.stvacdm_year || '';
  }

  if (!yearStr) {
    const [[school]] = await pool.query(
      `SELECT a.stvacdm_year
       FROM stvschool s
       LEFT JOIN stvacdm a ON s.stvschool_acdm_year = a.stvacdm_id
       LIMIT 1`
    );
    yearStr = school?.stvacdm_year || `${new Date().getFullYear()}-${new Date().getFullYear() + 1}`;
  }

  const acdmSubstr = yearStr.substring(0, 4);

  // Find max ID starting with S{acdmSubstr}
  const [[maxRow]] = await pool.query(
    `SELECT MAX(SPRIDEN_student_id) AS max_id
     FROM spriden
     WHERE SPRIDEN_student_id LIKE ? AND SPRIDEN_user_type = 'st'`,
    [`S${acdmSubstr}%`]
  );

  let nextId = `S${acdmSubstr}0001`;
  if (maxRow?.max_id) {
    const lastNumStr = maxRow.max_id.slice(-4);
    const lastNum = parseInt(lastNumStr, 10);
    if (!Number.isNaN(lastNum)) {
      const nextNum = String(lastNum + 1).padStart(4, '0');
      nextId = `S${acdmSubstr}${nextNum}`;
    }
  }

  return { studentId: nextId, academicYear: yearStr, prefix: acdmSubstr };
}

// ─────────────────────────────────────────────────────────────────────────────
// 4. CREATE NEW STUDENT (ADMISSION FORM)
// ─────────────────────────────────────────────────────────────────────────────

export async function createStudent(payload, userId) {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    // 1. Calculate height in cm if entered as ft.in
    let cmHeight = '';
    const rawHeight = (payload.height || '').trim().replace(/[^0-9.]/g, '');
    if ((rawHeight.match(/\./g) || []).length === 1) {
      const [feet, inches] = rawHeight.split('.').map(n => parseInt(n, 10) || 0);
      cmHeight = String(((feet * 12) + inches) * 2.54);
    }

    // 2. Division default by gender if not explicitly passed
    const div = payload.division || (payload.gender === 'Male' ? 'A' : 'B');

    // 3. Document string
    const docArray = [
      payload.main_docs || '0',
      payload.student_aadhar || '0',
      payload.father_aadhar || '0',
      payload.passport_photo || '0',
    ];
    const docStr = payload.documents || docArray.join('-');

    // 4. Insert into spriden
    const spridenData = {
      SPRIDEN_student_id: payload.student_id,
      spriden_gr_number: payload.gr_number || null,
      SPRIDEN_reg_number: payload.reg_number || null,
      SPRIDEN_pan_no: payload.pan_no || null,
      SPRIDEN_apaar_no: payload.apaar_no || null,
      SPRIDEN_surname: payload.surname || '',
      SPRIDEN_student_name: payload.student_name || '',
      SPRIDEN_father_name: payload.father_name || '',
      SPRIDEN_mother_name: payload.mother_name || '',
      SPRIDEN_religion: payload.religion || 'Islam',
      SPRIDEN_gender: payload.gender || 'Male',
      SPRIDEN_nationality: payload.nationality || 'Indian',
      SPRIDEN_mother_toungue: payload.mother_tongue || 'Urdu',
      SPRIDEN_birth_place: payload.birth_place || '',
      SPRIDEN_aadhar_no: payload.aadhar_number || '',
      SPRIDEN_birthdate: payload.birthdate || null,
      SPRIDEN_admission_class: payload.admission_class || '',
      SPRIDEN_last_school: payload.last_school || '',
      SPRIDEN_main_address: payload.main_address || '',
      SPRIDEN_alt_address: payload.alt_address || '',
      SPRIDEN_father_quali: payload.father_qualification || '',
      SPRIDEN_mother_quali: payload.mother_qualification || '',
      SPRIDEN_documents: docStr,
      SPRIDEN_user_type: 'st',
      SPRIDEN_date_created: new Date().toISOString().slice(0, 10),
      spriden_admission_year: String(new Date().getFullYear()),
      spriden_student_status: 'A',
      cast: payload.caste || '',
      sub_cast: payload.sub_caste || '',
      taluka: payload.taluka || '',
      district: payload.district || '',
      state: payload.state || 'Maharashtra',
      rte_status: payload.rte_status || 'No',
      spriden_admission_notes: payload.admission_note || '',
      admsn_acdm_year: payload.acdm_year || 1,
      height: payload.height || '',
      height_in_cm: cmHeight,
      weight: payload.weight || '',
      student_name_aadhar: payload.student_name_aadhar || '',
      father_aadhar_no: payload.father_aadhar_no || '',
      father_name_aadhar: payload.father_name_aadhar || '',
      mother_name_aadhar: payload.mother_name_aadhar || '',
      mother_aadhar_no: payload.mother_aadhar_no || '',
      address_landmark: payload.address_landmark || '',
      near_by_mosque: payload.nearby_masjid || '',
      student_profile_photo: payload.photo_filename || '',
      birthdate_words: payload.birthdate_words || '',
    };

    const [spridenRes] = await conn.query('INSERT INTO spriden SET ?', [spridenData]);
    const studentId = spridenRes.insertId;

    // 5. Insert into stvpcnf (parent contact)
    const pcnfData = {
      stvpcnf_student_id: studentId,
      stvpcnf_parent_typ: 'Father',
      stvpcnf_mobile: payload.parents_mobile || payload.mobile1 || '',
      stvpcnf_mobile2: payload.mobile2 || '',
      stvpcnf_whatsapp: payload.whatsapp_number || '',
      stvpncf_sms_no: payload.sms_number || '',
      stvpncf_added_by: userId || 1,
      stvpncf_date_added: new Date().toISOString().slice(0, 10),
    };
    await conn.query('INSERT INTO stvpcnf SET ?', [pcnfData]);

    // 6. Insert into stvclas (class allocation)
    const classData = {
      STVCLAS_student_id: studentId,
      STVCLAS_rollno: payload.roll_no || 0,
      STVCLAS_standard: payload.admission_class || '',
      STVCLAS_class: div,
      STVCLAS_userid: userId || 1,
      STVCLAS_acdm: payload.acdm_year || 1,
    };
    await conn.query('INSERT INTO stvclas SET ?', [classData]);

    // 7. Initial status activity
    const activityLine = `System / User #${userId || 1} created Student admission at ${new Date().toLocaleString('en-IN')}`;
    await conn.query(
      `INSERT INTO student_status_activity (student_id, activities) VALUES (?, ?)`,
      [studentId, JSON.stringify([activityLine])]
    );

    await conn.commit();
    return { success: true, id: studentId, studentIdStr: payload.student_id };
  } catch (error) {
    await conn.rollback();
    throw error;
  } finally {
    conn.release();
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// 5. UPDATE STUDENT DETAILS
// ─────────────────────────────────────────────────────────────────────────────

export async function updateStudent(id, payload, userId) {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    let cmHeight = '';
    const rawHeight = (payload.height || '').trim().replace(/[^0-9.]/g, '');
    if ((rawHeight.match(/\./g) || []).length === 1) {
      const [feet, inches] = rawHeight.split('.').map(n => parseInt(n, 10) || 0);
      cmHeight = String(((feet * 12) + inches) * 2.54);
    }

    const spridenUpdate = {
      spriden_gr_number: payload.gr_number || null,
      SPRIDEN_reg_number: payload.reg_no || payload.reg_number || null,
      SPRIDEN_pan_no: payload.pan_no || null,
      SPRIDEN_apaar_no: payload.apaar_no || null,
      spriden_college_gr: payload.college_gr || null,
      SPRIDEN_surname: payload.surname || '',
      SPRIDEN_student_name: payload.student_name || '',
      SPRIDEN_father_name: payload.father_name || '',
      SPRIDEN_mother_name: payload.mother_name || '',
      SPRIDEN_religion: payload.religion || '',
      SPRIDEN_gender: payload.gender || '',
      SPRIDEN_nationality: payload.nationality || '',
      SPRIDEN_mother_toungue: payload.mother_tongue || '',
      SPRIDEN_birth_place: payload.birth_place || '',
      SPRIDEN_aadhar_no: payload.aadhar_number || '',
      SPRIDEN_admission_class: payload.admission_class || '',
      SPRIDEN_birthdate: payload.birthdate || null,
      SPRIDEN_last_school: payload.last_school || '',
      SPRIDEN_main_address: payload.main_address || '',
      SPRIDEN_alt_address: payload.alt_address || '',
      SPRIDEN_father_quali: payload.father_qualification || '',
      SPRIDEN_mother_quali: payload.mother_qualification || '',
      rte_status: payload.rte_status || 'No',
      cast: payload.caste || '',
      sub_cast: payload.sub_caste || '',
      taluka: payload.taluka || '',
      district: payload.district || '',
      state: payload.state || 'Maharashtra',
      height: payload.height || '',
      height_in_cm: cmHeight,
      weight: payload.weight || '',
      SPRIDEN_BGRP: payload.blood_group || payload.SPRIDEN_BGRP || '',
      student_name_aadhar: payload.student_name_aadhar || '',
      father_aadhar_no: payload.father_aadhar_no || '',
      father_name_aadhar: payload.father_name_aadhar || '',
      mother_name_aadhar: payload.mother_name_aadhar || '',
      mother_aadhar_no: payload.mother_aadhar_no || '',
      birthdate_words: payload.birthdate_words || '',
      progress: payload.progress || '',
      conduct: payload.conduct || '',
      leaving_date: payload.leaving_date || null,
      leaving_reason: payload.leaving_reason || '',
      remark: payload.remark || '',
      since_when: payload.since_when || '',
      lc_date: payload.lc_date || null,
      SPRIDEN_last_updated: new Date().toISOString().slice(0, 10),
      SPRIDEN_updated_by: userId || 1,
    };

    if (payload.photo_filename) {
      spridenUpdate.student_profile_photo = payload.photo_filename;
    }

    await conn.query('UPDATE spriden SET ? WHERE id = ?', [spridenUpdate, id]);

    // Update or Insert Contact Details (stvpcnf)
    const [[existPcnf]] = await conn.query(
      'SELECT stvpcnf_id FROM stvpcnf WHERE stvpcnf_student_id = ? LIMIT 1',
      [id]
    );

    const contactData = {
      stvpcnf_mobile: payload.mobile_number1 || payload.mobile1 || '',
      stvpcnf_mobile2: payload.mobile_number2 || payload.mobile2 || '',
      stvpcnf_whatsapp: payload.whatsapp_number || '',
      stvpncf_sms_no: payload.sms_number || '',
    };

    if (existPcnf) {
      await conn.query('UPDATE stvpcnf SET ? WHERE stvpcnf_student_id = ?', [contactData, id]);
    } else {
      await conn.query('INSERT INTO stvpcnf SET ?', [{
        stvpcnf_student_id: id,
        stvpcnf_parent_typ: 'Father',
        ...contactData,
        stvpncf_added_by: userId || 1,
        stvpncf_date_added: new Date().toISOString().slice(0, 10),
      }]);
    }

    // Update Class in stvclas for current academic year if provided
    if (payload.current_class && payload.acdm_year) {
      await conn.query(
        `UPDATE stvclas SET STVCLAS_standard = ? WHERE STVCLAS_student_id = ? AND STVCLAS_acdm = ?`,
        [payload.current_class, id, payload.acdm_year]
      );
    }

    await conn.commit();
    return { success: true };
  } catch (error) {
    await conn.rollback();
    throw error;
  } finally {
    conn.release();
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// 6. STATUS CHANGE & ACTIVITY TRACKING
// ─────────────────────────────────────────────────────────────────────────────

export async function changeStudentStatus(id, status, reason = '', userId) {
  // Get user name for audit log
  let userName = 'Administrator';
  if (userId) {
    const [[user]] = await pool.query('SELECT spruser_name FROM spruser WHERE spruser_id = ?', [userId]);
    if (user?.spruser_name) userName = user.spruser_name;
  }

  await pool.query(
    `UPDATE spriden SET spriden_student_status = ?, spriden_status_end_date = ? WHERE id = ?`,
    [status, new Date().toISOString().slice(0, 10), id]
  );

  const timestamp = new Date().toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'medium' });
  const logLine = `${userName} updated Student Status to '${status}' at ${timestamp}. Reason: ${reason || 'N/A'}`;

  const [[existRow]] = await pool.query(
    'SELECT * FROM student_status_activity WHERE student_id = ? LIMIT 1',
    [id]
  );

  if (!existRow) {
    await pool.query(
      'INSERT INTO student_status_activity (student_id, activities) VALUES (?, ?)',
      [id, JSON.stringify([logLine])]
    );
  } else {
    let list = [];
    try {
      list = typeof existRow.activities === 'string' ? JSON.parse(existRow.activities) : existRow.activities;
    } catch {
      list = [existRow.activities];
    }
    list.push(logLine);
    await pool.query(
      'UPDATE student_status_activity SET activities = ? WHERE student_id = ?',
      [JSON.stringify(list), id]
    );
  }

  return { success: true, status, logLine };
}

// ─────────────────────────────────────────────────────────────────────────────
// 7. CLASS PROMOTION (BULK)
// ─────────────────────────────────────────────────────────────────────────────

export async function getPromotionStudents({ acdm_year, standard, division }) {
  let query = `
    SELECT
      sp.id,
      sp.SPRIDEN_student_id,
      sp.spriden_gr_number,
      sp.SPRIDEN_reg_number,
      CONCAT(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) AS name,
      cl.STVCLAS_standard,
      cl.STVCLAS_class,
      cl.STVCLAS_rollno,
      cl.STVCLAS_acdm
    FROM spriden sp
    INNER JOIN stvclas cl ON sp.id = cl.STVCLAS_student_id
    WHERE sp.SPRIDEN_user_type = 'st'
      AND sp.spriden_student_status = 'A'
      AND cl.STVCLAS_acdm = ?
      AND cl.STVCLAS_standard = ?
  `;
  const params = [acdm_year, standard];

  if (division && division !== 'ALL') {
    query += ` AND cl.STVCLAS_class = ?`;
    params.push(division);
  }

  query += ` ORDER BY CAST(cl.STVCLAS_rollno AS UNSIGNED) ASC, sp.spriden_gr_number ASC`;

  const [rows] = await pool.query(query, params);
  return rows;
}

export async function promoteStudents({ students, rollnos, targetAcdm, targetStandard, targetDivision, userId }) {
  let promoted = 0;
  let skipped = 0;
  const alreadyExist = [];

  for (let i = 0; i < students.length; i++) {
    const studentId = students[i];
    const rollno = rollnos[i] || 0;

    // Check if student is already in target academic year
    const [[existing]] = await pool.query(
      `SELECT STVCLAS_standard, STVCLAS_class FROM stvclas WHERE STVCLAS_student_id = ? AND STVCLAS_acdm = ? LIMIT 1`,
      [studentId, targetAcdm]
    );

    if (existing) {
      alreadyExist.push({
        student_id: studentId,
        class: existing.STVCLAS_standard,
        div: existing.STVCLAS_class,
      });
      skipped++;
      continue;
    }

    await pool.query(
      `INSERT INTO stvclas (STVCLAS_student_id, STVCLAS_rollno, STVCLAS_standard, STVCLAS_class, STVCLAS_userid, STVCLAS_acdm)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [studentId, rollno, targetStandard, targetDivision, userId || 1, targetAcdm]
    );
    promoted++;
  }

  return { promoted, skipped, existingStudents: alreadyExist };
}

// ─────────────────────────────────────────────────────────────────────────────
// 8. RE-ADMISSION
// ─────────────────────────────────────────────────────────────────────────────

export async function getReadmissionDetails(studentId, acdmYear) {
  const [[student]] = await pool.query(
    `SELECT sp.*, CONCAT(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) AS full_name
     FROM spriden sp WHERE sp.id = ? AND sp.SPRIDEN_user_type = 'st'`,
    [studentId]
  );
  if (!student) return null;

  // Previous class details
  const [classRows] = await pool.query(
    `SELECT c.*, a.stvacdm_year
     FROM stvclas c
     LEFT JOIN stvacdm a ON c.STVCLAS_acdm = a.stvacdm_id
     WHERE c.STVCLAS_student_id = ?
     ORDER BY c.STVCLAS_acdm DESC`,
    [studentId]
  );

  // Previous fees summary
  const [feeRows] = await pool.query(
    `SELECT f.*, a.stvacdm_year
     FROM sprfees f
     LEFT JOIN stvacdm a ON f.academic_year = a.stvacdm_id
     WHERE f.sprfees_studentid = ?
     ORDER BY f.sprfees_pk DESC`,
    [studentId]
  );

  return { student, classes: classRows, fees: feeRows };
}

export async function processReadmission({ studentId, targetAcdm, targetStandard, targetDivision, rollNo = 0, note = '', userId }) {
  const conn = await pool.getConnection();
  try {
    await conn.beginTransaction();

    // 1. Check if already has entry for target academic year
    const [[exist]] = await conn.query(
      `SELECT id FROM stvclas WHERE STVCLAS_student_id = ? AND STVCLAS_acdm = ? LIMIT 1`,
      [studentId, targetAcdm]
    );

    if (exist) {
      await conn.query(
        `UPDATE stvclas SET STVCLAS_standard = ?, STVCLAS_class = ?, STVCLAS_rollno = ?, STVCLAS_userid = ? WHERE id = ?`,
        [targetStandard, targetDivision, rollNo, userId || 1, exist.id]
      );
    } else {
      await conn.query(
        `INSERT INTO stvclas (STVCLAS_student_id, STVCLAS_rollno, STVCLAS_standard, STVCLAS_class, STVCLAS_userid, STVCLAS_acdm)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [studentId, rollNo, targetStandard, targetDivision, userId || 1, targetAcdm]
      );
    }

    // 2. Ensure status is Active
    await conn.query(
      `UPDATE spriden SET spriden_student_status = 'A', spriden_admission_notes = CONCAT(IFNULL(spriden_admission_notes,''), '\nRe-admission: ', ?) WHERE id = ?`,
      [note || 'Re-admitted', studentId]
    );

    // 3. Status activity
    const timestamp = new Date().toLocaleString('en-IN');
    const logLine = `User #${userId || 1} processed Re-Admission for student to ${targetStandard}-${targetDivision} at ${timestamp}. Note: ${note}`;
    const [[existAct]] = await conn.query('SELECT activities FROM student_status_activity WHERE student_id = ? LIMIT 1', [studentId]);
    let actList = [];
    if (existAct?.activities) {
      try { actList = JSON.parse(existAct.activities); } catch { actList = [existAct.activities]; }
    }
    actList.push(logLine);
    if (existAct) {
      await conn.query('UPDATE student_status_activity SET activities = ? WHERE student_id = ?', [JSON.stringify(actList), studentId]);
    } else {
      await conn.query('INSERT INTO student_status_activity (student_id, activities) VALUES (?, ?)', [studentId, JSON.stringify(actList)]);
    }

    await conn.commit();
    return { success: true };
  } catch (error) {
    await conn.rollback();
    throw error;
  } finally {
    conn.release();
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// 9. LEAVING CERTIFICATE (LC) & BONAFIDE
// ─────────────────────────────────────────────────────────────────────────────

export async function getLcRecords(acdmYear, standard) {
  let query = `
    SELECT
      sp.id,
      sp.SPRIDEN_student_id,
      sp.spriden_gr_number,
      sp.SPRIDEN_reg_number,
      CONCAT(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) AS name,
      sp.SPRIDEN_gender,
      sp.SPRIDEN_birthdate,
      sp.birthdate_words,
      sp.leaving_date,
      sp.leaving_reason,
      sp.conduct,
      sp.progress,
      sp.lc_date,
      cl.STVCLAS_standard,
      cl.STVCLAS_class,
      (SELECT DISTINCT lc_status FROM lc_status WHERE student_id = sp.id LIMIT 1) AS lc_status
    FROM spriden sp
    INNER JOIN stvclas cl ON sp.id = cl.STVCLAS_student_id
    WHERE sp.SPRIDEN_user_type = 'st'
      AND cl.STVCLAS_acdm = ?
  `;
  const params = [acdmYear];

  if (standard && standard !== 'ALL') {
    query += ` AND cl.STVCLAS_standard = ?`;
    params.push(standard);
  }

  query += ` ORDER BY CAST(sp.spriden_gr_number AS UNSIGNED) ASC`;
  const [rows] = await pool.query(query, params);
  return rows;
}

export async function issueLc(studentId, { leaving_date, leaving_reason, conduct = 'Good', progress = 'Satisfactory', lc_date, remark = '' }, userId) {
  const finalLcDate = lc_date || new Date().toISOString().slice(0, 10);
  const finalLeavingDate = leaving_date || new Date().toISOString().slice(0, 10);

  // Update spriden
  await pool.query(
    `UPDATE spriden
     SET leaving_date = ?, leaving_reason = ?, conduct = ?, progress = ?, lc_date = ?, remark = ?, spriden_student_status = 'I'
     WHERE id = ?`,
    [finalLeavingDate, leaving_reason, conduct, progress, finalLcDate, remark, studentId]
  );

  // Update or insert lc_status
  const [[existLc]] = await pool.query('SELECT id FROM lc_status WHERE student_id = ? LIMIT 1', [studentId]);
  if (existLc) {
    await pool.query(
      `UPDATE lc_status SET lc_status = 'Issued', date = ?, updated_by = ? WHERE id = ?`,
      [finalLcDate, userId || 1, existLc.id]
    );
  } else {
    await pool.query(
      `INSERT INTO lc_status (student_id, lc_status, date, updated_by) VALUES (?, 'Issued', ?, ?)`,
      [studentId, finalLcDate, userId || 1]
    );
  }

  return { success: true };
}

// ─────────────────────────────────────────────────────────────────────────────
// 10. MASTER DROPDOWN OPTIONS
// ─────────────────────────────────────────────────────────────────────────────

export async function getMasterOptions() {
  const [academicYears] = await pool.query('SELECT stvacdm_id, stvacdm_year, stvacdm_start, stvacdm_end FROM stvacdm ORDER BY stvacdm_id DESC');
  const [classes] = await pool.query('SELECT DISTINCT STVCLAS_standard FROM stvclas WHERE STVCLAS_standard IS NOT NULL AND STVCLAS_standard != "" ORDER BY STVCLAS_standard');
  const [divisions] = await pool.query('SELECT DISTINCT STVCLAS_class FROM stvclas WHERE STVCLAS_class IS NOT NULL AND STVCLAS_class != "" ORDER BY STVCLAS_class');
  const [[currentSchool]] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');

  return {
    academicYears,
    classes: classes
      .map(c => c.STVCLAS_standard)
      .filter(c => c && !c.includes('PHP') && !c.includes('<') && c.length <= 15),
    divisions: divisions
      .map(d => d.STVCLAS_class)
      .filter(d => d && !d.includes('PHP') && !d.includes('<') && d.length <= 5),
    currentAcdmId: currentSchool?.stvschool_acdm_year || academicYears[0]?.stvacdm_id,
  };
}
