<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/student-profile-new.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar with student list -->
            <div class="col-md-3 sidebar">
                <h5 class="mb-3 text-royal"><i class="fas fa-users me-2 text-royal"></i>Choose Filters</h5>

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
                <button class="btn btn-primary view-btn" id="view-btn"><i class="fa-solid fa-magnifying-glass"></i> View</button>
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
                    <div class="text-center text-muted py-4 d-flex align-items-center justify-content-center flex-column">
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
        <div class="school-header d-flex align-items-center p-3">
            <img src="https://school.oliveschool.org.in/images/logoNewDev.png" alt="Olive School Logo" class="school-logo me-3">
            <div class="school-details">
                <h4 class="school-name mb-1">Olive English School & Jr. College</h4>
                <p class="school-address mb-0">Behind Traffic Police Station NH3, Dhule</p>
                <p class="school-contact mb-0">🌐 <a href="https://www.oliveschool.org.in" target="_blank">www.oliveschool.org.in</a></p>
            </div>
        </div>

        <div class="report-card gradient-royal">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="<?php echo base_url('assets/images/student-'); ?>default.svg" alt="Student Photo" class="profile-img mb-2">
                    <h4 id="student-name-header" class="text-white"></h4>
                    <p class="text-white" id="student-id-class"></p>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">Father's Name:</span>
                                <strong id="father-name" class="text-white"></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">Date of Birth:</span>
                                <strong id="dob" class="text-white"></strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">Class Teacher:</span>
                                <strong id="class-teacher" class="text-white"></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white">Contact:</span>
                                <strong id="contact" class="text-white"></strong>
                            </div>
                            <!-- <div class="d-flex justify-content-between mb-2">
                                <span>Total Fees:</span>
                                <span class="badge badge-success" id="total-fees"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Balance Fees:</span>
                                <span class="badge badge-warning" id="balance-fees"></span>
                            </div> -->
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <h5 class="text-white">Fees Details (Current Academic Year)</h5>
                            <table class="table table-sm">
                                <thead>
                                    <th class="text-royal">Academic Year</th>
                                    <th class="text-royal">Class</th>
                                    <th class="text-royal">Total Fees</th>
                                    <th class="text-royal">Paid Fees</th>
                                    <th class="text-royal">Exumption</th>
                                    <th class="text-royal">Balance Fees</th>
                                </thead>
                                <tbody id="current-fees-details">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="report-card mt-4">
            <div class="row mt-2">
                <div class="col-md-12">
                    <h5 class="text-royal"><i class="fas fa-money-bill-wave me-2 text-royal"></i> Fees Details (All Academic Years)</h5>
                    <div class="accordion" id="feesAccordion"></div>
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
            <div class="col-md-6">
                <div class="report-card h-100">
                    <h5 class="text-royal"><i class="fas fa-chart-bar me-2 text-royal"></i>Attendance Overview</h5>
                    <div class="chart-container mb-3" style="height: 200px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <h5 class="my-2 text-royal">Monthly Present Days</h5>
                    <div id="month-list-attendance" class="d-flex flex-wrap gap-2 overflow-auto"></div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="report-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-royal"><i class="fas fa-star me-2 text-royal"></i>Performance</h5>
                        <select id="exam-filter" class="form-select form-select-sm" style="width: auto; max-width: 200px;">
                            <option value="all" selected>All Exams</option>
                            <!-- Options will be added dynamically -->
                        </select>
                    </div>
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
                <h5 class="mb-2 me-3 text-royal">
                    <i class="fas fa-award me-2 text-royal"></i>Academic Marks
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
            <h5 class="text-royal"><i class="fas fa-comment me-2 text-royal"></i>Teacher Remarks</h5>
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
            </div>
            <div id="no-remarks-message" class="text-muted text-center py-3 d-none">
                <i class="fas fa-comment-slash fa-lg mb-2 d-block"></i>
                No remarks available
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
    <script src="assets/js/new/student-profile-new.js"></script>
</body>

</html>