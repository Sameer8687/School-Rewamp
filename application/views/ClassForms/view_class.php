<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Olive English School - Student Books & Result Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />

    <!---Include CSS Files-->
    <?php include 'application/views/cssPages.php'; ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css">

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
        <form action="" id="classCreateForm" method="post">
            <div class="pcoded-content">
                <!-- [ breadcrumb ] start -->
                <?php include 'application/views/ClassForms/links.php'; ?>
                <!-- [ breadcrumb ] end -->
                <!-- [ Main Content ] start -->

                <div class="row margin-top-minus-40">
                    <div class="col-md-12">
                        <form action="<?php echo base_url(); ?>class" method="post">
                            <div class="card">

                                <div class="card-header pt-3 pb-0">
                                    <div class="row pb-0">
                                        <div class="col-md-4 ">
                                            <div class="form-group pb-0 row">
                                                <label for="" class="col-md-6 text">Standard :</label>
                                                <select required class="form-control form-control-sm col-md-6"
                                                    name="class" id="class" onchange="getStudentForClass()">
                                                    <option selected disabled value="">Select Standard</option>
                                                    <?php
                                                    foreach ($classes as $class) {
                                                    ?>
                                                    <option value="<?php echo $class['stvfees_class']; ?>">
                                                        <?php echo $class['stvfees_class']; ?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 ">
                                            <div class="form-group pb-0 row">
                                                <label for="" class="col-md-5">Division :</label>
                                                <select class="form-control form-control-sm col-md-7" name="divi"
                                                    id="divi" onchange="getStudentForClass()">
                                                    <option selected value="">Select Division</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-secondary btn-sm" id="findClassButton"
                                                style="border-radius: 50px;">View Class</button>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row margin-top-minus-20">
                                                <?php
                                                if ($action != "no") {
                                                    if ($dataforClass != null) {
                                                        foreach ($dataforClass as $stu_data) {
                                                ?>
                                                <div class="col-md-4">
                                                    <div class="card student-id-card mb-2">
                                                        <div class="card-header">
                                                            <div class="header-row">
                                                                <div class="img-area text-center">
                                                                    <img src="<?php echo base_url(); ?>images/logoNewDevWhite.png"
                                                                        alt="">
                                                                </div>
                                                                <div class="info-area text-center">
                                                                    <h6 class="school-name">
                                                                        Olive English School
                                                                    </h6>
                                                                    <h6 class="address">
                                                                        Behind Traffic Police Station, NH3 Dhule
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="student-card-profile-container">
                                                                <div class="student-img-container text-center">
                                                                    <div class="img-frame text-center">
                                                                        <?php
                                                                                    if ($stu_data['SPRIDEN_photo_path'] != "") {
                                                                                        $imagePath = base_url() . 'Student_Images/' . $stu_data['SPRIDEN_photo_path'];
                                                                                    } else {
                                                                                        $imagePath = base_url() . 'images/user.png';
                                                                                    }
                                                                                    ?>
                                                                        <img src="<?php echo $imagePath; ?>" alt="">
                                                                    </div>
                                                                    <div class="contact-container mt-2">
                                                                        <a href="" class="icon-container-icon">
                                                                            <i class="fas fa-phone-volume call"></i>
                                                                        </a>
                                                                        <a href="" class="icon-container-icon">
                                                                            <i class="fab fa-whatsapp wha"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="student-information-container">
                                                                    <table class="my-card-table">
                                                                        <tr>
                                                                            <td class="text-right">ID :</td>
                                                                            <td class="cust-pad">
                                                                                <?= $stu_data['SPRIDEN_student_id']; ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-right">Name :</td>
                                                                            <td class="cust-pad">
                                                                                <?= $stu_data['SPRIDEN_surname'] . " " . $stu_data['SPRIDEN_student_name']; ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-right">Father Name :</td>
                                                                            <td class="cust-pad">
                                                                                <?= $stu_data['SPRIDEN_father_name']; ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-right">Class :</td>
                                                                            <td class="cust-pad">
                                                                                <?= $stu_data['STVCLAS_standard'] . " - " . $stu_data['STVCLAS_class']; ?>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }
                                                    } else {
                                                        ?>
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body text-center p-2 pt-3">
                                                            <h4 class="text-info" style="font-size: 14px;">No Data
                                                                Available</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
        var action = '<?php echo $action; ?>';
        if (action != "no") {
            $('#class').val('<?php echo $newStd; ?>');
            $('#divi').val('<?php echo $newDiv; ?>');
        }
    });
    </script>

</body>

</html>