<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title> | Olive English School</title>

    <!-- Meta -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="description" content="" />

    <meta name="keywords" content="">

    <meta name="author" content="Phoenixcoded" />





    <?php include 'application/views/cssPages.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">

    <!-- Boxicons CSS -->

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>



<body class="">

    <!-- [ Pre-loader ] start -->

    <div class="loader-bg">

        <div class="loader-track">

            <div class="loader-fill"></div>

        </div>

    </div>

    <!-- [ Pre-loader ] End -->

    <?php include 'sidebar.php'; ?>

    <!-- [ Header ] start -->

    <?php include 'topNav.php'; ?>

    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <div class="pcoded-content">
            <input type="hidden" id="getFullScreenMode">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Std :</label>
                        <select class="form-control form-control-sm" name="std" id="std">
                            <option value="">Select</option>
                            <?php

                            foreach ($classes as $classs) {

                                ?>
                                <option <?= $classs['stvfees_class'] == $class ? 'selected' : '' ?>
                                    value="<?php echo $classs['stvfees_class']; ?>"><?php echo $classs['stvfees_class']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Div :</label>
                        <select class="form-control form-control-sm" name="div" id="div">
                            <option value="">Select</option>
                            <option <?= $div == 'A' ? 'selected' : '' ?> value="A">A</option>
                            <option <?= $div == 'B' ? 'selected' : '' ?> value="B">B</option>
                            <option <?= $div == 'C' ? 'selected' : '' ?> value="C">C</option>
                        </select>
                    </div>
                    <input type="hidden" name="link_type" id="link_type" value="<?= $type ?>">
                </div>
                <div class="col-4">
                    <label for=""><small>Click to View</small></label>
                    <button class="btn btn-sm btn-success pb-0 w-100" type="button" id="view_button">View</button>
                </div>
            </div>
            <style>
                .table thead tr th,
                .table tbody tr td {
                    font-size: 10px !important;
                    padding: 5px !important;
                }
            </style>
            <div class="row">
                <?php if ($type == 'alert'): ?>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr style="text-align: center;">

                                        <td style="font-weight: bold; width:10%">R.No</td>

                                        <td style="font-weight: bold; text-align: left;width:auto;">Name</td>
                                        <td style="font-weight: bold; text-align: left;width:auto;">Fees Paid</td>

                                        <td style="font-weight: bold; text-align: left;">Action</td>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $total_students = count($students);
                                    for ($i = 0; $i < $total_students; $i++) {

                                        $next = $i + 1;

                                        ?>

                                        <tr>

                                            <td>
                                                <input type="hidden" name="line_id<?= $next ?>" id="line_id<?= $next ?>"
                                                    value="<?= $students[$i]['STVCLAS_code'] ?>">

                                                <input type="hidden" name="line_sid<?= $next ?>" id="line_sid<?= $next ?>"
                                                    value="<?= $students[$i]['STVCLAS_student_id'] ?>">
                                                <lable class="form-control form-control-sm text-left" style="border:none;"
                                                    value="<?= $students[$i]['STVCLAS_rollno'] ?>" name="roll_no<?= $next ?>">
                                                    <?= $students[$i]['STVCLAS_rollno'] ?>
                                                </lable>
                                            </td>


                                            </td>

                                            <td data-toggle="modal" data-target="#exampleModal" class="addFeedbackByClick"
                                                data-id="<?php echo $students[$i]['STVCLAS_code'] ?>">

                                                <?php echo $students[$i]['SPRIDEN_surname'] . " " . $students[$i]['SPRIDEN_student_name'] . " " . $students[$i]['SPRIDEN_father_name'] ?>

                                            </td>
                                            <td>
                                                <?php echo "<span class='text-danger'>" . $students[$i]['total_fees_paid'] . "</span> / <span class='text-primary'>" . $students[$i]['total_fees_to_be_paid'] . "</span>" ?>
                                            </td>


                                            <td>
                                                <?php if ($students[$i]['stvpcnf_whatsapp'] != ''):
                                                    $student_id = $students[$i]['SPRIDEN_student_id'];
                                                    $Total_fees = $students[$i]['total_fees_to_be_paid'];
                                                    $Paid_Fees = $students[$i]['total_fees_paid_by_st'];
                                                    $Balance_Fees = $students[$i]['remaining_fees_to_be_paid'];
                                                    $academicYear = "2026-27";
                                                    $message = "Olive English School - Student Profile Link \n";
                                                    $message = "अस्सलामुअलैकुम पेरेंट्स,आपको इत्तेला दी जाती है के,आप के बच्चे की फिस आप ने $Paid_Fees भरी है, $Balance_Fees भरना बाकी है, आप अपने बच्चे की फिस सेमिस्टर एग्जाम शुरू होने से पेहले यानी 24 ऑक्टोबर $academicYear से पेहले फिस भर कर हॉल टिकट स्कूल से हासिल करे. \n Assalamualaikum Parents,
                                                This message is to inform you that your child fees $Paid_Fees/- Rs is paid of year $academicYear and   $Balance_Fees /- Rs amount is balance. First semester exam is starting from 25th October Pay Balance Fees before exam and get your hall ticket for examination Thanking you Olive English School";

                                                    // ======
                                        
                                                    $confirm_message = "अस्सलामुअलैकुम ,\n";
                                                    $confirm_message .= "आपके बच्चे का अकादमिक साल {$academicYear} के लिए एडमिशन अभी तक कन्फर्म नहीं हुआ है। कृपया कल ही के दिन री-एडमिशन करवा ऐ, क्योंकि 10 अगस्त 2026 से एग्जाम(परीक्षा) शुरू हो रही हैं। जिन बच्चो का एडमिशन कन्फर्म नहीं होगा, उन्हें एग्जाम(परीक्षा) में बैठने की इजाज़त नहीं दी जाएगी। और अगर आपके बच्चे का री-एडमिशन हो चूका है तो इस मैसेज को नज़रअंदाज़ कर दीजिये\n\n";
                                                    $confirm_message .= "ऑफिस में मिलने का टाइम \n";
                                                    // $confirm_message .= "अब तक जमा की गई फीस: ₹$Paid_Fees\n";
                                                    // $confirm_message .= "बाकी फीस: ₹$Balance_Fees\n\n";
                                                    // $confirm_message .= "मेहरबानी करके फौरन स्कूल ऑफिस तशरीफ लाएं, फीस जमा करें और एडमिशन की कारवाई मुकम्मल करके अपने बच्चे का दाखिला कन्फर्म करें।\n\n";
                                                    $confirm_message .= "Time to meet at the office,\n";
                                                    $confirm_message .= " Class: I to IV , Time: 12:30 to 2:00 For All\n\n";
                                                    $confirm_message .= " Class: V to XII , Time: 12:30 to 1:30 For Girls\n\n";
                                                    $confirm_message .= " Class: V to XII , Time: 1:15 to 2:00 For Boys\n\n";
                                                    $confirm_message .= "Assalamualaikum,\n";
                                                    $confirm_message .= "Your child's admission for the academic year {$academicYear} has not yet been confirmed. Please complete the re-admission process tomorrow itself, as examinations begin on August 10, 2026. Students whose admission is not confirmed will not be permitted to appear for the exams. If your child's re-admission has already been completed, please ignore this message.
                                                    
                                                    \n";
                                                    // $confirm_message .= "Your child's admission for the academic year {$academicYear} has not yet been confirmed.Please complete the re-admission process tomorrow itself, as examinations begin on August 10, 2026. Students whose admission is not confirmed will not be permitted to sit for the exams.";
                                                    // $confirm_message .= "Fees Paid: Rs. $Paid_Fees\n";
                                                    // $confirm_message .= "Balance Fees: Rs. $Balance_Fees\n\n";
                                                    // $confirm_message .= "Kindly visit the school office, \n\n";
                                                    $confirm_message .= " Class: I to IV , Time: 12:30 to 2:00 For All\n\n";
                                                    $confirm_message .= " Class: V to XII , Time: 12:30 to 1:30 For Girls\n\n";
                                                    $confirm_message .= " Class: V to XII , Time: 1:15 to 2:00 For Boys\n\n";
                                                    $confirm_message .= "pay the fees, and complete the readmission process at the earliest.\n\n";



                                                    $confirm_message .= "Regards,\n";
                                                    $confirm_message .= "Olive English School";

                                                    ?>
                                                    <?php
                                                    $paidFees = (float) $students[$i]['total_fees_paid'];
                                                    ?>

                                                    <a target="_blank" class="text-success"
                                                        href="https://api.whatsapp.com/send?text=<?= urlencode($message) ?>&phone=+91<?= $students[$i]['stvpcnf_whatsapp'] ?>">
                                                        <i class='bx bxl-whatsapp'></i> Balance Fees
                                                    </a>

                                                    <?php if ($paidFees == 0): ?>
                                                        |
                                                        <a target="_blank"
                                                            href="https://api.whatsapp.com/send?text=<?= urlencode($confirm_message) ?>&phone=+91<?= $students[$i]['stvpcnf_whatsapp'] ?>">
                                                            <i class='bx bxl-whatsapp'></i> Zero Paid
                                                        </a>
                                                    <?php endif; ?>
                                                    <!-- <a target="_blank" class="text-success"
                                                        href="https://api.whatsapp.com/send?text=<?= urlencode($message) ?>&phone=+91<?= $students[$i]['stvpcnf_whatsapp'] ?>">
                                                        <i class='bx bxl-whatsapp'></i> Paid Balance
                                                    </a> |
                                                    <?php if ($total_fees_paid == 0): ?>
                                                    <a target="_blank"
                                                        href="https://api.whatsapp.com/send?text=<?= urlencode($confirm_message) ?>&phone=+91<?= $students[$i]['stvpcnf_whatsapp'] ?>">
                                                        <i class='bx bxl-whatsapp'></i> Zero Balance
                                                    </a>
                                                    <?php endif; ?> -->
                                                <?php endif; ?>
                                            </td>

                                        </tr>

                                        <?php

                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr style="text-align: center;">

                                        <td style="font-weight: bold; width:10%">R.No</td>

                                        <td style="font-weight: bold; text-align: left;width:auto;">Name</td>

                                        <td style="font-weight: bold; text-align: left;">Action</td>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $total_students = count($students);
                                    for ($i = 0; $i < $total_students; $i++) {

                                        $next = $i + 1;

                                        ?>

                                        <tr>

                                            <td>
                                                <input type="hidden" name="line_id<?= $next ?>" id="line_id<?= $next ?>"
                                                    value="<?= $students[$i]['STVCLAS_code'] ?>">

                                                <input type="hidden" name="line_sid<?= $next ?>" id="line_sid<?= $next ?>"
                                                    value="<?= $students[$i]['STVCLAS_student_id'] ?>">
                                                <lable class="form-control form-control-sm text-left" style="border:none;"
                                                    value="<?= $students[$i]['STVCLAS_rollno'] ?>" name="roll_no<?= $next ?>">
                                                    <?= $students[$i]['STVCLAS_rollno'] ?>
                                                </lable>
                                            </td>



                                            <td data-toggle="modal" data-target="#exampleModal" class="addFeedbackByClick"
                                                data-id="<?php echo $students[$i]['STVCLAS_code'] ?>">

                                                <?php echo $students[$i]['SPRIDEN_surname'] . " " . $students[$i]['SPRIDEN_student_name'] . " " . $students[$i]['SPRIDEN_father_name'] ?>

                                            </td>


                                            <td>
                                                <?php if ($students[$i]['stvpcnf_whatsapp'] != ''):
                                                    $student_id = $students[$i]['SPRIDEN_student_id'];
                                                    $token = base64_encode($student_id);
                                                    $share_url = base_url() . "profile?source=sharer&student-id=$student_id&token=$token";
                                                    $message = "Olive English School - Student Profile Link \n";
                                                    $message .= $share_url;
                                                    ?>
                                                    <a target="_blank"
                                                        href="https://api.whatsapp.com/send?text=<?= urlencode($message) ?>&phone=+91<?= $students[$i]['stvpcnf_whatsapp'] ?>">
                                                        <i class='bx bxl-whatsapp'></i> Share Link
                                                    </a>
                                                <?php endif; ?>
                                            </td>

                                        </tr>

                                        <?php

                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Required Js -->

    <?php include 'application/views/jsPages.php'; ?>

    <script>
        var view_button = document.getElementById("view_button");
        let base_url = '<?= base_url() ?>';
        view_button.addEventListener("click", function () {

            var std = document.getElementById("std").value;
            var div = document.getElementById("div").value;
            var type = document.getElementById("link_type").value;
            let url = `${base_url}Dashboard/share_links?std=${std}&div=${div}`;
            if (type == 'alert')
                url += `&type=${type}`;
            window.location = url;
        });
    </script>

</body>



</html>