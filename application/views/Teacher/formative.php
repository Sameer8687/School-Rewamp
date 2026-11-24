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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

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

    <style>
        .btn-info {
            background-color: #26a69a !important;
        }

        .table.table-sm td,
        .table.table-sm th {
            padding: 0.3rem 0.3rem;
        }

        .btn-succe {
            background-color: #318eb9 !important;
            color: #fff;
        }

        .btn-inf {
            background-color: #26a69a !important;
            color: #fff;
        }
    </style>

    <!-- [ Main Content ] start -->
    <form id="Formevaluation">

        <div class="pc-container">

            <div class="row" style=" margin-right: 0px !important; margin-left: 0px !important;">

                <div class="col-md-12">

                    <div class="card-body p-0">

                        <div class="row">

                            <div class="col-md-12">

                                <table class="table table-sm table-borderless m-0">

                                    <tr>

                                        <td style="width: 40%;border-top: none;">Academic Year <span style="float:right">:</span> </td>

                                        <td style="width: 60%;border-top: none;">

                                            <select class="form-control form-control-sm" name="acdm" id="acdm">
                                                <option value="">Select Academic Year</option>
                                                <?php foreach ($academic_years as $academic_year) {

                                                    if ($academic_year['stvacdm_id'] == $academic_year['stvacdm_id']) {

                                                        $selected = 'selected';
                                                    } else {

                                                        $selected = '';
                                                    }

                                                ?>

                                                    <option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>">
                                                        <?= $academic_year['stvacdm_year']; ?></option>

                                                <?php } ?>
                                            </select>

                                            <input type="hidden" name="hidden_header_id" id="hidden_header_id" value="0">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="width: 40%;border-top: none;">Exam Name <span style="float:right">:</span> </td>

                                        <td style="width: 60%;border-top: none;">

                                            <select class="form-control form-control-sm" name="exam" id="exam">
                                                <option selected disabled value="">Select Exam</option>

                                                <?php

                                                if ($all_exams != null) {
                                                    foreach ($all_exams as $all_exam) {
                                                ?>

                                                    <option <?= $exam == $all_exam['stvexam_id'] ? 'selected' : '' ?> value="<?= $all_exam['stvexam_id']; ?>">
                                                            <?= $all_exam['stvexam_name']; ?>
                                                    </option>

                                                <?php }
                                                } ?>
                                            </select>

                                            <input type="hidden" name="hidden_header_id" id="hidden_header_id" value="0">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="width: 40%;border-top: none;">Standard <span style="float:right">:</span> </td>

                                        <td style="width: 60%;border-top: none;">

                                            <select class="form-control form-control-sm" name="std" id="std" onchange="GetSub(this)">
                                                <option value="">Select</option>
                                                <?php foreach ($classes as $classs) {  ?>
                                                    <option <?= $classs['stvfees_class'] == $class ? 'selected' : '' ?> value="<?php echo $classs['stvfees_class']; ?>">
                                                        <?php echo $classs['stvfees_class']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>

                                            <input type="hidden" name="hidden_header_id" id="hidden_header_id" value="0">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="width: 40%;border-top: none;">Division <span style="float:right">:</span> </td>

                                        <td style="width: 60%;border-top: none;">

                                            <select required name="division" class="form-control form-control-sm" id="division">

                                                <option value="" selected disabled> Select Division</option>

                                                <option <?= $div == 'A' ? 'selected' : '' ?> value="A">A</option>
                                                <option <?= $div == 'B' ? 'selected' : '' ?> value="B">B</option>
                                                <option <?= $div == 'C' ? 'selected' : '' ?> value="C">C</option>

                                            </select>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="width: 40%;border-top: none;">Subject <span style="float:right">:</span></td>

                                        <td style="width: 60%;border-top: none;">
                                            <select class="form-control form-control-sm" name="sub" id="sub">
                                                <option value=""> Select </option>
                                                <?php foreach ($subjects as $sub_value) : ?>
                                                    <option <?= $sub == $sub_value['stvsubj_id'] ? 'selected' : '' ?> value="<?= $sub_value['stvsubj_id'] ?>"><?= $sub_value['stvsubj_subject'] ?></option>
                                                <?PHP endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td style="width: 40%;border-top: none;">

                                            <a href="<?= base_url('Dashboard/evaluation') ?>" class="btn btn-sm btn-succe" type="button"><i class="bi bi-file-earmark-plus"></i> New Form</a>

                                        <td style="width: 60%;border-top: none; text-align: right;">

                                            <button class="btn btn-sm btn-succe" type="button" id="view_button">
                                                <span id="view_loader" style="display: none;">
                                                    <div class="spinner-border spinner-border-sm text-light" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </span>
                                                <span id="view_text">
                                                    <i class="bi bi-file-earmark-break"></i> View
                                                </span>
                                            </button>

                                            <button class="btn btn-sm btn-inf" type="submit" id="sub_button">
                                                <span id="sub_loader" style="display: none;">
                                                    <div class="spinner-border spinner-border-sm text-light" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </span>
                                                <span id="sub_text">
                                                    <i class="bi bi-file-earmark-check"></i> Add
                                                </span>
                                            </button>

                                        </td>

                                    </tr>

                                </table>

                            </div>

                        </div>

                    </div>


                    <style>
                        table thead tr .hello {
                            border: 1px solid #000;
                            color: #318eb9;
                            writing-mode: vertical-rl;
                            text-orientation: mixed;
                            white-space: nowrap;
                            height: auto;
                            transform: rotate(180deg);
                            padding: 5px !important;
                            text-align: center;
                            font-weight: 500;
                            font-size: 12px;
                        }


                        table thead tr .helloo {
                            color: #318eb9;
                            padding: 3px !important;
                            margin: auto;
                            text-align: center;
                            font-weight: 500;
                            font-size: 12px;
                        }

                        #lesson-table-body tr td.add-lesson-review {
                            cursor: pointer;
                        }

                        ::-webkit-scrollbar {
                            width: 2px;
                            height: 2px;
                        }

                        ::-webkit-scrollbar-thumb {
                            background-color: #318eb9 !important;
                            border-radius: 20px;
                        }

                        .small {
                            font-size: 100%;
                            font-weight: 400;
                            width: 100%;
                            border: none;
                        }

                        table.fm-table thead tr th,
                        table.fm-table tbody tr td {
                            border: 2px solid #77777760 !important;
                            font-size: 12px;
                            border-collapse: collapse !important;
                        }

                        .sticky {
                            position: sticky;
                            top: 0px;
                        }
                    </style>

                    <div class="card mt-2" style=" margin-bottom: 0px;">

                        <div class="card-body p-0 ">

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="table-responsive p-0">

                                        <div class="card" style="padding: 0px; height: 400px;overflow-x: auto;margin-bottom: 0px !important;">

                                            <table class="table fm-table table-bordered table-sm m-0" style=" margin-bottom: 0px;">

                                                <thead class="sticky">

                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th colspan="8" class="text-center helloo">Formative Evaluation (A)</th>
                                                        <th colspan="4" class="text-center helloo">Summative</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>

                                                    <tr>

                                                        <th class="hello">Roll No.</th>
                                                        <th class="helloo">Name Of Students</th>
                                                        <th class="hello">Daily Observ</th>
                                                        <th class="hello">Oral Work</th>
                                                        <th class="hello">Pract/Expt</th>
                                                        <th class="hello">Activity</th>
                                                        <th class="hello">Project</th>
                                                        <th class="hello">Test</th>
                                                        <th class="hello">C.W/H.W</th>
                                                        <th class="hello">Other</th>
                                                        <th class="hello">Total</th>
                                                        <th class="hello">Oral</th>
                                                        <th class="hello">Practical</th>
                                                        <th class="hello">Written</th>
                                                        <th class="hello">Total</th>
                                                        <th class="hello">A+B</th>
                                                        <th class="hello">Grade</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php
                                                    $total_students = count($students);
                                                    for ($i = 0; $i < $total_students; $i++) {
                                                        $index = $i + 1;
                                                    ?>

                                                        <tr>

                                                            <td class="p-0 m-0">

                                                                <input type="hidden" name="total_stud" id="total_stud" value="<?= $total_students ?>">

                                                                <input type="hidden" name="line_sid<?= $index ?>" id="line_sid<?= $index ?>" value="<?= $students[$i]['STVCLAS_student_id'] ?>">

                                                                <lable class="form-control form-control-sm text-left" style="border:none;" value="<?= $students[$i]['STVCLAS_rollno'] ?>" name="roll_no<?= $index ?>">
                                                                    <?= $students[$i]['STVCLAS_rollno'] ?></lable>

                                                            </td>

                                                            <td class="p-0 m-0">

                                                                <?php echo $students[$i]['SPRIDEN_surname'] . " " . $students[$i]['SPRIDEN_student_name'] . " " . $students[$i]['SPRIDEN_father_name'] ?>

                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" name="daily<?= $index ?>" id="daily<?= $index ?>" value="<?= $students[$i]['daily_observation'] ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_oral_work'] ?>" name="oral_w<?= $index ?>" id="oral_w<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_practical'] ?>" name="pract<?= $index ?>" id="pract<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_activity'] ?>" name="activity<?= $index ?>" id="activity<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_project'] ?>" name="project<?= $index ?>" id="project<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_test'] ?>" name="test<?= $index ?>" id="test<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_cw_hw'] ?>" name="cw<?= $index ?>" id="cw<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_other'] ?>" name="other<?= $index ?>" id="other<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['fm_total'] ?>" name="total<?= $index ?>" id="total<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['sm_oral'] ?>" name="oral<?= $index ?>" id="oral<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['sm_practical'] ?>" name="practical<?= $index ?>" <?= $index ?>id="practical">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['sm_written'] ?>" name="writen<?= $index ?>" id="writen<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['sm_total'] ?>" name="total_m<?= $index ?>" id="total_m<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['sm_a_b'] ?>" name="ab<?= $index ?>" id="ab<?= $index ?>">
                                                            </td>

                                                            <td class="p-0 m-0">
                                                                <input class="small" type="text" value="<?= $students[$i]['sm_grade'] ?>" name="grade<?= $index ?>" id="grade<?= $index ?>">
                                                            </td>

                                                        </tr>

                                                    <?php } ?>

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

            <input type="hidden" id="getFullScreenMode">

        </div>

    </form>

    <!-- Required Js -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <?php include 'application/views/jsPages.php'; ?>




    <script>
        // function to display toast
        function toastMessage({
            msgText,
            background
        }) {
            Toastify({
                text: msgText,
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: background,
                    color: "#fff",
                    marginTop: "10px"
                },
                onClick: function() {} // Callback after click
            }).showToast();
        }

        $("#Formevaluation").on('submit', function(e) {
            e.preventDefault();

            // Show loader
            $("#sub_loader").show();
            $("#sub_text").hide();

            $.ajax({
                url: "<?= base_url('index.php/dashboard/insert_evaluation') ?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(res) {
                    // Hide loader after AJAX request completes
                    $("#sub_loader").hide();
                    $("#sub_text").show();

                    if (res['status'] == 1) {
                        toastMessage({
                            msgText: res['message'],
                            background: "#28a745"
                        });
                    }
                    if (res['status'] == 0) {
                        toastMessage({
                            msgText: res['message'],
                            background: "#dc3545"
                        });
                    }
                }
            });
        });
    </script>


    <script>
        document.getElementById("getFullScreenMode").addEventListener("click", () => {

            toggleFullscreen();

        });
    </script>

    <script>
        let GetSub = (e) => {

            let std = e.value;

            $.ajax({
                url: "<?= base_url('index.php/dashboard/fetch_sub') ?>",
                method: "GET",
                data: {
                    std: std
                },
                dataType: "json",
                success: function(data) {

                    let str = `<option value =""> Select </option>`;

                    data.forEach(element => {
                        console.log(data);
                        str +=
                            `<option ${element == element['stvsubj_id'] ? 'selected' : '' } value = "${element.stvsubj_id}" > ${element.stvsubj_subject} </option>`
                    });

                    $("#sub").html(str);
                }
            })

        }
    </script>
    <script>
        var view_button = document.getElementById("view_button");
        let base_url = '<?= base_url() ?>';

        view_button.addEventListener("click", function() {
            // Show loader
            $("#view_loader").show();
            $("#view_text").hide();
            // toastMessage({
            //     msgText: 'Data is not available',
            //     background: "#dc3545", // error dc3545 -- success 28a745
            // });


            var std = document.getElementById("std").value;
            var div = document.getElementById("division").value;
            var acdm = document.getElementById("acdm").value;
            var exam = document.getElementById("exam").value;
            var sub = document.getElementById("sub").value;

            console.log(std, div, acdm, exam, sub);

            window.location = `${base_url}Dashboard/evaluation?std=${std}&div=${div}&acdm=${acdm}&exam=${exam}&sub=${sub}`;
        });
    </script>