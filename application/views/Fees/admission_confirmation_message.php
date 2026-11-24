<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                            <div class="col-md-2">
                                <h5 class="card-title">Admission Alert</h5>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Year <span
                                                        class="float-right">:</span></label>
                                                <select class="form-control form-control-sm col-md-7" id="year"
                                                    name="year">
                                                    <option value="">Select</option>
                                                    <?php foreach ($academic_year as $acdm) { ?>
                                                        <option <?= $year_filter == $acdm['stvacdm_id'] ? 'selected' : ''; ?>
                                                            value="<?= $acdm['stvacdm_id'] ?>"><?= $acdm['stvacdm_year'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Std <span
                                                        class="float-right">:</span></label>
                                                <select class="form-control form-control-sm col-md-7" name="std"
                                                    id="std">
                                                    <option value="">Select</option>
                                                    <?php foreach ($classes as $clas) { ?>
                                                        <option <?= $std_filter == $clas['stvfees_class'] ? 'selected' : ''; ?>
                                                            value="<?= $clas['stvfees_class'] ?>">
                                                            <?= $clas['stvfees_class'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <label for="" class="col-md-5">Div <span
                                                        class="float-right">:</span></label>
                                                <select class="form-control form-control-sm col-md-7" id="div"
                                                    name="div">
                                                    <option value="">Select</option>
                                                    <option <?= ($div_filter == 'A') ? 'selected' : '' ?> value="A">A
                                                    </option>
                                                    <option <?= ($div_filter == 'B') ? 'selected' : '' ?> value="B">B
                                                    </option>
                                                    <option <?= ($div_filter == 'C') ? 'selected' : '' ?> value="C">C
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-sm btn-secondary" type="button"
                                                id="view_button">View</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="dt-responsive table-responsive margin-top-minus-10">
                                            <table id="simpletable" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr style="text-align: center;">
                                                        <td style="font-weight: bold; width:10%">R.No</td>
                                                        <td style="font-weight: bold; text-align: left;width:auto;">Name
                                                        </td>
                                                        <td style="font-weight: bold; text-align: left;width:auto;">Fees
                                                            Paid</td>
                                                        <td style="font-weight: bold; text-align: left;">Action</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $total_students = count($students);
                                                    for ($i = 0; $i < $total_students; $i++) {
                                                        //Show only students whose paid fees is 0
                                                        if ($students[$i]['total_fees_paid_by_st'] != 0) {
                                                            continue;
                                                        }
                                                        $next = $i + 1;

                                                        $student_id = $students[$i]['SPRIDEN_student_id'];

                                                        $name = trim(
                                                            $students[$i]['SPRIDEN_surname'] . " " .
                                                            $students[$i]['SPRIDEN_student_name'] . " " .
                                                            $students[$i]['SPRIDEN_father_name']
                                                        );
                                                        $Total_fees = $students[$i]['total_fees_to_be_paid'];
                                                        $Paid_Fees = $students[$i]['total_fees_paid_by_st'];
                                                        $Balance_Fees = $students[$i]['remaining_fees_to_be_paid'];

                                                        $student_fees = parseBalanceInstallment($students[$i]['balance_installment']);
                                                        $message = "Assalamualaikum \n";
                                                        $message .= "Dear Parent,\n";
                                                        $message .= "Your child {$name} admission for this year is pending.\n";

                                                        $message .= "Kindly visit the school office, pay the fees, \n";

                                                        $message .= "Pending Fees Details\n";
                                                        $message .= "=============================\n\n";

                                                        $totalBalance = 0;

                                                        foreach ($student_fees as $row) {

                                                            // Skip fully paid years
                                                            if ($row['balance'] <= 0) {
                                                                continue;
                                                            }

                                                            $totalBalance += $row['balance'];

                                                            $message .= "Academic Year : {$row['academic_year_name']}\n";
                                                            $message .= "Total Fees    : ₹" . number_format($row['total_fees']) . "\n";
                                                            $message .= "Paid Amount   : ₹" . number_format($row['total_paid']) . "\n";
                                                            $message .= "Balance Fees  : ₹" . number_format($row['balance']) . "\n";
                                                            $message .= "-----------------------------\n";
                                                        }

                                                        $message .= "\n";

                                                        $message .= "Total Outstanding : ₹" . number_format($totalBalance) . "\n\n";

                                                        $message .= "and complete readmission at the earliest.\n\n";

                                                        $message .= "--------------------------------------------------\n\n";

                                                        $message .= "आपके बच्चे {$name} का इस साल के लिए एडमिशन अभी तक मुकम्मल नहीं हुआ है। फीस जमा न होने और दाखिले की कारवाई अधूरी होने की वजह से एडमिशन पेंडिंग है। मेहरबानी करके फौरन स्कूल ऑफिस तशरीफ लाएं, फीस जमा करें और री-एडमिशन की कारवाई मुकम्मल करके अपने बच्चे का दाखिला कन्फर्म करें। \n\n";

                                                        $message .= " Class: I to IV , Time: 12:30 to 2:00 For All\n\n";
                                                        $message .= " Class: V to XII , Time: 12:30 to 1:30 For Girls\n\n";
                                                        $message .= " Class: V to XII , Time: 1:15 to 2:00 For Boys\n\n";

                                                        $message .= "आपके तआवुन का शुक्रिया।\n\n";

                                                        $message .= "Regards,\n";
                                                        $message .= "Olive English School\n";
                                                        $message .= "Dhule";

                                                        $encodedMessage = urlencode($message);

                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?= $students[$i]['STVCLAS_rollno']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $students[$i]['SPRIDEN_surname'] . " " .
                                                                    $students[$i]['SPRIDEN_student_name'] . " " .
                                                                    $students[$i]['SPRIDEN_father_name']; ?>
                                                            </td>
                                                            <td>
                                                                <span class="text-danger">
                                                                    <?= $Paid_Fees; ?>
                                                                </span>/
                                                                <span class="text-primary">
                                                                    <?= $Total_fees; ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty($students[$i]['stvpcnf_whatsapp'])) { ?>
                                                                    <a target="_blank"
                                                                        href="https://api.whatsapp.com/send?phone=91<?= $students[$i]['stvpcnf_whatsapp']; ?>&text=<?= urlencode($message); ?>">
                                                                        <i class="bi bi-whatsapp"></i>
                                                                    </a>
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
    </div>
    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
    <script>
        $("#table-gr").DataTable({
            "pageLength": 25,
            dom: "Bfrtip",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        var view_button = document.getElementById("view_button");
        let base_url = "<?= base_url(); ?>";
        view_button.addEventListener("click", function () {
            var year = document.getElementById("year").value;
            var std = document.getElementById("std").value;
            var div = document.getElementById("div").value;
            if (year == '' || std == '' || div == '') {
                alert("Please select Year, Std and Div");
                return;
            }
            window.location = base_url +
                "FeesAlert/admission_confirmation/" +
                year + "/" +
                std + "/" +
                div;
        });
    </script>
</body>

</html>