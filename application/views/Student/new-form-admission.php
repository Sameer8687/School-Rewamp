<!DOCTYPE html>

<html lang="en">



<head>

	<meta charset="UTF-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Student Admission Form - Olive English School</title>

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



	<form action="<?= base_url('Student/add_student_new_data') ?>" id="new-form-submit" method="POST">

		<div class="container mt-2 not-visible">

			<div class="row">

				<div class="col-md-12 text-right">

					<a href="<?= base_url(); ?>" class="btn btn-sm btn-outline-secondary"> <i class='bx bxs-home'></i>
						Home</a>

					<button class="btn btn-sm btn-outline-warning" type="button" onclick="window.print();"> <i
							class='bx bxs-printer'></i> Print</button>

					<button class="btn btn-sm btn-success" id="load"> <i class='bx bxs-edit-alt'></i> Submit</button>

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

										<img src="<?= base_url('images/logoNewDev.png') ?>" class="img-fluid"
											style="height: 50px;" alt="">

									</td>

									<td style="border: none; vertical-align: middle;" class="text-center">

										<h3 id="acdetail" class="text-primary">Olive English School - Admission Form
											&nbsp; <?= $academic_details['stvacdm_year'] ?></h3>

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

										<span class="GR_no font-weight-bold" id="GR_no">GR.Number <span
												class="float-right">:</span>
										</span>

									</td>

									<td style="border: none; width: 29%; vertical-align: middle; padding:0px !important;"
										class="text-left form-control form-control-sm cust-border-bottom"
										id="Gr_number">

										<input name="gr_number" autocomplete="off" id="gr_number" type="text"
											class="form-control form-control-sm cust-border-bottom" style="width: 29%;">

									</td>


								</tr>
								<!-- --------------------------------------------------------- -->
								<!-- <td style="border: none; width: 25%;">

										<h3 id="acdetail" class="text-primary">Olive English School - Admission Form
											<?= $academic_details['stvacdm_year'] ?></h3>

									</td> -->

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Academic Year <span class="float-right">:</span>
										</span>

									</td>


									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important; display: flex;"
										class="text-center">

										<div class="select-box d-flex justify-content-bewteen align-items-center"
											style="width: 65%;">

											<select name="acdm_year" id="acdm_year" onchange="handleChange(event)"
												class="form-control form-control-sm cust-border-bottom"
												style="width: 50% !important;">

												<option value="">Select Academic Year</option>

												<?php foreach ($academic_years as $academic_year) {

													if ($academic_details['stvacdm_id'] == $academic_year['stvacdm_id']) {

														$selected = 'selected';
													} else {

														$selected = '';
													}

													?>

													<option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>">
														<?= $academic_year['stvacdm_year']; ?>
													</option>

												<?php } ?>

											</select>

											<div style="width: 50%; text-wrap: wrap;">
												<small class="text-danger font-weight-bold">Please Check the Academic
													Year Before Saving.</small>
											</div>

										</div>



										<div class="back-entry" style="width: 35%;">

											<input type="radio" name="back_entry" id="back_entry" value="back"> <span
												class="text-danger">Back Entry</span>

											<input type="radio" name="back_entry" checked id="new_entry" value="new">
											New Entry

										</div>

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Student ID (For Office Use Only) <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" autocomplete="off" name="office_id" id="office_id"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Student ID (System Generated) <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="student_id" readonly id="student_id"
											class="form-control form-control-sm cust-border-bottom"
											style="background-color: transparent;">

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

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required name="student_name" id="student_name"
											type="text" class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required name="father_name" id="father_name"
											type="text" class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required name="surname" id="surname" type="text"
											class="form-control with-border">

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

										<span class="font-weight-bold">Admission Class</span>

									</td>

								</tr>

								<tr>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required name="mother_name" id="mother_name"
											type="text" class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required name="date_of_birth" id="date_of_birth"
											type="date" class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<select class="form-control with-border" name="admission_class"
											id="admission_class" style="padding-top: 2px;" required>

											<option value="">Select Class</option>

											<?php

											foreach ($classes as $class) {

												?>

												<option value="<?php echo $class['stvfees_class']; ?>">

													<?php echo $class['stvfees_class']; ?>
												</option>

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

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required value="Indian" name="nationality"
											id="nationality" type="text" class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required value="Islam" name="religion" id="religion"
											type="text" class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" required value="Urdu" name="mother_tongue"
											id="mother_tongue" type="text" class="form-control with-border">

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

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="caste" id="caste" type="text"
											class="form-control with-border" required>

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="sub_caste" id="sub_caste" type="text"
											class="form-control with-border" required>

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="birth_place" id="birth_place" type="text"
											class="form-control with-border" required>

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

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" value="Dhule" name="taluka" id="taluka" type="text"
											class="form-control with-border" required>

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="district" value="Dhule" id="district"
											type="text" class="form-control with-border" required>

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" value="Maharashtra" name="state" id="state"
											type="text" class="form-control with-border" required>

									</td>

								</tr>

								<tr>

									<td style="border: none;  text-align:center;">

										<span class="font-weight-bold">Gender</span>

									</td>
									<td style="border: none;  text-align:center;">

										<span class="font-weight-bold">Height <small class="text-danger">(in Inches in
												this format 5.11 or 5.8)</small></span>

									</td>
									<td style="border: none;  text-align:center;">

										<span class="font-weight-bold">Weight</span>

									</td>


								</tr>

								<tr>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<select class="form-control with-border" name="gender" id="gender"
											style="padding-top: 2px;" required>

											<option value="">Select Gender</option>

											<option value="Male">Male</option>

											<option value="Female">Female</option>

										</select>

									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="height" id="height" type="text"
											class="form-control with-border" required>

									</td>
									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="weight" id="weight" type="text"
											class="form-control with-border" required>

									</td>


								</tr>
								<tr>


									<td style="border: none;  text-align:center;">

										<span class="font-weight-bold">Blood Group</span>

									</td>
									<td style="border: none;  text-align:center;">

										<span class="font-weight-bold">Whatsapp</span>

									</td>


								</tr>

								<tr>
									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<select class="form-control with-border" name="blood_group" id="blood_group"
											style="padding-top: 2px;" required>

											<option value="">Select Blood Group</option>
											<option value="A+">A+</option>
											<option value="A-">A-</option>
											<option value="B+">B+</option>
											<option value="B-">B-</option>
											<option value="AB+">AB+</option>
											<option value="AB-">AB-</option>
											<option value="O+">O+</option>
											<option value="O-">O-</option>
										</select>

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="whatsapp_number" id="whatsapp_number"
											type="text" class="form-control with-border" required>

									</td>


								</tr>

							</table>



							<table class="table head" style="margin-top: 0px;">
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-info">Student Aadhar Number <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input name="aadhar_number" autocomplete="off" required id="aadhar_number"
											type="text" class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-info">Student Name as per Aadhar <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input name="student_name_as_per_aadhar" autocomplete="off" required
											id="student_name_as_per_aadhar" type="text"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-info">Father Aadhar No. <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input name="father_aadhar_number" autocomplete="off" required
											id="father_aadhar_number" type="text"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-info">Father Name as per Aadhar <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input name="father_name_as_per_aadhar" autocomplete="off" required
											id="father_name_as_per_aadhar" type="text"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-info">Mother Aadhar No <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input name="mother_aadhar_no" autocomplete="off" required id="mother_aadhar_no"
											type="text" class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-info">Mother Name as per Aadhar <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input name="mother_name_as_per_aadhar" autocomplete="off" required
											id="mother_name_as_per_aadhar" type="text"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-primary">PEN No <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="SPRIDEN_pan_no" autocomplete="off" required
											id="SPRIDEN_pan_no" class="form-control form-control-sm cust-border-bottom">

									</td>
								</tr>
								
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold text-primary">Apaar No <span
												class="float-right">:</span> </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="SPRIDEN_apaar_no" autocomplete="off" required
											id="SPRIDEN_apaar_no"
											class="form-control form-control-sm cust-border-bottom">

									</td>
								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Previous School (For Office Use Only) : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="last_school" id="last_school"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Active Mobile Number : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="parents_mobile" id="parents_mobile"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Permanent Residence Address : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<textarea name="main_address" id="main_address"
											class="form-control form-control-sm cust-border-bottom"
											rows="3"> </textarea>

									</td>

								</tr>
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Address Landmark : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="address_landmark" id="address_landmark"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>
								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Nearby Masjid : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="nearby_masjid" id="nearby_masjid"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Date of Admission : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" value="<?= date('d-M-Y'); ?>" name="dot" id="dot"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Admission Place : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" value="Dhule" name="ad_place" id="ad_place"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Notes : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-center">

										<input type="text" name="admission_note" id="admission_note"
											class="form-control form-control-sm cust-border-bottom">

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">RTE Student : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-left">

										<input type="radio" name="rte_student" id="rte_student_yes" value="Yes"> Yes

										<input checked type="radio" name="rte_student" id="rte_student_no" value="No">
										No

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Main Documents : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-left">

										<input checked type="radio" name="main_docs" id="main_docs_birth" value="Yes">
										Birth Certificate

										<input type="radio" name="main_docs" id="main_docs_lc" value="No"> Leaving
										Certificate

									</td>

								</tr>

								<tr>

									<td style="border: none; width: 25%;">

										<span class="font-weight-bold">Other Documents : </span>

									</td>

									<td style="border: none; width: 75%; vertical-align: middle; padding:0px !important;"
										class="text-left">
										<div class="d-flex justify-content-between align-items-start">
											<!-- Document Checkboxes -->
											<div>
												<input type="checkbox" name="student_aadhar" value="Yes"> Student Aadhar
												Card<br>
												<input type="checkbox" name="father_aadhar" value="Yes"> Father Aadhar
												Card<br>
												<input type="checkbox" name="passport_photo" value="Yes"> 3 Passport
												Size Photo<br>
											</div>

											<!-- Student Photo Upload & Preview -->
											<div style="margin-left: 20px; text-align: center;">
												<label for="studentPhotoInput" style="font-weight: bold;">Student
													Photo</label><br>
												<input type="file" id="studentPhotoInput" name="userfile"
													accept="image/*" style="margin-top: 5px;" required><br>
												<img id="studentPhotoPreview" src="" alt="Photo Preview"
													style="margin-top:10px; display:none; max-width:100px; max-height:100px; border-radius:5px;"><br>
												<button type="button" id="removePhotoBtn"
													style="display:none; margin-top:5px;"
													class="btn btn-sm btn-danger">Remove</button>
											</div>
										</div>
									</td>


								</tr>

							</table>

							<table class="table stu-det" style="margin-top: 100px;">

								<tr>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="clerk" id="clerk" type="text"
											class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="parent_sign" id="parent_sign" type="text"
											class="form-control with-border">

									</td>

									<td style="border: none;  padding:0px !important; margin:5px !important;"
										class="text-center">

										<input autocomplete="off" name="princi" id="princi" type="text"
											class="form-control with-border">

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
		$('#acdm_year').change(function () {

			var acdm = $('#acdm_year option:selected').html();

			$.ajax({

				url: "<?= base_url(); ?>index.php/Student/genearetID?academic-year=" + acdm,

				success: function (data) {

					if (data != "fail") {

						$('#student_id').val(data);

					} else {

						$('#id-alert-row').slideDown();

					}

				}

			});

		});



		$(document).ready(function () {

			$.ajax({

				url: "<?= base_url(); ?>index.php/Student/genearetID?academic-year=0",

				success: function (data) {

					if (data != "fail") {

						$('#student_id').val(data);

					} else {

						$('#id-alert-row').slideDown();

					}

				}

			});

		});
	</script>
	<script>
		let log = document.getElementById('acdetail');

		function handleChange(e) {
			let innerText = e.target[e.target.options.selectedIndex].innerText;
			let value = e.target.value;

			log.innerHTML = `<table class="table" style="border: none;">
								<tr>
									<td style="border: none;"></td><td style="border: none; vertical-align: middle;" class="text-center">
										<h3 id="acdetail" class="text-primary">Olive English School - Admission Form ${innerText}</h3>
									</td>
								</tr>
							  </table>`;
		}
	</script>

	<script>
		// $('#new-form-submit').on('submit', function() {

		// 	$('#load').attr("disabled", true);

		// });

		$(document).ready(function () {

			$('#new-form-submit').on('keypress', function (event) {
				if (event.key === 'Enter') {
					event.preventDefault();
				}
			});
		});
	</script>


	<script>
		// updated script after mazhar sir requirements 
		document.getElementById('studentPhotoInput').addEventListener('change', function (event) {
			const preview = document.getElementById('studentPhotoPreview');
			const removeBtn = document.getElementById('removePhotoBtn');
			const file = event.target.files[0];

			if (file) {
				const reader = new FileReader();
				reader.onload = function (e) {
					preview.src = e.target.result;
					preview.style.display = 'block';
					removeBtn.style.display = 'inline-block';
				};
				reader.readAsDataURL(file);
			}
		});

		document.getElementById('removePhotoBtn').addEventListener('click', function () {
			const input = document.getElementById('studentPhotoInput');
			const preview = document.getElementById('studentPhotoPreview');
			input.value = '';
			preview.src = '';
			preview.style.display = 'none';
			this.style.display = 'none';
		});
	</script>

</body>



</html>