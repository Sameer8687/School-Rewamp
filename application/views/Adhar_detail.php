<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Adhar details</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>StudAdhar_css/Adhar.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>StudAdhar_css/normalize.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>StudAdhar_css/style.css">

</head>

<body style="background-color: #eaeaea;">
    <form id="" action="<?= base_url() ?>Data/Student_Adhar" method="POST" enctype="multipart/form-data">
        <nav class="navbar p-0">
            <div class="header p-0 text-center">
                <div class="container-fluid">
                    <h4 class="navbar-headding">
                        <img src="<?php echo base_url(); ?>images/logoNewDevWhite.png" class="login-logo" alt="">

                        Olive English School
                    </h4>
                </div>
            </div>
        </nav>
        <div class="container">

            <div class="row">
                <div class="col-sm-2 pts-1">
                    <label for="staticEmail" class="form-label">Student ID <span>:</span></label>
                </div>
                <div class="col-sm-2">
                    <input type="text" readonly class="form-control form-control-sm" id="Stud_id"
                        value="<?= $profile['SPRIDEN_student_id']; ?>" readonly>
                </div>
                <div class="col-sm-2">
                    <label for="inputPassword" class="form-label">Student Name<span>:</span></label>
                </div>
                <div class="col-sm-5">
                    <input type="text" class="form-control form-control-sm" id="Name" value="<?= $profile['name'] ?>"
                        readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <label for="inputPassword" class="col-form-label">Class<span>:</span></label>
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control form-control-sm" id="class" value="<?= $profile['class'] ?>"
                        readonly>
                </div>
                <div class="col-md-1">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Division<span>:</span></label>
                </div>
                <div class="col-sm-1">
                    <input type="text" class="form-control form-control-sm" id="Division"
                        value="<?= $profile['division']; ?>" readonly>
                </div>
            </div>
            <input type="hidden" value="<?= $profile["id"] ?>" name="user_id" id="user_id">
            <div class="border-up"></div>
            <div class="border-down"></div>
            <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->
            <fieldset>

                <div class="row">
                    <div class="col-sm-3">
                        <label for="exampleFormControlInput1" class="form-label">Adhar Namber</label>
                        <span>(आधार नंबर)</span>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class=" form-control form-control-sm" id="aadhar_number" name="aadhar_number"
                            placeholder="" value="<?= $record["aadhar_number"] ?>">
                        <span id="lblError" class="error"></span>
                        <hr />
                        <!-- <input type="button" id="btnSubmit" value="Submit" onclick="ValidateAadhaar()" /> -->
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Student Name as per Adhar</label>
                        <span>(छात्र का नाम आधार के अनुसार)</span>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" name="name_on_aadhar" id="name_on_aadhar"
                            placeholder="" value="<?= $record["name_on_aadhar"] ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Date Of Birth</label>
                        <span>(जन्म की तारीख)</span>
                    </div>
                    <div class="col-sm-9">
                        <input type="date" class="form-control form-control-sm" name="date_of_birth" id="date_of_birth"
                            placeholder="" value="<?= $record["date_of_birth"] ?>">
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Adhar Card Front Image</label>
                        <span>(आधार कार्ड सामने का फोटो)</span>
                    </div>

                    <div class="col-sm-9 ">
                        <input class="form-control form-control-sm" name="aadhar_front_img" id="aadhar_front_img"
                            type="file" value="<?= $record["aadhar_front_img"] ?>">
                        <span>Photo size maximum: 1 Mb</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Adhar Card Back Image</label>
                        <span>(आधार कार्ड पीछे का फोटो)</span>
                    </div>

                    <div class="col-sm-9 ">
                        <input class="form-control form-control-sm" name="aadhar_back_img" id="aadhar_back_img" type="file"
                            value="<?= $record["aadhar_back_img"] ?>">
                        <span>Photo size maximum: 1 Mb</span>
                    </div>
                </div>
                <!-- <br> -->
                <?php if ($submit_enabled) : ?>
                    <button type="submit" class="btn btn-success" onclick="ValidateAadhaar()">Submit</button>
                <?php endif; ?>

            </fieldset>
        </div>
    </form>

</body>

<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>
</body>
<script type="text/javascript">
    function ValidateAadhaar() {
        var aadhaar = document.getElementById("aadhar_number").value;
        var lblError = document.getElementById("lblError");
        lblError.innerHTML = "";
        var expr = /^([0-9]{4}[0-9]{4}[0-9]{4}$)|([0-9]{4}\s[0-9]{4}\s[0-9]{4}$)|([0-9]{4}-[0-9]{4}-[0-9]{4}$)/;
        if (!expr.test(aadhaar)) {
            lblError.innerHTML = "Invalid Aadhaar Number";
        }
    }
</script>
<script>
    $('#form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url() ?>index.php/Data/Student_Adhar',
            method: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'JSON',
            beforeSend: function() {
                $('#save-btn').html("<i class='bx bx-loader bx-spin'></i> Please Wait ...");
            },
            success: function(res) {
                $('#save-btn').html('<i class="fa-solid fa-floppy-disk"></i> Save');
                if (res['msg'] == 'fail') {
                    alert("Failed to Save Request");
                } else {
                    alert(`Request ${res['msg']} Successfully ..`);
                    if (res['msg'] == 'saved')
                        window.location = res['path'];
                    else if (res['msg'] == 'updated')
                        window.location.reload();

                }
            }
        })
    });
</script>

</html>