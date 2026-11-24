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
                            <h5 class="card-title">Teacher Activity</h5>

                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">From Date
                                                    <span class="float-right">:</span></label>

                                                <input class="form-control form-control-sm col-md-7" type="date"
                                                    name="from_date" id="from_date" value="<?= date('Y-m-d') ?>">

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Academic year
                                                    <span class="float-right">:</span></label>

                                                <input class="form-control form-control-sm col-md-7" type="date"
                                                    name="to_date" id="to_date" value="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">

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
                                                    <th>#</th>
                                                    <th>Student ID</th>
                                                    <th>Name</th>
                                                    <th>Activity Date</th>
                                                    <th>Activity Time</th>
                                                    <th>Activity Sorce</th>
                                                    <th>Action Date</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 1;
                                                // print_r($application);
                                                foreach ($activity as $activ): ?>
                                                    <tr>
                                                        <td><?= $i ?></td>

                                                        <td><?= $activ['SPRIDEN_student_id'] ?></td>
                                                        <td><?= $activ['name'] ?></td>
                                                        <td><?= $activ['activity_date'] ?></td>
                                                        <td><?= $activ['activity_time'] ?></td>
                                                        <td><?= $activ['activity_sorce'] ?></td>
                                                        <td><?= $activ['action_date'] ?></td>
                                                    </tr>
                                                    <?php $i++;
                                                endforeach; ?>
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
        var view_button = document.getElementById("view_button");
        let base_url = '<?= base_url() ?>';
        view_button.addEventListener("click", function () {
            // Retrieve the value of the 'type' select element inside the event listener
            var from_d = document.getElementById("from_date").value;
            var to_d = document.getElementById("to_date").value;
            console.log(from_d);
            console.log(to_d);
            window.location = `${base_url}Report/teacher_activity?from=${from_d}&to=${to_d}`;
        });
    </script>

</html>