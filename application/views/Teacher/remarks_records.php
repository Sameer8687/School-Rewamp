<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Remarks</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!---Include CSS Files-->

    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <style>

        .table thead tr th,

        .table tbody tr td {

            padding: 5px !important;

            font-size: 12px !important;

            border: 1px solid #77777730;

        }



        .table tbody tr td.no-padding {

            padding: 0 !important;

        }



        .table tbody tr td .form-control.no-border {

            border: none !important;

            outline: none !important;

        }

    </style>

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

        <form id="form_data_data" enctype="multipart/form-data" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->



                <div class="row margin-top-minus-40 mobile-margin-top-minus-10 mb-2">

                    <div class="col-md-7">

                        <!-- <a href="<?= base_url('Student/student_list') ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->

                        <!-- <button class="btn btn-secondary btn-sm" id="save-btn" type="submit"> <i class="fas fa-user-graduate"></i> <span id="dynamicLabelAddButton">Save Details</span></button> -->

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="row" style="margin-top: -10px;">

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">Select Class <span class="float-right">:</span></label>

                                            <select name="std" id="std" class="form-control form-control-sm col-md-7">

                                                <option value="">Select Class</option>

                                                <?php foreach ($classes as $clas) { ?>

                                                    <option value="<?= $clas['stvfees_class'] ?>"><?= $clas['stvfees_class'] ?></option>

                                                <?php } ?>

                                            </select>

                                            <input type="hidden" name="max_rows" id="max_rows">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-5">Academic year <span class="float-right">:</span></label>

                                            <select name="acdm_year" id="acdm_year" class="form-control form-control-sm col-md-7">

                                                <option value="">Select Academic Year</option>

                                                <?php foreach ($academic_years as $academic_year) {

                                                    if ($academic_details['stvacdm_id'] == $academic_year['stvacdm_id']) {

                                                        $selected = 'selected';

                                                    } else {

                                                        $selected = '';

                                                    }

                                                ?>

                                                    <option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>

                                                <?php } ?>

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-md-2">

                                        <div class="form-group row">

                                            <div class="col-md-12">

                                                <button id="find-students" type="button" class="btn btn-sm btn-outline-secondary">View Class</button>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-2">

                                        <div class="form-group row">

                                            <div class="col-md-12">

                                            <button class="btn btn-secondary btn-sm float-right" id="save-btn" type="submit"> <i class="fas fa-user-graduate"></i> <span id="dynamicLabelAddButton">Save Details</span></button>


                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12" id="total-div">



                                    </div>

                                    <div class="col-md-12">



                                        <div class="col-md-12" id="total-div">



                                        </div>

                                        <div id="tholder">

                                            <table class="table table-sm" id="example">

                                                <thead>

                                                    <tr>

                                                        <th>#</th>

                                                        <th>Student</th>

                                                        <th>Teacher</th>

                                                        <th>Std</th>

                                                        <th>Date</th>

                                                        <th>Remark</th>

                                                        <th>Parent Feedback</th>

                                                        <th>Feedback Date</th>

                                                        <th>Status</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php $i = 1;

                                                    foreach ($students_remarks as $remark) { ?>

                                                        <tr>

                                                            <td><?= $i; ?></td>

                                                            <td><?= $remark['student_name'] ?></td>

                                                            <td><?= $remark['teacher_name']; ?></td>

                                                            <td><?= $remark['stvrmrk_std']; ?></td>

                                                            <td><?= $remark['stvrmrk_date']; ?></td>

                                                            <td><?= $remark['stvrmrk_remark']; ?></td>

                                                            <td><?= $remark['stvrmrk_reply']; ?></td>

                                                            <td><?= $remark['stvrmrk_date_of_reply'] != '0000-00-00' ? date('d-M-Y', strtotime($remark['stvrmrk_date_of_reply'])) : '' ?></td>

                                                            <td><?= $remark['stvrmrk_status']; ?></td>

                                                        </tr>

                                                    <?php $i++;

                                                    } ?>

                                                </tbody>

                                            </table>

                                        </div>

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

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>



    <script>

        document.getElementById("find-students").addEventListener('click', async () => {

            var html = '';

            let std = document.getElementById("std").value;

            let acdm_year = document.getElementById("acdm_year").value;

            let base_url = '<?= base_url(); ?>';

            window.location = base_url + 'student/remarks/' + std + '/' + acdm_year;



        });

        $(document).ready(function() {

            $('#example').DataTable({

                pageLength: '25',

                dom: 'Bfrtip',

                buttons: [

                    'copy', 'csv', 'excel', 'pdf', 'print'

                ]

            });

        });

    </script>

</body>



</html>



<!--  -->