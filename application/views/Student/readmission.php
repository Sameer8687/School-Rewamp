<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student Re-Admission Form - Olive English School</title>
	<?php include 'application/views/cssPages.php'; ?>
	<link rel="icon" href="<?php echo base_url(); ?>images/fav.png" type="image/x-icon">
	<!-- Boxicons CSS -->
	<link href='https://unpkg.com/boxicons@2.0.8/css/boxicons.min.css' rel='stylesheet'>
	<style>
		.card {
			box-sizing: border-box;
			border: 1px solid #fff !important;
		}

		.card.form {
			border: 1px solid #77777750;
		}

		.table tr td {
			padding-top: 6px !important;
			padding-bottom: 6px !important;
		}

		.form-control.cust-border-bottom {
			border: none !important;
			border-bottom: 1px solid #999 !important;
			border-radius: 0px !important;
			padding-left: 0px !important;
			font-weight: 500;
		}

		.form-control.with-border {
			height: calc(1em + 1.15rem + 1px);
			padding: 0.55rem 1rem;
			text-align: center;
			font-weight: 500;
			border-color: #999;
		}

		.form-control.with-border:focus {
			border-color: #777 !important;
		}

		body {
			background-image: url('<?= base_url(); ?>images/bg.jpg');
			background-size: cover;
			background-attachment: fixed;
			background-position: center;
			background-repeat: no-repeat;
		}

		@media print {
			.not-visible {
				visibility: hidden;
			}
		}
	</style>
</head>

<body>

	<form action="" id="submit" method="POST">
		<div class="container mt-2 not-visible">
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="<?= base_url(); ?>" class="btn btn-sm btn-outline-secondary"> <i class='bx bxs-home'></i> Home</a>
					<button class="btn btn-sm btn-outline-warning" type="button" onclick="window.print();"> <i class='bx bxs-printer'></i> Print</button>
					<button class="submitForm btn btn-sm btn-success" id="load"> <i class='bx bxs-edit-alt'></i> Submit</button>
				</div>
			</div>
		</div>
		<div class="container mt-2">
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
										<h3 id="acdetail" class="text-primary">Olive English School - Re-Admission Form <?= $academic_details['stvacdm_year'] ?></h3>
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

										<span class="GR_no font-weight-bold" id="GR_no">GR.Number <span class="float-right">:</span>
										</span>

									</td>

									<td style="border: none; width: 29%; vertical-align: middle; padding:0px !important;" class="text-left form-control form-control-sm cust-border-bottom" id="Gr_number">

										<input name="gr_number" autocomplete="off" id="gr_number" type="text" class="form-control form-control-sm cust-border-bottom" style="width: 29%;">

									</td>
								</tr>
								<!-- --------------------------------------------------------- -->
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Academic Year <span class="float-right">:</span> </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important; display: flex;" class="text-center">
										<div class="select-box" style="width: 65%;">
											<select name="acdm_year" id="acdm_year" onchange="handleChange(event)" class="form-control form-control-sm cust-border-bottom" style="width: 60% !important;">
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
										</div>

										<div class="back-entry" style="width: 35%;">
										</div>
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Students List<span class="float-right">:</span> </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input placeholder="Select Student Name" autocomplete="off" list="student_list_search_box" name="student_list_search" id="student_list_search" class="form-control form-control-sm cust-border-bottom">
										<datalist id="student_list_search_box">
											<?php foreach ($all_students as $student) { ?>
												<option value="<?= $student['id'] ?>"><?= $student['name'] ?></option>
											<?php } ?>
										</datalist>
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Student ID (For Office Use Only) <span class="float-right">:</span> </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input type="text" autocomplete="off" name="office_id" id="office_id" class="form-control form-control-sm cust-border-bottom">
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Student ID (System Generated) <span class="float-right">:</span> </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input type="text" name="student_id" readonly id="student_id" class="form-control form-control-sm cust-border-bottom" style="background-color: transparent;">
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Student Aadhar Number <span class="float-right">:</span> </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input name="aadhar_number" autocomplete="off" id="aadhar_number" type="text" class="form-control form-control-sm cust-border-bottom">
									</td>
								</tr>
							</table>
							<table class="table stu-det">
								<!--First Row-->
								<tr>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Student Name</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Father Name</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Surname</span>
									</td>
								</tr>
								<tr>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="student_name" id="student_name" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="father_name" id="father_name" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="surname" id="surname" type="text" class="form-control with-border" required>
									</td>
								</tr>
								<!--Second Row-->
								<tr>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Mother Name</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Date of Birth</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Class</span>
									</td>
								</tr>
								<tr>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="mother_name" id="mother_name" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="date_of_birth" id="date_of_birth" type="date" class="form-control with-border" required>
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
										<span class="font-weight-bold">Nationality</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Religion</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Mother Tongue</span>
									</td>
								</tr>
								<tr>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" value="Indian" name="nationality" id="nationality" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" value="Muslim" name="religion" id="religion" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" value="Urdu" name="mother_tongue" id="mother_tongue" type="text" class="form-control with-border" required>
									</td>
								</tr>
								<!--Fourth Row-->
								<tr>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Caste</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Sub Caste</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Place of Birth</span>
									</td>
								</tr>
								<tr>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="caste" id="caste" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="sub_caste" id="sub_caste" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="birth_place" id="birth_place" type="text" class="form-control with-border" required>
									</td>
								</tr>
								<!--Fifth Row-->
								<tr>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Taluka</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">District</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">State</span>
									</td>
								</tr>
								<tr>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" value="Dhule" name="taluka" id="taluka" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="district" value="Dhule" id="district" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" value="Maharashtra" name="state" id="state" type="text" class="form-control with-border" required>
									</td>
								</tr>
								<tr>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Gender</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Whatsapp</span>
									</td>
									<td style="border: none;  text-align:center;">
										<!-- <span class="font-weight-bold">Fees</span> -->
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
										<input autocomplete="off" name="whatsapp_number" id="whatsapp_number" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<!-- <input autocomplete="off" placeholder="Enter Fees Amount" name="fees_paid" id="fees_paid" type="text" class="form-control with-border" required> -->
									</td>
								</tr>
								<!-- <tr>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Due Date</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Fees Balance</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Payment Mode <span class="text-danger">*</span> </span>
									</td>
								</tr>
								<tr>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="due_date_of_fees" id="due_date_of_fees" type="date" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<input autocomplete="off" name="pre_fees_balance" id="pre_fees_balance" type="text" class="form-control with-border" required>
									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;" class="text-center">
										<select class="form-control with-border" name="payment_mode" id="payment_mode" style="padding-top: 2px;">
											<option value="">Select </option>
											<option selected value="cash">Cash</option>
											<option value="online">Online</option>
										</select>
									</td>
								</tr>
											-->
							</table>
							<table class="table head" style="margin-top: 0px;">
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Previous School (For Office Use Only) : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input type="text" name="last_school" id="last_school" class="form-control form-control-sm cust-border-bottom">
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Parent's Mobile Number : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input type="text" name="parents_mobile" id="parents_mobile" class="form-control form-control-sm cust-border-bottom" required>
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Permanent Residence Address : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<textarea name="main_address" id="main_address" class="form-control form-control-sm cust-border-bottom" rows="3"> </textarea>
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Date of Admission : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input type="text" value="<?= date('d-M-Y'); ?>" name="dot" id="dot" class="form-control form-control-sm cust-border-bottom">
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Admission Place : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input type="text" value="Dhule" name="ad_place" id="ad_place" class="form-control form-control-sm cust-border-bottom">
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Notes : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-center">
										<input type="text" autocomplete="off" name="admission_note" id="admission_note" class="form-control form-control-sm cust-border-bottom" list="note-list">
										<datalist id="note-list">
											<option value="Yes, Agreed to pay balance fees"></option>
											<option value="Want to exumption in fees"></option>
										</datalist>
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">RTE Student : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-left">
										<input type="radio" name="rte_student" id="rte_student_yes" value="Yes"> Yes
										<input checked type="radio" name="rte_student" id="rte_student_no" value="No"> No
									</td>
								</tr>
								<!-- <tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Main Documents : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-left">
										<input checked type="radio" name="main_docs" id="main_docs_birth" value="Yes"> Birth Certificate
										<input type="radio" name="main_docs" id="main_docs_lc" value="No"> Leaving Certificate
									</td>
								</tr>
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Other Documents : </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-left">
										<input type="checkbox" name="student_aadhar" value="Yes"> Student Aadhar Card <br>
										<input type="checkbox" name="father_aadhar" value="Yes"> Father Aadhar Card <br>
										<input type="checkbox" name="passport_photo" value="Yes"> 3 Passport Size Photo <br>
									</td>
								</tr> -->
								<tr>
									<td style="border: none; width: 25%;">
										<span class="font-weight-bold">Previous Fees Details: </span>
									</td>
									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;" class="text-left">
										<table style="width:100%; border-collapse: collapse;">
											<thead>
												<tr>
													<th style="width: 10%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">#</th>
													<th style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">Class</th>
													<th style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">Total Fees</th>
													<th style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">Fees Paid</th>
													<th style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">Exumption</th>
													<th style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">Bal. Fees</th>
													<th style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">Academic Year</th>
												</tr>
											</thead>
											<tbody id="tbody">

											</tbody>
										</table>
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
										<span class="font-weight-bold">Clerk Signature</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Parent Signature</span>
									</td>
									<td style="border: none;  text-align:center;">
										<span class="font-weight-bold">Principal Signature</span>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<?php include 'application/views/jsPages.php'; ?>
	<script>
		$('#acdm_year').change(function() {
			var acdm = $('#acdm_year option:selected').html();
			$.ajax({
				url: "<?= base_url(); ?>index.php/Student/genearetID?academic-year=" + acdm,
				success: function(data) {
					if (data != "fail") {
						$('#student_id').val(data);
					} else {
						$('#id-alert-row').slideDown();
					}
				}
			});
		});

		$('#student_list_search').change(function() {
			var id = $(this).val();
			var acdm_year = $('#acdm_year').val();
			$.ajax({
				url: "<?= base_url() ?>index.php/Student/fetchReadmissionDetails?student-id=" + id + "&acdm-year=" + acdm_year,
				method: "GET",
				dataType: "JSON",
				success: function(res) {
					let to_fill_last = 0;
					let last_acdm = 5;
					if (res['student_details'] != null) {
						// Student Details
						$('#student_name').val(res['student_details'].SPRIDEN_student_name);
						$('#father_name').val(res['student_details'].SPRIDEN_father_name);
						$('#surname').val(res['student_details'].SPRIDEN_surname);
						$('#mother_name').val(res['student_details'].SPRIDEN_mother_name);
						$('#date_of_birth').val(res['student_details'].SPRIDEN_birthdate);
						$('#caste').val(res['student_details'].cast);
						$('#sub_caste').val(res['student_details'].sub_cast);
						$('#birth_place').val(res['student_details'].SPRIDEN_birth_place);
						$('#taluka').val(res['student_details'].taluka);
						$('#district').val(res['student_details'].district);
						$('#state').val(res['student_details'].state);
						$('#gender').val(res['student_details'].SPRIDEN_gender);
						$('#student_id').val(res['student_details'].SPRIDEN_student_id);
						$('#aadhar_number').val(res['student_details'].SPRIDEN_aadhar_no);
						$('#whatsapp_number').val(res['student_details'].stvpcnf_whatsapp);
						// Class Details
						$('#admission_class').val(res['class_details'].STVCLAS_standard);
						// Fees Paid Details

						var html = '';
						var style = '';
						if (res['fees_details'] != null) {
							for (var loop = 0; loop < res['fees_details'].length; loop++) {
								if (res['fees_details'][loop].balance > 0) {
									style = "color: red;";
								} else {
									style = '';
								}
								if (res['fees_details'][loop].academic_year == last_acdm)
									to_fill_last = res['fees_details'][loop].final_balance;
								html += `<tr>
									<td style="width: 10%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">${loop + 1}</td>
									<td style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;"> ${res['fees_details'][loop].sprfees_standard} </td>
									<td style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">${res['fees_details'][loop].class_fees}</td>
									<td style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">${res['fees_details'][loop].total_paid}</td>
									<td style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">${res['fees_details'][loop].total_exumpted}</td>
									<td style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px; ${style}">${res['fees_details'][loop].final_balance}</td>
									<td style="width: 15%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">${res['fees_details'][loop].stvacdm_year}</td>
								</tr>`;
							}
						} else {
							html += '<tr><td style="width: 100%; text-align: center; padding: 3px; font-weight: normal; text-transform: capitalize; border: 1px solid #77777750; font-size: 12px;">No Data Available</td></tr>';
						}
						//if (to_fill_last > 0)
							// document.getElementById("pre_fees_balance").value = 0;
						// document.getElementById("pre_fees_balance").value = to_fill_last;
						//else
							// document.getElementById("pre_fees_balance").value = 0;
						$('#tbody').html(html);
					}
				}
			});
		})
	</script>
	<script>
		// $('#submit').on("submit", function(event) {
		// 	event.preventDefault();
		// 	$.ajax({
		// 		url: "<?= base_url() ?>student/addReadmission",
		// 		method: "POST",
		// 		data: new FormData(this),
		// 		contentType: false,
		// 		processData: false,
		// 		dataType: "JSON",
		// 		beforeSend: function() {
		// 			$('#load').attr("disabled", true);
		// 			$('#load').html('<i class="bx bx-loader-alt bx-spin"></i> Saving Data ...');
		// 		},
		// 		success: function(data) {
		// 			$('#load').html('Save Data');
		// 			$('#load').attr("disabled", false);
		// 			if (data == "fail") {
		// 				alert("Failed to Save Data");
		// 			} else {
		// 				alert("data saved succesfully!!");

		// 			}
		// 		}
		// 	});
		// });


		$(document).ready(function() {
			$('#submit').on('keypress', function(event) {
				if (event.key === 'Enter') {
					event.preventDefault();
				}
			});
			$('#submit').on("submit", function(event) {
				// event.preventDefault();
				$.ajax({
					url: "<?= base_url() ?>student/addReadmission",
					method: "POST",
					data: new FormData(this),
					contentType: false,
					processData: false,
					dataType: "JSON",
					beforeSend: function() {
						$('#load').attr("disabled", true);
						$('#load').html('<i class="bx bx-loader-alt bx-spin"></i> Saving Data ...');
					},
					success: function(data) {
						$('#load').html('Save Data');
						$('#load').attr("disabled", false);
						if (data == "fail") {
							alert("Failed to Save Data");
						} else {
							alert("data saved succesfully!!");

						}
					}
				});
			});
		});
	</script>
	<script>
		let log = document.getElementById('acdetail');

		function handleChange(e) {
			let innerText = e.target[e.target.options.selectedIndex].innerText;
			let value = e.target.value;

			log.innerHTML = `<table class="table" style="border: none;">
    <tr><td style="border: none;"></td><td style="border: none; vertical-align: middle;" class="text-center"><h3 id="acdetail" class="text-primary">Olive English School - Re-Admission Form ${innerText} </h3></td></tr>
  </table>`;
		}
	</script>
</body>

</html>