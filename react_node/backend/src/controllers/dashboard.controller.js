/**
 * Dashboard Controller — handles role-aware dashboard metrics and embedded widgets.
 *
 * CI3 equivalents:
 *   Admin   → Welcome::index(), Stats_model, Task.php
 *   Teacher → Dashboard::index(), Dashboard_model
 *   Student → Profile::index(), Profile_model
 */
import { failure } from '../utils/helpers.js';
import {
  getStudentTeacherCounts,
  getCurrentAcademicYear,
  getRecentLogins,
  getChartData,
  fetchDueDateRecords,
  getDailyFeesReport,
  fetchStudentFeeDetails,
  getAllTasks,
  insertTask,
  updateTask,
  updateTaskStatus as modelUpdateTaskStatus,
  deleteTask as modelDeleteTask,
  getTeacherAllocatedClasses,
  getClassRoster,
  getTeacherRemarksCounts,
  getStudentProfile,
  getStudentAttendanceSummary,
  getStudentRemarks,
  getStudentClassTeacher,
  getAllFormsList,
  getAllAcademicYearsForAdmin,
  fetchAllocatedForms,
} from '../models/Dashboard.model.js';

// ─────────────────────────────────────────────────────────────────────────────
// GET /api/dashboard/stats
// ─────────────────────────────────────────────────────────────────────────────
export async function getDashboardStats(req, res) {
  try {
    const { user } = req;
    const query = req.query || {};

    // ── ADMIN ──
    if (user.role === 'admin') {
      const from = query.dash_from || new Date().toISOString().slice(0, 10);
      const to   = query.dash_to   || new Date().toISOString().slice(0, 10);
      const year = query.dash_year || 'current';
      const newAdmsn = query.dash_new_admission || 'off';

      const [metrics, academicYear, loginsData, chartData, dueDateRecords, dailyFees, allAcademicYears, formsList, allocatedForms] = await Promise.all([
        getStudentTeacherCounts(),
        getCurrentAcademicYear(),
        getRecentLogins(user.id, 6),
        getChartData(),
        fetchDueDateRecords(),
        getDailyFeesReport(from, to, year, newAdmsn),
        getAllAcademicYearsForAdmin(),
        getAllFormsList(),
        fetchAllocatedForms(user.id),
      ]);

      return res.status(200).json({
        success: true,
        role: 'admin',
        data: {
          metrics,
          academicYear,
          allAcademicYears,
          formsList,
          allocatedForms,
          recentLogins: loginsData.recent,
          currentLogin: loginsData.current,
          chartData,
          dueDateRecords,
          dailyFees,
        },
      });
    }


    // ── TEACHER ──
    if (user.role === 'teacher') {
      const teacherId   = user.odId || user.username || '';
      const selectedStd = query.std || user.sessionStd;
      const selectedDiv = query.div || user.sessionDiv;

      const [allocatedClasses, remarksCounts] = await Promise.all([
        getTeacherAllocatedClasses(teacherId),
        getTeacherRemarksCounts(teacherId),
      ]);

      const std = selectedStd || allocatedClasses[0]?.SPATESD_std || '';
      const div = selectedDiv || allocatedClasses[0]?.SPATESD_div || '';

      const students = std && div ? await getClassRoster(std, div) : [];

      return res.status(200).json({
        success: true,
        role: 'teacher',
        data: {
          teacher: {
            id:            user.id,
            odId:          user.odId,
            name:          user.name,
            selectedClass: std && div ? `${std}-${div}` : (user.classType || 'Assigned'),
            sessionStd:    std,
            sessionDiv:    div,
            loginData:     user.loginData,
          },
          allocatedClasses,
          students,
          studentCount: students.length,
          remarksCounts,
        },
      });
    }

    // ── STUDENT ──
    if (user.role === 'student') {
      const raw = await getStudentProfile(user.id);
      if (!raw) {
        return failure(res, 'Student profile not found.', 404);
      }

      const [attendance, remarks, classTeacher] = await Promise.all([
        getStudentAttendanceSummary(user.id),
        getStudentRemarks(user.id),
        getStudentClassTeacher(raw.STVCLAS_standard, raw.STVCLAS_class),
      ]);

      const profile = {
        id:          raw.id,
        studentId:   raw.SPRIDEN_student_id,
        grNumber:    raw.spriden_gr_number,
        surname:     raw.SPRIDEN_surname,
        studentName: raw.SPRIDEN_student_name,
        fatherName:  raw.SPRIDEN_father_name,
        motherName:  raw.SPRIDEN_mother_name,
        gender:      raw.SPRIDEN_gender,
        birthdate:   raw.SPRIDEN_birthdate,
        address:     raw.SPRIDEN_main_address,
        standard:    raw.STVCLAS_standard,
        division:    raw.STVCLAS_class,
        rollNo:      raw.STVCLAS_rollno,
        academicYear: raw.stvacdm_year || user.academicYear,
        status:      raw.spriden_student_status,
        religion:    raw.SPRIDEN_religion,
        bloodGroup:  raw.SPRIDEN_BGRP,
        aadharNo:    raw.SPRIDEN_aadhar_no,
      };

      return res.status(200).json({
        success: true,
        role: 'student',
        data: {
          profile,
          attendance,
          remarks,
          classTeacher,
        },
      });
    }

    return failure(res, 'Invalid role.', 400);
  } catch (err) {
    console.error('[getDashboardStats error]', err);
    return failure(res, 'Failed to retrieve dashboard data.', 500);
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// TASK MANAGER CONTROLLER METHODS (CI3: Task.php)
// ─────────────────────────────────────────────────────────────────────────────

export async function getTasks(req, res) {
  try {
    const { status, title } = req.query;
    const tasks = await getAllTasks(status, title);
    return res.status(200).json({ success: true, tasks });
  } catch (err) {
    console.error('[getTasks error]', err);
    return failure(res, 'Failed to fetch tasks.', 500);
  }
}

export async function createTask(req, res) {
  try {
    const { title, start_date, reminder_date, status } = req.body;
    if (!title || !title.trim()) {
      return failure(res, 'Task title is required.', 400);
    }

    const taskId = await insertTask({
      title: title.trim(),
      start_date,
      reminder_date,
      status: status || 'pending',
    });

    return res.status(201).json({
      success: true,
      message: 'Task created successfully.',
      task_id: taskId,
    });
  } catch (err) {
    console.error('[createTask error]', err);
    return failure(res, 'Failed to create task.', 500);
  }
}

export async function updateTaskDetails(req, res) {
  try {
    const { id } = req.params;
    const { title, start_date, reminder_date } = req.body;

    const ok = await updateTask(id, { title, start_date, reminder_date });
    if (!ok) return failure(res, 'Task not found or not modified.', 404);

    return res.status(200).json({ success: true, message: 'Task updated successfully.' });
  } catch (err) {
    console.error('[updateTask error]', err);
    return failure(res, 'Failed to update task.', 500);
  }
}

export async function updateTaskStatus(req, res) {
  try {
    const { id } = req.params;
    const { status } = req.body;

    if (!status) return failure(res, 'Status is required.', 400);

    const ok = await modelUpdateTaskStatus(id, status);
    if (!ok) return failure(res, 'Task not found.', 404);

    return res.status(200).json({ success: true, message: 'Task status updated.' });
  } catch (err) {
    console.error('[updateTaskStatus error]', err);
    return failure(res, 'Failed to update task status.', 500);
  }
}

export async function deleteTask(req, res) {
  try {
    const { id } = req.params;
    const ok = await modelDeleteTask(id);
    if (!ok) return failure(res, 'Task not found.', 404);

    return res.status(200).json({ success: true, message: 'Task deleted successfully.' });
  } catch (err) {
    console.error('[deleteTask error]', err);
    return failure(res, 'Failed to delete task.', 500);
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// INSTANT STUDENT FEE SEARCH (CI3: Student::fetchReadmissionDetails)
// ─────────────────────────────────────────────────────────────────────────────

export async function searchStudentFees(req, res) {
  try {
    const studentId = req.query['student-id'] || req.query.studentId || req.query.q;
    if (!studentId) {
      return failure(res, 'Student ID query parameter required.', 400);
    }

    const details = await fetchStudentFeeDetails(studentId);
    if (!details) {
      return failure(res, 'Student not found.', 404);
    }

    return res.status(200).json({ success: true, data: details });
  } catch (err) {
    console.error('[searchStudentFees error]', err);
    return failure(res, 'Error searching student fees.', 500);
  }
}

/**
 * CI3: Welcome::setAcademicYear()
 */
export async function setAcademicYear(req, res) {
  try {
    const { academic_year } = req.body;
    if (!academic_year) {
      return res.status(400).json({ status: false, message: 'Academic year missing' });
    }
    return res.status(200).json({ status: true, message: 'Academic year updated' });
  } catch (err) {
    console.error('[setAcademicYear error]', err);
    return failure(res, 'Error setting academic year.', 500);
  }
}

