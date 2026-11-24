/**
 * App root — route definitions with strict separation of Login portals.
 *
 * Route Mapping:
 *   Admin Login:    /login, /admin/login, /admin_login    → AdminLoginPage
 *   Teacher Login:  /teacher_login, /teacher-login        → TeacherLoginPage
 *   Student Login:  /student_login, /student-login        → StudentLoginPage
 *   Dashboard:      /dashboard, /profile                  → DashboardPage (Protected)
 *   Students:       /students, /admission-form, /students/promotion, etc.
 *   Root:           /                                     → /dashboard (or /login)
 */
import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import AdminLoginPage from './pages/AdminLoginPage.jsx';
import TeacherLoginPage from './pages/TeacherLoginPage.jsx';
import StudentLoginPage from './pages/StudentLoginPage.jsx';
import DashboardPage from './pages/DashboardPage.jsx';
import StudentListPage from './pages/students/StudentListPage.jsx';
import StudentAdmissionPage from './pages/students/StudentAdmissionPage.jsx';
import StudentDetailPage from './pages/students/StudentDetailPage.jsx';
import StudentPromotionPage from './pages/students/StudentPromotionPage.jsx';
import StudentReadmissionPage from './pages/students/StudentReadmissionPage.jsx';
import StudentLcPage from './pages/students/StudentLcPage.jsx';
import FeesCollectionPage from './pages/fees/FeesCollectionPage.jsx';
import FeesReportPage from './pages/fees/FeesReportPage.jsx';
import FeeStructurePage from './pages/fees/FeeStructurePage.jsx';
import FeesReconciliationPage from './pages/fees/FeesReconciliationPage.jsx';
import StudentAttendancePage from './pages/attendance/StudentAttendancePage.jsx';
import AbsentStudentPage from './pages/attendance/AbsentStudentPage.jsx';
import TeacherListPage from './pages/teachers/TeacherListPage.jsx';
import TeacherRegistrationPage from './pages/teachers/TeacherRegistrationPage.jsx';
import TeacherAssignmentPage from './pages/teachers/TeacherAssignmentPage.jsx';
import TimetablePage from './pages/teachers/TimetablePage.jsx';
import LessonPlanPage from './pages/teachers/LessonPlanPage.jsx';
import MarksEntryPage from './pages/exams/MarksEntryPage.jsx';
import ExamMasterPage from './pages/exams/ExamMasterPage.jsx';
import HallTicketPage from './pages/exams/HallTicketPage.jsx';
import ReportCardPage from './pages/exams/ReportCardPage.jsx';
import AcademicYearMasterPage from './pages/master/AcademicYearMasterPage.jsx';
import SubjectMasterPage from './pages/master/SubjectMasterPage.jsx';
import SchoolInfoPage from './pages/master/SchoolInfoPage.jsx';
import UserAccessPage from './pages/master/UserAccessPage.jsx';
import ModulesManagementPage from './pages/master/ModulesManagementPage.jsx';
import FormsManagementPage from './pages/master/FormsManagementPage.jsx';
import UserAccessReportPage from './pages/master/UserAccessReportPage.jsx';
import MasterAuxiliaryPage from './pages/master/MasterAuxiliaryPage.jsx';
import WebAppSettingsPage from './pages/master/WebAppSettingsPage.jsx';
import TransportationPage from './pages/transport/TransportationPage.jsx';
import TemplatesPage from './pages/communication/TemplatesPage.jsx';
import EventsCalendarPage from './pages/communication/EventsCalendarPage.jsx';
import ReportsCenterPage from './pages/reports/ReportsCenterPage.jsx';
import OnlineApplicationsPage from './pages/applications/OnlineApplicationsPage.jsx';
import NotesManagementPage from './pages/teachers/NotesManagementPage.jsx';
import ParentsMeetingPage from './pages/communication/ParentsMeetingPage.jsx';
import TasksManagementPage from './pages/tasks/TasksManagementPage.jsx';
import ChangePasswordPage from './pages/profile/ChangePasswordPage.jsx';
import VisitingManagementPage from './pages/fees/VisitingManagementPage.jsx';
import BooksResultPage from './pages/students/BooksResultPage.jsx';
import FeesAlertBroadcastPage from './pages/communication/FeesAlertBroadcastPage.jsx';
import FeesExecutiveDashboardPage from './pages/fees/FeesExecutiveDashboardPage.jsx';
import LessonApprovalPage from './pages/teachers/LessonApprovalPage.jsx';
import StudentProfilePage from './pages/students/StudentProfilePage.jsx';
import AadharManagementPage from './pages/students/AadharManagementPage.jsx';
import PublicAadharKycPage from './pages/PublicAadharKycPage.jsx';
import PublicJuniorCollegeFormPage from './pages/applications/PublicJuniorCollegeFormPage.jsx';
import TeacherCallingPage from './pages/students/TeacherCallingPage.jsx';
import RandomClassAllocationPage from './pages/teachers/RandomClassAllocationPage.jsx';
import ClassAnalyticsPage from './pages/exams/ClassAnalyticsPage.jsx';
import QuizReviewPage from './pages/exams/QuizReviewPage.jsx';
import ClassManagementPage from './pages/teachers/ClassManagementPage.jsx';
import StudentICardPage from './pages/students/StudentICardPage.jsx';
import StudentTailorPage from './pages/students/StudentTailorPage.jsx';
import StudentRemarksPage from './pages/students/StudentRemarksPage.jsx';
import TeacherActivityPage from './pages/reports/TeacherActivityPage.jsx';
import ProtectedRoute from './components/ProtectedRoute.jsx';
import useAuthStore from './store/useAuthStore.js';

export default function App() {
  const { isAuthenticated, user } = useAuthStore();

  return (
    <Routes>
      {/* ─── Public Unauthenticated Portals (CI3 SMS/WhatsApp links & Online Forms) ─── */}
      <Route path="/public/student/aadhar_info" element={<PublicAadharKycPage />} />
      <Route path="/public/student/aadhar-info" element={<PublicAadharKycPage />} />
      <Route path="/student/aadhar-info" element={<PublicAadharKycPage />} />
      <Route path="/public/aadhar-info" element={<PublicAadharKycPage />} />
      <Route path="/forms/jr_college" element={<PublicJuniorCollegeFormPage />} />
      <Route path="/forms/jr-college" element={<PublicJuniorCollegeFormPage />} />
      <Route path="/admission/junior-college" element={<PublicJuniorCollegeFormPage />} />
      <Route path="/admission/jr-college" element={<PublicJuniorCollegeFormPage />} />

      {/* ─── 1. Separate Login Routes ─── */}
      {/* Admin Login */}
      <Route path="/login" element={<AdminLoginPage />} />
      <Route path="/admin/login" element={<AdminLoginPage />} />
      <Route path="/admin_login" element={<AdminLoginPage />} />

      {/* Teacher Login */}
      <Route path="/teacher_login" element={<TeacherLoginPage />} />
      <Route path="/teacher-login" element={<TeacherLoginPage />} />

      {/* Student Login */}
      <Route path="/student_login" element={<StudentLoginPage />} />
      <Route path="/student-login" element={<StudentLoginPage />} />


      {/* ─── 2. Protected Dashboard & Profile Routes ─── */}
      <Route
        path="/dashboard"
        element={
          <ProtectedRoute>
            <DashboardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/profile"
        element={
          <ProtectedRoute>
            <StudentProfilePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/profile/:studentId"
        element={
          <ProtectedRoute>
            <StudentProfilePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/student-profile"
        element={
          <ProtectedRoute>
            <StudentProfilePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/student/profile"
        element={
          <ProtectedRoute>
            <StudentProfilePage />
          </ProtectedRoute>
        }
      />

      {/* ─── Student & Parent Aadhaar KYC Management (CI3: Aadhar-Report / Welcome::get_aadhar_data) ─── */}
      <Route
        path="/aadhar-report"
        element={
          <ProtectedRoute>
            <AadharManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Aadhar-Report"
        element={
          <ProtectedRoute>
            <AadharManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/student-aadhar"
        element={
          <ProtectedRoute>
            <AadharManagementPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 3. Student Module Routes ─── */}
      <Route
        path="/students"
        element={
          <ProtectedRoute>
            <StudentListPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/student/student_list"
        element={
          <ProtectedRoute>
            <StudentListPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/students/admission"
        element={
          <ProtectedRoute>
            <StudentAdmissionPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/admission-form"
        element={
          <ProtectedRoute>
            <StudentAdmissionPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/students/:id"
        element={
          <ProtectedRoute>
            <StudentDetailPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/students/promotion"
        element={
          <ProtectedRoute>
            <StudentPromotionPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/student/promotion"
        element={
          <ProtectedRoute>
            <StudentPromotionPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/students/readmission"
        element={
          <ProtectedRoute>
            <StudentReadmissionPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/readmission"
        element={
          <ProtectedRoute>
            <StudentReadmissionPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/students/lc"
        element={
          <ProtectedRoute>
            <StudentLcPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 4. Fees Module Routes ─── */}
      <Route
        path="/fees"
        element={
          <ProtectedRoute>
            <FeesCollectionPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/fees-report"
        element={
          <ProtectedRoute>
            <FeesReportPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/fees/structure"
        element={
          <ProtectedRoute>
            <FeeStructurePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/manage-fees/transactions"
        element={
          <ProtectedRoute>
            <FeesReconciliationPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/fees/reconciliation"
        element={
          <ProtectedRoute>
            <FeesReconciliationPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/fees/bank-upload"
        element={
          <ProtectedRoute>
            <FeesReconciliationPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/fees-bank-upload"
        element={
          <ProtectedRoute>
            <FeesReconciliationPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 5. Attendance Module Routes ─── */}
      <Route
        path="/attendance"
        element={
          <ProtectedRoute>
            <StudentAttendancePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/student_attendance"
        element={
          <ProtectedRoute>
            <StudentAttendancePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/attendance/absentee"
        element={
          <ProtectedRoute>
            <AbsentStudentPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 6. Teacher & Staff Module Routes ─── */}
      <Route
        path="/teachers"
        element={
          <ProtectedRoute>
            <TeacherListPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/teachers/create"
        element={
          <ProtectedRoute>
            <TeacherRegistrationPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/teacher-account"
        element={
          <ProtectedRoute>
            <TeacherRegistrationPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/teachers/assign-class"
        element={
          <ProtectedRoute>
            <TeacherAssignmentPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/assign-teacher"
        element={
          <ProtectedRoute>
            <TeacherAssignmentPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/teachers/timetable"
        element={
          <ProtectedRoute>
            <TimetablePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/create-time"
        element={
          <ProtectedRoute>
            <TimetablePage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/teachers/lessons"
        element={
          <ProtectedRoute>
            <LessonPlanPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/lesson"
        element={
          <ProtectedRoute>
            <LessonPlanPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 7. Examination & Academic Evaluation Routes ─── */}
      <Route
        path="/exams/marks"
        element={
          <ProtectedRoute>
            <MarksEntryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/marks"
        element={
          <ProtectedRoute>
            <MarksEntryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/student_marks"
        element={
          <ProtectedRoute>
            <MarksEntryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/exams/setup"
        element={
          <ProtectedRoute>
            <ExamMasterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/exam-master"
        element={
          <ProtectedRoute>
            <ExamMasterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/examsetup"
        element={
          <ProtectedRoute>
            <ExamMasterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/exams/hall-tickets"
        element={
          <ProtectedRoute>
            <HallTicketPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/hall_ticket"
        element={
          <ProtectedRoute>
            <HallTicketPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/hall-ticket"
        element={
          <ProtectedRoute>
            <HallTicketPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/exams/report-card"
        element={
          <ProtectedRoute>
            <ReportCardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/report_card"
        element={
          <ProtectedRoute>
            <ReportCardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/report-card"
        element={
          <ProtectedRoute>
            <ReportCardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/result"
        element={
          <ProtectedRoute>
            <ReportCardPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 8. Master Forms & Configurations Routes ─── */}
      <Route
        path="/master/academic-years"
        element={
          <ProtectedRoute>
            <AcademicYearMasterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvacdm"
        element={
          <ProtectedRoute>
            <AcademicYearMasterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/subjects"
        element={
          <ProtectedRoute>
            <SubjectMasterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvsubj"
        element={
          <ProtectedRoute>
            <SubjectMasterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/school-profile"
        element={
          <ProtectedRoute>
            <SchoolInfoPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvpsch"
        element={
          <ProtectedRoute>
            <SchoolInfoPage />
          </ProtectedRoute>
        }
      />
      {/* Module Management (CI3: Welcome/mode, modules.php) */}
      <Route
        path="/master/modules"
        element={
          <ProtectedRoute>
            <ModulesManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/modules"
        element={
          <ProtectedRoute>
            <ModulesManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/mode"
        element={
          <ProtectedRoute>
            <ModulesManagementPage />
          </ProtectedRoute>
        }
      />

      {/* Forms Management (CI3: Welcome/forms, forms.php) */}
      <Route
        path="/master/forms"
        element={
          <ProtectedRoute>
            <FormsManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/forms"
        element={
          <ProtectedRoute>
            <FormsManagementPage />
          </ProtectedRoute>
        }
      />

      {/* User Access & Form Permissions (CI3: Welcome/user, user_access.php) */}
      <Route
        path="/master/user-access"
        element={
          <ProtectedRoute>
            <UserAccessPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/user-access"
        element={
          <ProtectedRoute>
            <UserAccessPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/user_access"
        element={
          <ProtectedRoute>
            <UserAccessPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/update_access/:userId"
        element={
          <ProtectedRoute>
            <UserAccessPage />
          </ProtectedRoute>
        }
      />

      {/* User Access Reports (CI3: Welcome/user_report & access_report_list) */}
      <Route
        path="/master/user-reports"
        element={
          <ProtectedRoute>
            <UserAccessReportPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/user-report"
        element={
          <ProtectedRoute>
            <UserAccessReportPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/user-reports"
        element={
          <ProtectedRoute>
            <UserAccessReportPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/user_report"
        element={
          <ProtectedRoute>
            <UserAccessReportPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/user-report"
        element={
          <ProtectedRoute>
            <UserAccessReportPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/access_report_list"
        element={
          <ProtectedRoute>
            <UserAccessReportPage />
          </ProtectedRoute>
        }
      />

      {/* Auxiliary Masters (CI3: stvshif, stvposn, stvgrds, stvstts, stvcity, stvhold, stvuniv, stvmajr) */}
      <Route
        path="/master/auxiliary"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/shifts"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvshif"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/positions"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvposn"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/grades"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvgrds"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/states"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvstts"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/cities"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvcity"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/hold-reasons"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvhold"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/universities"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvuniv"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/majors"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/stvmajr"
        element={
          <ProtectedRoute>
            <MasterAuxiliaryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/master/visibility"
        element={
          <ProtectedRoute>
            <WebAppSettingsPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/setup/web_app_visibility"
        element={
          <ProtectedRoute>
            <WebAppSettingsPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 9. Transportation Module Routes ─── */}
      <Route
        path="/transportation"
        element={
          <ProtectedRoute>
            <TransportationPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/driver_master"
        element={
          <ProtectedRoute>
            <TransportationPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/vehicle_master"
        element={
          <ProtectedRoute>
            <TransportationPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 10. Communication & Events Routes ─── */}
      <Route
        path="/communication/templates"
        element={
          <ProtectedRoute>
            <TemplatesPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/msgtemplate"
        element={
          <ProtectedRoute>
            <TemplatesPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Fee-massage"
        element={
          <ProtectedRoute>
            <TemplatesPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/communication/events"
        element={
          <ProtectedRoute>
            <EventsCalendarPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/events"
        element={
          <ProtectedRoute>
            <EventsCalendarPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 11. Reports & Analytics Routes (CI3: Report.php / Modules 8, 18, 21) ─── */}
      <Route
        path="/reports"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reports/students"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reports/fees"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reports/attendance"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reports/certificates"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reports/activity"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reports/audit"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />

      {/* CI3 Legacy Report URL Aliases */}
      <Route
        path="/all_reports"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/report"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/report/reports"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/balance_fees"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/daily_fees_report"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Absent_student"
        element={
          <ProtectedRoute>
            <AbsentStudentPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/absent_student"
        element={
          <ProtectedRoute>
            <AbsentStudentPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/absent-student"
        element={
          <ProtectedRoute>
            <AbsentStudentPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reports/absent_student"
        element={
          <ProtectedRoute>
            <AbsentStudentPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Aadhar-Report"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Notes-Report"
        element={
          <ProtectedRoute>
            <ReportsCenterPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 12. Online Admission Applications (CI3: Online_Application.php / Module 10) ─── */}
      <Route
        path="/online-applications"
        element={
          <ProtectedRoute>
            <OnlineApplicationsPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/online_application"
        element={
          <ProtectedRoute>
            <OnlineApplicationsPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/junior_clg"
        element={
          <ProtectedRoute>
            <OnlineApplicationsPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 13. Academic Notes & Study Materials (CI3: Generate-Notes.php) ─── */}
      <Route
        path="/notes"
        element={
          <ProtectedRoute>
            <NotesManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/notes-report"
        element={
          <ProtectedRoute>
            <NotesManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Generate-Notes"
        element={
          <ProtectedRoute>
            <NotesManagementPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 14. Parent-Teacher Meetings (CI3: Generate-Meeting.php / Module 17) ─── */}
      <Route
        path="/meetings"
        element={
          <ProtectedRoute>
            <ParentsMeetingPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/generate-meeting"
        element={
          <ProtectedRoute>
            <ParentsMeetingPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Generate-Meeting"
        element={
          <ProtectedRoute>
            <ParentsMeetingPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Welcome/generate_meeting"
        element={
          <ProtectedRoute>
            <ParentsMeetingPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/welcome/generate_meeting"
        element={
          <ProtectedRoute>
            <ParentsMeetingPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Welcome/meeting"
        element={
          <ProtectedRoute>
            <ParentsMeetingPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/welcome/meeting"
        element={
          <ProtectedRoute>
            <ParentsMeetingPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 15. Tasks & Operations (CI3: Task.php) ─── */}
      <Route
        path="/tasks"
        element={
          <ProtectedRoute>
            <TasksManagementPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 16. Change Password (CI3: Welcome/change_password) ─── */}
      <Route
        path="/change-password"
        element={
          <ProtectedRoute>
            <ChangePasswordPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/welcome/change_password"
        element={
          <ProtectedRoute>
            <ChangePasswordPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 17. Visiting & Office Counter Records (CI3: Welcome/visiting) ─── */}
      <Route
        path="/visiting"
        element={
          <ProtectedRoute>
            <VisitingManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/visiting-counter"
        element={
          <ProtectedRoute>
            <VisitingManagementPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/welcome/visiting"
        element={
          <ProtectedRoute>
            <VisitingManagementPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 18. Books & Results Distribution (CI3: Welcome/booksandresult) ─── */}
      <Route
        path="/books-and-result"
        element={
          <ProtectedRoute>
            <BooksResultPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/books-distribution"
        element={
          <ProtectedRoute>
            <BooksResultPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/welcome/booksandresult"
        element={
          <ProtectedRoute>
            <BooksResultPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 19. Fees Reminder & Admission Alert (CI3: Fee-massage, FeesAlert.php) ─── */}
      <Route
        path="/fees-alert"
        element={
          <ProtectedRoute>
            <FeesAlertBroadcastPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Fee-massage"
        element={
          <ProtectedRoute>
            <FeesAlertBroadcastPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/fees-massage"
        element={
          <ProtectedRoute>
            <FeesAlertBroadcastPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/admission-confirmation"
        element={
          <ProtectedRoute>
            <FeesAlertBroadcastPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/FeesAlert"
        element={
          <ProtectedRoute>
            <FeesAlertBroadcastPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 20. Fees Executive Visual Dashboard (CI3: FeesDashboard.php / DB_model.php) ─── */}
      <Route
        path="/fees/dashboard"
        element={
          <ProtectedRoute>
            <FeesExecutiveDashboardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/fees-dashboard"
        element={
          <ProtectedRoute>
            <FeesExecutiveDashboardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Fees_db"
        element={
          <ProtectedRoute>
            <FeesExecutiveDashboardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/FeesDashboard"
        element={
          <ProtectedRoute>
            <FeesExecutiveDashboardPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 21. Lesson Approval Workflow (CI3: Lesson_approval.php / Approval_model.php) ─── */}
      <Route
        path="/teachers/lesson-approval"
        element={
          <ProtectedRoute>
            <LessonApprovalPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/lesson-approval"
        element={
          <ProtectedRoute>
            <LessonApprovalPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/lesson_approval"
        element={
          <ProtectedRoute>
            <LessonApprovalPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/Lesson_approval"
        element={
          <ProtectedRoute>
            <LessonApprovalPage />
          </ProtectedRoute>
        }
      />

      {/* ─── 23. Exact CI3 Form Link Legacy Aliases (Covers all 141 form registry entries) ─── */}
      {/* Online Applications */}
      <Route path="/Online_Application/list" element={<ProtectedRoute><OnlineApplicationsPage /></ProtectedRoute>} />
      <Route path="/online_application/list" element={<ProtectedRoute><OnlineApplicationsPage /></ProtectedRoute>} />
      <Route path="/Online_Application/junior_clg" element={<ProtectedRoute><OnlineApplicationsPage /></ProtectedRoute>} />
      <Route path="/online_application/junior_clg" element={<ProtectedRoute><OnlineApplicationsPage /></ProtectedRoute>} />

      {/* Tracking & Teacher Activity */}
      <Route path="/Report/login_tracking" element={<ProtectedRoute><TeacherActivityPage initialTab="login" /></ProtectedRoute>} />
      <Route path="/report/login_tracking" element={<ProtectedRoute><TeacherActivityPage initialTab="login" /></ProtectedRoute>} />
      <Route path="/reports/teacher-activity" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/stats/activity_report" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/Stats/activity_report" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/Report/teacher_activity" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/report/teacher_activity" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/Report/teacher_activity_db" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/report/teacher_activity_db" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/report/teacher_activity_report" element={<ProtectedRoute><TeacherActivityPage /></ProtectedRoute>} />
      <Route path="/Report/syllabus" element={<ProtectedRoute><LessonPlanPage /></ProtectedRoute>} />
      <Route path="/report/syllabus" element={<ProtectedRoute><LessonPlanPage /></ProtectedRoute>} />
      <Route path="/Report/syllabus_calender" element={<ProtectedRoute><TimetablePage /></ProtectedRoute>} />
      <Route path="/report/syllabus_calender" element={<ProtectedRoute><TimetablePage /></ProtectedRoute>} />

      {/* Section (Classes, Teacher Assignment, Division, Cards, Hall Tickets) */}
      <Route path="/teachers/class-management" element={<ProtectedRoute><ClassManagementPage /></ProtectedRoute>} />
      <Route path="/subject-teacher" element={<ProtectedRoute><ClassManagementPage /></ProtectedRoute>} />
      <Route path="/create-class" element={<ProtectedRoute><ClassManagementPage /></ProtectedRoute>} />
      <Route path="/class" element={<ProtectedRoute><ClassManagementPage /></ProtectedRoute>} />
      
      {/* ─── Teacher Calling & Fee Recovery Management (CI3: TCM / schoolm) ─── */}
      <Route path="/students/calling-management" element={<ProtectedRoute><TeacherCallingPage /></ProtectedRoute>} />
      <Route path="/tcm" element={<ProtectedRoute><TeacherCallingPage /></ProtectedRoute>} />
      <Route path="/TCM" element={<ProtectedRoute><TeacherCallingPage /></ProtectedRoute>} />
      <Route path="/student/schoolm" element={<ProtectedRoute><TeacherCallingPage /></ProtectedRoute>} />
      <Route path="/Student/schoolm" element={<ProtectedRoute><TeacherCallingPage /></ProtectedRoute>} />

      {/* ─── Random Class Allocation (CI3: RandomClass/assign_class) ─── */}
      <Route path="/teachers/random-allocation" element={<ProtectedRoute><RandomClassAllocationPage /></ProtectedRoute>} />
      <Route path="/randomclass/assign_class" element={<ProtectedRoute><RandomClassAllocationPage /></ProtectedRoute>} />
      <Route path="/RandomClass/assign_class" element={<ProtectedRoute><RandomClassAllocationPage /></ProtectedRoute>} />
      <Route path="/random-class" element={<ProtectedRoute><RandomClassAllocationPage /></ProtectedRoute>} />

      {/* ─── Class & Student Exam Performance Analytics (CI3: Class_DB & Result_Db) ─── */}
      <Route path="/exams/class-analytics" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/classresult" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/classresult/classresult" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/Class_DB" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/class_db" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/studresult" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/StudResult" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/result_db" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />
      <Route path="/Result_db" element={<ProtectedRoute><ClassAnalyticsPage /></ProtectedRoute>} />

      {/* ─── Online Quiz Results & Assessment Review (CI3: Result/quiz_result) ─── */}
      <Route path="/exams/quiz-results" element={<ProtectedRoute><QuizReviewPage /></ProtectedRoute>} />
      <Route path="/quiz-result" element={<ProtectedRoute><QuizReviewPage /></ProtectedRoute>} />
      <Route path="/quiz_result" element={<ProtectedRoute><QuizReviewPage /></ProtectedRoute>} />
      <Route path="/quiz-results" element={<ProtectedRoute><QuizReviewPage /></ProtectedRoute>} />
      <Route path="/Result/quiz_result" element={<ProtectedRoute><QuizReviewPage /></ProtectedRoute>} />
      <Route path="/result/quiz_result" element={<ProtectedRoute><QuizReviewPage /></ProtectedRoute>} />
      <Route path="/promote" element={<ProtectedRoute><StudentPromotionPage /></ProtectedRoute>} />
      <Route path="/students/icard" element={<ProtectedRoute><StudentICardPage /></ProtectedRoute>} />
      <Route path="/student/i_card" element={<ProtectedRoute><StudentICardPage /></ProtectedRoute>} />
      <Route path="/student/student_card" element={<ProtectedRoute><StudentICardPage /></ProtectedRoute>} />
      <Route path="/hall_ticket/fetchreport" element={<ProtectedRoute><HallTicketPage /></ProtectedRoute>} />
      <Route path="/hall_ticket/report" element={<ProtectedRoute><HallTicketPage /></ProtectedRoute>} />
      <Route path="/Transportation/Driver_master" element={<ProtectedRoute><TransportationPage /></ProtectedRoute>} />
      <Route path="/Transportation/Vehicle_master" element={<ProtectedRoute><TransportationPage /></ProtectedRoute>} />
      <Route path="/Transportation/student_details" element={<ProtectedRoute><TransportationPage /></ProtectedRoute>} />
      <Route path="/create-timetable" element={<ProtectedRoute><TimetablePage /></ProtectedRoute>} />
      <Route path="/view-timetable" element={<ProtectedRoute><TimetablePage /></ProtectedRoute>} />
      <Route path="/Marks/Subject_master" element={<ProtectedRoute><SubjectMasterPage /></ProtectedRoute>} />
      <Route path="/marks/subject_master" element={<ProtectedRoute><SubjectMasterPage /></ProtectedRoute>} />
      <Route path="/Marks/Exam_master" element={<ProtectedRoute><ExamMasterPage /></ProtectedRoute>} />
      <Route path="/marks/exam_master" element={<ProtectedRoute><ExamMasterPage /></ProtectedRoute>} />
      <Route path="/Marks/Examsetup" element={<ProtectedRoute><ExamMasterPage /></ProtectedRoute>} />
      <Route path="/marks/examsetup" element={<ProtectedRoute><ExamMasterPage /></ProtectedRoute>} />
      <Route path="/Marks/Student_marks" element={<ProtectedRoute><MarksEntryPage /></ProtectedRoute>} />
      <Route path="/marks/student_marks" element={<ProtectedRoute><MarksEntryPage /></ProtectedRoute>} />
      <Route path="/Marks/marks_report" element={<ProtectedRoute><ReportCardPage /></ProtectedRoute>} />
      <Route path="/marks/marks_report" element={<ProtectedRoute><ReportCardPage /></ProtectedRoute>} />

      {/* Auxiliary & Master Forms */}
      <Route path="/stvexam" element={<ProtectedRoute><ExamMasterPage /></ProtectedRoute>} />
      <Route path="/stvfees" element={<ProtectedRoute><FeeStructurePage /></ProtectedRoute>} />
      <Route path="/stvcolg" element={<ProtectedRoute><MasterAuxiliaryPage /></ProtectedRoute>} />
      <Route path="/stvexpr" element={<ProtectedRoute><MasterAuxiliaryPage /></ProtectedRoute>} />
      <Route path="/stvlmst" element={<ProtectedRoute><MasterAuxiliaryPage /></ProtectedRoute>} />
      <Route path="/sprhold" element={<ProtectedRoute><MasterAuxiliaryPage /></ProtectedRoute>} />
      <Route path="/stvpcnf" element={<ProtectedRoute><MasterAuxiliaryPage /></ProtectedRoute>} />
      <Route path="/msgtemplate/index" element={<ProtectedRoute><TemplatesPage /></ProtectedRoute>} />
      <Route path="/msgtemplate" element={<ProtectedRoute><TemplatesPage /></ProtectedRoute>} />
      <Route path="/stvldrm" element={<ProtectedRoute><TransportationPage /></ProtectedRoute>} />
      <Route path="/stvlvm" element={<ProtectedRoute><TransportationPage /></ProtectedRoute>} />
      <Route path="/MasterForm/studentStdDiv" element={<ProtectedRoute><MasterAuxiliaryPage initialTab="update-std-div" /></ProtectedRoute>} />
      <Route path="/masterform/studentstddiv" element={<ProtectedRoute><MasterAuxiliaryPage initialTab="update-std-div" /></ProtectedRoute>} />
      <Route path="/studentStdDiv" element={<ProtectedRoute><MasterAuxiliaryPage initialTab="update-std-div" /></ProtectedRoute>} />

      {/* Account Management & Security */}
      <Route path="/Welcome/mode" element={<ProtectedRoute><ModulesManagementPage /></ProtectedRoute>} />
      <Route path="/welcome/mode" element={<ProtectedRoute><ModulesManagementPage /></ProtectedRoute>} />
      <Route path="/Welcome/forms" element={<ProtectedRoute><FormsManagementPage /></ProtectedRoute>} />
      <Route path="/welcome/forms" element={<ProtectedRoute><FormsManagementPage /></ProtectedRoute>} />
      <Route path="/Welcome/user" element={<ProtectedRoute><UserAccessPage /></ProtectedRoute>} />
      <Route path="/welcome/user" element={<ProtectedRoute><UserAccessPage /></ProtectedRoute>} />
      <Route path="/Welcome/user_report" element={<ProtectedRoute><UserAccessReportPage /></ProtectedRoute>} />
      <Route path="/welcome/user_report" element={<ProtectedRoute><UserAccessReportPage /></ProtectedRoute>} />
      <Route path="/stvfacs" element={<ProtectedRoute><UserAccessPage /></ProtectedRoute>} />
      <Route path="/stvusrs" element={<ProtectedRoute><UserAccessPage /></ProtectedRoute>} />
      <Route path="/stvlogh" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/stvfrmh" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />

      {/* Reports (Module 18 & Module 8) */}
      <Route path="/reports/students/Addmission-report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/addmission-report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/New-addmission" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/new-addmission" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/Readdmission-report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/readdmission-report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/admission-summary-report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/books-report" element={<ProtectedRoute><BooksResultPage /></ProtectedRoute>} />
      <Route path="/reports/students/balance-student" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/Rte-report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/rte-report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/all-students" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/reports/students/Fees-report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/reports/students/fees-report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/reports/students/daily-fees-report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/reports/students/datewise-fees-report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/reports/students/fees-installment-report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/reports/students/Balance-report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/reports/students/balance-report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/classReport" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/classreport" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/all_previous_fees_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/Report/last_year_fees_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/last_year_fees_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/Report/current_zero_paid_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/current_zero_paid_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/fees_date_wise" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/fees_instment_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/fees_alert" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/reports" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/rte_report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/admissionreport" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/admission_summary" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/admission_report" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/readmission" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/balance_rep" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/current_inactive" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/Report/lc_report" element={<ProtectedRoute><StudentLcPage /></ProtectedRoute>} />
      <Route path="/report/lc_report" element={<ProtectedRoute><StudentLcPage /></ProtectedRoute>} />
      <Route path="/report/monthly_fees" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/exam_alert" element={<ProtectedRoute><ReportsCenterPage /></ProtectedRoute>} />
      <Route path="/report/current_year_fees" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />

      {/* Student, Tailor, Books, LC, Attendance */}
      <Route path="/students/remarks" element={<ProtectedRoute><StudentRemarksPage /></ProtectedRoute>} />
      <Route path="/remark" element={<ProtectedRoute><StudentRemarksPage /></ProtectedRoute>} />
      <Route path="/student/remarks" element={<ProtectedRoute><StudentRemarksPage /></ProtectedRoute>} />
      <Route path="/student/student_comments" element={<ProtectedRoute><StudentRemarksPage /></ProtectedRoute>} />
      <Route path="/students/tailor" element={<ProtectedRoute><StudentTailorPage /></ProtectedRoute>} />
      <Route path="/student/tailor_details" element={<ProtectedRoute><StudentTailorPage /></ProtectedRoute>} />
      <Route path="/student/books" element={<ProtectedRoute><BooksResultPage /></ProtectedRoute>} />
      <Route path="/student/books_records" element={<ProtectedRoute><BooksResultPage /></ProtectedRoute>} />
      <Route path="/report/report_stats_lc" element={<ProtectedRoute><StudentLcPage /></ProtectedRoute>} />
      <Route path="/report/report_stats_bf" element={<ProtectedRoute><StudentLcPage /></ProtectedRoute>} />
      <Route path="/report/lc_isshu_report" element={<ProtectedRoute><StudentLcPage /></ProtectedRoute>} />
      <Route path="/report/Student_atnd" element={<ProtectedRoute><StudentAttendancePage /></ProtectedRoute>} />
      <Route path="/report/student_atnd" element={<ProtectedRoute><StudentAttendancePage /></ProtectedRoute>} />
      <Route path="/FeesAlert/admission_confirmation" element={<ProtectedRoute><FeesAlertBroadcastPage /></ProtectedRoute>} />
      <Route path="/feesalert/admission_confirmation" element={<ProtectedRoute><FeesAlertBroadcastPage /></ProtectedRoute>} />
      <Route path="/Report_card/getReportCardData" element={<ProtectedRoute><ReportCardPage /></ProtectedRoute>} />
      <Route path="/report_card/getreportcarddata" element={<ProtectedRoute><ReportCardPage /></ProtectedRoute>} />
      <Route path="/student-attendance" element={<ProtectedRoute><StudentAttendancePage /></ProtectedRoute>} />
      <Route path="/teacher-attendance" element={<ProtectedRoute><StudentAttendancePage /></ProtectedRoute>} />

      {/* Fees & Transactions */}
      <Route path="/FeesAlert/fees_report" element={<ProtectedRoute><FeesAlertBroadcastPage /></ProtectedRoute>} />
      <Route path="/feesalert/fees_report" element={<ProtectedRoute><FeesAlertBroadcastPage /></ProtectedRoute>} />
      <Route path="/structure" element={<ProtectedRoute><FeeStructurePage /></ProtectedRoute>} />
      <Route path="/student/feesDetails" element={<ProtectedRoute><FeesCollectionPage /></ProtectedRoute>} />
      <Route path="/student/feesdetails" element={<ProtectedRoute><FeesCollectionPage /></ProtectedRoute>} />
      <Route path="/Report/fees_balance_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/fees_balance_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/Report/Fees_comment" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/report/fees_comment" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />
      <Route path="/fees_report" element={<ProtectedRoute><FeesReportPage /></ProtectedRoute>} />

      {/* Teacher & Lessons */}
      <Route path="/teacher/lesson" element={<ProtectedRoute><LessonApprovalPage /></ProtectedRoute>} />
      <Route path="/teachers/lesson" element={<ProtectedRoute><LessonApprovalPage /></ProtectedRoute>} />
      <Route path="/teachers/lesson-management" element={<ProtectedRoute><LessonApprovalPage /></ProtectedRoute>} />
      <Route path="/account-details" element={<ProtectedRoute><TeacherListPage /></ProtectedRoute>} />
      <Route path="/teacher/account" element={<ProtectedRoute><TeacherRegistrationPage /></ProtectedRoute>} />
      <Route path="/teachers/account" element={<ProtectedRoute><TeacherRegistrationPage /></ProtectedRoute>} />
      <Route path="/teacher/account_details" element={<ProtectedRoute><TeacherListPage /></ProtectedRoute>} />
      <Route path="/generate-notes" element={<ProtectedRoute><NotesManagementPage /></ProtectedRoute>} />
      <Route path="/generate_notes" element={<ProtectedRoute><NotesManagementPage /></ProtectedRoute>} />
      <Route path="/Lesson_management" element={<ProtectedRoute><LessonPlanPage /></ProtectedRoute>} />
      <Route path="/lesson_management" element={<ProtectedRoute><LessonPlanPage /></ProtectedRoute>} />

      {/* ─── 22. Root Route — mirrors CI3 default controller check ─── */}
      <Route
        path="/"
        element={
          isAuthenticated ? (
            user?.user_type === 'st' ? (
              <Navigate to="/profile" replace />
            ) : (
              <Navigate to="/dashboard" replace />
            )
          ) : (
            <Navigate to="/login" replace />
          )
        }
      />

      {/* ─── 5. Catch-all fallback ─── */}
      <Route path="*" element={<Navigate to="/login" replace />} />
    </Routes>
  );
}
