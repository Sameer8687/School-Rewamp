import pool from '../config/db.js';

class StudentProfileModel {
  /**
   * Fetch complete student portal profile data matching CI3 Profile.php and new-sp.php
   */
  static async getStudentPortalProfile({ studentId, academicYear }) {
    // 1. Get active academic year if not provided
    let acdmYear = academicYear;
    if (!acdmYear) {
      const [[school]] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
      acdmYear = school?.stvschool_acdm_year || 1;
    }

    // 2. Fetch Student Primary Record (by spriden.id or SPRIDEN_student_id)
    const [studentRows] = await pool.query(
      `SELECT 
        s.id,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        s.SPRIDEN_reg_number,
        s.SPRIDEN_surname,
        s.SPRIDEN_student_name,
        s.SPRIDEN_father_name,
        s.SPRIDEN_mother_name,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS full_name,
        s.SPRIDEN_gender,
        s.SPRIDEN_birthdate,
        s.SPRIDEN_birth_place,
        s.SPRIDEN_religion,
        s.SPRIDEN_nationality,
        s.SPRIDEN_mother_toungue,
        s.SPRIDEN_aadhar_no,
        s.SPRIDEN_main_address,
        s.SPRIDEN_alt_address,
        s.SPRIDEN_photo_path,
        s.spriden_student_status,
        s.rte_status,
        s.spriden_auth_token,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_rollno,
        c.STVCLAS_acdm,
        a.stvacdm_year,
        p.stvpcnf_mobile,
        p.stvpcnf_whatsapp,
        p.stvpcnf_gmail AS stvpcnf_email
      FROM spriden s
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
      LEFT JOIN stvacdm a ON a.stvacdm_id = ?
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      WHERE (s.id = ? OR s.SPRIDEN_student_id = ?)
      LIMIT 1`,
      [acdmYear, acdmYear, studentId, studentId]
    );

    if (studentRows.length === 0) {
      return null;
    }

    const student = studentRows[0];
    const internalId = student.id;
    const std = student.STVCLAS_standard || '';
    const div = student.STVCLAS_class || '';

    // 3. Class Teacher Info (from spatesd & stvtcnf)
    let teacherInfo = null;
    if (std && div) {
      const [teacherRows] = await pool.query(
        `SELECT 
          d.SPATESD_teacher_id,
          CONCAT(IFNULL(t.SPRIDEN_surname, ''), ' ', IFNULL(t.SPRIDEN_student_name, ''), ' ', IFNULL(t.SPRIDEN_father_name, '')) AS teacher_name,
          tc.mobile AS teacher_mobile,
          tc.whatsapp AS teacher_whatsapp
        FROM spatesd d
        JOIN spriden t ON t.SPRIDEN_student_id = d.SPATESD_teacher_id
        LEFT JOIN stvtcnf tc ON tc.teacher_id = d.SPATESD_teacher_id
        WHERE d.SPATESD_std = ? AND d.SPATESD_div = ? AND d.SPATESD_academic_year = ?
        LIMIT 1`,
        [std, div, acdmYear]
      );
      if (teacherRows.length > 0) {
        teacherInfo = teacherRows[0];
      }
    }

    // 4. Attendance Summary (from spratnd)
    const [attRows] = await pool.query(
      `SELECT 
        SPRATND_status,
        COUNT(*) AS count
      FROM spratnd
      WHERE SPRATND_student_id = ? AND (SPRATND_acdm_year = ? OR SPRATND_acdm_year IS NULL)
      GROUP BY SPRATND_status`,
      [internalId, acdmYear]
    );

    let presentDays = 0;
    let absentDays = 0;
    attRows.forEach((r) => {
      if (r.SPRATND_status === 'P') presentDays += Number(r.count);
      else if (r.SPRATND_status === 'A') absentDays += Number(r.count);
    });
    const totalWorkingDays = presentDays + absentDays;
    const attendancePercentage =
      totalWorkingDays > 0 ? Math.round((presentDays / totalWorkingDays) * 1000) / 10 : 0;

    // Monthly attendance breakdown
    const [monthlyAttRows] = await pool.query(
      `SELECT 
        DATE_FORMAT(SPRATND_date, '%b %Y') AS month_name,
        DATE_FORMAT(SPRATND_date, '%Y-%m') AS month_key,
        SUM(CASE WHEN SPRATND_status = 'P' THEN 1 ELSE 0 END) AS present_count,
        SUM(CASE WHEN SPRATND_status = 'A' THEN 1 ELSE 0 END) AS absent_count,
        COUNT(*) AS total_count
      FROM spratnd
      WHERE SPRATND_student_id = ?
      GROUP BY month_key, month_name
      ORDER BY month_key DESC
      LIMIT 12`,
      [internalId]
    );

    // 5. Fees Ledger (from stvfstr and sprfees)
    const [[feeStructure]] = await pool.query(
      `SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = ? AND stvfstr_acdm_year = ? LIMIT 1`,
      [std, acdmYear]
    );
    const totalClassFees = parseFloat(feeStructure?.stvfstr_fees) || 0;

    const [feeReceipts] = await pool.query(
      `SELECT 
        f.sprfees_pk AS id,
        f.sprfees_amount,
        f.sprfees_feesdate,
        f.sprfees_exumption,
        f.sprfees_notes AS sprfees_details,
        f.sprfees_due_date,
        f.payment_mode,
        u.spruser_name AS received_by_name
      FROM sprfees f
      LEFT JOIN spruser u ON u.spruser_id = f.sprfees_recivedby
      WHERE f.sprfees_studentid = ? AND f.academic_year = ?
      ORDER BY f.sprfees_feesdate DESC, f.sprfees_pk DESC`,
      [internalId, acdmYear]
    );

    const totalPaid = feeReceipts.reduce((sum, r) => sum + (parseFloat(r.sprfees_amount) || 0), 0);
    const totalExemption = feeReceipts.reduce(
      (sum, r) => sum + (parseFloat(r.sprfees_exumption) || 0),
      0
    );
    const currentBalance = Math.max(0, totalClassFees - (totalPaid + totalExemption));

    // Last year balance (if any)
    const [lastYearFees] = await pool.query(
      `SELECT 
        SUM(IFNULL(fs.stvfstr_fees, 0)) - SUM(IFNULL(f.sprfees_amount, 0)) AS last_year_balance
      FROM stvclas c
      JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
      LEFT JOIN sprfees f ON f.sprfees_studentid = c.STVCLAS_student_id AND f.academic_year = c.STVCLAS_acdm
      WHERE c.STVCLAS_student_id = ? AND c.STVCLAS_acdm < ?`,
      [internalId, acdmYear]
    );
    const lastYearBalance = Math.max(0, parseFloat(lastYearFees[0]?.last_year_balance) || 0);
    const netTotalBalance = currentBalance + lastYearBalance;

    // 6. Exam Marks
    const [marksRows] = await pool.query(
      `SELECT 
        e.stvexam_id,
        e.stvexam_name,
        sb.stvsubj_subject AS subject_name,
        m.sprmark_total_marks AS max_marks,
        m.sprmark_mark AS marks_obtained,
        CASE 
          WHEN m.sprmark_total_marks > 0 AND (m.sprmark_mark / m.sprmark_total_marks) >= 0.85 THEN 'A+'
          WHEN m.sprmark_total_marks > 0 AND (m.sprmark_mark / m.sprmark_total_marks) >= 0.70 THEN 'A'
          WHEN m.sprmark_total_marks > 0 AND (m.sprmark_mark / m.sprmark_total_marks) >= 0.55 THEN 'B'
          WHEN m.sprmark_total_marks > 0 AND (m.sprmark_mark / m.sprmark_total_marks) >= 0.40 THEN 'C'
          ELSE 'D'
        END AS grade
      FROM sprmark m
      JOIN stvexam e ON e.stvexam_id = m.sprmark_exam_name
      LEFT JOIN stvsubj sb ON sb.stvsubj_id = m.sprmark_subject
      WHERE m.sprmark_student_id = ?
      ORDER BY e.stvexam_id ASC, sb.stvsubj_subject ASC`,
      [internalId]
    );

    // Group marks by exam
    const examsMap = {};
    marksRows.forEach((m) => {
      const examName = m.stvexam_name || 'Terminal Exam';
      if (!examsMap[examName]) {
        examsMap[examName] = { exam_name: examName, subjects: [], total_obtained: 0, total_max: 0 };
      }
      const obtained = parseFloat(m.marks_obtained) || 0;
      const maxM = parseFloat(m.max_marks) || 0;
      examsMap[examName].subjects.push({
        subject: m.subject_name || 'Subject',
        marks_obtained: obtained,
        max_marks: maxM,
        grade: m.grade || '',
      });
      examsMap[examName].total_obtained += obtained;
      examsMap[examName].total_max += maxM;
    });

    const examsList = Object.values(examsMap).map((ex) => ({
      ...ex,
      percentage:
        ex.total_max > 0 ? Math.round((ex.total_obtained / ex.total_max) * 1000) / 10 : 0,
    }));

    // 7. Assigned Subjects & Teachers
    const [subjectsRows] = await pool.query(
      `SELECT 
        s.stvsubj_id,
        s.stvsubj_subject,
        s.stvsubj_std
      FROM stvsubj s
      WHERE s.stvsubj_std = ? AND s.stvsubj_acdm = ?
      ORDER BY s.stvsubj_subject ASC`,
      [std, acdmYear]
    );

    // 8. Teacher Remarks (from stvrmrk)
    let remarks = [];
    try {
      const [remRows] = await pool.query(
        `SELECT 
          r.stvrmrk_id AS id,
          r.stvrmrk_remark AS remark,
          r.stvrmrk_date AS date,
          r.stvrmrk_reply AS reply,
          CONCAT(IFNULL(t.SPRIDEN_surname, ''), ' ', IFNULL(t.SPRIDEN_student_name, '')) AS teacher_name
        FROM stvrmrk r
        LEFT JOIN spriden t ON t.SPRIDEN_student_id = r.stvrmrk_teacher_id
        WHERE r.stvrmrk_student_id = ?
        ORDER BY r.stvrmrk_date DESC`,
        [internalId]
      );
      remarks = remRows;
    } catch {
      remarks = [];
    }

    // 9. Upcoming School Events
    const [eventsRows] = await pool.query(
      `SELECT 
        stvevnt_id AS id,
        stvevnt_title AS title,
        stvevnt_details AS details,
        stvevnt_start_date AS start_date,
        stvevnt_end_date AS end_date
      FROM stvevnt
      WHERE (stvevnt_std = ? OR stvevnt_std = 'ALL' OR stvevnt_std IS NULL)
        AND (stvevnt_academic_year = ? OR stvevnt_academic_year IS NULL)
      ORDER BY stvevnt_start_date DESC
      LIMIT 10`,
      [std, acdmYear]
    );

    // 10. Student Hold status (sprhold)
    const [[holdRow]] = await pool.query(
      `SELECT sprhold_status, sprhold_from, sprhold_to 
       FROM sprhold 
       WHERE sprhold_student_id = ? AND sprhold_status = 'Yes' 
       LIMIT 1`,
      [internalId]
    );
    const isHold = Boolean(holdRow);

    // 11. Web App Visibility
    const [[visibility]] = await pool.query(`SELECT * FROM web_app_visibility LIMIT 1`);

    return {
      student,
      teacher: teacherInfo,
      hold: {
        is_hold: isHold,
        details: holdRow || null,
      },
      visibility: visibility || { student_profile: 'yes', marks_visibility: 'yes', attendance_visibility: 'yes' },
      attendance: {
        present_days: presentDays,
        absent_days: absentDays,
        total_working_days: totalWorkingDays,
        percentage: attendancePercentage,
        monthly_breakdown: monthlyAttRows,
      },
      fees: {
        total_class_fees: totalClassFees,
        total_paid: totalPaid,
        total_exemption: totalExemption,
        current_balance: currentBalance,
        last_year_balance: lastYearBalance,
        net_total_balance: netTotalBalance,
        receipts: feeReceipts,
      },
      exams: examsList,
      subjects: subjectsRows,
      remarks,
      events: eventsRows,
    };
  }
}

export default StudentProfileModel;
