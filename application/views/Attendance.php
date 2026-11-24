<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Lists</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
    .buttons-excel {
        padding: 0px 5px 0px 5px !important;
        background-color: #53769c !important;
        color: #fff !important;
    }

    .buttons-pdf {
        padding: 0px 5px 0px 5px !important;
        background-color: #53769c !important;
        color: #fff !important;
    }

    .buttons-print {
        padding: 0px 5px 0px 5px !important;
        background-color: #53769c !important;
        color: #fff !important;
    }

    .paginate_button {
        padding: 0px 5px 0px 5px !important;
    }

    .dataTables_info {
        padding: 0px 5px 0px 5px !important;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        padding: 0px !important;
        padding-left: 5px !important;
        width: 75% !important;
    }

    .no-gutterss .col-md-2 {
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    .table-responsive::-webkit-scrollbar {
        height: 10px !important;
        color: #53769c !important;
    }

    .dataTables_wrapper .dt-buttons {
        float: right;
    }

    div.dataTables_wrapper div.dataTables_paginate {
        margin: 0;
        white-space: nowrap;
        text-align: right;
        padding: 0;
    }

    table.dataTable {
        clear: both;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        max-width: none !important;
        border-collapse: separate !important;
        border-spacing: 0;
        padding: 0px;
    }

    .pcoded-content {
        padding: 10px !important;
    }

    .pc-container {
        position: relative;
        top: 35px;
    }

    .form-group {
        margin-bottom: 0.5rem !important;
    }

    ::-webkit-scrollbar {
        width: 5px;
        height: 1px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #53769c;
        border-radius: 20px;
    }

    .card {
        margin-bottom: 0px !important;
    }

    .card-body {
        padding: 5px !important;
        padding: var(--bs-card-spacer-y) var(--bs-card-spacer-x);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: 5px;
        margin-left: 5px;
        --bs-gutter-x: 1rem !important;
    }

    .sm-mt-5 {
        margin-top: 0px;
    }

    @media screen and (max-width: 768px) {
        .sm-mt-5 {
            margin-top: 40px !important;
        }
    }
</style>

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
            <div class="row sm-mt-5">


                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="std_filter_" id="std_filter_">
                        <option value="">Select</option>
                        <?php foreach ($classes as $class): ?>
                            <option <?= $current_class == $class['stvfees_class'] ? 'selected' : ''  ?> value="<?= $class['stvfees_class'] ?>"><?= $class['stvfees_class'] ?></option>
                        <?php endforeach; ?>

                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control form-control-sm" name="div_filter_" id="div_filter_">
                        <option value="">Select</option>
                        <option <?= $current_div == 'A' ? 'selected' : '' ?> value="A">A</option>
                        <option <?= $current_div == 'B' ? 'selected' : '' ?> value="B">B</option>
                        <option <?= $current_div == 'C' ? 'selected' : '' ?> value="C">C</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm" name="cur_date" id="cur_date" value="<?= $current_date ?>">

                </div>

                <div class="col-md-3">
                    <button class="btn btn-sm btn-outline-secondary" type="button" onclick="filter_data()">Apply</button>
                </div>
            </div>

            <div class="row p-2">
                <div class="card">
                    <div class="card-body" style="overflow-x: auto;">
                        <style>
                            .table tr td,
                            .table tr th {
                                padding: 5px !important;
                                font-size: 11px !important;
                            }

                            .text-capitalize {
                                background-color: #53769c !important;
                                color: #fff !important;

                            }
                        </style>
                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-capitalize text-center">Sr.No.</th>
                                    <th class="text-capitalize text-center">Student ID</th>
                                    <th class="text-capitalize text-center">Name</th>
                                    <th class="text-capitalize text-center">class</th>
                                    <th class="text-capitalize text-center">Attendance</th>
                                    <th class="text-capitalize text-center">Whatsapp (Attend)</th>
                                    <th class="text-capitalize text-center">Whatsapp (Exam)</th>
                                </tr>
                            </thead>
                            <style>
                                #simpletable tbody tr td {
                                    padding: 5px;
                                    font-size: 12px;
                                    font-weight: 500;
                                }
                            </style>
                            <tbody>
                                <?php $i = 1; ?>

                                <?php foreach ($students as $student) :
                                    $status_name = '';
                                    $status_color = '';

                                    if ($student['SPRATND_status'] == "A") {
                                        $status_name = 'A';
                                        $status_color = 'danger';
                                    } elseif ($student['SPRATND_status'] == "P") {
                                        $status_name = 'P';
                                        $status_color = 'black';
                                    }

                                    // $massage = "https://api.whatsapp.com/send?phone=+91" . $student['stvpcnf_whatsapp'] . "&text= Dear " . $student['SPRIDEN_father_name'] . ' ' . $student['SPRIDEN_surname'] . ", Your child, " . $student['SPRIDEN_student_id'] . ", from class " . $student['STVCLAS_standard'] . '-' . $student['STVCLAS_class'] . ", was absent on " . $student['SPRATND_status'] . ". Please note this. Thank you!

                                    // प्रिय " . $student['SPRIDEN_father_name'] . ' ' . $student['SPRIDEN_surname'] . ", आपका बच्चा, " . $student['SPRIDEN_student_id'] . ", कक्षा " . $student['STVCLAS_standard'] . '-' . $student['STVCLAS_class'] . " से " . $student['SPRATND_status'] . " को अनुपस्थित रहा। कृपया ध्यान दें। धन्यवाद!";

                                    // print_r($message);
                                    $message = "Dear " . $student['SPRIDEN_father_name'] . ' ' . $student['SPRIDEN_surname'] . ",\n" .
                                        str_repeat(' ', 4) . "We would like to inform you that your child, " . $student['name'] . ", was absent from school today on " . date('d-M-Y', strtotime($current_date)) . ".\n" .
                                        str_repeat(' ', 4) . "We encourage you to ensure that your child catches up on any missed work to stay on track with their studies. Thank you for your attention to this matter.\n\n" .
                                        str_repeat(' ', 4) . "आपको इत्तिला दी जाती है के आपका बच्चा, " . $student['name'] . ", आज " . date('d-M-Y', strtotime($current_date)) . " को स्कूल से ग़ैर हाज़िर है।\n" .
                                        str_repeat(' ', 4) . "गुजारिश है कि आप ध्यान दें और आज के छूटे हुए सबक को पूरा कराएं।\n" .
                                        "आपकी तवज्जो का शुक्रिया।\n\n" .
                                        "Best regards,\nOlive English School\nDhule";

                                    $message2 = "Dear " . $student['SPRIDEN_father_name'] . ' ' . $student['SPRIDEN_surname'] . ",\n" .
                                        str_repeat(' ', 4) . "We would like to inform you that your child, " . $student['name'] . ", was absent from school today on " . date('d-M-Y', strtotime($current_date)) . ".\n" .
                                        str_repeat(' ', 4) . "We encourage you to ensure that your child dose not miss any more exam. Thank you for your attention to this matter.\n\n" .
                                        str_repeat(' ', 4) . "आपको इत्तिला दी जाती है के आपका बच्चा, " . $student['name'] . ", आज " . date('d-M-Y', strtotime($current_date)) . " को स्कूल से ग़ैर हाज़िर है।\n" .
                                        str_repeat(' ', 4) . "आपसे गुज़ारिश है, आपके बच्चे का इम्तेहान न छूटे ।\n" .
                                        "आपकी तवज्जो का शुक्रिया।\n\n" .
                                        "Best regards,\nOlive English School\nDhule";

                                    // Prepare the phone number and URL
                                    $phone = '+91' . $student['stvpcnf_whatsapp']; // Include country code
                                    $encoded_message = urlencode($message);
                                    $encoded_message2 = urlencode($message2);

                                    // Create the WhatsApp URL
                                    $whatsapp_url = "https://api.whatsapp.com/send?phone={$phone}&text={$encoded_message}";
                                    $whatsapp_url2 = "https://api.whatsapp.com/send?phone={$phone}&text={$encoded_message2}";
                                ?>

                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td class="text-left"><?= $student['SPRIDEN_student_id']; ?></td>
                                        <td class="text-left"><?= $student['name']; ?></td>
                                        <td><?= $student['STVCLAS_standard'] . '-' . $student['STVCLAS_class']; ?></td>
                                        <td class="text-<?= $status_color; ?>"><?= $status_name; ?></td> <!-- Added class for styling -->
                                        <td class="text-center">
                                            <a target="_blank" href="<?= $whatsapp_url ?>">
                                                <i class="bi bi-whatsapp text-success"></i> <!-- Fixed the closing tag -->
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a target="_blank" href="<?= $whatsapp_url2 ?>">
                                                <i class="bi bi-whatsapp text-primary"></i> <!-- Fixed the closing tag -->
                                            </a>
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    </div>
    </div>
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        function filter_data() {
            let std = document.querySelector('#std_filter_').value;
            let div = document.querySelector('#div_filter_').value;
            let cur_date = document.querySelector('#cur_date').value;

            if (std != '' && div != '' && cur_date != '') {
                window.location = `<?= base_url() ?>welcome/attendance_students?std=${std}&div=${div}&date=${cur_date}`;
            }
        }
    </script>
</body>

</html>