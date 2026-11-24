<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Admission Form - Olive English School</title>

    <?php include 'application/views/cssPages.php'; ?>

    <link rel="icon" href="<?php echo base_url(); ?>images/fav.png" type="image/x-icon">

    <!-- Boxicons CSS -->

    <link href='https://unpkg.com/boxicons@2.0.8/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .card {
            box-sizing: border-box;
        }

        .card.form {
            border: 1px solid #77777750;
        }

        .table tr td {
            padding-top: 6px !important;
            padding-bottom: 6px !important;
        }

        .form-control.cust-border-bottom {
            border: none !important;
            border-bottom: 1px solid #999 !important;
            border-radius: 0px !important;
            padding-left: 0px !important;
            font-weight: 500;
        }

        .form-control.with-border {
            height: calc(1em + 1.15rem + 1px);
            padding: 0.55rem 1rem;
            text-align: center;
            font-weight: 500;
            border-color: #999;
        }

        .form-control.with-border:focus {
            border-color: #777 !important;
        }

        @media print {
            .not-visible {
                visibility: hidden;
            }
        }
    </style>

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
        }

        .card {
            box-sizing: border-box;
            border-radius: 10px;
            overflow: hidden;
        }

        .card.form {
            border: 1px solid #dcdcdc;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        .table {
            margin-bottom: 0.7rem;
        }

        .table tr td {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
            vertical-align: middle !important;
        }

        h3 {
            font-weight: 700;
            color: #1d3557;
            margin-bottom: 0px;
            letter-spacing: 0.3px;
        }

        hr {
            border-top: 1px solid #dcdcdc;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .font-weight-bold {
            font-weight: 600 !important;
            color: #333;
            font-size: 14px;
        }

        .form-control {
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            box-shadow: none !important;
        }

        .form-control.cust-border-bottom {
            border: none !important;
            border-bottom: 1px solid #999 !important;
            border-radius: 0px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
            font-weight: 500;
            background: transparent;
            font-size: 14px;
        }

        .form-control.cust-border-bottom:focus {
            border-bottom: 1px solid #1d3557 !important;
        }

        .form-control.with-border {
            height: calc(1em + 1.3rem + 2px);
            padding: 0.6rem 1rem;
            text-align: center;
            font-weight: 500;
            border: 1px solid #bdbdbd;
            border-radius: 6px;
            background: #fcfcfc;
            font-size: 14px;
        }

        .form-control.with-border:focus {
            border-color: #1d3557 !important;
            background: #fff;
        }

        textarea.form-control {
            resize: none;
        }

        .btn {
            border-radius: 6px;
            padding: 6px 14px;
            font-weight: 500;
        }

        .btn i {
            vertical-align: middle;
        }

        .head td {
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        .stu-det td {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        @media print {

            body {
                background: #fff !important;
            }

            .not-visible {
                display: none !important;
            }

            .card.form {
                box-shadow: none !important;
                border: 1px solid #999;
            }

            .form-control {
                background: transparent !important;
            }
        }
    </style>

</head>



<body style="background-color: #fff;">



    <form action="<?= base_url('Student/add_student_new_data') ?>" method="POST">

        <div class="container mt-2 not-visible">

            <div class="row">

                <div class="col-md-12 text-right">

                    <button class="btn btn-sm btn-secondary" type="button" onclick="window.print();"> <i
                            class='bx bxs-printer'></i> Print</button>

                    <a href="<?= base_url('admission-form'); ?>" class="btn btn-sm btn-primary"> <i
                            class='bx bxs-edit'></i> Form</a>



                </div>

            </div>

        </div>

        <div class="container mt-2">

            <div class="row">

                <div class="col-md-12">

                    <div class="card form">

                        <div class="card-body pt-1">

                            <table class="table" style="border: none;">

                                <tr>

                                    <td style="border: none;">

                                        <img src="<?= base_url('images/logoNewDev.png') ?>" class="img-fluid"
                                            style="height: 50px;" alt="">

                                    </td>

                                    <td style="border: none; vertical-align: middle;" class="text-center">

                                        <h3>Olive English School - Admission Form
                                            <?= isset($academic['stvacdm_year']) ? $academic['stvacdm_year'] : '' ?>

                                        </h3>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none;" colspan="2">

                                        <hr>

                                    </td>

                                </tr>

                            </table>

                            <table class="table head">

                                <tr>

                                    <td style="border: none; width: 25%;">

                                        <span class="font-weight-bold">Student ID (For Office Use Only) : </span>

                                    </td>

                                    <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
                                        class="text-center">

                                        <input type="text" name="office_id" id="office_id"
                                            class="form-control form-control-sm cust-border-bottom"
                                            value="<?= $student_details['SPRIDEN_student_id']; ?>">

                                        <input type="hidden" name="student_id" id="student_id"
                                            class="form-control form-control-sm cust-border-bottom">

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none; width: 25%;">

                                        <span class="font-weight-bold">Student Aadhar Number : </span>

                                    </td>

                                    <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
                                        class="text-center">

                                        <?php

                                        // Database se aadhar number
                                        $aadhar = preg_replace('/\D/', '', $student_details['SPRIDEN_aadhar_no']);

                                        // Format: 1234 5678 9012
                                        $aadhar = trim(chunk_split($aadhar, 4, ' '));

                                        ?>

                                        <input name="aadhar_number" id="aadhar_number" type="text" maxlength="14"
                                            autocomplete="off" class="form-control form-control-sm cust-border-bottom"
                                            value="<?= $aadhar; ?>">

                                    </td>

                                </tr>

                            </table>

                            <table class="table stu-det">

                                <!--First Row-->

                                <tr>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Student Name</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Father Name</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Surname</span>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="student_name" id="student_name" type="text"
                                            class="form-control with-border"
                                            value="<?= $student_details['SPRIDEN_student_name']; ?>">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="father_name" id="father_name" type="text"
                                            class="form-control with-border"
                                            value="<?= $student_details['SPRIDEN_father_name']; ?>">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="surname" id="surname" type="text"
                                            class="form-control with-border"
                                            value="<?= $student_details['SPRIDEN_surname']; ?>">

                                    </td>

                                </tr>

                                <!--Second Row-->

                                <tr>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Mother Name</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Date of Birth</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Admission Class</span>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="mother_name" id="mother_name" type="text"
                                            class="form-control with-border"
                                            value="<?= $student_details['SPRIDEN_mother_name']; ?>">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="date_of_birth" id="date_of_birth" type="text"
                                            class="form-control with-border"
                                            value="<?= date('d-M-Y', strtotime($student_details['SPRIDEN_birthdate'])); ?>">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="admission_class" id="admission_class"
                                            type="text" class="form-control with-border"
                                            value="<?= $student_details['SPRIDEN_admission_class']; ?>">

                                    </td>

                                </tr>

                                <!--Third Row-->

                                <tr>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Nationality</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Religion</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Mother Tongue</span>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off"
                                            value="<?= $student_details['SPRIDEN_nationality']; ?>" name="nationality"
                                            id="nationality" type="text" class="form-control with-border">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" value="<?= $student_details['SPRIDEN_religion']; ?>"
                                            name="religion" id="religion" type="text" class="form-control with-border">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off"
                                            value="<?= $student_details['SPRIDEN_mother_toungue']; ?>"
                                            name="mother_tongue" id="mother_tongue" type="text"
                                            class="form-control with-border">

                                    </td>

                                </tr>

                                <!--Fourth Row-->

                                <tr>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Caste</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Sub Caste</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Place of Birth</span>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" value="<?= $student_details['cast']; ?>" name="caste"
                                            id="caste" type="text" class="form-control with-border">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" value="<?= $student_details['sub_cast']; ?>"
                                            name="sub_caste" id="sub_caste" type="text"
                                            class="form-control with-border">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off"
                                            value="<?= $student_details['SPRIDEN_birth_place']; ?>" name="birth_place"
                                            id="birth_place" type="text" class="form-control with-border">

                                    </td>

                                </tr>

                                <!--Fifth Row-->

                                <tr>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Taluka</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">District</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">State</span>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" value="<?= $student_details['taluka']; ?>"
                                            name="taluka" id="taluka" type="text" class="form-control with-border">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="district"
                                            value="<?= $student_details['district']; ?>" id="district" type="text"
                                            class="form-control with-border">

                                    </td>

                                    <td style="border: none;  padding:0px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" value="<?= $student_details['state']; ?>" name="state"
                                            id="state" type="text" class="form-control with-border">

                                    </td>

                                </tr>

                            </table>

                            <table class="table head" style="margin-top: 0px;">

                                <tr>

                                    <td style="border: none; width: 25%;">

                                        <span class="font-weight-bold">Previous School (For Office Use Only) : </span>

                                    </td>

                                    <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
                                        class="text-center">

                                        <input type="text" value="<?= $student_details['SPRIDEN_last_school']; ?>"
                                            name="last_school" id="last_school"
                                            class="form-control form-control-sm cust-border-bottom">

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none; width: 25%;">

                                        <span class="font-weight-bold">Parent's Mobile Number : </span>

                                    </td>

                                    <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
                                        class="text-center">

                                        <input type="text" value="<?= $contact['stvpcnf_mobile'] ?>"
                                            name="parents_mobile" id="parents_mobile"
                                            class="form-control form-control-sm cust-border-bottom">

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none; width: 25%;">

                                        <span class="font-weight-bold">Permanent Residence Address : </span>

                                    </td>

                                    <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
                                        class="text-center">

                                        <textarea name="main_address" id="main_address"
                                            class="form-control form-control-sm cust-border-bottom"
                                            rows="2"> <?= $student_details['SPRIDEN_main_address']; ?> </textarea>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none; width: 25%;">

                                        <span class="font-weight-bold">Date of Admission : </span>

                                    </td>

                                    <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
                                        class="text-center">

                                        <input type="text"
                                            value="<?= date('d-M-Y', strtotime($student_details['SPRIDEN_date_created'])); ?>"
                                            name="parents_mobile" id="parents_mobile"
                                            class="form-control form-control-sm cust-border-bottom">

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none; width: 25%;">

                                        <span class="font-weight-bold">Admission Place : </span>

                                    </td>

                                    <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
                                        class="text-center">

                                        <input type="text" value="Dhule" name="parents_mobile" id="parents_mobile"
                                            class="form-control form-control-sm cust-border-bottom">

                                    </td>

                                </tr>

                            </table>

                            <table class="table stu-det" style="margin-top: 100px;">

                                <tr>

                                    <td style="border: none;  padding:5px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="clerk" id="clerk" type="text"
                                            class="form-control with-border cust-border-bottom">

                                    </td>

                                    <td style="border: none;  padding:5px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="parent_sign" id="parent_sign" type="text"
                                            class="form-control with-border cust-border-bottom">

                                    </td>

                                    <td style="border: none;  padding:5px !important; margin:5px !important;"
                                        class="text-center">

                                        <input autocomplete="off" name="princi" id="princi" type="text"
                                            class="form-control with-border cust-border-bottom">

                                    </td>

                                </tr>

                                <tr>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold ">Clerk Signature</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Parent Signature</span>

                                    </td>

                                    <td style="border: none;  text-align:center;">

                                        <span class="font-weight-bold">Principal Signature</span>

                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>



    <?php include 'application/views/jsPages.php'; ?>

    <script>

        window.onload = function () {

            const aadharInput = document.getElementById('aadhar_number');

            aadharInput.addEventListener('keyup', function () {

                let value = this.value.replace(/\D/g, '');

                value = value.substring(0, 12);

                let formatted = '';

                for (let i = 0; i < value.length; i++) {

                    if (i > 0 && i % 4 === 0) {
                        formatted += ' ';
                    }

                    formatted += value[i];
                }

                this.value = formatted;
            });

        };

    </script>

</body>



</html>