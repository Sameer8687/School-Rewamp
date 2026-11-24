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
                            <h5 class="card-title">Readmission Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">

                                        <!-- 1 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Academic Year <span
                                                        class="float-right">:</span></label>

                                                <select name="class" class="form-control form-control-sm col-md-7"
                                                    id="class">
                                                    <option value="0">Select Academic Year</option>
                                                    <?php foreach ($academic_years as $academic_year) {
                                                        ?>
                                                        <option <?= $acdm == $academic_year['stvacdm_id'] ? 'selected' : ''; ?>
                                                            value="<?= $academic_year['stvacdm_id']; ?>">
                                                            <?= $academic_year['stvacdm_year']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Type <span
                                                        class="float-right">:</span></label>

                                                <select name="type" class="form-control form-control-sm col-md-7" id="type">
                                                    <option value="0">Select Type</option>
                                                    <option <?= $type == 'readmission' ? 'selected' : '' ?>
                                                        value="readmission">
                                                        Readmission</option>
                                                    <option <?= $type == 'noadmission' ? 'selected' : '' ?>
                                                        value="noadmission">
                                                        No Admission</option>
                                                </select>

                                            </div>
                                        </div>

                                        <!-- 3 -->
                                        <div class="col-md-4">

                                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                                onclick="view_data()">View</button>

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
                                                    <th class="text-capitalize">ID</th>
                                                    <th class="text-capitalize">Student ID</th>
                                                    <th class="text-capitalize">Name</th>
                                                    <th class="text-capitalize">Class</th>
                                                    <?php if ($type == 'readmission'): ?>
                                                        <th class="text-capitalize">Fees Paid</th>
                                                        <th class="text-capitalize">Admission Date</th>
                                                        <?php
                                                    else: ?>
                                                        <th class="text-capitalize">Contact Number</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 1;
                                                foreach ($all_records as $record) { ?>
                                                    <tr>
                                                        <td><?= $i; ?></td>
                                                        <td><?= $record['id'] ?></td>
                                                        <td><?= $record['SPRIDEN_student_id'] ?></td>
                                                        <td><?= $record['name'] ?></td>
                                                        <td><?= $record['stvradm_class'] ?></td>
                                                        <?php if ($type == 'readmission'): ?>
                                                            <td><?= $record['stvradm_fess'] ?></td>
                                                            <td><?= $record['stvradm_date'] == '' || $record['stvradm_date'] == null ? '' : date('d-M-Y', strtotime($record['stvradm_date'])) ?>
                                                            </td>
                                                            <?php
                                                        else: ?>
                                                            <td><?= $record['phone'] ?></td>
                                                        <?php endif; ?>
                                                    </tr>
                                                    <?php $i++;
                                                } ?>
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
        function view_data() {
            let vls = $('#class').val();
            let type = $('#type').val();
            window.location = `<?= base_url() ?>report/readmission?academic-year=${vls}&type=${type}`;
        };
    </script>

</body>

</html>