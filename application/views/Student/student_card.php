<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Student I-Card Form</title>

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

        <form action="<?php echo base_url(); ?>Student/add_image" method="post" enctype="multipart/form-data">

            <div class="pcoded-content">

                <div class="row margin-top-minus-50">

                    <?php if ($this->session->userdata('msg') != '') { ?>

                        <div class="col-md-12">

                            <div class="alert alert-info">

                                <?= $this->session->userdata('msg'); ?>

                            </div>

                        </div>

                    <?php } ?>

                    <div class="col-md-12 mb-1">



                    </div>

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-header pt-0 pb-0 mt-0 mb-0">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2">Students I-Card</h5>
                                    </div>
                                    <div class="col-md-6"><button type="submit" class="btn btn-sm btn-secondary float-right">Save Data</button></div>
                                </div>


                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-4">Class <span class="float-right">:</span> </label>

                                            <select class="form-control form-control-sm col-md-6 find-students" name="admission_class" id="admission_class" required>

                                                <option value="0">Select Class</option>

                                                <?php

                                                foreach ($classes as $class) {

                                                ?>

                                                    <option <?= $std_selected == $class['stvfees_class'] ? 'selected' : ''; ?> value="<?php echo $class['stvfees_class']; ?>">

                                                        <?php echo $class['stvfees_class']; ?></option>

                                                <?php } ?>

                                            </select>

                                            <input type="hidden" name="total_students" id="total_students">

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-4">Division <span class="float-right">:</span> </label>

                                            <select class="form-control form-control-sm col-md-6 find-students" name="division" id="division" required>

                                                <option value="0">Select Division</option>

                                                <option <?= $div_selected == 'A' ? 'selected' : ''; ?> value="A">A</option>

                                                <option <?= $div_selected == 'B' ? 'selected' : ''; ?> value="B">B</option>

                                                <option <?= $div_selected == 'C' ?  'selected' : ''; ?> value="C">C</option>

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <div class="col-md-12">

                                                <button id="find-students" type="button" class="btn btn-sm btn-outline-secondary btn_click">View Class</button>

                                                <a type="button" id="generate" class="btn btn-sm btn-outline-secondary" href="javascript:void(0)"><i class="fas fa-file-pdf text-danger"></i> PDF View</a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <style>
                                .itext {

                                    font-size: 12px;

                                    padding: 2px;

                                }



                                .mt {

                                    margin-top: -3px;

                                }



                                .hero-image {

                                    background-image: url("<?= base_url() ?>images/bg-design.png");

                                    background-color: #cccccc;

                                    background-position: center;

                                    background-repeat: no-repeat;

                                    background-size: cover;

                                    position: relative;

                                    border: 1px solid #77777750;

                                    width: 85mm;

                                    height: 54mm;

                                }



                                .box {

                                    display: flex;

                                    justify-content: center;

                                    align-items: center;

                                    width: 100%;

                                    height: 50%;

                                    border: 1px solid #77777750;

                                }



                                .heading {

                                    /* color: #476f3d; */

                                    color: #fff;

                                    font-weight: bold;

                                    margin-left: 50px;

                                }



                                .footer {

                                    font-size: 10px;

                                    color: #fff;

                                    margin-left: -15px;

                                }



                                .img-stu {

                                    width: 100%;

                                    object-fit: fill;

                                }
                            </style>

                            <?php $i = 1;

                            foreach ($list as $record) { ?>

                                <div class="col-md-4">

                                    <div class="row">

                                        <div class="col-md-2">

                                            <input type="checkbox" name="icard" id="icard" class="form-check-input ads_Checkbox" value="<?= $record['id'] ?>">

                                        </div>

                                    </div>

                                    <div class="card hero-image">

                                        <hr style="padding:0; margin: 0; margin-bottom: 15px;">

                                        <div class="col-md-12" style="margin-top: 30px;">

                                            <div class="form-group row">

                                                <div class="col-md-3">

                                                    <div class="box">

                                                        <label for=""><img src="<?= base_url('stu_img_icard/' . $record['stvicard_stu_img']) ?>" class="img-fluid" alt=""> </label>

                                                    </div>

                                                </div>

                                                <div class="col-md-9 row">

                                                    <label for="" class="col-md-4 itext">Stud Id <span class="float-right">:</span></label>

                                                    <label for="" class="col-md-8 itext"> <?= $record['SPRIDEN_student_id'] ?></label>

                                                    <label for="" class="col-md-4 itext" style="margin-top: -15px;">Name <span class="float-right">:</span></label>

                                                    <label for="" class="col-md-8 itext" style="margin-top: -15px;"> <?= $record['name'] ?></label>

                                                    <label for="" class="col-md-4 itext" style="margin-top: -15px;">Father <span class="float-right">:</span></label>

                                                    <label for="" class="col-md-8 itext" style="margin-top: -15px;"> <?= $record['father_name'] ?></label>

                                                    <label for="" class="col-md-4 itext" style="margin-top: -15px;">Class <span class="float-right">:</span></label>

                                                    <label for="" class="col-md-8 itext" style="margin-top: -15px;"> <?= $record['STVCLAS_standard'] ?> </label>

                                                    <label for="" class="col-md-4 itext" style="margin-top: -15px;">Mobile <span class="float-right">:</span></label>

                                                    <label for="" class="col-md-8 itext" style="margin-top: -15px;"> <?= $record['stvpcnf_mobile'] ?></label>

                                                </div>

                                            </div>

                                        </div>



                                    </div>

                                </div>

                            <?php $i++;
                            } ?>

                        </div>

                    </div>

                </div>

            </div>

        </form>

        <!-- [ Main Content ] end -->

    </div>











    <!---Include JS Files-->

    <?php include 'application/views/jsPages.php'; ?>





    <script>
        document.getElementById("find-students").addEventListener('click', async () => {

            var html = '';

            let std = document.getElementById("admission_class").value;

            let div = document.getElementById("division").value;

            let base_url = '<?= base_url(); ?>';

            window.location = base_url + 'student/student_card/' + std + '/' + div;



        });
    </script>

    <script>
        $(document).ready(function() {

            $("#generate").click(function() {

                var test = new Array();

                let std = document.getElementById("admission_class").value;

                let div = document.getElementById("division").value;

                $("input[name='icard']:checked").each(function() {

                    test.push($(this).val());

                });

                if (test.length > 0) {

                    $.ajax({

                        url: '<?= base_url('index.php/Student/select_card/') ?>',

                        method: "POST",

                        data: {

                            lists: test,

                            std: std,

                            div: div

                        },

                        success: function(data) {

                            window.open(data);

                        }

                    });

                } else {



                    let base_url = '<?= base_url(); ?>';

                    window.open(base_url + 'student/icard/' + std + '/' + div);

                }







            });

        });
    </script>

    <script>
        $('#std_name').change(function() {

            const Value = document.querySelector('#std_name').value;

            if (!Value) return;

            const Text = document.querySelector('option[value="' + Value + '"]').label;

            document.getElementById("stud_id").value = Text;

        });

        $(document).on('change', '.get-students', function() {

            let id = document.getElementById("stud_id").value;

            if (id != 0) {

                $.ajax({

                    url: '<?= base_url('index.php/student/student_icard_dt/') ?>?id=' + id,

                    method: "GET",

                    dataType: "JSON",

                    success: function(data) {

                        if (data != null) {

                            var html = '';

                            $('#stud_id').val(data.SPRIDEN_student_id);

                            $('#stud_name').val(data.name);

                            $('#stud_std').val(data.STVCLAS_standard);

                            $('#stud_div').val(data.STVCLAS_class)

                        }

                    }

                });

            } else {

                alert("Select Class & Division");

            }

        });
    </script>

</body>



</html>