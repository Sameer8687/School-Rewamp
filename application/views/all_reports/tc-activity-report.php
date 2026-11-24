<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Activity Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Frank+Ruhl+Libre:wght@300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>css/tc-activity-report.css">
</head>


<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top mb-5">
        <div class="container-fluid">
            <!-- Brand with icon and better typography -->
            <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
                <i class="bi bi-journal-text me-2 fs-4"></i>
                <h5 class="d-none d-sm-inline mt-1">Teacher's Daily Activity Report</h5>
            </a>

            <!-- Mobile toggle button with animation -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav items with better spacing and hover effects -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-3">
                        <a class="nav-link d-flex align-items-center active" aria-current="page" href="<?= base_url() ?>">
                            <i class="bi bi-house-door me-1"></i>
                            <span class="d-none d-sm-inline">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="#" id="refresh-link">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            <span class="d-none d-sm-inline">Refresh</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-2 mt-5" style="background-color:#f8f9fa;">
        <div class="row pb-2 mt-2 align-items-center">
            <!-- From Date -->
            <div class="col-auto"> <!-- Changed to auto width column -->
                <div class="form-group mb-0 d-flex align-items-center"> <!-- Flex container -->
                    <label for="filter_date" class="mb-0 mr-2" style="width: 120px;"><i class="bi bi-calendar-week"></i> From Date:</label>
                    <input type="date" class="form-control form-control-sm" value="<?= $date ?>" id="filter_date" style="width: 150px;">
                </div>
            </div>

            <!-- To Date -->
            <div class="col-auto">
                <div class="form-group mb-0 d-flex align-items-center">
                    <label for="filter_date_to" class="mb-0 mr-2" style="width: 100px;"><i class="bi bi-calendar-week"></i> To Date:</label>
                    <input type="date" class="form-control form-control-sm" value="<?= $toDateValue ?>" id="filter_date_to" style="width: 150px;">
                </div>
            </div>

            <!-- Run Button -->
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button onclick="run()" class="btn btn-sm btn-success rounded-0">
                        <i class="bi bi-arrow-right-circle"></i> Run Report
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-light">
        <div class="row">
            <!-- Teacher List Column -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active" onclick="loadAllTeachers(this)">
                        <i class="bi bi-people-fill"></i> All Teachers
                    </a>
                    <?php
                    $i = 1;
                    foreach ($teachers as $tc):
                        $gender = $tc['SPRIDEN_gender'] == 'Male' ? 'Sir' : 'Miss';
                    ?>
                        <a href="#" class="list-group-item list-group-item-action"
                            onclick="loadTeacherData('<?= $tc['SPRIDEN_student_id'] ?>', this)">
                            <small ><?= $i . ' . ' . $gender . ' ' . $tc['teacher_name'] ?></small>
                        </a>
                    <?php
                        $i++;
                    endforeach; ?>
                </div>
            </div>

            <!-- Report Data Column -->
            <div class="col-md-9" id="reportDataContainer">
                <!-- Report data will be loaded here dynamically -->
                <?php if (!empty($activities)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr class="">
                                    <!-- <th class="fw-normal app-bg text-white text-center">#</th> -->
                                    <th class="fw-normal app-bg text-white text-center"><i class="bi bi-calendar3"></i> Attendance</th>
                                    <th class="fw-normal app-bg text-white text-center"><i class="bi bi-file-earmark-medical"></i> Remarks</th>
                                    <th class="fw-normal app-bg text-white text-center"><i class="bi bi-chat-left-text"></i> Absent Message</th>
                                    <th class="fw-normal app-bg text-white text-center"><i class="bi bi-laptop"></i> Quiz</th>
                                    <th class="fw-normal app-bg text-white text-center"><i class="bi bi-receipt"></i> Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 0;
                                foreach ($activities as $activity): ?>
                                    <tr>
                                        <!-- <td class="text-center"> <small><?= ++$counter; ?></small></td> -->
                                        <td class="cursor-pointer fw-normal text-center" onclick="attendanceSummary('<?= $activity['user_id'] ?>', '<?= $activity['date'] ?>')">
                                            <div class="circle">
                                                <small><?= $activity['total_attendance'] ?></small>
                                            </div>
                                        </td>
                                        <td class="cursor-pointer fw-normal text-center" onclick="remarkDetail('<?= $activity['user_id'] ?>', '<?= $activity['date'] ?>')">
                                            <div class="circle">
                                                <small><?= $activity['total_remark'] ?></small>
                                            </div>
                                        </td>
                                        <td class="cursor-pointer fw-normal text-center" onclick="absentSMSDetail('<?= $activity['user_id'] ?>', '<?= $activity['date'] ?>')">
                                            <div class="circle">
                                                <small><?= $activity['total_absent_sms'] ?></small>
                                            </div>
                                        </td>
                                        <td class="cursor-pointer fw-normal text-center" onclick="quizDetail('<?= $activity['user_id'] ?>', '<?= $activity['date'] ?>')">
                                            <div class="circle">
                                                <small><?= $activity['total_quiz'] ?></small>
                                            </div>
                                        </td>
                                        <td class="cursor-pointer fw-normal text-center" onclick="markDetail('<?= $activity['user_id'] ?>', '<?= $activity['date'] ?>')">
                                            <div class="circle">
                                                <small><?= $activity['total_marks'] ?></small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center p-5">
                        <img src="<?= base_url() ?>img/no-data.JPG" class="no-data" alt="">
                        <h6 class="text-danger fw-normal">
                            Please select a teacher or click "All Teachers" to view activities.
                        </h6>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Include modals -->
    <?php
    include_once 'tc-activity-report-modals/attendance-summary-modal.php';
    include_once 'tc-activity-report-modals/attendance-detail-modal.php';
    include_once 'tc-activity-report-modals/remark-detail-modal.php';
    include_once 'tc-activity-report-modals/absent-sms-details-modal.php';
    include_once 'tc-activity-report-modals/quiz-details-modal.php';
    include_once 'tc-activity-report-modals/mark-details-modal.php';
    ?>

    <div class="loader d-none" id="loader">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script>
        const base_url = "<?= base_url() ?>";
    </script>

    <script>
        // Add smooth scroll and refresh functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Refresh button functionality
            document.getElementById('refresh-link').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.reload();
            });

            // Add shadow when scrolling
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    document.querySelector('.navbar').classList.add('shadow');
                } else {
                    document.querySelector('.navbar').classList.remove('shadow');
                }
            });
        });

        function loadTeacherData(teacherId, element) {
            const fromDate = document.getElementById('filter_date').value;
            const toDate = document.getElementById('filter_date_to').value;

            // Show loader
            document.getElementById('loader').classList.remove('d-none');

            // Highlight selected teacher
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');

            // Fetch data via AJAX
            fetch(`${base_url}report/get_teacher_activities`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `teacher_id=${teacherId}&from_date=${fromDate}&to_date=${toDate}`
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('reportDataContainer').innerHTML = data;
                    document.getElementById('loader').classList.add('d-none');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loader').classList.add('d-none');
                });
        }

        function loadAllTeachers() {
            const fromDate = document.getElementById('filter_date').value;
            const toDate = document.getElementById('filter_date_to').value;

            // Show loader
            document.getElementById('loader').classList.remove('d-none');

            // Highlight "All Teachers"
            document.querySelectorAll('.list-group-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector('.list-group-item:first-child').classList.add('active');

            // Fetch data for all teachers
            fetch(`${base_url}report/get_teacher_activities`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `teacher_id=all&from_date=${fromDate}&to_date=${toDate}`
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('reportDataContainer').innerHTML = data;
                    document.getElementById('loader').classList.add('d-none');
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loader').classList.add('d-none');
                });
        }

        function run() {
            loadAllTeachers();
        }

        // Initialize with all teachers data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllTeachers();
        });

        // function loadAllTeachers() {
        //     const fromDate = document.getElementById('filter_date').value;
        //     const toDate = document.getElementById('filter_date_to').value;

        //     // Show loader
        //     document.getElementById('loader').classList.remove('d-none');

        //     // Fetch data for all teachers
        //     fetch(`${base_url}report/get_teacher_activities`, {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/x-www-form-urlencoded',
        //             },
        //             body: `teacher_id=all&from_date=${fromDate}&to_date=${toDate}`
        //         })
        //         .then(response => response.text())
        //         .then(data => {
        //             document.getElementById('reportDataContainer').innerHTML = data;
        //             document.getElementById('loader').classList.add('d-none');

        //             // Highlight "All Teachers"
        //             document.querySelectorAll('.list-group-item').forEach(item => {
        //                 item.classList.remove('active');
        //             });
        //             document.querySelector('.list-group-item:first-child').classList.add('active');
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //             document.getElementById('loader').classList.add('d-none');
        //         });
        // }

        function run() {
            // Default to loading all teachers when Run is clicked
            loadAllTeachers();
        }
    </script>

    <script src="<?= base_url() ?>js/tc-activity-report.js"></script>
</body>

</html>