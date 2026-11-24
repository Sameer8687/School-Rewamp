<?php
function teacher_forms()
{
  return array(
    array('name' => 'Student Contact', 'link' => 'dashboard/student_contacts'),
    array('name' => 'Student Attendance', 'link' => 'dashboard/student_attendance'),
    array('name' => 'Tracking', 'link' => 'dashboard/tracking'),
    // ---------------------------Ansari kamran--------------------------------
    // ---------------------------- 13-09-23 ----------------------------------
    array('name' => 'Share Links', 'link' => 'dashboard/share_links'),
    // Ab. MAtin 17-Oct-23
    array('name' => 'Fees Alerts Massage', 'link' => 'dashboard/share_links?type=alert'),
    // ---------------------------Ansari kamran--------------------------------
    // ---------------------------- 23-09-23 ----------------------------------
    array('name' => 'Syllabus', 'link' => 'dashboard/syllabus'),

    // ---------------------------Ansari kamran--------------------------------
    // ---------------------------- 26-09-23 ----------------------------------
    array('name' => 'Syllabus Calendar', 'link' => 'dashboard/syllabus_calendar'),

    //-----------------------AB MATEEN----------------------
    array('name' => 'Class Result Dashboard', 'link' => 'Class_DB'),

    // --------------------------- Ansari kamran -------------------------------
    // ----------------------------- 15-11-23 ----------------------------------
    array('name' => 'Formative', 'link' => 'dashboard/evaluation'),
    // --------------------------- Ansari kamran -------------------------------
    // ----------------------------- 18-11-24 ----------------------------------
    array('name' => 'Parents Meeting', 'link' => 'dashboard/parents_meeting'),

    // --------------------------- AAsif -------------------------------
    // ----------------------------- 06-04-25 ----------------------------------
    array('name' => 'Report Card', 'link' => 'dashboard/getReportCardData'),

     // --------------------------- Ansari kamran -------------------------------
    // ----------------------------- 23-06-25 ----------------------------------
    array('name' => 'Lesson Files Approval', 'link' => 'Lesson_approval'),

    // --------------------------- Mugal Hassan -------------------------------
    // ----------------------------- 30-03-2026 ----------------------------------
    array('name' => 'Personal Details', 'link' => 'dashboard/personal_details_form'),
  );
}
