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
                            <h5 class="card-title">Admission Summary Report</h5>
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
                                                    class="form-control form-control-sm col-md-7"
                                                    value="<?= $from_date ?>" />
                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">To Date <span
                                                        class="float-right">:</span></label>

                                                <input type="date" name="to_date" id="to_date"
                                                    class="form-control form-control-sm col-md-7"
                                                    value="<?= $to_date ?>" />
                                            </div>
                                        </div>

                                        <!-- 3 -->
                                        <div class="col-md-4">
                                            <button id="find-students" type="button"
                                                class="btn btn-sm btn-outline-secondary">View Class</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="text-capitalize">#</th>
                                                    <th class="text-capitalize">Class</th>
                                                    <th class="text-capitalize">Fees Paid</th>
                                                    <th class="text-capitalize">No. Of Student</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 1;
                                                $sum = 0;
                                                $stud = 0;
                                                foreach ($all_records as $record) {
                                                    $sum += (float) $record['sprfees_amount'];
                                                    $stud += (float) $record['sprfees_studentid'];
                                                    ?>

                                                    <tr class="getRowData"
                                                        data-header-id="<?= $record['sprfees_standard'] ?>">
                                                        <td><?= $i; ?></td>
                                                        <td><?= $record['sprfees_standard'] ?></td>
                                                        <td><?= number_format($record['sprfees_amount']) ?></td>
                                                        <td><?= $record['sprfees_studentid'] ?></td>
                                                    </tr>
                                                    <?php $i++;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td>Total Amount</td>
                                                    <td><?= number_format($sum); ?></td>
                                                    <td><?= number_format($stud); ?></td>
                                                </tr>
                                            </tfoot>
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
        document.getElementById("find-students").addEventListener('click', async () => {
            var html = '';
            let from = document.getElementById("from_date").value != '' ? document.getElementById("from_date")
                .value : 0;
            let to = document.getElementById("to_date").value != '' ? document.getElementById("to_date").value :
                0;
            let base_url = '<?= base_url(); ?>';
            window.location = base_url + 'report/admission_summary/' + from + '/' + to;
        });

        $(document).on("click", ".getRowData", function () {
            let header_id = $(this).attr("data-header-id");
            let url = "<?= base_url(); ?>index.php/report/admissionreport/" + header_id;
            window.location = url;
        });
    </script>

</body>

</html>