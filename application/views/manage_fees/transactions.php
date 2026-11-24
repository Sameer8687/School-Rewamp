<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$bank_total = 0;
$daily_total = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Manage Fees Transaction</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?php include 'application/views/cssPages.php'; ?>
    <style>
        :root {
            /* Same theme, only responsive layout/scroll behavior. */
            --fees-card-height: 520px;
            --fees-card-min-height: 340px;
            --fees-card-viewport-offset: 130px;
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

        .daily-fees-date {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .daily-fees-date.fees-date-success {
            border-color: #36a852 !important;
            box-shadow: 0 0 0 0.08rem rgba(54, 168, 82, 0.2) !important;
        }

        .daily-fees-date.fees-date-error {
            border-color: #f1aeb5 !important;
            box-shadow: 0 0 0 0.08rem rgba(220, 53, 69, 0.12) !important;
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
                    <h5 class="mb-0">Manage Fees Transaction</h5>
                </div>
                <div class="col-sm-6 col-12 fees-action-col">
                    <a href="<?= base_url('manage-fees/reconciliation-report'); ?>"
                        class="btn btn-sm btn-outline-primary">Reconciliation Report</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-12" id="fees-alert"></div>
            </div>

            <div class="row fees-cards-row">
                <div class="col-md-6 fees-card-col pr-1">
                    <div class="card fees-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Bank Transaction</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="row fees-filter align-items-end mb-2 card-filter">
                                <div class="col-md-4">
                                    <label>From Date</label>
                                    <input type="date" class="form-control form-control-sm" id="bank_from_date"
                                        value="<?= html_escape($from_date); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label>To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="bank_to_date"
                                        value="<?= html_escape($to_date); ?>">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="bank-view">View</button>
                                </div>
                            </div>
                            <div class="dt-responsive fees-table-wrap">
                                <table id="bank-table" class="table table-striped table-bordered nowrap">
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
                                                        <?= number_format((float) $row->transaction_amount); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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

                <div class="col-md-6 fees-card-col pl-1">
                    <div class="card fees-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Daily Fees</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="row fees-filter align-items-end mb-2 card-filter">
                                <div class="col-md-4">
                                    <label>From Date</label>
                                    <input type="date" class="form-control form-control-sm" id="daily_from_date"
                                        value="<?= html_escape($from_date); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label>To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="daily_to_date"
                                        value="<?= html_escape($to_date); ?>">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="daily-view">View</button>
                                </div>
                            </div>
                            <div class="dt-responsive fees-table-wrap">
                                <table id="daily-table" class="table table-striped table-bordered nowrap">
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
                                                <?php $daily_total += (float) $row->sprfees_amount; ?>
                                                <tr>
                                                    <td><?= html_escape($row->name); ?></td>
                                                    <td class="text-right">
                                                        <?= number_format((float) $row->sprfees_amount); ?>
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control form-control-sm daily-fees-date"
                                                            value="<?= html_escape($row->sprfees_feesdate); ?>"
                                                            data-id="<?= html_escape($row->sprfees_pk); ?>"
                                                            data-student-id="<?= html_escape($row->sprfees_studentid); ?>"
                                                            data-current-date="<?= html_escape($row->sprfees_feesdate); ?>">
                                                    </td>
                                                    <td><?= html_escape($row->payment_mode); ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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

        if ($.fn.DataTable.isDataTable('#bank-table')) {
            $('#bank-table').DataTable().destroy();
        }

        $('#bank-table').DataTable({
            pageLength: 25,
            dom: 'Bfrtip',
            buttons: ['pdf', 'excel']
        });

        if ($.fn.DataTable.isDataTable('#daily-table')) {
            $('#daily-table').DataTable().destroy();
        }

        $('#daily-table').DataTable({
            pageLength: 25,
            dom: 'Bfrtip',
            buttons: ['pdf', 'excel']
        });


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

        function resetDateInputState(input) {
            input.removeClass('fees-date-success fees-date-error');
        }

        function markDateInputState(input, stateClass) {
            resetDateInputState(input);
            input.addClass(stateClass);

            window.setTimeout(function () {
                input.removeClass(stateClass);
            }, 1400);
        }

        function isValidDateValue(dateValue) {
            if (!/^\d{4}-\d{2}-\d{2}$/.test(dateValue)) {
                return false;
            }

            var parts = dateValue.split('-');
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10);
            var day = parseInt(parts[2], 10);
            var parsedDate = new Date(year, month - 1, day);

            return parsedDate.getFullYear() === year &&
                (parsedDate.getMonth() + 1) === month &&
                parsedDate.getDate() === day;
        }

        function loadBankTransactions(fromDate, toDate) {
            if (!validateDates(fromDate, toDate)) {
                return;
            }

            $.ajax({
                url: base_url + 'manage-fees/bank-transactions',
                type: 'POST',
                dataType: 'json',
                data: {
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function (response) {
                    if (response.status) {
                        $('#bank-transaction-body').html(response.html);
                        $('#bank-total').text(response.total);
                        resetFeesTableScroll('#bank-transaction-body');
                    } else {
                        showFeesMessage(response.message, 'danger');
                    }
                },
                error: function () {
                    showFeesMessage('Something went wrong while fetching bank transactions.', 'danger');
                }
            });
        }

        function loadDailyFees(fromDate, toDate) {
            if (!validateDates(fromDate, toDate)) {
                return;
            }

            $.ajax({
                url: base_url + 'manage-fees/daily-fees',
                type: 'POST',
                dataType: 'json',
                data: {
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function (response) {
                    if (response.status) {
                        $('#daily-fees-body').html(response.html);
                        $('#daily-total').text(response.total);
                        resetFeesTableScroll('#daily-fees-body');
                    } else {
                        showFeesMessage(response.message, 'danger');
                    }
                },
                error: function () {
                    showFeesMessage('Something went wrong while fetching daily fees.', 'danger');
                }
            });
        }

        $('#bank-view').on('click', function () {
            loadBankTransactions($('#bank_from_date').val(), $('#bank_to_date').val());
        });

        $('#daily-view').on('click', function () {
            loadDailyFees($('#daily_from_date').val(), $('#daily_to_date').val());
        });

        $('#daily-fees-body').on('change', '.daily-fees-date', function () {
            var input = $(this);
            var sprfeesPk = input.data('id');
            var studentId = input.data('student-id');
            var currentDate = String(input.data('current-date') || '');
            var updateDate = String(input.val() || '');
            var existingTimer = input.data('update-timer');

            if (existingTimer) {
                clearTimeout(existingTimer);
            }

            resetDateInputState(input);

            if (!updateDate || !isValidDateValue(updateDate)) {
                input.val(currentDate);
                markDateInputState(input, 'fees-date-error');
                return;
            }

            if (!sprfeesPk || !studentId) {
                input.val(currentDate);
                markDateInputState(input, 'fees-date-error');
                return;
            }

            if (updateDate === currentDate) {
                return;
            }

            var timerId = window.setTimeout(function () {
                input.prop('disabled', true);

                $.ajax({
                    url: base_url + 'manage-fees/update-fees-date/' + sprfeesPk,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        student_id: studentId,
                        update_date: updateDate,
                        current_date: currentDate
                    },
                    success: function (response) {
                        if (response.status) {
                            input.data('current-date', updateDate);
                            markDateInputState(input, 'fees-date-success');
                        } else {
                            input.val(currentDate);
                            markDateInputState(input, 'fees-date-error');
                        }
                    },
                    error: function () {
                        input.val(currentDate);
                        markDateInputState(input, 'fees-date-error');
                    },
                    complete: function () {
                        input.prop('disabled', false);
                        input.removeData('update-timer');
                    }
                });
            }, 350);

            input.data('update-timer', timerId);
        });
    </script>
</body>

</html>