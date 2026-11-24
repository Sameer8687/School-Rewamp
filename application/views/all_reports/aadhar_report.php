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
                            <h5 class="card-title">Aadhar Report</h5>

                        </div>
                        <div class="card-body">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">

                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class
                                                    <span class="float-right">:</span></label>

                                                <select class="form-control form-control-sm col-md-7" name="class"
                                                    id="class" required>
                                                    <option value="">Select STD</option>
                                                    <?php
                                                    foreach ($classes as $cls) {
                                                        $selected = (isset($selected_class) && $selected_class == $cls['stvfees_class']) ? 'selected' : '';
                                                        ?>
                                                        <option value="<?= $cls['stvfees_class']; ?>" <?= $selected ?>>
                                                            <?= $cls['stvfees_class']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Div
                                                    <span class="float-right">:</span></label>

                                                <select required class="form-control form-control-sm col-md-7"
                                                    name="division" id="division">
                                                    <option value="">Select Division</option>
                                                    <option value="A" <?php echo (isset($selected_div) && $selected_div == 'A') ? 'selected' : ''; ?>>A</option>
                                                    <option value="B" <?php echo (isset($selected_div) && $selected_div == 'B') ? 'selected' : ''; ?>>B</option>
                                                    <option value="C" <?php echo (isset($selected_div) && $selected_div == 'C') ? 'selected' : ''; ?>>C</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-4">

                                            <button id="find-students" type="button"
                                                class="btn btn-sm btn-outline-secondary">View Report</button>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simletable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>#</th>
                                                    <th class="text-center">ID</th>
                                                    <th class="text-center">Student Name</th>
                                                    <th class="text-center">Aadhar No</th>
                                                    <th class="text-center">Aadhar Name</th>
                                                    <th class="text-center">Date of Birth</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody id="slbs_report">
                                                <?php
                                                $i = 1;
                                                    foreach ($details as $detail) {
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?= $i++; ?></td>
                                                            <td class="text-center"><?= $detail['SPRIDEN_student_id']; ?></td>
                                                            <td class="text-center"><?= $detail['student_name']; ?></td>

                                                            <td class="text-center">
                                                                <?= !empty($detail['aadhar_number']) ? $detail['aadhar_number'] : '-'; ?>
                                                            </td>

                                                            <td class="text-center">
                                                                <?= !empty($detail['name_on_aadhar']) ? $detail['name_on_aadhar'] : '-'; ?>
                                                            </td>

                                                            <td class="text-center">
                                                                <?= !empty($detail['date_of_birth']) ? $detail['date_of_birth'] : '-'; ?>
                                                            </td>

                                                            <td class="text-center">
                                                                <?php if (!empty($detail['aadhar_front_img'])) { ?>
                                                                    <a href="<?= base_url('application/upload/' . $detail['aadhar_front_img']) ?>"
                                                                        target="_blank">View</a>
                                                                    |
                                                                    <a href="<?= base_url('application/upload/' . $detail['aadhar_front_img']) ?>"
                                                                        download style="color:red;">Download</a>
                                                                <?php } else { ?>
                                                                    No File
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
        $(document).ready(function () {
            $('#simletable').DataTable({
                pageLength: 25,
                destroy: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });

        document.getElementById("find-students").addEventListener('click', function () {
            let std = document.getElementById("class").value;
            let div = document.getElementById("division").value;
            let base_url = '<?= base_url(); ?>';

            if (std && div) {
                window.location = base_url + "index.php/Report/Aadhar_report/" + std + "/" + div;
            } else {
                alert("Please select both Standard and Division.");
            }
        });
    </script>

</html>