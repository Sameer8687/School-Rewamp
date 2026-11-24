<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?= base_url() ?>">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets2/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets2/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets2/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets2/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets2/vendors/mdi/css/materialdesignicons.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css"> -->
    <link rel="stylesheet" href="assets2/vendors/datatables.net-bs5/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="assets2/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="assets2/js/select.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets2/css/style.css">
    <!-- endinject -->
    <!-- <link rel="shortcut icon" href="assets2/images/favicon.png" /> -->
    <link rel="shortcut icon" href="assets2/images/olive.jpg" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo me-5" href="<?= base_url() ?>"><img src="assets/images/olive.jpg"
                        class="me-2" alt="logo" /><span class="eighth"
                        style="font-family:Nunito, sans-serif; text-transform:capitalize;font-size: 18px;">Olive English
                        School</span></a>
                <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/olive.jpg"
                        class="me-2" alt="logo" /><span class="eighth"
                        style="font-family:Nunito, sans-serif; text-transform:capitalize;font-size: 18px;">Olive English
                        School</span></a>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold">
                                        <?= $student_info['SPRIDEN_surname'] . ' ' . $student_info['SPRIDEN_student_name'] . ' ' . $student_info['SPRIDEN_father_name']; ?>
                                    </h3>
                                    <h6 class="font-weight-normal mb-0">This Student Absent <span
                                            class="text-primary">Information</span>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card tale-bg">
                                <div class="card-people mt-auto">
                                    <img src="assets2/images/dashboard/people.svg" alt="people">
                                    <div class="weather-info">
                                        <div class="mb-0 font-weight-normal fs-4">Student ID :
                                            <?= $student_info['SPRIDEN_student_id'] ?>
                                        </div>

                                        <div class="d-flex mb-0 font-weight-normal fs-5">
                                            Class : <?= $std . ' - ' . $div ?>
                                        </div>
                                        <!-- <div class="d-flex">
                                            <div>
                                                <h2 class="mb-0 font-weight-normal"><i
                                                        class="icon-sun me-2"></i>31<sup>C</sup></h2>
                                            </div>
                                            <div class="ms-2">
                                                <h4 class="location font-weight-normal">Chicago</h4>
                                                <h6 class="font-weight-normal">Illinois</h6>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <p class="mb-4">Today’s Bookings</p>
                                            <p class="fs-30 mb-2">4006</p>
                                            <p>10.00% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Total Bookings</p>
                                            <p class="fs-30 mb-2">61344</p>
                                            <p>22.00% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Number of Meetings</p>
                                            <p class="fs-30 mb-2">34040</p>
                                            <p>2.00% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 stretch-card transparent">
                                    <div class="card card-light-danger">
                                        <div class="card-body">
                                            <p class="mb-4">Number of Clients</p>
                                            <p class="fs-30 mb-2">47033</p>
                                            <p>0.22% (30 days)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card" style="height: 100%;">
                                <div class="card-head p-3 d-flex justify-content-between align-items-center">
                                    <p class="card-title mb-0">Monthly Attendance</p>
                                    <div class="d-flex align-items-center">
                                        <div class="mb-0 card-title" style="color: #4747a1;">Total Absent = </div>
                                        <div class="mb-0 mx-2 card-title" style="color: #f3797e;">
                                            <?= $absent_details['total_absent'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <?php
                                    $i = 1;
                                    $month_num = [
                                        "jan" => 1,
                                        "feb" => 2,
                                        "mar" => 3,
                                        "apr" => 4,
                                        "may" => 5,
                                        "jun" => 6,
                                        "jul" => 7,
                                        "aug" => 8,
                                        "sep" => 9,
                                        "oct" => 10,
                                        "nov" => 11,
                                        "dec" => 12
                                    ];

                                    foreach ($absent as $abc):
                                        // Debug if needed
                                        // echo "<pre>"; print_r($abc); echo "</pre>";     
                                    
                                        $monthLower = !empty($abc['month_name']) ? strtolower($abc['month_name']) : '';
                                        $monthNumber = isset($month_num[$monthLower]) ? $month_num[$monthLower] : 0;
                                        $attendanceYear = $abc['year'];
                                        ?>
                                        <div class="row rounded align-items-center justify-content-between p-2 mb-2 month-card open-calendar-for-analysis"
                                            data-month="<?= $monthNumber ?>" data-attendance-year="<?= $attendanceYear ?>"
                                            style="background-color: #f0f1f3;cursor: pointer;">
                                            <div class="col-2">
                                                <p class="m-0"><?= $i++ ?></p>
                                            </div>
                                            <div class="col-4" style="color: #4747a1;">
                                                <h5 class="m-0"><?= $abc['month_name'] ?></h5>
                                            </div>
                                            <div class="col-3 text-center" style="color: #f3797e;">
                                                <h4 class="m-0"><?= $abc['total_absent'] ?></h4>
                                                <p class="m-0">Absent</p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                        <!-- data-bs-toggle="modal"
                        data-bs-target="#parentsMeetingModal" -->
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Notifications</p>
                                    <ul class="icon-data-list">
                                        <li>
                                            <div class="d-flex">
                                                <img src="assets2/images/faces/face1.jpg" alt="user">
                                                <div>
                                                    <p class="text-info mb-1">Isabella Becker</p>
                                                    <p class="mb-0">Sales dashboard have been created</p>
                                                    <small>9:30 am</small>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Calendar -->
                <!-- Bootstrap Modal -->
                <div class="modal fade" id="parentsMeetingModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Calendar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body position-relative">
                                <!-- Loading Spinner -->
                                <div id="calendarLoader"
                                    style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10; display:none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <!-- Calendar -->
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>






                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023.
                            Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin
                                template</a> from BootstrapDash. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made
                            with <i class="ti-heart text-danger ms-1"></i></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="assets2/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets2/vendors/chart.js/chart.umd.js"></script>
    <script src="assets2/vendors/datatables.net/jquery.dataTables.js"></script>
    <!-- <script src="assets2/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->
    <script src="assets2/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <script src="assets2/js/dataTables.select.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets2/js/off-canvas.js"></script>
    <script src="assets2/js/template.js"></script>
    <script src="assets2/js/settings.js"></script>
    <script src="assets2/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="assets2/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="assets2/js/dashboard.js"></script>
    <!-- <script src="assets2/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->

    <!-- Trigger Card -->
    <!-- <div class="open-calendar-for-analysis" style="cursor:pointer; padding:10px; background:#eee; width:200px;">
        Open Calendar
    </div> -->

    <!-- Modal -->
    <!-- <div id="parentsMeetingModal"
        style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
        <button id="closeModal">Close</button>
        <div id="calendar"></div>
    </div> -->

    <!-- FullCalendar CDN -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendar = null;
            var modalEl = document.getElementById('parentsMeetingModal');
            var loader = document.getElementById('calendarLoader');

            var id = "<?= $id ?>";
            var academic_year = "<?= $year ?>";

            function showModalAndReturnMode() {
                // prefer bootstrap 5 API if available
                if (window.bootstrap && bootstrap.Modal) {
                    var bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    bsModal.show();
                    return 'bootstrap';
                }
                // fallback to jQuery/Bootstrap4 modal if available
                if (window.jQuery && $(modalEl).modal) {
                    $(modalEl).modal('show');
                    return 'jquery';
                }
                // last-resort: just show the element (not ideal)
                modalEl.style.display = 'block';
                return 'none';
            }

            document.querySelectorAll('.open-calendar-for-analysis').forEach(function (card) {
                card.addEventListener('click', function () {
                    var month = parseInt(this.getAttribute('data-month'), 10);
                    var attendanceYear = parseInt(this.getAttribute('data-attendance-year'), 10);

                    loader.style.display = 'block';

                    var data = {
                        id: id,
                        year: academic_year,
                        month: month
                    };

                    // If CodeIgniter CSRF is enabled, include token (recommended)
                    <?php if ($this->config->item('csrf_protection')): ?>
                        data['<?= $this->security->get_csrf_token_name() ?>'] = '<?= $this->security->get_csrf_hash() ?>';
                    <?php endif; ?>

                    $.ajax({
                        url: "<?= base_url('Absent_student/get_daily_absent') ?>",
                        type: "POST",
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            // destroy previous calendar instance safely
                            if (calendar) {
                                try { calendar.destroy(); } catch (e) { console.warn(e); }
                                calendar = null;
                            }

                            var calendarEl = document.getElementById('calendar');
                            calendarEl.innerHTML = ''; // clear old DOM

                            calendar = new FullCalendar.Calendar(calendarEl, {
                                initialView: 'dayGridMonth',
                                height: 500,
                                initialDate: new Date(attendanceYear, month - 1, 1),
                                events: response
                            });

                            var mode = showModalAndReturnMode();

                            if (mode === 'bootstrap') {
                                // render only after modal is visible (prevents sizing issues)
                                modalEl.addEventListener('shown.bs.modal', function handler() {
                                    calendar.render();
                                    loader.style.display = 'none';
                                    modalEl.removeEventListener('shown.bs.modal', handler);
                                });
                            } else if (mode === 'jquery') {
                                $(modalEl).one('shown.bs.modal', function () {
                                    calendar.render();
                                    loader.style.display = 'none';
                                });
                            } else {
                                // fallback render
                                calendar.render();
                                loader.style.display = 'none';
                            }
                        },
                        error: function (xhr, status, err) {
                            loader.style.display = 'none';
                            console.error('AJAX error', status, err, xhr.responseText);
                            alert('Failed to load calendar data');
                        }
                    });
                });
            });
        });
    </script>

    <style>
        #calendar {
            min-height: 420px;
        }
    </style>


</body>

</html>