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
                            <h5 class="card-title">Fees Comments Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="tab" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student ID</th>
                                                    <th>Student Name</th>
                                                    <th>Class</th>
                                                    <th>Acdm Year</th>
                                                    <th>Total Fees</th>
                                                    <th>Paid</th>
                                                    <th>Exmp</th>
                                                    <th>Balance</th>
                                                    <th>Last Paid Date</th>
                                                    <th>Last Paid</th>
                                                    <th>Comments</th>
                                                    <th>Last Payment Year</th>
                                                    <th>Mobile No</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                foreach ($comments as $comment):
                                                    $mobile_num = $comment['father_whatsapp_number'];
                                                    $message = urlencode(
                                                        "Hi " . $comment['student_name'] .
                                                        "\n\nFees Comment : " . $comment['last_payment_notes']
                                                    );
                                                    ?>
                                                    <tr>
                                                        <td><?= $i++ ?></td>
                                                        <td><?= $comment['student_id'] ?></td>
                                                        <td><?= $comment['student_name'] ?></td>
                                                        <td><?= $comment['class_name'] ?> - <?= $comment['division'] ?>
                                                        </td>
                                                        <td><?= $comment['academic_year'] ?></td>
                                                        <td><?= $comment['total_fee'] ?></td>
                                                        <td><?= $comment['total_paid'] ?></td>
                                                        <td><?= $comment['total_exempted'] ?></td>
                                                        <td><?= $comment['balance_fee'] ?></td>
                                                        <td><?= $comment['last_paid_date'] ?></td>
                                                        <td><?= $comment['last_paid_amount'] ?></td>
                                                        <td><?= $comment['last_payment_academic_year'] ?></td>
                                                        <td><?= $comment['last_payment_notes'] ?></td>
                                                        <td><?= $comment['father_whatsapp_number'] ?></td>
                                                        <td>
                                                            <?php if (!empty($mobile_num)) { ?>
                                                                <a target="_blank"
                                                                    href="https://api.whatsapp.com/send?phone=91<?= $mobile_num ?>&text=<?= $message ?>">
                                                                    <i class="bi bi-whatsapp text-success fs-5"></i>
                                                                </a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
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
        $('#tab').DataTable({
            dom: 'Bfrtip',
            "pageLength": 20,
            "order": [
                [0, "asc"],
                [1, "asc"]
            ],
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    </script>
</body>

</html>