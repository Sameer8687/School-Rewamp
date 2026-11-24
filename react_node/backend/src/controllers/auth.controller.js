/**
 * Auth Controller — handles HTTP request/response for all login flows.
 *
 * CI3 equivalents:
 *   Login.php::validate()          → adminLogin()
 *   Teacher_login.php::validate()  → teacherLogin()
 *   Student_login.php::validate()  → studentLogin()
 *   Student_login.php::index(auth) → studentTokenLogin()
 *   Teacher_login.php::getAssignedClasses() → getTeacherClasses()
 *   Student_login.php::index()     → getAcademicYears()
 *
 * This controller ONLY deals with HTTP layer.
 * All database queries live in models/Auth.model.js.
 */
import { signToken } from '../utils/jwt.js';
import { indianDateTime, failure } from '../utils/helpers.js';
import {
  findAdminByCredentials,
  updateAdminAuthToken,
  findTeacherByCredentials,
  getTeacherAssignedClasses,
  findStudentById,
  addLoginHistory,
  upsertAppTracking,
  getAcademicYears as fetchAcademicYears,
  updateUserPassword,
} from '../models/Auth.model.js';

// ─────────────────────────────────────────────
// Helper: set JWT in cookie + respond with body
// ─────────────────────────────────────────────
function sendAuthResponse(res, user) {
  const token = signToken(user);

  res.cookie('token', token, {
    httpOnly: true,
    secure:   process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge:   3 * 60 * 60 * 1000,   // 3 h — matches CI3 cookie expiry
  });

  return res.status(200).json({ success: true, token, user });
}

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/auth/admin-login
// CI3: Login::validate()
// ─────────────────────────────────────────────────────────────────────────────
export async function adminLogin(req, res) {
  const { username, password } = req.body;

  if (!username || !password) {
    return failure(res, 'Username and Password are required.', 400);
  }

  const admin = await findAdminByCredentials(username.trim(), password);
  if (!admin) {
    return failure(res, 'Invalid Username or Password', 401);
  }

  // Record login history in stvlgin (mirrors addLoginHistory)
  await addLoginHistory(admin.spruser_id);

  // Write sha256 token to spruser_auth_token (legacy cookie compat)
  await updateAdminAuthToken(admin.spruser_id);

  const user = {
    id:       admin.spruser_id,
    role:     'admin',
    userType: admin.spruser_usertype || 'Admin',
    name:     admin.spruser_name || admin.spruser_username,
    username: admin.spruser_username,
    email:    admin.spruser_email   || null,
    mobile:   admin.spruser_mobile  || null,
  };

  return sendAuthResponse(res, user);
}

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/auth/teacher-login
// CI3: Teacher_login::validate()
// ─────────────────────────────────────────────────────────────────────────────
export async function teacherLogin(req, res) {
  const { username, password, classType } = req.body;

  if (!username || !password) {
    return failure(res, 'Teacher ID and Password are required.', 400);
  }

  const teacher = await findTeacherByCredentials(username.trim(), password);
  if (!teacher) {
    return failure(res, 'Invalid Credentials..! Please Check ID & Password', 401);
  }

  const teacherId = teacher.SPRIDEN_student_id;

  // Parse classType: "XI-A" → std=XI, div=A (mirrors CI3 explode logic)
  let sessionStd = null;
  let sessionDiv = null;
  if (classType && classType.includes('-')) {
    const [std, ...divParts] = classType.split('-');
    sessionStd = std;
    sessionDiv = divParts.join('-');
  }

  // App tracking (mirrors Login_model::manageLoginHistory)
  await upsertAppTracking(teacherId, 'tc');

  const teacherFullName = [teacher.SPRIDEN_surname, teacher.SPRIDEN_student_name]
    .filter(Boolean).join(' ').trim() || teacherId;

  const user = {
    id:         teacher.id,
    odId:       teacherId,
    role:       'teacher',
    userType:   'tc',
    name:       teacherFullName,
    classType:  classType || 'Assigned',
    sessionStd,
    sessionDiv,
    loginData:  indianDateTime(),
  };

  return sendAuthResponse(res, user);
}

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/auth/student-login
// CI3: Student_login::validate()
// ─────────────────────────────────────────────────────────────────────────────
export async function studentLogin(req, res) {
  const { username, academicYear } = req.body;

  if (!username) {
    return failure(res, 'Enter Valid Student ID', 400);
  }

  const student = await findStudentById(username.trim());
  if (!student) {
    return failure(res, 'Enter Valid Student ID', 401);
  }

  // App tracking (mirrors Login_model::studentLoginHistory)
  await upsertAppTracking(student.id, 'st');

  const studentFullName = [student.SPRIDEN_surname, student.SPRIDEN_student_name, student.SPRIDEN_father_name]
    .filter(Boolean).join(' ').trim() || student.SPRIDEN_student_id;

  const user = {
    id:           student.id,
    odId:         student.SPRIDEN_student_id,
    role:         'student',
    userType:     'st',
    name:         studentFullName,
    academicYear: academicYear || student.SPRIDEN_academic_year || null,
  };

  return sendAuthResponse(res, user);
}

// ─────────────────────────────────────────────────────────────────────────────
// GET /api/auth/student-token-auth?student-id=...&token=...
// CI3: Student_login::index() – the ?auth=true block
// ─────────────────────────────────────────────────────────────────────────────
export async function studentTokenLogin(req, res) {
  const studentId = req.query['student-id'] || req.query.studentId;
  const token     = req.query.token;

  if (!studentId || !token) {
    return failure(res, 'Student ID and Token are required.', 400);
  }

  const student = await findStudentById(studentId.trim());
  if (!student) {
    return failure(res, `Invalid Student ID ${studentId}`, 404);
  }

  if (student.spriden_auth_token !== token) {
    return failure(res, `Authentication Token For Student ID ${studentId} not matched`, 401);
  }

  await upsertAppTracking(student.id, 'st');

  const studentFullName = [student.SPRIDEN_surname, student.SPRIDEN_student_name, student.SPRIDEN_father_name]
    .filter(Boolean).join(' ').trim() || student.SPRIDEN_student_id;

  const user = {
    id:           student.id,
    odId:         student.SPRIDEN_student_id,
    role:         'student',
    userType:     'st',
    name:         studentFullName,
    academicYear: student.SPRIDEN_academic_year || null,
  };

  return sendAuthResponse(res, user);
}

// ─────────────────────────────────────────────────────────────────────────────
// GET /api/auth/me
// Return current user from decoded JWT (no DB hit needed)
// ─────────────────────────────────────────────────────────────────────────────
export async function getMe(req, res) {
  return res.status(200).json({ success: true, user: req.user, data: req.user });
}

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/auth/logout
// CI3: $this->session->sess_destroy()
// ─────────────────────────────────────────────────────────────────────────────
export async function logout(req, res) {
  if (req.user?.id) {
    const userId = req.user.odId || req.user.id;
    await addLoginHistory(userId, 'Logged Out');
  }

  res.clearCookie('token');
  return res.status(200).json({ success: true, message: 'Logged out successfully.' });
}

// ─────────────────────────────────────────────────────────────────────────────
// GET /api/auth/academic-years
// CI3: Student_login::index() — stvacdm + stvschool
// ─────────────────────────────────────────────────────────────────────────────
export async function getAcademicYears(req, res) {
  const data = await fetchAcademicYears();
  return res.status(200).json({ success: true, data });
}

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/auth/teacher-classes
// CI3: Teacher_login::getAssignedClasses()
// ─────────────────────────────────────────────────────────────────────────────
export async function getTeacherClasses(req, res) {
  const teacherId = (req.body.teacher_id || req.body.teacherId || '').trim();
  if (!teacherId) {
    return res.status(200).json({ status: false, data: [] });
  }

  const classes = await getTeacherAssignedClasses(teacherId);
  return res.status(200).json({ status: true, data: classes });
}

// ─────────────────────────────────────────────────────────────────────────────
// POST /api/auth/change-password
// CI3: Welcome::change_password() / Stats_model::change_password()
// ─────────────────────────────────────────────────────────────────────────────
export async function changePassword(req, res) {
  const { currentPassword, newPassword, confirmPassword } = req.body;

  if (!currentPassword || !newPassword) {
    return failure(res, 'Current and new password are required.', 400);
  }
  if (confirmPassword && newPassword !== confirmPassword) {
    return failure(res, 'New password and confirmation do not match.', 400);
  }

  const userId = req.user?.id;
  const role = req.user?.role || 'admin';

  if (!userId) {
    return failure(res, 'Unauthorized.', 401);
  }

  const result = await updateUserPassword(userId, role, currentPassword, newPassword);
  if (!result.success) {
    return failure(res, result.message || 'Failed to change password.', 400);
  }

  return res.status(200).json({ success: true, message: 'Password changed successfully.' });
}

