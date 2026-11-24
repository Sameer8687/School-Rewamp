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
                <?php include 'application/views/Student/links.php'; ?>
                <!-- [ breadcrumb ] end -->
                <!-- [ Main Content ] start -->

                <div class="row margin-top-minus-40 mobile-margin-top-minus-40  mb-2">
                    <div class="col-md-7">
                        <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>
                        <button class="btn btn-secondary btn-sm" id="add_random_class"> <i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
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
                                <h5 class="card-title" style="font-weight: normal; color: #fff;">Random Class</h5>
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
                                            <select class="form-control form-control-sm col-md-4" required name="teacher_name" id="teacher_name">
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
                                                <th style="background-color: #53769c !important; color: #fff;">Select</th>
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
                            if (data.length > 20) {
                                $('.table-height').css("height", "400px");
                            }
                            var html = '';
                            for (var count = 0; count < data.length; count++) {
                                var next = count + 1;
                                html += '<tr>';
                                html += '<td class="text-center">' + next + '</td>';
                                html += '<td class="text-center" style="padding: 0px !important;">';
                                html += '<input type="text" readonly class="form-control form-control-sm no-border" id="student_id' + next + '" name="student_id' + next + '" value="' + data[count].SPRIDEN_student_id + '">';
                                html += '<input type="hidden" class="form-control form-control-sm no-border" id="student_id_hidden' + next + '" name="student_id_hidden' + next + '" value="' + data[count].id + '">';
                                html += '</td>';
                                html += '<td style="padding: 0px !important;">';
                                html += '<input type="text" readonly class="form-control form-control-sm no-border" id="student_name' + next + '" name="student_name' + next + '" value="' + data[count].name + '">';
                                html += '</td>';
                                html += '<td class="text-center" style="padding: 0px !important;">';
                                html += '<input type="text" readonly class="form-control form-control-sm no-border" id="class' + next + '" name="class' + next + '" value="' + data[count].STVCLAS_standard + '">';
                                html += '</td>';
                                html += '<td style="padding: 0px !important; text-align: center;">';
                                html += '<input type="checkbox" value="Yes" id="status' + next + '" name="status' + next + '">';
                                html += '</td>';
                                html += '</tr>';
                            }
                            $('#tbody').html(html);
                            $('#total_rows').val(data.length);
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
            if (class_name != '' && teacher_name != '') {
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
                            window.location.reload();
                        } else {
                            alert("Failed");
                        }
                    }

                });
            } else {
                alert("Select Class & Teacher Name");
            }
        });
    </script>


</body>

</html>