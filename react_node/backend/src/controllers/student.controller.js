/**
 * Student Controller — manages all REST endpoints for Student module.
 */
import {
  getStudentsList,
  getStudentById,
  generateNextStudentId,
  createStudent,
  updateStudent,
  changeStudentStatus,
  getPromotionStudents,
  promoteStudents,
  getReadmissionDetails,
  processReadmission,
  getLcRecords,
  issueLc,
  getMasterOptions,
} from '../models/Student.model.js';
import { success, failure, toInt } from '../utils/helpers.js';

/**
 * GET /api/students
 * Query: acdm_year, status, type ('ssc' | 'jc'), search
 */
export async function getStudents(req, res) {
  try {
    const { acdm_year, status, type, search } = req.query;
    const data = await getStudentsList({
      acdm_year: acdm_year ? toInt(acdm_year) : undefined,
      status: status || 'A',
      type: type || 'ssc',
      search: search || '',
    });
    return success(res, { data });
  } catch (err) {
    console.error('getStudents error:', err);
    return failure(res, 'Failed to fetch students: ' + err.message, 500);
  }
}

/**
 * GET /api/students/options
 */
export async function getOptions(req, res) {
  try {
    const data = await getMasterOptions();
    return success(res, { data, options: data, ...data });
  } catch (err) {
    console.error('getOptions error:', err);
    return failure(res, 'Failed to fetch options: ' + err.message, 500);
  }
}

/**
 * GET /api/students/next-id
 * Query: academic_year_id
 */
export async function getNextId(req, res) {
  try {
    const academicYearId = req.query.academic_year_id ? toInt(req.query.academic_year_id) : undefined;
    const data = await generateNextStudentId(academicYearId);
    return success(res, { data });
  } catch (err) {
    console.error('getNextId error:', err);
    return failure(res, 'Failed to generate ID: ' + err.message, 500);
  }
}

/**
 * GET /api/students/:id
 */
export async function getStudent(req, res) {
  try {
    const id = toInt(req.params.id);
    if (!id) return failure(res, 'Invalid student ID', 400);

    const student = await getStudentById(id);
    if (!student) return failure(res, 'Student not found', 404);

    return success(res, { data: student });
  } catch (err) {
    console.error('getStudent error:', err);
    return failure(res, 'Failed to fetch student details: ' + err.message, 500);
  }
}

/**
 * POST /api/students
 * Creates new student admission
 */
export async function postStudent(req, res) {
  try {
    const payload = req.body;
    if (req.file) {
      payload.photo_filename = req.file.filename;
    }

    if (!payload.student_name || !payload.student_id) {
      return failure(res, 'Student name and ID are required.', 400);
    }

    const userId = req.user?.id || 1;
    const result = await createStudent(payload, userId);
    return success(res, { message: 'Student registered successfully!', data: result }, 201);
  } catch (err) {
    console.error('postStudent error:', err);
    return failure(res, 'Failed to create student: ' + err.message, 500);
  }
}

/**
 * PUT /api/students/:id
 * Updates existing student
 */
export async function putStudent(req, res) {
  try {
    const id = toInt(req.params.id);
    if (!id) return failure(res, 'Invalid student ID', 400);

    const payload = req.body;
    if (req.file) {
      payload.photo_filename = req.file.filename;
    }

    const userId = req.user?.id || 1;
    await updateStudent(id, payload, userId);
    return success(res, { message: 'Student details updated successfully!' });
  } catch (err) {
    console.error('putStudent error:', err);
    return failure(res, 'Failed to update student: ' + err.message, 500);
  }
}

/**
 * PATCH /api/students/:id/status
 * Changes status (A, I, N) and logs activity
 */
export async function patchStatus(req, res) {
  try {
    const id = toInt(req.params.id);
    const { status, reason } = req.body;
    if (!id || !status) return failure(res, 'Student ID and status are required', 400);

    const userId = req.user?.id || 1;
    const result = await changeStudentStatus(id, status, reason, userId);
    return success(res, { message: `Student status changed to ${status}`, data: result });
  } catch (err) {
    console.error('patchStatus error:', err);
    return failure(res, 'Failed to change student status: ' + err.message, 500);
  }
}

/**
 * GET /api/students/promotions/candidates
 * Query: acdm_year, standard, division
 */
export async function getPromotionCandidates(req, res) {
  try {
    let year = req.query.acdm_year || req.query.academic_year || req.query.from_year;
    const standard = req.query.standard || req.query.class || req.query.std;
    const division = req.query.division || req.query.div;
    if (!year) {
      const opts = await getMasterOptions();
      year = opts.currentAcdmId || 9;
    }
    if (!standard) {
      return failure(res, 'Standard is required', 400);
    }
    const data = await getPromotionStudents({
      acdm_year: toInt(year),
      standard,
      division,
    });
    return success(res, { data });
  } catch (err) {
    console.error('getPromotionCandidates error:', err);
    return failure(res, 'Failed to fetch promotion candidates: ' + err.message, 500);
  }
}

/**
 * POST /api/students/promotions
 * Body: { students: [], rollnos: [], targetAcdm, targetStandard, targetDivision }
 */
export async function postPromotion(req, res) {
  try {
    const { students, rollnos, targetAcdm, targetStandard, targetDivision } = req.body;
    if (!students || !Array.isArray(students) || students.length === 0) {
      return failure(res, 'Please select at least one student to promote.', 400);
    }
    if (!targetAcdm || !targetStandard || !targetDivision) {
      return failure(res, 'Target academic year, standard, and division are required.', 400);
    }

    const userId = req.user?.id || 1;
    const result = await promoteStudents({
      students,
      rollnos: rollnos || [],
      targetAcdm: toInt(targetAcdm),
      targetStandard,
      targetDivision,
      userId,
    });

    return success(res, {
      message: `Promotion completed: ${result.promoted} promoted, ${result.skipped} skipped.`,
      data: result,
    });
  } catch (err) {
    console.error('postPromotion error:', err);
    return failure(res, 'Failed to process promotions: ' + err.message, 500);
  }
}

/**
 * GET /api/students/readmission/details
 * Query: student_id, acdm_year
 */
export async function getReadmission(req, res) {
  try {
    const studentId = toInt(req.query.student_id);
    if (!studentId) return failure(res, 'student_id is required', 400);

    const data = await getReadmissionDetails(studentId, req.query.acdm_year);
    if (!data) return failure(res, 'Student not found', 404);

    return success(res, { data });
  } catch (err) {
    console.error('getReadmission error:', err);
    return failure(res, 'Failed to fetch re-admission details: ' + err.message, 500);
  }
}

/**
 * POST /api/students/readmission
 * Body: { studentId, targetAcdm, targetStandard, targetDivision, rollNo, note }
 */
export async function postReadmission(req, res) {
  try {
    const { studentId, targetAcdm, targetStandard, targetDivision, rollNo, note } = req.body;
    if (!studentId || !targetAcdm || !targetStandard || !targetDivision) {
      return failure(res, 'Missing required fields for re-admission', 400);
    }

    const userId = req.user?.id || 1;
    await processReadmission({
      studentId: toInt(studentId),
      targetAcdm: toInt(targetAcdm),
      targetStandard,
      targetDivision,
      rollNo: toInt(rollNo, 0),
      note,
      userId,
    });

    return success(res, { message: 'Student re-admission completed successfully!' });
  } catch (err) {
    console.error('postReadmission error:', err);
    return failure(res, 'Failed to process re-admission: ' + err.message, 500);
  }
}

/**
 * GET /api/students/lc/records
 * Query: acdm_year, standard
 */
export async function getLc(req, res) {
  try {
    const year = req.query.acdm_year || req.query.academic_year || req.query.year;
    const { standard } = req.query;
    if (!year) {
      // Default to current academic year if omitted
      const pool = (await import('../config/db.js')).default;
      const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
      const activeYear = rows[0]?.stvschool_acdm_year || 9;
      const data = await getLcRecords(toInt(activeYear), standard);
      return success(res, { data });
    }

    const data = await getLcRecords(toInt(year), standard);
    return success(res, { data });
  } catch (err) {
    console.error('getLc error:', err);
    return failure(res, 'Failed to fetch LC records: ' + err.message, 500);
  }
}

/**
 * POST /api/students/:id/issue-lc
 * Body: { leaving_date, leaving_reason, conduct, progress, lc_date, remark }
 */
export async function postIssueLc(req, res) {
  try {
    const id = toInt(req.params.id);
    if (!id) return failure(res, 'Invalid student ID', 400);

    const userId = req.user?.id || 1;
    await issueLc(id, req.body, userId);
    return success(res, { message: 'Leaving Certificate issued successfully!' });
  } catch (err) {
    console.error('postIssueLc error:', err);
    return failure(res, 'Failed to issue Leaving Certificate: ' + err.message, 500);
  }
}

/**
 * GET /api/students/portal-profile
 * GET /api/students/portal-profile/:studentId
 * Student Self-Service Portal profile (CI3: Profile.php / new-sp.php)
 */
export async function getStudentPortalProfile(req, res) {
  try {
    const studentId =
      req.params.studentId ||
      req.query.student_id ||
      req.query.id ||
      req.user?.student_id ||
      req.user?.id;

    if (!studentId) {
      return failure(res, 'Student ID could not be identified', 400);
    }

    const { academic_year, academicYear } = req.query;
    const StudentProfileModel = (await import('../models/StudentProfile.model.js')).default;
    const profile = await StudentProfileModel.getStudentPortalProfile({
      studentId,
      academicYear: academic_year || academicYear,
    });

    if (!profile) {
      return failure(res, 'Student profile not found', 404);
    }

    return success(res, { profile });
  } catch (err) {
    console.error('getStudentPortalProfile error:', err);
    return failure(res, 'Failed to load student portal profile: ' + err.message, 500);
  }
}

