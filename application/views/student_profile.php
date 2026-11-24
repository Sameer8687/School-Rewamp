<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Profile</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />

    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/profile-student.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>

<body class="profile-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-profile user-card mb-4">
                    <div class="card-header border-0 p-0 pb-0">
                        <div class="cover-img-block">
                            <img src="<?= base_url(); ?>images/olive1.jpg" alt="" class="img-fluid olive-cover-img" />
                            <div class="overlay"></div>
                        </div>
                    </div>
                    <div class="card-body body-bg py-0">
                        <div class="user-about-block m-0">
                            <div class="row pb-2">
                                <div class="col-md-4 text-center mt-n5">
                                    <div class="change-profile text-center">
                                        <div class="dropdown w-auto d-inline-block">
                                            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <div class="profile-dp">
                                                    <div class="position-relative d-inline-block">
                                                        <img class="img-radius img-fluid wid-100" src="<?= base_url(); ?>images/user.png" alt="User image" />
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h5 class="mb-1 text-olive">
                                        <?php
                                        echo $spriden_details['SPRIDEN_surname'] . " " . $spriden_details['SPRIDEN_student_name'] . " " . $spriden_details['SPRIDEN_father_name'];
                                        ?>
                                    </h5>
                                    <p class="mb-2 text-olive">
                                        <?php
                                        echo "ID : " . $spriden_details['SPRIDEN_student_id'];
                                        ?>
                                    </p>
                                    <?php
                                    $ip_check = array(
                                        '127.0.0.1',
                                        '::1'
                                    );

                                    if (!in_array($_SERVER['REMOTE_ADDR'], $ip_check)) {
                                        $quiz_url = "http://quiz.oliveschool.org.in/student?student-id=" . $spriden_details['SPRIDEN_student_id'];
                                    } else {
                                        $quiz_url = "https://localhost/oliveQuiz/student?student-id=" . $spriden_details['SPRIDEN_student_id'];
                                    }
                                    ?>
                                    <a href="<?= base_url(); ?>profile/logout" class="btn btn-olive btn-sm" style="border-radius: 20px">
                                        Logout</a>
                                    <a target="_blank" href="<?= $quiz_url; ?>" class="btn btn-olive btn-sm" style="border-radius: 20px">
                                        Take Quiz</a>
                                </div>
                                <div class="col-md-8 mt-md-4">
                                    <div class="row">
                                        <div class="col-md-6 custom-margin-small-2">
                                            <h6 class="bg-olive text-white p-1 olive-h6-title pl-3">
                                                Student Details
                                            </h6>
                                            <a href="#!" class="mb-1 text-olive d-flex align-items-end text-h-primary"><i class="feather icon-user mr-2 f-18"></i> Father
                                                Name :
                                                <?= $spriden_details['SPRIDEN_surname'] . " " . $spriden_details['SPRIDEN_father_name']; ?>
                                            </a>
                                            <div class="clearfix"></div>
                                            <a href="mailto:demo@domain.com" class="mb-1 text-olive d-flex align-items-end text-h-primary"><i class="feather icon-grid mr-2 f-18"></i> Class :
                                                <?php
                                                if ($stvclas_data != null) {
                                                    echo  $stvclas_data['STVCLAS_standard'] . " - " . $stvclas_data['STVCLAS_class'];
                                                } else {
                                                    echo "Not Assigned";
                                                }
                                                ?>
                                            </a>
                                            <div class="clearfix"></div>
                                            <a href="#!" class="mb-1 text-olive d-flex align-items-end text-h-primary"><i class="feather icon-credit-card mr-2 f-18"></i>
                                                Aadhar No. :
                                                <?= $spriden_details['SPRIDEN_aadhar_no']; ?>
                                            </a>
                                        </div>
                                        <div class="col-md-6 custom-margin-small-2">
                                            <h6 class="bg-olive text-white p-1 olive-h6-title pl-3">
                                                Teacher Details
                                            </h6>
                                            <a href="#!" class="mb-1 text-olive d-flex align-items-end text-h-primary"><i class="feather icon-users mr-2 f-18"></i>
                                                Teacher Name : <?= $teacher_data['SPRIDEN_surname'] . " " . $teacher_data['SPRIDEN_student_name'] . " " . $teacher_data['SPRIDEN_father_name']; ?>
                                            </a>
                                            <div class="clearfix"></div>
                                            <a href="mailto:demo@domain.com" class="mb-1 text-olive d-flex align-items-end text-h-primary"><i class="feather icon-message-square mr-2 f-18"></i>
                                                Message : +91 90215 94215
                                            </a>
                                            <div class="clearfix"></div>
                                            <a href="#!" class="mb-1 text-olive d-flex align-items-end text-h-primary"><i class="feather icon-phone-call mr-2 f-18"></i>
                                                Call : +91 90215 94215
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <?php

                                $today = date('Y-m-d');
                                $first_day = date('Y-m-01', strtotime($today));
                                $firstDayFormat = date('d M', strtotime($first_day));

                                $last_day = date('Y-m-t', strtotime($today));
                                $lastDayFormat = date('d M', strtotime($last_day));
                                ?>
                                <h5 class="text-white">
                                    Analysis -
                                    <span class="text-white">Attendance From
                                        <?= $firstDayFormat; ?>
                                        to
                                        <?= $lastDayFormat; ?>
                                        <?= date('Y'); ?></span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="attendance-container">
                                            <?php

                                            for ($i = 0; $i < count($attendance_details); $i++) {
                                                $icon = date('d', strtotime($attendance_details[$i]['SPRATND_date']));
                                                if ($attendance_details[$i]['SPRATND_status'] == "P") {
                                                    $class = "bg-success";
                                                } else {
                                                    $class = "bg-danger";
                                                }

                                            ?>
                                                <span class="attendance-status <?= $class; ?> text-white">
                                                    <?= $icon; ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="attendance-chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card table-card review-card">
                            <div class="card-header borderless bg-olive text-white" style="background-color: #156399">
                                <h5 class="text-white">Teacher's Remark</h5>
                            </div>
                            <div class="card-body pb-0">
                                <style>
                                    #remarkTable tr td,
                                    #remarkTable tr th {
                                        padding: 5px;
                                        font-size: 12px;
                                    }
                                </style>
                                <div class="table-responsive">
                                    <table class="table table-bordered pb-0 mb-0" id="remarkTable">
                                        <thead>
                                            <tr>

                                                <th>Teacher Remark</th>
                                                <th>Date</th>
                                                <th>Feedback</th>
                                                <th>Feedback Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($remark_details as $remark_detail) {
                                                if ($remark_detail['stvrmrk_date_of_reply'] == "0000-00-00"){
                                                    $newDate = " ";
                                                }else{
                                                    $newDate = date('d M Y', strtotime($remark_detail['stvrmrk_date_of_reply']));
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= $remark_detail['stvrmrk_remark'] ?></td>
                                                    <td><?= date('d M Y', strtotime($remark_detail['stvrmrk_date'])) ?></td>
                                                    <td><?= $remark_detail['stvrmrk_reply'] ?></td>
                                                    <td><?= $newDate; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card table-card">
                            <div class="card-header bg-warning">
                                <h5 class="text-white">Quiz Analysis</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Quiz</th>
                                                <th>Date</th>
                                                <th>Marks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($quiz_details as $quiz_dt) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-inline-block align-middle">
                                                            <p class="text-muted m-b-0">
                                                                <?= $quiz_dt['quiz_name']; ?>
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?= date('d M Y', strtotime($quiz_dt['exam_date'])); ?>
                                                    </td>
                                                    <td>
                                                        <?= $quiz_dt['marks_scored']; ?>
                                                        /
                                                        <?= $quiz_dt['marks_scored']; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <?php
                                        if (
                                            count($quiz_details) >
                                            3
                                        ) { ?>
                                            <a href="#!" class="b-b-primary text-primary">View All</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card myfeesCard">
                            <div class="card-header bg-secondary">
                                <h5 class="text-white">Fees Details</h5>
                            </div>
                            <div class="card-body pb-1 mb-0 pb-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p style="margin-top: -20px"></p>
                                    </div>
                                    <?php
                                    if ($visiting_details != null) {
                                        $total_fees = 4000;
                                        $paid_fees = $visiting_details['visiting_student_fees_paid'];
                                        $balance_fees = $total_fees - $paid_fees;
                                    }
                                    ?>
                                    <div class="col-md-12 text-center f-w-600 pt-3">
                                        <div class="alert alert-info">
                                            <?php
                                            if ($visiting_details != null) {
                                            ?>
                                                <?= $visiting_details['visiting_fees_status_of']; ?>
                                            <?php } else { ?>
                                                No Data Available
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                    if ($visiting_details != null) {
                                        $msg_fees = '';
                                        if ($balance_fees == 0) {
                                            $msg_fees = "Paid";
                                        } else if ($paid_fees == 0) {
                                            $msg_fees = "Hold";
                                        } else {
                                            $msg_fees = "Continue";
                                        }
                                    }
                                    ?>
                                    <?php
                                    if ($visiting_details != null) {
                                    ?>
                                        <div class="col-md-12 text-center mt-3 pb-1">
                                            <a class="btn btn-info text-white">
                                                <?= $msg_fees; ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card task-card">
                            <div class="card-header bg-success">
                                <h5 class="text-white">Events</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled task-list">
                                    <li>
                                        <i class="feather icon-check f-w-600 task-icon bg-success"></i>
                                        <p class="m-b-5">8:50</p>
                                        <h6 class="text-muted">Extra Class on Tomorrow</h6>
                                    </li>
                                    <li>
                                        <i class="task-icon bg-primary"></i>
                                        <p class="m-b-5">Thu, 31 Dec</p>
                                        <h6 class="text-muted">Friday there will be a holiday</h6>
                                    </li>
                                    <li>
                                        <i class="task-icon bg-danger"></i>
                                        <p class="m-b-5">Wed, 30 Dec</p>
                                        <h6 class="text-muted">Complete your fair book</h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        $(document).ready(function() {
            var present_days_in = "<?= $present_days; ?>";
            var absent_days_in = "<?= $absent_days; ?>";
            var int_pre = parseInt(present_days_in);
            var int_abs = parseInt(absent_days_in);
            // [ satisfaction-chart ] start
            $(function() {
                var options = {
                    chart: {
                        height: 260,
                        type: "pie",
                    },
                    series: [int_pre, int_abs],
                    labels: ["Present", "Absent"],
                    legend: {
                        show: true,
                        offsetY: 50,
                    },
                    dataLabels: {
                        enabled: true,
                        dropShadow: {
                            enabled: false,
                        },
                    },
                    colors: ["#17C666", "#EA4D4D"],
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 320,
                            },
                            legend: {
                                position: "bottom",
                                offsetY: 0,
                            },
                        },
                    }, ],
                };
                var chart = new ApexCharts(
                    document.querySelector("#attendance-chart"),
                    options
                );
                chart.render();
            });
            // [ satisfaction-chart ] end
        });
    </script>
</body>

</html>
