<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Teacher Home | Olive English School</title>
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />


	<?php include 'application/views/cssPages.php'; ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/teacher-module.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/new-db.css">
</head>

<body class="">
	<!-- [ Pre-loader ] start -->
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<!-- [ Pre-loader ] End -->
	<?php include 'sidebar.php'; ?>
	<!-- [ Header ] start -->
	<?php include 'topNav.php'; ?>
	<!-- [ Header ] end -->

	<!-- [ Main Content ] start -->
	<div class="pc-container">
		<div class="pcoded-content">
			<div class="row top-row">
				<div class="col-md-12 top-header">
					<!-- <div class="card user-card user-card-1 olive-card">

						<div class="card-body pt-0">
							<div class="user-about-block text-center">
								<div class="row align-items-end">
									<div class="col"></div>
									<div class="col"><img class="img-radius img-fluid wid-90" src="<?= base_url(); ?>images/user.png" alt="User image"></div>
									<div class="col"></div>
								</div>
							</div>
							<div class="text-center">
								<h6 class="mb-1 mt-3 olive-title-color">
									<?= $userData['SPRIDEN_surname'] . " " . $userData['SPRIDEN_student_name'] . " " . $userData['SPRIDEN_father_name']; ?>
								</h6>
								<p class="mb-3 text-muted" style="font-weight: bold; color: #4DB6AC !important;">
									<?php
									if (!$allocated_class == null) {
									?>
										<?= $allocated_class['SPATESD_std'] . " - " . $allocated_class['SPATESD_div']; ?>

									<?php } ?>
								</p>
								<a href="<?= base_url(); ?>index.php/Dashboard/logout" class="btn btn-sm btn-olive"> <i class="fas fa-sign-out-alt"></i> Logout</a>
							</div>
							<hr class="wid-80 b-wid-3 my-4">
							<div class="row text-center label-row">
								<div class="col">
									<h6 class="mb-1"><?= $TotalCount; ?></h6>
									<p class="mb-0">Students</p>
								</div>
								<div class="col">
									<h6 class="mb-1"><?= $total_pending; ?></h6>
									<p class="mb-0">Pending Remarks</p>
								</div>
								<div class="col">
									<h6 class="mb-1"><?= $total_replied; ?></h6>
									<p class="mb-0">Remarks Replied</p>
								</div>
							</div>
						</div>
					</div> -->
					<div class="card user-card user-card-2 shape-center olive-card" style="background-color: #E0F2F1;">
						<div class="card-header border-0 p-2 pb-0">
							<div class="cover-img-block">
								<img src="<?= base_url(); ?>images/olive1.jpg" alt="" class="img-fluid">
							</div>
						</div>
						<div class="card-body pt-0">
							<div class="user-about-block text-center">
								<div class="row align-items-end">
									<div class="col text-left pb-3"></div>
									<div class="col">
										<div class="position-relative d-inline-block">
											<img class="img-radius img-fluid wid-90" style="margin-top: -15px;" src="<?= base_url(); ?>images/user.png" alt="User image">

										</div>
									</div>
									<div class="col text-right pb-3">

									</div>
								</div>
							</div>
							<div class="text-center">
								<h6 class="mb-1 mt-3 olive-title-color"><?= $userData['SPRIDEN_surname'] . " " . $userData['SPRIDEN_student_name'] . " " . $userData['SPRIDEN_father_name']; ?></h6>
								<p class="text-muted" style="font-size: 12px; font-weight: 600; padding-bottom: 0px; margin-bottom: 0px;"><?= $userData['SPRIDEN_student_id']; ?></p>
								<p class="mb-3 olive-title-color" style="font-size: 12px; font-weight: 600; padding-bottom: 0px;">
									<?php
									if (!$allocated_class == null) {
									?>
										<?= $allocated_class['SPATESD_std'] . " - " . $allocated_class['SPATESD_div']; ?>

									<?php } ?>
								</p>
								<a href="<?= base_url(); ?>index.php/Dashboard/logout" class="btn btn-sm btn-olive"> <i class="fas fa-sign-out-alt"></i> Logout</a>
							</div>
							<hr class="wid-80 b-wid-3 my-4">
							<div class="row text-center label-row">
								<div class="col">
									<h6 class="mb-1"><?= $TotalCount; ?></h6>
									<p class="mb-0">Students</p>
								</div>
								<div class="col">
									<h6 class="mb-1"><?= $total_pending; ?></h6>
									<p class="mb-0">Pending Remarks</p>
								</div>
								<div class="col">
									<h6 class="mb-1"><?= $total_replied; ?></h6>
									<p class="mb-0">Remarks Replied</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="section">
				<div class="sec-col" data-toggle="modal" data-target="#exampleModal11">
					<i class="fas fa-calendar-alt"></i>
					<p>Add Event</p>
					<div class="circle"></div>
				</div>
				<div data-toggle="modal" data-target="#exampleModal21" class="sec-col">
					<i class="fas fa-chart-bar"></i>
					<p>Fill Marks</p>
					<div class="circle"></div>
				</div>
				<div data-toggle="modal" data-target="#exampleModal211" class="sec-col">
					<i class="fas fa-book-reader"></i>
					<p>Add Remark</p>
					<div class="circle"></div>
				</div>

			</div>
			<?php
			$ip_check = array(
				'127.0.0.1',
				'::1'
			);

			if (!in_array($_SERVER['REMOTE_ADDR'], $ip_check)) {
				$quiz_url = "http://quiz.oliveschool.org.in/teacher?teacher-id=" . $this->session->userdata('oliveMobileSystemUser');
			} else {
				$quiz_url = "https://localhost/oliveQuiz/teacher?teacher-id=" . $this->session->userdata('oliveMobileSystemUser');
			}
			?>
			<div class="section">
				<div data-toggle="modal" data-target="#exampleModal2" class="sec-col">
					<i class="fas fa-calendar-check"></i>
					<p>Mark Attendance</p>
					<div class="circle"></div>
				</div>
				<a href="<?= $quiz_url; ?>" target="_blank" class="sec-col">
					<i class="fas fa-desktop"></i>
					<p>Quiz Manager</p>
					<div class="circle"></div>
				</a>
				<div data-toggle="modal" data-target="#exampleModal2112" class="sec-col">
					<i class="fas fa-grip-vertical"></i>
					<p>TimeTable</p>
					<div class="circle"></div>
				</div>

			</div>
			<!--List of Students For Contact Details--->
			<div class="row" style="padding: 5px;">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header bg-student-details">
							<h5 class="text-white">Student Details</h5>
						</div>
						<style>
							#studentTable tr:hover {
								cursor: pointer;
								color: #26A69A;
								font-weight: normal;
								transition: all 0.2s ease-in-out;
							}

							#studentTable tr td,
							#studentTable th td {
								padding: 5px;
								font-size: 12px;
							}
						</style>
						<div class="card-body  student_style_table p-0">
							<div class="student_details_div">
								<table class="table table-bordered table-striped pb-0 mb-0" id="studentTable">
									<tbody id="tbody">
										<tr style="text-align: center;">
											<td style="font-weight: bold;">Profile</td>
											<td style="font-weight: bold;">Sr. No</td>
											<td style="font-weight: bold; text-align: left;">Name</td>
										</tr>
										<?php
										for ($i = 0; $i < count($all_students); $i++) {
										?>
											<tr data-toggle="modal" data-target="#exampleModal" class="addFeedbackByClick" data-id="<?php echo $all_students[$i]['STVCLAS_code'] ?>">
												<td style="text-align: center; padding: 2px;">
													<img style="height: 30px; border-radius: 50%;" src="<?= base_url(); ?>images/user.png" alt="">
												</td>
												<td style="text-align: center;">
													<?php echo $i + 1; ?>
												</td>
												<td>
													<?php echo $all_students[$i]['SPRIDEN_surname'] . " " . $all_students[$i]['SPRIDEN_student_name'] . " " . $all_students[$i]['SPRIDEN_father_name']  ?>
												</td>
											</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--- Hidden / Display Contact Card--->
			<div class="row mobile-margin-top-minus-20" style="padding: 5px;">
				<div class="col-md-12">
					<div class="card" id=studentIDCard>
						<div class="card-header bg-contact-card justify-content-center">
							<img src="<?= base_url(); ?>images/logoNewDevWhite.png" style="height: 40px;" alt="">
							<h5 class="text-center ml-2" style="color: #fff;">Olive English School - Student Contact Details</h5>
						</div>
						<div class="card-body p-0">
							<div class="id-card-container">
								<div class="image-container">
									<img src="<?= base_url(); ?>images/user.png" alt="">
								</div>
								<div class="info-id-container">
									<p>
										Name : <span id="student_id_card_name"></span>
									</p>
									<p>
										Father Name : <span id="student_id_card_fathername"></span>
									</p>
									<input type="hidden" id="hiddenstudent_id">
									<p style="margin-bottom: 0px; padding-bottom: 2px; font-weight: bold;">
										Contact Details
									</p>
									<div class="icon-container">
										<a id="callRef" href="" class="icon-con">
											<i class="fas fa-phone-volume call"></i>
										</a>
										<a id="whaRef" href="" class="icon-con ">
											<i class="fab fa-whatsapp wha"></i>
										</a>
										<a id="msgRef" href="" class="icon-con ">
											<i class="fas fa-comment-alt msg"></i>
										</a>
										<a id="emailRef" href="" class="icon-con ">
											<i class="fas fa-envelope email"></i>
										</a>
									</div>
									<div class="modal-container-con pb-2">
										<button class="btn btn-sm btn-remark-bg addFeedbackByClick1" data-toggle="modal" data-target="#exampleModal1">Add Remark</button>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- student Remark Card--->
			<div class="row" style="padding: 5px;">
				<div class="col-md-12">
					<div class="card ">
						<div class="card-header bg-student-remark">
							<h5 class="text-white">Student Remarks</h5>
						</div>
						<style>
							.remarkTable tr:hover {
								color: #36d1dc !important;
							}
						</style>
						<div class="card-body student_style_table remark p-0">
							<div class="student_details_div">
								<table class="table remarkTable table-bordered table-striped pb-0 mb-0" id="studentTable">
									<tbody id="tbody">
										<tr style="text-align: center;">

											<td style="font-weight: bold;">Sr.No.</td>
											<td style="font-weight: bold; text-align: left;">Name</td>
										</tr>
										<?php
										$j = 1;
										for ($i = 0; $i < count($all_remarks); $i++) {
										?>
											<tr data-toggle="modal" data-target="#staticBackdrop" class="getDataForUpdateFeedback" data-id="<?php echo $all_remarks[$i]['stvrmrk_id'] ?>">
												<td style="text-align: center;">
													<?php echo $j; ?>
												</td>
												<td>
													<?php echo $all_remarks[$i]['SPRIDEN_surname'] . " " . $all_remarks[$i]['SPRIDEN_student_name'] . " " . $all_remarks[$i]['SPRIDEN_father_name'] . " ( " . $all_remarks[$i]['stvrmrk_remark'] . " ) ";  ?>
												</td>
											</tr>
										<?php
											$j++;
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- student Admission Status Card--->
			<div class="row" style="padding: 5px;">
				<div class="col-md-12">
					<div class="card ">
						<div class="card-header bg-student-remark">
							<h5 class="text-white">Admission Status</h5>
						</div>
						<style>
							.remarkTable tr:hover {
								color: #36d1dc !important;
							}
						</style>
						<div class="card-body student_style_table remark p-0">
							<div class="student_details_div">
								<table class="table remarkTable table-bordered table-striped pb-0 mb-0" id="studentTable">

									<tbody id="tbodyMarkss">
										<tr>
											<td style="font-weight: bold; text-align: center;">Sr. No</td>
											<td style="font-weight: bold;">Name</td>
											<td style="font-weight: bold;">Status</td>
										</tr>
										<?php
										if ($admission_status != null) {
											$ad_st_count = 1;
											foreach ($admission_status as $adm_status) {
												$status_color = '';
												if ($adm_status['visiting_fees_status_of'] == "Full") {
													$status_color = "bg-success";
												} else {
													$status_color = "bg-secondary";
												}
										?>
												<tr>
													<td class="text-center"><?= $ad_st_count; ?></td>
													<td><?= $adm_status['SPRIDEN_surname'] . " " . $adm_status['SPRIDEN_student_name'] . " " . $adm_status['SPRIDEN_father_name']; ?></td>
													<td class="text-left">
														<div class="<?= $status_color; ?> text-center text-white ">
															<?= $adm_status['visiting_fees_status_of']; ?>
														</div>
													</td>

												</tr>
										<?php $ad_st_count++;
											}
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- [ Main Content ] end -->
		</div>
	</div>
	<!-- [ Main Content ] end -->
	<?php
	if ($allocated_class != null) {
		$dis_std = $allocated_class['SPATESD_std'];
		$dis_div = $allocated_class['SPATESD_div'];
	} else {
		$dis_std = '';
		$dis_div = '';
	}
	?>
	<!-- Feedback -->
	<style>
		#staticBackdrop .modal-dialog .modal-content .modal-body .right-label {
			text-align: right;
		}

		#staticBackdrop .modal-dialog .modal-content .modal-body table tr {
			padding: 0px;
		}

		#staticBackdrop .modal-dialog .modal-content .modal-body table tr td {
			border: none;
			padding: 4px;
			font-size: 12px;
			font-weight: 400;
		}
	</style>
	<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-2">
					<h5 style="font-size: 14px;" class="modal-title text-white" id="staticBackdropLabel">Parents Feedback</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table">
						<tr>
							<td class="right-label">Student ID : </td>
							<td>
								<input id="modal_stu_stuid" type="text" style="border: none;" readonly class="form-control form-control-sm">
								<input id="modal_stu_rowID" type="hidden" style="border: none;" class="form-control form-control-sm">
							</td>
						</tr>
						<tr>
							<td class="right-label">Student Name : </td>
							<td><input id="modal_stu_name" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
						</tr>
						<tr>
							<td class="right-label">Remark Date : </td>
							<td><input id="modal_stu_remark_date" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
						</tr>
						<tr>
							<td class="right-label">Remark : </td>
							<td>
								<textarea readonly class="form-control form-control-sm" name="student_remark" id="student_remark" cols="30" rows="3"></textarea>
							</td>
						</tr>
						<tr>
							<td class="right-label">Fedback : </td>
							<td>
								<textarea class="form-control form-control-sm" name="student_remark_feedback" id="student_remark_feedback" cols="30" rows="3"></textarea>
							</td>
						</tr>
						<tr>
							<td class="right-label"></td>
							<td>
								<button class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close"> <i class="fas fa-window-close"></i> Cancel</button>
								<button class="btn btn-success btn-sm" type="button" id="add_feedbackButton"> <i class="fas fa-edit"></i> Add Feedback</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	</div>
	<!--Student Remark Modal-->
	<!-- Modal -->
	<style>
		#exampleModal1 .modal-dialog,
		#exampleModal1 .modal-dialog .modal-content,
		#exampleModal1 .modal-dialog .modal-content .modal-header {

			border-color: #53769c;
		}

		#exampleModal1 .modal-dialog .modal-content .modal-header {
			padding-top: 10px;
			padding-bottom: 10px;
			background: #26A69A !important;
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

		}

		#exampleModal1 .modal-dialog .modal-content .modal-header {
			text-align: center !important;
		}

		#exampleModal1 .modal-dialog .modal-content .modal-body .right-label {
			text-align: right;
		}

		#exampleModal1 .modal-dialog .modal-content .modal-body table tr {
			padding: 0px;
		}

		#exampleModal1 .modal-dialog .modal-content .modal-body table tr td {
			border: none;
			padding: 4px;
			font-size: 12px;
			font-weight: 400;
		}
	</style>
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Remark</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table">
						<tr>
							<td class="right-label">Student ID : </td>
							<td><input id="emodal_stu_stuid" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
							<td><input id="emodal_stu_stuid_hidden" type="hidden" style="border: none;" readonly class="form-control form-control-sm"></td>
						</tr>
						<tr>
							<td class="right-label">Roll No : </td>
							<td><input id="emodal_stu_roll" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
						</tr>
						<tr>
							<td class="right-label">Student Name : </td>
							<td><input id="emodal_stu_name" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
						</tr>

						<tr>
							<td class="right-label">Class : </td>
							<td><input id="emodal_stu_class" type="text" style="border: none;" readonly class="form-control form-control-sm"></td>
							<td><input id="emodal_stu_class_hidden" type="hidden" style="border: none;" readonly class="form-control form-control-sm"></td>
						</tr>

						<tr>
							<td class="right-label">Remark : </td>
							<td>
								<style>
									input[list] {
										font-size: 12px !important;
										padding: 5px !important;
									}
								</style>
								<!-- <textarea class="form-control form-control-sm" name="estudent_remark" id="estudent_remark" cols="30" rows="3"></textarea> -->
								<input class="form-control form-control-sm" list="st_rm" name="estudent_remark" id="estudent_remark">
								<datalist id="st_rm">
									<?php
									if ($all_remarks_masters != null) {
										foreach ($all_remarks_masters as $all_rem_mast) {
									?>
											<option value="<?= $all_rem_mast['stvrmst_data']; ?>">
										<?php }
									} ?>
								</datalist>
							</td>
						</tr>
						<tr>
							<td class="right-label"></td>
							<td>
								<button class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close"> <i class="fas fa-window-close"></i> Cancel</button>
								<button class="btn btn-success btn-sm" type="button" id="add_remarkButton"> <i class="fas fa-edit"></i> Add Remark</button>
							</td>
						</tr>
					</table>

				</div>
			</div>
		</div>
	</div>

	<!--Student Attendance Modal-->
	<!-- Modal -->
	<style>
		#exampleModal2 .modal-dialog,
		#exampleModal2 .modal-dialog .modal-content,
		#exampleModal2 .modal-dialog .modal-content .modal-header {

			border-color: #53769c;
		}

		#exampleModal2 .modal-dialog .modal-content .modal-header {
			padding-top: 10px;
			padding-bottom: 10px;
			background: #26A69A !important;
		}

		.my-cust-bg {
			background: #26A69A !important;
			color: #fff;
			border: 1px solid #36D1DC;
		}

		.my-cust-bg:hover {
			color: #fff;
		}

		#exampleModal2 .modal-dialog .modal-content .modal-header {
			text-align: center !important;
		}



		#exampleModal2 .modal-dialog .modal-content .modal-body .right-label {
			text-align: right;
		}

		#exampleModal2 .modal-dialog .modal-content .modal-body table tr {
			padding: 0px;
		}

		#exampleModal2 .modal-dialog .modal-content .modal-body table tr td {
			border: none;
			padding: 4px;
			font-size: 12px;
			font-weight: 400;
		}
	</style>
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Attendance</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<style>
					#studentAttendanceTable tr td,
					#studentAttendanceTable tr th {
						padding: 5px;
						font-size: 12px;
						border: 1px solid #77777710 !important;
					}
				</style>
				<div class="modal-body p-0 pb-0 mb-0">
					<div class="grid2x2 p-1">
						<div class="box">
							<div class="form-group ml-1">
								<label for="">Date</label>
								<input type="date" class="form-control form-control-sm" name="attendancedate" id="attendancedate" value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="box">
							<div class="form-group ml-1">
								<label for="">Standard</label>

								<input type="text" name="class" id="class" class="form-control form-control-sm" readonly value="<?= $dis_std; ?>">
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for="">Division</label>
								<input type="text" name="division" id="division" class="form-control form-control-sm" readonly value="<?= $dis_div; ?>">
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for=""></label>
								<button type="button" id="markAttendanceButton" class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-user-plus"></i> Mark Attendance</button>
							</div>
						</div>
					</div>
					<div class="table-responsive pb-0 mb-0 mobile-margin-top-minus-20">
						<table class="table table-bordered pb-0 " id="studentAttendanceTable">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th>Name</th>
									<th>
										<input type="checkbox" checked id="selectAll" value="allPresent"> P
										<input type="checkbox" id="deselectAll" value="allAbsent"> A
									</th>
								</tr>
							</thead>
							<tbody id="tbodyAttendance">
								<?php
								if ($all_students != null) {
									foreach ($all_students as $all_student) {
								?>
										<tr>
											<td class="text-center"><?= $all_student['STVCLAS_rollno']; ?></td>
											<td><?= $all_student['SPRIDEN_surname'] . " " . $all_student['SPRIDEN_student_name'] . " " . $all_student['SPRIDEN_father_name']; ?></td>
											<td class="text-left"><input type="checkbox" value="<?= $all_student['id']; ?>" checked name="student_row[]" id="" class="studentRowSelector"></td>

										</tr>
								<?php }
								} ?>
							</tbody>
						</table>
						<input type="hidden" id="allo_standard" value="<?= $dis_std; ?>">
						<input type="hidden" id="allo_div" value="<?= $dis_div; ?>">
					</div>
					<!-- <div class="pb-2 pr-2 text-right">
                        <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="button" id="markAttendanceButton" class="btn btn-sm btn-success">Mark Attendance</button>
                    </div> -->
				</div>
			</div>
		</div>
	</div>
	<!--Student Remark Modal-->
	<!-- Modal -->
	<style>
		#exampleModal11 .modal-dialog,
		#exampleModal11 .modal-dialog .modal-content,
		#exampleModal11 .modal-dialog .modal-content .modal-header {

			border-color: #53769c;
		}

		#exampleModal11 .modal-dialog .modal-content .modal-header {
			padding-top: 10px;
			padding-bottom: 10px;
			background: #26A69A !important;


		}

		#exampleModal11 .modal-dialog .modal-content .modal-header {
			text-align: center !important;
		}

		#exampleModal11 .modal-dialog .modal-content .modal-body .right-label {
			text-align: right;
		}

		#exampleModal11 .modal-dialog .modal-content .modal-body table tr {
			padding: 0px;
		}

		#exampleModal11 .modal-dialog .modal-content .modal-body table tr td {
			border: none;
			padding: 4px;
			font-size: 12px;
			font-weight: 400;
		}
	</style>
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal11" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Event </h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table">
						<tr>
							<td class="right-label">Start Date : </td>
							<td><input id="event_start_date" type="date" class="form-control form-control-sm"></td>
						</tr>
						<tr>
							<td class="right-label">End Date : </td>
							<td><input id="event_end_date" type="date" class="form-control form-control-sm"></td>
						</tr>
						<tr>
							<td class="right-label">Class : </td>
							<td>
								<input id="class_with_div" value="<?= $dis_std . " - " . $dis_div; ?>" type="text" style="border: none;" readonly class="form-control form-control-sm">
								<input id="class_with_div_std_hidden" value="<?= $dis_std; ?>" type="hidden" style="border: none;" class="form-control form-control-sm">
								<input id="class_with_div_hidden" value="<?= $dis_div; ?>" type="hidden" style="border: none;" class="form-control form-control-sm">
							</td>

						</tr>
						<tr>
							<td class="right-label">Event Title : </td>
							<td><input id="event_title" type="text" class="form-control form-control-sm"></td>
						</tr>
						<tr>
							<td class="right-label">Event Detail : </td>
							<td>
								<textarea class="form-control form-control-sm" name="event_details" id="event_details" cols="30" rows="3"></textarea>
							</td>
						</tr>
						<tr>
							<td class="right-label"></td>
							<td>
								<button class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close"> <i class="fas fa-window-close"></i> Cancel</button>
								<button class="btn btn-success btn-sm" type="button" id="add_eventButton"> <i class="fas fa-edit"></i> Add Event</button>
							</td>
						</tr>
					</table>

				</div>
			</div>
		</div>
	</div>

	<!--Teacher Time table-->
	<!-- Modal -->
	<style>
		#exampleModal2112 .modal-dialog,
		#exampleModal2112 .modal-dialog .modal-content,
		#exampleModal2112 .modal-dialog .modal-content .modal-header {

			border-color: #53769c;
		}

		#exampleModal2112 .modal-dialog .modal-content .modal-header {
			padding-top: 10px;
			padding-bottom: 10px;
			background: #26A69A !important;
		}

		.my-cust-bg {
			background: #26A69A !important;
			color: #fff;
			border: 1px solid #26A69A !important;
		}

		.my-cust-bg:hover {
			color: #fff;
		}

		#exampleModal2112 .modal-dialog .modal-content .modal-header {
			text-align: center !important;
		}



		#exampleModal2112 .modal-dialog .modal-content .modal-body .right-label {
			text-align: right;
		}

		#exampleModal2112 .modal-dialog .modal-content .modal-body table tr {
			padding: 0px;
		}

		#exampleModal2112 .modal-dialog .modal-content .modal-body table tr td {
			border: none;
			padding: 4px;
			font-size: 12px;
			font-weight: 400;
		}
	</style>
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal2112" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Time Table</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<style>
					#studentMarksTable tr td,
					#studentMarksTable tr th {
						padding: 5px;
						font-size: 12px;
						border: 1px solid #77777710 !important;
					}
				</style>
				<div class="modal-body p-0 pb-0 mb-0">
					<div class="table-responsive pb-0 mb-0 ">
						<table class="table table-bordered pb-0 " id="timeTableTable">
							<thead>
								<tr>
									<th>Sr.No</th>
									<th>Time</th>
									<th>Monday</th>
									<th>Tuesday</th>
									<th>Wednesday</th>
									<th>Thursday</th>
									<th>Friday</th>
									<th>Saturday</th>
								</tr>
							</thead>
							<tbody id="teacher-timetable-table">
								<?php
								if ($timetable != null) {
									$i = 0;
									foreach ($timetable as $tmtb) {
								?>
										<tr>
											<td><?= $i; ?></td>
											<td><?= $tmtb['stvttbl_time']; ?></td>
											<td><?= $tmtb['stvttbl_monday']; ?></td>
											<td><?= $tmtb['stvttbl_tuesday']; ?></td>
											<td><?= $tmtb['stvttbl_wednesday']; ?></td>
											<td><?= $tmtb['stvttbl_thurday']; ?></td>
											<td><?= $tmtb['stvttbl_friday']; ?></td>
											<td><?= $tmtb['stvttbl_saturday']; ?></td>
										</tr>
									<?php }
									$i++;
								} else { ?>
									<tr>
										<td colspan="8" class="text-center">Time Table Not Generated For <?= $dis_std . " - " . $dis_div; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<!-- <div class="pb-2 pr-2 text-right">
                        <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="button" id="markAttendanceButton" class="btn btn-sm btn-success">Mark Attendance</button>
                    </div> -->
				</div>
			</div>
		</div>
	</div>

	<!--Student Marks All Modal-->
	<!-- Modal -->
	<style>
		#exampleModal21 .modal-dialog,
		#exampleModal21 .modal-dialog .modal-content,
		#exampleModal21 .modal-dialog .modal-content .modal-header {

			border-color: #53769c;
		}

		#exampleModal21 .modal-dialog .modal-content .modal-header {
			padding-top: 10px;
			padding-bottom: 10px;
			background: #26A69A !important;
		}

		.my-cust-bg {
			background: #26A69A !important;
			color: #fff;
			border: 1px solid #26A69A !important;
		}

		.my-cust-bg:hover {
			color: #fff;
		}

		#exampleModal21 .modal-dialog .modal-content .modal-header {
			text-align: center !important;
		}



		#exampleModal21 .modal-dialog .modal-content .modal-body .right-label {
			text-align: right;
		}

		#exampleModal21 .modal-dialog .modal-content .modal-body table tr {
			padding: 0px;
		}

		#exampleModal21 .modal-dialog .modal-content .modal-body table tr td {
			border: none;
			padding: 4px;
			font-size: 12px;
			font-weight: 400;
		}
	</style>
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal21" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Marks</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<style>
					#studentMarksTable tr td,
					#studentMarksTable tr th {
						padding: 5px;
						font-size: 12px;
						border: 1px solid #77777710 !important;
					}
				</style>
				<div class="modal-body p-0 pb-0 mb-0">
					<div class="grid2x2 p-1">
						<div class="box">
							<div class="form-group ml-1">
								<label for="">Standard</label>
								<!-- <input type="text" name="marks_class" id="marks_class" class="form-control form-control-sm" readonly value="<?= $dis_std; ?>"> -->
								<select name="marks_class" class="form-control form-control-sm" id="marks_class">
									<option value="" selected disabled>Select Standard</option>
									<?php foreach ($classes as $st_class) { ?>
										<option value="<?= $st_class['std']; ?>"><?= $st_class['std']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="box ">
							<div class="form-group ml-1">
								<label for="">Division</label>
								<!-- <input type="text" name="marks_division" id="marks_division" class="form-control form-control-sm" readonly value="<?= $dis_div; ?>"> -->
								<select name="marks_division" class="form-control form-control-sm" id="marks_division">
									<option value="" selected disabled>Select Division</option>

								</select>
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for="">Exam Name</label>
								<select name="exam_name" class="form-control form-control-sm" id="exam_name">
									<option selected disabled value="">Select Exam</option>
									<?php
									if ($all_exams != null) {
										foreach ($all_exams as $all_exam) {
									?>
											<option value="<?= $all_exam['stvexam_id']; ?>"><?= $all_exam['stvexam_name']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for="">Subject</label>
								<select name="marks_subject" class="form-control form-control-sm" id="marks_subject">
									<option selected disabled value="">Select Subject</option>

								</select>
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for="">Total Marks</label>
								<input type="text" name="total_marks" id="total_marks" class="form-control form-control-sm">
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for=""></label>
								<button type="button" id="fillMarksButton" class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-chart-bar"></i> Fill Marks</button>
							</div>
						</div>
					</div>
					<div class="table-responsive pb-0 mb-0 mobile-margin-top-minus-20">
						<table class="table table-bordered pb-0 " id="studentMarksTable">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th>Name</th>
									<th>
										Marks
									</th>
								</tr>
							</thead>
							<tbody id="tbodyMarks">
								<!-- <?php
										if ($all_students != null) {
											foreach ($all_students as $all_student) {
										?>
										<tr>
											<td class="text-center"><?= $all_student['STVCLAS_rollno']; ?></td>
											<td><?= $all_student['SPRIDEN_surname'] . " " . $all_student['SPRIDEN_student_name'] . " " . $all_student['SPRIDEN_father_name']; ?></td>
											<td class="text-left">
												<input type="number" style="width: 70px;" name="student_marks_input[]" id="student_marks_input" class="studentRowSelector form-control form-control-sm">
												<input type="hidden" value="<?= $all_student['id']; ?>" name="student_marks_input_hidden[]" id="student_id_input_hidden" class="studentRowSelector">
											</td>

										</tr>
								<?php }
										} ?> -->
							</tbody>
						</table>
						<input type="hidden" id="allo_standard" value="<?= $dis_std; ?>">
						<input type="hidden" id="allo_div" value="<?= $dis_div; ?>">
					</div>
					<!-- <div class="pb-2 pr-2 text-right">
                        <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="button" id="markAttendanceButton" class="btn btn-sm btn-success">Mark Attendance</button>
                    </div> -->
				</div>
			</div>
		</div>
	</div>

	<!--Student Status Fees All Modal-->
	<!-- Modal -->
	<style>
		#exampleModal211 .modal-dialog,
		#exampleModal211 .modal-dialog .modal-content,
		#exampleModal211 .modal-dialog .modal-content .modal-header {

			border-color: #53769c;
		}

		#exampleModal211 .modal-dialog .modal-content .modal-header {
			padding-top: 10px;
			padding-bottom: 10px;
			background: #26A69A !important;
		}

		.my-cust-bg {
			background: #26A69A !important;
			color: #fff;
			border: 1px solid #7F00FF;
		}

		.my-cust-bg:hover {
			color: #fff;
		}

		#exampleModal211 .modal-dialog .modal-content .modal-header {
			text-align: center !important;
		}



		#exampleModal211 .modal-dialog .modal-content .modal-body .right-label {
			text-align: right;
		}

		#exampleModal211 .modal-dialog .modal-content .modal-body table tr {
			padding: 0px;
		}

		#exampleModal211 .modal-dialog .modal-content .modal-body table tr td {
			border: none;
			padding: 4px;
			font-size: 12px;
			font-weight: 400;
		}
	</style>
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal211" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Details</h5>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<style>
					#studentMarksTable tr td,
					#studentMarksTable tr th {
						padding: 5px;
						font-size: 12px;
						border: 1px solid #77777710 !important;
					}
				</style>
				<div class="modal-body p-0 pb-0 mb-0">
					<div class="grid2x2 p-1">
						<div class="box">
							<div class="form-group ml-1">
								<label for="">Standard</label>

								<select name="student_details_std" class="form-control form-control-sm" id="student_details_std">
									<option value="" selected disabled>Select Standard</option>
									<?php foreach ($classes as $st_class) { ?>
										<option value="<?= $st_class['std']; ?>"><?= $st_class['std']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="box ">
							<div class="form-group ml-1">
								<label for="">Division</label>
								<select name="student_details_div" class="form-control form-control-sm" id="student_details_div">
									<option value="" selected disabled> Select Division</option>
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
								</select>
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for="">Date</label>
								<input type="date" class="form-control form-control-sm" name="attendancedate" id="attendancedate" value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for=""></label>
								<button type="button" id="findStudentByRemarkStdDivButton" class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-user"></i> View Students</button>
							</div>
						</div>
					</div>

					<div class="table-responsive pb-0 mb-0">
						<table class="table table-bordered pb-0 " id="studentMarksTable">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th>Name</th>
									<th>
										Class
									</th>
								</tr>
							</thead>
							<tbody id="students-for-remark-tbody">

							</tbody>
						</table>
					</div>
					<!-- <div class="pb-2 pr-2 text-right">
                        <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="button" id="markAttendanceButton" class="btn btn-sm btn-success">Mark Attendance</button>
                    </div> -->
				</div>
			</div>
		</div>
	</div>
	<!-- Required Js -->
	<?php include 'application/views/jsPages.php'; ?>
	<script>
		$(document).on('click', '.addFeedbackByClick1', function() {
			var rowID = $('#hiddenstudent_id').val();
			$.ajax({
				url: "<?= base_url(); ?>index.php/Dashboard/get_student_for_remark",
				data: {
					rowID: rowID
				},
				method: "POST",
				dataType: "JSON",
				success: function(data) {
					$('#emodal_stu_roll').val(data.STVCLAS_rollno);
					$('#emodal_stu_stuid').val(data.SPRIDEN_student_id);
					$('#emodal_stu_stuid_hidden').val(data.id);
					$('#emodal_stu_name').val(data.SPRIDEN_surname + " " + data.SPRIDEN_student_name + " " + data.SPRIDEN_father_name);
					$('#emodal_stu_class').val(data.STVCLAS_standard + " - " + data.STVCLAS_class);
					$('#emodal_stu_class_hidden').val(data.STVCLAS_standard);
				}
			});
		});
		$('.addFeedbackByClick').on('click', function() {

			var emailLink = '';
			var whaLink = '';
			var msgLink = '';
			var callLink = '';
			var validsms = '';
			$('#studentIDCard').slideDown();

			var rowID = $(this).attr("data-id");
			$.ajax({
				url: "<?= base_url(); ?>index.php/Dashboard/get_student_details_for_contact",
				data: {
					rowID: rowID
				},
				method: "POST",
				dataType: "JSON",
				success: function(data) {
					$('#student_id_card_name').html(data['data1'].SPRIDEN_surname + " " + data['data1'].SPRIDEN_student_name);
					$('#hiddenstudent_id').val(data['data1'].STVCLAS_code);
					$('#student_id_card_fathername').html(data['data1'].SPRIDEN_surname + " " + data['data1'].SPRIDEN_father_name);
					callLink = 'tel:+91' + data['data2'].stvpncf_emergency_no;
					whaLink = 'https://api.whatsapp.com/send?phone=+91' + data['data2'].stvpcnf_whatsapp;
					if (data['data2'].stvpncf_sms_no == "") {
						validsms = data['data2'].stvpncf_emergency_no
					} else {
						validsms = data['data2'].stvpncf_sms_no
					}
					msgLink = 'sms:+91' + validsms + '&body="Message"';
					emailLink = 'mailto:' + data['data2'].stvpcnf_gmail;

					$('#callRef').attr("href", callLink);
					$('#whaRef').attr("href", whaLink);
					$('#msgRef').attr("href", msgLink);
					$('#emailRef').attr("href", emailLink);
				}
			});
		});

		//Feedback Update Code
		$('.getDataForUpdateFeedback').on('click', function() {
			var rowID = $(this).attr("data-id");
			$.ajax({
				url: "<?= base_url(); ?>index.php/Dashboard/getDataForUpdateFeedback",
				data: {
					rowID: rowID
				},
				method: "POST",
				dataType: "JSON",
				success: function(data) {
					$('#modal_stu_stuid').val(data.SPRIDEN_student_id);
					$('#modal_stu_rowID').val(data.stvrmrk_id);
					$('#modal_stu_name').val(data.SPRIDEN_surname + " " + data.SPRIDEN_student_name + " " + data.SPRIDEN_father_name);
					$('#modal_stu_remark_date').val(data.stvrmrk_date);
					$('#student_remark').val(data.stvrmrk_remark);
				}
			});
		});

		$('#add_feedbackButton').click(function() {
			var rowid = $('#modal_stu_rowID').val();
			var feedback_data = $('#student_remark_feedback').val();
			if (feedback_data != "") {
				$.ajax({
					url: "<?= base_url(); ?>index.php/Dashboard/updateParentsFeedbackByTeacher",
					data: {
						rowID: rowid,
						feedback_data: feedback_data
					},
					method: "POST",
					beforeSend: function() {
						$('#add_feedbackButton').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
					},
					success: function(data) {
						$('#add_feedbackButton').html('<i class="fas fa-edit"></i> Add Feedback');
						if (data != "fail") {
							alert('Feedback Added !!!');
							window.location.reload();
						} else {
							swal("Failed to Add ")
						}
					}
				});
			} else {
				swal("Provide Feedback");
				return false;
			}
		});
		$('#add_remarkButton').click(function() {
			var student_id = $('#emodal_stu_stuid_hidden').val();
			var remark_data = $('#estudent_remark').val();
			var std = $('#emodal_stu_class_hidden').val();
			$.ajax({
				url: "<?= base_url(); ?>index.php/Dashboard/add_remark",
				data: {
					student_id: student_id,
					remark_data: remark_data,
					std: std
				},
				method: "POST",
				beforeSend: function() {
					$('#add_remarkButton').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
				},
				success: function(data) {
					if (data != "fail") {
						$('#add_remarkButton').html('<i class="fas fa-edit"></i> Add Remark');
						alert("Remark Added Successfully");
						window.location.reload();
					} else {
						$('#add_remarkButton').html('<i class="fas fa-edit"></i> Add Remark');
						alert("Failed");

					}
				}
			});
		});

		// Add Event Details
		$('#add_eventButton').click(function() {
			var start_date = $('#event_start_date').val();
			var end_date = $('#event_end_date').val();
			var std = $('#class_with_div_std_hidden').val();
			var div = $('#class_with_div_hidden').val();
			var title = $('#event_title').val();
			var details = $('#event_details').val();
			$.ajax({
				url: "<?= base_url(); ?>index.php/Dashboard/add_event",
				data: {
					start_date: start_date,
					end_date: end_date,
					std: std,
					div: div,
					title: title,
					details: details
				},
				method: "POST",
				beforeSend: function() {
					$('#add_eventButton').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
				},
				success: function(data) {
					if (data != "fail") {
						$('#add_eventButton').html('<i class="fas fa-edit"></i> Add Event');
						alert("Event Added Successfully");
						window.location.reload();
					} else {
						$('#add_eventButton').html('<i class="fas fa-edit"></i> Add Event');
						alert("Failed");

					}
				}
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			var student_ids = [];
			$('#selectAll').click(function() {
				var isChecked = $(this).prop("checked");
				if (isChecked == true) {
					$('#deselectAll').prop('checked', false);
					$('#studentAttendanceTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
				}
				//$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
			});
			$('#deselectAll').click(function() {
				var isChecked = $(this).prop("checked");
				if (isChecked == true) {
					$('#selectAll').prop('checked', false);
					$('#studentAttendanceTable tr:has(td)').find('input[type="checkbox"]').prop('checked', false);
				}
				//$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
			});
		});
		$('#markAttendanceButton').click(function() {
			student_ids = [];
			attendanceStatus = [];
			var std = $('#class').val();
			var division = $('#division').val();
			var dateOfAtt = $('#attendancedate').val();
			$('#tbodyAttendance').find('input[type="checkbox"]').each(function() {
				if ($(this).prop("checked") == true) {
					student_ids.push($(this).val());
					attendanceStatus.push('P');
				} else {
					student_ids.push($(this).val());
					attendanceStatus.push('A');
				}
			});
			$.ajax({
				url: "<?php echo base_url(); ?>index.php/Dashboard/fillStudentsAttendance",
				data: {
					student_ids: student_ids,
					std: std,
					division: division,
					attendanceStatus: attendanceStatus,
					dateOfAtt: dateOfAtt
				},
				method: "POST",
				beforeSend: function() {
					$('#markAttendanceButton').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
				},
				success: function(data) {
					$('#markAttendanceButton').html('<i class="fas fa-user-plus"></i> Mark Attendance');
					if (data != "fail") {
						alert("Attendence Marked Successfully !!!");
						window.location.reload();
					} else {
						swal("Failed to Mark Attendance");
					}
				}
			});
		});

		//Add Marks All
		$('#fillMarksButton').click(function() {
			var stad = $('#marks_class').val();
			var divi = $('#marks_division').val();
			var subject = $('#marks_subject').val();
			var exam_name = $('#exam_name').val();
			var total_marks = $('#total_marks').val();
			var marks = [];
			var student_id_marks = [];
			$('#tbodyMarks').find('input[type="number"]').each(function() {
				marks.push($(this).val());
			});
			$('#tbodyMarks').find('input[type="hidden"]').each(function() {
				student_id_marks.push($(this).val());
			});

			$.ajax({
				url: "<?php echo base_url(); ?>index.php/Dashboard/fillStudentsMarks",
				data: {
					stad: stad,
					divi: divi,
					subject: subject,
					marks: marks,
					student_id_marks: student_id_marks,
					total_marks: total_marks,
					exam_name: exam_name
				},
				method: "POST",
				beforeSend: function() {
					$('#fillMarksButton').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
				},
				success: function(data) {
					$('#fillMarksButton').html('<i class="fas fa-chart-bar"></i> Fill Marks');
					if (data != "fail") {
						alert("Marks Added Successfully !!!");
						window.location.reload();
					} else {
						swal("Failed to Add Marks");
					}
				}
			});
		});

		$('#findStudentByRemarkStdDivButton').click(function() {
			var std = $('#student_details_std').val();
			var div = $('#student_details_div').val();
			if (std == null && div == null) {
				alert("Select Standard & Division");
				return false;
			} else if (std != null && div == null) {
				alert("Select Division");
				return false;
			} else if (std == null && div != null) {
				alert("Select Standard");
				return false;
			} else {
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Dashboard/get_students_for_teacher_remark_by_std_and_div",
					data: {
						std: std,
						div: div
					},
					method: "POST",
					dataType: "JSON",
					success: function(data) {
						if (data != "fail") {
							var html = '';
							for (var count = 0; count < data.length; count++) {
								html += '<tr class="addStudentRemarkForStudent" data-id="' + data[count].id + '">';
								html += '<td class="text-center">' + data[count].STVCLAS_rollno + '</td>'
								html += '<td>' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '</td>';
								html += '<td>' + data[count].STVCLAS_standard + ' - ' + data[count].STVCLAS_class + '</td>';
								html += '</tr>';
							}
							$('#students-for-remark-tbody').html(html);
						} else if (data == "fail") {
							console.log("fail");
						}
					}
				});
			}
		});
	</script>

	<script>
		// $(document).ready(function() {
		// 	$(".sec-col").mouseenter(function() {
		// 		$(this).addClass("anim");
		// 	});
		// 	$(".sec-col").mouseleave(function() {
		// 		$(this).removeClass("anim");
		// 	});
		// });
		$('#marks_class').change(function() {
			var std = $('#marks_class').val();
			if (std == null) {
				alert("Select Standard");
				return false;
			} else {
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Dashboard/get_subject_by_std",
					data: {
						std: std
					},
					method: "POST",
					dataType: "JSON",
					success: function(data) {
						if (data != "fail") {
							var html = '<option value="" selected disabled>Select Subject</option>';
							for (var count = 0; count < data['subjects'].length; count++) {
								html += '<option value="' + data['subjects'][count].stvsubj_id + '">' + data['subjects'][count].stvsubj_subject + '</option>';
							}
							$('#marks_subject').html(html);
						}
					}
				});
			}
		});

		// Dynamically generated Student List with STD and DIV
		$('#marks_division').change(function() {
			var std = $('#marks_class').val();
			var div = $('#marks_division').val();
			if (std != null && div != null) {
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Dashboard/getStudentsForFillMarks",
					data: {
						std: std,
						div: div
					},
					method: "POST",
					dataType: "JSON",
					success: function(data) {
						if (data != "fail") {
							var html = '';
							for (var count = 0; count < data.length; count++) {
								var ma_count = count + 1;
								html += '<tr>';
								html += '<td class="text-center">' + ma_count + '</td>'
								html += '<td>' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '</td>';
								html += '<td class="text-left">';
								html += '<input type="number" style="width: 70px;" name="student_marks_input[]" id="student_marks_input" class="studentRowSelector form-control form-control-sm">';
								html += '<input type="hidden" value="' + data[count].id + '" name="student_marks_input_hidden[]" id="student_id_input_hidden" class="studentRowSelector">'
								html += '</td>'
								html += '</tr>';
							}
							$('#tbodyMarks').html(html);
							//console.log(data);
						}
					}
				});
			} else {
				console.log("Select Standard");
				return false;
			}
		});


		//get Division
		$('#marks_class').change(function() {
			var std = $('#marks_class').val();
			if (std == null) {
				alert("Select Standard");
				return false;
			} else {
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Dashboard/getDivByStd",
					data: {
						std: std
					},
					method: "POST",
					dataType: "JSON",
					success: function(data) {
						if (data != "fail") {
							var html = '<option value="" selected disabled>Select Division</option>';
							for (var count = 0; count < data['division'].length; count++) {
								html += '<option value="' + data['division'][count].division + '">' + data['division'][count].division + '</option>';
							}
							$('#marks_division').html(html);
						}
					}
				});
			}
		});

		$(document).on('click', '.addStudentRemarkForStudent', function() {
			var rowID = $(this).attr("data-id");
			//alert(rowID);
			$.ajax({
				url: "<?= base_url(); ?>index.php/Dashboard/get_student_for_remark_by_modalbutton",
				data: {
					rowID: rowID
				},
				method: "POST",
				dataType: "JSON",
				success: function(data) {
					$('#emodal_stu_roll').val(data.STVCLAS_rollno);
					$('#emodal_stu_stuid').val(data.SPRIDEN_student_id);
					$('#emodal_stu_stuid_hidden').val(data.id);
					$('#emodal_stu_name').val(data.SPRIDEN_surname + " " + data.SPRIDEN_student_name + " " + data.SPRIDEN_father_name);
					$('#emodal_stu_class').val(data.STVCLAS_standard + " - " + data.STVCLAS_class);
					$('#emodal_stu_class_hidden').val(data.STVCLAS_standard);

				}

			});
			$('#exampleModal211').modal('hide');
			$('#exampleModal1').modal('toggle');
			$('#exampleModal1').modal('show');
		});
	</script>
</body>

</html>
