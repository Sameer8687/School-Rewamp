<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Issue LC</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css" />
    <style>
        .pc-container {
            top: 0px !important;
        }

        .border {
            border: 1px solid;
        }

        .table tr td,
        .table tr th {
            padding: 5px !important;
            font-size: 11px !important;
            cursor: pointer;
        }
    </style>
</head>

<body class="">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <?php include 'application/views/sidebar.php'; ?> <?php include
            'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Syllabus Calender</h5>

                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-6">Teachers Name
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-6" name="teacher_id"
                                                    id="teacher_id">
                                                    <option value="">Select</option>
                                                    <?php

                                                    foreach ($teachers as $teacher) {

                                                        ?>
                                                        <option <?= $teacher_id == $teacher['SPRIDEN_student_id'] ? 'selected' : '' ?> value="<?php echo $teacher['SPRIDEN_student_id']; ?>">
                                                            <?php echo $teacher['SPRIDEN_surname'] . " " . $teacher['SPRIDEN_student_name'] . " " . $teacher['SPRIDEN_father_name']; ?>
                                                        </option>

                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="std"
                                                    id="std">
                                                    <option value="">Select</option>
                                                    <?php foreach ($classes as $classs) { ?>
                                                        <option <?= $classs['stvfees_class'] == $class ? 'selected' : '' ?>
                                                            value="<?php echo $classs['stvfees_class']; ?>">
                                                            <?php echo $classs['stvfees_class']; ?>
                                                        </option>
                                                    <?php } ?>

                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Div
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="div"
                                                    id="div">

                                                    <option value="">Select</option>
                                                    <option <?= $div == 'A' ? 'selected' : '' ?> value="A">A</option>
                                                    <option <?= $div == 'B' ? 'selected' : '' ?> value="B">B</option>
                                                    <option <?= $div == 'C' ? 'selected' : '' ?> value="C">C</option>

                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                                id="view_button">View</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="table-gr" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <td class="fixed-column" style="width:9%;"></td>
                                                    <?php
                                                    $x = $start;
                                                    $y = $end;

                                                    $startDateObj = new DateTime($start);
                                                    $endDateObj = new DateTime($end);

                                                    // Create an array to store the list of dates
                                                    $dateList = array();

                                                    // Use a loop to iterate through the dates and add them to the array
                                                    for ($date = clone $startDateObj; $date <= $endDateObj; $date->modify('+1 day')) {
                                                        $dateList[] = $date->format('Y-m-d');
                                                    }
                                                    ?>

                                                    <?php foreach ($dateList as $date):
                                                        ?>
                                                        <td>
                                                            <?= date('l', strtotime($date)) ?> <br>
                                                            <?= date('d M Y', strtotime($date)) ?>
                                                        </td>

                                                    <?php endforeach; ?>
                                                </tr>
                                            </thead>

                                            <tbody id="slbs_report">
                                                <?php
                                                foreach ($subjects as $subj):
                                                    ?>
                                                    <tr>
                                                        <td class="fixed-column" style="font-size: 13px !important;">
                                                            <?= $subj['stvsubj_subject'] ?>
                                                        </td>
                                                        <!-- <td style="font-size: 13px !important;"><?= $subj['stvsubj_subject'] ?></td> -->
                                                        <?php
                                                        foreach ($dateList as $date):
                                                            $current_lessions = [];
                                                            foreach ($syllabus as $syl) {
                                                                if ($date == $syl['teaching_date'] && $syl['subject'] == $subj['stvsubj_id']) {
                                                                    $current_lessions[] = $syl;
                                                                }
                                                            }
                                                            ?>
                                                            <td style="padding: 0.2rem 0.2rem;">
                                                                <ul style="margin-top: 0;margin-bottom: 0rem;">
                                                                    <?php
                                                                    foreach ($current_lessions as $crls):
                                                                        ?>
                                                                        <li style="font-size: 13px !important;">
                                                                            <?= "$crls[title] : $crls[status]" ?>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            </td>

                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        $("#table-gr").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>

    <script>
        $("#view_button").click(function () {
            let std = $("#std").val();
            var div = document.getElementById("div").value;
            var teacher_id = document.getElementById("teacher_id").value;
            let base_url = '<?= base_url() ?>';
            window.location.href = `${base_url}Report/syllabus_calender?teacher_id=${teacher_id}&std=${std}&div=${div}`;


        });
    </script>

</html>