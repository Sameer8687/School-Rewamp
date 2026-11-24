<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Student Books & Result Form</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!---Include CSS Files-->

    <?php include 'application/views/cssPages.php'; ?>

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>



</head>



<body class="">

    <!-- [ Pre-loader ] start -->

    <div class="loader-bg">

        <div class="loader-track">

            <div class="loader-fill"></div>

        </div>

    </div>

    <!-- [ Pre-loader ] End -->



    <!--Include Sidebar -->

    <?php include 'application/views/sidebar.php'; ?>



    <!--Include Top Navbar-->

    <?php include 'application/views/topNav.php'; ?>



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <form action="" id="random_class_form" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->



                <div class="row margin-top-minus-40 mobile-margin-top-minus-40  mb-2">

                    <div class="col-md-7">

                        <!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->

                        <!-- <button class="btn btn-secondary btn-sm" id="add_random_class"> <i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span></button> -->

                    </div>

                </div>

                <style>
                    .mini-label label {

                        font-size: 12px !important;

                        font-weight: normal !important;

                    }



                    .table thead tr th,

                    .table tbody tr td {

                        font-size: 12px !important;

                        font-weight: normal !important;

                        padding: 5px !important;

                        text-transform: capitalize;

                        border: 1px solid #77777790 !important;

                    }
                </style>

                <div class="row">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-header" style="background-color: #53769c !important;">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2" style="color:#fff;">Random Class</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-secondary btn-sm float-right" id="add_random_class"> <i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
                                    </div>
                                </div>


                            </div>

                            <div class="card-body">

                                <div class="row mini-label">

                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-3 text-left">Select Class<span class="text-danger">*</span> :</label>

                                            <select class="form-control form-control-sm col-md-4" required name="class" id="class">

                                                <option value="">Select Class</option>

                                                <?php

                                                foreach ($classes as $class) {

                                                ?>

                                                    <option value="<?php echo $class['stvfees_class']; ?>">

                                                        <?php echo $class['stvfees_class']; ?></option>

                                                <?php } ?>

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">

                                            <label for="" class="col-md-3 text-left">Select Teacher<span class="text-danger">*</span> :</label>

                                            <select class="form-control form-control-sm col-md-4" name="teacher_name" id="teacher_name">

                                                <option value="">Select Teacher Name</option>

                                                <?php

                                                foreach ($teachers as $teacher) {

                                                ?>

                                                    <option value="<?php echo $teacher['SPRIDEN_student_id']; ?>"><?php echo $teacher['SPRIDEN_surname'] . " " . $teacher['SPRIDEN_student_name'] . " " . $teacher['SPRIDEN_father_name']; ?></option>

                                                <?php } ?>

                                            </select>

                                            <input type="hidden" name="total_rows" id="total_rows">

                                        </div>

                                    </div>

                                </div>

                                <div class="table-height" style="overflow-y: auto;">

                                    <table class="table table-sm table-bordered">

                                        <thead>

                                            <tr class="text-center">

                                                <th style="background-color: #53769c !important; color: #fff;">#</th>

                                                <th style="background-color: #53769c !important; color: #fff;">Student ID</th>

                                                <th style="background-color: #53769c !important; color: #fff;">Student Name</th>

                                                <th style="background-color: #53769c !important; color: #fff;">Class</th>

                                                <th style="background-color: #53769c !important; color: #fff;">Status</th>

                                                <th style="background-color: #53769c !important; color: #fff;">Teacher</th>

                                            </tr>

                                        </thead>

                                        <style>
                                            .no-border {

                                                border: none !important;

                                            }
                                        </style>

                                        <tbody id="tbody">



                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>







            </div>

        </form>

        <!-- [ Main Content ] end -->

    </div>







    </div>

    <!-- [ Main Content ] end -->

    </div>











    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>



    <script>
        $('#class').change(function() {

            var class_name = $('#class').val();

            if (class_name != '') {

                $.ajax({

                    url: "<?= base_url('index.php/RandomClass/student_class_wise') ?>?class=" + class_name,

                    method: "GET",

                    dataType: "JSON",

                    success: function(data) {

                        if (data != null) {

                            //console.log(data);

                            if (data['stu_details'].length > 20) {

                                $('.table-height').css("height", "400px");

                            }

                            var html = '';



                            for (var count = 0; count < data['stu_details'].length; count++) {

                                var isChecked = '';

                                var rndm_id = '';

                                var tcr_rndm_id = '';

                                var isTc = '';

                                for (var loop = 0; loop < data['rndm_details'].length; loop++) {

                                    if (data['stu_details'][count].id == data['rndm_details'][loop].stvrndm_student_id && data['rndm_details'][loop].stvrndm_status == '1') {

                                        isChecked = "checked";

                                        rndm_id = data['rndm_details'][loop].stvrndm_id;

                                        tcr_rndm_id = data['rndm_details'][loop].stvrndm_teacher_id;

                                        break;

                                    } else {

                                        isChecked = '';

                                        rndm_id = '';

                                        tcr_rndm_id = '';

                                    }

                                }

                                var next = count + 1;

                                html += '<tr>';

                                html += '<td class="text-center">' + next + '</td>';

                                html += '<td class="text-center" style="padding: 0px !important;">';

                                html += '<input type="text" readonly class="form-control form-control-sm no-border" id="student_id' + next + '" name="student_id' + next + '" value="' + data['stu_details'][count].SPRIDEN_student_id + '">';

                                html += '<input type="hidden" class="form-control form-control-sm no-border" id="student_id_hidden' + next + '" name="student_id_hidden' + next + '" value="' + data['stu_details'][count].id + '">';

                                html += '<input type="hidden" class="form-control form-control-sm no-border" id="random_id_hidden' + next + '" name="random_id_hidden' + next + '" value="' + rndm_id + '">';

                                html += '</td>';

                                html += '<td style="padding: 0px !important;">';

                                html += '<input type="text" readonly class="form-control form-control-sm no-border" id="student_name' + next + '" name="student_name' + next + '" value="' + data['stu_details'][count].name + '">';

                                html += '</td>';

                                html += '<td class="text-center" style="padding: 0px !important;">';

                                html += '<input type="text" readonly class="form-control form-control-sm no-border" id="class' + next + '" name="class' + next + '" value="' + data['stu_details'][count].STVCLAS_standard + '">';

                                html += '</td>';

                                html += '<td style="padding: 0px !important; text-align: center;">';

                                html += '<input ' + isChecked + ' type="checkbox" value="Yes" id="status' + next + '" name="status' + next + '">';

                                html += '</td>';

                                html += '<td style="padding: 0px !important;">';

                                html += '<select class="form-control form-control-sm no-border" name="random_teacher_id' + next + '" id="random_teacher_id' + next + '">';

                                html += '<option value="">Select Teacher Name</option>';

                                html += '<?php foreach ($teachers as $teacher) { ?>';

                                var tc_id = '<?php echo $teacher['SPRIDEN_student_id']; ?>';

                                if (tc_id == tcr_rndm_id) {

                                    isTc = "selected";

                                } else {

                                    isTc = '';

                                }

                                html += '<option ' + isTc + ' value="<?php echo $teacher['SPRIDEN_student_id']; ?>">';

                                html += '<?php echo $teacher['SPRIDEN_surname'] . " " . $teacher['SPRIDEN_student_name'] . " " . $teacher['SPRIDEN_father_name']; ?></option>';

                                html += '<?php } ?>';

                                html += '</select>';

                                html += '</td>';

                                html += '</tr>';



                                //console.log(tcr_rndm_id);

                                //console.log("#random_teacher_id" + next);

                                $("#random_teacher_id" + next).val(tcr_rndm_id);

                            }

                            $('#tbody').html(html);

                            $('#total_rows').val(data['stu_details'].length);

                        }

                    }

                });

            }

        });





        // add data

        $('#random_class_form').on('submit', function(e) {

            e.preventDefault();

            var class_name = $('#class').val();

            var teacher_name = $('#teacher_name').val();

            if (class_name != '') {

                $.ajax({

                    url: "<?= base_url('index.php/RandomClass/add_data') ?>",

                    method: "POST",

                    data: new FormData(this),

                    contentType: false,

                    processData: false,

                    beforeSend: function() {

                        $('#add_random_class').html("<i class='bx bx-loader bx-spin'></i> Please Wait");

                    },

                    success: function(data) {

                        $('#add_random_class').html('<i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span>');

                        if (data != "fail") {

                            alert("Student Assigned Successfully !!");

                            //console.log("Student Assigned Successfully !!");

                            window.location.reload();

                        } else {

                            alert("Failed");

                        }

                    }



                });

            } else {

                alert("Select Class");

            }

        });
    </script>





</body>



</html>