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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />

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



        .dt-button.buttons-print,

        .dt-button.buttons-copy,

        .dt-button.buttons-csv,

        .dt-button.buttons-excel,

        .dt-button.buttons-pdf {




            background-color: #53769c !important;

            color: #fff !important;

            padding: 0px 10px 0px 10px !important;

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

        <form action="" id="submit-form" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->

                <?php include 'application/views/all_reports/links.php'; ?>

                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->



                <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">



                </div>

                <div class="print-container">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="card">

                                <div class="card-body p-2">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <!-- <h6>Filter Report</h6> -->

                                            <div class="row">
                                                <div class="col-md-12">

                                                    <!-- <h6>Filter Report</h6> -->

                                                    <div class="row">

                                                        <div class="col-md-3">

                                                            <div class="form-group row"> <label for="" class="col-md-5">STD<span class="text-danger">*</span>
                                                                    :</label>
                                                                <select class="form-control form-control-sm col-md-6" name="class" id="class" required>
                                                                    <option value="">Select STD</option>
                                                                    <?php
                                                                    foreach ($classes as $class) {
                                                                    ?>
                                                                        <option value="<?= $class['stvfees_class']; ?>">
                                                                            <?= $class['stvfees_class']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group row">
                                                                <label for="" class="col-md-5">Division<span class="text-danger">*</span>
                                                                    :</label>
                                                                <select required class="form-control form-control-sm col-md-6" name="division" id="division">
                                                                    <option selected disabled value="">Select Division
                                                                    </option>
                                                                    <option value="A">A</option>
                                                                    <option value="B">B</option>
                                                                    <option value="C">C</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="" style="opacity: 0;">Click on the</label>
                                                                <button id="find-students" type="button" class="btn btn-sm btn-outline-secondary" value="">View </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered table-striped" id="table-gr">
                                                    <thead class="text-center">
                                                        <tr class="text-center">
                                                            <th>#</th>
                                                            <th class="text-center">ID</th>
                                                            <th class="text-center">Student Name</th>
                                                            <th class="text-center">Aadhar No</th>
                                                            <th class="text-center">Aadhar Name</th>
                                                            <th class="text-center">Date of Birth</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="tbody">
                                                        <?php

                                                        $i = 1;
                                                        foreach ($details as $detail) {
                                                        ?>
                                                            <tr>
                                                                <td class="text-center"><?= $i++; ?></td>
                                                                <td class="text-center">
                                                                    <?= $detail['SPRIDEN_student_id']; ?></td>
                                                                <td class="text-center"><?= $detail['student_name']; ?></td>
                                                                <td class="text-center"><?= $detail['aadhar_number']; ?>
                                                                </td>
                                                                <td class="text-center"><?= $detail['name_on_aadhar']; ?>
                                                                </td>
                                                                <td class="text-center"><?= $detail['date_of_birth']; ?>
                                                                </td>
                                                                <td><a style="padding-left: 4%;" href="<?= base_url('application/upload/') . $detail['aadhar_front_img'] ?>" target="_blank">View
                                                                    </a>
                                                                    <a style="padding-left: 20%; color: #ff0000;" href="<?= base_url('application/upload/') . $detail['aadhar_front_img'] ?>" download="download">download
                                                                        </style=>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
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
        </form>
    </div>


    <!-- [ Main Content ] end -->

    </div>

    </div>

    <!-- [ Main Content ] end -->

    </div>

    </div>

    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>

    <script src=" https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
    </script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js">
    </script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js">
    </script>

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
        document.getElementById("find-students").addEventListener('click',
            async () => {
                var html = '';
                let std = document.getElementById("class")
                    .value != '' ? document.getElementById(
                        "class")
                    .value : 0;
                let div = document.getElementById("division")
                    .value != '' ? document.getElementById("division")
                    .value : 0;
                let base_url = '<?= base_url(); ?>';
                window.location =


                    `${base_url}index.php/Report/Aadhar_report/${std}/${div}`;


            });
    </script>




</body>

</html>