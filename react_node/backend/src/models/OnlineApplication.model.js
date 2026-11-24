import pool from '../config/db.js';

class OnlineApplicationModel {
  /**
   * List all school applications (ext_application_header)
   */
  static async getSchoolApplications() {
    const [rows] = await pool.query(
      `SELECT 
        h.id,
        h.student_name,
        h.father_name,
        h.mobile_no,
        h.email,
        h.standard_applied,
        h.previous_school,
        h.application_status,
        h.created_at,
        h.comments
       FROM ext_application_header h
       ORDER BY h.id DESC`
    );
    return rows;
  }

  /**
   * List all Junior College applications (clg_stu_det)
   */
  static async getJuniorCollegeApplications() {
    const [rows] = await pool.query(
      `SELECT 
        Id AS id,
        Application_id,
        db_branch,
        db_stream,
        db_admissiontype,
        db_class,
        db_division,
        db_medium,
        CONCAT(IFNULL(firstname, ''), ' ', IFNULL(Middlename, ''), ' ', IFNULL(lastname, '')) AS student_name,
        firstname,
        Middlename,
        lastname,
        date AS application_date,
        db_gender,
        db_religion,
        db_caste,
        db_adharnumber,
        choice_subj_hin_mar,
        choice_math_geog_bio,
        commerce_optional_sub,
        commerce_sub_h_m
       FROM clg_stu_det
       ORDER BY Id DESC`
    );
    return rows;
  }

  /**
   * Get single school application with line items
   */
  static async getSchoolApplicationById(id) {
    const [headerRows] = await pool.query(
      'SELECT * FROM ext_application_header WHERE id = ?',
      [id]
    );
    if (!headerRows.length) return null;

    const [lineRows] = await pool.query(
      'SELECT * FROM ext_application_lines WHERE header_id = ?',
      [id]
    );

    return {
      header: headerRows[0],
      lines: lineRows,
    };
  }

  /**
   * Update school application status
   */
  static async updateSchoolApplicationStatus(id, status, comments) {
    const [result] = await pool.query(
      `UPDATE ext_application_header 
       SET application_status = ?, comments = ? 
       WHERE id = ?`,
      [status, comments, id]
    );
    return result.affectedRows > 0;
  }

  /**
   * Delete school application
   */
  static async deleteSchoolApplication(id) {
    await pool.query('DELETE FROM ext_application_lines WHERE header_id = ?', [id]);
    const [result] = await pool.query('DELETE FROM ext_application_header WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }

  /**
   * Delete junior college application
   */
  static async deleteJuniorCollegeApplication(id) {
    await pool.query('DELETE FROM clg_par_in WHERE application_ref_id = ?', [id]);
    await pool.query('DELETE FROM clg_com_det WHERE application_id_ref = ?', [id]);
    await pool.query('DELETE FROM clg_exm_det WHERE application_id_ref = ?', [id]);
    await pool.query('DELETE FROM clg_doc_det WHERE application_id_ref = ?', [id]);
    const [result] = await pool.query('DELETE FROM clg_stu_det WHERE Id = ?', [id]);
    return result.affectedRows > 0;
  }

  /**
   * Submit Junior College Application (CI3: Forms/add_college_form & Form_college.php)
   */
  static async submitJuniorCollegeApplication(data) {
    const applicationId = "OLV" + String(Math.floor(Math.random() * 900000) + 100000);
    const math_selected = data.geog_maths_biology_m || (data.optional_subjects?.includes('Mathematics') ? 'Mathematics' : '');
    const bio_selected = data.geog_maths_biology_b || (data.optional_subjects?.includes('Biology') ? 'Biology' : '');
    const geo_selected = data.geog_maths_biology_g || (data.optional_subjects?.includes('Geography') ? 'Geography' : '');
    const choice_math_geog_bio = [math_selected, bio_selected, geo_selected].filter(Boolean).join('-');

    // 1. Insert clg_stu_det
    const [stuResult] = await pool.query(
      `INSERT INTO clg_stu_det (
        Application_id, db_branch, db_stream, db_admissiontype, db_class, db_division, db_medium,
        firstname, Middlename, lastname, date, db_nationality, db_place, db_gender,
        db_mothertounge, db_religion, db_caste, db_adharnumber,
        choice_subj_hin_mar, choice_math_geog_bio, commerce_optional_sub, commerce_sub_h_m
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        applicationId,
        data.db_branch || 'Olive Junior College',
        data.db_stream || 'Science',
        data.db_admissiontype || 'New Admission',
        data.db_class || '11th',
        data.db_division || 'A',
        data.db_medium || 'English',
        data.firstname || '',
        data.Middlename || data.middlename || '',
        data.lastname || '',
        data.date || new Date().toISOString().slice(0, 10),
        data.db_nationality || 'Indian',
        data.db_place || '',
        data.db_gender || 'Male',
        data.db_mothertounge || 'Urdu',
        data.db_religion || 'Islam',
        data.db_caste || '',
        data.db_adharnumber || '',
        data.choice_subj_hin_mar || data.marathi_hindi || '',
        choice_math_geog_bio,
        data.commerce_optional_sub || data.commerce_sub || '',
        data.commerce_sub_h_m || data.commerce_h_m || '',
      ]
    );

    const studentInsertedId = stuResult.insertId;

    // 2. Insert clg_par_in
    await pool.query(
      `INSERT INTO clg_par_in (
        application_ref_id, db_fatherfirstname, db_fathermiddlename, db_fatherlastname,
        db_fatheradharnumber, db_fatherqualification, db_fatheroccupation, db_fathersannualincome,
        db_mothersname, db_mothersqualificaiton, db_mothersoccupation, db_mothersannualincome,
        db_parentsmobile, db_parentsemail
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        studentInsertedId,
        data.db_fatherfirstname || '',
        data.db_fathermiddlename || '',
        data.db_fatherlastname || '',
        data.db_fatheradharnumber || '',
        data.db_fatherqualification || '',
        data.db_fatheroccupation || '',
        data.db_fathersannualincome || '',
        data.db_mothersname || '',
        data.db_mothersqualificaiton || '',
        data.db_mothersoccupation || '',
        data.db_mothersannualincome || '',
        data.db_parentsmobile || '',
        data.db_parentsemail || '',
      ]
    );

    // 3. Insert clg_com_det
    await pool.query(
      `INSERT INTO clg_com_det (
        application_id_ref, db_permenantaddress, db_landmark, db_pincode,
        db_telephonenumber, db_mobilenumber, db_email, db_lastschool, db_lastudiseno, student_id
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        studentInsertedId,
        data.db_permenantaddress || data.db_pemrenantaddress || '',
        data.db_landmark || '',
        data.db_pincode || '',
        data.db_telephonenumber || '-',
        data.db_mobilenumber || '',
        data.db_email || '',
        data.db_lastschool || '',
        data.db_lastudiseno || '',
        data.student_id || '',
      ]
    );

    // 4. Insert clg_exm_det
    await pool.query(
      `INSERT INTO clg_exm_det (
        application_id_ref, db_lastclass, db_lastboard, db_lastyear,
        db_lastseatno, db_lastmarks, db_lastoutof, db_lastpercentage, db_lastappeared
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        studentInsertedId,
        data.db_lastclass || '10th',
        data.db_lastboard || 'SSC',
        Number(data.db_lastyear) || new Date().getFullYear(),
        data.db_lastseatno || '',
        Number(data.db_lastmarks) || 0,
        Number(data.db_lastoutof) || 0,
        Number(data.db_lastpercentage) || 0,
        data.db_lastappeared || '',
      ]
    );

    // 5. Insert clg_doc_det
    await pool.query(
      `INSERT INTO clg_doc_det (
        application_id_ref, birth_certificate, birth_certificate_docu,
        school_leaving_certi, school_leaving_docu, progress_report_Card, progress_report_docu,
        passport_photo_student, passport_photo_docu, nationality_domicle,
        nationality_domicle_docx, caste_certificate, caste_certificate_docu,
        aadhar_card, aadhar_card_docu
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        studentInsertedId,
        data.birth_certificate || '',
        data.birth_certificate_docu || '',
        data.school_leaving || data.school_leaving_certi || '',
        data.school_leaving_docu || '',
        data.progress_report || data.progress_report_Card || '',
        data.progress_report_docu || '',
        data.passport_photo_student || '',
        data.passport_photo_docu || '',
        data.nationality_domicile || data.nationality_domicle || '',
        data.nationality_domicile_docu || data.nationality_domicle_docx || '',
        data.caste_certificate || '',
        data.caste_certificate_docu || '',
        typeof data.aadhar_card === 'object' ? JSON.stringify(data.aadhar_card) : (data.aadhar_card || ''),
        data.aadhar_card_docu || '',
      ]
    );

    return {
      success: true,
      id: studentInsertedId,
      applicationId,
    };
  }

  /**
   * Get single Junior College application with all 5 joined tables
   */
  static async getJuniorCollegeApplicationById(id) {
    const [rows] = await pool.query(
      `SELECT clg_stu_det.*, clg_par_in.*, clg_com_det.*, clg_exm_det.*, clg_doc_det.* 
       FROM clg_stu_det 
       LEFT JOIN clg_par_in ON clg_par_in.application_ref_id = clg_stu_det.Id 
       LEFT JOIN clg_com_det ON clg_com_det.application_id_ref = clg_stu_det.Id 
       LEFT JOIN clg_exm_det ON clg_exm_det.application_id_ref = clg_stu_det.Id 
       LEFT JOIN clg_doc_det ON clg_doc_det.application_id_ref = clg_stu_det.Id 
       WHERE clg_stu_det.Id = ?`,
      [id]
    );
    return rows[0] || null;
  }
}

export default OnlineApplicationModel;

