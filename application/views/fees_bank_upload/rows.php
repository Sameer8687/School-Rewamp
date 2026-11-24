<!DOCTYPE html>
<html lang="en">

<head>
    <title>Uploaded Bank Transactions</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <?php include 'application/views/cssPages.php'; ?>

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .group-id-box {
            font-size: 12px;
            color: #6b7280;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            border-radius: 8px;
            word-break: break-all;
        }

        .bank-table tr td,
        .bank-table tr th {
            padding: 6px !important;
            font-size: 11px !important;
            vertical-align: middle !important;
        }

        .amount-text {
            font-weight: 700;
            color: #111827;
        }

        .empty-row {
            text-align: center;
            padding: 20px !important;
            color: #6b7280;
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

            <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">
                <div class="col-md-7">
                    <a href="<?= site_url('fees-bank-upload'); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-arrow-alt-circle-left"></i> Back to Upload
                    </a>

                    <button onclick="window.print();" class="btn btn-secondary btn-sm" id="print-button" type="button">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Uploaded Bank Transactions
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="font-weight-bold mb-1">Group ID</label>
                                    <div class="group-id-box">
                                        <?= html_escape($group_id); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="dt-responsive table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered nowrap bank-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Transaction ID</th>
                                            <th class="text-center">Value Date</th>
                                            <th class="text-center">Txn Posted Date</th>
                                            <th>Cheque No</th>
                                            <th>Description</th>
                                            <th class="text-center">Mode</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-center">Uploaded At</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (!empty($transactions)): ?>
                                            <?php $i = 1; foreach ($transactions as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?= $i++; ?></td>
                                                    <td><?= html_escape($row['transaction_id']); ?></td>
                                                    <td class="text-center"><?= html_escape($row['value_date']); ?></td>
                                                    <td class="text-center"><?= html_escape($row['txn_posted_date']); ?></td>
                                                    <td><?= html_escape($row['cheque_no']); ?></td>
                                                    <td><?= html_escape($row['description']); ?></td>
                                                    <td class="text-center"><?= html_escape($row['mode']); ?></td>
                                                    <td class="text-right amount-text">
                                                        <?= number_format((float)$row['transaction_amount'], 2); ?>
                                                    </td>
                                                    <td class="text-center"><?= html_escape($row['uploaded_at']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="empty-row">
                                                    No transactions found.
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

    <?php include 'application/views/jsPages.php'; ?>

</body>

</html>