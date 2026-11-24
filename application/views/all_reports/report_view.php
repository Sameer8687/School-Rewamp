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
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }

        .main-content {
            margin-left: 25%;
            padding: 20px;
        }

        .student-list {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            margin-top: 10px;
        }

        .student-card {
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 10px;
        }

        .student-card:hover {
            background-color: #e9ecef;
            transform: translateX(5px);
        }

        .report-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #6c757d;
            margin: 0 auto;
            display: block;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            min-height: 300px;
        }

        .performance-chart {
            width: 100% !important;
            height: 100% !important;
        }

        .badge-attendance {
            background-color: #1cc88a;
        }

        .badge-remark {
            background-color: #f6c23e;
        }

        .active-student {
            background-color: #4e73df !important;
            color: white !important;
        }

        .filter-row {
            margin-bottom: 15px;
            display: flex;
            gap: 2px;
        }

        .filter-row .form-control {
            flex: 1;
        }

        .view-btn {
            flex: 0 0 auto;
            width: auto;
            padding: 10px 15px;
        }
        
        .remark-status {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-new {
            background-color: #ffc107;
            color: #000;
        }
        .status-replied {
            background-color: #28a745;
            color: #fff;
        }
        
        .exam-card {
            border-left: 4px solid #4e73df;
            margin-bottom: 20px;
        }
        .subject-card {
            border-left: 3px solid #1cc88a;
            margin-bottom: 15px;
        }
        .marks-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
        }
        .progress {
            height: 10px;
        }
        .exam-header {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar with student list -->
            <div class="col-md-3 sidebar">
                <h3 class="text-center mb-4">Class Students</h3>

                <div class="filter-row">
                    <select class="form-control" id="academic-year-selector">
                        <option value="">Academic Year</option>
                        <!-- Years will be loaded via API -->
                    </select>

                    <select class="form-control" id="class-selector">
                        <option value="">Class</option>
                        <!-- Classes will be loaded via API -->
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
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <p>Select filters and click View</p>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <div class="col-md-9 main-content">
                <div id="student-report">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-user-graduate fa-5x mb-3"></i>
                        <h3>Select a student to view report</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variable to store chart instances
        var performanceChart = null;
        var attendanceChart = null;
        var monthlyAttendanceChart = null;

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
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3"></i>
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
                            studentList.append(`
                                <div class="card student-card" onclick="loadStudentData(${student.id}, ${academicYear}, '${classId}')">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-user-circle fa-2x"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">${student.student_name}</h5>
                                                <small class="text-muted"> ${student.SPRIDEN_student_id}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        studentList.html(`
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                                <p>No students found with these filters</p>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading students:', error);
                    $('#student-list').html(`
                        <div class="text-center text-danger py-5">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <p>Error loading students</p>
                            <small>${error}</small>
                        </div>
                    `);
                }
            });
        }

        function loadStudentData(studentId, academicYear, classId) {
            // Show loading state
            $('#student-report').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading student data...</p>
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
                                    }, { data: [] });
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
                            }, { data: [] }, { data: [] });
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading student data:', error);
                    $('#student-report').html(`
                        <div class="text-center text-danger py-5">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <p>Error loading student details</p>
                            <small>${error}</small>
                        </div>
                    `);
                }
            });
        }

        function renderStudentReport(studentId, academicYear, studentData, attendanceResponse, remarksResponse, marksResponse) {
            const {
                personal,
                fees,
                contact_details,
            } = studentData?.data;

            const studentClass = studentData?.data?.class;
            const {
                absent_days,
                present_days,
                total_days
            } = attendanceResponse?.data?.academic || { absent_days: 0, present_days: 0, total_days: 0 };

            // Update active state in student list
            $('.student-card').removeClass('active-student');
            $(`[onclick="loadStudentData(${studentId}, ${academicYear}, '${studentClass.class}')"]`).addClass('active-student');

            // Update the report view with student details
            $('#student-report').html(`
                <div class="report-card">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="<?php echo base_url('assets/images/student-'); ?>default.svg" alt="Student Photo" class="profile-img mb-3">
                            <h3>${personal.student_name || 'N/A'}</h3>
                            <p class="text-muted">${studentData.data.SPRIDEN_student_id || 'N/A'} | ${studentClass.class || 'N/A'}</p>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Father's Name:</span>
                                        <strong>${personal.SPRIDEN_father_name || 'N/A'}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Date of Birth:</span>
                                        <strong>${personal.SPRIDEN_birthdate || 'N/A'}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Contact:</span>
                                        <strong>${contact_details.stvpcnf_mobile || 'N/A'}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Class Teacher:</span>
                                        <strong>${studentClass.teacher_name || 'N/A'}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Fees:</span>
                                        <span class="badge bg-success text-white">
                                            ${fees.summary.total || 'N/A'}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Balance Fees:</span>
                                        <span class="badge bg-warning text-dark">
                                            ${fees.summary.balance || 'N/A'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="report-card">
                            <h4 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Attendance Overview</h4>
                            <div class="chart-container">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                            <div class="mt-3">
                                <p><i class="fas fa-circle text-success me-2"></i>Present: ${present_days || 0} days</p>
                                <p><i class="fas fa-circle text-danger me-2"></i>Absent: ${absent_days || 0} days</p>
                                <p><i class="fas fa-circle text-info me-2"></i>Total: ${total_days || 0} days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="report-card">
                            <h4 class="mb-4"><i class="fas fa-star me-2"></i>Performance</h4>
                            <div class="chart-container">
                                <canvas id="performanceChart" class="performance-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="report-card mt-4">
                    <h4 class="mb-4"><i class="fas fa-calendar-alt me-2"></i>Monthly Attendance</h4>
                    <div class="chart-container">
                        <canvas id="monthlyAttendanceChart"></canvas>
                    </div>
                </div>
                
                <div class="report-card mt-4">
                    <h4 class="mb-4"><i class="fas fa-award me-2"></i>Academic Marks</h4>
                    ${generateMarksCards(marksResponse.data || [])}
                </div>
                
                <div class="report-card mt-4">
                    <h4 class="mb-4"><i class="fas fa-comment me-2"></i>Teacher Remarks</h4>
                    ${generateRemarksTable(remarksResponse.data || [])}
                </div>
            `);

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
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Absent', 'Total'],
                    datasets: [{
                        data: [
                            present_days || 0,
                            absent_days || 0,
                            total_days || 0
                        ],
                        backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e'],
                        hoverBackgroundColor: ['#17a673', '#be2617', '#dda20a'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Draw performance chart
            var performanceCtx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: ['English', 'Maths', 'Marathi'], // Default subjects
                    datasets: [{
                            label: 'Obtained Marks',
                            backgroundColor: '#4e73df',
                            hoverBackgroundColor: '#2e59d9',
                            borderColor: '#4e73df',
                            data: [0, 0, 0], // Default values
                        },
                        {
                            label: 'Max Marks',
                            backgroundColor: '#e74a3b',
                            hoverBackgroundColor: '#be2617',
                            borderColor: '#e74a3b',
                            data: [100, 100, 100], // Default values
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Draw monthly attendance chart if data exists
            if (attendanceResponse.data && attendanceResponse.data.monthly) {
                renderMonthlyAttendanceChart(attendanceResponse.data.monthly);
            }

            // Update performance chart with actual marks data if available
            if (marksResponse.data && marksResponse.data.length > 0) {
                updatePerformanceChart(marksResponse.data);
            }
        }

        function generateMarksCards(examData) {
            if (!examData || examData.length === 0) {
                return '<p class="text-muted">No marks data available</p>';
            }

            let html = '';
            
            examData.forEach(exam => {
                // Group records by subject
                const subjects = {};
                exam.records.forEach(record => {
                    if (!subjects[record.stvsubj_subject]) {
                        subjects[record.stvsubj_subject] = {
                            written: { obtained: 0, total: 0 },
                            oral: { obtained: 0, total: 0 }
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

                html += `
                    <div class="exam-card card mb-4">
                        <div class="card-body">
                            <div class="exam-header">
                                <h5 class="mb-0">${exam.exam.exam_name}</h5>
                                <small class="text-muted">Exam Date: ${exam.records[0]?.sprmark_date || 'N/A'}</small>
                            </div>
                `;

                for (const [subject, marks] of Object.entries(subjects)) {
                    const totalObtained = marks.written.obtained + marks.oral.obtained;
                    const totalMarks = marks.written.total + marks.oral.total;
                    const percentage = totalMarks > 0 ? Math.round((totalObtained / totalMarks) * 100) : 0;
                    
                    html += `
                        <div class="subject-card card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">${subject}</h6>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Written:</span>
                                    <span>
                                        <span class="badge bg-primary marks-badge">${marks.written.obtained}/${marks.written.total}</span>
                                        ${marks.written.total > 0 ? 
                                            `<span class="badge bg-info marks-badge">${Math.round((marks.written.obtained / marks.written.total) * 100)}%</span>` : 
                                            ''}
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Oral:</span>
                                    <span>
                                        <span class="badge bg-primary marks-badge">${marks.oral.obtained}/${marks.oral.total}</span>
                                        ${marks.oral.total > 0 ? 
                                            `<span class="badge bg-info marks-badge">${Math.round((marks.oral.obtained / marks.oral.total) * 100)}%</span>` : 
                                            ''}
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Total:</span>
                                    <span>
                                        <span class="badge bg-success marks-badge">${totalObtained}/${totalMarks}</span>
                                        <span class="badge ${getPercentageBadgeClass(percentage)} marks-badge">${percentage}%</span>
                                    </span>
                                </div>
                                
                                <div class="progress">
                                    <div class="progress-bar ${getPercentageBarClass(percentage)}" 
                                         role="progressbar" 
                                         style="width: ${percentage}%" 
                                         aria-valuenow="${percentage}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                html += `</div></div>`;
            });

            return html;
        }

        function updatePerformanceChart(examData) {
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
                    datasets: [
                        {
                            label: 'Present Days',
                            data: presentData,
                            backgroundColor: 'rgba(75, 192, 192, 0.7)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Absent Days',
                            data: absentData,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Total Days',
                            data: totalData,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
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
                                text: 'Number of Days'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
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

        function generateRemarksTable(remarks) {
            if (!remarks || remarks.length === 0) {
                return '<p class="text-muted">No remarks available</p>';
            }

            var html = `
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Remark</th>
                                <th>Reply</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            $.each(remarks, function(index, remark) {
                const statusClass = remark.stvrmrk_status === 'New' ? 'status-new' : 'status-replied';
                const formattedDate = remark.stvrmrk_date !== '0000-00-00' ? remark.stvrmrk_date : 'N/A';
                const replyDate = remark.stvrmrk_date_of_reply !== '0000-00-00' ? remark.stvrmrk_date_of_reply : '';
                
                html += `
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${remark.stvrmrk_remark || 'N/A'}</td>
                        <td>
                            ${remark.stvrmrk_reply || 'No reply yet'}
                            ${replyDate ? `<small class="text-muted d-block">Replied on: ${replyDate}</small>` : ''}
                        </td>
                        <td>
                            <span class="remark-status ${statusClass}">
                                ${remark.stvrmrk_status || 'N/A'}
                            </span>
                        </td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;
            return html;
        }

        function getPercentageBadgeClass(percentage) {
            if (percentage >= 90) return 'bg-success';
            if (percentage >= 75) return 'bg-primary';
            if (percentage >= 50) return 'bg-warning text-dark';
            return 'bg-danger';
        }

        function getPercentageBarClass(percentage) {
            if (percentage >= 90) return 'bg-success';
            if (percentage >= 75) return 'bg-primary';
            if (percentage >= 50) return 'bg-warning';
            return 'bg-danger';
        }

        function calculateGrade(percentage) {
            percentage = parseFloat(percentage);
            if (percentage >= 90) return 'A+';
            if (percentage >= 80) return 'A';
            if (percentage >= 70) return 'B+';
            if (percentage >= 60) return 'B';
            if (percentage >= 50) return 'C';
            if (percentage >= 40) return 'D';
            return 'F';
        }

        function getGradeBadgeClass(grade) {
            switch (grade) {
                case 'A+':
                    return 'bg-success';
                case 'A':
                    return 'bg-primary';
                case 'B+':
                    return 'bg-info';
                case 'B':
                    return 'bg-secondary';
                case 'C':
                    return 'bg-warning text-dark';
                case 'D':
                    return 'bg-orange text-white';
                default:
                    return 'bg-danger';
            }
        }
    </script>
</body>
</html>