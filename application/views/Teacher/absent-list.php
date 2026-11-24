<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title> | Olive English School</title>

    <!-- Meta -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />





    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">

    <!-- Boxicons CSS -->

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>



<body class="">

    <!-- [ Pre-loader ] start -->

    <div class="loader-bg">

        <div class="loader-track">

            <div class="loader-fill"></div>

        </div>

    </div>

    <!-- [ Pre-loader ] End -->

    <?php include 'sidebar.php'; ?>

    <!-- [ Header ] start -->

    <?php include 'topNav.php'; ?>

    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="pcoded-content">
            <input type="hidden" id="getFullScreenMode">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Month</label>
                        <select name="month" id="month" class="form-control form-control-sm">
                            <option value="">Select</option>
                            <?php
                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            for ($i = 1; $i <= 12; $i++) : ?>
                                <option <?= $selected_month == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $months[$i - 1] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Year</label>
                        <select name="year" id="year" class="form-control form-control-sm">
                            <option value="">Select</option>

                            <?php
                            $start_year = 2022;
                            $end_year = date('Y');
                            for ($i = $start_year; $i <= $end_year; $i++) : ?>
                                <option <?= $selected_year == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <label for=""><small>Click to View</small></label>
                    <button class="btn btn-sm btn-success pb-0 w-100" type="button" id="view">View</button>
                </div>
            </div>
            <style>
                .table thead tr th,
                .table tbody tr td {
                    font-size: 10px !important;
                    padding: 5px !important;
                }
            </style>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <?php
                                    $currentMonth = sprintf("%02d", $selected_month); // Get the current month (without leading zeros)
                                    $currentYear = $selected_year;  // Get the current year
                                    $days = date('t', strtotime("$currentYear-$currentMonth-01"));
                                    for ($i = 1; $i <= $days; $i++) :
                                        $i = sprintf("%02d", $i);
                                        $givenDate = $currentYear . '-' . $currentMonth . '-' . $i; // Replace with your given date in YYYY-MM-DD format
                                        $dayOfWeek = date('w', strtotime($givenDate));
                                        $sunday = date('w', strtotime($givenDate));
                                    ?>
                                        <th class="<?= $sunday == '0' ? 'bg-danger text-white' : '' ?>"><?= $i ?></th>
                                    <?php endfor; ?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_students = count($all_students);
                                foreach ($all_students as $student) :
                                    $student_id = $student['id'];
                                    $student_name = "$student[SPRIDEN_surname] $student[SPRIDEN_student_name] $student[SPRIDEN_father_name]";
                                ?>
                                    <tr>
                                        <td><?= $student['STVCLAS_rollno'] ?></td>
                                        <td><?= "$student[SPRIDEN_surname] $student[SPRIDEN_student_name] $student[SPRIDEN_father_name]" ?></td>
                                        <?php
                                        for ($i = 1; $i <= $days; $i++) :
                                            $attendanceStatus = null;
                                            $comment = '';
                                            $date_of_absent = null;
                                            $i = sprintf("%02d", $i);
                                            $givenDate = $currentYear . '-' . sprintf("%02d", $selected_month) . '-' . $i; // Replace with your given date in YYYY-MM-DD format
                                            $dayOfWeek = date('w', strtotime($givenDate));
                                            $sunday = date('w', strtotime($givenDate));
                                        ?>
                                            <td class="<?= $sunday == '0' ? 'alert-danger text-white' : '' ?>">
                                                <?php
                                                if ($sunday == '0')
                                                    echo "<span class='badge badge-danger'>S</span>";
                                                else {
                                                    foreach ($attendance_data as $at) {
                                                        if ($at['SPRATND_student_id'] == $student['id'] && $at['SPRATND_date'] == $givenDate) {
                                                            $attendanceStatus = $at['SPRATND_status'];
                                                            $comment = $at['SPRATND_comment'];
                                                            $date_of_absent = date('d M, Y', strtotime($at['SPRATND_date']));
                                                            break;
                                                        }
                                                    }
                                                    if ($attendanceStatus == 'P')
                                                        echo "<span class='border font-weight-bold text-success'>$attendanceStatus</span>";
                                                    if ($attendanceStatus == 'A') {
                                                        echo "<span style='cursor:pointer;' onclick=\"showModal(this, '{$student['SPRIDEN_surname']}', '{$student['SPRIDEN_student_name']}', '{$student['SPRIDEN_father_name']}')\" data-id='$student_id' data-name='$student_name' data-date='$date_of_absent' data-comment='$comment' class='border font-weight-bold text-danger cursor-pointer'>$attendanceStatus</span>";

                                                    }
                                                } ?>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">

                </div>
            </div>
        </div>
    </div>


    <!-- Required Js -->

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        async function showModal(element, surname= null, student_name= null, father_name = null) {
            $('#exampleModal').modal('show');
            let ids = element.getAttribute("data-id");
            let st_name = element.getAttribute("data-name");
            let comment = element.getAttribute("data-comment");
            let date_of = element.getAttribute("data-date");
            let str = '';
            $('#exampleModalLabel').html(st_name);
            let url = `<?= base_url() ?>dashboard/fetchWhatsappNumber?ids=${ids}`;
            const response = await fetch(url);
            const data = await response.json();
            let whatsapp = data?.whatsapp;
            if (whatsapp != null) {
                let textToSend = `
\t Assalamualaikum ${surname} ${father_name}, We would like to inform you that your child, ${surname} ${student_name} ${father_name} , was absent from school today on ${date_of}. We encourage you to ensure that your child catches up on any missed work to stay on track with their studies. Thank you for your attention to this matter.
\n
\t आपको इत्तिला दी जाती है के आपका बच्चा, ${surname} ${student_name} ${father_name}, आज ${date_of} को स्कूल से ग़ैर हाज़िर है। गुजारिश है कि आप ध्यान दें और आज के छूटे हुए सबक को पूरा कराएं। आपकी तवज्जो का शुक्रिया।`;
                let url_to_send = `https://api.whatsapp.com/send?text=${encodeURIComponent(textToSend)}&phone=+91${whatsapp}`;
                str = `<div class="form-group">
                                <label for="">Whatsapp Number</label>
                                <input type="text" class="form-control" readonly id="whatsapp_number" value="${whatsapp}">
                                </div>
                                <div class="alert alert-secondary">
                                ${textToSend}
                                </div>
                                <a onclick="addToActivity(${ids})" href="${url_to_send}" target="_blank" class="btn btn-success m-auto">Send Message</a>`;
            } else {
                str = `<div class="form-group">
                                <label for="">Whatsapp Number</label>
                                <input type="text" class="form-control" readonly id="whatsapp_number" value="Not Found">
                                </div>
                                <div class="alert alert-secondary">
                                ${textToSend}
                                </div>
                                <a class="btn btn-danger m-auto">Whatsapp Number Not Found</a>`;
            }
            $('#modal-body').html(str);


        }

        var url = '<?= base_url() ?>dashboard/student_attendance/';
        document.getElementById("view").addEventListener("click", function() {
            let month = document.getElementById("month").value;
            let year = document.getElementById("year").value;
            if (month == '') {
                alert("Select Month");
                return;
            }
            if (year == '') {
                alert("Select Year");
                return;
            }
            window.location = `${url}${month}/${year}`;
        })

        // Updated 2024 Jul4 23

        function addToActivity(sid) {
            $.ajax({
                url: "<?= base_url() ?>Dashboard/addActivity",
                method: "GET",
                data: {
                    source: 'absent-sms',
                    sid: sid
                },
                success: function(res) {
                    console.log(res);
                }
            })
        }
    </script>
</body>



</html>