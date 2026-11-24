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
                            <h5 class="card-title">New Admission Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">

                                        <!-- 1 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class <span
                                                        class="float-right">:</span></label>

                                                <select data-type="class" name="class"
                                                    class="form-control form-control-sm col-md-7 view-record"
                                                    id="class">
                                                    <option value="0">Select Class</option>
                                                    <?php foreach ($classes as $clas) { ?>
                                                        <option <?= $class == $clas['stvfees_class'] ? 'selected' : ''; ?>
                                                            value="<?= $clas['stvfees_class'] ?>">
                                                            <?= $clas['stvfees_class'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Section <span
                                                        class="float-right">:</span></label>

                                                <select data-type="section" name="class_section"
                                                    class="form-control form-control-sm col-md-7 view-record"
                                                    id="class_section">
                                                    <option value="0">Select Section</option>
                                                    <option <?= $class == 1 ? 'selected' : ''; ?> value="1">Pre-Primary
                                                    </option>
                                                    <option <?= $class == 2 ? 'selected' : ''; ?> value="2">Primary
                                                    </option>
                                                    <option <?= $class == 3 ? 'selected' : ''; ?> value="3">Secondary
                                                    </option>
                                                    <option <?= $class == 4 ? 'selected' : ''; ?> value="4">College
                                                    </option>
                                                </select>

                                            </div>
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
                                                    <th class="text-capitalize">Fees Paid</th>
                                                    <th class="text-capitalize">Admission Date</th>
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
                                                        <td><?= $record['STVCLAS_standard'] ?></td>
                                                        <td><?= $record['fees_paid'] ?></td>
                                                        <td><?= $record['SPRIDEN_date_created'] == '' || $record['SPRIDEN_date_created'] == null ? '' : date('d-M-Y', strtotime($record['SPRIDEN_date_created'])) ?>
                                                        </td>
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
        $(document).on("change", '.view-record', function (e) {
            var type = $(this).attr("data-type");
            var vls = document.getElementById($(this).attr('id')).value;
            if (vls != 0) {
                if (type == 'class') {
                    window.location = '<?= base_url() ?>' + 'report/admission_report?type=class&class=' + vls;
                } else {
                    window.location = '<?= base_url() ?>' + 'report/admission_report?type=section&class=' + vls;
                }
            }

        });
    </script>
</body>

</html>