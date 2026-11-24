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
                            <h5 class="card-title">RTE Student Report</h5>
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

                                                <select class="form-control form-control-sm col-md-7 find-students"
                                                    name="admission_class" id="class" required>
                                                    <option value="0">Select Class</option>
                                                    <option <?= $std_selected == 'All' ? 'selected' : ''; ?> value="All">
                                                        All</option>
                                                    <?php foreach ($classes as $class) { ?>
                                                        <option <?= $std_selected == $class['stvfees_class'] ? 'selected' : ''; ?> value="<?php echo $class['stvfees_class']; ?>">
                                                            <?php echo $class['stvfees_class']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="total_students" id="total_students">

                                            </div>
                                        </div>
                                        <!-- 2 -->
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Division <span
                                                        class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7 find-students"
                                                    name="division" id="division" required>
                                                    <option value="0">Select Division</option>
                                                    <option <?= $div_selected == 'A' ? 'selected' : ''; ?> value="A">A
                                                    </option>
                                                    <option <?= $div_selected == 'B' ? 'selected' : ''; ?> value="B">B
                                                    </option>
                                                    <option <?= $div_selected == 'C' ? 'selected' : ''; ?> value="C">C
                                                    </option>
                                                </select>

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
                                                    <th class="text-capitalize">ID</th>
                                                    <th class="text-capitalize">Student ID</th>
                                                    <th class="text-capitalize">Name</th>
                                                    <th class="text-capitalize">Class</th>
                                                    <th class="text-capitalize">Status</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 1;
                                                foreach ($list as $record) { ?>
                                                    <tr>
                                                        <td><?= $i; ?></td>
                                                        <td><?= $record['id']; ?></td>
                                                        <td><?= $record['SPRIDEN_student_id']; ?></td>
                                                        <td><?= $record['name']; ?></td>
                                                        <td><?= $record['STVCLAS_standard'] . '-' . $record['STVCLAS_class']; ?>
                                                        </td>
                                                        <td><?= $record['SPRIDEN_student_status']; ?></td>
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
        document.getElementById("find-students").addEventListener('click', async () => {
            var html = '';
            let std = document.getElementById("class").value;
            let div = document.getElementById("division").value;
            let base_url = '<?= base_url(); ?>';
            window.location = base_url + 'report/rte_report/' + std + '/' + div;
        });

        $(document).ready(function () {
            $('#example').DataTable({
                pageLength: '25',
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
</body>

</html>