<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">


<head>

    <title>Teacher Activity Dashboard</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />



    <!---Include CSS Files-->

    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            /* overflow-x: hidden;
            overflow-y: hidden; */
        }

        .demo {
            font-family: 'Noto Sans', sans-serif;
        }

        /* -----------------------PANEL------------------------------- */
        .panel {
            background: linear-gradient(to right, #2980b9, #2c3e50);
            /* background: linear-gradient(to right, #8e44ad, #e74c3c); */
            padding: 0px;
            border-radius: 0px;
            border: none;
            margin-top: -5px;
            /* box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.05), 0 0 0 10px rgba(0, 0, 0, 0.05); */
            width: 100% !important;
            max-height: min-content;
        }

        .panel .panel-body {
            padding: 0;
        }

        .panel .panel-body .table thead tr th {
            color: #fff;
            background-color: rgba(250, 250, 250, 0.2);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            padding: 5px;
            border: none;
        }

        .panel .panel-body .table tbody tr td {
            color: #fff;
            font-size: 11px;
            padding: 1px 1px;
            vertical-align: middle;
            border: none;
        }

        .panel .panel-body .table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 5px !important;
        }

        .panel .panel-body .table tbody .action-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .panel .panel-body .table tbody .action-list li {
            display: inline-block;
            margin: 0 5px;
        }

        .panel .panel-body .table tbody .action-list li a {
            color: #fff;
            font-size: 15px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease 0s;
        }

        .panel .panel-body .table tbody .action-list li a:hover {
            text-shadow: 3px 3px 0 rgba(255, 255, 255, 0.3);
        }

        .panel .panel-body .table tbody .action-list li a:before,
        .panel .panel-body .table tbody .action-list li a:after {
            content: attr(data-tip);
            color: #fff;
            background-color: #111;
            font-size: 12px;
            padding: 5px 7px;
            border-radius: 4px;
            text-transform: capitalize;
            display: none;
            transform: translateX(-50%);
            position: absolute;
            left: 50%;
            top: -32px;
            transition: all 0.3s ease 0s;
        }

        .panel .panel-body .table tbody .action-list li a:after {
            content: '';
            height: 15px;
            width: 15px;
            padding: 0;
            border-radius: 0;
            transform: translateX(-50%) rotate(45deg);
            top: -18px;
            z-index: -1;
        }

        .panel .panel-body .table tbody .action-list li a:hover:before,
        .panel .panel-body .table tbody .action-list li a:hover:after {
            display: block;
        }

        .panel .panel-footer {
            color: #fff;
            background-color: transparent;
            padding: 15px;
            border: none;
        }

        .panel .panel-footer .col {
            line-height: 35px;
        }

        /* ---------------------------------------------- remarks-------------------------- */
        .remarks {
            background: linear-gradient(to right, #2980b9, #2c3e50);
            padding: 0px;
            border-radius: 0px;
            border: none;
            margin-top: -5px;
            box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.05), 0 0 0 10px rgba(0, 0, 0, 0.05);
            width: 100% !important;
            max-height: min-content;
        }

        .remarks .remarks-body {
            padding: 0;
        }

        .remarks .remarks-body .table thead tr th {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            padding: 5px;
            border: none;
        }

        .remarks .remarks-body .table tbody tr td {
            color: #fff;
            font-size: 11px;
            padding: 1px 1px;
            vertical-align: middle;
            border: none;
        }

        .remarks .remarks-body .table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 5px !important;
        }

        .remarks .remarks-body .table tbody .action-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .remarks .remarks-body .table tbody .action-list li {
            display: inline-block;
            margin: 0 5px;
        }

        .remarks .remarks-body .table tbody .action-list li a {
            color: #fff;
            font-size: 15px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease 0s;
        }

        .remarks .remarks-body .table tbody .action-list li a:hover {
            text-shadow: 3px 3px 0 rgba(255, 255, 255, 0.3);
        }

        .remarks .remarks-body .table tbody .action-list li a:before,
        .remarks .remarks-body .table tbody .action-list li a:after {
            content: attr(data-tip);
            color: #fff;
            background-color: #111;
            font-size: 12px;
            padding: 5px 7px;
            border-radius: 4px;
            text-transform: capitalize;
            display: none;
            transform: translateX(-50%);
            position: absolute;
            left: 50%;
            top: -32px;
            transition: all 0.3s ease 0s;
        }

        .remarks .remarks-body .table tbody .action-list li a:after {
            content: '';
            height: 15px;
            width: 15px;
            padding: 0;
            border-radius: 0;
            transform: translateX(-50%) rotate(45deg);
            top: -18px;
            z-index: -1;
        }

        .remarks .remarks-body .table tbody .action-list li a:hover:before,
        .remarks .remarks-body .table tbody .action-list li a:hover:after {
            display: block;
        }

        .remarks .remarks-footer {
            color: #fff;
            background-color: transparent;
            padding: 15px;
            border: none;
        }

        .remarks .remarks-footer .col {
            line-height: 35px;
        }


        /* ------------------------------------------------------- */

        /* ---------------------------------------------- fees-------------------------- */
        .fees {
            /* background: linear-gradient(to right, #2980b9, #27ae60); */
            background: linear-gradient(to right, #2980b9, #2c3e50);
            padding: 2px;
            border-radius: 0px;
            border: none;
            margin-top: -5px;
            width: 100% !important;
            max-height: min-content;
        }

        .fees .fees-body {
            padding: 0;
        }

        .fees .fees-body .table thead tr th {
            color: #fff;
            background-color: rgba(250, 250, 250, 0.2);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            padding: 5px;
            border: none;
        }

        .fees .fees-body .table tbody tr td {
            color: #fff;
            font-size: 11px;
            padding: 1px 1px;
            vertical-align: middle;
            border: none;
        }

        .fees .fees-body .table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 5px !important;
        }

        .fees .fees-body .table tbody .action-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .fees .fees-body .table tbody .action-list li {
            display: inline-block;
            margin: 0 5px;
        }

        .fees .fees-body .table tbody .action-list li a {
            color: #fff;
            font-size: 15px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease 0s;
        }

        .fees .fees-body .table tbody .action-list li a:hover {
            text-shadow: 3px 3px 0 rgba(255, 255, 255, 0.3);
        }

        .fees .fees-body .table tbody .action-list li a:before,
        .fees .fees-body .table tbody .action-list li a:after {
            content: attr(data-tip);
            color: #fff;
            background-color: #111;
            font-size: 12px;
            padding: 5px 7px;
            border-radius: 4px;
            text-transform: capitalize;
            display: none;
            transform: translateX(-50%);
            position: absolute;
            left: 50%;
            top: -32px;
            transition: all 0.3s ease 0s;
        }

        .fees .fees-body .table tbody .action-list li a:after {
            content: '';
            height: 15px;
            width: 15px;
            padding: 0;
            border-radius: 0;
            transform: translateX(-50%) rotate(45deg);
            top: -18px;
            z-index: -1;
        }

        .fees .fees-body .table tbody .action-list li a:hover:before,
        .fees .fees-body .table tbody .action-list li a:hover:after {
            display: block;
        }

        .fees .fees-footer {
            color: #fff;
            background-color: transparent;
            padding: 15px;
            border: none;
        }

        .fees .fees-footer .col {
            line-height: 35px;
        }


        /* ------------------------------------------------------- */
        /* ---------------------------------------------- event-------------------------- */
        .event {
            background: linear-gradient(to right, #2980b9, #2c3e50);
            /* background: linear-gradient(to right, #d92b2b, #3b6bbb); */

            padding: 2px;
            border-radius: 0px;
            border: none;
            margin-top: -5px;
            width: 100% !important;
            max-height: min-content;
        }

        .event .event-body {
            padding: 0;
        }

        .event .event-body .table thead tr th {
            color: #fff;
            background-color: rgba(250, 250, 250, 0.2);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            padding: 5px;
            border: none;
        }

        .event .event-body .table tbody tr td {
            color: #fff;
            font-size: 11px;
            padding: 1px 1px;
            vertical-align: middle;
            border: none;
        }

        .event .event-body .table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 5px !important;
        }

        .event .event-body .table tbody .action-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .event .event-body .table tbody .action-list li {
            display: inline-block;
            margin: 0 5px;
        }

        .event .event-body .table tbody .action-list li a {
            color: #fff;
            font-size: 15px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease 0s;
        }

        .event .event-body .table tbody .action-list li a:hover {
            text-shadow: 3px 3px 0 rgba(255, 255, 255, 0.3);
        }

        .event .event-body .table tbody .action-list li a:before,
        .event .event-body .table tbody .action-list li a:after {
            content: attr(data-tip);
            color: #fff;
            background-color: #111;
            font-size: 12px;
            padding: 5px 7px;
            border-radius: 4px;
            text-transform: capitalize;
            display: none;
            transform: translateX(-50%);
            position: absolute;
            left: 50%;
            top: -32px;
            transition: all 0.3s ease 0s;
        }

        .event .event-body .table tbody .action-list li a:after {
            content: '';
            height: 15px;
            width: 15px;
            padding: 0;
            border-radius: 0;
            transform: translateX(-50%) rotate(45deg);
            top: -18px;
            z-index: -1;
        }

        .event .event-body .table tbody .action-list li a:hover:before,
        .event .event-body .table tbody .action-list li a:hover:after {
            display: block;
        }

        .event .event-footer {
            color: #fff;
            background-color: transparent;
            padding: 15px;
            border: none;
        }

        .event .event-footer .col {
            line-height: 35px;
        }


        /* ------------------------------------------------------- */

        .pagination {
            margin: 0;
        }

        .pagination li a {
            color: #fff;
            background-color: transparent;
            border: 2px solid transparent;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            line-height: 31px;
            width: 35px;
            height: 35px;
            padding: 0;
            margin: 0 3px;
            border-radius: 50px;
            transition: all 0.3s ease 0s;
        }

        .pagination li a:hover {
            color: #fff;
            background-color: transparent;
            border-color: rgba(255, 255, 255, 0.2);
        }

        .pagination li a:focus,
        .pagination li.active a,
        .pagination li.active a:hover {
            color: #fff;
            background-color: transparent;
            border-color: #fff;
        }

        .pagination li:first-child a,
        .pagination li:last-child a {
            border-radius: 50%;
        }

        @media only screen and (max-width:767px) {
            .panel .panel-heading .title {
                text-align: center;
                margin: 0 0 10px;
            }

            .panel .panel-heading .btn_group {
                text-align: center;
            }

            .panel .panel-body .table thead tr th .fixed-header {
                position: sticky !important;
                top: 0 !important;
                background-color: #f0f2f8 !important;
                /* Adjust the background color as needed */
                z-index: 1 !important;
            }

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





    <!-- [ Main Content ] start -->

    <div class="container-fluid">
        <form action="" id="classCreateForm" method="post">
            <div class="row mt-3 mb-3">
                <div class="col-md-4"></div>
                <div class="col-md-4 text-center">
                    <h3 class="font-weight-bold" style="color:#2a79ae">Teacher's Activity Dashboard</h3>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-1"><a href="<?=base_url()?>" class="btn btn-sm" style="background-color:#2E3A46;color:white">Home</a></div>
            </div>

            <div class="row ">
                <div class="col-md-3 d-flex text-center font-weight-bold" style="color:#2E3A46">
                    <label>Teacher</label>
                    <select class="form-control form-control-sm ml-1 w-80" name="t_name" id="t_name">
                        <option>Select Teacher</option>
                        <?php foreach ($teachers as $teach) : ?>
                            <option <?= $t_id == $teach['id'] ? 'selected' : '' ?> value="<?= $teach['id'] ?>"><?= $teach['NAME'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-3 d-flex text-center font-weight-bold">
                    <label>From </label>
                    <input type="date" class="form-control form-control-sm ml-2" name="from_date" id="from_date" value="<?= $from ?>">
                </div>

                <div class="col-md-3 d-flex text-center font-weight-bold">
                    <label>To </label>
                    <input type="date" class="form-control form-control-sm ml-2" name="to_date" id="to_date" value="<?= $to ?>">
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-primary" name="view_button" id="view_button">View</button>
                </div>
            </div>

            <!-- <div class="card mt-2 p-1" style="background-color:#a5b6c7;"> -->
            <div class="card mt-2 p-1" style="background-color:#a5b6c7;">
                <div class="row mt-2 p-1 ">
                    <div class="col-md-6">
                        <div class="card text-center" style="height:270px">
                            <div class="card-header font-weight-bold" style="background-color:#f0f2f8;min-height:0px;vertical-align:middle">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <h4 class="font-weight-bold mt-1" style="color:#2a79ae"> Attendance
                                    </h4>
                                </div>
                                <div class="col-md-4"></div>

                            </div>
                            <div class="attend card-body" id="attend" style="padding: 10px !important; background-color:#f0f2f8">
                                <div class="panel">
                                    <div class="panel-body table-responsive" style="max-height: 220px;overflow-y: scroll; scrollbar-width: thin; scrollbar-color: transparent transparent;height:220px">
                                        <table class="table">
                                            <thead class="fixed-header">
                                                <tr>
                                                    <th>Sr.No</th>
                                                    <th>Activity Date</th>
                                                    <th>Activity Time</th>
                                                    <th>Action Date</th>
                                                    <th>Present</th>
                                                    <th>Absent</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                if ($attend != null) {
                                                    foreach ($attend as $att) :
                                                ?>
                                                        <tr>
                                                            <td><?= $i ?></td>
                                                            <td><?= $att['activity_date'] ?></td>
                                                            <td><?= $att['activity_time'] ?></td>
                                                            <td><?= $att['action_date'] ?></td>
                                                            <td><?= $att['Present'] ?></td>
                                                            <td><?= $att['Absent'] ?></td>

                                                        </tr>
                                                    <?php
                                                        $i++;
                                                    endforeach;
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="6"><img style="height: 135px;width:200px" class="mt-2" src="<?= base_url('assets/images/no-datas.png') ?>"></td>
                                                    </tr>
                                                <?php } ?>


                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card text-center" style="height:270px">
                            <div class="card-header font-weight-bold" style="background-color:#f0f2f8;min-height:0px;vertical-align:middle">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <h4 class="font-weight-bold mt-1" style="color:#2a79ae"> Fees Alert
                                    </h4>
                                </div>
                                <div class="col-md-4"></div>

                            </div>
                            <div class="feess card-body" id="attend" style="padding: 10px !important; background-color:#f0f2f8">
                                <div class="fees">
                                    <div class="fees-body table-responsive" style="max-height: 210px;overflow-y: scroll; scrollbar-width: thin; scrollbar-color: transparent transparent;height:215px">
                                        <table class="table">
                                            <thead class="fixed-header">
                                                <tr>
                                                    <th>Activity Date</th>
                                                    <th>Activity Time</th>
                                                    <th>Student</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($fees_alert != null) {
                                                    foreach ($fees_alert as $alrt) :
                                                        $students = [];
                                                        if ($alrt['student_names'] != '')
                                                            $students = explode(",", $alrt['student_names']);
                                                        $rows = count($students);
                                                        $counter = 0;
                                                ?>
                                                        <?php
                                                        for ($i = 0; $i < $rows; $i++) {
                                                            if ($counter == 0) :
                                                        ?>
                                                                <tr>
                                                                    <td rowspan="<?= $rows ?>"><?= $alrt['activity_date'] ?></td>
                                                                    <td rowspan="<?= $rows ?>"><?= $alrt['activity_time'] ?></td>
                                                                    <td class="text-left"><?= $students[$i] ?></td>
                                                                </tr>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td class="text-left"><?= $students[$i] ?></td>
                                                                </tr>
                                                        <?php
                                                            endif;
                                                            $counter++;
                                                        }
                                                        ?>
                                                    <?php
                                                    endforeach;
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="3"><img style="height: 135px;width:200px" class="mt-2" src="<?= base_url('assets/images/no-datas.png') ?>"></td>
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
                <div class="row mt-1 p-1 " style="margin-top:-25px !important">
                    <div class="col-md-12">
                        <div class="card text-center" style="height:270px">
                            <div class="card-header font-weight-bold" style="background-color:#f0f2f8;min-height:0px;vertical-align:middle">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <h4 class="font-weight-bold mt-1" style="color:#2a79ae"> Remark
                                    </h4>
                                </div>
                                <div class="col-md-4"></div>

                            </div>
                            <div class="remark card-body" id="attend" style="padding: 10px !important; background-color:#f0f2f8">
                                <div class="remarks">
                                    <div class="remarks-body table-responsive" style="max-height: 210px;overflow-y: scroll; scrollbar-width: thin; scrollbar-color: transparent transparent;height:215px">
                                        <table class="table">
                                            <thead class="fixed-header">
                                                <tr>
                                                    <th>Activity Date</th>
                                                    <th>Activity Time</th>
                                                    <th>Student</th>
                                                    <th>Remark</th>
                                                    <th>Reply</th>
                                                    <th>Reply Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($remark != null) {
                                                    foreach ($remark as $rmrk) :
                                                        $students = [];
                                                        if ($rmrk['student_names'] != '')
                                                            $students = explode(",", $rmrk['student_names']);
                                                        $st_rmrk = explode(",", $rmrk['remarks']);
                                                        $rmrk_reply = explode(",", $rmrk['replies']);
                                                        $rmrk_rep_date = explode(",", $rmrk['reply_dates']);
                                                        $rows = count($students);
                                                        $counter = 0;
                                                ?>
                                                        <?php
                                                        for ($i = 0; $i < $rows; $i++) {
                                                            if ($counter == 0) :
                                                        ?>
                                                                <tr>
                                                                    <td rowspan="<?= $rows ?>"><?= $rmrk['activity_date'] ?></td>
                                                                    <td rowspan="<?= $rows ?>"><?= $rmrk['activity_time'] ?></td>
                                                                    <td class="text-left"><?= $students[$i] ?></td>
                                                                    <td class="text-left"><?= $st_rmrk[$i] ?></td>
                                                                    <td class="text-left"><?= $rmrk_reply[$i] ?></td>
                                                                    <td class="text-left"><?= $rmrk_rep_date[$i] ?></td>
                                                                </tr>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td class="text-left"><?= $students[$i] ?></td>
                                                                    <td class="text-left"><?= $st_rmrk[$i] ?></td>
                                                                    <td class="text-left"><?= $rmrk_reply[$i] ?></td>
                                                                    <td class="text-left"><?= $rmrk_rep_date[$i] ?></td>
                                                                </tr>
                                                        <?php
                                                            endif;
                                                            $counter++;
                                                        }
                                                        ?>
                                                    <?php endforeach;
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="6"><img style="height: 135px;width:200px" class="mt-2" src="<?= base_url('assets/images/no-datas.png') ?>"></td>
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
                <div class="row mt-1 p-1 " style="margin-top:-30px !important">

                    <div class="col-md-12">
                        <div class="card text-center" style="height:270px">
                            <div class="card-header text-center font-weight-bold" style="background-color:#f0f2f8;min-height:0px;vertical-align:middle">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <h4 class="font-weight-bold mt-1" style="color:#2a79ae"> Event
                                    </h4>
                                </div>
                                <div class="col-md-4"></div>

                            </div>
                            <div class="events card-body" id="attend" style="padding: 10px !important; background-color:#f0f2f8">
                                <div class="event">
                                    <div class="event-body table-responsive" style="max-height: 210px;overflow-y: scroll; scrollbar-width: thin; scrollbar-color: transparent transparent; height:215px">
                                        <table class="table">
                                            <thead class="fixed-header">
                                                <tr>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Title</th>
                                                    <th>Details</th>
                                                    <th>Std</th>
                                                    <th>Div</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($event != null) {
                                                    foreach ($event as $evnt) :
                                                ?>

                                                        <tr>
                                                            <td><?= $evnt['stvevnt_start_date'] ?></td>
                                                            <td><?= $evnt['stvevnt_end_date'] ?></td>
                                                            <td class="text-left"><?= $evnt['stvevnt_title'] ?></td>
                                                            <td class="text-left"><?= $evnt['stvevnt_details'] ?></td>
                                                            <td><?= $evnt['stvevnt_std'] ?></td>
                                                            <td><?= $evnt['stvevnt_academic_year'] ?></td>
                                                        </tr>

                                                    <?php endforeach;
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="6"><img style="height: 135px;width:200px" class="mt-2 text-center" src="<?= base_url('assets/images/no-datas.png') ?>"></td>
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


        </form>
        <!-- [ Main Content ] end -->
    </div>
    </div>
    <!-- [ Main Content ] end -->
    </div>
    </div>
    <!---Include JS Files-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $("#table-gr").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    </script>

    <!-- <script>
        var options = {
            series: [44, 15],
            chart: {
                type: 'pie',
                width: '100%', // Set width to 100% to make it responsive
                height: '100vh', // Set height to 100% to make it responsive
            },
            labels: ['Present', 'Absent'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: '100%' // Set width to 100% for responsiveness at this breakpoint
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#attend"), options);
        chart.render(); -->
    </script>


    <script>
        var view_button = document.getElementById("view_button");
        let base_url = '<?= base_url() ?>';
        view_button.addEventListener("click", function() {
            var t_name = document.getElementById("t_name").value;
            var from_d = document.getElementById("from_date").value;
            var to_d = document.getElementById("to_date").value;

            console.log(t_name);
            console.log(from_d);
            console.log(to_d);

            window.location = `${base_url}Report/teacher_activity_db?teacher_name=${t_name}&from=${from_d}&to=${to_d}`;
        });
    </script>


</body>



</html>