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

        .dataTables_wrapper {
            padding: 10px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            margin: 0 3px;
            border: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1f3864 !important;
            color: #fff !important;
        }

        #simpletable {
            width: 100%;
        }

        #simpletable thead th {
            background: #1f3864 !important;
            color: #fff !important;
            border: none !important;
            padding: 5px !important;
            font-size: 13px !important;
            font-weight: 600;
            text-align: center;
        }

        #simpletable tbody td {
            background: #fff !important;
            padding: 5px !important;
            border-top: 1px solid #ececec !important;
            vertical-align: middle;
        }

        #simpletable tbody tr:nth-child(even) {
            background: #f7faffcc !important;
        }

        #simpletable tbody tr:nth-child(even) td {
            background: #f7faffcc !important;
        }

        #simpletable tbody tr:hover td {
            background: #eef4ff !important;
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
                            <h5 class="card-title">Fees balance Report</h5>
                        </div>
                        <div class="card-body p-2">
                            <div class="print-container">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <label for="" class="col-md-5">Class<span
                                                        class="float-right">:</span></label>
                                                <select name="type" id="type"
                                                    class="form-control form-control-sm col-md-7">
                                                    <option>Select Type</option>
                                                    <option value="all">Last All Previous Fees</option>
                                                    <option value="zero">Current Zero Paid</option>
                                                    <option value="last">Last Balance Current Zero</option>
                                                    <option value="balanced">Current Balance Last Zero</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <button class="btn btn-sm btn-outline-secondary" id='search'>View</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="dt-responsive table-responsive margin-top-minus-10">
                                        <table id="simpletable" class="table table-hover align-middle mb-0">
                                            <thead id="tableHead">
                                                <?php $type = $this->input->get('type'); ?>
                                                <?php $academic_years = isset($academic_years) && is_array($academic_years) ? $academic_years : []; ?>
                                                <?php if ($type == "all") { ?>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student ID</th>
                                                        <th>Student Name</th>
                                                        <th>Mobile Number</th>

                                                        <?php foreach ($academic_years as $year) { ?>
                                                            <th class="text-right">
                                                                <?= html_escape($year['academic_year_name']); ?>
                                                            </th>
                                                        <?php } ?>
                                                        <th class="text-center">WhatsApp</th>

                                                    </tr>
                                                    </tr>
                                                <?php } elseif ($type == "last") { ?>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student ID</th>
                                                        <th>Student Name</th>
                                                        <th>Academic Year</th>
                                                        <th>Total Fee</th>
                                                        <th>Total Paid</th>
                                                        <th>Pending Fees</th>
                                                    </tr>
                                                <?php } elseif ($type == "zero") { ?>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student ID</th>
                                                        <th>Student Name</th>
                                                        <th>Mobile Number</th>
                                                        <th>Class</th>
                                                        <th>Division</th>
                                                        <th class="text-center">WhatsApp</th>
                                                    </tr>
                                                <?php } elseif ($type == "balanced") { ?>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student ID</th>
                                                        <th>Student Name</th>
                                                        <th>Class</th>
                                                        <th>Division</th>
                                                        <th>Current Fee</th>
                                                        <th>Current Paid</th>
                                                        <th>Current Balance</th>
                                                    </tr>
                                                <?php } ?>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $type = $this->input->get('type');
                                                $normalizeWhatsAppNumber = static function ($mobileNumber) {
                                                    $digits = preg_replace('/\D+/', '', (string) $mobileNumber);

                                                    if ($digits === '') {
                                                        return '';
                                                    }

                                                    // Remove starting zeros.
                                                    $digits = ltrim($digits, '0');

                                                    // Indian mobile number without country code.
                                                    if (strlen($digits) === 10) {
                                                        return '91' . $digits;
                                                    }

                                                    // Indian mobile number already containing country code.
                                                    if (
                                                        strlen($digits) === 12 &&
                                                        substr($digits, 0, 2) === '91'
                                                    ) {
                                                        return $digits;
                                                    }

                                                    return '';
                                                };

                                                foreach ($records as $row) {
                                                    echo '<tr>';


                                                    if ($type == 'all') {
                                                        $studentName = trim(
                                                            (string) ($row['name'] ?? '')
                                                        );

                                                        $mobileNumber = trim(
                                                            (string) ($row['MobileNumber'] ?? '')
                                                        );

                                                        $whatsappNumber =
                                                            $normalizeWhatsAppNumber($mobileNumber);

                                                        $pendingYearDetails = '';
                                                        $totalOutstanding = 0;

                                                        foreach ($academic_years as $year) {
                                                            $academicYearId =
                                                                (int) $year['academic_year_id'];

                                                            $academicYearName =
                                                                (string) $year['academic_year_name'];

                                                            $pendingKey =
                                                                'Pending_' . $academicYearId;

                                                            $pendingAmount =
                                                                isset($row[$pendingKey])
                                                                ? (float) $row[$pendingKey]
                                                                : 0;

                                                            if ($pendingAmount <= 0) {
                                                                continue;
                                                            }

                                                            $totalOutstanding += $pendingAmount;

                                                            $pendingYearDetails .=
                                                                "Academic Year: {$academicYearName}\n";

                                                            $pendingYearDetails .=
                                                                "Pending Fees: ₹" .
                                                                number_format($pendingAmount, 0) .
                                                                "\n";

                                                            $pendingYearDetails .=
                                                                "-----------------------------\n";
                                                        }

                                                        $message = "Assalamualaikum\n\n";

                                                        $message .= "Dear Parent,\n\n";

                                                        $message .=
                                                            "This is to inform you that previous academic year " .
                                                            "fees for your child {$studentName} are still pending.\n\n";

                                                        $message .= "Pending Fees Details\n";
                                                        $message .= "=============================\n\n";

                                                        $message .= $pendingYearDetails;

                                                        $message .= "\nTotal Outstanding: ₹" .
                                                            number_format($totalOutstanding, 0) .
                                                            "\n\n";

                                                        $message .=
                                                            "Kindly visit the school office and clear the " .
                                                            "pending fees at the earliest.\n\n";

                                                        $message .=
                                                            "आपके बच्चे {$studentName} की पिछली कक्षाओं की " .
                                                            "फीस अभी बाकी है।\n\n";

                                                        $message .=
                                                            "कुल बकाया फीस: ₹" .
                                                            number_format($totalOutstanding, 0) .
                                                            "\n\n";

                                                        $message .=
                                                            "कृपया स्कूल ऑफिस में आकर बाकी फीस जल्द से जल्द " .
                                                            "जमा करें।\n\n";

                                                        $message .=
                                                            "आपके सहयोग के लिए धन्यवाद।\n\n";

                                                        $message .= "Regards,\n";
                                                        $message .= "Olive English School\n";
                                                        $message .= "Dhule";

                                                        $whatsappUrl = '';

                                                        if (
                                                            $whatsappNumber !== '' &&
                                                            $totalOutstanding > 0
                                                        ) {
                                                            $whatsappUrl =
                                                                'https://wa.me/' .
                                                                $whatsappNumber .
                                                                '?text=' .
                                                                rawurlencode($message);
                                                        }

                                                        echo '<td>' .
                                                            $i++ .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($row['SPRIDEN_student_id']) .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($studentName) .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($mobileNumber) .
                                                            '</td>';

                                                        foreach ($academic_years as $year) {
                                                            $academicYearId =
                                                                (int) $year['academic_year_id'];

                                                            $pendingKey =
                                                                'Pending_' . $academicYearId;

                                                            $pendingAmount =
                                                                isset($row[$pendingKey])
                                                                ? (float) $row[$pendingKey]
                                                                : 0;

                                                            echo '<td class="text-right fee-amount">' .
                                                                number_format($pendingAmount, 0) .
                                                                '</td>';
                                                        }

                                                        echo '<td class="text-center">';

                                                        if ($whatsappUrl !== '') {
                                                            echo '<a
            href="' . html_escape($whatsappUrl) . '"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-outline-success btn-sm rounded-pill text-center"
            title="Send pending fees WhatsApp message"
        >
            <i class="fab fa-whatsapp"></i>
            Send
        </a>';
                                                        } else {
                                                            echo '';
                                                        }

                                                        echo '</td>';
                                                    } elseif ($type == "last") {
                                                         $paid = (float) $row['Total_paid'];
                                                        $totalFee = (float) $row['Total_fee'];

                                                        echo "<td>" . $i++ . "</td>";
                                                        echo "<td>" . $row['SPRIDEN_student_id'] . "</td>";
                                                        echo "<td>" . $row['name'] . "</td>";
                                                        echo "<td>" . $row['Academic_year'] . "</td>";
                                                        echo "<td>" . $row['Total_fee'] . "</td>";
                                                        if ($paid == 0) {
                                                            echo "<td><span class='text-danger fw-bold'>
                                                                    " . number_format($paid) . "
                                                                </span></td>";
                                                        } elseif ($paid < $totalFee) {
                                                            echo "<td><span class=' fw-bold'>
                                                                    " . number_format($paid) . " <i class='fas fa-arrow-down ms-1 text-warning'></i>
                                                                    </span></td>";
                                                        } else {
                                                            echo "<td><span class='text-success fw-bold'>
                                                                        " . number_format($paid) . "
                                                                    </span></td>";
                                                        }
                                                        
                                                        echo "<td>" . $row['Pending_fees'] . "</td>";
                                                    } elseif ($type == "zero") {
                                                        
                                                        $studentName = trim(
                                                            (string) ($row['name'] ?? '')
                                                        );

                                                        $mobileNumber = trim(
                                                            (string) ($row['MobileNumber'] ?? '')
                                                        );

                                                        $whatsappNumber =
                                                            $normalizeWhatsAppNumber($mobileNumber);

                                                        /*
             * WhatsApp message for this student.
             */
                                                        $message = "Assalamualaikum\n\n";

                                                        $message .= "Dear Parent,\n\n";

                                                        $message .=
                                                            "Your child {$studentName}'s admission " .
                                                            "for the current academic year is still pending.\n\n";

                                                        $message .=
                                                            "Our records show that the current year fee " .
                                                            "payment and readmission process have not yet " .
                                                            "been completed.\n\n";

                                                        $message .=
                                                            "Kindly visit the school office, pay the " .
                                                            "applicable fees, and complete the readmission " .
                                                            "process at the earliest.\n\n";

                                                        $message .= "Office Timings:\n";

                                                        $message .=
                                                            "Class I to IV: 12:30 PM to 2:00 PM\n";

                                                        $message .=
                                                            "Class V to XII Girls: 12:30 PM to 1:30 PM\n";

                                                        $message .=
                                                            "Class V to XII Boys: 1:15 PM to 2:00 PM\n\n";

                                                        $message .=
                                                            "आपके बच्चे {$studentName} का इस शैक्षणिक " .
                                                            "वर्ष का एडमिशन अभी तक पूरा नहीं हुआ है। " .
                                                            "हमारे रिकॉर्ड के अनुसार वर्तमान वर्ष की फीस " .
                                                            "और री-एडमिशन की प्रक्रिया पूरी नहीं हुई है।\n\n";

                                                        $message .=
                                                            "कृपया स्कूल ऑफिस आकर फीस जमा करें और " .
                                                            "री-एडमिशन की प्रक्रिया जल्द पूरी करें।\n\n";

                                                        $message .=
                                                            "आपके सहयोग के लिए धन्यवाद।\n\n";

                                                        $message .= "Regards,\n";
                                                        $message .= "Olive English School\n";
                                                        $message .= "Dhule";

                                                        $whatsappUrl = '';

                                                        if ($whatsappNumber !== '') {
                                                            $whatsappUrl =
                                                                'https://wa.me/' .
                                                                $whatsappNumber .
                                                                '?text=' .
                                                                rawurlencode($message);
                                                        }

                                                        echo '<td>' .
                                                            $i++ .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($row['StudentID']) .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($studentName) .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($mobileNumber) .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($row['Class']) .
                                                            '</td>';

                                                        echo '<td>' .
                                                            html_escape($row['Division']) .
                                                            '</td>';

                                                        echo '<td class="text-center">';

                                                        if ($whatsappUrl !== '') {
                                                            echo '<a 
                    href="' . html_escape($whatsappUrl) . '"
                    target="_blank"
                    rel="noopener noreferrer"
            class="btn btn-outline-success btn-sm rounded-pill text-center"
                    title="Send WhatsApp message"
                >
                    <i class="fab fa-whatsapp"></i>
                    Send
                </a>';
                                                        } else {
                                                            echo '';
                                                        }

                                                        echo '</td>';
                                                    } elseif ($type == "balanced") {
                                                        $paids = (float) $row['LastYearPaid'];
                                                        $totalFees = (float) $row['LastYearFee'];

                                                        echo "<td>" . $i++ . "</td>";
                                                        echo "<td>" . $row['StudentID'] . "</td>";
                                                        echo "<td>" . $row['name'] . "</td>";
                                                        echo "<td>" . $row['Class'] . "</td>";
                                                        echo "<td>" . $row['Division'] . "</td>";
                                                        echo "<td>" . $row['LastYearFee'] . "</td>";
                                                        if ($paids == 0) {
                                                            echo "<td><span class='text-danger fw-bold'>
                                                                    " . number_format($paids) . "
                                                                </span></td>";
                                                        } elseif ($paids < $totalFees) {
                                                            echo "<td><span class=' fw-bold'>
                                                                    " . number_format($paids) . "
                                                                    <i class='fas fa-arrow-down ms-1 text-warning'></i>
                                                                </span></td>";
                                                        } else {
                                                            echo "<td><span class='text-success fw-bold'>
                                                                    " . number_format($paids) . "
                                                                    </span></td>";
                                                        }
                                                        echo "<td>" . $row['LastYearBalance'] . "</td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                            <?php if ($type == "all") { ?>
                                                <tfoot>
                                                    <tr>
                                                        <th
                                                            colspan="4"
                                                            id="all-report-total-label"
                                                            class="text-right font-weight-bold">
                                                            Total
                                                        </th>

                                                        <?php foreach ($academic_years as $year) { ?>
                                                            <th
                                                                id="academic-year-total-<?=
                                                                                        (int) $year['academic_year_id'];
                                                                                        ?>"
                                                                class="text-right font-weight-bold">
                                                                0
                                                            </th>
                                                        <?php } ?>
                                                    </tr>
                                                </tfoot>
                                            <?php } ?>
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
        $(document).ready(function() {
            var type = "<?php echo $this->input->get('type'); ?>";
            if (type != "") {
                $("#type").val(type);
            }
            $("#search").click(function() {
                var type = $("#type").val();
                if (type == "" || type == "Select Type") {
                    alert("Please Select Report Type");
                    return;
                }
                window.location.href =
                    "<?php echo base_url('Report/fees_balance_report'); ?>?type=" +
                    encodeURIComponent(type);
            });
        });
    </script>
    <?php if ($type == "all") { ?>
        <script>
            $(document).ready(function() {
                let totalBindingAttempts = 0;

                function convertToNumber(value) {
                    if (value === null || value === undefined || value === '') {
                        return 0;
                    }

                    const cleanedValue = String(value)
                        .replace(/<[^>]*>/g, '')
                        .replace(/,/g, '')
                        .replace(/[^\d.-]/g, '');

                    const numericValue = parseFloat(cleanedValue);

                    return Number.isFinite(numericValue) ?
                        numericValue :
                        0;
                }

                function formatFeeAmount(value) {
                    return Number(value).toLocaleString('en-IN', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }

                function updateAllReportTotals(dataTable) {
                    /*
                     * Column positions:
                     * 0 = Serial Number
                     * 1 = Student ID
                     * 2 = Student Name
                     * 3 = Mobile Number
                     * 4 onwards = Academic year pending amounts
                     */

                    const firstFeeColumnIndex = 4;

                    const totalRecordCount = dataTable
                        .rows({
                            search: 'none',
                            page: 'all'
                        })
                        .count();

                    $('#all-report-total-label').text(
                        'Total (' +
                        totalRecordCount +
                        ' Students)'
                    );

                    <?php foreach ($academic_years as $index => $year) { ?> {
                            const columnIndex =
                                firstFeeColumnIndex + <?= (int) $index; ?>;

                            const yearTotal = dataTable
                                .column(columnIndex, {
                                    search: 'none',
                                    page: 'all'
                                })
                                .data()
                                .reduce(function(total, value) {
                                    return total + convertToNumber(value);
                                }, 0);

                            $('#academic-year-total-<?=
                                                    (int) $year['academic_year_id'];
                                                    ?>').text(
                                formatFeeAmount(yearTotal)
                            );
                        }
                    <?php } ?>
                }

                function bindAllReportTotals() {
                    totalBindingAttempts++;

                    if (
                        $.fn.dataTable &&
                        $.fn.dataTable.isDataTable('#simpletable')
                    ) {
                        const dataTable = $('#simpletable').DataTable();

                        dataTable.off('draw.feesBalanceTotals');

                        dataTable.on(
                            'draw.feesBalanceTotals',
                            function() {
                                updateAllReportTotals(dataTable);
                            }
                        );

                        updateAllReportTotals(dataTable);
                        return;
                    }

                    /*
                     * jsPages.php may initialize DataTables after this script.
                     * Retry briefly until the existing DataTable is ready.
                     */
                    if (totalBindingAttempts < 20) {
                        setTimeout(bindAllReportTotals, 100);
                    }
                }

                bindAllReportTotals();
            });
        </script>
    <?php } ?>
</body>

</html>