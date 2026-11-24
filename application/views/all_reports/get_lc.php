<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

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
                height: 120vh;
                background-color: #ffffff;
                margin: none;
                margin-top: -30px;

            }
        }

        .cont {
            margin-left: 300px;

        }

        .lc {
            /* width: 70px; */
            margin-left: 900px;
        }

        h2 {
            margin-left: 30px
        }

        .form-control,
        .form-control-sm {
            border: none;
            border-bottom: 1px solid #77777750;
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
                /* margin-top: 50%; */
                /* this affects the margin in the printer settings */
                border: 1px solid red;
                /* set a border for all printed pages */
                /* height:max-content;
                width: 100%; */
                /* margin: 40px 5px 35px;; */
                /* -webkit-transform: scale(0.85); */
                /* Saf3.1+, Chrome */
                /* -moz-transform: scale(0.85); */
                /* FF3.5+ */
                /* -ms-transform: scale(0.25); */
                /* IE9 */
                /* -o-transform: scale(0.85); */
                /* Opera 10.5+ */
                /* transform: scale(0.85); */
                /* margin: -50px -73px 0; */
                /* big hack to reposition the page top the top and left of the viewer control */

                /* width: auto; */
                /* height: auto; */
                /* position: absolute; */
                /* top: 0px; */
                /* bottom: 0px; */
                /* margin: 40px 5px 35px; */
                /* margin-top: 0px !important; */
                /* margin-bottom: 0px !important; */
                /* border: 1px solid; */
                /* page-break-after: always; */
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0px !important;
                bottom: 0px !important;
                margin: none !important;
                /* margin-top: 0px !important; */
                border: 1px solid;
                scale: 100;

            }

        }
    </style>
    <style>
        .container {
            font-weight: 700;
        }

        .form-control {
            font-weight: 700;
        }
    </style>
</head>



<body class="">

    <!-- [ Pre-loader ] start -->

    <div class="loader-bg">

        <div class="loader-track">

            <div class="loader-fill"></div>

        </div>

    </div>

    <!-- [ Pre-loader ] End -->

    <?php include 'application/views/topNav.php'; ?>

    <!--Include Sidebar -->

    <?php include 'application/views/sidebar.php'; ?>



    <!--Include Top Navbar-->





    <!-- [ Main Content ] start -->

    <div class="pc-container">

        <form action="<?= base_url('report/student_lc_status') ?>" method="post">

            <div class="pcoded-content">

                <!-- [ breadcrumb ] start -->
                <?php include 'application/views/Student/links.php'; ?>
                <!-- [ breadcrumb ] end -->

                <!-- [ Main Content ] start -->


                <div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">
                    <div class="col-md-12 no-print pl-4">
                        <a href="<?= base_url('Report/report_stats_lc') ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left no-print  "></i> Back</a>
                        <a href="" class="btn btn-primary btn-sm no-print " onclick="window.print()"> <i class="fa fa-print"></i> Print</a>
                        <button class="btn btn-sm no-print btn-info <?= $record['lc_num'] != 0 ? 'd-nones' : ''; ?>" type="button" id="issue-lc">
                            <i class="fas fa-file-alt"></i> Issue LC</button>
                        <input type="hidden" value="<?= $lc_no['lc_no']; ?>" id="hidden_lc_number">
                        <!-- Hidden input for student standard -->
                        <input type="hidden" value="<?= $student_std; ?>" id="hidden_std">

                        <?php
                        $stud_type = ($student_std == 'XI' || $student_std == 'XII') ? 'jc' : 'school';
                        ?>

                        <!-- Hidden input for student type -->
                        <input type="hidden" value="<?= $stud_type; ?>" id="hidden_stud_type">


                        <span class="ml-3 font-weight-bold text-danger"><?php echo $record['lc_num'] != 0 ? 'LC Issued on - ' . $record['date_issued_on'] : '' ?></span>
                    </div>

                </div>

                <div class="row" id="alertRow">

                    <div class="col-md-12">

                        <?php

                        $message = $this->session->userdata('message');

                        $alert_class = $this->session->userdata('alert_class');

                        if ($message != "") {

                        ?>

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
                        /* margin-left: 15px; */
                        /* width: 140px; */
                        /* margin-left: 30px; */
                        object-fit: cover;
                        /* margin-top: 40px; */
                    }

                    .border-top {
                        border-top: 1px solid #888888 !important;
                        width: 450px;
                    }

                    hr {
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
                                <div class="">
                                </div>

                                <input type="hidden" value="<?= $record['id'] ?>" id="spr_id" name="spr_id">

                                <div class="d-flex mb-1">

                                    <div><img src="<?= base_url('assets/images/jc_logo.jpg') ?>" class="image" /></div>
                                    <div>
                                        <h5 class="text-center">Uttar Maharashtra Educational Welfare Trust Dhule</h5>
                                        <h2 style="margin-left:20px">
                                            <span class=""></span>Olive English Medium School & Jr. College Dhule
                                        </h2>
                                        <h5 class="text-center">National Highway No.3, Near Traffic Police Station, Dhule.</h5>
                                    </div>
                                </div>
                                <hr>
                                <?php
                                $lc_number = $record['lc_num'] != 0 ? $record['lc_num'] : $lc_no['lc_no']
                                ?>
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
                                            <td class="text-center P-1">Recog No:- निर्णय क्रमांक एसएफएस-१०२१ / प्र.क्र ९७९ / एसएस-२/२०२३
                                            </td>
                                            <td class="p-1">Index No :-</td>
                                            <td class="p-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>

                                        <tr class="tr">
                                            <td class="p-0 border border-dark text-center">G.R No :- </td>
                                            <td class="text-center p-0 border border-dark"><?= $record['spriden_gr_number'] ?></td>
                                            <td class="text-center border-top p-1">
                                                मा. शिक्षण उपसंचालक नाशिक विभाग नाशिक यांचे कडील <br> पत्र आदेश क्र. शि उ सं / नावी / माध्य -२/ दर्जावाढ प्रस्ताव /२०२३/९३०१/०२
                                            </td>
                                            <td class="p-0 border border-dark text-center">L.C No :-</td>
                                            <td class="text-center p-0 border border-dark"><?= $lc_number ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="mt-2">
                                    <table>
                                        <tr>
                                            <td class="border border-dark" style="width: 160px; text-align: center; color: #ff768e">
                                                UDISE NO.
                                            </td>
                                            <td class="border border-dark" style="width: 160px; text-align: center; color: #ff768e">
                                                27020500322
                                            </td>
                                            <td class="border border-dark" style="width: 160px; text-align: center; color: #ff768e">
                                                BOARD
                                            </td>
                                            <td class="border border-dark" style="width: 160px; text-align: center; color: #ff768e">
                                                Nashik
                                            </td>
                                            <td class="border border-dark" style="width: 160px; text-align: center; color: #ff768e">
                                                MEDIUM
                                            </td>
                                            <td class="border border-dark" style="width: 160px; text-align: center; color: #ff768e">
                                                English
                                            </td>
                                        </tr>
                                    </table>

                                    <style>
                                        textarea {
                                            border: none;
                                            border-bottom: 1px solid #77777750;
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
                                            border: 1px solid #77777750;
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
                                            border: 1px solid #77777750;
                                            border-collapse: collapse;
                                            padding: 5px;
                                            text-align: center;
                                        }

                                        .box-col span {
                                            border: 1px solid #77777750;
                                            border-collapse: collapse;
                                            padding: 5px;
                                            text-align: center;
                                        }

                                        .box-aadhar span {
                                            border: 1px solid #77777750;
                                            border-collapse: collapse;
                                            padding: 2px;
                                            text-align: center;
                                            height: 32px;

                                        }
                                    </style>
                                    <div style="background-color: #ffdee3;color: #ff768e;text-align: center;padding: 3px;" class="mt-3">
                                        <h3 style="margin-bottom: 0.3rem;color:#ff768e;font-weight:bold;    margin-top: 0.3rem;">LEAVING CERTIFICATE</h3>
                                    </div>

                                    <div class="mt-3 d-flex" style="justify-content: space-between; align-items: center">
                                        Student ID
                                        <div class="box-col">
                                            <?php
                                            $reg_arr = [];
                                            $str_reg = str_replace("ID:", '', $record['SPRIDEN_reg_number']);
                                            for ($i = 0; $i < strlen($str_reg); $i++) {
                                                array_push($reg_arr, $str_reg[$i]);
                                            }
                                            if (count($reg_arr) < 20) {
                                                for ($i = count($reg_arr); $i < 20; $i++) {
                                                    array_push($reg_arr, '');
                                                }
                                            }
                                            foreach ($reg_arr as $reg) :
                                                echo "<span>" . $reg . "</span>";
                                            endforeach;
                                            ?>
                                        </div>
                                    </div>

                                    <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                        Aadhar Card No
                                        <div class="box-aadhar">
                                            <?php
                                            $adh_arr = [];
                                            for ($i = 0; $i < strlen($record['SPRIDEN_aadhar_no']); $i++) {
                                                array_push($adh_arr, $record['SPRIDEN_aadhar_no'][$i]);
                                            }
                                            if (count($adh_arr) < 12) {
                                                for ($i = count($adh_arr); $i < 12; $i++) {
                                                    array_push($adh_arr, '');
                                                }
                                            }
                                            foreach ($adh_arr as $adh) :
                                                echo "<span>" . $adh . "</span>";
                                            endforeach;
                                            ?>
                                        </div>
                                    </div>

                                    <style>
                                        .form-control,
                                        .form-control-sm {
                                            border: none;
                                            border-bottom: 1px solid #77777750;
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

                                    <div class="mt-3 d-flex ">
                                        Pupil's Full Name &nbsp;
                                        <input type="" class="form-control form-control-sm ml-5 " style="width: 717px" name="full_name" id="full_name" value="<?= $record['SPRIDEN_student_name'] . ' ' . $record['SPRIDEN_father_name'] . ' ' . $record['SPRIDEN_surname'] ?>" readonly />
                                    </div>
                                    <div class="d-flex mt-2">
                                        Father's Name
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 300px" name="full_name" id="full_name" value="<?= $record['SPRIDEN_father_name'] ?>" readonly />
                                        <div class="d-flex ml-4">
                                            Surname &nbsp;
                                            <input type="text" class="form-control form-control-sm ml-5" style="width: 250px" name="surname" id="surname" value="<?= $record['SPRIDEN_surname'] ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="d-flex mt-2">
                                        Mother's Name &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 720px" name="mname" id="mname" value="<?= $record['SPRIDEN_mother_name'] ?>" readonly />
                                    </div>
                                    <div>
                                        <div class="d-flex mt-2">
                                            Nationality &nbsp;
                                            <input type="text" class="form-control form-control-sm ml-5" style="width: 300px" name="indian" id="indian" value="INDIAN" readonly />

                                            <div class="d-flex ml-5">
                                                Mother Tongue &nbsp;
                                                <input type="text" class="form-control form-control-sm  ml-2" style="width: 250px" name="mtongue" id="mtongue" value="<?= $record['SPRIDEN_mother_toungue'] ?>" readonly />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex mt-3">
                                        Religion &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 150px" name="mtongue" id="mtongue" value="Islam" readonly />
                                        <div class="d-flex ml-4">
                                            Caste &nbsp;
                                            <input type="text" class="form-control form-control-sm ml-5" style="width: 150px" name="caste" id="caste" value="<?= $record['cast'] ?>" readonly />
                                        </div>
                                        <div class="d-flex ml-4">
                                            Sub-Caste &nbsp;
                                            <input type="text" class="form-control form-control-sm ml-5" style="width: 150px" name="scaste" id="scaste" value="<?= $record['sub_cast'] ?>" readonly />
                                        </div>
                                    </div>

                                    <div class="d-flex mt-3">
                                        Place of Birth &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 150px" name="mtongue" id="mtongue" value="<?= $record['SPRIDEN_birth_place'] ?>" readonly />
                                        <div class="d-flex ml-4">
                                            Taluka &nbsp;
                                            <input type="text" class="form-control form-control-sm ml-5" style="width: 150px" name="caste" id="caste" value="<?= $record['taluka'] ?>" readonly />
                                        </div>
                                        <div class="d-flex ml-4">
                                            District &nbsp;
                                            <input type="text" class="form-control form-control-sm ml-5" style="width: 150px" name="scaste" id="scaste" value="<?= $record['district'] ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="d-flex mt-3">
                                        State &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 325px" name="indian" id="state" value="<?= $record['state'] ?>" readonly />
                                        <div class="d-flex ml-4">
                                            Country &nbsp;
                                            <input type="text" class="form-control form-control-sm ml-5" style="width: 330px" name="country" id="country" value="INDIA" readonly />
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                        Date of Birth (Figures)
                                        <div class="box-dob">
                                            <?php
                                            $dob_arr = [];
                                            $str_dob = str_replace("-", '', date("d-m-Y", strtotime($record['spriden_birthdate'])));

                                            for ($i = 0; $i < strlen($str_dob); $i++) {
                                                array_push($dob_arr, $str_dob[$i]);
                                            }
                                            if (count($dob_arr) < 8) {
                                                for ($i = count($dob_arr); $i < 8; $i++) {
                                                    array_push($dob_arr, '');
                                                }
                                            }
                                            foreach ($dob_arr as $dob) :
                                                echo "<span>" . $dob . "</span>";
                                            endforeach;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-3">
                                        Date of Birth (Words) &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 660px" name="dob_words" id="dob_words" value="<?= $record['birthdate_words'] ?>" />
                                    </div>

                                    <div class="d-flex mt-2">
                                        Last School Attended &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 650px" name="last_school" id="last_school" value="<?= $record['spriden_last_school'] ?>" readonly />
                                    </div>
                                    <div class="d-flex mt-2" style="justify-content: left; align-items: center">
                                        Admission Date
                                        <div class="box-adm ml-3">
                                            <?php
                                            $doa_arr = [];
                                            $str_doa = $record['SPRIDEN_date_created'] == null || $record['SPRIDEN_date_created'] == '0000-00-00' ? '' : date('d-m-Y', strtotime($record['SPRIDEN_date_created']));
                                            if ($str_doa != '') {
                                                for ($i = 0; $i < strlen($str_doa); $i++) {
                                                    if ($str_doa[$i] != '-')
                                                        array_push($doa_arr, $str_doa[$i]);
                                                }
                                            } else {
                                                $doa_arr = ['0', '0', '0', '0', '0', '0', '0', '0'];
                                            }
                                            foreach ($doa_arr as $doa) :
                                                echo "<span>" . $doa . "</span>";
                                            endforeach;
                                            ?>
                                        </div>
                                        <div class="ml-5">
                                            Std
                                            <input type="text" class="ml-1 text-center" style="border:none;border-bottom: 1px solid #77777750" name="std" id="std" value="<?= $record['spriden_admission_class'] ?>" readonly />
                                        </div>
                                    </div>


                                </div>
                                <div class="d-flex mt-3">
                                    Progress &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 325px" name="progress" id="progress" value="<?= $record['progress'] ?>" />
                                    <div class="d-flex ml-4">
                                        Conduct &nbsp;
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 330px" name="conduct" id="conduct" value="<?= $record['conduct'] ?>" />
                                    </div>
                                </div>
                                <div class="mt-2 d-flex" style="justify-content: space-between; align-items: center">
                                    Date of Leaving
                                    <div class="box-dob">
                                        <?php
                                        $dob_arr = [];
                                        $str_dob = str_replace("-", '', date("d-m-Y", strtotime($record['leaving_date'])));

                                        for ($i = 0; $i < strlen($str_dob); $i++) {
                                            array_push($dob_arr, $str_dob[$i]);
                                        }
                                        if (count($dob_arr) < 8) {
                                            for ($i = count($dob_arr); $i < 8; $i++) {
                                                array_push($dob_arr, '');
                                            }
                                        }
                                        foreach ($dob_arr as $dob) :
                                            echo "<span>" . $dob . "</span>";
                                        endforeach;
                                        ?>
                                    </div>
                                </div>

                                <div class="d-flex mt-3">
                                    Standard in which studied
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 250px" name="std_studied" id="std_studied" value="<?= $record['stvclas_standard'] ?>" />
                                    <div class="d-flex ml-4">
                                        Since when
                                        <input type="text" class="form-control form-control-sm ml-5" style="width: 200px" name="since" id="since" value="<?= $record['since_when'] ?>" />
                                    </div>
                                </div>
                                <div class="d-flex mt-3">
                                    School Leaving Reason
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 620px" name="mname" id="mname" value="<?= $record['leaving_reason'] ?>" />
                                    <!-- <textarea cols="75" rows="1" class="ml-5" style="border-color: #77777750"> -->
                                    </textarea>
                                </div>
                                <div class="d-flex mt-3">
                                    Remark &nbsp;
                                    &nbsp;
                                    <input type="text" class="form-control form-control-sm ml-5" style="width: 780px" name="mname" id="mname" value="<?= $record['remark'] ?>" />
                                </div>

                                <div class="text-center mt-4">
                                    (Certified that the above information is in accordance with the
                                    School General Register)
                                </div>

                                <div class="d-flex mt-3">
                                    Place &nbsp;
                                    <input type="text" name="place" id="place" value="Dhule" class="form-control form-control-sm text-center" style="width: 15%;" />
                                    <!-- <div class="d-flex ml-4">
											Date &nbsp;
											<input type="date" class="form-control form-control-sm ml-5" style="width: 330px" name="date" id="date" value="" />
										</div> -->
                                </div>

                                <div class="d-flex mt-3">
                                    <div class="d-flex">
                                        Date &nbsp;
                                        <input type="text" name="date" id="date" value="<?= date('Y-m-d', strtotime($record['lc_date'])) == null ? '' : date('d-m-Y') ?>" class="form-control form-control-sm " style="width: 150px;">
                                    </div>

                                    <!-- <span class="ml-5"></span>
										<span style="margin-left: 280px; "></span>
										<span style="margin-left: 280px;"></span> -->
                                </div>
                                <div class="d-flex mt-5">
                                    <div style="margin-left:250px">Class Teacher</div>
                                    <div style="margin-left:180px">Clerk</div>
                                    <div style="margin-left:180px">Principal</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

        </form>

        <!-- [ Main Content ] end -->

    </div>







    </div>

    <!-- [ Main Content ] end -->

    </div>






    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>




    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>

    <!-- <script>

		$('#findStudent').click(function() {

			var id = $('#search_student_name').val().trim();

			if (id != '') {

				$.ajax({

					url: "<?= base_url('Student/StudentUniqueId') ?>?id=" + id,

					method: "GET",

					dataType: "JSON",

					success: function(data) {

						if (data != null) {

							window.location = '<?= base_url('student/update_details/') ?>' + data.id;

						} else {

							alert("Data Not Available");

						}

					}

				});

			}



		});

	</script> -->
    <!-- <script>
		function printDiv() {
			var divContents = document.getElementById("main_div").innerHTML;
			var a = window.open('', '', 'height=500, width=500');
			a.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"><html>');
			a.document.write('<body >');
			a.document.write(divContents);
			a.document.write('</body></html>');
			a.document.close();
			a.print();
		}
	</script> -->

    <script>
        $('#issue-lc').click(function() {
            if (confirm("do you want to issue LC?")) {
                var lc_num = $('#hidden_lc_number').val();
                var stu_id = $('#spr_id').val();
                var date = $('#date').val();
                var std = $('#hidden_std').val();
                var typ = $('#hidden_stud_type').val();


                $.ajax({
                    url: '<?= base_url() ?>index.php/Report/save_lc_details', // where to go
                    method: "POST", // by which way data should go
                    data: { // what data to send
                        lc_number: lc_num,
                        student_id: stu_id,
                        date_issued: date,
                        standard: std,
                        stud_type: typ
                    },
                    beforeSend: function() { // what to do before sending request
                        $(this).html(`<i class="fas fa-spinner fa-spin"></i> Please Wait`);
                    },
                    success: function(res) { // what to do after receiving response
                        if (res == 'fail') {
                            alert("Fail to save data");
                        } else {
                            alert("LC Details Saved");
                        }
                    }
                });
            }
        });
    </script>

</body>


</html>