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
                            <h5 class="card-title">Monthly Fees Report</h5>
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

                                                <input type="date" name="from_date" id="from_date"
                                                    class="form-control form-control-sm col-md-7" value="<?= $from ?>">
                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">To Date <span
                                                        class="float-right">:</span></label>

                                                <input type="date" name="to_date" id="to_date"
                                                    class="form-control form-control-sm col-md-7" value="<?= $to ?>">
                                            </div>
                                        </div>

                                        <!-- 3 -->
                                        <div class="col-md-4">
                                            <button id="filter-btn" type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                onclick="generate_report()">Search</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Cash</th>
                                                    <th>Online</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 0;
                                                foreach ($records as $record):
                                                    ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><?= $record['fees_date'] ?? 0 ?></td>
                                                        <td><?= $record['cash_collection'] ?? 0 ?></td>
                                                        <td><?= $record['online_collection'] ?? 0 ?></td>
                                                        <td><?= $record['total_collection'] ?? 0 ?></td>
                                                    </tr>
                                                    <?php $i++; endforeach; ?>
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
        function generate_report() {
            let from_date = document.getElementById("from_date").value;
            let to_date = document.getElementById("to_date").value;

            window.location.href = `<?= base_url() ?>Report/monthly_fees?from=${from_date}&to=${to_date}`;
        }
    </script>
</body>

</html>