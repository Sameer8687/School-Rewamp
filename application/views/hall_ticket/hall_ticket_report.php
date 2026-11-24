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
                            <h5 class="card-title">Hall Ticket Generate</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <!-- 1 -->
                                        <div class="col-md-1">
                                            <div class="row px-1">
                                                <!-- <label for="" class="col-md-5">Class <span
                                                        class="float-right">:</span></label> -->

                                                <select class="form-control form-control-sm col-md-12" name="class"
                                                    id="class">
                                                    <option value="">
                                                        Class
                                                    </option>
                                                    <?php foreach ($classes as $class): ?>
                                                        <option <?= $std == $class['stvfees_class'] ? 'selected' : '' ?>
                                                            value="<?= $class['stvfees_class'] ?>">
                                                            <?= $class['stvfees_class'] ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="col-md-1">
                                            <div class="row px-1">
                                                <!-- <label for="" class="col-md-5">Div <span
                                                        class="float-right">:</span></label> -->

                                                <select class="form-control form-control-sm col-md-12" name="div"
                                                    id="div">
                                                    <option value="">
                                                        Division
                                                    </option>
                                                    <option <?= $div == 'A' ? 'selected' : '' ?> value="A">A</option>
                                                    <option <?= $div == 'B' ? 'selected' : '' ?> value="B">B</option>
                                                    <option <?= $div == 'C' ? 'selected' : '' ?> value="C">C</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- 2 -->
                                        <div class="col-md-1">
                                            <div class="row px-1">
                                                    <!-- <label for="" class="col-md-5">Exam <span
                                                            class="float-right">:</span></label> -->

                                                <select class="form-control form-control-sm col-md-12" name="exam"
                                                    id="exam">
                                                    <option value="">Exam</option>
                                                    <?php foreach ($exams as $exam): ?>
                                                        <option <?= $test == $exam['stvexam_id'] ? 'selected' : '' ?>
                                                            value="<?= $exam['stvexam_id'] ?>"><?= $exam['stvexam_name'] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- 2 -->
                                        <div class="col-md-1">
                                            <div class="row px-1">
                                                    <!-- <label for="" class="col-md-5">Amount <span
                                                            class="float-right">:</span></label> -->

                                                <input type="text" class="form-control form-control-sm col-md-12"
                                                    name="paid_fees" id="paid_fees" placeholder="Fees Amount Paid"
                                                    autocomplete="off" value="<?= $fees ?>">
                                            </div>
                                        </div>
                                        <!-- 2 -->
                                        <div class="col-md-1">
                                            <div class="row px-1">
                                                    <!-- <label for="" class="col-md-5">RTE <span
                                                            class="float-right">:</span></label> -->

                                                <select class="form-control form-control-sm col-md-12" name="rte_status"
                                                    id="rte_status">
                                                    <option value="">
                                                        Select RTE
                                                    </option>
                                                    <option <?= $rte_status == 'yes' ? 'selected' : '' ?> value="yes">Yes
                                                    </option>
                                                    <option <?= $rte_status == 'no' ? 'selected' : '' ?> value="no">No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- 2 -->
                                        <div class="col-md-1">
                                            <div class="row px-1">
                                                <!-- <label for="" class="col-md-5">Academic Year <span
                                                        class="float-right">:</span></label> -->

                                                <input type="date" class="form-control form-control-sm col-md-12"
                                                    name="date" id="date" autocomplete="off" value="<?= $crn_date ?>">
                                            </div>
                                        </div>

                                        <!-- 3 -->
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                                onclick="view_data()">View</button>
                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                onclick="generate()">Generate</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" data-custom="true"
                                            class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>ID</th>
                                                    <th>Student ID</th>
                                                    <th>Name</th>
                                                    <th>Paid Fees</th>
                                                    <th>Last Date</th>
                                                    <th>Last Paid</th>
                                                    <th>
                                                        Action
                                                        <input type="checkbox" onclick="toggleAllCheckBoxes(this)"
                                                            id="all-select">
                                                    </th>
                                                    <th>Generate</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody">
                                                <?php $i = 1;
                                                foreach ($all_records as $record):
                                                    $paid_date = $record['last_date'] ?? null;
                                                    if ($paid_date != null && !in_array($paid_date, ['', ' ', '0000-00-00']))
                                                        $paid_date = date('d M Y', strtotime($paid_date));
                                                    ?>
                                                    <tr>
                                                        <td><?= $i; ?></td>
                                                        <td><?= $record['id'] ?></td>
                                                        <td><?= $record['SPRIDEN_student_id'] ?> </td>
                                                        <td><?= $record['student_name'] ?>
                                                            (<?= $record['STVCLAS_standard'] ?> -
                                                            <?= $record['STVCLAS_class'] ?>)</td>
                                                        <td><?= $record['total_paid'] ?></td>
                                                        <td><?= $paid_date ?></td>
                                                        <td><?= $record['last_paid'] ?? null ?></td>
                                                        <td class="text-center">
                                                            <input type="checkbox" class="record-checkbox"
                                                                id="checkbox_<?= $record['id'] ?>"
                                                                value="<?= $record['id'] ?>" onclick="isAllChecked(this)">
                                                        </td>
                                                        <td>
                                                            <a class="text-primary" href="javascript:void(0)"
                                                                onclick="generate('<?= $record['id'] ?>', 'link')">Print</a>
                                                        </td>
                                                    </tr>
                                                    <?php $i++;
                                                endforeach;
                                                ;
                                                ?>
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
            let std = $('#class').val();
            let div = $('#div').val();
            let exam = $('#exam').val();
            let rte = $('#rte_status').val();
            let paid_fees = $('#paid_fees').val();
            let date = $('#date').val();
            window.location = `<?= base_url() ?>Hall_ticket/report?action=search&std=${std}&div=${div}&exam=${exam}&paid_fees=${paid_fees}&rte=${rte}&date=${date}`;
        };
    </script>

    <script>
        function generate(student_id = null, mode = 'button') {
            let std = $('#class').val();
            let div = $('#div').val();
            let exam = $('#exam').val();
            let rte = $('#rte_status').val();
            let paid_fees = $('#paid_fees').val();
            let search_mode = 'classwise';
            if (exam == '') {
                alert("Select Exam Name")
                return;
            }

            // check if any check box is selected
            var checkboxes = document.querySelectorAll('.record-checkbox:checked');
            // Array to store selected values
            var selectedStudents = [];
            // Loop through the checkboxes and get their values
            if (mode == 'button') {
                checkboxes.forEach(function (checkbox) {
                    selectedStudents.push(checkbox.value);
                });
            }
            if (mode == 'link') {
                if (student_id != null)
                    selectedStudents.push(student_id);
            }

            let student_list = '';
            if (selectedStudents.length > 0) {
                search_mode = 'studentwise';
                student_list = selectedStudents.join(",");
            }

            let url = `<?= base_url() ?>Hall_ticket/generate?action=search&std=${std}&div=${div}&exam=${exam}&paid_fees=${paid_fees}&rte=${rte}`;
            if (student_list != null)
                url += `&search-mode=${search_mode}&student-id=${btoa(student_list)}`;
            window.open(url);
        }

        function toggleAllCheckBoxes(element) {
            let isChecked = element.checked;
            let checkboxes = document.querySelectorAll(".record-checkbox");
            checkboxes.forEach((checkbox) => {
                checkbox.checked = isChecked;
            })
        }

        function isAllChecked() {
            let all_select = document.getElementById("all-select");
            let all_checked = true;
            let checkboxes = document.querySelectorAll(".record-checkbox");

            checkboxes.forEach((checkbox) => {
                if (!checkbox.checked) {
                    all_checked = false;
                }
            });

            if (all_checked) {
                all_select.checked = true;
            } else {
                all_select.checked = false;
            }
        }
    </script>
</body>

</html>