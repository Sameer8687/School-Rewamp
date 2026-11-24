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

    <?php include 'application/views/sidebar.php'; ?>
    <?php include 'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row" id="alertRow" style="display:none;">
                <div class="col-md-12">
                    <div id="alertBox" class="alert mb-2"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 pr-1">
                    <div class="card mb-2">
                        <div class="card-header">
                            <div class="col-md-6 p-0">
                                <h5 class="card-title">Select Student From</h5>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-outline-secondary float-right" id="load_students"
                                    type="submit">View Students</button>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="">Acdm Year <span class="float-right">:</span></label>
                                    <select name="from_acdm" id="from_acdm" class="form-control form-control-sm ">
                                        <option value="">Select Year</option>
                                        <?php foreach ($academic_years as $acdm) { ?>
                                            <option <?php echo $selected_academic_year == $acdm['stvacdm_id'] ? 'selected' : ''; ?>
                                                value="<?= $acdm['stvacdm_id'] ?>">
                                                <?= $acdm['stvacdm_year'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="">Class
                                        <span class="float-right">:</span></label>
                                    <select name="from_std" id="from_std" class="form-control form-control-sm ">
                                        <option value="">Select Class</option>
                                        <?php foreach ($classes as $clas) { ?>
                                            <option value="<?= $clas['stvfees_class'] ?>">
                                                <?= $clas['stvfees_class'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="">Div
                                        <span class="float-right">:</span></label>
                                    <select name="from_div" id="from_div" class="form-control form-control-sm ">
                                        <option value="">Select Div</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 pl-1">
                    <div class="card mb-2">
                        <div class="card-header">
                            <div class="col-md-6 p-0">
                                <h5 class="card-title">Promote Students To</h5>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-sm btn-outline-secondary float-right" id="promote_students"
                                    type="submit">Promote Students</button>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="">Acdm Year <span class="float-right">:</span></label>
                                    <select name="to_acdm" id="to_acdm" class="form-control form-control-sm ">
                                        <option value="">Select Year</option>
                                        <?php foreach ($academic_years as $acdm) { ?>
                                            <option <?php echo $selected_academic_year == $acdm['stvacdm_id'] ? 'selected' : ''; ?>
                                                value="<?= $acdm['stvacdm_id'] ?>">
                                                <?= $acdm['stvacdm_year'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="">Class
                                        <span class="float-right">:</span></label>
                                    <select name="to_std" id="to_std" class="form-control form-control-sm ">
                                        <option value="">Select Class</option>
                                        <?php foreach ($classes as $clas) { ?>
                                            <option value="<?= $clas['stvfees_class'] ?>">
                                                <?= $clas['stvfees_class'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="">Div
                                        <span class="float-right">:</span></label>
                                    <select name="to_div" id="to_div" class="form-control form-control-sm ">
                                        <option value="">Select Div</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-2">
                        <div class="card-header">

                            <div class="col-md-3">
                                Count = <span id="selected_count" class="badge badge-primary">0</span>
                            </div>

                            <div class="col-md-3">
                                Promote = <span id="promote_count" class="badge badge-primary">0</span>
                            </div>

                            <div class="col-md-3">
                                Skip Student = <span id="skip_count" class="badge badge-primary">0</span>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">

                                        <table id="" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="check_all">
                                                    </th>
                                                    <th class="text-capitalize">Sr.No</th>
                                                    <th class="text-capitalize">Student ID</th>
                                                    <th class="text-capitalize">Student Name</th>
                                                    <th class="text-capitalize">Roll No</th>
                                                    <th class="text-capitalize">Class</th>
                                                    <th class="text-capitalize">Div</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
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

        function showAlert(message, type = "success") {

            $("#alertBox")
                .removeClass("alert-success alert-danger alert-warning alert-info")
                .addClass("alert-" + type)
                .html(message);

            $("#alertRow").stop(true, true).fadeIn();

            $('html, body').animate({
                scrollTop: $("#alertRow").offset().top - 20
            }, 300);

            setTimeout(function () {
                $("#alertRow").fadeOut();
            }, 3000);
        }
        // -------------------------
        function updateSelectedCount() {
            var count = $(".student_checkbox:checked").length;
            $("#selected_count").text(count);
        }

        $(document).on("change", ".student_checkbox", function () {

            var total = $(".student_checkbox").length;
            var checked = $(".student_checkbox:checked").length;

            $("#check_all").prop("checked", total === checked);

            updateSelectedCount();
        });
        // -------------------------
        // check all
        $(document).on("change", "#check_all", function () {
            $(".student_checkbox").prop("checked", $(this).prop("checked"));
            updateSelectedCount();
        });

        // load students
        $("#load_students").click(function () {
            var year = $("#from_acdm").val();
            var clas = $("#from_std").val();
            var div = $("#from_div").val();
            if (year == "" || clas == "") {
                showAlert("Please select Academic Year, Class and Division.", "warning");
                return;
            }
            $.ajax({
                url: "<?= base_url('Student/getPromotionStudents'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    year: year,
                    class: clas,
                    div: div
                },
                success: function (response) {
                    var html = "";
                    var i = 1;

                    $.each(response, function (index, student) {
                        html += "<tr>";
                        html += '<td><input type="checkbox" class="student_checkbox" value="' + student.id + '" data-rollno="' + student.STVCLAS_rollno + '"></td>';
                        html += "<td>" + i++ + "</td>";
                        html += "<td>" + student.SPRIDEN_student_id + "</td>";
                        html += "<td>" + student.SPRIDEN_student_name + "</td>";
                        html += "<td>" + student.STVCLAS_rollno + "</td>";
                        html += "<td>" + student.STVCLAS_standard + "</td>";
                        html += "<td>" + student.STVCLAS_class + "</td>";
                        html += "</tr>";
                    });
                    $("#tbody").html(html);
                    $("#check_all").prop("checked", false);
                    updateSelectedCount();

                },
                error: function () {
                    showAlert("Unable to load students.", "danger");
                }
            });
        });

        // promote students
        $("#promote_students").click(function () {
            var students = [];
            var rollnos = [];
            $(".student_checkbox:checked").each(function () {
                students.push($(this).val());
                rollnos.push($(this).data("rollno"));
            });
            if (students.length == 0) {
                showAlert("Please select at least one student.", "warning");
                return;
            }
            var to_acdm = $("#to_acdm").val();
            var to_std = $("#to_std").val();
            var to_div = $("#to_div").val();
            if (to_acdm == "" || to_std == "" || to_div == "") {
                showAlert("Please select Promotion Academic Year, Class and Division.", "warning");
                return;
            }
            $.ajax({
                url: "<?= base_url('Student/promoteStudents'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    students: students,
                    rollnos: rollnos,
                    acdm: to_acdm,
                    std: to_std,
                    div: to_div
                },
                success: function (response) {

                    $("#promote_count").text(response.promoted);
                    $("#skip_count").text(response.skipped);

                    if (response.skipped > 0) {

                        showAlert(
                            "Promotion Completed. Promoted: " +
                            response.promoted +
                            " | Skipped: " +
                            response.skipped,
                            "warning"
                        );

                    } else {

                        showAlert(
                            "All selected students promoted successfully.",
                            "success"
                        );

                    }

                    setTimeout(function () {
                        $("#load_students").click();
                    }, 1000);
                },
                error: function () {
                    showAlert("Promotion Failed.", "danger");
                }
            });
        });
    </script>
</body>

</html>