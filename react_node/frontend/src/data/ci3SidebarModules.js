/**
 * Complete CodeIgniter 3 Sidebar Modules & Forms Catalog
 * Extracted directly from CI3 database and sidebar.php
 * Total Modules: 17 | Total Forms: 141
 */

export const CI3_MODULES = [
  {
    "module_id": 14,
    "module_name": "Account Management",
    "icon": "layers",
    "forms": [
      {
        "id": 94,
        "form_name": "Forms",
        "form_link": "Welcome/forms"
      },
      {
        "id": 93,
        "form_name": "Module Form",
        "form_link": "Welcome/mode"
      },
      {
        "id": 95,
        "form_name": "User Access",
        "form_link": "Welcome/user"
      },
      {
        "id": 96,
        "form_name": "User Report",
        "form_link": "Welcome/user_report"
      }
    ]
  },
  {
    "module_id": 9,
    "module_name": "Administrative",
    "icon": "server",
    "forms": [
      {
        "id": 35,
        "form_name": "Books & Result",
        "form_link": "books-and-result"
      },
      {
        "id": 36,
        "form_name": "Lesson Management",
        "form_link": "teacher/lesson"
      },
      {
        "id": 122,
        "form_name": "Lessons",
        "form_link": "Lesson_management"
      },
      {
        "id": 37,
        "form_name": "Remarks",
        "form_link": "student/remarks"
      }
    ]
  },
  {
    "module_id": 7,
    "module_name": "Attendance",
    "icon": "check-square",
    "forms": [
      {
        "id": 8,
        "form_name": "Student Attendance",
        "form_link": "student-attendance"
      },
      {
        "id": 19,
        "form_name": "Teacher Attendance",
        "form_link": "teacher-attendance"
      }
    ]
  },
  {
    "module_id": 5,
    "module_name": "Fees",
    "icon": "dollar-sign",
    "forms": [
      {
        "id": 141,
        "form_name": "Fees Balance Report",
        "form_link": "Report/fees_balance_report?type=all"
      },
      {
        "id": 142,
        "form_name": "Fees Comments",
        "form_link": "Report/Fees_comment"
      },
      {
        "id": 4,
        "form_name": "Fees Form",
        "form_link": "fees"
      },
      {
        "id": 123,
        "form_name": "Fees History",
        "form_link": "student/feesDetails"
      },
      {
        "id": 5,
        "form_name": "Fees Report",
        "form_link": "fees-report"
      },
      {
        "id": 133,
        "form_name": "Previous Fees Form",
        "form_link": "fees?type=pre"
      },
      {
        "id": 17,
        "form_name": "Send Fees Alert",
        "form_link": "FeesAlert/fees_report"
      },
      {
        "id": 18,
        "form_name": "Structure Master",
        "form_link": "structure"
      },
      {
        "id": 9,
        "form_name": "Visiting",
        "form_link": "visiting"
      }
    ]
  },
  {
    "module_id": 20,
    "module_name": "Fees Reconciliation",
    "icon": "users",
    "forms": [
      {
        "id": 135,
        "form_name": "Manage Fees Transaction",
        "form_link": "manage-fees/transactions"
      },
      {
        "id": 136,
        "form_name": "Upload Bank Transactions ",
        "form_link": "fees-bank-upload"
      }
    ]
  },
  {
    "module_id": 21,
    "module_name": "Fees Report",
    "icon": "dollar-sign",
    "forms": [
      {
        "id": 143,
        "form_name": "All Previous Fees Report",
        "form_link": "report/all_previous_fees_report"
      },
      {
        "id": 145,
        "form_name": "Currently Zero Fees Paid",
        "form_link": "Report/current_zero_paid_report"
      },
      {
        "id": 144,
        "form_name": "Last Year Fees Balance",
        "form_link": "Report/last_year_fees_report"
      }
    ]
  },
  {
    "module_id": 13,
    "module_name": "Master Forms",
    "icon": "clipboard",
    "forms": [
      {
        "id": 84,
        "form_name": "Academic Year",
        "form_link": "stvacdm"
      },
      {
        "id": 87,
        "form_name": "City Master",
        "form_link": "stvcity"
      },
      {
        "id": 77,
        "form_name": "College Master",
        "form_link": "stvcolg"
      },
      {
        "id": 89,
        "form_name": "Communication Details",
        "form_link": "stvpcnf"
      },
      {
        "id": 91,
        "form_name": "Driver Master",
        "form_link": "stvldrm"
      },
      {
        "id": 72,
        "form_name": "Exam Master",
        "form_link": "stvexam"
      },
      {
        "id": 81,
        "form_name": "Experiece Master",
        "form_link": "stvexpr"
      },
      {
        "id": 74,
        "form_name": "Fees Master",
        "form_link": "stvfees"
      },
      {
        "id": 83,
        "form_name": "Grades Master",
        "form_link": "stvgrds"
      },
      {
        "id": 73,
        "form_name": "Hold Master",
        "form_link": "stvhold"
      },
      {
        "id": 88,
        "form_name": "Hold Student",
        "form_link": "sprhold"
      },
      {
        "id": 85,
        "form_name": "Lesson Master",
        "form_link": "stvlmst"
      },
      {
        "id": 79,
        "form_name": "Major Master ",
        "form_link": "stvmajr"
      },
      {
        "id": 90,
        "form_name": "Manage Message Template ",
        "form_link": "msgtemplate/index"
      },
      {
        "id": 82,
        "form_name": "Position Master",
        "form_link": "stvposn"
      },
      {
        "id": 76,
        "form_name": "School Master",
        "form_link": "stvpsch"
      },
      {
        "id": 80,
        "form_name": "Shift Master",
        "form_link": "stvshif"
      },
      {
        "id": 86,
        "form_name": "State Master",
        "form_link": "stvstts"
      },
      {
        "id": 75,
        "form_name": "Subject Master",
        "form_link": "stvsubj"
      },
      {
        "id": 78,
        "form_name": "University Master",
        "form_link": "stvuniv"
      },
      {
        "id": 139,
        "form_name": "Update Class or Division",
        "form_link": "MasterForm/studentStdDiv"
      },
      {
        "id": 92,
        "form_name": "Vehicle Master",
        "form_link": "stvlvm"
      }
    ]
  },
  {
    "module_id": 18,
    "module_name": "New Reports",
    "icon": "pie-chart",
    "forms": [
      {
        "id": 127,
        "form_name": "Absent Report",
        "form_link": "Absent_student"
      },
      {
        "id": 108,
        "form_name": "Admission Report",
        "form_link": "reports/students/Addmission-report"
      },
      {
        "id": 111,
        "form_name": "Admission Summary",
        "form_link": "reports/students/admission-summary-report"
      },
      {
        "id": 115,
        "form_name": "All Student Report",
        "form_link": "reports/students/all-students"
      },
      {
        "id": 120,
        "form_name": "Balance Fees Report",
        "form_link": "reports/students/Balance-report"
      },
      {
        "id": 113,
        "form_name": "Balance Student",
        "form_link": "reports/students/balance-student"
      },
      {
        "id": 117,
        "form_name": "Daily Fees Report",
        "form_link": "reports/students/daily-fees-report"
      },
      {
        "id": 118,
        "form_name": "Datewise Fees Report",
        "form_link": "reports/students/datewise-fees-report"
      },
      {
        "id": 119,
        "form_name": "Fees Installment",
        "form_link": "reports/students/fees-installment-report"
      },
      {
        "id": 116,
        "form_name": "Fees Report",
        "form_link": "reports/students/Fees-report"
      },
      {
        "id": 110,
        "form_name": "Readmission",
        "form_link": "reports/students/Readdmission-report"
      },
      {
        "id": 109,
        "form_name": "Report - New Admission",
        "form_link": "reports/students/New-addmission"
      },
      {
        "id": 114,
        "form_name": "RTE Students",
        "form_link": "reports/students/Rte-report"
      },
      {
        "id": 112,
        "form_name": "Student Books",
        "form_link": "reports/students/books-report"
      }
    ]
  },
  {
    "module_id": 10,
    "module_name": "Online Application",
    "icon": "file-text",
    "forms": [
      {
        "id": 40,
        "form_name": "Junior College",
        "form_link": "Online_Application/junior_clg"
      },
      {
        "id": 38,
        "form_name": "Teacher Application",
        "form_link": "Online_Application/list"
      }
    ]
  },
  {
    "module_id": 17,
    "module_name": "Parents Forms",
    "icon": "package",
    "forms": [
      {
        "id": 107,
        "form_name": "Generate Meeting",
        "form_link": "Generate-Meeting"
      }
    ]
  },
  {
    "module_id": 19,
    "module_name": "Public",
    "icon": "airplay",
    "forms": [
      {
        "id": 121,
        "form_name": "Student Profile DB",
        "form_link": "classReport"
      }
    ]
  },
  {
    "module_id": 8,
    "module_name": "Reports",
    "icon": "pie-chart",
    "forms": [
      {
        "id": 104,
        "form_name": "Aadhar Details Report",
        "form_link": "Aadhar-Report"
      },
      {
        "id": 128,
        "form_name": "Absent Report",
        "form_link": "Absent_student"
      },
      {
        "id": 29,
        "form_name": "Admission Report",
        "form_link": "report/admissionreport"
      },
      {
        "id": 30,
        "form_name": "Admission Summary report",
        "form_link": "report/admission_summary"
      },
      {
        "id": 20,
        "form_name": "All Status Report",
        "form_link": "all_reports"
      },
      {
        "id": 22,
        "form_name": "Balance Fees Report",
        "form_link": "balance_fees"
      },
      {
        "id": 33,
        "form_name": "Balance Report",
        "form_link": "report/balance_rep"
      },
      {
        "id": 134,
        "form_name": "Current Year Fees",
        "form_link": "report/current_year_fees"
      },
      {
        "id": 21,
        "form_name": "Daily Fees Report",
        "form_link": "fees_report"
      },
      {
        "id": 23,
        "form_name": "Datewise Fees Report",
        "form_link": "report/fees_date_wise"
      },
      {
        "id": 129,
        "form_name": "Daywise Fees Report",
        "form_link": "report/monthly_fees"
      },
      {
        "id": 131,
        "form_name": "Exam Alert",
        "form_link": "report/exam_alert"
      },
      {
        "id": 25,
        "form_name": "Fees Alert Receipt",
        "form_link": "report/fees_alert"
      },
      {
        "id": 24,
        "form_name": "Fees Installment Report",
        "form_link": "report/fees_instment_report"
      },
      {
        "id": 34,
        "form_name": "Inactive Students Report",
        "form_link": "report/current_inactive"
      },
      {
        "id": 124,
        "form_name": "LC Report",
        "form_link": "Report/lc_report"
      },
      {
        "id": 31,
        "form_name": "New Admission Report",
        "form_link": "report/admission_report"
      },
      {
        "id": 106,
        "form_name": "Notes Report",
        "form_link": "Notes-Report"
      },
      {
        "id": 32,
        "form_name": "Readmission Report",
        "form_link": "report/readmission"
      },
      {
        "id": 26,
        "form_name": "Report ",
        "form_link": "report/report"
      },
      {
        "id": 27,
        "form_name": "Report2",
        "form_link": "report/reports"
      },
      {
        "id": 28,
        "form_name": "RTE Student Report",
        "form_link": "report/rte_report"
      }
    ]
  },
  {
    "module_id": 12,
    "module_name": "Section",
    "icon": "list",
    "forms": [
      {
        "id": 64,
        "form_name": " Create Time",
        "form_link": "create-time"
      },
      {
        "id": 70,
        "form_name": " Student Mark",
        "form_link": "Marks/Student_marks"
      },
      {
        "id": 50,
        "form_name": " Subject Teacher",
        "form_link": "subject-teacher"
      },
      {
        "id": 49,
        "form_name": "Assign Teacher",
        "form_link": "assign-teacher"
      },
      {
        "id": 51,
        "form_name": "Create Class",
        "form_link": "create-class"
      },
      {
        "id": 65,
        "form_name": "Create Time Table",
        "form_link": "create-timetable"
      },
      {
        "id": 56,
        "form_name": "Display Card",
        "form_link": "student/student_card"
      },
      {
        "id": 54,
        "form_name": "Division update",
        "form_link": "promote"
      },
      {
        "id": 61,
        "form_name": "Driver Master",
        "form_link": "Transportation/Driver_master"
      },
      {
        "id": 57,
        "form_name": "Event",
        "form_link": "events"
      },
      {
        "id": 68,
        "form_name": "Exam master",
        "form_link": "Marks/Exam_master"
      },
      {
        "id": 69,
        "form_name": "Exam Setup",
        "form_link": "Marks/Examsetup"
      },
      {
        "id": 60,
        "form_name": "Generate Hall Ticket",
        "form_link": "hall_ticket/report"
      },
      {
        "id": 59,
        "form_name": "Hall Ticket Report",
        "form_link": "hall_ticket/fetchreport"
      },
      {
        "id": 71,
        "form_name": "Mark Report",
        "form_link": "Marks/marks_report"
      },
      {
        "id": 58,
        "form_name": "New Hall Ticket ",
        "form_link": "hall_ticket"
      },
      {
        "id": 53,
        "form_name": "Random class",
        "form_link": "randomclass/assign_class"
      },
      {
        "id": 63,
        "form_name": "Student Vehicle Details",
        "form_link": "Transportation/student_details"
      },
      {
        "id": 67,
        "form_name": "Subject Master",
        "form_link": "Marks/Subject_master"
      },
      {
        "id": 55,
        "form_name": "Upload Photo",
        "form_link": "student/i_card"
      },
      {
        "id": 62,
        "form_name": "Vehicle Master",
        "form_link": "Transportation/Vehicle_master"
      },
      {
        "id": 52,
        "form_name": "View Class",
        "form_link": "class"
      },
      {
        "id": 66,
        "form_name": "View Time Table",
        "form_link": "view-timetable"
      }
    ]
  },
  {
    "module_id": 16,
    "module_name": "Security",
    "icon": "shield",
    "forms": [
      {
        "id": 98,
        "form_name": "Create User",
        "form_link": "stvusrs"
      },
      {
        "id": 97,
        "form_name": "Form Access ",
        "form_link": "stvfacs"
      },
      {
        "id": 100,
        "form_name": "Form Access History",
        "form_link": "stvfrmh"
      },
      {
        "id": 99,
        "form_name": "Login History",
        "form_link": "stvlogh"
      }
    ]
  },
  {
    "module_id": 4,
    "module_name": "Student",
    "icon": "users",
    "forms": [
      {
        "id": 103,
        "form_name": "Approve LC",
        "form_link": "report/lc_isshu_report"
      },
      {
        "id": 102,
        "form_name": "Issue Bonafide",
        "form_link": "report/report_stats_bf"
      },
      {
        "id": 101,
        "form_name": "Issue LC",
        "form_link": "report/report_stats_lc"
      },
      {
        "id": 3,
        "form_name": "Re-Admission Form",
        "form_link": "readmission"
      },
      {
        "id": 138,
        "form_name": "Readmission massage",
        "form_link": "FeesAlert/admission_confirmation"
      },
      {
        "id": 130,
        "form_name": "Report Card",
        "form_link": "Report_card"
      },
      {
        "id": 140,
        "form_name": "Results",
        "form_link": "Report_card/getReportCardData"
      },
      {
        "id": 2,
        "form_name": "Student Admission Form",
        "form_link": "admission-form"
      },
      {
        "id": 132,
        "form_name": "Student Attendance",
        "form_link": "report/Student_atnd"
      },
      {
        "id": 15,
        "form_name": "Student Books",
        "form_link": "student/books"
      },
      {
        "id": 16,
        "form_name": "Student Books Report",
        "form_link": "student/books_records"
      },
      {
        "id": 14,
        "form_name": "Student Comments ",
        "form_link": "student/student_comments"
      },
      {
        "id": 12,
        "form_name": "Student Remark",
        "form_link": "remark"
      },
      {
        "id": 137,
        "form_name": "Students Promotion",
        "form_link": "student/promotion"
      },
      {
        "id": 13,
        "form_name": "Tailor Details",
        "form_link": "student/tailor_details"
      },
      {
        "id": 11,
        "form_name": "Update Student",
        "form_link": "student/student_list"
      }
    ]
  },
  {
    "module_id": 6,
    "module_name": "Teacher",
    "icon": "user",
    "forms": [
      {
        "id": 10,
        "form_name": "Account Info",
        "form_link": "account-details"
      },
      {
        "id": 6,
        "form_name": "Add Account",
        "form_link": "teacher-account"
      },
      {
        "id": 105,
        "form_name": "Generate Notes",
        "form_link": "Generate-Notes"
      },
      {
        "id": 7,
        "form_name": "Lesson Management",
        "form_link": "teacher/lesson"
      }
    ]
  },
  {
    "module_id": 11,
    "module_name": "Tracking",
    "icon": "activity",
    "forms": [
      {
        "id": 42,
        "form_name": "Student Application",
        "form_link": "Report/login_tracking"
      },
      {
        "id": 43,
        "form_name": "Student Application",
        "form_link": "stats/activity_report"
      },
      {
        "id": 48,
        "form_name": "Syllabus Calender",
        "form_link": "Report/syllabus_calender"
      },
      {
        "id": 47,
        "form_name": "Syllabus Report",
        "form_link": "Report/syllabus"
      },
      {
        "id": 44,
        "form_name": "Teacher Activity",
        "form_link": "Report/teacher_activity"
      },
      {
        "id": 45,
        "form_name": "Teacher Activity DB",
        "form_link": "Report/teacher_activity_db"
      },
      {
        "id": 46,
        "form_name": "Teacher Activity Report",
        "form_link": "report/teacher_activity_report"
      },
      {
        "id": 41,
        "form_name": "Teacher Application ",
        "form_link": "Report/login_tracking"
      }
    ]
  }
];

export default CI3_MODULES;
