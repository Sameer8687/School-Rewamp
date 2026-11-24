<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Teacher Registration Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />

    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>

</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!--Include Sidebar -->
    <?php include 'application/views/sidebar.php'; ?>

    <!--Include Top Navbar-->
    <?php include 'application/views/topNav.php'; ?>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <form action="<?php echo base_url(); ?>Teacher/register_teacher" enctype="multipart/form-data" method="post">
            <div class="pcoded-content">
                <!-- [ breadcrumb ] start -->
                
                <!-- [ breadcrumb ] end -->
                <!-- [ Main Content ] start -->

                <div class="row margin-top-minus-40 mobile-margin-top-minus-10 mb-2">
                    <div class="col-md-7">
                        <!-- <a href="" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
                        <button class="btn btn-secondary btn-sm"> <i class="fas fa-user-tie"></i> <span id="dynamicLabelAddButton">Add Teacher</span></button>
                    </div>
                    <div class="col-md-5 student-form-search">
                        <input type="text" class="form-control form-control-sm" id="search_student_name" autocomplete="off" placeholder="Search Teacher Name">
                        <span class="left-icon"><i class="fas fa-search"></i></span>
                        <span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="pc-wizard card" id="besicwizard">
                            <ul class="nav justify-content-center card-header pb-0">
                                <li class="nav-item"><a href="#b-w-tab1" class="nav-link" data-toggle="tab"><i class="fas f-w-900 fas fa-user-tie"></i> Personal Details</a></li>
                                <li class="nav-item"><a href="#b-w-tab2" class="nav-link" data-toggle="tab"><i class="fas f-w-900 fa-user-friends"></i>Parents Details</a></li>
                                <li class="nav-item"><a href="#b-w-tab3" class="nav-link" data-toggle="tab"><i class="fas f-w-900 fa-file-alt"></i>Documents Details</a></li>
                                <li class="nav-item"><a href="#b-w-tab4" class="nav-link" data-toggle="tab"><i class="fas f-w-900 fa-medal"></i>Qualification</a></li>
                            </ul>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-xl-10 col-md-10">
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="b-w-tab1">
                                                <div class="row">
                                                    <div class="col-md-6 ">
                                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Surname<span class="text-danger">*</span> :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="surname" id="surname">
                                                            <input type="hidden" class="form-control form-control-sm col-md-7" name="student_id_row" id="student_id_row">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Teacher Name<span class="text-danger">*</span> :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" required name="student_name" id="student_name">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Father Name :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="father_name" id="father_name">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Mother Name
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="mother_name" id="mother_name">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Aadhar Number :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="aadhar_number" id="aadhar_number">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Date of Birth
                                                                :</label>
                                                            <input type="date" class="form-control form-control-sm col-md-7" name="date_of_birth" id="date_of_birth">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6 ">
                                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Teacher ID :</label>
                                                            <input type="text" value="<?= $new_id; ?>" autocomplete="off" class="form-control form-control-sm col-md-7" name="student_id" id="student_id">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Birth Place
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="birth_place" id="birth_place">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Gender :</label>
                                                            <select class="form-control form-control-sm col-md-7" name="gender" id="gender" required>
                                                                <option value="">Select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Nationality
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" value="Indian" name="nationality" id="nationality">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Religion & Cast
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" value="Muslim" name="religion" id="religion">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Mother Tongue
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" value="Urdu" name="mother_tongue" id="mother_tongue">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="b-w-tab2">
                                                <div class="row">
                                                    <div class="col-md-10 ">
                                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Father's
                                                                Qualificaion :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="father_qualification" id="father_qualification">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Mother's
                                                                Qualificaion :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="mother_qualification" id="mother_qualification">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Main Address
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="main_address" id="main_address">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Alternate Address
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="alternate_addresss" id="alternate_addresss">
                                                        </div>
                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Office Address
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="office_address" id="office_address">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="b-w-tab3">
                                                <div class="row">
                                                    <div class="col-md-10 ">
                                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-15">
                                                            <label for="" class="col-md-5 text-right">Required
                                                                Documents<span class="text-danger">*</span> :</label>
                                                            <div class="col-md-7">
                                                                <div class="form-group form-check">
                                                                    <input type="checkbox" class="form-check-input" name="doc[]" id="exampleCheck1" value="degree_certificate">
                                                                    <label class="form-check-label" for="exampleCheck1">Original Degree
                                                                        Certificate</label>
                                                                </div>
                                                                <div class="form-group form-check margin-top-minus-20 mobile-margin-top-minus-20">
                                                                    <input type="checkbox" class="form-check-input" name="doc[]" id="exampleCheck2" value="passport_photo">
                                                                    <label class="form-check-label" for="exampleCheck1">3 Passport size
                                                                        Photos</label>
                                                                </div>
                                                                <div class="form-group form-check margin-top-minus-20 mobile-margin-top-minus-20">
                                                                    <input type="checkbox" class="form-check-input" name="doc[]" id="exampleCheck3" value="aadhar">
                                                                    <label class="form-check-label" for="exampleCheck1">Aadhar Card</label>
                                                                </div>
                                                                <div class="form-group form-check margin-top-minus-20 mobile-margin-top-minus-20">
                                                                    <input type="checkbox" class="form-check-input" name="doc[]" id="exampleCheck4" value="marksheet">
                                                                    <label class="form-check-label" for="exampleCheck1">Marksheets (Final
                                                                        Semester)</label>
                                                                </div>
                                                                <div class="form-group form-check margin-top-minus-20 mobile-margin-top-minus-20">
                                                                    <input type="checkbox" class="form-check-input" name="doc[]" id="exampleCheck5" value="pan_card">
                                                                    <label class="form-check-label" for="exampleCheck1">Pan Card (if Exist)</label>
                                                                </div>
                                                                <div class="form-group form-check margin-top-minus-20 mobile-margin-top-minus-20">
                                                                    <input type="checkbox" class="form-check-input" name="doc[]" id="exampleCheck6" value="e_cert">
                                                                    <label class="form-check-label" for="exampleCheck1">Experience Certificate (if
                                                                        Exist)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row margin-top-minus-30 mobile-margin-top-minus-20">
                                                            <label for="" class="col-md-5 text-right">Upload Photo
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <input type="file" onchange="" name="student_image_control" id="student_image_control" class="form-control form-control-sm" style="padding: 0px;  border: none;">
                                                                <div class="student-image-container">
                                                                    <img src="" id="student_image_source" alt="Student Photo">
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="b-w-tab4">
                                                <div class="row">
                                                    <div class="col-md-10 ">
                                                        <div class="form-group row margin-top-minus-10 mobile-margin-top-minus-20">
                                                            <label for="" class="col-md-2 text-right">10<sup>th</sup>
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="School Name" name="ten_school" id="ten_school">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="Board Name" name="ten_board" id="ten_board">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-2" placeholder="Year" name="ten_year" id="ten_year">
                                                        </div>

                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-10">
                                                            <label for="" class="col-md-2 text-right">12<sup>th</sup>
                                                                :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="College Name" name="twe_school" id="twe_school">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="Board Name" name="twe_board" id="twe_board">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-2" placeholder="Year" name="twe_year" id="twe_year">
                                                        </div>

                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-10">
                                                            <label for="" class="col-md-2 text-right">UG :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="College Name" name="ug_school" id="ug_school">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="University Name" name="ug_board" id="ug_board">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-2" placeholder="Year" name="ug_year" id="ug_year">
                                                        </div>

                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-10">
                                                            <label for="" class="col-md-2 text-right">PG :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="College Name" name="pg_school" id="pg_school">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="University Name" name="pg_board" id="pg_board">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-2" placeholder="Year" name="pg_year" id="pg_year">
                                                        </div>

                                                        <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-10">
                                                            <label for="" class="col-md-2 text-right">PHD :</label>
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="College Name" name="phd_school" id="phd_school">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" placeholder="University Name" name="phd_board" id="phd_board">
                                                            <input type="text" autocomplete="off" class="form-control form-control-sm col-md-2" placeholder="Year" name="phd_year" id="phd_year">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [ Search List ] Start -->
                            <div class="search-list-container-panel">
                                <div class="list-group" id="dynamic_name_list"></div>
                            </div>
                            <!-- [ Search List ] End -->
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





    <!---Include JS Files-->
    <?php include 'application/views/jsPages.php'; ?>

    <script>
        $(document).ready(function() {
            $('#besicwizard').bootstrapWizard({
                withVisible: false,
                'tabClass': 'nav nav-tabs',
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
            });
            // -------------------------- Search Student Names  -----------------------------
            $('#search_student_name').keyup(function() {
                var names = $('#search_student_name').val().trim();
                if (names != '') {
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/Teacher/search_teacher_names",
                        method: "POST",
                        data: {
                            name: names
                        },
                        dataType: "JSON",
                        success: function(data) {
                            if (data != '') {
                                var html = '';
                                for (var count = 0; count < data.length; count++) {
                                    var father = ' ';
                                    var middlename = ' ';
                                    if (data[count].SPRIDEN_student_name != null) {
                                        middlename = data[count].SPRIDEN_student_name;
                                    }
                                    if (data[count].SPRIDEN_father_name != null) {
                                        father = data[count].SPRIDEN_father_name;
                                    }
                                    html += '<a id="' + data[count].SPRIDEN_student_id +
                                        '" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' +
                                        data[count].SPRIDEN_surname + " " + middlename + " " + father + '</span></a>';
                                }
                                $('#dynamic_name_list').html(html);
                                $('.search-list-container-panel').css('display', 'block');

                            } else {
                                var notAvailable =
                                    '<a href="#" style="border-radius: 0px; border:none; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action noDataOfStudent"> <span class="text-info">Data Not Available</span></a>';
                                $('#dynamic_name_list').html(notAvailable);
                            }
                        }
                    });
                } else {
                    clearFormInputIcon();
                    $('#dynamicLabelAddButton').html("Add Student");
                    $('#dynamic_name_list').html('');
                    $('.search-list-container-panel').css('display', 'none');
                }
            });

            // Get Details After Clicking on Student Names
            $(document).on('click', '.getDataOfStudent', function() {
                var names = $(this).text();
                var student_ids = $(this).attr('id');

                $('#show-list').html('');
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/Teacher/get_selected_teacher_data",
                    method: "POST",
                    data: {
                        student_id: student_ids
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data != '') {
                            //--------- Assign Value to PERONAL DETAILS text box ------ 
                            $('#student_id_row').val(data['info'].id);
                            $('#student_id').val(data['info'].SPRIDEN_student_id);
                            $('#surname').val(data['info'].SPRIDEN_surname);
                            $('#student_name').val(data['info'].SPRIDEN_student_name);
                            $('#father_name').val(data['info'].SPRIDEN_father_name);
                            $('#mother_name').val(data['info'].SPRIDEN_mother_name);
                            $('#aadhar_number').val(data['info'].SPRIDEN_aadhar_no);
                            $('#date_of_birth').val(data['info'].SPRIDEN_birthdate);
                            $('#birth_place').val(data['info'].SPRIDEN_birth_place);
                            $('#gender').val(data['info'].SPRIDEN_gender);
                            $('#nationality').val(data['info'].SPRIDEN_nationality);
                            $('#religion').val(data['info'].SPRIDEN_religion);
                            $('#mother_tongue').val(data['info'].SPRIDEN_mother_toungue);
                            $('#admission_class').val(data['info'].SPRIDEN_admission_class);
                            $('#last_school').val(data['info'].SPRIDEN_last_school);

                            //--------- Assign Value to PARENTS DETAILS text box ------
                            $('#father_qualification').val(data['info'].SPRIDEN_father_quali);
                            $('#mother_qualification').val(data['info'].SPRIDEN_mother_quali);
                            $('#main_address').val(data['info'].SPRIDEN_main_address);
                            $('#alternate_addresss').val(data['info'].SPRIDEN_alt_address);
                            $('#office_address').val(data['info'].SPRIDEN_office_add);

                            //--------- Assign Value to DOCUMENTS DETAILS ------
                            $('.student-image-container').css("display", "block");
                            var imagePath = "<?php echo base_url(); ?>Teacher_images/" + data[
                                'info'].SPRIDEN_photo_path;
                            $('#student_image_source').attr('src', imagePath);

                            //--------- Assign Value to Qualification DETAILS text box ------
                            $('#ten_school').val(data['info'].SPRIDEN_ten_school);
                            $('#ten_board').val(data['info'].SPRIDEN_ten_board);
                            $('#ten_year').val(data['info'].SPRIDEN_ten_year);
                            $('#twe_school').val(data['info'].SPRIDEN_twelve_college);
                            $('#twe_board').val(data['info'].SPRIDEN_twelve_board);
                            $('#twe_year').val(data['info'].SPRIDEN_twelve_year);
                            $('#ug_school').val(data['info'].SPRIDEN_ug_college);
                            $('#ug_board').val(data['info'].SPRIDEN_ug_uni);
                            $('#ug_year').val(data['info'].SPRIDEN_ug_year);
                            $('#pg_school').val(data['info'].SPRIDEN_pg_college);
                            $('#pg_board').val(data['info'].SPRIDEN_pg_uni);
                            $('#pg_year').val(data['info'].SPRIDEN_pg_year);
                            $('#phd_school').val(data['info'].SPRIDEN_phd_college);
                            $('#phd_board').val(data['info'].SPRIDEN_phd_uni);
                            $('#phd_year').val(data['info'].SPRIDEN_phd_year);


                            //--------- Dynamically Change The LAbels ------
                            $('#dynamicLabelAddButton').html("Update Data");
                            $('#dynamic_name_list').html('');
                            $('.search-list-container-panel').css('display', 'none');
                        } else {
                            clearFormInputIcon();
                            $('#dynamic_name_list').html('');
                            $('.search-list-container-panel').css('display', 'none');
                        }
                    }
                });
            });

            $(document).on('click', '.noDataOfStudent', function() {
                clearFormInputIcon();
                $('#search_student_name').val('');
                $('#dynamicLabelAddButton').html("Add Student");
                $('#dynamic_name_list').html('');
                $('.search-list-container-panel').css('display', 'none');
            });
        });

        function clearFormInputIcon() {
            $('#search_student_name').val('');
            //--------- Assign Value to PERONAL DETAILS text box ------ 
            $('#student_id').val('');
            $('#surname').val('');
            $('#student_name').val('');
            $('#father_name').val('');
            $('#mother_name').val('');
            $('#aadhar_number').val('');
            $('#date_of_birth').val('');
            $('#birth_place').val('');
            $('#gender').val('');
            $('#nationality').val('');
            $('#religion').val('');
            $('#mother_tongue').val('');
            $('#admission_class').val('');
            $('#last_school').val('');

            //--------- Assign Value to PARENTS DETAILS text box ------
            $('#father_qualification').val('');
            $('#mother_qualification').val('');
            $('#main_address').val('');
            $('#alternate_addresss').val('');
            $('#office_address').val('');

            //--------- Assign Value to DOCUMENTS DETAILS ------
            $('.student-image-container').css("display", "none");
            $('#student_image_source').attr('src', "");

            //--------- Assign Value to FEES DETAILS text box ------
            $('#fees_type').val('');
            $('#fees_details').val('');

            //--------- Dynamically Change The LAbels ------
            $('#dynamicLabelAddButton').html("Add Student");
            $('#dynamic_name_list').html('');
            $('.search-list-container-panel').css('display', 'none');
        }
    </script>
</body>

</html>
