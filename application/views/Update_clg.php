<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="11th 12th - Olive English Junior College - Admission Application Form" />
    <meta property="og:description"
        content="11th and 12th class admission form of Olive Englisj Junior College, Dhule" />
    <meta property="og:image" content="https://school.oliveschool.org.in/img/logo.jpg" />
    <meta property="og:url" content="https://school.oliveschool.org.in/forms/jr_college" />
    <meta property="og:type" content="website" />
    <title>11th 12th - Olive English School Application Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


    <style>
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        h3 {
            font-size: 1.75rem;
            background-color: #f2f2f2;
            padding: 10px;
            line-height: 30px;
            color: #53769c;
            font-weight: 700;
        }

        .card {
            box-shadow: 0 0 5px #77777780 !important;
            border: none !important;
            border-radius: 0px !important;
        }

        .card-body {
            border: none !important;
            border-radius: 0px !important;
        }

        .school-logo {
            height: 120px !important;
        }

        .admission-divider {
            background-color: #53769c !important;
            color: #fff;
            text-align: center;
            padding: 6px 0;
            border-radius: 50px;
        }

        .readonly-select {
            pointer-events: none;
            /* Prevent interaction */
            background-color: #e9ecef;
            /* Change background to indicate it's readonly */
            -webkit-appearance: none;
            /* Hide default dropdown arrow in Safari and Chrome */
            -moz-appearance: none;
            /* Hide default dropdown arrow in Firefox */
            appearance: none;
            /* Hide default dropdown arrow in other browsers */
            position: relative;
            /* For custom arrow positioning */
        }

        .readonly-select::after {
            content: '';
            /* Remove custom arrow */
        }
        
        .back-button-container {
    text-align: right;
}

.back-button {
    display: inline-block;
    align-items: center;
    padding: 5px 10px;
    font-size: 13px;
    color: white;
    background-color: #007bff;
    text-decoration: none;
    border-radius: 10px;
    margin-top: 1px;
    /* float: right; */
}

.back-button:hover {
    background-color: #0056b3;
}
        /* ======================= */
    </style>

</head>

<body class="bg-light">
    <div class="container">
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- School Details  -->
                         <!-- ==============changed 23/===================== -->
                         <div class="col-md-2" >
                         <a href="<?=base_url('Online_Application/junior_clg')?>" class="back-button">Back</a>
                         </div>
                         <!-- ======================================== -->
                        <div class="row bg-ban">
                            <div
                                class="col-md-3 text-right d-flex flex-column justify-content-center align-items-center">
                                <img src="<?php echo base_url('img/logo.jpg'); ?>" class="img-fluid school-logo" alt="">
                            </div>

                            <div
                                class="col-md-9 text-center d-flex flex-column justify-content-center align-items-center">
                                <h6 class="font-weight-normal">Uttar Maharashtra Educational Welfare Trust Dhule</h6>
                                <h2>Olive English School & Jr. College, Dhule</h2>
                                <h4 class="font-weight-normal">National Highway No.3, Near Traffic Police Station, Dhule
                                </h4>
                          
                        </div>
                        <form action="" id="myForm" method="post">
                            <hr class="m-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="admission-divider">
                                        <h4 class="m-0">Admission Form</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- =====================date 21 readonly=============== -->

                            <?php
                            $hideFileInput = false;
                            $disabled = "";
                            $readonlyClass = "";
                            if (isset($_GET['action']) && $_GET['action'] == 'view') {
                                $readonlyClass = "readonly-select";
                                $disabled = "disabled";
                                $hideFileInput = true;
                            }
                            ?>
                            <!-- ====================================================== -->
                            <!-- ================================================================================ -->
                            <?php $db_branch = isset($details['db_branch']) == null ? '' : $details['db_branch'] ?>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label>Branch <span style="color:red;">*</span></label>
                                    <select name="db_branch" class="form-control <?= $readonlyClass; ?> ">
                                        <option value="Olive junior College" <?php $db_branch == 'Olive junior College' ? 'selected' : '' ?>>Olive Junior College</option>
                                    </select>
                                </div>
                                <?php $db_stream = isset($details['db_stream']) == null ? '' : $details['db_stream'] ?>
                                <div class="col-md-4">
                                    <label>Stream<span style="color:red;">*</span></label>
                                    <select name="db_stream" id="id_stream" class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="Science" <?php if ($db_stream == 'Science')
                                            echo 'selected'; ?>>Science</option>
                                        <option value="Commerce" <?php if ($db_stream == 'Commerce')
                                            echo 'selected'; ?>>Commerce</option>
                                    </select>
                                </div>
                                <?php $db_admissiontype = isset($details['db_admissiontype']) == null ? '' : $details['db_admissiontype'] ?>
                                <div class="col-md-4">

                                    <label>Admission Type <span style="color:red;">*</span></label>
                                    <select name="db_admissiontype" id="db_admissiontype"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="New Admission" <?php if ($db_admissiontype == 'New Admission')
                                            echo 'selected'; ?>>New Admission</option>
                                        <option value="Re-Admission" <?php if ($db_admissiontype == 'Re-Admission')
                                            echo 'selected'; ?>>
                                            Re-Admission</option>
                                    </select>
                                </div>
                                <?php $db_class = isset($details['db_class']) == null ? '' : $details['db_class'] ?>
                                <div class="col-md-4">
                                    <label>Class <span style="color:red;">*</span></label>
                                    <select name="db_class" id="db_class" class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="11th" <?php if ($db_class == '11th')
                                            echo 'selected'; ?>>11th</option>
                                        <option value="12th" <?php if ($db_class == '12th')
                                            echo 'selected'; ?>>12th</option>
                                    </select>
                                </div>
                                <?php $db_division = isset($details['db_division']) == null ? '' : $details['db_division'] ?>
                                <div class="col-md-4">
                                    <label>Division <span style="color:red;">*</span></label>
                                    <select name="db_division" id="db_division"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="A" <?php if ($db_division == 'A')
                                            echo 'selected'; ?>>A
                                        </option>
                                        <option value="B" <?php if ($db_division == 'B')
                                            echo 'selected'; ?>>B
                                        </option>
                                        <option value="C" <?php if ($db_division == 'C')
                                            echo 'selected'; ?>>C
                                        </option>
                                    </select>
                                    <?php $Id = isset($details['Id']) == null ? '' : $details['db_division'] ?>
                                    <input type="hidden" id="id" name="id"
                                        value="<?= $Id == null ? 0 : $details['Id'] ?>">
                                </div>
                                <?php $db_division = isset($details['db_division']) == null ? '' : $details['db_division'] ?>
                                <div class="col-md-4">
                                    <label>Medium <span style="color:red;">*</span></label>
                                    <select required name="db_medium" id="db_medium"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="English" <?php if ($db_division == 'English')
                                            echo 'selected'; ?>>English</option>
                                    </select>
                                </div>

                            </div>


                            <!--========================================================================== -->
                            <h3 class="tile-title" style="font-size: 18px;">Student's Information :</h3>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Firstname <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="firstname" name="firstname"
                                        value="<?= isset($details['firstname']) ? $details['firstname'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?> ">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Middlename<span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="Middle-name" name="Middlename"
                                        value="<?= isset($details['Middlename']) ? $details['Middlename'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?> ">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Lastname <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="lastname" name="lastname"
                                        value="<?= isset($details['lastname']) ? $details['lastname'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?> ">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Date of birth <span
                                            style="color:red;">*</span></label>
                                    <input type="date" id="date" name="date"
                                        value="<?= isset($details['date']) ? $details['date'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?> ">
                                </div>
                            </div>


                            <div class="form-group row">


                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Nationality <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_nationality" id="db_nationality"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="Indian">Indian</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Place of birth <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_place" name="db_place"
                                        value="<?= isset($details['db_place']) ? $details['db_place'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Gender <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_gender" id="db_gender"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother Tongue <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_mothertounge" id="db_mothertounge"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="Marathi" <?php if ($details['db_mothertounge'] == 'Marathi')
                                            echo 'selected'; ?>>Marathi</option>
                                        <option value="Hindi" <?php if ($details['db_mothertounge'] == 'Hindi')
                                            echo 'selected'; ?>>Hindi</option>
                                        <option value="English" <?php if ($details['db_mothertounge'] == 'English')
                                            echo 'selected'; ?>>English</option>
                                        <option value="Gujarati" <?php if ($details['db_mothertounge'] == 'Gujarati')
                                            echo 'selected'; ?>>Gujarati</option>
                                        <option value="Urdu" <?php if ($details['db_mothertounge'] == 'Urdu')
                                            echo 'selected'; ?>>Urdu</option>
                                        <option value="Punjabi" <?php if ($details['db_mothertounge'] == 'Punjabi')
                                            echo 'selected'; ?>>Punjabi</option>
                                    </select>
                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Religion <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_religion" id="db_religion"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="Hindu" <?php if ($details['db_religion'] == 'Hindu')
                                            echo 'selected'; ?>>Hindu</option>
                                        <option value="Islam" <?php if ($details['db_religion'] == 'Islam')
                                            echo 'selected'; ?>>Islam</option>
                                        <option value="Christian" <?php if ($details['db_religion'] == 'Christian')
                                            echo 'selected'; ?>>Christian</option>
                                        <option value="Sikh" <?php if ($details['db_religion'] == 'Sikh')
                                            echo 'selected'; ?>>Sikh</option>
                                        <option value="Other" <?php if ($details['db_religion'] == 'Other')
                                            echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Caste <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required name="db_caste" id="db_caste"
                                        class="form-control <?= $readonlyClass; ?>"
                                        value="<?= isset($details['db_caste']) ? $details['db_caste'] : ''; ?>"
                                        placeholder="Enter caste">
                                </div>


                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Student Aadhar No.</label>
                                    <input type="number" id="db_adharnumber" name="db_adharnumber"
                                        value="<?= isset($details['db_adharnumber']) ? $details['db_adharnumber'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>

                            </div>
                            <!--  ============================================================== -->
                            <h3 class="tile-title" style="font-size: 18px;">Parent's Information :</h3>

                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's first Name <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fatherfirstname" name="db_fatherfirstname"
                                        value="<?= isset($details['db_fatherfirstname']) ? $details['db_fatherfirstname'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's middle Name <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fathermiddlename" name="db_fathermiddlename"
                                        value="<?= isset($details['db_fathermiddlename']) ? $details['db_fathermiddlename'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's last Name <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fatherlastname" name="db_fatherlastname"
                                        value="<?= isset($details['db_fatherlastname']) ? $details['db_fatherlastname'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Aadhar No. <span
                                            style="color:red;">*</span></label>
                                    <input type="number"
                                        value="<?= isset($details['db_fatheradharnumber']) ? $details['db_fatheradharnumber'] : ''; ?>"
                                        required id="db_fatheradharnumber" name="db_fatheradharnumber"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's Qualification <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fatherqualification"
                                        name="db_fatherqualification"
                                        value="<?= isset($details['db_fatherqualification']) ? $details['db_fatherqualification'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>


                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's Occupation <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_fatheroccupation" id="db_fatheroccupation"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="Government" <?php if ($details['db_fatheroccupation'] == 'Government')
                                            echo 'selected'; ?>>
                                            Government Employee</option>
                                        <option value="Semi Government" <?php if ($details['db_fatheroccupation'] == 'Semi Government')
                                            echo 'selected'; ?>>Semi Government Employee</option>
                                        <option value="Private Company Job" <?php if ($details['db_fatheroccupation'] == 'Private Company Job')
                                            echo 'selected'; ?>>Private Company Job</option>
                                        <option value="Business" <?php if ($details['db_fatheroccupation'] == 'Business')
                                            echo 'selected'; ?>>Business</option>
                                        <option value="Other" <?php if ($details['db_fatheroccupation'] == 'Other')
                                            echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's Annual Income <span
                                            style="color:red;">*</span></label>
                                    <select name="db_fathersannualincome" id="db_fathersannualincome"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="1 Lakh to 2 Lakh" <?php if ($details['db_fathersannualincome'] == '1 Lakh to 2 Lakh')
                                            echo 'selected'; ?>>1 Lakh to 2 Lakh</option>
                                        <option value="2 Lakh to 4 Lakh" <?php if ($details['db_fathersannualincome'] == '2 Lakh to 4 Lakh')
                                            echo 'selected'; ?>>2 Lakh to 4 Lakh</option>
                                        <option value="4 Lakh to 6 Lakh" <?php if ($details['db_fathersannualincome'] == '4 Lakh to 6 Lakh')
                                            echo 'selected'; ?>>4 Lakh to 6 Lakh</option>
                                        <option value="6 Lakh to 8 Lakh" <?php if ($details['db_fathersannualincome'] == '6 Lakh to 8 Lakh')
                                            echo 'selected'; ?>>6 Lakh to 8 Lakh</option>
                                        <option value="8 Lakh to 10 Lakh" <?php if ($details['db_fathersannualincome'] == '8 Lakh to 10 Lakh')
                                            echo 'selected'; ?>>8 Lakh to 10 Lakh</option>
                                        <option value="10 Lakh to 12 Lakh" <?php if ($details['db_fathersannualincome'] == '10 Lakh to 12 Lakh')
                                            echo 'selected'; ?>>10 Lakh to 12 Lakh</option>
                                        <option value="12 Lakh & Above" <?php if ($details['db_fathersannualincome'] == '12 Lakh & Above')
                                            echo 'selected'; ?>>
                                            12 Lakh & Above</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Name <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_mothersname" name="db_mothersname"
                                        value="<?= isset($details['db_mothersname']) ? $details['db_mothersname'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Qualification</label>

                                    <input type="text" id="db_mothersqualificaiton" name="db_mothersqualificaiton"
                                        value="<?= isset($details['db_mothersqualificaiton']) ? $details['db_mothersqualificaiton'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Occupation</label>
                                    <select required name="db_mothersoccupation" id="db_mothersoccupation"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="Government" <?php if ($details['db_mothersoccupation'] == 'Government')
                                            echo 'selected'; ?>>
                                            Government Employee</option>
                                        <option value="Semi Government" <?php if ($details['db_mothersoccupation'] == 'Semi Government')
                                            echo 'selected'; ?>>
                                            Semi Government Employee</option>
                                        <option value="Private Company Job" <?php if ($details['db_mothersoccupation'] == 'Private Company Job')
                                            echo 'selected'; ?>>Private Company Job</option>
                                        <option value="Business" <?php if ($details['db_mothersoccupation'] == 'Business')
                                            echo 'selected'; ?>>Business</option>
                                        <option value="Housewife" <?php if ($details['db_mothersoccupation'] == 'Housewife')
                                            echo 'selected'; ?>>
                                            Housewife</option>
                                        <option value="Other" <?php if ($details['db_mothersoccupation'] == 'Other')
                                            echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Annual Income</label>
                                    <select name="db_mothersannualincome" id="db_mothersannualincome"
                                        class="form-control <?= $readonlyClass; ?>">
                                        <option value="" disabled>--Select--</option>
                                        <option value="1 Lakh to 2 Lakh" <?php if ($details['db_mothersannualincome'] == '1 Lakh to 2 Lakh')
                                            echo 'selected'; ?>>1 Lakh to 2 Lakh</option>
                                        <option value="2 Lakh to 4 Lakh" <?php if ($details['db_mothersannualincome'] == '2 Lakh to 4 Lakh')
                                            echo 'selected'; ?>>2 Lakh to 4 Lakh</option>
                                        <option value="4 Lakh to 6 Lakh" <?php if ($details['db_mothersannualincome'] == '4 Lakh to 6 Lakh')
                                            echo 'selected'; ?>>4 Lakh to 6 Lakh</option>
                                        <option value="6 Lakh to 8 Lakh" <?php if ($details['db_mothersannualincome'] == '6 Lakh to 8 Lakh')
                                            echo 'selected'; ?>>6 Lakh to 8 Lakh</option>
                                        <option value="8 Lakh to 10 Lakh" <?php if ($details['db_mothersannualincome'] == '8 Lakh to 10 Lakh')
                                            echo 'selected'; ?>>8 Lakh to 10 Lakh</option>
                                        <option value="10 Lakh to 12 Lakh" <?php if ($details['db_mothersannualincome'] == '10 Lakh to 12 Lakh')
                                            echo 'selected'; ?>>10 Lakh to 12 Lakh</option>
                                        <option value="12 Lakh & Above" <?php if ($details['db_mothersannualincome'] == '12 Lakh & Above')
                                            echo 'selected'; ?>>
                                            12 Lakh & Above</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Parent's Mobile No. <span
                                            style="color:red;">*</span></label>

                                    <input type="number" id="db_parentsmobile" name="db_parentsmobile"
                                        value="<?= isset($details['db_parentsmobile']) ? $details['db_parentsmobile'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>" required>
                                </div>
                            </div>
                            <!--================================================================================= -->
                            <h3 class="tile-title" style="font-size: 18px;">Communication Details :</h3>


                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label style="margin-bottom:-0.5rem;">Permanent Address <span
                                            style="color:red;">*</span></label>
                                    <input type="text"
                                        value="<?= isset($details['db_permenantaddress']) ? $details['db_permenantaddress'] : ''; ?>"
                                        name="db_pemrenantaddress" id="db_permenantaddress"
                                        class="form-control <?= $readonlyClass; ?>">
                                    <br />
                                </div>
                                <div class="col-md-6">
                                    <label style="margin-bottom:-0.5rem;">Nearest landmark <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_landmark" name="db_landmark"
                                        value="<?= isset($details['db_landmark']) ? $details['db_landmark'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Pincode <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_pincode" name="db_pincode"
                                        value="<?= isset($details['db_pincode']) ? $details['db_pincode'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Telephone No.</label>

                                    <input type="text" id="db_telephonenumber" name="db_telephonenumber"
                                        value="<?= isset($details['db_telephonenumber']) ? $details['db_telephonenumber'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mobile No. <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_mobilenumber" name="db_mobilenumber"
                                        value="<?= isset($details['db_mobilenumber']) ? $details['db_mobilenumber'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Email ID</label>

                                    <input type="text" id="db_email" name="db_email"
                                        value="<?= isset($details['db_email']) ? $details['db_email'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                            </div>
                            <!-- ==================================================================================-->
                            <h3 class="tile-title" style="font-size: 18px;">Previous Examination History :</h3>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Previous School Name & Ph No. <span
                                            style="color:red;">*</span></label>

                                    <input type="text" id="db_lastschool" name="db_lastschool"
                                        value="<?= isset($details['db_lastschool']) ? $details['db_lastschool'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Udise No. </label>

                                    <input type="text" id="db_lastudiseno" name="db_lastudiseno"
                                        value="<?= isset($details['db_lastudiseno']) ? $details['db_lastudiseno'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Student id. </label>

                                    <input type="text" id="student_id" name="student_id"
                                        value="<?= isset($details['student_id']) ? $details['student_id'] : ''; ?>"
                                        class="form-control <?= $readonlyClass; ?>">
                                </div>
                            </div>
                            <!-- ================================================================================-->
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="sampleTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 260px;background-color:#f2f2f2;">Examination<br />(S.S.C
                                                or
                                                C.B.S.E /Other) <span style="color:red;">*</span></th>
                                            <th style="background-color:#f2f2f2;">Board <span
                                                    style="color:red;">*</span>
                                            </th>
                                            <th style="background-color:#f2f2f2;">Passing<br />Year <span
                                                    style="color:red;">*</span></th>
                                            <th style="background-color:#f2f2f2;">Seat No. <span
                                                    style="color:red;">*</span>
                                            </th>
                                            <th style="background-color:#f2f2f2;">Marks Obtained <span
                                                    style="color:red;">*</span>
                                            </th>
                                            <th style="background-color:#f2f2f2;">Out Of <span
                                                    style="color:red;">*</span>
                                            </th>
                                            <th style="background-color:#f2f2f2;">Percentage <span
                                                    style="color:red;">*</span>
                                            </th>
                                            <th style="background-color:#f2f2f2;">Which Appeared <span
                                                    style="color:red;">*</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="gradeX">
                                            <td><input name="db_lastclass"
                                                    value="<?= isset($details['db_lastclass']) ? $details['db_lastclass'] : ''; ?>"
                                                    id="db_lastclass" style="width:100%;" required
                                                    class="<?= $readonlyClass; ?> form-control">
                                            </td>
                                            <td><input name="db_lastboard" id="db_lastboard" 
                                                    value="<?= isset($details['db_lastboard']) ? $details['db_lastboard'] : ''; ?>"
                                                    style="width:100%;" required class="<?= $readonlyClass; ?> form-control">
                                            </td>
                                            <td><input name="db_lastyear" id="db_lastyear" 
                                                    value="<?= isset($details['db_lastyear']) ? $details['db_lastyear'] : ''; ?>"
                                                    style="width:100%;" required class="<?= $readonlyClass; ?> form-control">
                                            </td>
                                            <td><input name="db_lastseatno" id="db_lastseatno"
                                                    value="<?= isset($details['db_lastseatno']) ? $details['db_lastseatno'] : ''; ?>"
                                                    style="width:100%;" required class="<?= $readonlyClass; ?> form-control">
                                            </td>

                                            <td>
                                                <input type="number" name="db_lastmarks" id="marks_obtained"
                                                    value="<?= isset($details['db_lastmarks']) ? $details['db_lastmarks'] : ''; ?>"
                                                    style="width:100%;" class="form-control <?= $readonlyClass; ?> form-control"
                                                    oninput="calculatePercentage()" placeholder="">
                                            </td>
                                            <td>
                                                <input type="number" name="db_lastoutof" id="out_of"
                                                    value="<?= isset($details['db_lastoutof']) ? $details['db_lastoutof'] : ''; ?>"
                                                    style="width:100%;" class="form-control  <?= $readonlyClass; ?>"
                                                    oninput="calculatePercentage()" placeholder="">
                                            </td>
                                            <td>
                                                <input type="text" name="db_lastpercentage" id="percentage"
                                                    value="<?= isset($details['db_lastpercentage']) ? $details['db_lastpercentage'] : ''; ?>"
                                                    style="width:100%;" class="form-control  <?= $readonlyClass; ?>"
                                                    placeholder="">
                                            </td>
                                            </td>
                                            <td><input name="db_lastappeared" id="db_lastappeared"
                                                    value="<?= isset($details['db_lastappeared']) ? $details['db_lastappeared'] : ''; ?>"
                                                    style="width:100%;" required class="<?= $readonlyClass; ?>">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- ==================================================================================-->
                            <h3 class="tile-title" style="font-size: 18px;">Subjects offered for this academic year :
                            </h3>
                            <label><strong>Enclosure (NOTE: Please tickmark the subject
                                    appropriately.)</strong><br /><br /><br /></label>
                            <?php if (strtolower($details['db_stream']) == 'science'):
                                $gbm_choices = isset($details['choice_math_geog_bio']) ? explode("-", $details['choice_math_geog_bio']) : [];


                                ?>
                                <div id="id_science">
                                    <div class="form-group row">
                                        <div class="col-md-6">

                                            <table id="customers">
                                                <tr>
                                                    <td style="background-color:#f2f2f2;"><strong> Subjects :</strong>
                                                    </td>
                                                    <td style="background-color:#f2f2f2;"><strong> Marks </strong>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>1.English(compulsory)</strong></td>
                                                    <td><strong>(100)</strong></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>2.Physics(compulsory)</strong>
                                                    <td><strong>(100)</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>3.Chemistry(compulsory)</strong>
                                                    <td><strong>(100)</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>4.Hindi Or marathi</strong>
                                                    <td><strong>(100)</strong></td>
                                                </tr>


                                                <tr>
                                                    <td><strong>5.Environment Education(compulsory)</strong>
                                                    <td><strong>(Grade)</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>6.Health and physical education(compulsory)</strong>
                                                    <td><strong>(Grade)</strong></td>
                                                </tr>

                                            </table>
                                        </div>


                                        <div class="col-md-6">



                                            <table id="customers" class="col-md-8">
                                                <tr>
                                                    <td style="background-color:#f2f2f2;"><strong>Optional : (Select Any
                                                            Two)</strong></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>1. Mathematics&nbsp;</strong><input type="checkbox"
                                                            id="geog_maths_bio" style="margin:6px" ;
                                                            name="geog_maths_biology_m" class="pull-right"
                                                            value="Mathematics" <?= in_array("Mathematics", $gbm_choices) ? 'checked' : '' ?>     <?= $disabled; ?>> </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>2. Geography&nbsp;&nbsp;&nbsp;</strong><input
                                                            type="checkbox" id="math_geog_biol" style="margin:6px" ;
                                                            name="geog_maths_biology_g" class="pull-right" value="Geography"
                                                            <?= in_array("Geography", $gbm_choices) ? 'checked' : '' ?>
                                                            <?= $disabled; ?>> </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>3.
                                                            Biology&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><input
                                                            type="checkbox" id="math_geog_biolo" style="margin:6px" ;
                                                            name="geog_maths_biology_b" class="pull-right" value="Biology"
                                                            <?= in_array("Biology", $gbm_choices) ? 'checked' : '' ?>
                                                            <?= $disabled; ?>> </td>
                                                </tr>

                                                <tr>
                                                    <td style="background-color:#f2f2f2;"><strong>Choose any One</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>1. Hindi</strong><input type="radio" id="sci_hindi"
                                                            style="margin:6px" ; name="marathi_hindi" class="pull-right"
                                                            value="Hindi" <?= $details['choice_subj_hin_mar'] == 'Hindi' ? 'checked' : '' ?>     <?= $disabled; ?>> </td>
                                                    <td><strong>2. Marathi</strong><input type="radio" id="sci_marathi"
                                                            style="margin:6px" ; name="marathi_hindi" class="pull-right"
                                                            value="marathi" <?= $details['choice_subj_hin_mar'] == 'Hindi' ? 'checked' : '' ?>     <?= $disabled; ?>> </td>
                                                </tr>

                                            </table>


                                        </div>

                                    </div>
                                </div>

                                <!-- ============================================================================================================= -->

                            <?php elseif (strtolower($details['db_stream']) == 'commerce'): ?>
                                <div id="id_commerce">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <table id="customers">
                                                <tr>
                                                    <td style="background-color:#f2f2f2;"><strong> Subjects :</strong>
                                                    </td>
                                                    <td style="background-color:#f2f2f2;"><strong> Marks </strong>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><strong>1.English(compulsory)</strong></td>
                                                    <td><strong>(100)</strong></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>2.Book-Keeping and accountancy(compulsory)</strong>
                                                    <td><strong>(100)</strong></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>3.Organization of Commerce & Management(compulsory)</strong>
                                                    <td><strong>(100)</strong></td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>4. Economics(compulsory)</strong>
                                                    <td><strong>(100)</strong></td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>5.Hindi Or marathi</strong>
                                                    <td><strong>(100)</strong></td>
                                                </tr>


                                                <tr>
                                                    <td><strong>6.Environment Education(compulsory)</strong>
                                                    <td><strong>(Grade)</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>7.Health and physical education(compulsory)</strong>
                                                    <td><strong>(Grade)</strong></td>
                                                </tr>


                                            </table>
                                        </div>

                                        <div class="col-md-6">

                                            <table id="customers">
                                                <tr>
                                                    <td style="background-color:#f2f2f2;"><strong>Option - 1 :</strong></td>
                                                </tr>

                                                <tr>
                                                    <td><strong>1. Mathematics</strong><input type="radio"
                                                            id="geog_maths_bio" style="margin:6px" ; name="commerce_sub"
                                                            class="pull-right mgb_radio" value="Mathematics"
                                                            <?= $details['commerce_optional_sub'] == 'Mathematics' ? 'checked' : '' ?>     <?= $disabled; ?>> </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>2. Geography&nbsp;</strong><input type="radio"
                                                            id="math_geog_biol" style="margin:6px" ; name="commerce_sub"
                                                            class="pull-right mgb_radio" value="Geography"
                                                            <?= $details['commerce_optional_sub'] == 'Geography' ? 'checked' : '' ?>     <?= $disabled; ?>> </td>
                                                </tr>


                                            </table>
                                            <!-- ============================================= Updated 07 Jun 24 START=============================================================                               -->
                                            <table>
                                                <tr>
                                                    <td style="background-color:#f2f2f2;"><strong>Option - 2 :</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>1. Marathi</strong><input type="radio" id="marathi"
                                                            style="margin:6px" ; name="commerce_h_m"
                                                            class="pull-right mgb_radio" value="marathi"
                                                            <?= $details['commerce_sub_h_m'] == 'marathi' ? 'checked' : '' ?>
                                                            <?= $disabled; ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>2.hindi&nbsp;&nbsp;</strong><input type="radio" id="hindi"
                                                            style="margin:6px" ; name="commerce_h_m"
                                                            class="pull-right mgb_radio" value="hindi"
                                                            <?= $details['commerce_sub_h_m'] == 'hindi' ? 'checked' : '' ?>
                                                            <?= $disabled; ?>>
                                                    </td>
                                                </tr>
                                            </table>


                                            <br /><label><strong>Optional Subjects : (Select one each from the given
                                                    groups)</strong></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <br />


                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label>Enclosure (NOTE: Please tickmark in checkbox if that document is
                                        submitted.)</label><br />
                                </div>
                            </div>
                            <table id="customers" class="table-responsive">
                                <tr>
                                    <td style="background-color:#f2f2f2;"><strong>Certificate Name</strong></td>
                                    <td style="background-color:#f2f2f2;"><strong>Photocopy</strong></td>
                                    <td style="background-color:#f2f2f2;"><strong>Original</strong></td>
                                    <td style="background-color:#f2f2f2;"><strong>Upload File</strong></td>
                                </tr>

                                <tr>
                                    <td><strong>Birth Certificate</strong></td>
                                    <td><input type="radio" name="birth_certificate" value="Photocopy"
                                            <?= $details['birth_certificate'] == 'Photocopy' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td><input type="radio" name="birth_certificate" value="Original"
                                            <?= $details['birth_certificate'] == 'Original' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td>
                                        <?php if (!$hideFileInput): ?>
                                            <input type="file" name="birth_certificate_docu">
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Transfer / School Leaving Certificate</strong></td>
                                    <td><input type="radio" name="school_leaving" value="Photocopy"
                                            <?= $details['school_leaving_certi'] == 'Photocopy' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td><input type="radio" name="school_leaving" value="Original"
                                            <?= $details['school_leaving_certi'] == 'Original' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td>
                                        <?php if (!$hideFileInput): ?>
                                            <input type="file" name="school_leaving_docu">
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Progress / Report Card</strong></td>
                                    <td><input type="radio" name="progress_report" value="Photocopy"
                                            <?= $details['progress_report_Card'] == 'Photocopy' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td><input type="radio" name="progress_report" value="Original"
                                            <?= $details['progress_report_Card'] == 'Original' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td>
                                        <?php if (!$hideFileInput): ?>
                                            <input type="file" name="progress_report_docu">
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Passport Size Color Photographs of Student (4 Nos.)</strong></td>
                                    <td><input type="radio" name="passport_photo_student" value="Photocopy"
                                            <?= $details['passport_photo_student'] == 'Photocopy' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td><input type="radio" name="passport_photo_student" value="Original"
                                            <?= $details['passport_photo_student'] == 'Original' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td>
                                        <?php if (!$hideFileInput): ?>
                                            <input type="file" name="passport_photo_docu">
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Nationality Domicile</strong></td>
                                    <td><input type="radio" name="nationality_domicile" value="Photocopy"
                                            <?= $details['nationality_domicle'] == 'Photocopy' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td><input type="radio" name="nationality_domicile" value="Original"
                                            <?= $details['nationality_domicle'] == 'Original' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td>
                                        <?php if (!$hideFileInput): ?>
                                            <input type="file" name="nationality_domicile_docu">
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <?= $details['caste_certificate'] ?>

                                    <td><strong>Caste Certificate</strong></td>
                                    <td><input type="radio" name="caste_certificate" value="Photocopy"
                                            <?= $details['caste_certificate'] == 'Photocopy' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td><input type="radio" name="caste_certificate" value="Original"
                                            <?= $details['caste_certificate'] == 'Original' ? 'checked' : '' ?>
                                            <?= $disabled; ?>></td>
                                    <td>
                                        <?php if (!$hideFileInput): ?>
                                            <input type="file" name="caste_certificate_docu">
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Aadhar Card</strong></td>
                                    <td><input type="radio" name="aadhar_card" value="Photocopy"
                                            <?= $details['aadhar_card'] == 'Photocopy' ? 'checked' : '' ?> <?= $disabled; ?>></td>
                                    <td><input type="radio" name="aadhar_card" value="Original"
                                            <?= $details['aadhar_card'] == 'Original' ? 'checked' : '' ?> <?= $disabled; ?>>
                                    </td>
                                    <td>
                                        <?php if (!$hideFileInput): ?>
                                            <input type="file" name="aadhar_card_docu">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                            <button type="submit" id="updateButton" class="btn btn-primary">Update</button>
                        </form>
                        <!-- ==================================================================================== -->


                    </div>
                </div>

                <br />


            </div>

        </div>
        <!-- ================================================================================================== -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const dk = document.querySelectorAll('.mgb_checkbox');

                dk.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                        const checkedCheckboxes = document.querySelectorAll('.mgb_checkbox:checked');
                        if (checkedCheckboxes.length > 2) {
                            checkbox.checked = false;
                            alert("You can only select up to 2 options.");
                        }
                    });
                });
            });
        </script>
        <!-- ============================================================================================== -->

        <script>
            function calculatePercentage() {
                var marksObtained = parseFloat(document.getElementById('marks_obtained').value);
                var outOf = parseFloat(document.getElementById('out_of').value);
                var percentage = 0;

                if (!isNaN(marksObtained) && !isNaN(outOf) && outOf > 0) {
                    percentage = (marksObtained / outOf) * 100;
                }

                document.getElementById('percentage').value = percentage.toFixed(2) + '%';
            }
        </script>
        <!-- ================================================================================================ -->
        <script>
            $(document).ready(function () {
                $('#myForm').on('submit', function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: '<?= base_url() ?>welcome/add_college_form',
                        type: 'post',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            alert('Data saved successfully');
                            $('#myForm')[0].reset();
                        },
                        error: function (xhr, status, error) {
                            alert('There was an error submitting the form.');
                        }
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var action = '<?php echo isset($_GET['action']) ? $_GET['action'] : '' ?>';
                if (action === 'view') {
                    document.getElementById('updateButton').style.display = 'none';
                }
            });
        </script>
        <!-- 
$('#submit-form').on('submit', function(e) {
            e.preventDefault();
            // if (confirm("Did you review the data?")) {
            $.ajax({
                url: "<?= base_url('trial/add') ?>",
                data: new FormData(this),
                method: "POST",
                contentType: false,
                processData: false,
                beforeSend: function() {},
                success: function(response) {
                    if (response != 'fail') {
                        alert("Data Saved Successfully");
                        window.location = response;
                    } else {
                        alert("Failed to Save Data");
                    }
                },
            });
            // }
        }); -->
        <script>
            $('#updateButton').submit(function (e) {
                e.preventDefault(); // Prevent the default button click behavior

                // var form = $('#id').val(); // Ensure this is the correct form ID
                // var formData = new FormData(form);

                $.ajax({
                    url: '<?= base_url() ?>Online_Application/update',
                    method: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.status == 'success') {
                            alert('Update was successful');
                        } else {
                            alert('Failed to update data');
                        }
                    },
                    error: function () {
                        alert('Error in updating data');
                    }
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
            integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+"
            crossorigin="anonymous"></script>

</body>

</html>