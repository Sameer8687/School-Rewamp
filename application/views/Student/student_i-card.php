<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

    <title>Olive English School - Student Image Upload Form</title>

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

                        <!-- <button type="submit" class="btn btn-sm btn-secondary mb-1">Save Data</button> -->

                    </div>

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-header pt-0 pb-0 mt-0 mb-0">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <h5 class="pt-2">Students I-Card</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-sm btn-secondary mb-1 float-right">Save Data</button>
                                    </div>
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

                                                <button id="find-students" type="button" class="btn btn-sm btn-outline-secondary">View Class</button>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group row">

                                            <label for="" class="col-md-4">Stud Name <span class="float-right">:</span> </label>

                                            <input type="text" name="std_name" id="std_name" class="col-md-6 form-control form-control-sm get-students" value="" list="stud_icard" />

                                            <datalist id="stud_icard">

                                                <option value=""></option>

                                                <?php foreach ($list as $record) { ?>

                                                    <option value="<?= $record['name']; ?>" label="<?= $record['id']; ?>"><?= $record['name'] ?></option>

                                                <?php } ?>

                                            </datalist>

                                        </div>

                                    </div>

                                    <div class="col-md-6"></div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-4">Name <span class="float-right">:</span> </label>

                                            <input type="text" class="col-md-6 form-control form-control-sm" name="stud_name" id="stud_name" value="" />

                                            <input type="hidden" class="form-control form-control-sm" name="stud_id" id="stud_id" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-4">School <span class="float-right">:</span> </label>

                                            <input type="text" class="col-md-6 form-control form-control-sm" name="school_name" id="school_name" value="Olive English School Dhule 424001" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-3">Std <span class="float-right">:</span> </label>

                                            <input type="text" class="col-md-3 form-control form-control-sm" name="stud_std" id="stud_std" value="" />

                                            <label for="" class="col-md-3">Div <span class="float-right">:</span> </label>

                                            <input type="text" class="col-md-3 form-control form-control-sm" name="stud_div" id="stud_div" value="" />

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group row">

                                            <label for="" class="col-md-4">Image <span class="float-right">:</span> </label>

                                            <input required type="file" class="col-md-6 form-control form-control-sm p-0" name="files[]" id="files[]">

                                        </div>

                                    </div>

                                </div>



                            </div>

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

            window.location = base_url + 'student/i_card/' + std + '/' + div;



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

                            $('#stud_id').val(data.id);

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