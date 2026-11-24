<link rel="stylesheet" href="<?= base_url('assets/css/new/admission_form.css'); ?>">
<form action="<?= base_url('Student/add_student_new_data') ?>" id="new-form-submit" method="POST">

    <div class="row p-3">

        <div class="col-md-12 ps-1 mt-2" id="mainContent">
            <div class="row">

                <div class="col-md-12 text-end">

                    <a href="<?= base_url(); ?>" class="btn btn-sm btn-outline-secondary"> <i class='bx bxs-home'></i> Home</a>

                    <button class="btn btn-sm btn-outline-warning" type="button" onclick="window.print();"> <i class='bx bxs-printer'></i> Print</button>

                    <button class="btn btn-sm btn-success" id="load"> <i class='bx bxs-edit-alt'></i> Submit</button>

                </div>


            </div>
            <div class="card mt-2" style="height:auto">
                <div class="row">

                    <div class="col-md-12">

                        <div class="card form">

                            <div class="card-body">

                                <table class="table" style="border: none;">

                                    <tr>

                                        <td style="border: none;">

                                            <img src="<?= base_url('images/logoNewDev.png') ?>" class="img-fluid" style="height: 50px;" alt="">

                                        </td>

                                        <td style="border: none; vertical-align: middle;" class="text-center">

                                            <h3 id="acdetail" class="text-primary"><?= $entity[0] . ' ' . $entity[1] ?> - Admission Form &nbsp; <?= $academic_details['stvacdm_year'] ?></h3>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;" colspan="2">

                                            <hr>

                                        </td>

                                    </tr>

                                </table>

                                <table class="table head">

                                    <!-- ------------------------------------CHANGES ADDED----------->
                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="GR_no fw-bold" id="GR_no">GR.Number <span class="float-right">:</span>
                                            </span>

                                        </td>

                                        <td style="border: none; width: 29%; vertical-align: middle; padding:0px !important;" class="text-left form-control form-control-sm cust-border-bottom" id="Gr_number">

                                            <input name="gr_number" autocomplete="off" id="gr_number" type="text" class="form-control form-control-sm cust-border-bottom" style="width: 29%;">

                                        </td>


                                    </tr>
                                    <!-- --------------------------------------------------------- -->
                                    <!-- <td style="border: none; width: 25%;">

                        <h3 id="acdetail" class="text-primary">Olive English School - Admission Form
                            <?= $academic_details['stvacdm_year'] ?></h3>

                    </td> -->

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Academic Year <span class="float-right">:</span>
                                            </span>

                                        </td>


                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important; display: flex;" class="text-center">

                                            <div class="select-box d-flex justify-content-bewteen align-items-center" style="width: 65%;">

                                                <select name="acdm_year" id="acdm_year" onchange="handleChange(event)" class="form-control form-control-sm cust-border-bottom" style="width: 50% !important;">

                                                    <option value="">Select Academic Year</option>

                                                    <?php foreach ($academic_years as $academic_year) {

                                                        if ($academic_details['stvacdm_id'] == $academic_year['stvacdm_id']) {

                                                            $selected = 'selected';
                                                        } else {

                                                            $selected = '';
                                                        }

                                                    ?>

                                                        <option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>

                                                    <?php } ?>

                                                </select>

                                                <div style="width: 100%; text-wrap: wrap;">
                                                    <small class="text-danger fw-bold">Check Academic Year Before Saving.</small>
                                                </div>

                                            </div>

                                            <style>
                                                .back-entry {
                                                    font-size: 14px;
                                                }
                                            </style>

                                            <div class="back-entry" style="width: 35%;">

                                                <input type="radio" name="back_entry" id="back_entry" value="back"> <span class="text-danger">Back Entry</span>

                                                <input type="radio" name="back_entry" checked id="new_entry" value="new"> New Entry

                                            </div>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Student ID (For Office Use Only) <span class="float-right">:</span> </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input type="text" autocomplete="off" name="office_id" id="office_id" class="form-control form-control-sm cust-border-bottom">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Student ID (System Generated) <span class="float-right">:</span> </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input type="text" name="student_id" readonly id="student_id" class="form-control form-control-sm cust-border-bottom" style="background-color: transparent;">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Student Aadhar Number <span class="float-right">:</span> </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input name="aadhar_number" autocomplete="off" required id="aadhar_number" type="text" class="form-control form-control-sm cust-border-bottom">

                                        </td>

                                    </tr>

                                </table>

                                <table class="table stu-det">

                                    <!--First Row-->

                                    <tr>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Student Name</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Father Name</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Surname</span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required name="student_name" id="student_name" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required name="father_name" id="father_name" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required name="surname" id="surname" type="text" class="form-control with-border">

                                        </td>

                                    </tr>

                                    <!--Second Row-->

                                    <tr>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Mother Name</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Date of Birth</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Admission Class</span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required name="mother_name" id="mother_name" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required name="date_of_birth" id="date_of_birth" type="date" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <select class="form-control with-border" name="admission_class" id="admission_class" style="padding-top: 2px;" required>

                                                <option value="">Select Class</option>

                                                <?php

                                                foreach ($classes as $class) {

                                                ?>

                                                    <option value="<?php echo $class['stvfees_class']; ?>">

                                                        <?php echo $class['stvfees_class']; ?></option>

                                                <?php } ?>



                                            </select>

                                        </td>

                                    </tr>

                                    <!--Third Row-->

                                    <tr>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Nationality</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Religion</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Mother Tongue</span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required value="Indian" name="nationality" id="nationality" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required value="Muslim" name="religion" id="religion" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" required value="Urdu" name="mother_tongue" id="mother_tongue" type="text" class="form-control with-border">

                                        </td>

                                    </tr>

                                    <!--Fourth Row-->

                                    <tr>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Caste</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Sub Caste</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Place of Birth</span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="caste" id="caste" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="sub_caste" id="sub_caste" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="birth_place" id="birth_place" type="text" class="form-control with-border">

                                        </td>

                                    </tr>

                                    <!--Fifth Row-->

                                    <tr>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Taluka</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">District</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">State</span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" value="Dhule" name="taluka" id="taluka" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="district" value="Dhule" id="district" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" value="Maharashtra" name="state" id="state" type="text" class="form-control with-border">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Gender</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Fees <small class="text-danger fw-bold">(At the time of Admission)</small> </span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Payment Mode <span class="text-danger">*</span> </span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <select class="form-control with-border" name="gender" id="gender" style="padding-top: 2px;" required>

                                                <option value="">Select Gender</option>

                                                <option value="Male">Male</option>

                                                <option value="Female">Female</option>

                                            </select>

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="fees_paid" id="fees_paid" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <select class="form-control with-border" name="payment_mode" id="payment_mode" style="padding-top: 2px;" required>

                                                <option value="">Select Mode</option>

                                                <option selected value="cash">Cash</option>

                                                <option value="online">Online</option>

                                            </select>

                                        </td>

                                    </tr>
                                    <tr>


                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Whatsapp</span>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="whatsapp_number" id="whatsapp_number" type="text" class="form-control with-border">

                                        </td>

                                    </tr>

                                </table>

                                <table class="table head" style="margin-top: 0px;">

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Previous School (For Office Use Only) : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input type="text" name="last_school" id="last_school" class="form-control form-control-sm cust-border-bottom">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Parent's Mobile Number : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input type="text" name="parents_mobile" id="parents_mobile" class="form-control form-control-sm cust-border-bottom">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Permanent Residence Address : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <textarea name="main_address" id="main_address" class="form-control form-control-sm cust-border-bottom" rows="3"> </textarea>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Date of Admission : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input type="text" value="<?= date('d-M-Y'); ?>" name="dot" id="dot" class="form-control form-control-sm cust-border-bottom">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Admission Place : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input type="text" value="Dhule" name="ad_place" id="ad_place" class="form-control form-control-sm cust-border-bottom">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Notes : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">

                                            <input type="text" name="admission_note" id="admission_note" class="form-control form-control-sm cust-border-bottom">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">RTE Student : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-left">

                                            <input type="radio" name="rte_student" id="rte_student_yes" value="Yes"> Yes

                                            <input checked type="radio" name="rte_student" id="rte_student_no" value="No"> No

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Main Documents : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-left">

                                            <input checked type="radio" name="main_docs" id="main_docs_birth" value="Yes"> Birth Certificate

                                            <input type="radio" name="main_docs" id="main_docs_lc" value="No"> Leaving Certificate

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none; width: 25%;">

                                            <span class="fw-bold">Other Documents : </span>

                                        </td>

                                        <td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-left">

                                            <input type="checkbox" name="student_aadhar" value="Yes"> Student Aadhar Card <br>

                                            <input type="checkbox" name="father_aadhar" value="Yes"> Father Aadhar Card <br>

                                            <input type="checkbox" name="passport_photo" value="Yes"> 3 Passport Size Photo <br>

                                        </td>

                                    </tr>

                                </table>

                                <table class="table stu-det" style="margin-top: 100px;">

                                    <tr>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="clerk" id="clerk" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="parent_sign" id="parent_sign" type="text" class="form-control with-border">

                                        </td>

                                        <td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">

                                            <input autocomplete="off" name="princi" id="princi" type="text" class="form-control with-border">

                                        </td>

                                    </tr>

                                    <tr>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Clerk Signature</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Parent Signature</span>

                                        </td>

                                        <td style="border: none;  text-align:center;">

                                            <span class="fw-bold">Principal Signature</span>

                                        </td>

                                    </tr>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        <?php include_once('application/views/pages/includes/sidebar.php') ?>

    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url('assets/js/new/admission_form.js') ?>"></script>