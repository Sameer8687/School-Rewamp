<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Kolkata');



?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School -</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!---Include CSS Files-->

    <!-- Favicon icon -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" /> -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" /> -->
    <style>
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: #333 !important;
        border: 1px solid #979797;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        box-sizing: border-box;
        display: inline-block;
        min-width: 1.5em;
        padding: 0 !important;
        margin-left: 2px;
        text-align: center;
        text-decoration: none !important;
        cursor: pointer;
        *cursor: hand;
        color: #333 !important;
        border: 1px solid transparent;
        border-radius: 2px;
    }

    .row {
        display: block;
    }

    button.dt-button,
    div.dt-button,
    a.dt-button,
    input.dt-button {
        position: relative;
        display: inline-block;
        box-sizing: border-box;
        margin-left: 0.167em;
        margin-right: 0.167em;
        margin-bottom: 0.333em;
        /* padding: 5px !important; */
    }

    .navbar {
        padding-left: 0px;
        padding-right: 0px;
    }
    </style>



    <style>
    @media print {

        .t1 thead tr th,
        .t1 tbody tr td {
            border: 1px solid black !important;
        }

        .t2 thead tr td {
            border: 1px solid black !important;
        }

        body {
            /* border: 1px solid black; */
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
            margin-top: 1px !important;
            margin-bottom: -1px !important;
            padding: auto;
            page-break-before: avoid;
            /* width: 100%;
            height: 100%; */
            /* -webkit-transform: rotate(-90deg) scale(.99, .99);
            -moz-transform: rotate(-90deg) scale(.0, .0); */
            zoom: 90%
        }



        .text-center {
            justify-content: center !important;
            margin-right: auto !important;
            margin-left: auto !important;
        }

        @page {
            size: portrait;
        }

        /* .thead1 {
            border: 1px solid black;
        }

        .thead2 {
            border: 1px solid black !important;
        }

        .table tbody tr td {
            border: 1px solid black !important;
        } */






        /* table thead tr td,
        table tbody tr td {
            border: 1px solid black !important;
        } */


        /* * {
            margin: auto !important;
            padding: auto !important;

        } */

        #controls,
        .footer,
        .footerarea {
            display: none;
        }
    }
    </style>
</head>

<body>
    <!-- <div class=" container-fluid">
    <div class="row">

    </div>
    </div> -->
    <style>
    /* .table {
        border: none !important;
    } */

    .table thead tr th {
        padding: 5px !important;
        font-size: 15px !important;
        /* border: 1px solid #e9e9e9; */
        cursor: pointer;
    }

    .thead1 {
        padding: 5px !important;
        font-size: 17px !important;
        border: 1px solid black;
        cursor: pointer;
    }

    .t2 thead tr td {
        border: 1px solid black !important;
    }

    .thead2 {
        padding: 5px !important;
        font-size: 12px !important;
        border: 1px solid black;
        cursor: pointer;
    }

    .table tbody tr td {
        padding: 5px !important;
        font-size: 12px !important;


        border: 1px solid black;
        cursor: pointer;
    }

    .table thead tr th {
        border: 1px solid black;
    }

    .head {
        font-size: 15px !important;
        /* background-color: #6aad94; */
        color: black;
    }

    .td {
        vertical-align: middle !important;
    }
    </style>
    <div class="container mt-4" style="border: 1px solid black;">
        <!-- <div class="row mt-2">
            <div class="col-md-12"> -->
        <!-- style="padding-left: 19px; padding-right: 19px" -->
        <!-- <table class="table ">
                    <tr class="text-center">
                        <td class="shaw" style="width: 10%;"><img
                                src="https://school.oliveschool.org.in/images/logoNewDev.png" class="logo"
                                style="width: 90%;"> </td>
                        <td class="olive">
                            <h1 style="font-size:29px; font-weight: bold;">Olive English School & Junior College
                            </h1>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="width: 10%;">
                            <h2 class="font-normal " style="font-size:18px ;">National Highway No.3 Behind
                                Traffic
                                Police Station, Dhule.</h2>
                        </td>
                    </tr>
                    <tr>
                        <h1 style="font-size:29px; font-weight: bold; text-align:center;">XI SCIENCE RESULT
                            2022-23
                        </h1>
                    </tr>
                    <tr>
                        <h2 style="font-size:29px; font-weight: bold; text-align:center;">STATEMENT OF MARKS
                        </h2>
                    </tr>
                </table> -->
        <!-- <table>
                    <tr class="">
                        <td><img src="https://school.oliveschool.org.in/images/logoNewDev.png" class=""
                                style="height:100px"></td>
                        <td class="text-right">
                            <h1 class="text-right">Olive English School & Junior College
                            </h1>
                        </td>
                    </tr>
                    <hr>
                </table> -->
        <div class=" row mt-5">
            <div class="col-md-12">
                <!-- <div class="col-md-4 d-flex"><img src="https://school.oliveschool.org.in/images/logoNewDev.png" class=""
                    style="height:135px;position: absolute;margin-top: -35px;margin-bottom: auto;margin-left: 120%;margin-right: auto;">
            </div> -->
                <table class="table ">
                    <tr class="text-center">
                        <td class="shaw" style="border: none;"><img
                                src="https://school.oliveschool.org.in/images/logoNewDev.png" class="logo"
                                style="width: 15%;"> </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-4 ">

            <div class="col-md-8 d-flex"
                style="margin-left: auto;margin-right: auto;justify-content:center;font-weight:bold">
                <h1 class="text-center" style="font-weight: bold;">Olive English School & Jr
                    College
                </h1>
            </div>
        </div>
        <div class="row text-center ">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center" style="margin-left:auto;margin-right:auto">
                <h2 class="text-center" style="font-size:18px ;justify-content:center;">National Highway
                    No.3 Behind
                    Traffic
                    Police Station, Dhule.</h2>
            </div>
            <div class="col-md-3"></div>

        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <h3 style="font-size:29px; font-weight: bold; text-align:center;">XI Science Annual Result 2022-23
                </h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3 style="font-size:29px; font-weight: bold; text-align:center;">Statement of Marks
                </h3>
            </div>
        </div>
        <!-- <table id="example" class="table table-sm table-bordered mt-5" style="width: 100%">
            <thead>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <th class="head" style="width: 50%;">Month & Year OF Exam</th>
                    <th class="head" style="width: 50%;">College Code</th>
                
                </tr>
            </thead>
            <tbody>
                <style>
                .getRowData {
                    cursor: pointer;
                }
                </style>
                <tr class="getRowData" data-header-id="" style="font-weight: 700 !important;">
                    <td style="text-align: center">March-2023</td>
                    <td style="text-align: center"></td>
                </tr>
            </tbody>
        </table> -->
        <table id="example" class="table table-sm table-bordered mt-5 t1" style="width: 100%">
            <thead>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <th class="head" style="vertical-align: middle;">
                        <h5 style="font-weight: bold; margin:0px;">Roll No</h5>
                    </th>
                    <th class="head" style="vertical-align: middle;">
                        <h5 style="font-weight: bold; margin:0px;">Name of the Student</h5>
                    </th>
                    <th class="head" style="vertical-align: middle;">
                        <h5 style="font-weight: bold; margin:0px;">Month & Year OF Exam</h5>
                    </th>

                </tr>
            </thead>
            <tbody>
                <style>
                .getRowData {
                    cursor: pointer;
                }
                </style>
                <?php
                // $rows= get_row();
                foreach ($student_name as $name) : ?>
                <tr class="getRowData" data-header-id="">

                    <td style="text-align: center; vertical-align: middle;">
                        <h6 style="font-weight: bold; margin:0px;"><?= $name['spriden_student_id'] ?>
                        </h6>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <h6 style="font-weight: bold; margin:0px;"><?= $name['name'] ?></h6>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <h6 style="font-weight: bold; margin:0px;">March-2023</h6>
                    </td>

                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>



        <table id="example" class="table table-sm table-bordered mt-5 t1" style="width: 100%">
            <thead>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <th class="head">
                        <h5 style="font-weight: bold; ">Subject</h5>
                    </th>
                    <?php foreach ($subjects as $subj) : ?>
                    <th>
                        <h5 style="font-weight: bold; "><?= $subj ?></h5>
                    </th>
                    <?php endforeach; ?>
                    <th class="head">
                        <h5 style="font-weight: bold; ">Total</h5>
                    </th>
                    <th class="head">
                        <h5 style="font-weight: bold; ">Percentage.</h5>
                    </th>
                </tr>

            </thead>
            <tbody>
                <style>
                .getRowData {
                    cursor: pointer;
                }
                </style>
                <tr class="getRowData" data-header-id="">
                    <td>
                        <h6>Max Marks</h6>
                    </td>
                    <?php
                    $total_marks_sum = 0;
                    foreach ($total_marks as $t_mrks) :
                        $total_marks_sum += (float)$t_mrks;
                    ?>
                    <td class="text-center" style="vertical-align: middle;">
                        <h6><?= $t_mrks ?></h6>
                    </td>
                    <?php endforeach; ?>
                    <td style="text-align: center; vertical-align: middle;">
                        <h6><?= $total_marks_sum; ?></h6>
                    </td>
                    <td contenteditable style="text-align: center; vertical-align: middle;">
                        <h6>Out of 100</h6>
                    </td>



                </tr>
                <tr class="getRowData" data-header-id="">
                    <td>
                        <h6>Marks Obtained</h6>
                    </td>
                    <?php
                    $total_obtained_marks = 0;
                    // $rows= get_row();
                    for ($m = 0; $m < count($marks); $m++) :
                        $total_obtained_marks += (float)$marks[$m];
                    ?>
                    <td style="text-align: center; vertical-align: middle;">
                        <h6 style="font-weight: bold; margin:0px; "> <?= $marks[$m] ?>
                            <?= $marks[$m] < $passing_marks[$m] ? '*' : ''; ?></h6>
                    </td>
                    <?php endfor; ?>
                    <?php
                    $percentage = 0;
                    $percentage = $total_marks_sum > 0 ? ($total_obtained_marks / $total_marks_sum) * 100 : 0;
                    ?>
                    <td class="text-center" style="vertical-align: middle;">
                        <h6 style="font-weight: bold; margin:0px;"><?= $total_obtained_marks ?></h6>
                    </td>
                    <td class="text-center " style="vertical-align: middle; margin:0px;">
                        <h6 style="font-weight: bold; margin:0px;"><?= number_format($percentage, 2) ?>%</h6>
                    </td>
                </tr>
            </tbody>
        </table>

        <table id="example" class="table table-sm table-bordered mt-5 t1" style="width: 100%">
            <thead>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <th class="head" style="width: 50%;">
                        <h5 style="font-weight: bold; margin:0px;">Subject</h5>
                    </th>
                    <th class="head" style="width: 50%;">
                        <h5 style="font-weight: bold; margin:0px;">Grade</h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <td>
                        <h5 style="font-weight: bold; margin:0px;">Health & Physical Education</h5>
                    </td>
                    <td contenteditable class="head text-center" style="width: 50%; font-weight:bold; font-size:72px">
                        <h5 style="font-weight: bold; margin:0px;">A</h5>
                    </td>
                </tr>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <td>
                        <h5 style="font-weight: bold; margin:0px;">Enviroment Education</h5>

                    </td>

                    <td contenteditable class="head text-center" style="width: 50%; font-weight:bold;font-size:72px">
                        <h5 style="font-weight: bold; margin:0px;">A</h5>
                    </td>

                </tr>
            </tbody>
        </table>


        <table id="example" class="table table-sm table-bordered mt-5 t2" style="width: 100%">
            <thead>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <td class="head" style="width: 50%; ">
                        <h5 style="font-weight: bold; margin:0px;">Remark</h5>
                    </td>
                    <td contenteditable class="head" style="width: 50%; font-weight:bold; font-size:72px">
                        <h5 style="font-weight: bold; margin:0px;">Eligible to Std XII Science</h5>
                    </td>
                </tr>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <td class="head" style="width: 50%;">
                        <h5 style="font-weight: bold; margin:0px;">Progress</h5>
                    </td>
                    <td contenteditable class="head" style="width: 50%; font-weight:bold; font-size:72px">
                        <h5 style="font-weight: bold; margin:0px;">Fair</h5>
                    </td>
                </tr>
                <tr id="thead2" class="alert-secondary" style="
                  text-align: center;
                  background-color: white;
                  color: black;
                ">
                    <td class="head" style="width: 50%;">
                        <h5 style="font-weight: bold; margin:0px;">Conduct</h5>
                    </td>
                    <td contenteditable class="head" style="width: 50%; font-weight:bold; font-size:72px">
                        <h5 style="font-weight: bold; margin:0px;">Good</h5>
                    </td>
                </tr>
            </thead>
            <!-- <tbody>
                        <styl
                        e>
                        .getRowData {
                            cursor: pointer;
                        }
                        </styl>
                        <tr class="getRowData" data-header-id="">
                            <td>HEALTH & PHYSICAL EDUCATION</td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr class="getRowData" data-header-id="">
                            <td>ENVIROMENT EDUCATION</td>
                            <td style="text-align: center"></td>
                        </tr>
                    </tbody> -->
        </table>

        <table class="table stu-det mt-5 d-n">

            <!--First Row-->

            <tr>

                <td style="border: none; width: 70px;">

                    <span class="font-weight-bold" style="font-size: medium;">
                        <h5>Date :</h5>
                    </span>

                </td>

                <td contenteditable
                    style="border: none; width: 15%; vertical-align: middle; font-weight:bold; font-size:72px"
                    class="text-left form-control form-control-sm cust-border-bottom" id="number">
                    <h5>13-May-2023</h5>
                </td>
            </tr>
            <tr>
                <td style="border: none; width: 70px;">

                    <span class="font-weight-bold" style="font-size: medium;">
                        <h5>Place :</h5>
                    </span>

                </td>
                <td contenteditable
                    style="border: none; width: 100%; vertical-align: middle;font-weight:bold; font-size:72px"
                    class="text-left form-control form-control-sm cust-border-bottom" id="number">
                    <h5>Dhule</h5>
                </td>

            </tr>

        </table>
        <!-- <div class="row d-flex text-center ">
            <div class="col-md-6">
                <h5>Teacher</h5>
            </div>
            <div class="col-md-6">
                <h5>Principal</h5>
            </div>
        </div> -->
        <table class="table stu-det text-center d-n" style="margin-top: 10%;">


            <tr>

                <td style="border: none;">

                    <span class="font-weight-bold" style="font-size: medium;">
                        <h5>Teacher</h5>
                    </span>

                </td>

                <td style="border: none;" class="text-center">
                    <span class="font-weight-bold" style="font-size: medium;">
                        <h5>Principal</h5>
                    </span>
                </td>
            </tr>


        </table>


        <!-- </div>
        </div> -->
    </div>



</body>



</html>