<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Olive English School - Update Student Details</title>
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
		<form id="form_data_data" action="<?php echo base_url(); ?>Student/update_student_details_by_form"
			enctype="multipart/form-data" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->
				<?php include 'application/views/Student/links.php'; ?>
				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->
				<div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">
					<div class="col-md-8">
						<a href="<?= base_url('Student/student_list') ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>
						<button class="btn btn-secondary btn-sm"> <i class="fas fa-user-graduate"></i> <span
								id="dynamicLabelAddButton">Update Details</span></button> </div>
					<div class="col-md-3 student-form-search">
						<input type="text" class="form-control form-control-sm" id="search_student_name"
							placeholder="Enter Student ID" autocomplete="off">
						<span class="left-icon"><i class="fas fa-search"></i></span>
					</div>
					<div class="col-md-1">
						<button class="btn btn-sm btn-outline-secondary" type="button" id="findStudent">Find</button>
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
								<li class="nav-item active"><a href="#b-w-tab1" class="nav-link " data-toggle="tab"><i
											class="fas f-w-900 fa-user-graduate"></i> Personal Details</a></li>
								<li class="nav-item"><a href="#b-w-tab2" class="nav-link" data-toggle="tab"><i
											class="fas f-w-900 fa-user-friends"></i> Parents Details</a></li>
							</ul>
							<div class="card-body">
								<div class="row justify-content-center">
									<div class="col-xl-12 col-md-12">
										<div class="tab-content">
											<div class="tab-pane active show" id="b-w-tab1">
												<div class="row">
													<div class="col-md-6">
														<div
															class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">GR Number<span class="text-danger">*</span> <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="gr_number" id="gr_number" value="<?= $personal_details['spriden_gr_number'] ?>">
															<input type="hidden" name="hidden_acdm_year" id="hidden_acdm_year" value="<?= $acdm_year ?>">
															<input type="hidden" value="<?= $student_std; ?>" id="hidden_std">
															<input type="hidden" value="<?= $lc_no['lc_no']; ?>"id="hidden_lc_number">
															<input type="hidden" value="<?= $record['id'] ?>" id="spr_id" name="spr_id">
															<input type="hidden" name="date" id="hiddendate"
																value="<?= !empty($record['lc_date']) ? date('d-m-Y', strtotime($record['lc_date'])) : '' ?>" class="form-control form-control-sm " style="width: 150px;">
															<?php $stud_type = ($student_std == 'XI' || $student_std == 'XII') ? 'jc' : 'school'; ?>
															<input type="hidden" value="<?= $stud_type; ?>" id="hidden_stud_type">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Surname<span class="text-danger">*</span> <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="surname" id="surname" value="<?= $personal_details['SPRIDEN_surname'] ?>">
															<input type="hidden" class="form-control form-control-sm col-md-7" name="hidden_student_id" id="hidden_student_id" value="<?= $personal_details['id'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Student Name<span class="text-danger">*</span> <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="student_name" id="student_name" value="<?= $personal_details['SPRIDEN_student_name'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Father Name<span class="text-danger">*</span> <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="father_name" id="father_name" value="<?= $personal_details['SPRIDEN_father_name'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Mother Name<span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="mother_name" id="mother_name" value="<?= $personal_details['SPRIDEN_mother_name'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left"> Student Aadhar Number<span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="aadhar_number" id="aadhar_number" value="<?= $personal_details['SPRIDEN_aadhar_no'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Student Name as per Aadhar<span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="student_name_aadhar" id="student_name_aadhar" value="<?= $personal_details['student_name_aadhar'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Father Aadhar No.<span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="father_aadhar_no" id="father_aadhar_no" value="<?= $personal_details['father_aadhar_no'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Father Name as per Aadhar<span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="father_name_aadhar" id="father_name_aadhar" value="<?= $personal_details['father_name_aadhar'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Mother Aadhar No<span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="mother_aadhar_no" id="mother_aadhar_no" value="<?= $personal_details['mother_aadhar_no'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Mother Name as per Aadhar<span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="mother_name_aadhar"  id="mother_name_aadhar" value="<?= $personal_details['mother_name_aadhar'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Mobile Number 1 <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="mobile_number1" id="mobile_number1" value="<?= $contact_details['stvpcnf_mobile'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Mobile Number 2 <span class="float-right">:</span></label>
															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-6"
																name="mobile_number2" id="mobile_number2"
																value="<?= $contact_details['stvpcnf_mobile2'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Place of Birth<span class="float-right">:</span></label>
															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-6"
																name="place_of_birth" id="place_of_birth"
																value="<?= $personal_details['SPRIDEN_birth_place'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Date of Birth <span class="float-right">:</span></label>
															<input type="date" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="dat_of_birth" id="dat_of_birth"
																value="<?= $personal_details['SPRIDEN_birthdate'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Gender<span class="float-right">:</span></label>
															<select class="form-control form-control-sm col-md-4" name="gender" id="gender">
																<?php if ($personal_details['SPRIDEN_gender'] == "Male") {
																	$isMale = "selected";
																	$isFemale = "";
																} else if ($personal_details['SPRIDEN_gender'] == "Female") {
																	$isFemale = "selected";
																	$isMale = "";
																} else {
																	$isFemale = "";
																	$isMale = "";
																}
																?>
																<option value="">Select Gender</option>
																<option <?= $isMale; ?> value="Male">Male</option>
																<option <?= $isFemale; ?> value="Female">Female</option>
															</select>
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Admission Class <span class="float-right">:</span></label>
															<select class="form-control form-control-sm col-md-4" name="admission_class" id="admission_class" style="padding-top: 2px;" required>
																<option value="">Select Class</option>
																<?php foreach ($classes as $class) { ?>
																	<option
																		<?= $class['stvfees_class'] == $personal_details['SPRIDEN_admission_class'] ? 'selected' : ''; ?>
																		value="<?= $class['stvfees_class']; ?>">
																		<?= $class['stvfees_class']; ?>
																	</option>
																<?php } ?>
															</select>
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Admission Date <span class="float-right">:</span></label>
															<input type="date" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="admission_date" id="admission_date"
																value="<?= $personal_details['SPRIDEN_date_created'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Current Class <span class="float-right">:</span></label>
															<select class="form-control form-control-sm col-md-4" name="current_class" id="current_class" style="padding-top: 2px;" required>
																<option value="">Select Class</option>
																<?php foreach ($classes as $class) { ?>
																	<option
																		<?= $class_details['STVCLAS_standard'] == $class['stvfees_class'] ? "selected" : ''; ?>
																		value="<?php echo $class['stvfees_class']; ?>">
																		<?php echo $class['stvfees_class']; ?>
																	</option>
																<?php } ?>
															</select>
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">RTE Status <span class="float-right">:</span></label>
															<select class="form-control form-control-sm col-md-4" name="rte_status" id="rte_status" style="padding-top: 2px;" required>
																<option value="">Select Status</option>
																<option <?= $personal_details['rte_status'] == 'Yes' ? 'selected' : ''; ?> value="Yes">Yes</option>
																<option <?= $personal_details['rte_status'] == 'No' ? 'selected' : ''; ?> value="No">No</option>
															</select>
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Since When <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="since_when" id="since_when" value="<?= $personal_details['since_when'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Leaving Date <span class="float-right">:</span></label>
															<input type="date" autocomplete="off" class="form-control form-control-sm col-md-4" name="leaving_date" id="leaving_date" value="<?= $personal_details['leaving_date'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">LC Date <span class="float-right">:</span></label>
															<input type="date" autocomplete="off" class="form-control form-control-sm col-md-4" name="lc_date" id="lc_date" value="<?= $personal_details['lc_date'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Leaving Reason <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="leaving_reason" id="leaving_reason" value="<?= $personal_details['leaving_reason'] ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">College GR
																Nunber<span class="text-danger">*</span> <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="spriden_college_gr" id="spriden_college_gr" value="<?= $personal_details['spriden_college_gr'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Reg. Number<span
																	class="text-danger">*</span> <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6"  required name="SPRIDEN_reg_no" id="SPRIDEN_reg_no" value="<?= $personal_details['SPRIDEN_reg_number'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">PEN Number <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6"  required name="SPRIDEN_pan_no" id="SPRIDEN_pan_no" value="<?= $personal_details['SPRIDEN_pan_no'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Apaar No <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" required name="SPRIDEN_apaar_no" id="SPRIDEN_apaar_no" value="<?= $personal_details['SPRIDEN_apaar_no'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Student ID <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-4" name="student_id" readonly id="student_id" value="<?= $personal_details['SPRIDEN_student_id'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Previous School
																<span class="float-right">:</span></label>
															<textarea rows="2" class="form-control form-control-sm col-md-6" name="pre_school" id="pre_school"><?= $personal_details['SPRIDEN_last_school'] ?></textarea>
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Previous College
																<span class="float-right">:</span></label>
															<textarea rows="2" class="form-control form-control-sm col-md-6" name="previos_college" id="previos_college"><?= $personal_details['previos_college'] ?></textarea>
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15" style="margin-top: -18px;">
															<label for="" class="col-md-4 text-left">Nationality <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="nationality" id="nationality" value="<?= $personal_details['SPRIDEN_nationality'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Religion <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="religion" id="religion" value="<?= $personal_details['SPRIDEN_religion'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Whatsapp <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="whatsapp_number" id="whatsapp_number" value="<?= $contact_details['stvpcnf_whatsapp'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">SMS Number <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="sms_number" id="sms_number" value="<?= $contact_details['stvpncf_sms_no'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Mother Tongue <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="mother_tongue" id="mother_tongue" value="<?= $personal_details['SPRIDEN_mother_toungue'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">DOB (words)<span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="birthdate_words" id="birthdate_words" value="<?= $personal_details['birthdate_words'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Caste <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="caste" id="caste" value="<?= $personal_details['cast'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Sub Caste <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="sub_caste" id="sub_caste" value="<?= $personal_details['sub_cast'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Taluka <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="taluka" id="taluka" value="<?= $personal_details['taluka'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">District <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="district" id="district" value="<?= $personal_details['district'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">State <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="state" id="state" value="<?= $personal_details['state'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Height <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="height" id="height" value="<?= $personal_details['height'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Weight <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="weight" id="weight" value="<?= $personal_details['weight'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Blood Group <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="SPRIDEN_BGRP" id="SPRIDEN_BGRP" value="<?= $personal_details['SPRIDEN_BGRP'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Progress <span
																	class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="progress" id="progress" value="<?= $personal_details['progress'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Conduct <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="conduct" id="conduct" value="<?= $personal_details['conduct'] ?>">
														</div>
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Remarks <span class="float-right">:</span></label>
															<input type="text" autocomplete="off" class="form-control form-control-sm col-md-6" name="remark" id="remark" value="<?= $personal_details['remark'] ?>">
														</div>
														<?php if ($personal_details['lc_status'] == 'Issued'): ?>
															<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
																<label for="" class="col-md-4 text-left">LC Status <span
																		class="float-right">:</span></label>
																<input type="text" readonly autocomplete="off"
																	class="form-control form-control-sm col-md-6 text-danger"
																	name="remarks" id="remarks"
																	value="<?= $personal_details['lc_status'] ?>">
</div>
														<?php else: ?>
															<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
																<label for="" class="col-md-4 text-left">L.C. Issue <span
																		class="float-right">:</span></label>
																<div class="col-md-7 d-flex">
																	<div class="w-50">
																		<input type="radio" autocomplete="off"
																			class="update-book-issue" name="book_issues"
																			id="issue-lc" value="Yes"> Yes
																		<input value="No" type="radio" autocomplete="off"
																			class="update-book-issue" name="book_issues"
																			id="book_issues_no"> No <br>
																		<small class="font-weight-bold text-danger"
																			id="card-loader-small"></small>
																	</div>
																</div>
															</div>
															<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
																<label for="" class="col-md-4 text-left"><span
																		class="float-right">:</span></label>
																<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-6"
																name="remarks" id="remarks"
																value="<?= $personal_details['remarks'] ?>">
															</div>
														<?php endif; ?>
													</div>
												</div>
											</div>

											<div class="tab-pane " id="b-w-tab2">
												<div class="row">
													<div class="col-md-10 ">
														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Father's
																Qualificaion <span class="float-right">:</span></label>
															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="father_qualification" id="father_qualification"
																value="<?= $personal_details['SPRIDEN_father_quali'] ?>">
														</div>

														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 text-left">Mother's
																Qualificaion <span class="float-right">:</span></label>
															<input type="text" autocomplete="off"
																class="form-control form-control-sm col-md-4"
																name="mother_qualification" id="mother_qualification"
																value="<?= $personal_details['SPRIDEN_mother_quali'] ?>">
														</div>

														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
															<label for="" class="col-md-4 float-left">Main Address
																<span class="float-right">:</span></label>
															<textarea class="form-control form-control-sm col-md-7"
																rows="3" cols="200" name="main_address"
																id="main_address"><?= $personal_details['SPRIDEN_main_address'] ?></textarea>
														</div>

														<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15"
															style="margin-top: -15px;">
															<label for="" class="col-md-4 float-left">Alternate Address
																<span class="float-right">:</span></label>
															<textarea class="form-control form-control-sm col-md-7"
																rows="3" cols="200" name="alternate_address"
																id="alternate_address"><?= $personal_details['SPRIDEN_alt_address'] ?></textarea>
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
					<style>
						#id-alert-row {
							display: none;
						}
					</style>
					<div class="row" id="id-alert-row">
						<div class="col-md-12">
							<div class="card pb-0 mb-0" style="background-color: #99999910;">
								<div class="card-body p-3 mb-0 pb-0">
									<p class="pb-0 mb-0" id="id-alert-text">Please Set Academic year First !!!</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<!-- [ Main Content ] end -->
	</div>
	<!-- [ Main Content ] end -->
	</div>
	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$('#findStudent').click(function () {
			var id = $('#search_student_name').val().trim();
			if (id != '') {
				$.ajax({
					url: "<?= base_url('Student/StudentUniqueId') ?>?id=" + id,
					method: "GET",
					dataType: "JSON",
					success: function (data) {
						if (data != null) {
							window.location = '<?= base_url('student/update_details/') ?>' + data.id;
						} else {
							alert("Data Not Available");
						}
					}
				});
			}
		});
	</script>
	<!-- Kamran --><!-- Kamran  implement-->
	<script>
		$('#issue-lc').click(function () {
			if (confirm("Do you want to issue LC?")) {
				$.ajax({
					url: '<?= base_url("index.php/Report/save_lc_details") ?>',
					method: "POST",
					data: {
						lc_number: $('#hidden_lc_number').val(),
						student_id: $('#spr_id').val(),
						date_issued: $('#hiddendate').val(),
						standard: $('#hidden_std').val(),
						stud_type: $('#hidden_stud_type').val(),
						acdm: $('#hidden_acdm_year').val()
					},
					beforeSend: function () {
						$("#card-loader-small").html(`<i class="fas fa-spinner fa-spin"></i> Please Wait`);
						$('#issue-lc').prop('disabled', true);
					},
					success: function (res) {
						$("#card-loader-small").html("");
						$('#issue-lc').prop('disabled', false);

						switch (res) {
							case 'success':
								alert("LC Details Saved Successfully");
								break;
							case 'lc_exists':
								alert("LC already exists for this student");
						}
					},
					error: function () {
						$("#card-loader-small").html("");
						$('#issue-lc').prop('disabled', false);
						alert("Network error occurred");
					}
				});
			}
		});
	</script>
	<!-- Kamran  implement-->
</body>

</html>