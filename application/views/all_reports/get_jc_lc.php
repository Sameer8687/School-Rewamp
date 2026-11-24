<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Update Student Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" />
    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <style type="text/css" media="print">
        @media print {
            .no-print {
                display: none !important;
            }
        }

        @media print {

            html,
            body {
                height: 100vh;
                background-color: #ffffff;
                margin: none;
            }
        }

        .cont {
            margin-left: 300px;
        }

        .lc {
            margin-left: 900px;
        }

        h2 {
            margin-left: 30px
        }

        .form-control,
        .form-control-sm {
            border: none;
            border-bottom: 1px solid #56565650;
        }

        .form-control-sm {
            box-shadow: none;
            font-size: 15px;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #ffffff;
            opacity: 1;
        }

        @media print {
            @page {
                font-size: xx-large;
                size: A4 portrait;
                width: 100%;
                height: 100vh;
                margin: 5mm;
                scale: 100;
            }
        }

        .container {
            font-weight: 700;
        }

        .form-control {
            font-weight: 700;
        }

        .fonts {
            font-weight: 1000 !important;
            font-size: medium !important;
        }
    </style>
</head>

<body class="">
    <?php include 'application/views/topNav.php'; ?>
    <!--Include Sidebar -->
    <?php include 'application/views/sidebar.php'; ?>
    <!--Include Top Navbar-->
    <div class="pc-container">
        <form action="<?= base_url('report/student_lc_status') ?>" method="post">
            <div class="pcoded-content">
                <?php include 'application/views/Student/links.php'; ?>

                <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">
                    <div class="col-md-12 no-print pl-4">
                        <a href="<?= base_url('Report/report_stats_lc') ?>" class="btn btn-danger btn-sm"> <i
                                class="fas fa-arrow-alt-circle-left no-print  "></i> Back</a>
                        <a href="" class="btn btn-primary btn-sm no-print " onclick="window.print()"> <i
                                class="fa fa-print"></i> Print</a>
                        <?php if ($record['date_issued_on'] == null): ?>
                            <button class="btn btn-sm no-print btn-info <?= $record['lc_num'] != 0 ? 'd-nones' : ''; ?>"
                                type="button" id="issue-lc">
                                <i class="fas fa-file-alt"></i>Issue LC</button>
                        <?php endif; ?>

                        <input type="hidden" value="<?= $lc_no['lc_no']; ?>" id="hidden_lc_number">
                        <input type="hidden" value="<?= $student_std; ?>" id="hidden_std">

                        <?php $stud_type = ($student_std == 'XI' || $student_std == 'XII') ? 'jc' : 'school'; ?>

                        <input type="hidden" value="<?= $stud_type; ?>" id="hidden_stud_type">

                        <span
                            class="ml-3 font-weight-bold text-danger"><?php isset($record['lc_status']) == 'Issued' ? 'LC Issued on - ' . isset($record['date_issued_on']) : '' ?></span>
                    </div>
                </div>

                <div class="row" id="alertRow">
                    <div class="col-md-12">
                        <?php $message = $this->session->userdata('message');
                        $alert_class = $this->session->userdata('alert_class');
                        if ($message != "") { ?>
                            <div class="alert <?= $alert_class; ?>">
                                <?= $message; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <style>
                    h1 {
                        color: #ff768e;
                        font-weight: bold;
                        text-align: center;
                    }

                    h2 {
                        color: #ff768e;
                        font-weight: bold;
                        text-align: center;
                    }

                    .image {
                        height: 100px;
                        object-fit: cover;
                    }

                    .border-top {
                        border-top: 1px solid #888888 !important;
                        width: 450px;
                    }

                    .custom-divider {
                        margin: 0 !important;
                        color: #000000 !important;
                        border-top: 3px solid #000000 !important;
                        padding-bottom: 5px !important;
                    }
                </style>

                <div class="container print">
                    <div class="card mt-1">
                        <div class="card-body ">
                            <div>
                                <input type="hidden" value="<?= $record['id'] ?>" id="spr_id" name="spr_id">

                                <div class="d-flex mb-1 mx-4 align-items-center">
                                    <div><img src="<?= base_url('assets/images/jc_logo.jpg') ?>" class="image" /></div>
                                    <div class="flex-grow-1">
                                        <h5 class="text-center">Uttar Maharashtra Educational Welfare Trust Dhule</h5>
                                        <h2 style="margin-left:20px">
                                            <span class=""></span>Olive English Medium School & Jr. College Dhule
                                        </h2>
                                        <h5 class="text-center">National Highway No.3, Near Traffic Police Station,
                                            Dhule.</h5>
                                    </div>
                                </div>

                                <div class="custom-divider"></div>
                                
                                <?php $lc_number = $record['lc_num'] != 0 ? $record['lc_num'] : $lc_no['lc_no'] ?>
                                <style>
                                    .tab {
                                        width: 100%
                                    }

                                    .tr {
                                        margin: 50px !important;
                                    }
                                </style>
                                <div class="">
                                    <table class="tab">
                                        <tr class="">
                                            <td class="p-1"></td>
                                            <td class="p-1"></td>
                                            <td class="text-center P-1">Recog No:- निर्णय क्रमांक एसएफएस-१०२१ / प्र.क्र
                                                ९७९ / एसएस-२/२०२३
                                            </td>
                                            <td class="p-1">Index No :-</td>
                                            <td class="p-1">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>

                                        <tr class="tr">
                                            <td class="p-0 border border-dark text-center">G.R No :- </td>
                                            <td class="text-center p-0 border border-dark">
                                                <?= $record['stvclas_standard'] == 'XI' || $record['stvclas_standard'] == 'XII' ? $record['spriden_college_gr'] : $record['spriden_gr_number'] ?>
                                            </td>
                                            <td class="text-center border-top p-1 ml-5">
                                                मा. शिक्षण उपसंचालक नाशिक विभाग नाशिक यांचे कडील <br> पत्र आदेश क्र. शि
                                                उ सं / नावी / माध्य -२/ दर्जावाढ प्रस्ताव /२०२३/९३०१/०२
                                            </td>
                                            <td class="p-0 border border-dark text-center">L.C No :-</td>
                                            <td class="text-center p-0 border border-dark"><?= $lc_number ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="mt-2">
                                    <table class="w-100">
                                        <tr>
                                            <td class="border border-dark" style=" text-align: center; color: #ff768e">
                                                UDISE NO.
                                            </td>
                                            <td class="border border-dark" style=" text-align: center; color: #ff768e">
                                                27020500322
                                            </td>
                                            <td class="border border-dark" style=" text-align: center; color: #ff768e">
                                                BOARD
                                            </td>
                                            <td class="border border-dark" style=" text-align: center; color: #ff768e">
                                                Nashik
                                            </td>
                                            <td class="border border-dark" style=" text-align: center; color: #ff768e">
                                                MEDIUM
                                            </td>
                                            <td class="border border-dark" style=" text-align: center; color: #ff768e">
                                                English
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <style>
                                    textarea {
                                        border: none;
                                        border-bottom: 1px solid #56565650;
                                    }

                                    .box-col {
                                        display: grid;
                                        grid-template-columns: repeat(20, 1fr);
                                        width: 80%;
                                        justify-content: space-around;
                                    }

                                    .box-aadhar {
                                        display: grid;
                                        grid-template-columns: repeat(12, 1fr);
                                        width: 80%;
                                        justify-content: space-around;
                                    }

                                    .box-dob {
                                        display: grid;
                                        grid-template-columns: repeat(8, 1fr);
                                        width: 80%;
                                        justify-content: space-around;
                                    }

                                    .box-dob span {
                                        border: 1px solid #56565650;
                                        border-collapse: collapse;
                                        padding: 5px;
                                        text-align: center;
                                    }

                                    .box-adm {
                                        display: grid;
                                        grid-template-columns: repeat(8, 1fr);
                                        width: 50%;
                                        justify-content: space-around;
                                    }

                                    .box-adm span {
                                        border: 1px solid #56565650;
                                        border-collapse: collapse;
                                        padding: 5px;
                                        text-align: center;
                                    }

                                    .box-col span {
                                        border: 1px solid #56565650;
                                        border-collapse: collapse;
                                        padding: 5px;
                                        text-align: center;
                                    }

                                    .box-aadhar span {
                                        border: 1px solid #56565650;
                                        border-collapse: collapse;
                                        padding: 2px;
                                        text-align: center;
                                        height: 32px;

                                    }
                                </style>
                                <div style="background-color: #ffdee3;color: #ff768e;text-align: center;padding: 3px;"
                                    class="mt-2">
                                    <h3
                                        style="margin-bottom: 0.3rem;color:#ff768e;font-weight:bold;    margin-top: 0.3rem;">
                                        LEAVING CERTIFICATE</h3>
                                </div>

                                <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                    Student ID
                                    <div class="box-col">
                                        <?php $reg_arr = [];
                                        $str_reg = str_replace("ID:", '', $record['SPRIDEN_reg_number']);
                                        for ($i = 0; $i < strlen($str_reg); $i++) {
                                            array_push($reg_arr, $str_reg[$i]);
                                        }
                                        if (count($reg_arr) < 20) {
                                            for ($i = count($reg_arr); $i < 20; $i++) {
                                                array_push($reg_arr, '');
                                            }
                                        }
                                        foreach ($reg_arr as $reg):
                                            echo "<span>" . $reg . "</span>";
                                        endforeach; ?>
                                    </div>
                                </div>

                                <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                    PEN No
                                    <div class="box-aadhar">
                                        <?php $pen_arr = [];
                                        for ($i = 0; $i < strlen($record['SPRIDEN_pan_no']); $i++) {
                                            array_push($pen_arr, $record['SPRIDEN_pan_no'][$i]);
                                        }
                                        if (count($pen_arr) < 12) {
                                            for ($i = count($pen_arr); $i < 12; $i++) {
                                                array_push($pen_arr, '');
                                            }
                                        }
                                        foreach ($pen_arr as $pen):
                                            echo "<span>" . $pen . "</span>";
                                        endforeach; ?>
                                    </div>
                                </div>

                                <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                    Aadhar Card No
                                    <div class="box-aadhar">
                                        <?php $adh_arr = [];
                                        for ($i = 0; $i < strlen($record['SPRIDEN_aadhar_no']); $i++) {
                                            array_push($adh_arr, $record['SPRIDEN_aadhar_no'][$i]);
                                        }
                                        if (count($adh_arr) < 12) {
                                            for ($i = count($adh_arr); $i < 12; $i++) {
                                                array_push($adh_arr, '');
                                            }
                                        }
                                        foreach ($adh_arr as $adh):
                                            echo "<span>" . $adh . "</span>";
                                        endforeach; ?>
                                    </div>
                                </div>

                                <style>
                                    .form-control,
                                    .form-control-sm {
                                        border: none;
                                        border-bottom: 1px solid #56565650;
                                    }

                                    .form-control-sm {
                                        box-shadow: none;
                                        font-size: 15px;
                                    }

                                    .form-control:disabled,
                                    .form-control[readonly] {
                                        background-color: #ffffff;
                                        opacity: 1;
                                    }
                                </style>

                                <div class="mt-3 d-flex align-items-center">Pupil's Full Name &nbsp;
                                    <input type="" class="form-control form-control-sm ml-5 " style="width: 717px"
                                        name="full_name" id="full_name"
                                        value="<?= $record['SPRIDEN_student_name'] . ' ' . $record['SPRIDEN_father_name'] . ' ' . $record['SPRIDEN_surname'] ?>"
                                        readonly />
                                </div>
                                <div class="d-flex mt-2 align-items-center">Father's Name
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 300px"
                                        name="full_name" id="full_name" value="<?= $record['SPRIDEN_father_name'] ?>"
                                        readonly />
                                    <div class="d-flex ml-4">Surname &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 250px" name="surname" id="surname"
                                            value="<?= $record['SPRIDEN_surname'] ?>" readonly />
                                    </div>
                                </div>
                                <div class="d-flex mt-2 align-items-center">Mother's Name &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 720px"
                                        name="mname" id="mname" value="<?= $record['SPRIDEN_mother_name'] ?>"
                                        readonly />
                                </div>

                                <div class="d-flex mt-2 align-items-center">Nationality &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 300px"
                                        name="indian" id="indian" value="INDIAN" readonly />
                                    <div class="d-flex ml-5 align-items-center">Mother Tongue &nbsp;
                                        <input type="text" class="form-control form-control-sm  ml-2"
                                            style="width: 250px" name="mtongue" id="mtongue"
                                            value="<?= $record['SPRIDEN_mother_toungue'] ?>" readonly />
                                    </div>
                                </div>


                                <div class="d-flex mt-2 align-items-center">Religion &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 150px"
                                        name="mtongue" id="mtongue" value="<?= $record['spriden_religion'] ?>"
                                        readonly />
                                    <div class="d-flex ml-4 align-items-center">Caste &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 150px" name="caste" id="caste" value="<?= $record['cast'] ?>"
                                            readonly />
                                    </div>
                                    <div class="d-flex ml-4 align-items-center">Sub-Caste &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 150px" name="scaste" id="scaste"
                                            value="<?= $record['sub_cast'] ?>" readonly />
                                    </div>
                                </div>

                                <div class="d-flex mt-2 align-items-center">Place of Birth &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 150px"
                                        name="mtongue" id="mtongue" value="<?= $record['spriden_birth_place'] ?>"
                                        readonly />
                                    <div class="d-flex ml-4 align-items-center">Taluka &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 150px" name="caste" id="caste"
                                            value="<?= $record['taluka'] ?>" readonly />
                                    </div>
                                    <div class="d-flex ml-4 align-items-center">District &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 150px" name="scaste" id="scaste"
                                            value="<?= $record['district'] ?>" readonly />
                                    </div>
                                </div>
                                <div class="d-flex mt-2 align-items-center align-items-center">State &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 325px"
                                        name="indian" id="state" value="<?= $record['state'] ?>" readonly />
                                    <div class="d-flex ml-4 align-items-center align-items-center">Country &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 330px" name="country" id="country" value="INDIA" readonly />
                                    </div>
                                </div>
                                <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                    Date of Birth (Figures)
                                    <div class="box-dob">
                                        <?php $dob_arr = [];
                                        $str_dob = str_replace("-", '', date("d-m-Y", strtotime($record['spriden_birthdate'])));

                                        for ($i = 0; $i < strlen($str_dob); $i++) {
                                            array_push($dob_arr, $str_dob[$i]);
                                        }
                                        if (count($dob_arr) < 8) {
                                            for ($i = count($dob_arr); $i < 8; $i++) {
                                                array_push($dob_arr, '');
                                            }
                                        }
                                        foreach ($dob_arr as $dob):
                                            echo "<span>" . $dob . "</span>";
                                        endforeach; ?>
                                    </div>
                                </div>
                                <div class="d-flex mt-2 align-items-center">Date of Birth (Words) &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 660px"
                                        name="dob_words" id="dob_words" value="<?= $record['birthdate_words'] ?>" />
                                </div>

                                <div class="d-flex mt-2 align-items-center">Last School Attended &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 650px"
                                        name="last_school" id="last_school"
                                        value="<?= $record['spriden_last_school'] ?>" readonly />
                                </div>
                                <div class="d-flex mt-2" style="justify-content: left; align-items: center">
                                    Admission Date <div class="box-adm ml-3">
                                        <?php $doa_arr = [];
                                        $str_doa = $record['spriden_date_created'] == null || $record['spriden_date_created'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($record['spriden_date_created']));
                                        if ($str_doa != '') {
                                            for ($i = 0; $i < strlen($str_doa); $i++) {
                                                if ($str_doa[$i] != '-')
                                                    array_push($doa_arr, $str_doa[$i]);
                                            }
                                        } else {
                                            $doa_arr = ['0', '0', '0', '0', '0', '0', '0', '0'];
                                        }
                                        foreach ($doa_arr as $doa):
                                            echo "<span>" . $doa . "</span>";
                                        endforeach; ?>
                                    </div>
                                    <div class="ml-5 align-items-center">Std
                                        <input type="text" class="ml-1 text-center"
                                            style="border:none;border-bottom: 1px solid #56565650" name="std" id="std"
                                            value="<?= $record['spriden_admission_class'] ?>" readonly />
                                    </div>
                                </div>


                                <div class="d-flex mt-2 align-items-center">Progress &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 325px"
                                        name="progress" id="progress" value="Good" />
                                    <div class="d-flex ml-4 align-items-center">Conduct &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 330px" name="conduct" id="conduct" value="Good" />
                                    </div>
                                </div>
                                <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                    Date of Leaving
                                    <div class="box-dob">
                                        <?php $dob_arr = [];
                                        $str_dob = str_replace("-", '', date("d-m-Y", strtotime($record['leaving_date'])));

                                        for ($i = 0; $i < strlen($str_dob); $i++) {
                                            array_push($dob_arr, $str_dob[$i]);
                                        }
                                        if (count($dob_arr) < 8) {
                                            for ($i = count($dob_arr); $i < 8; $i++) {
                                                array_push($dob_arr, '');
                                            }
                                        }
                                        foreach ($dob_arr as $dob):
                                            echo "<span>" . $dob . "</span>";
                                        endforeach; ?>
                                    </div>
                                </div>

                                <div class="d-flex mt-2 align-items-center">Standard in which studied
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 250px"
                                        name="std_studied" id="std_studied"
                                        value="<?= $record['stvclas_standard'] ?>" />
                                    <div class="d-flex ml-4 align-items-center">Since when
                                        <input type="text" class="form-control form-control-sm ml-5"
                                            style="width: 200px" name="since" id="since"
                                            value="<?= $record['since_when'] ?>" />
                                    </div>
                                </div>
                                <div class="d-flex mt-2 align-items-center">School Leaving Reason
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 620px"
                                        name="mname" id="mname" value="<?= $record['leaving_reason'] ?>" />
                                    </textarea>
                                </div>
                                <div class="d-flex mt-2 align-items-center">Remark &nbsp;
                                    &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 780px"
                                        name="mname" id="mname" value="<?= $record['remark'] ?? '' ?>" />
                                </div>

                                <div class="text-center mt-3"> (Certified that the above information is in accordance
                                    with the School General Register) </div>

                                <div class="d-flex mt-2 align-items-center">Place &nbsp;
                                    <input type="text" name="place" id="place" value="Dhule"
                                        class="form-control form-control-sm text-center" style="width: 15%;" />
                                </div>

                                <div class="d-flex mt-2 align-items-center">
                                    <div class="d-flex">Date &nbsp;
                                        <input type="text" name="date" id="date"
                                            value="<?= date('Y-m-d', strtotime($record['lc_date'])) == null ? '' : date('d-m-Y') ?>"
                                            class="form-control form-control-sm " style="width: 150px;">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4 mx-5">
                                    <div class="fonts mt-3">Class Teacher</div>
                                    <div class="fonts mt-3">Clerk</div>
                                    <div class="fonts mt-3">Principal</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>
</body>

</html>