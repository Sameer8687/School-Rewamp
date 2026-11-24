<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Profile</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Phoenixcoded" />
    <meta property="og:url" content="<?php echo base_url(); ?>profile" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Student Profile" />
    <meta property="og:description" content="Olive English School - Student Profile" />
    <meta property="og:site_name" content="<?php echo base_url(); ?>" />
    <meta property="og:image" content="<?php echo base_url(); ?>images/logoNewDev.png" />
    <?php include 'application/views/cssPages.php'; ?>
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/profile-student.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/new-student-profile.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>

<body class="profile-bg">
    <div class="sticky-header">
        <img src="<?= base_url(); ?>images/logoNewDevWhite.png" class="img-fluid header-img" alt="">
        <span>Olive English School</span>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card user-card user-card-2 shape-center">
                    <div class="card-header border-0 p-2 pb-0">
                        <div class="cover-img-block">
                            <img src="<?= base_url(); ?>images/olive1.jpg" alt="" class="img-fluid">
                            <input type="hidden" id="getFullScreenMode">
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="user-about-block text-center">
                            <div class="row align-items-end">
                                <div class="col text-left pb-3"><a href="#!"><i class=""></i></a></div>
                                <div class="col">
                                    <div class="position-relative d-inline-block">
                                        <img class="img-radius img-fluid wid-90" style="margin-top: -20px;"
                                            src="<?= base_url(); ?>images/user.png" alt="User image">
                                    </div>
                                </div>
                                <div class="col text-right pb-3">
                                </div>
                            </div>
                        </div>
                        <div class="text-center name-div">
                            <h6 class="mb-1 mt-3 name-title">
                                <?php
                                $student_name = $spriden_details['SPRIDEN_surname'] . " " . $spriden_details['SPRIDEN_student_name'] . " " . $spriden_details['SPRIDEN_father_name'];
                                echo $student_name;
                                ?>
                            </h6>
                            <p class="mb-1 text-muted">
                                <?php
                                echo "ID : " . $spriden_details['SPRIDEN_student_id'];
                                if ($stvclas_data != null) {
                                    echo " &nbsp;&nbsp;Class : " . $stvclas_data['STVCLAS_standard'] . " - " . $stvclas_data['STVCLAS_class'];
                                } else {
                                    echo "Not Assigned";
                                }
                                ?>
                            </p>
                            <p>Academic Year: <?= $academic_year_detail ?></p>
                        </div>
                        <hr class="wid-80 b-wid-3 my-4">
                        <div class="row text-center">
                            <div class="col">
                                <a href="<?= base_url(); ?>public/student/logout" class="btn btn-sm w-100 header-button-round">
                                    <i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                            <div class="col">
                                <a href="" class="btn btn-sm w-100 header-button-round"> <i class="fas fa-user"></i>
                                    Profile</a>
                            </div>
                            <div class="col">
                                <a target="_blank" href="" class="btn btn-sm w-100 header-button-round"> <i
                                        class="fas fa-desktop"></i> Take
                                    Quiz</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        function show_field($array, $key)
        {
            if (isset($array[$key]))
                return $array[$key];
            return '';
        }
        ?>
        <input type="hidden" name="student_id" id="student_id" value="<?php echo $stud ?>">
        <div class="row">
            <div class="card p-3 w-100" style="display: block;">
                <div class="form-group">
                    <label for="stud_name">Student Name As Per Aadhar : [ बच्चे का नाम आधार कार्ड पर जैसा है ]</label>
                    <input type="text" class="form-control" name="stud_name" id="stud_name"
                        placeholder="Enter student name" value="<?= show_field($aadhar_information, 'student_name') ?>">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="stud_dob">Date of Birth : [ जनम तारीख ]</label>
                        <input type="date" class="form-control" name="stud_dob" id="stud_dob" value="<?= show_field($aadhar_information, 'student_dob') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="stud_aadahr">Aadhar Number : [ आधार नंबर ]</label>
                        <input type="text" class="form-control validate-aadhar" data-user="Student" name="stud_aadahr" id="stud_aadahr"
                            placeholder="Enter Aadhar Number" min="12" max="12" value="<?= show_field($aadhar_information, 'student_aadhar_number') ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card p-3 w-100">

                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label for="father_dob">Student Father Name As Per Aadhar : [ बच्चे के अब्बा का नाम आधार कार्ड पर
                            जैसा है ]</label>
                        <input type="text" class="form-control" name="father_name" id="father_name"
                            placeholder="Enter student name" value="<?= show_field($aadhar_information, 'father_name') ?>">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="father_aadahr">Mobile Number : [ मोबाइल नंबर ]</label>
                        <input type="text" class="form-control" name="father_mobile" id="father_mobile"
                            placeholder="Enter Mobile Number" value="<?= show_field($aadhar_information, 'fat_mob_no') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="father_dob">Date of Birth : [ जनम तारीख ]</label>
                        <input type="date" class="form-control" name="father_dob" id="father_dob" value="<?= show_field($aadhar_information, 'father_dob') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="father_aadahr">Aadhar Number : [ आधार नंबर ]</label>
                        <input type="text" class="form-control validate-aadhar" data-user="Father" name="father_aadahr" id="father_aadahr"
                            placeholder="Enter Aadhar Number" min="12" max="12" value="<?= show_field($aadhar_information, 'father_aadhar_number') ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card p-3 w-100">
                <div class="form-group">
                    <label for="mother_name">Student Mother Name As Per Aadhar : [ बच्चे की माँ का नाम आधार कार्ड पर
                        जैसा है ]</label>
                    <input type="text" class="form-control" name="mother_name" id="mother_name"
                        placeholder="Enter student name" value="<?= show_field($aadhar_information, 'mother_name') ?>">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="mother_dob">Date of Birth : [ जनम तारीख ]</label>
                        <input type="date" class="form-control" name="mother_dob" id="mother_dob" value="<?= show_field($aadhar_information, 'mother_dob') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mother_aadahr">Aadhar Number : [ आधार नंबर ]</label>
                        <input type="text" class="form-control validate-aadhar" data-user="Mother" name="mother_aadahr" id="mother_aadahr"
                            placeholder="Enter Aadhar Number" min="12" max="12" value="<?= show_field($aadhar_information, 'mother_aadhar_number') ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card p-3 w-100">
                <div class="form-group m-0 text-center">
                    <button class="btn btn-md btn-success" id="submit_btn"> Submit </button>
                </div>

            </div>
        </div>
    </div>
    <?php include 'application/views/jsPages.php'; ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        $(document).ready(function() {
            $("#submit_btn").click(function(e) {
                e.preventDefault();

                // Validate 12 digit aadhar number

                const aadharInputs = document.querySelectorAll('.validate-aadhar');
                let allValid = true;

                aadharInputs.forEach(input => {
                    // Get the value of the input
                    const value = input.value.trim();
                    // Get the user type from the data attribute
                    const userType = input.getAttribute('data-user');

                    // Validate: must be 12 digits, numeric only
                    if (!/^\d{12}$/.test(value)) {
                        alert(`Invalid Aadhar number for user type: ${userType}. It must be exactly 12 digits and contain only numbers.`);
                        input.focus(); // Set focus back to the invalid input
                        allValid = false;
                        return; // Exit the loop for the current invalid input
                    }
                });

                if (!allValid)
                    return;

                // Fetch input values
                var student_id = $("#student_id").val();
                var stud_name = $("#stud_name").val();
                var stud_dob = $("#stud_dob").val();
                var stud_aadahr = $("#stud_aadahr").val();
                var father_name = $("#father_name").val();
                var father_dob = $("#father_dob").val();
                var father_aadahr = $("#father_aadahr").val();
                var father_mobile = $("#father_mobile").val();
                var mother_name = $("#mother_name").val();
                var mother_dob = $("#mother_dob").val();
                var mother_aadahr = $("#mother_aadahr").val();


                // AJAX request
                $.ajax({
                    url: "<?php echo base_url('public/student/save_aadhar_details'); ?>",
                    type: "POST",
                    data: {
                        student_id: student_id,
                        stud_name: stud_name,
                        stud_dob: stud_dob,
                        stud_aadahr: stud_aadahr,
                        father_name: father_name,
                        father_dob: father_dob,
                        father_mobile,
                        father_aadahr: father_aadahr,
                        mother_name: mother_name,
                        mother_dob: mother_dob,
                        mother_aadahr: mother_aadahr
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            alert(data.message); // Success message
                        } else {
                            alert('Error: ' + data.message); // Error message
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>