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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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

        /* .bg-ban {
            background: url("https://www.generian.com/wp-content/uploads/2020/08/img-home-hero-bg-4-scaled.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        } */

        /* Extra small devices (phones, 600px and down) */
        @media only screen and (max-width: 600px) {
            .school-logo {
                height: 90px !important;
            }
        }
    </style>

</head>

<body class="bg-light">
    <div class="container">
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- School Details  -->
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
                            <!-- ================================================================================ -->
                            <div class="form-group row">


                                <div class="col-md-4">
                                    <label style="margin-bottom: -0.5rem;">Branch <span
                                            style="color:red;">*</span></label>
                                    <select name="db_branch" class="form-control">
                                        <option value="Olive junior College" selected>Olive Junior College</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Stream<span
                                            style="color:red;">*</span></label>
                                    <select name="db_stream" id="id_stream" class="form-control"
                                        onchange="function_stream()">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="Science" selected>Science</option>
                                        <option value="Commerce">Commerce</option>

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Admission Type <span
                                            style="color:red;">*</span></label>
                                    <select name="db_admissiontype" id="db_admissiontype" required class="form-control">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="New Admission">New Admission</option>
                                        <option value="Re-Admission">Re-Admission</option>
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Class <span
                                            style="color:red;">*</span></label>
                                    <select name="db_class" required id="db_class" class="form-control">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="11th">11th</option>
                                        <option value="12th">12th</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Division <span
                                            style="color:red;">*</span></label>
                                    <select name="db_division" id="db_division" class="form-control">
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Medium <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_medium" id="db_medium" class="form-control">
                                        <option value="English">English</option>

                                    </select>
                                </div>

                            </div>
                            <!--========================================================================== -->
                            <h3 class="tile-title" style="font-size: 18px;">Student's Information :</h3>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Firstname <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="firstname" name="firstname" value=""
                                        class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Middlename<span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="Middle-name" name="Middlename" value=""
                                        class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Lastname <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="lastname" name="lastname" value=""
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Date of birth <span
                                            style="color:red;">*</span></label>
                                    <input type="date" id="date" name="date" value="" class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">


                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Nationality <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_nationality" id="db_nationality" class="form-control">
                                        <option value="Indian">Indian</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Place of birth <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_place" name="db_place" value=""
                                        class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Gender <span
                                            style="color:red;">*</span></label>
                                    <select required name="db_gender" id="db_gender" class="form-control">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother Tongue <span
                                            style="color:red;">*</span></label>

                                    <select required name="db_mothertounge" id="db_mothertounge" class="form-control">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="Marathi">Marathi</option>
                                        <option value="Hindi">Hindi</option>
                                        <option value="English">English</option>
                                        <option value="Gujarati">Gujarati</option>
                                        <option value="Urdu">Urdu</option>
                                        <option value="Punjabi">Punjabi</option>

                                    </select>

                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Religion <span
                                            style="color:red;">*</span></label>

                                    <select required name="db_religion" id="db_religion" class="form-control">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="Hindu">Hindu</option>
                                        <option selected value="Islam">Islam</option>
                                        <option value="Christian">Christian</option>
                                        <option value="Sikh">Sikh</option>
                                        <option value="Other">Other</option>
                                    </select>

                                </div>


                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Caste <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required name="db_caste" id="db_caste" class="form-control"
                                        placeholder="Enter caste">
                                </div>


                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Student Aadhar No.</label>
                                    <input type="number" id="db_adharnumber" name="db_adharnumber" value=""
                                        class="form-control">
                                </div>

                            </div>


                            <input type="hidden" id="id" name="id" value="0">


                            <!--  ============================================================== -->
                            <h3 class="tile-title" style="font-size: 18px;">Parent's Information :</h3>


                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's first Name <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fatherfirstname" name="db_fatherfirstname"
                                        value="" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's middle Name <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fathermiddlename" name="db_fathermiddlename"
                                        value="" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's last Name <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fatherlastname" name="db_fatherlastname"
                                        value="" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Aadhar No. <span
                                            style="color:red;">*</span></label>
                                    <input type="number" required id="db_fatheradharnumber" name="db_fatheradharnumber"
                                        class="form-control">
                                </div>

                            </div>
                            <div class="form-group row">

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's Qualification <span
                                            style="color:red;">*</span></label>
                                    <input type="text" required id="db_fatherqualification"
                                        name="db_fatherqualification" value="" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's Occupation <span
                                            style="color:red;">*</span></label>

                                    <select required name="db_fatheroccupation" id="db_fatheroccupation"
                                        class="form-control">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="Government">Government Employee</option>
                                        <option value="Semi Government">Semi Government Employee</option>
                                        <option value="Private Company Job">Private Company Job</option>
                                        <option value="Business">Business</option>
                                        <option value="Other">Other</option>
                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Father's Annual Income <span
                                            style="color:red;">*</span></label>

                                    <select name="db_fathersannualincome" id="db_fathersannualincome"
                                        class="form-control">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="1 Lakh to 2 Lakh">1 Lakh to 2 Lakh</option>
                                        <option value="2 Lakh to 4 Lakh">2 Lakh to 4 Lakh</option>
                                        <option value="4 Lakh to 6 Lakh">4 Lakh to 6 Lakh</option>
                                        <option value="6 Lakh to 8 Lakh">6 Lakh to 8 Lakh</option>
                                        <option value="8 Lakh to 10 Lakh">8 Lakh to 10 Lakh</option>
                                        <option value="10 Lakh to 12 Lakh">10 Lakh to 12 Lakh</option>
                                        <option value="12 Lakh & Above">12 Lakh & Above</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Name <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_mothersname" name="db_mothersname" value=""
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Qualification</label>

                                    <input type="text" id="db_mothersqualificaiton" name="db_mothersqualificaiton"
                                        value="" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Occupation</label>

                                    <select required name="db_mothersoccupation" id="db_mothersoccupation"
                                        class="form-control">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="Government">Government Employee</option>
                                        <option value="Semi Government">Semi Government Employee</option>
                                        <option value="Private Company Job">Private Company Job</option>
                                        <option value="Business">Business</option>
                                        <option value="Housewife">Housewife</option>
                                        <option value="Other">Other</option>
                                    </select>


                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mother's Annual Income</label>


                                    <select name="db_mothersannualincome" id="db_mothersannualincome"
                                        class="form-control">
                                        <option value="" selected>--Select--</option>
                                        <option value="1 Lakh to 2 Lakh">1 Lakh to 2 Lakh</option>
                                        <option value="2 Lakh to 4 Lakh">2 Lakh to 4 Lakh</option>
                                        <option value="4 Lakh to 6 Lakh">4 Lakh to 6 Lakh</option>
                                        <option value="6 Lakh to 8 Lakh">6 Lakh to 8 Lakh</option>
                                        <option value="8 Lakh to 10 Lakh">8 Lakh to 10 Lakh</option>
                                        <option value="10 Lakh to 12 Lakh">10 Lakh to 12 Lakh</option>
                                        <option value="12 Lakh & Above">12 Lakh & Above</option>
                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Parent's Mobile No. <span
                                            style="color:red;">*</span></label>

                                    <input type="number" id="db_parentsmobile" name="db_parentsmobile" value=""
                                        class="form-control" required>
                                </div>
                            </div>
                            <!--================================================================================= -->
                            <h3 class="tile-title" style="font-size: 18px;">Communication Details :</h3>


                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label style="margin-bottom:-0.5rem;">Permanent Address <span
                                            style="color:red;">*</span></label>
                                    <input type="text" name="db_pemrenantaddress" id="db_permenantaddress"
                                        class="form-control">
                                    <br />
                                </div>
                                <div class="col-md-6">
                                    <label style="margin-bottom:-0.5rem;">Nearest landmark <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_landmark" name="db_landmark" value=""
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Pincode <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_pincode" name="db_pincode" value=""
                                        class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Telephone No.</label>

                                    <input type="text" id="db_telephonenumber" name="db_telephonenumber" value="-"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Mobile No. <span
                                            style="color:red;">*</span></label>

                                    <input type="text" required id="db_mobilenumber" name="db_mobilenumber" value=""
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label style="margin-bottom:-0.5rem;">Email ID</label>

                                    <input type="text" id="db_email" name="" value="" class="form-control">
                                </div>
                            </div>
                            <!-- ==================================================================================-->
                            <h3 class="tile-title" style="font-size: 18px;">Previous Examination History :</h3>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Previous School Name & Ph No. <span
                                            style="color:red;">*</span></label>

                                    <input type="text" id="db_lastschool" name="db_lastschool" value=""
                                        class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Udise No. </label>

                                    <input type="text" id="db_lastudiseno" name="db_lastudiseno" value=""
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label style="margin-bottom:-0.5rem;">Student id. </label>

                                    <input type="text" id="student_id" name="student_id" value="" class="form-control">
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
                                            <td><input name="db_lastclass" id="db_lastclass" style="width:100%;"
                                                    required>
                                            </td>
                                            <td><input name="db_lastboard" id="db_lastboard" style="width:100%;"
                                                    required>
                                            </td>
                                            <td><input name="db_lastyear" id="db_lastyear" style="width:100%;"
                                                    required>
                                            </td>
                                            <td><input name="db_lastseatno" id="db_lastseatno" style="width:100%;"
                                                    required>
                                            </td>

                                            <td>
                                                <input type="number" name="db_lastmarks" id="marks_obtained"
                                                    style="width:100%;" class="form-control"
                                                    oninput="calculatePercentage()" placeholder="">
                                            </td>
                                            <td>
                                                <input type="number" name="db_lastoutof" id="out_of" style="width:100%;"
                                                    class="form-control" oninput="calculatePercentage()" placeholder="">
                                            </td>
                                            <td>
                                                <input type="text" name="db_lastpercentage" id="percentage"
                                                    style="width:100%;" class="form-control" placeholder="">
                                            </td>
                                            </td>
                                            <td><input name="db_lastappeared" id="db_lastappeared" style="width:100%;"
                                                    required>
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
                                                        name="geog_maths_biology_m" class="pull-right mgb_checkbox"
                                                        value="Mathematics"> </td>
                                            </tr>
                                            <tr>
                                                <td><strong>2. Geography&nbsp;&nbsp;&nbsp;</strong><input
                                                        type="checkbox" id="math_geog_biol" style="margin:6px" ;
                                                        name="geog_maths_biology_g" class="pull-right mgb_checkbox"
                                                        value="Geography"> </td>
                                            </tr>

                                            <tr>
                                                <td><strong>3.
                                                        Biology&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong><input
                                                        type="checkbox" id="math_geog_biolo" style="margin:6px" ;
                                                        name="geog_maths_biology_b" class="pull-right mgb_checkbox"
                                                        value="Biology"> </td>
                                            </tr>

                                            <tr>
                                                <td style="background-color:#f2f2f2;"><strong>Choose any One</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>1. Hindi</strong><input type="radio" id="sci_hindi"
                                                        style="margin:6px" ; name="marathi_hindi" class="pull-right"
                                                        value="Hindi"> </td>
                                                <td><strong>2. Marathi</strong><input type="radio" id="sci_marathi"
                                                        style="margin:6px" ; name="marathi_hindi" class="pull-right"
                                                        value="marathi"> </td>
                                            </tr>

                                        </table>


                                    </div>

                                </div>
                            </div>

                            <!-- ============================================================================================================= -->


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
                                                        class="pull-right mgb_radio" value="Mathematics"> </td>
                                            </tr>
                                            <tr>
                                                <td><strong>2. Geography&nbsp;</strong><input type="radio"
                                                        id="math_geog_biol" style="margin:6px" ; name="commerce_sub"
                                                        class="pull-right mgb_radio" value="Geography"> </td>
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
                                                        class="pull-right mgb_radio" value="marathi"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>2.hindi&nbsp;&nbsp;</strong><input type="radio" id="hindi"
                                                        style="margin:6px" ; name="commerce_h_m"
                                                        class="pull-right mgb_radio" value="hindi"></td>
                                            </tr>
                                        </table>


                                        <br /><label><strong>Optional Subjects : (Select one each from the given
                                                groups)</strong></label>
                                    </div>
                                </div>
                            </div>

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
                                    <td><input type="radio" name="birth_certificate" value="Photocopy"></td>
                                    <td><input type="radio" name="birth_certificate" value="Original"></td>
                                    <td><input type="file" name="birth_certificate_docu"></td>
                                </tr>
                                <tr>
                                    <td><strong>Transfer / School Leaving Certificate</strong></td>
                                    <td><input type="radio" name="school_leaving" value="Photocopy"></td>
                                    <td><input type="radio" name="school_leaving" value="Original"></td>
                                    <td><input type="file" name="school_leaving_docu"></td>
                                </tr>
                                <tr>
                                    <td><strong>Progress / Report Card</strong></td>
                                    <td><input type="radio" name="progress_report" value="Photocopy"></td>
                                    <td><input type="radio" name="progress_report" value="Original"></td>
                                    <td><input type="file" name="progress_report_docu"></td>
                                </tr>
                                <tr>
                                    <td><strong>Passport Size Color Photographs of Student (4 Nos.)</strong>
                                    </td>
                                    <td><input type="radio" name="passport_photo_student" value="Photocopy">
                                    </td>
                                    <td><input type="radio" name="passport_photo_student" value="Original">
                                    </td>
                                    <td><input type="file" name="passport_photo_docu"></td>
                                </tr>
                                <tr>
                                    <td><strong>Nationality Domicile</strong></td>
                                    <td><input type="radio" name="nationality_domicile" value="Photocopy">
                                    </td>
                                    <td><input type="radio" name="nationality_domicile" value="Original">
                                    </td>
                                    <td><input type="file" name="nationality_domicile_docu"></td>
                                </tr>
                                <tr>
                                    <td><strong>Passport Size Color Photographs of Father & Mother (4
                                            Nos.)</strong></td>
                                    <td><input type="radio" name="passport_photo_parents" value="Photocopy">
                                    </td>
                                    <td><input type="radio" name="passport_photo_parents" value="Original">
                                    </td>
                                    <td><input type="file" name="passport_photo_parents_docu"></td>
                                </tr>
                                <tr>
                                    <td><strong>Caste Certificate</strong></td>
                                    <td><input type="radio" name="caste_certificate" value="Photocopy"></td>
                                    <td><input type="radio" name="caste_certificate" value="Original"></td>
                                    <td><input type="file" name="caste_certificate_docu"></td>
                                </tr>
                                <tr>
                                    <td><strong>Aadhar Card</strong></td>
                                    <td><input type="radio" name="aadhar_card" value="Photocopy"></td>
                                    <td><input type="radio" name="aadhar_card" value="Original"></td>
                                    <td><input type="file" name="aadhar_card_docu"></td>
                                </tr>

                            </table>
                            <div class="tile-footer" style="text-align:center;">
                                <button type="submit" id="submit-btn" class="button-default" name="submit"
                                    style="padding: 5px;border-radius: 10px;font-size:18px;"><i
                                        class="fa fa-fw fa-lg fa-check-circle"></i>Submit Form</button>

                            </div>
                        </form>

                    </div>
                </div>

                <br />


            </div>

        </div>
    </div>
    <!-- ================================================================================= -->

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+"
        crossorigin="anonymous"></script>

    <script>
        function function_stream() {
            var stream = document.getElementById("id_stream").value;

            if (stream === "Science") {
                document.getElementById("id_science").style.display = "block";
                document.getElementById("id_commerce").style.display = "none";
            } else if (stream === "Commerce") {
                document.getElementById("id_science").style.display = "none";
                document.getElementById("id_commerce").style.display = "block";
            } else {
                document.getElementById("id_science").style.display = "none";
                document.getElementById("id_commerce").style.display = "none";
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            var streamSelect = document.getElementById("id_stream");
            if (streamSelect.value === "Science" || streamSelect.value === "Commerce") {
                function_stream();
            }
        });
    </script>
    <!-- ================================================================================================== -->


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
                    url: '<?= base_url() ?>Forms/add_college_form',
                    type: 'post',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        alert('Form submitted successfully!');
                        $('#myForm')[0].reset();
                    },
                    error: function (xhr, status, error) {
                        alert('There was an error submitting the form.');
                    }
                });
            });
        });
    </script>



</body>

</html>