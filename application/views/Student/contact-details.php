  <!DOCTYPE html>
  <html lang="en">

  <head>
  	<meta charset="UTF-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<title>Student Contact Details</title>
  	<?php include 'application/views/cssPages.php' ?>
  	<link href="https://fonts.googleapis.com/css2?family=Dongle:wght@300&display=swap" rel="stylesheet">
  	<style>
  		/* Extra small devices (phones, 600px and down) */
  		@media only screen and (max-width: 600px) {
  			.container {
  				padding: 0;
  			}
  		}

  		/* Small devices (portrait tablets and large phones, 600px and up) */
  		@media only screen and (min-width: 600px) {}

  		/* Medium devices (landscape tablets, 768px and up) */
  		@media only screen and (min-width: 768px) {}

  		/* Large devices (laptops/desktops, 992px and up) */
  		@media only screen and (min-width: 992px) {}

  		/* Extra large devices (large laptops and desktops, 1200px and up) */
  		@media only screen and (min-width: 1200px) {}

  		/* Custom CSS */
  		* {
  			font-family: 'Dongle', sans-serif;
  		}

  		body {
  			background-image: url('<?= base_url(); ?>images/bg.jpg');
  			background-size: cover;
  			background-attachment: fixed;
  			background-position: center;
  			background-repeat: no-repeat;
  		}

  		.table tr td.no-padding {
  			padding: 0px !important;
  		}

  		.header {
  			display: flex;
  			justify-content: center;
  			text-align: center;
  			align-items: center;
  		}

  		.header img {
  			height: 50px;
  			margin-top: -10px;
  		}

  		.header h1 {
  			margin-left: 20px;
  			color: #36632b;
  			font-weight: bold;
  		}

  		.header h3 {
  			margin: 0;
  			padding: 0;
  			margin-top: -20px;
  		}

  		.table {
  			border: none !important;
  		}

  		.table-details tr td,
  		.form-control,
  		label,
  		button,
  		.alert span {
  			font-size: 18px !important;
  		}

  		.alert {
  			padding: 5px;
  		}

  		label {
  			font-weight: bold;
  		}

  		label span {
  			float: right;
  		}

  		.table-details tr td {
  			padding: 2px !important;
  			border: none !important;
  		}

  		.table-details tr td.no-padding {
  			padding: 0px;
  		}

  		.table-details tr td:nth-child(odd) {
  			width: 30%;
  		}

  		.table-details tr td:nth-child(even) {
  			width: 70%;
  		}
  	</style>

  </head>

  <body>

  	<div class="container">
  		<div class="row">
  			<div class="col-md-12 main-col">
  				<form action="<?= base_url('student/add_student_details'); ?>" method="post">
  					<div class="card">
  						<div class="card-body">
  							<div class="row">
  								<div class="col-md-12 p-0 m-0">
  									<div class="header" style="margin-top: -10px;">
  										<img src="<?= base_url('images/logoNewDev.png') ?>" alt="">
  										<div class="info">
  											<h1>Olive English School</h1>
  											<h3 class="font-weight-bold">Student Contact Information Form</h3>
  										</div>
  									</div>
  									<hr>
  								</div>
  								<?php if ($this->session->userdata('msg') != '') { ?>
  									<div class="col-md-12">
  										<div class="alert alert-success">
  											<span><?= $this->session->userdata('msg') ?></span>
  										</div>
  									</div>
  								<?php } ?>
  								<div class="col-md-12">
  									<table class="table table-sm table-borderless table-details">
  										<tr>
  											<td><label for="">Class </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<select class="form-control form-control-sm with-border get-student-list" name="admission_class" id="admission_class" style="padding-top: 2px;" required>
  													<option value="0">Select Class</option>
  													<?php
														foreach ($classes as $class) {
														?>
  														<option value="<?php echo $class['stvfees_class']; ?>">
  															<?php echo $class['stvfees_class']; ?></option>
  													<?php } ?>
  												</select>
  											</td>
  										</tr>
  										<!--<tr>-->
  										<!--	<td><label for="">Division </label> <span class="float-right">:</span></td>-->
  										<!--	<td class="no-padding">-->
  										<!--		<select class="form-control form-control-sm with-border get-student-list" name="division" id="division" style="padding-top: 2px;" required>-->
  										<!--			<option value="0">Select Division</option>-->
  										<!--			<option value="A">A</option>-->
  										<!--			<option value="B">B</option>-->
  										<!--<option value="C">C</option>-->
  										<!--		</select>-->
  										<!--	</td>-->
  										<!--</tr>-->
  										<tr>
  											<td><label for=""> Name </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<select class="form-control form-control-sm with-border" name="student_name" id="student_name" style="padding-top: 2px;" required>
  													<option value="">Select</option>
  												</select>
  											</td>
  										</tr>
  									</table>
  									<hr>
  									<table class="table table-sm table-borderless table-details">
  										<tr>
  											<td><label for=""> ID </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" required class="form-control form-control-sm" name="student_id" id="student_id" readonly>
  												<input type="hidden" class="form-control form-control-sm" name="stu_id" id="stu_id">
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Surname </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" required class="form-control form-control-sm" name="surname" id="surname">
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Name </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" required class="form-control form-control-sm" name="stu_name" id="stu_name">
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Father Name </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" required class="form-control form-control-sm" name="father_name" id="father_name">
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Class </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" class="form-control form-control-sm" name="class" id="class" readonly>
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Mobile No 1</label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" class="form-control form-control-sm" name="mobile_no" id="mobile_no">
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Mobile No 2</label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" class="form-control form-control-sm" name="mobile_no2" id="mobile_no2">
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Whatsapp </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<input type="text" class="form-control form-control-sm" name="whatsapp_no" id="whatsapp_no">
  											</td>
  										</tr>
  										<tr>
  											<td><label for=""> Address </label> <span class="float-right">:</span></td>
  											<td class="no-padding">
  												<textarea cols="3" class="form-control form-control-sm" name="address" id="address"></textarea>
  											</td>
  										</tr>
  										<tr>
  											<td></td>
  											<td class="no-padding">
  												<button type="submit" class="btn btn-sm btn-success mt-2">Submit</button>
  											</td>
  										</tr>
  									</table>
  								</div>
  							</div>
  						</div>
  					</div>
  				</form>
  			</div>
  		</div>
  	</div>
  	<?php include 'application/views/jsPages.php'; ?>

  	<script>
  		$(document).on('change', '.get-student-list', function() {
  			var std = $('#admission_class option:selected').val();
  			if (std != 0) {
  				// 
  				$.ajax({
  					url: "<?= base_url('student/getStudentList') ?>?class=" + std,
  					method: "GET",
  					dataType: "JSON",
  					success: function(data) {
  						if (data != null) {
  							var html = '<option value="0">Select Student</option>';
  							for (var count = 0; count < data.length; count++) {
  								var class_name = '';
  								if (data[count].phone != null) {
  									class_name = '#A5D6A7';
  								} else {
  									class_name = '';
  								}
  								html += '<option style="background-color: ' + class_name + '"; value="' + data[count].id + '">' + data[count].name + ' <span style="color: red;">( ' + data[count].class + ' ) - '+data[count].spriden_student_status+'</span> </option>';
  							}
  							
  							$('#student_name').html(html);
  						} else {
  							alert("Student Not Available");
  						}
  					}
  				});
  			} else {
  				alert("Select Class");
  			}
  		});

  		$('#student_name').change(function() {
  			var sid = $('#student_name option:selected').val();
  			$.ajax({
  				url: "<?= base_url('student/getStudentDetailsToUpdate') ?>?student-id=" + sid,
  				method: "GET",
  				dataType: "JSON",
  				success: function(data) {
  					if (data != null) {
  						$('#student_id').val(data.SPRIDEN_student_id);
  						$('#stu_id').val(data.id);
  						$('#surname').val(data.SPRIDEN_surname);
  						$('#stu_name').val(data.SPRIDEN_student_name);
  						$('#father_name').val(data.SPRIDEN_father_name);
  						$('#class').val(data.class);
  						$('#mobile_no').val(data.phone);
  						$('#mobile_no2').val(data.phone2);
  						$('#whatsapp_no').val(data.whatsapp);
  						$('#address').val(data.address);
  					} else {
  						alert("Data Not Available");
  					}
  				}
  			});
  		});
  	</script>
  </body>

  </html>
