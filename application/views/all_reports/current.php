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
                            <h5 class="card-title">Inactive Students Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <!-- 1 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">From Date <span
                                                        class="float-right">:</span></label>

                                                <input type="date" name="todate" id="todate"
                                                    class="form-control form-control-sm col-md-7"
                                                    value="<?= $to_date != '' ? $to_date : '' ?>">
                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">To Date <span
                                                        class="float-right">:</span></label>

                                                <input type="date" name="fromdate" id="fromdate"
                                                    class="form-control form-control-sm col-md-7"
                                                    value="<?= $from_date != '' ? $from_date : '' ?>">
                                            </div>
                                        </div>

                                        <!-- 3 -->
                                        <div class="col-md-4">
                                            	<button class="btn btn-sm btn-outline-secondary" id="apply" onclick="filter_data()">Apply</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr class="text-center">
                                                    <th class="text-capitalize">Sr.No</th>
                                                    <th class="text-capitalize">ID</th>
                                                    <th class="text-capitalize">Student ID</th>
                                                    <th class="text-left text-capitalize">Name</th>
                                                    <th class="text-capitalize">End Date</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php
                                                $i = 1;
                                                foreach ($students as $student):
                                                    ?>
                                                    <tr>
                                                        <td><?= $i ?></td>
                                                        <td><?= $student['id'] ?></td>
                                                        <td><?= $student['SPRIDEN_student_id'] ?></td>
                                                        <td><?= $student['SPRIDEN_surname'] . ' ' . $student['SPRIDEN_student_name'] . ' ' . $student['SPRIDEN_father_name'] ?>
                                                        </td>
                                                        <td><?= date('d-M-y', strtotime($student['spriden_status_end_date'])) ?>
                                                        </td>
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
        function filter_data() {
            let to_date = document.getElementById('todate').value;
            let from_date = document.getElementById('fromdate').value;
            if (to_date !== '' && from_date !== '') {
                window.location.href = `<?= base_url() ?>Report/current_inactive?to_date=${to_date}&from_date=${from_date}`;
            }
        }
    </script>
</body>

</html>