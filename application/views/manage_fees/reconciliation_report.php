<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$bank_total = 0;
$daily_total = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Reconciliation Report</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?php include 'application/views/cssPages.php'; ?>
    <style>
        :root {
            /* Same theme, only responsive layout/scroll behavior. */
            --fees-card-height: 420px;
            --fees-card-min-height: 340px;
            --fees-card-viewport-offset: 220px;
        }

        html,
        body {
            height: auto;
            min-height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
        }

        .pc-container {
            top: 0px !important;
            height: auto !important;
            min-height: calc(100vh - 34px);
            min-height: calc(100dvh - 34px);
            overflow: visible !important;
        }

        .pcoded-content {
            padding: 8px !important;
            overflow: visible;
        }

        .table tr td,
        .table tr th {
            padding: 5px !important;
            font-size: 11px !important;
            vertical-align: middle !important;
        }

        .fees-filter label {
            font-size: 12px;
            margin-bottom: 2px;
        }

        .fees-page {
            width: 100%;
            min-height: calc(100vh - 50px);
            min-height: calc(100dvh - 50px);
            overflow: visible;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .fees-page>.row {
            min-width: 0;
        }

        .fees-page>.row:not(.fees-cards-row) {
            flex-shrink: 0;
        }

        .fees-cards-row {
            flex: 0 0 auto;
            height: clamp(var(--fees-card-min-height), calc(100dvh - var(--fees-card-viewport-offset)), var(--fees-card-height));
            min-height: var(--fees-card-min-height);
            overflow: visible;
            align-items: stretch;
            row-gap: 12px;
        }

        .fees-card-col {
            display: flex;
            height: 100%;
            min-height: 0;
            min-width: 0;
        }

        .fees-card {
            width: 100%;
            height: 100%;
            max-height: 100%;
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }

        .fees-card .card-header,
        .fees-card .card-filter,
        .fees-card .fees-total {
            flex: 0 0 auto;
        }

        .fees-card .card-body {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
            overflow: hidden;
        }

        .fees-filter {
            row-gap: 8px;
        }

        .fees-filter .form-control,
        .fees-filter .btn {
            min-height: 31px;
        }

        .fees-table-wrap {
            flex: 1 1 auto;
            min-height: 0;
            max-height: none;
            overflow-y: auto;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
        }

        .fees-table-wrap table {
            margin-bottom: 0;
            min-width: 560px;
            white-space: nowrap;
        }

        .fees-table-wrap thead th {
            position: sticky;
            top: 0;
            background: #fff;
            /* z-index: 2; */
        }

        .fees-table-wrap .form-control-sm {
            min-width: 130px;
        }

        .fees-total {
            font-weight: 600;
            font-size: 13px;
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding-top: 8px;
            margin-top: 8px;
        }

        .fees-title-row {
            row-gap: 8px;
        }

        .fees-title-row h5 {
            line-height: 30px;
        }

        .fees-action-col {
            text-align: right;
        }

        @media only screen and (max-width: 991px) {
            :root {
                --fees-card-height: 460px;
                --fees-card-min-height: 360px;
            }

            .fees-page {
                display: block;
            }

            .fees-cards-row {
                height: auto;
                min-height: 0;
                overflow: visible;
            }

            .fees-card-col {
                height: min(var(--fees-card-height), 72dvh);
                min-height: var(--fees-card-min-height);
                margin-bottom: 12px;
            }

            .fees-card {
                height: 100%;
            }
        }

        @media only screen and (max-width: 575px) {
            :root {
                --fees-card-height: 430px;
                --fees-card-min-height: 340px;
            }

            .pcoded-content {
                padding: 6px !important;
            }

            .fees-action-col,
            .fees-title-row h5 {
                text-align: left !important;
            }

            .fees-action-col {
                margin-top: 0;
            }

            .fees-filter .btn {
                width: 100%;
            }

            .fees-table-wrap table {
                min-width: 520px;
            }
        }

        @media only screen and (max-height: 650px) and (min-width: 992px) {
            :root {
                --fees-card-min-height: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <?php include 'application/views/sidebar.php'; ?>
    <?php include 'application/views/topNav.php'; ?>

    <div class="pc-container">
        <div class="pcoded-content fees-page">
            <div class="row mb-2 fees-title-row">
                <div class="col-sm-6 col-12">
                    <h5 class="mb-0">Reconciliation Report</h5>
                </div>
                <div class="col-sm-6 col-12 fees-action-col">
                    <a href="<?= base_url('manage-fees/transactions'); ?>" class="btn btn-sm btn-outline-primary">Manage
                        Fees Transaction</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12" id="fees-alert"></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row fees-filter align-items-end">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                    <label>From Date</label>
                                    <input type="date" class="form-control form-control-sm" id="report_from_date"
                                        value="<?= html_escape($from_date); ?>">
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                    <label>To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="report_to_date"
                                        value="<?= html_escape($to_date); ?>">
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-12 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="report-view">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row fees-cards-row">
                <div class="col-md-6 fees-card-col">
                    <div class="card fees-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Bank Transaction</h5>
                        </div>
                        <div class="card-body">
                            <div class="dt-responsive fees-table-wrap">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-capitalize">Date</th>
                                            <th class="text-capitalize">Description</th>
                                            <th class="text-capitalize">Type</th>
                                            <th class="text-capitalize text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bank-transaction-body">
                                        <?php if (!empty($bank_transactions)) { ?>
                                            <?php foreach ($bank_transactions as $row) { ?>
                                                <?php $bank_total += (float) $row->transaction_amount; ?>
                                                <tr>
                                                    <td><?= html_escape($row->value_date); ?></td>
                                                    <td><?= html_escape($row->txn_posted_date); ?></td>
                                                    <td><?= html_escape($row->mode); ?></td>
                                                    <td class="text-right">
                                                        <?= number_format((float) $row->transaction_amount, 2); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No records found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right fees-total">Total: <span id="bank-total">Rs.
                                    <?= number_format($bank_total); ?></span></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 fees-card-col">
                    <div class="card fees-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Daily Fees</h5>
                        </div>
                        <div class="card-body">
                            <div class="dt-responsive fees-table-wrap">
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-capitalize">SName</th>
                                            <th class="text-capitalize text-right">Amt</th>
                                            <th class="text-capitalize">Date</th>
                                            <th class="text-capitalize">Mode</th>
                                        </tr>
                                    </thead>
                                    <tbody id="daily-fees-body">
                                        <?php if (!empty($daily_fees)) { ?>
                                            <?php foreach ($daily_fees as $row) { ?>
                                                <?php $daily_total += $row->sprfees_amount; ?>
                                                <tr>
                                                    <td><?= html_escape($row->name); ?></td>
                                                    <td class="text-right">Rs.
                                                        <?= number_format($row->sprfees_amount); ?>
                                                    </td>
                                                    <td><?= html_escape($row->sprfees_feesdate); ?></td>
                                                    <td><?= html_escape($row->payment_mode); ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No records found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right fees-total">Total: <span id="daily-total">Rs.
                                    <?= number_format($daily_total); ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        var base_url = '<?= base_url(); ?>';
        function showFeesMessage(message, type) {
            var alertType = type || 'success';
            $('#fees-alert').html('<div class="alert alert-' + alertType + ' py-1 px-2 mb-0">' + message + '</div>');
            setTimeout(function () {
                $('#fees-alert').html('');
            }, 2500);
        }

        function resetFeesTableScroll(selector) {
            $(selector).closest('.fees-table-wrap').scrollTop(0).scrollLeft(0);
        }

        function validateDates(fromDate, toDate) {
            if (!fromDate || !toDate) {
                showFeesMessage('From Date and To Date are required', 'danger');
                return false;
            }

            if (fromDate > toDate) {
                showFeesMessage('From Date should not be greater than To Date', 'danger');
                return false;
            }

            return true;
        }

        $('#report-view').on('click', function () {
            var fromDate = $('#report_from_date').val();
            var toDate = $('#report_to_date').val();

            if (!validateDates(fromDate, toDate)) {
                return;
            }

            $.ajax({
                url: base_url + 'manage-fees/reconciliation-report-data',
                type: 'POST',
                dataType: 'json',
                data: {
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function (response) {
                    if (response.status) {
                        $('#bank-transaction-body').html(response.bank_html);
                        $('#bank-total').text(response.bank_total);
                        $('#daily-fees-body').html(response.daily_html);
                        $('#daily-total').text(response.daily_total);
                        resetFeesTableScroll('#bank-transaction-body');
                        resetFeesTableScroll('#daily-fees-body');
                    } else {
                        showFeesMessage(response.message, 'danger');
                    }
                },
                error: function () {
                    showFeesMessage('Something went wrong while fetching reconciliation report.', 'danger');
                }
            });
        });
    </script>
</body>

</html>