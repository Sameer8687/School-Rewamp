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

    <?php include 'application/views/sidebar.php'; ?>
    <?php include 'application/views/topNav.php'; ?>
    <div class="pc-container">
        <div class="pcoded-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Student Attendance</h5>
                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-6">Academic Year
                                                    <span class="float-right">:</span>
                                                </label>
                                                <select id="acdm_year" class="form-control form-control-sm col-md-6">
                                                    <option value="">Select</option>
                                                    <?php foreach ($academic_years as $acdm) { ?>
                                                        <option <?php echo $selected_academic_year == $acdm['stvacdm_id'] ? 'selected' : ''; ?>
                                                            value="<?= $acdm['stvacdm_id'] ?>">
                                                            <?= $acdm['stvacdm_year'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Std
                                                    <span class="float-right">:</span></label>

                                                <select id="class" class="form-control form-control-sm col-md-7">
                                                    <option value="0">Select</option>
                                                    <?php foreach ($classes as $c) { ?>
                                                        <option value="<?= $c['stvfees_class'] ?>"
                                                            <?= $selected_class == $c['stvfees_class'] ? 'selected' : '' ?>>
                                                            <?= $c['stvfees_class'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Div
                                                    <span class="float-right">:</span></label>

                                                <select id="division" class="form-control form-control-sm col-md-7">
                                                    <option value="0">Division</option>
                                                    <option value="A" <?= ($selected_division == 'A') ? 'selected' : '' ?>>
                                                        A</option>
                                                    <option value="B" <?= ($selected_division == 'B') ? 'selected' : '' ?>>
                                                        B</option>
                                                    <option value="C" <?= ($selected_division == 'C') ? 'selected' : '' ?>>
                                                        C</option>
                                                </select>
                                            </div>
                                        </div>

                                        

                                        <div class="col-md-3">
                                            <button id="view" class="btn btn-sm btn-outline-secondary">View</button>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" class="table table-striped table-bordered nowrap">

                                            <thead>
                                                <tr>
                                                    <th rowspan="2">#</th>
                                                    <th rowspan="2">Name</th>

                                                    <th colspan="2">Jun</th>
                                                    <th colspan="2">Jul</th>
                                                    <th colspan="2">Aug</th>
                                                    <th colspan="2">Sep</th>
                                                    <th colspan="2">Oct</th>
                                                    <th colspan="2">Nov</th>
                                                    <th colspan="2">Dec</th>
                                                    <th colspan="2">Jan</th>
                                                    <th colspan="2">Feb</th>
                                                    <th colspan="2">Mar</th>
                                                    <th colspan="2">Apr</th>
                                                    <th colspan="2">May</th>

                                                    <th rowspan="2">Total Present</th>
                                                    <th rowspan="2">Total Working</th>
                                                </tr>

                                                <tr>
                                                    <?php for ($i = 0; $i < 12; $i++) { ?>
                                                    <th>Pr</th>
                                                        <th>Wo</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php if (!empty($attendance_data)) {
                                                    $i = 1;
                                                    foreach ($attendance_data as $row) { ?>
                                                        <tr>
                                                            <td class="text-center"><?= $i++ ?></td>
                                                            <td><?= $row['name'] ?></td>

                                                            <td class="text-center"><?= $row['jun_present'] ?></td>
                                                            <td class="text-center"><?= $row['jun_working'] ?></td>

                                                            <td class="text-center"><?= $row['jul_present'] ?></td>
                                                            <td class="text-center"><?= $row['jul_working'] ?></td>

                                                            <td class="text-center"><?= $row['aug_present'] ?></td>
                                                            <td class="text-center"><?= $row['aug_working'] ?></td>

                                                            <td class="text-center"><?= $row['sep_present'] ?></td>
                                                            <td class="text-center"><?= $row['sep_working'] ?></td>

                                                            <td class="text-center"><?= $row['oct_present'] ?></td>
                                                            <td class="text-center"><?= $row['oct_working'] ?></td>

                                                            <td class="text-center"><?= $row['nov_present'] ?></td>
                                                            <td class="text-center"><?= $row['nov_working'] ?></td>

                                                            <td class="text-center"><?= $row['dec_present'] ?></td>
                                                            <td class="text-center"><?= $row['dec_working'] ?></td>

                                                            <td class="text-center"><?= $row['jan_present'] ?></td>
                                                            <td class="text-center"><?= $row['jan_working'] ?></td>

                                                            <td class="text-center"><?= $row['feb_present'] ?></td>
                                                            <td class="text-center"><?= $row['feb_working'] ?></td>

                                                            <td class="text-center"><?= $row['mar_present'] ?></td>
                                                            <td class="text-center"><?= $row['mar_working'] ?></td>

                                                            <td class="text-center"><?= $row['apr_present'] ?></td>
                                                            <td class="text-center"><?= $row['apr_working'] ?></td>

                                                            <td class="text-center"><?= $row['may_present'] ?></td>
                                                            <td class="text-center"><?= $row['may_working'] ?></td>

                                                            <td class="text-center"><?= $row['total_present'] ?></td>
                                                            <td class="text-center"><?= $row['total_working'] ?></td>
                                                        </tr>
                                                    <?php }
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
        var url = '<?= base_url() ?>report/Student_atnd/';
        document.getElementById("view").addEventListener("click", function () {
            let acdm_year = document.getElementById("acdm_year").value;
            let cls = document.getElementById("class").value;
            let div = document.getElementById("division").value;
            window.location = `${url}${acdm_year}/${cls}/${div}`;
        });
    </script>
</body>

</html>