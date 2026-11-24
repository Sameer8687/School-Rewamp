<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Exam Hall Ticket - Olive English School</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" />
    <!---Include CSS Files-->

    <?php include 'application/views/cssPages.php'; ?>

    <style>
        .table tr th,
        .table tr td {
            border: 1px solid #999;
            font-weight: 500 !important;
        }

        .text-olive {
            color: #53769c;
        }

        .form-control {
            border: none;
            border-bottom: 0.5px solid black;
            margin-top: -6px;
            font-size: 17px;
        }

        p {
            font-weight: 500;
            font-size: 16px;
        }

        .name {
            text-align: center;
            font-size: 15px;
            color: black;

        }

        .abc {
            font-size: 16px;
            text-align: center;

        }

        .und {

            border-bottom: 1px solid black;
        }

        .table-bordered {
            border: 1px solid black !important;
        }
    </style>
    <style type="text/css" media="print">
        @media print {
            .no-print {
                display: none !important;
            }
        }

        @media print {

            html,
            body {
                height: 100%;
                background-color: #ffffff;

            }
        }



        @media print {
            .card {
                width: 100%;
                /* Adjust the width to fit two cards on the page */
                margin: 5px;
                display: inline-block;
                page-break-inside: avoid;
                /* Prevent page breaks inside the cards */
            }
        }




        @media print {
            .text-olive {
                color: #53769c;
            }

            .bg-wali-kissu {
                color: #383d41;
                background-color: #e2e3e5;
                border-color: #d6d8db;
            }
        }

        @page {
            font-size: xx-large;

            size: A4 portrait;
            /* margin-top: 50%; */
            /* this affects the margin in the printer settings */
            border: 1px solid red;
            /* set a border for all printed pages */
            /* height:max-content;
                width: 100%; */
            /* margin: 40px 5px 35px;; */
            /* -webkit-transform: scale(0.85); */
            /* Saf3.1+, Chrome */
            /* -moz-transform: scale(0.85); */
            /* FF3.5+ */
            /* -ms-transform: scale(0.25); */
            /* IE9 */
            /* -o-transform: scale(0.85); */
            /* Opera 10.5+ */
            /* transform: scale(0.85); */
            /* margin: -50px -73px 0; */
            /* big hack to reposition the page top the top and left of the viewer control */

            /* width: auto; */
            /* height: auto; */
            /* position: absolute; */
            /* top: 0px; */
            /* bottom: 0px; */
            /* margin: 40px 5px 35px; */
            /* margin-top: 0px !important; */
            /* margin-bottom: 0px !important; */
            /* border: 1px solid; */
            /* page-break-after: always; */
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0px;
            bottom: 0px;
            margin: none;
            margin-top: 0px !important;
            border: 1px solid;
            scale: 100;

        }

        .table thead tr th,
        .table tbody tr td {
            border: 1px solid #999;
            font-weight: 500 !important;
            padding: 5px !important;
        }


        .table.table-borderless tr th,
        .table tr td {
            border: 1px solid transparent;
            padding: 5px !important;
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

    <?php include 'application/views/topNav.php'; ?>

    <!--Include Sidebar -->

    <?php include 'application/views/sidebar.php'; ?>



    <!--Include Top Navbar-->

    <style>

    </style>



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="container">

            <div class="row no-print ">
                <div class="col-md-4">
                    <button class="btn btn-primary btn-sm mb-3" onclick="window.print()">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
            </div>
            <?php
            $exam_name = '';
            $academic_year = '';

            ?>

            <?php
            if ($search_mode == 'classwise'):
                
                foreach ($all_records as $record) : ?>
                    <div class="card" style="border: 2px solid black;font-size:smaller !important">
                        <div class="row mt-1">
                            <div class="col-md-12">

                                <div class="d-flex text-center">
                                    <div class="mr-3"><img src="<?= base_url('images/logoblack.png') ?>" style="height: 90px; width:90px;" class="ml-5 mr-4" /></div>
                                    <div class="text-center ml-5 ">
                                        <h1 class="text-olive m-0">
                                            Olive English School & Jr College
                                        </h1>
                                        <h5 class="m-0">National Highway No.3, Near Traffic Police Station, Dhule.</h5>
                                        <span>
                                            <h5 class="text-dark font-weight-bold m-0" style="padding: 5px;border-radius: 10px;"> <?= $exam_name ?> - Hall Ticket ( Time Table <?= $academic_year ?>)</h5>
                                        </span>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 px-3">
                                <table class="table table-borderless">
                                    <tr class="pt-0 py-5 alert alert-secondary">
                                        <th class="text-left fw-bold">
                                            <h4 class="m-0">Name : <?= $record['student_name'] ?></h4>
                                        </th>
                                        <th class="text-center fw-bold">
                                            <h4 class="m-0">Std : <?= $std ?></h4>
                                        </th>
                                        <th class="text-center fw-bold">
                                            <h4 class="m-0">Div : <?= $div ?></h4>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>




                        <div class="row ">
                            <div class="col-md-12 px-3">
                                <table class="table table-bordered ticket-table">
                                    <!-- <tr class="border border-dark alert alert-secondary">
                                    <th colspan="3" class="text-center p-1 alert-secondary"><h6 class="m-0">Oral</h6></th>
                                </tr> -->
                                    <thead>
                                        <tr class="text-left alert alert-secondary bg-wali-kissu">
                                            <th class="p-0 pl-2 fw-bold">
                                                <h5 class="m-0">Subject</h5>
                                            </th>
                                            <th class="p-0 pl-2 fw-bold">
                                                <h5 class="m-0">Exam Type</h5>
                                            </th>
                                            <th class="p-0 pl-2 fw-bold">
                                                <h5 class="m-0">Day & Date</h5>
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $invalid = ['', ' ', '0000-00-00'];
                                        foreach ($timetable as $time_table) :
                                            $exam_date = !in_array($time_table['stvhtkt_date'], $invalid) ? date('d-M-Y', strtotime($time_table['stvhtkt_date'])) : null;
                                            $day = $exam_date != null ? date('l', strtotime($exam_date)) : null;
                                        ?>
                                            <tr>
                                                <td><?= $time_table['stvsubj_subject'] ?></td>
                                                <td><?= $time_table['stvhtkt_type'] ?></td>
                                                <td><?= "$day, $exam_date - In School Time" ?></td>

                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>


                        </div>



                    </div>
                <?php endforeach;
            else:
                foreach ($all_records as $record) : ?>
                    <div class="card" style="border: 2px solid black;font-size:smaller !important">
                        <div class="row mt-1">
                            <div class="col-md-12">

                                <div class="d-flex text-center">
                                    <div class="mr-3"><img src="<?= base_url('images/logoblack.png') ?>" style="height: 90px; width:90px;" class="ml-5 mr-4" /></div>
                                    <div class="text-center ml-5 ">
                                        <h1 class="text-olive m-0">
                                            Olive English School & Jr College
                                        </h1>
                                        <h5 class="m-0">National Highway No.3, Near Traffic Police Station, Dhule.</h5>
                                        <span>
                                            <h5 class="text-dark font-weight-bold m-0" style="padding: 5px;border-radius: 10px;"> <?= $exam_name ?> - Hall Ticket ( Time Table <?= $academic_year ?>)</h5>
                                        </span>
                                    </div>



                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 px-3">
                                <table class="table table-borderless">
                                    <tr class="pt-0 py-5 alert alert-secondary">
                                        <th class="text-left fw-bold">
                                            <h4 class="m-0">Name : <?= $record['name'] ?></h4>
                                        </th>
                                        <th class="text-center fw-bold">
                                            <h4 class="m-0">Std : <?= $record['STVCLAS_standard'] ?></h4>
                                        </th>
                                        <th class="text-center fw-bold">
                                            <h4 class="m-0">Div : <?= $record['STVCLAS_class'] ?></h4>
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>




                        <div class="row ">
                            <div class="col-md-12 px-3">
                                <table class="table table-bordered ticket-table">
                                    <!-- <tr class="border border-dark alert alert-secondary">
                                    <th colspan="3" class="text-center p-1 alert-secondary"><h6 class="m-0">Oral</h6></th>
                                </tr> -->
                                    <thead>
                                        <tr class="text-left alert alert-secondary bg-wali-kissu">
                                            <th class="p-0 pl-2 fw-bold">
                                                <h5 class="m-0">Subject</h5>
                                            </th>
                                            <th class="p-0 pl-2 fw-bold">
                                                <h5 class="m-0">Exam Type</h5>
                                            </th>
                                            <th class="p-0 pl-2 fw-bold">
                                                <h5 class="m-0">Day & Date</h5>
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $invalid = ['', ' ', '0000-00-00'];
                                        foreach ($record['hallticket'] as $time_table) :
                                            $exam_date = !in_array($time_table['stvhtkt_date'], $invalid) ? date('d-M-Y', strtotime($time_table['stvhtkt_date'])) : null;
                                            $day = $exam_date != null ? date('l', strtotime($exam_date)) : null;
                                        ?>
                                            <tr>
                                                <td><?= $time_table['stvsubj_subject'] ?></td>
                                                <td><?= $time_table['stvhtkt_type'] ?></td>
                                                <td><?= "$day, $exam_date - In School Time" ?></td>

                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>


                        </div>



                    </div>
            <?php endforeach;
            endif; ?>
        </div>

    </div>


    </div>


    </div>






    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>




    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>



</body>


</html>