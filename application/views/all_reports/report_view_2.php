<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Color Scheme from Reference Image */
        :root {
            --primary-blue: #1A73E8;
            --secondary-blue: #4285F4;
            --dark-blue: #174EA6;
            --light-blue: #E8F0FE;
            --dark-gray: #3C4043;
            --medium-gray: #5F6368;
            --light-gray: #F1F3F4;
            --white: #FFFFFF;
            --accent-green: #34A853;
            --accent-red: #EA4335;
            --accent-yellow: #FBBC05;
        }

        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #FAFAFA;
            color: var(--dark-gray);
            font-size: 13px;
            line-height: 1.4;
        }

        .sidebar {
            background-color: var(--white);
            border-right: 1px solid var(--light-gray);
            height: 100vh;
            position: fixed;
            padding: 15px;
            width: 25%;
        }

        .main-content {
            margin-left: 25%;
            padding: 15px;
            background-color: #FAFAFA;
        }

        .student-list {
            max-height: calc(100vh - 180px);
            overflow-y: auto;
            margin-top: 10px;
        }

        .student-card {
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 8px;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
        }

        .student-card:hover {
            background-color: var(--light-blue);
        }

        .report-card {
            background-color: var(--white);
            border-radius: 4px;
            border: 1px solid var(--light-gray);
            padding: 15px;
            margin-bottom: 15px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--light-gray);
            margin: 0 auto;
            display: block;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .active-student {
            background-color: var(--primary-blue) !important;
            color: white !important;
        }

        .filter-row {
            margin-bottom: 10px;
            display: flex;
            gap: 5px;
        }

        .filter-row .form-control {
            flex: 1;
            font-size: 13px;
            padding: 6px 10px;
            border: 1px solid var(--light-gray);
        }

        .view-btn {
            flex: 0 0 auto;
            width: auto;
            padding: 6px 12px;
            font-size: 13px;
            background-color: var(--primary-blue);
            border: none;
        }

        .view-btn:hover {
            background-color: var(--dark-blue);
        }

        .remark-status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: normal;
            white-space: nowrap;
        }

        .status-new {
            background-color: var(--accent-yellow);
            color: #000;
        }

        .status-replied {
            background-color: var(--accent-green);
            color: #fff;
        }

        .exam-card {
            border-left: 3px solid var(--primary-blue);
            margin-bottom: 15px;
        }

        .subject-card {
            border-left: 2px solid var(--accent-green);
            margin-bottom: 10px;
        }

        .marks-badge {
            font-size: 12px;
            padding: 3px 8px;
        }

        .progress {
            height: 8px;
            background-color: var(--light-gray);
        }

        .exam-header {
            background-color: var(--light-gray);
            border-radius: 3px;
            padding: 8px;
            margin-bottom: 8px;
        }

        h3,
        h4,
        h5,
        h6 {
            font-weight: 500;
            color: var(--dark-gray);
            margin-bottom: 12px;
        }

        h3 {
            font-size: 18px;
        }

        h4 {
            font-size: 16px;
        }

        h5 {
            font-size: 15px;
        }

        h6 {
            font-size: 14px;
        }

        .table {
            font-size: 13px;
            margin-bottom: 0;
        }

        .table th {
            font-weight: 500;
            background-color: var(--light-gray);
            padding: 8px 12px;
        }

        .table td {
            padding: 8px 12px;
            vertical-align: middle;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Hidden templates */
        .template {
            display: none;
        }

        /* Fix for remarks table */
        .remark-reply-cell {
            min-width: 200px;
            word-wrap: break-word;
        }

        .no-data-message {
            text-align: center;
            padding: 20px;
            color: var(--medium-gray);
            border: 1px dashed var(--light-gray);
            border-radius: 4px;
            margin-top: 10px;
        }

        .no-data-message i {
            font-size: 24px;
            margin-bottom: 8px;
            color: var(--medium-gray);
        }

        .badge-success {
            background-color: var(--accent-green);
        }

        .badge-warning {
            background-color: var(--accent-yellow);
            color: #000;
        }

        .badge-danger {
            background-color: var(--accent-red);
        }

        .badge-primary {
            background-color: var(--primary-blue);
        }

        .badge-info {
            background-color: var(--secondary-blue);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar with student list -->
            <div class="col-md-3 sidebar">
                <h5 class="mb-3"><i class="fas fa-users me-2"></i>Class Students</h5>

                <div class="filter-row">
                    <select class="form-control" id="academic-year-selector">
                        <option value="">Academic Year</option>
                    </select>

                    <select class="form-control" id="class-selector">
                        <option value="">Class</option>
                    </select>

                    <select class="form-control" id="division-selector">
                        <option value="">Division</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                </div>
                <button class="btn btn-primary view-btn" id="view-btn">View</button>
                <hr>

                <div class="student-list" id="student-list">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p>Select filters and click View</p>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <div class="col-md-9 main-content">
                <div id="student-report">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-user-graduate fa-4x mb-2"></i>
                        <h4>Select a student to view report</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates -->
    <div class="template" id="student-card-template">
        <div class="card student-card">
            <div class="card-body py-2">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <i class="fas fa-user-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 student-name"></h6>
                        <small class="text-muted student-id"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="template" id="student-report-template">
        <div class="report-card">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="<?php echo base_url('assets/images/student-'); ?>default.svg" alt="Student Photo" class="profile-img mb-2">
                    <h4 id="student-name-header"></h4>
                    <p class="text-muted" id="student-id-class"></p>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Father's Name:</span>
                                <strong id="father-name"></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Date of Birth:</span>
                                <strong id="dob"></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Contact:</span>
                                <strong id="contact"></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Class Teacher:</span>
                                <strong id="class-teacher"></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Fees:</span>
                                <span class="badge badge-success" id="total-fees"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Balance Fees:</span>
                                <span class="badge badge-warning" id="balance-fees"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- fees details and records   -->
        <style>
            .fees-table {
                width: 100%;
                font-size: 0.85rem;
                border-collapse: collapse;
            }

            .fees-table th {
                background: #48A6A7;
                color: #fff;
                text-align: center;

            }

            .fees-table td,
            .fees-table th {
                font-weight: normal;
                padding: 8px;
                text-align: center;
                vertical-align: middle;
                border: 1px solid #e7e7e7;
            }

            .fees-table .text-danger {
                color: #dc3545;
                font-weight: 500;
            }

            .fees-table .text-success {
                color: #28a745;
                font-weight: 500;
            }

            .fees-table .text-warning {
                color: #ffc107;
                font-weight: 500;
            }

            .fees-table .summary-row td {
                background-color: rgb(253, 253, 253);
                font-weight: 600;
                font-size: 0.85rem;
            }

            .fees-table .academic-gap td {
                background-color: rgb(248, 248, 248);
                border: none;
                height: 10px;
            }

            .text-sm {
                font-size: 0.8rem;
            }
        </style>



        <div class="report-card mt-4">
            <h5><i class="fas fa-money-bill-wave me-2"></i>Fees Report</h5>
            <div id="fees-report-container">
                <div class="no-data-message">
                    <i class="fas fa-book-open"></i>
                    <p>No fees data available</p>
                </div>
            </div>
        </div>


        <style>
            .report-card {
                position: relative;
            }

            #month-list-attendance {
                
                max-height: 300px;
                overflow-y: auto;
            }

            #month-list-attendance ul li {
                padding: 5px;
                font-weight: 500;
                margin-bottom: 2px;
            }
        </style>
        <div class="row mt-3">
            <!-- <div class="col-md-6">
                <div class="report-card">
                    <h5><i class="fas fa-chart-pie me-2"></i>Attendance Overview</h5>
                    <div class="chart-container">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mt-2">
                                <p><i class="fas fa-circle text-success me-2"></i>Present: <span id="present-days">0</span> days</p>
                                <p><i class="fas fa-circle text-danger me-2"></i>Absent: <span id="absent-days">0</span> days</p>
                                <p><i class="fas fa-circle text-primary me-2"></i>Total: <span id="total-days">0</span> days</p>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div id="month-list-attendance"></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-md-6">
                <div class="row">
                    <!-- Left Card: Bar Chart -->
                    <div class="col-md-6">
                        <div class="report-card h-100">
                            <h5><i class="fas fa-chart-bar me-2"></i>Attendance Chart</h5>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Right Card: Monthly List -->
                    <div class="col-md-6">
                        <div class="report-card h-100">
                            <h5><i class="fas fa-calendar-alt me-2"></i>Monthly Attendance</h5>
                            <div id="month-list-attendance" class="p-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="report-card">
                    <h5><i class="fas fa-star me-2"></i>Performance</h5>
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                    <div id="performance-summary" class="mt-3"></div>
                </div>
            </div>

        </div>

        <!-- <div class="report-card mt-3">
            <h5><i class="fas fa-calendar-alt me-2"></i>Monthly Attendance</h5>
            <div class="chart-container">
                <canvas id="monthlyAttendanceChart"></canvas>
            </div>
        </div> -->

        <div class="report-card mt-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                <h5 class="mb-2 me-3">
                    <i class="fas fa-award me-2"></i>Academic Marks
                </h5>
                <div class="legends d-flex flex-wrap align-items-center gap-3">
                    <span class="legend-item text-danger fw-semibold">Fail (&lt; 35%)</span>
                    <span class="legend-item text-warning fw-semibold">Pass (&ge; 35%)</span>
                    <span class="legend-item text-primary fw-semibold">Good (&ge; 60%)</span>
                    <span class="legend-item text-success fw-semibold">Distinction (&ge; 75%)</span>
                </div>
            </div>

            <div id="marks-container">
                <div class="no-data-message">
                    <i class="fas fa-book-open"></i>
                    <p>No marks data available</p>
                </div>
            </div>
        </div>


        <div class="report-card mt-3">
            <h5><i class="fas fa-comment me-2"></i>Teacher Remarks</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="remarks-table">
                    <thead>
                        <tr>
                            <th style="width: 15%">Date</th>
                            <th style="width: 35%">Remark</th>
                            <th style="width: 40%" class="remark-reply-cell">Reply</th>
                            <th style="width: 10%">Status</th>
                        </tr>
                    </thead>
                    <tbody id="remarks-body">
                        <!-- Will be populated by JS -->
                    </tbody>
                </table>
                <div id="no-remarks-message" class="no-data-message">
                    <i class="fas fa-comment-slash"></i>
                    <p>No remarks available</p>
                </div>
            </div>
        </div>
    </div>

    <div class="template" id="exam-card-template">
        <div class="exam-card card mb-3">
            <div class="card-body py-2">
                <div class="exam-header">
                    <h6 class="mb-0 exam-name"></h6>
                    <small class="text-muted exam-date"></small>
                </div>
                <div class="subjects-container"></div>
            </div>
        </div>
    </div>

    <div class="template" id="subject-card-template">
        <div class="subject-card card mb-2">
            <div class="card-body py-2">
                <h6 class="card-title subject-name mb-2"></h6>

                <div class="d-flex justify-content-between mb-1">
                    <span>Written:</span>
                    <span class="written-marks"></span>
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <span>Oral:</span>
                    <span class="oral-marks"></span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span>Total:</span>
                    <span class="total-marks"></span>
                </div>

                <div class="progress mt-1">
                    <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="template" id="remark-row-template">
        <tr>
            <td class="remark-date"></td>
            <td class="remark-text"></td>
            <td class="remark-reply">
                <span class="reply-text"></span>
                <small class="text-muted reply-date"></small>
            </td>
            <td>
                <span class="remark-status"></span>
            </td>
        </tr>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variable to store chart instances
        var performanceChart = null;
        var attendanceChart = null;
        var monthlyAttendanceChart = null;
        var currentStudentId = null;
        var currentAcademicYear = null;
        var currentClass = null;

        $(document).ready(function() {
            // Load academic years and classes from API
            loadAcademicYears();
            loadClasses();

            // Handle view button click
            $('#view-btn').click(function() {
                var academicYear = $('#academic-year-selector').val();
                var classId = $('#class-selector').val();
                var division = $('#division-selector').val();

                if (academicYear && classId && division) {
                    loadStudents(classId, division, academicYear);
                } else {
                    $('#student-list').html(`
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>Please select all filters</p>
                        </div>
                    `);
                }
            });
        });

        function loadAcademicYears() {
            $.ajax({
                url: 'https://school.oliveschool.org.in/scst-api/api/v1/academic-years',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.data.list && data.data.list.length > 0) {
                        const selectedYear = data?.data?.current_academic_year?.stvschool_acdm_year;
                        var yearSelector = $('#academic-year-selector');
                        // Clear existing options except the first one
                        yearSelector.find('option:not(:first)').remove();

                        // Add new options
                        $.each(data.data.list, function(index, yearItem) {
                            yearSelector.append(`
                                <option ${yearItem.stvacdm_id == selectedYear ? 'selected' : ''} value="${yearItem.stvacdm_id}">${yearItem.stvacdm_year}</option>
                            `);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading academic years:', error);
                }
            });
        }

        function loadClasses() {
            $.ajax({
                url: 'https://school.oliveschool.org.in/scst-api/api/v1/classes',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.data && data.data.length > 0) {
                        var classSelector = $('#class-selector');
                        // Clear existing options except the first one
                        classSelector.find('option:not(:first)').remove();

                        // Add new options
                        $.each(data.data, function(index, classItem) {
                            classSelector.append(`
                                <option value="${classItem.class}">${classItem.class}</option>
                            `);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading classes:', error);
                }
            });
        }

        function loadStudents(classId, division, academicYear) {
            // Build the API URL with query parameters
            var apiUrl = `https://school.oliveschool.org.in/scst-api/api/v1/students?academic-year=${academicYear}&class=${classId}&division=${division}`;

            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var studentList = $('#student-list');
                    studentList.empty();

                    if (data && data.data && data.data.length > 0) {
                        $.each(data.data, function(index, student) {
                            // Clone the student card template
                            var card = $('#student-card-template').clone().removeClass('template').show();

                            // Set the student data
                            card.find('.student-name').text(student.student_name);
                            card.find('.student-id').text(student.SPRIDEN_student_id);

                            // Set the click handler
                            card.attr('onclick', `loadStudentData(${student.id}, ${academicYear}, '${classId}')`);

                            // Add to the list
                            studentList.append(card);
                        });
                    } else {
                        studentList.html(`
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                <p>No students found with these filters</p>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading students:', error);
                    $('#student-list').html(`
                        <div class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p>Error loading students</p>
                            <small>${error}</small>
                        </div>
                    `);
                }
            });
        }

        function loadStudentData(studentId, academicYear, classId) {
            currentStudentId = studentId;
            currentAcademicYear = academicYear;
            currentClass = classId;

            // Show loading state
            $('#student-report').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading student data...</p>
                </div>
            `);

            // Fetch student details from the API
            $.ajax({
                url: `https://school.oliveschool.org.in/scst-api/api/v1/students/detail?academic-year=${academicYear}&student-id=${studentId}`,
                type: 'GET',
                dataType: 'json',
                success: function(studentData) {
                    // Fetch attendance data
                    $.ajax({
                        url: `https://school.oliveschool.org.in/scst-api/api/v1/students/attendance-summary?academic-year=${academicYear}&student-id=${studentId}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(attendanceResponse) {
                            // Fetch remarks data
                            $.ajax({
                                url: `https://school.oliveschool.org.in/scst-api/api/v1/students/remark?academic-year=${academicYear}&student-id=${studentId}&class=${classId}`,
                                type: 'GET',
                                dataType: 'json',
                                success: function(remarksResponse) {
                                    // Fetch academic marks data
                                    $.ajax({
                                        url: `https://school.oliveschool.org.in/scst-api/api/v1/students/academic-marks?academic-year=${academicYear}&student-id=${studentId}&class=${classId}`,
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(marksResponse) {
                                            // Process all responses
                                            renderStudentReport(studentId, academicYear, studentData, attendanceResponse, remarksResponse, marksResponse);
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error loading marks data:', error);
                                            // Render with empty marks if API fails
                                            renderStudentReport(studentId, academicYear, studentData, attendanceResponse, remarksResponse, {
                                                data: []
                                            });
                                        }
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error loading remarks data:', error);
                                    // Render with empty remarks if API fails
                                    renderStudentReport(studentId, academicYear, studentData, attendanceResponse, {
                                        data: []
                                    }, {
                                        data: []
                                    });
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading attendance data:', error);
                            // Render with default attendance data if API fails
                            renderStudentReport(studentId, academicYear, studentData, {
                                data: {
                                    academic: {
                                        present_days: 0,
                                        absent_days: 0,
                                        total_days: 0
                                    },
                                    monthly: []
                                }
                            }, {
                                data: []
                            }, {
                                data: []
                            });
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading student data:', error);
                    $('#student-report').html(`
                        <div class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p>Error loading student details</p>
                            <small>${error}</small>
                        </div>
                    `);
                }
            });

            fetchAndRenderFeesReport(studentId, academicYear, classId);
        }

        function renderStudentReport(studentId, academicYear, studentData, attendanceResponse, remarksResponse, marksResponse) {
            const {
                personal,
                fees,
                contact_details,
                classDetails
            } = studentData?.data;

            const studentClass = studentData?.data?.class;
            const {
                absent_days,
                present_days,
                total_days
            } = attendanceResponse?.data?.academic || {
                absent_days: 0,
                present_days: 0,
                total_days: 0
            };

            // Update active state in student list
            $('.student-card').removeClass('active-student');
            $(`[onclick="loadStudentData(${studentId}, ${academicYear}, '${studentClass.class}')"]`).addClass('active-student');

            // Clone the report template and show it
            var report = $('#student-report-template').clone().removeClass('template').show();
            $('#student-report').empty().append(report);

            // Set student details
            $('#student-name-header').text(personal.student_name || 'N/A');
            $('#student-id-class').text(`${personal.SPRIDEN_student_id || 'N/A'} | ${studentClass.STVCLAS_standard || 'N/A'} - ${studentClass.STVCLAS_class || 'N/A'}`);
            $('#father-name').text(personal.SPRIDEN_father_name || 'N/A');
            $('#dob').text(personal.SPRIDEN_birthdate || 'N/A');
            $('#contact').text(contact_details.stvpcnf_mobile || 'N/A');
            $('#class-teacher').text(studentClass.teacher_name || 'N/A');
            $('#total-fees').text(fees.summary.total || 'N/A');
            $('#balance-fees').text(fees.summary.balance || 'N/A');

            // Set attendance numbers
            $('#present-days').text(present_days || 0);
            $('#absent-days').text(absent_days || 0);
            $('#total-days').text(total_days || 0);

            // Destroy previous charts if they exist
            if (attendanceChart) {
                attendanceChart.destroy();
            }
            if (performanceChart) {
                performanceChart.destroy();
            }
            if (monthlyAttendanceChart) {
                monthlyAttendanceChart.destroy();
            }

            // Draw attendance chart
            var attendanceCtx = document.getElementById('attendanceChart').getContext('2d');

            attendanceChart = new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: ['Present', 'Absent', 'Total'],
                    datasets: [{
                        data: [
                            present_days || 0,
                            absent_days || 0,
                            total_days || 0
                        ],
                        backgroundColor: ['#34A853', '#EA4335', '#4285F4'],
                        hoverBackgroundColor: ['#2d9248', '#d33a2e', '#3a7ae8'],
                        borderRadius: 8,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // No legend needed since labels are obvious
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} days`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Days'
                            },
                            ticks: {
                                precision: 0 // Ensures only whole numbers
                            }
                        },
                        x: {
                            title: {
                                display: false
                            }
                        }
                    }
                }
            });


            // Draw performance chart
            var performanceCtx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: ['English', 'Math', 'Science'], // Will be replaced dynamically
                    datasets: [{
                            label: 'Obtained Marks',
                            type: 'bar',
                            backgroundColor: '#4285F4',
                            borderColor: '#3367D6',
                            borderWidth: 1,
                            data: [0, 0, 0], // Dynamic
                            yAxisID: 'y',
                        },
                        {
                            label: 'Total Marks',
                            type: 'line',
                            borderColor: '#EA4335',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            tension: 0.3,
                            pointBackgroundColor: '#EA4335',
                            pointRadius: 4,
                            data: [100, 100, 100], // Dynamic
                            yAxisID: 'y',
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Marks'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#eee'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });



            // Draw monthly attendance chart if data exists
            if (attendanceResponse.data && attendanceResponse.data.monthly) {
                // renderMonthlyAttendanceChart(attendanceResponse.data.monthly);
                renderAttendanceMonthlyDetails(attendanceResponse.data.monthly);
            }

            // Update performance chart with actual marks data if available
            if (marksResponse.data && marksResponse.data.length > 0) {
                updatePerformanceChart(marksResponse.data);
                renderMarksCards(marksResponse.data);
            } else {
                $('#marks-container').html(`
                    <div class="no-data-message">
                        <i class="fas fa-book-open"></i>
                        <p>No marks data available</p>
                    </div>
                `);
            }

            // Render remarks
            renderRemarksTable(remarksResponse.data || []);
        }

        function renderAttendanceMonthlyDetails(attendanceData) {
            const list = document.getElementById("month-list-attendance");
            list.innerHTML = ""; // Clear previous

            attendanceData.forEach(item => {
                const monthName = new Date(item.month + "-01").toLocaleString("default", {
                    month: "short",
                    year: "numeric"
                });

                const present = parseInt(item.present_days);
                const total = parseInt(item.total_days);
                const percentage = total ? (present / total) * 100 : 0;

                const badgeColor =
                    percentage >= 75 ? "success" :
                    percentage >= 50 ? "warning" :
                    "danger";

                // Create styled card item
                const card = document.createElement("div");
                card.className = `mb-2 p-2 rounded shadow-sm border-start border-4 border-${badgeColor}`;
                card.style.backgroundColor = "#f9f9f9";

                card.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${monthName}</strong><br>
                            <small class="text-muted">${present} out of ${total} days</small>
                        </div>
                        <span class="badge bg-${badgeColor}">${Math.round(percentage)}%</span>
                    </div>
                `;

                list.appendChild(card);
            });
        }


        function renderMarksCards_old(examData) {
            const marksContainer = $('#marks-container');
            marksContainer.empty();

            if (!examData || examData.length === 0) {
                marksContainer.html(`
                    <div class="no-data-message">
                        <i class="fas fa-book-open"></i>
                        <p>No marks data available</p>
                    </div>
                `);
                return;
            }

            examData.forEach(exam => {
                // Group records by subject
                const subjects = {};
                exam.records.forEach(record => {
                    if (!subjects[record.stvsubj_subject]) {
                        subjects[record.stvsubj_subject] = {
                            written: {
                                obtained: 0,
                                total: 0
                            },
                            oral: {
                                obtained: 0,
                                total: 0
                            }
                        };
                    }

                    if (record.sprmark_exam_type === 'written') {
                        subjects[record.stvsubj_subject].written.obtained += parseInt(record.sprmark_mark) || 0;
                        subjects[record.stvsubj_subject].written.total += parseInt(record.sprmark_total_marks) || 0;
                    } else if (record.sprmark_exam_type === 'oral') {
                        subjects[record.stvsubj_subject].oral.obtained += parseInt(record.sprmark_mark) || 0;
                        subjects[record.stvsubj_subject].oral.total += parseInt(record.sprmark_total_marks) || 0;
                    }
                });

                // Create exam card
                const examCard = $('#exam-card-template').clone().removeClass('template').show();
                examCard.find('.exam-name').text(exam.exam.exam_name);
                examCard.find('.exam-date').text(`Exam Date: ${exam.records[0]?.sprmark_date || 'N/A'}`);

                const subjectsContainer = examCard.find('.subjects-container');

                // Add subjects to the exam card
                for (const [subject, marks] of Object.entries(subjects)) {
                    const totalObtained = marks.written.obtained + marks.oral.obtained;
                    const totalMarks = marks.written.total + marks.oral.total;
                    const percentage = totalMarks > 0 ? Math.round((totalObtained / totalMarks) * 100) : 0;

                    // Create subject card
                    const subjectCard = $('#subject-card-template').clone().removeClass('template').show();
                    subjectCard.find('.subject-name').text(subject);

                    // Set written marks
                    const writtenMarks = subjectCard.find('.written-marks');
                    writtenMarks.html(`
                        <span class="badge badge-primary marks-badge">${marks.written.obtained}/${marks.written.total}</span>
                        ${marks.written.total > 0 ? 
                            `<span class="badge badge-info marks-badge">${Math.round((marks.written.obtained / marks.written.total) * 100)}%</span>` : 
                            ''}
                    `);

                    // Set oral marks
                    const oralMarks = subjectCard.find('.oral-marks');
                    oralMarks.html(`
                        <span class="badge badge-primary marks-badge">${marks.oral.obtained}/${marks.oral.total}</span>
                        ${marks.oral.total > 0 ? 
                            `<span class="badge badge-info marks-badge">${Math.round((marks.oral.obtained / marks.oral.total) * 100)}%</span>` : 
                            ''}
                    `);

                    // Set total marks
                    const totalMarksEl = subjectCard.find('.total-marks');
                    totalMarksEl.html(`
                        <span class="badge badge-success marks-badge">${totalObtained}/${totalMarks}</span>
                        <span class="badge ${getPercentageBadgeClass(percentage)} marks-badge">${percentage}%</span>
                    `);

                    // Set progress bar
                    const progressBar = subjectCard.find('.progress-bar');
                    progressBar.addClass(getPercentageBarClass(percentage));
                    progressBar.attr('aria-valuenow', percentage);
                    progressBar.css('width', `${percentage}%`);

                    subjectsContainer.append(subjectCard);
                }

                marksContainer.append(examCard);
            });
        }

        function renderMarksCards(examData) {
            const marksContainer = $('#marks-container');
            marksContainer.empty();

            if (!Array.isArray(examData) || examData.length === 0) {
                marksContainer.html(`
                            <div class="no-data-message text-center p-4 text-muted">
                                <i class="fas fa-book-open fa-2x mb-2"></i>
                                <p>No marks data available</p>
                            </div>
                        `);
                return;
            }

            const allSubjectsSet = new Set();
            const examHeaders = [];
            const examSubjectsMap = [];

            examData.forEach(exam => {
                const examName = exam?.exam?.exam_name?.trim() || 'Unnamed Exam';
                examHeaders.push(examName);

                const records = Array.isArray(exam.records) ? exam.records : [];
                const subjectsMap = {};

                records.forEach(record => {
                    const subject = record?.stvsubj_subject?.trim() || 'Unknown Subject';
                    const examType = record?.sprmark_exam_type?.toLowerCase() || '';
                    const obtained = parseInt(record?.sprmark_mark) || 0;
                    const total = parseInt(record?.sprmark_total_marks) || 0;

                    if (!['oral', 'written'].includes(examType)) return;

                    allSubjectsSet.add(subject);

                    if (!subjectsMap[subject]) {
                        subjectsMap[subject] = {
                            oral: {
                                obtained: 0,
                                total: 0
                            },
                            written: {
                                obtained: 0,
                                total: 0
                            }
                        };
                    }

                    subjectsMap[subject][examType].obtained += obtained;
                    subjectsMap[subject][examType].total += total;
                });

                examSubjectsMap.push(subjectsMap);
            });

            const allSubjects = Array.from(allSubjectsSet).sort();

            let tableHtml = `
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered text-center align-middle" style="background-color: white;">
                                <thead>
                                    <tr style="background: linear-gradient(to right, #6a11cb, #2575fc); color: white;">
                                        <th rowspan="2" class="align-middle fs-6">Subject</th>
                                        ${examHeaders.map(name => `<th colspan="3" class="fs-6">${name}</th>`).join('')}
                                    </tr>
                                    <tr style="background-color: #f0f0f0;">
                                        ${examHeaders.map(() => `
                                            <th>Oral</th>
                                            <th>Written</th>
                                            <th>Total</th>
                                        `).join('')}
                                    </tr>
                                </thead>
                                <tbody>
                    `;

            allSubjects.forEach(subject => {
                tableHtml += `<tr><td class="text-start fw-semibold">${subject}</td>`;
                examSubjectsMap.forEach(subjectsMap => {
                    const marks = subjectsMap[subject] || {
                        oral: {
                            obtained: 0,
                            total: 0
                        },
                        written: {
                            obtained: 0,
                            total: 0
                        }
                    };

                    const oral = marks.oral;
                    const written = marks.written;

                    const oralText = oral.total > 0 ? `${oral.obtained}/${oral.total}` : '-';
                    const writtenText = written.total > 0 ? `${written.obtained}/${written.total}` : '-';
                    const totalObtained = oral.obtained + written.obtained;
                    const totalMarks = oral.total + written.total;

                    const totalText = totalMarks > 0 ? `${totalObtained}/${totalMarks}` : '-';

                    let textColor = 'text-muted';
                    if (totalMarks > 0) {
                        const percentage = Math.round((totalObtained / totalMarks) * 100);
                        if (percentage < 35) {
                            textColor = 'text-danger'; // Fail
                        } else if (percentage < 60) {
                            textColor = 'text-warning'; // Pass
                        } else if (percentage < 75) {
                            textColor = 'text-primary'; // Good
                        } else {
                            textColor = 'text-success'; // Distinction
                        }
                    }

                    tableHtml += `
                                <td>${oralText}</td>
                                <td>${writtenText}</td>
                                <td class="${textColor} fw-semibold">${totalText}</td>
                            `;
                });
                tableHtml += `</tr>`;
            });

            tableHtml += `
                                </tbody>
                            </table>
                        </div>
                    `;

            marksContainer.html(tableHtml);
        }




        function updatePerformanceChart_old(examData) {
            // Group marks by subject across all exams
            const subjectMarks = {};

            examData.forEach(exam => {
                exam.records.forEach(record => {
                    if (!subjectMarks[record.stvsubj_subject]) {
                        subjectMarks[record.stvsubj_subject] = {
                            obtained: 0,
                            total: 0
                        };
                    }

                    subjectMarks[record.stvsubj_subject].obtained += parseInt(record.sprmark_mark) || 0;
                    subjectMarks[record.stvsubj_subject].total += parseInt(record.sprmark_total_marks) || 0;
                });
            });

            // Prepare data for the chart
            const subjects = Object.keys(subjectMarks);
            const obtainedMarks = subjects.map(subject => subjectMarks[subject].obtained);
            const totalMarks = subjects.map(subject => subjectMarks[subject].total);

            // Update the performance chart
            performanceChart.data.labels = subjects;
            performanceChart.data.datasets[0].data = obtainedMarks;
            performanceChart.data.datasets[1].data = totalMarks;
            performanceChart.update();
        }

        function updatePerformanceChart(examData) {
            const subjectMarks = {};

            examData.forEach(exam => {
                exam.records.forEach(record => {
                    const subject = record.stvsubj_subject;
                    if (!subjectMarks[subject]) {
                        subjectMarks[subject] = {
                            obtained: 0,
                            total: 0
                        };
                    }

                    subjectMarks[subject].obtained += parseInt(record.sprmark_mark) || 0;
                    subjectMarks[subject].total += parseInt(record.sprmark_total_marks) || 0;
                });
            });

            const subjects = Object.keys(subjectMarks);
            const obtainedMarks = subjects.map(subject => subjectMarks[subject].obtained);
            const totalMarks = subjects.map(subject => subjectMarks[subject].total);

            // Update chart data
            performanceChart.data.labels = subjects;
            performanceChart.data.datasets[0].data = obtainedMarks;
            performanceChart.data.datasets[1].data = totalMarks;
            performanceChart.update();

            // Story-based performance summary
            let strongSubjects = [];
            let weakSubjects = [];
            let summaryText = `<h6 class="fw-bold">Performance Summary:</h6><ul class="mb-0">`;

            subjects.forEach((subject, i) => {
                const obtained = obtainedMarks[i];
                const total = totalMarks[i];
                const percentage = total > 0 ? (obtained / total) * 100 : 0;

                if (percentage >= 75) {
                    strongSubjects.push(subject);
                    summaryText += `<li><span class="text-success">${subject}</span>: Excellent performance (Distinction)</li>`;
                } else if (percentage >= 60) {
                    summaryText += `<li><span class="text-primary">${subject}</span>: Good performance</li>`;
                } else if (percentage >= 35) {
                    summaryText += `<li><span class="text-warning">${subject}</span>: Needs improvement</li>`;
                } else {
                    weakSubjects.push(subject);
                    summaryText += `<li><span class="text-danger">${subject}</span>: Poor performance</li>`;
                }
            });

            summaryText += `</ul>`;
            $('#performance-summary').html(summaryText);
        }


        function renderMonthlyAttendanceChart(monthlyData) {
            const ctx = document.getElementById('monthlyAttendanceChart').getContext('2d');

            // Prepare data for the chart
            const labels = monthlyData.map(item => item.month);
            const presentData = monthlyData.map(item => parseInt(item.present_days));
            const absentData = monthlyData.map(item => parseInt(item.absent_days));
            const totalData = monthlyData.map(item => parseInt(item.total_days));

            window.monthlyAttendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Present Days',
                            data: presentData,
                            backgroundColor: '#34A853',
                            borderColor: '#34A853',
                            borderWidth: 0
                        },
                        {
                            label: 'Absent Days',
                            data: absentData,
                            backgroundColor: '#EA4335',
                            borderColor: '#EA4335',
                            borderWidth: 0
                        },
                        {
                            label: 'Total Days',
                            data: totalData,
                            backgroundColor: '#4285F4',
                            borderColor: '#4285F4',
                            borderWidth: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Days',
                                font: {
                                    size: 11
                                }
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#F1F3F4'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month',
                                font: {
                                    size: 11
                                }
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 10,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                afterBody: function(context) {
                                    const dataIndex = context[0].dataIndex;
                                    const present = presentData[dataIndex];
                                    const total = totalData[dataIndex];
                                    const percentage = total > 0 ? ((present / total) * 100).toFixed(1) : 0;
                                    return `Attendance Rate: ${percentage}%`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderRemarksTable(remarks) {
            const remarksBody = $('#remarks-body');
            const noRemarksMessage = $('#no-remarks-message');

            remarksBody.empty();

            if (remarks && remarks.length > 0) {
                noRemarksMessage.hide();

                $.each(remarks, function(index, remark) {
                    const statusClass = remark.stvrmrk_status === 'New' ? 'status-new' : 'status-replied';
                    const formattedDate = remark.stvrmrk_date !== '0000-00-00' ? remark.stvrmrk_date : 'N/A';
                    const replyDate = remark.stvrmrk_date_of_reply !== '0000-00-00' ? `Replied on: ${remark.stvrmrk_date_of_reply}` : '';

                    // Clone the remark row template
                    const row = $('#remark-row-template').clone().removeClass('template').show();

                    // Set the remark data
                    row.find('.remark-date').text(formattedDate);
                    row.find('.remark-text').text(remark.stvrmrk_remark || 'N/A');

                    const replyCell = row.find('.remark-reply');
                    replyCell.find('.reply-text').text(remark.stvrmrk_reply || 'No reply yet');

                    if (replyDate) {
                        replyCell.find('.reply-date').text(replyDate).show();
                    } else {
                        replyCell.find('.reply-date').hide();
                    }

                    row.find('.remark-status')
                        .addClass(statusClass)
                        .text(remark.stvrmrk_status || 'N/A');

                    remarksBody.append(row);
                });
            } else {
                remarksBody.html('<tr><td colspan="4" class="text-center py-3 text-muted">No remarks found</td></tr>');
                noRemarksMessage.show();
            }
        }

        function getPercentageBadgeClass(percentage) {
            if (percentage >= 90) return 'badge-success';
            if (percentage >= 75) return 'badge-primary';
            if (percentage >= 50) return 'badge-warning';
            return 'badge-danger';
        }

        function getPercentageBarClass(percentage) {
            if (percentage >= 90) return 'bg-success';
            if (percentage >= 75) return 'bg-primary';
            if (percentage >= 50) return 'bg-warning';
            return 'bg-danger';
        }
    </script>
    <script>
        function fetchAndRenderFeesReport(studentId, academicYearId, className) {
            const apiUrl = `https://school.oliveschool.org.in/scst-api/api/v1/students/fees-details?academic-year=${academicYearId}&student-id=${studentId}&class=${className}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(response => {
                    if (response.status === 'success' && response.data.length > 0) {
                        renderFeesTable(response.data);
                    } else {
                        $('#fees-report-container').html(`
                        <div class="no-data-message">
                            <i class="fas fa-book-open"></i>
                            <p>No fees data available</p>
                        </div>
                    `);
                    }
                })
                .catch(error => {
                    console.error("Error fetching fee data:", error);
                    $('#fees-report-container').html(`
                    <div class="no-data-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error loading fees data</p>
                    </div>
                `);
                });
        }

        function renderFeesTable(feesData) {
            const container = $('#fees-report-container');
            container.empty();

            const table = $(`
                    <div class="table-responsive">
                        <table class="fees-table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Academic Year</th>
                                    <th>Class</th>
                                    <th>Total Fees</th>
                                    <th>Date</th>
                                    <th>Amount Paid</th>
                                    <th>Exemption</th>
                                    <th>Payment Mode</th>
                                    <th>Received By</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                `);

            const tbody = table.find('tbody');

            feesData.forEach((entry, index) => {
                const year = entry.academic_year.stvacdm_year;
                const totalFees = parseFloat(entry.total_fees);
                const records = entry.records;
                const rowspan = records.length;

                let totalPaid = 0,
                    totalExempted = 0;

                if (index > 0) {
                    tbody.append('<tr class="academic-gap"><td colspan="9"></td></tr>');
                }

                records.forEach((record, recIndex) => {
                    const amount = parseFloat(record.sprfees_amount) || 0;
                    const exemption = parseFloat(record.sprfees_exumption) || 0;
                    totalPaid += amount;
                    totalExempted += exemption;

                    const row = $('<tr></tr>');

                    if (recIndex === 0) {
                        row.append(`<td rowspan="${rowspan}">${year}</td>`);
                        row.append(`<td rowspan="${rowspan}">${record.STVCLAS_standard} - ${record.STVCLAS_class}</td>`);
                        row.append(`<td rowspan="${rowspan}">₹${totalFees}</td>`);
                    }

                    row.append(`<td>${record.sprfees_feesdate}</td>`);
                    row.append(`<td class="${amount === 0 ? 'text-danger' : 'text-success'}">₹${amount}</td>`);
                    row.append(`<td class="${exemption > 0 ? 'text-warning' : ''}">₹${exemption}</td>`);
                    row.append(`<td>${record.payment_mode}</td>`);
                    row.append(`<td>${record.spruser_name}</td>`);
                    row.append(`<td>${record.sprfees_source || '-'}</td>`);

                    tbody.append(row);
                });

                const balance = totalFees - (totalPaid + totalExempted);

                const summaryRow = $(`
                        <tr class="summary-row">
                            <td colspan="9">
                                <div class="d-flex justify-content-between text-sm">
                                    <div><strong>Total Fees:</strong> ₹${totalFees}</div>
                                    <div><strong>Total Paid:</strong> ₹${totalPaid}</div>
                                    <div><strong>Exempted:</strong> ₹${totalExempted}</div>
                                    <div><strong>Balance:</strong> 
                                        <span class="${balance > 0 ? 'text-danger' : 'text-success'}">₹${balance}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                tbody.append(summaryRow);
            });

            container.append(table);
        }
    </script>

</body>

</html>