<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'Welcome/not_found';
$route['translate_uri_dashes'] = FALSE;
$route['events'] = 'Welcome/events';
//----- student Section ----------------
$route['admission-form'] = 'Welcome/admission_form';
$route['books-and-result'] = 'Welcome/booksandresult';
$route['fees'] = 'Welcome/fees';
$route['fees-report'] = 'Welcome/fees_report';
$route['sprhold'] = 'Welcome/sprhold';
$route['readmission'] = 'student/readmission';
//----- teacher Section ----------------
$route['teacher-account'] = 'Welcome/add_account';
$route['remark'] = 'Welcome/remark';
$route['account-details'] = 'Teacher/account_details';
//------- Class Section ------------------
$route['assign-teacher'] = 'Welcome/assign_teacher';
$route['create-class'] = 'Welcome/create_class';
$route['class'] = 'Welcome/class';
$route['subject-teacher'] = 'Welcome/subject_teacher';
$route['promote'] = 'Welcome/promote';
//------- Attendance Section ------------------
$route['student-attendance'] = 'Welcome/student_attendance';
$route['teacher-attendance'] = 'Welcome/teacher_attendance';
//================= MASTER FORMS ======================================
// ---------------- Academic ---------------------
$route['stvfees'] = 'Welcome/stvfees';
$route['stvsubj'] = 'Welcome/stvsubj';
$route['stvpsch'] = 'Welcome/stvpsch';
$route['stvcolg'] = 'Welcome/stvcolg';
$route['stvhold'] = 'Welcome/stvhold';
$route['stvuniv'] = 'Welcome/stvuniv';
$route['stvmajr'] = 'Welcome/stvmajr';
$route['stvshif'] = 'Welcome/stvshif';
$route['stvexpr'] = 'Welcome/stvexpr';
$route['stvposn'] = 'Welcome/stvposn';
$route['stvgrds'] = 'Welcome/stvgrds';
$route['stvacdm'] = 'Welcome/stvacdm';
$route['stvexam'] = 'Welcome/stvexam';
$route['stvlmst'] = 'Welcome/stvlmst';
$route['structure'] = 'welcome/structure';
// ---------------- General ---------------------
$route['stvstts'] = 'Welcome/stvstts';
$route['stvcity'] = 'Welcome/stvcity';
$route['stvpcnf'] = 'Welcome/stvpcnf';
//--------------History-----------------
$route['stvfacs'] = 'Welcome/stvfacs';
$route['stvusrs'] = 'Welcome/stvusrs';
$route['stvlogh'] = 'Welcome/stvlogh';
$route['stvfrmh'] = 'Welcome/stvfrmh';
//=================== ACCOUNT =======================
$route['account'] = 'Welcome/account';
// ================= Student Profile ===================
$route['profile'] = 'Profile';
$route['visiting'] = 'Welcome/visiting';

// ================ Teacher - Student Module ==================
$route['attendance'] = 'Dashboard/attendance';
$route['student-remark'] = 'Dashboard/student_remark';
$route['student-marks'] = 'Dashboard/student_marks';

// ================ Time Table Module ==================
$route['create-time'] = 'Welcome/create_time';
$route['create-timetable'] = 'Welcome/create_timetable';
$route['view-timetable'] = 'Welcome/view_timetable';

// ================= Vehicle And Driver ===============
$route['stvlvm'] = 'welcome/stvlvm'; 
$route['stvldrm'] = 'welcome/stvldrm';

// ================= Reports ============================
$route['all_reports'] = 'report/report_stats';
$route['fees_report'] = 'report/daily_fees_report';
$route['balance_fees'] = 'report/balance_fees';

// =========== this report only for mazhar bhai ==================
$route['student-Report'] = 'Student/students';
$route['Attendance'] = 'Welcome/attendance_students';
$route['Fee-massage'] = 'Welcome/fees_massage';
$route['Generate-Meeting'] = 'Welcome/generate_meeting';
// $route['Generate-Notes-Report'] = 'Welcome/notes_report'; // Pending
$route['Aadhar-Report'] = 'Welcome/get_aadhar_data';
$route['Generate-Notes'] = 'Welcome/generate_notes';
$route['Notes-Report'] = 'Welcome/notes_report';


// ====================== Fees Reconciliation =======================
$route['manage-fees/transactions'] = 'Manage_fees/transactions';
$route['manage-fees/bank-transactions'] = 'Manage_fees/get_bank_transactions';
$route['manage-fees/daily-fees'] = 'Manage_fees/get_daily_fees';
$route['manage-fees/update-fees-date/(:num)'] = 'Manage_fees/update_fees_date/$1';
$route['manage-fees/reconciliation-report'] = 'Manage_fees/reconciliation_report';
$route['manage-fees/reconciliation-report-data'] = 'Manage_fees/get_reconciliation_report';


$route['fees-bank-upload'] = 'fees_bank_upload/index';
$route['fees-bank-upload/upload'] = 'fees_bank_upload/upload';
$route['fees-bank-upload/rows/(:any)'] = 'fees_bank_upload/rows/$1';
$route['fees-bank-upload/report'] = 'fees_bank_upload/report';


$route['Report/last-year-balance-data'] =
    'Report/last_year_balance_report_ajax';
// $route['Report/all-previous-fees-data'] =
//     'Report/all_previous_fees_data';