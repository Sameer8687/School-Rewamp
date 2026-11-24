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
                            <h5 class="card-title">L.C Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="lcReportTable" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student ID</th>
                                                    <th>Name</th>
                                                    <th>Class</th>
                                                    <th>Status</th>
                                                    <th>L.C Status</th>
                                                    <th>L.C No</th>
                                                    <th>Jr.L.C No</th>
                                                    <th>Mobile</th>
                                                    <th>WhatsApp</th>
                                                    <th>Request Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 1;
                                                foreach ($records as $row) { ?>
                                                    <input type="hidden" name="year" id="year"
                                                        value="<?= $row['stvacdm_id'] ?>">
                                                    <tr>
                                                        <td><?= $i++ ?></td>
                                                        <td><?= $row['SPRIDEN_student_id'] ?></td>
                                                        <td><?= $row['name'] ?></td>
                                                        <td><?= $row['STVCLAS_standard'] ?></td>
                                                        <td><?= $row['spriden_student_status'] ?></td>
                                                        <td><?= $row['lc_status'] ?></td>
                                                        <td><?= $row['lc_no'] ?></td>
                                                        <td><?= $row['lc_no_jc'] ?></td>
                                                        <td><?= $row['stvpcnf_mobile'] ?></td>
                                                        <td><?= $row['stvpcnf_whatsapp'] ?></td>
                                                        <td><?= $row['lc_request_status'] ?></td>
                                                        <td>
                                                            <?php
                                                            $lc_type = '';
                                                            if (!empty($row['lc_no'])) {
                                                                $lc_type = 'school';
                                                            } elseif (!empty($row['lc_no_jc'])) {
                                                                $lc_type = 'college';
                                                            }
                                                            ?>

                                                            <?php if ($row['lc_status'] == 'Issued') { ?>
                                                                <button type="button" class="btn btn-sm btn-success"
                                                                    data-id="<?= $row['id'] ?>"
                                                                    data-acdm="<?= $row['stvacdm_id'] ?>"
                                                                    data-token="<?= $row['token'] ?? '' ?>"
                                                                    data-lc-type="<?= $lc_type ?>"
                                                                    onclick="viewLC(this)">View</button>
                                                            <?php } ?>
                                                        </td>
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
        </div>
    </div>

    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
        // $(document).ready(function () {
        //     // DataTable init with pagination & header filters
        //     var table = $('#lcReportTable').DataTable({
        //         pageLength: 100,
        //         lengthMenu: [5, 10, 25, 50, 100],
        //         order: [],
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'excel', 'pdf', 'print'
        //         ],
        //     });
        // });

        $("#lcReportTable").DataTable({
            "pageLength": 100,
            dom: "Bfrtip",
            buttons: [
                'excel', 'pdf', 'print'
            ],
        });

        function viewLC(button) {
            var id = button.getAttribute('data-id');
            var acdm = button.getAttribute('data-acdm');
            var token = button.getAttribute('data-token');
            var lc_type = button.getAttribute('data-lc-type');
            window.open(`<?= base_url() ?>Report/student_get_lc?lc-type=${lc_type}&student-id=${id}&year=${acdm}&token=${token}`, '_blank');
        }
    </script>
</body>

</html>