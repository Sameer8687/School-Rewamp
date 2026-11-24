<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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

    <?php include 'application/views/sidebar.php'; ?>
    <?php include 'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-4">
                                <h5 class="card-title">Current Year Fees Report</h5>
                            </div>

                            <div class="col-md-8 text-end">
                                <?php
                                $cash_total = 0;
                                $online_total = 0;

                                foreach ($records as $row) {
                                    if (strtolower(trim($row['payment_mode'])) == 'cash') {
                                        $cash_total += $row['sprfees_amount'];
                                    } else {
                                        $online_total += $row['sprfees_amount'];
                                    }
                                }

                                $grand_total = $cash_total + $online_total;
                                ?>

                                <span class="text-success p-2">
                                    Cash : ₹
                                    <?= number_format($cash_total); ?>
                                </span>

                                <span class="text-primary p-2">
                                    Online : ₹
                                    <?= number_format($online_total); ?>
                                </span>

                                <span class="text-danger p-2">
                                    Total : ₹
                                    <?= number_format($grand_total); ?>
                                </span>
                            </div>

                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="alert alert-success">#</th>
                                                    <th class="alert alert-success">Name</th>
                                                    <th class="alert alert-success">Payment Type</th>
                                                    <th class="alert alert-success">Amount</th>
                                                    <th class="alert alert-success">Year</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php if (!empty($records)): ?>
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($records as $fees): ?>
                                                        <tr>
                                                            <td><?= $i++; ?></td>
                                                            <td><?= $fees['name']; ?></td>
                                                            <td><?= $fees['payment_mode']; ?></td>
                                                            <td><?= number_format($fees['sprfees_amount'], 2); ?></td>
                                                            <td><?= $fees['stvacdm_year']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            No Record Found
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
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


        <!---Include JS Files-->
        <?php include 'application/views/jsPages.php'; ?>

</body>

</html>