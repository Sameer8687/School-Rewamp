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
			<!-- [ Main Content ] start -->
			<div class="row p-0 counter-display-container">
				<div class="col-md-12 main-display">
					<div class="display-col">
						<div class="card statustic-progress-card bg-first-gradient">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col">
										<i class=" text-white fas fa-user f-20 m-b-10"></i>
										<h6 class="text-white">Students</h6>
									</div>
									<div class="col text-right">
										<h4 class="text-white"><?= $TotalCount; ?></h4>
									</div>
								</div>
							</div>
						</div>
						<div class="button-div">
							<button class="btn btn-sm btn-button-cust w-100 newButon" data-toggle="modal" data-target="#exampleModal11"> <i class="fas fa-calendar-alt"></i> Add Event</button>
							<button class="btn btn-sm btn-button-cust w-100 third mt-1" data-toggle="modal" data-target="#exampleModal21"> <i class="fas fa-chart-bar"></i> Fill Marks</button>
							<button class="btn btn-sm btn-button-cust w-100 fifth mt-1" data-toggle="modal" data-target="#exampleModal211"> <i class="fas fa-book-reader"></i> Admission Status</button>
						</div>
					</div>
					<div class="display-col">
						<div class="card statustic-progress-card bg-second-gradient">
							<div class="card-body">
								<div class="row align-items-center">
									<div class="col">
										<i class=" text-white fas fa-key f-20 m-b-10"></i> <span class="text-white">Login Time</span>
										<h6 class="text-white"><?= $this->session->userdata('login_data'); ?></h6>
									</div>
								</div>
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
						<div class="button-div">
							<button class="btn btn-sm btn-button-cust w-100 second" data-toggle="modal" data-target="#exampleModal2"> <i class="fas fa-calendar-alt"></i> Mark Attendance</button>
							<a href="<?= $quiz_url; ?>" target="_blank" class="btn btn-sm btn-button-cust w-100 first mt-1"> <i class="fas fa-calendar-alt"></i> Quiz Manager</a>

						</div>
					</div>
				</div>
			</div>
			<div class="row mobile-margin-top-minus-20" style="padding: 5px;">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header bg-student-details">
							<h5 class="text-white">Student Details</h5>
						</div>
						<style>
							#studentTable tr:hover {
								cursor: pointer;
								color: #F37335;
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
											<td style="font-weight: bold;">Roll No</td>
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
													<?php echo $all_students[$i]['STVCLAS_rollno'] ?>
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
			<div class="row mobile-margin-top-minus-20" style="padding: 5px;">
				<div class="col-md-12">
					<div class="card" id=studentIDCard>
						<div class="card-header bg-contact-card justify-content-center">
							<img src="<?= base_url(); ?>images/logoNewDev.png" style="height: 40px;" alt="">
							<h5 class="text-center ml-2" style="color: #36632b;">Olive English School - Student Contact Details</h5>
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
										<button class="btn btn-sm btn-remark-bg btn-warning-bg">Add Marks</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
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
			<!-- [ Main Content ] end -->
		</div>
	</div>
	<!-- [ Main Content ] end -->
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
			background: #ff9966;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to left, #ff5e62, #ff9966);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to left, #ff5e62, #ff9966);
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
								<textarea class="form-control form-control-sm" name="estudent_remark" id="estudent_remark" cols="30" rows="3"></textarea>
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
			background: #36D1DC;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to top, #5B86E5, #36D1DC);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to top, #5B86E5, #36D1DC);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
		}

		.my-cust-bg {
			background: #36D1DC;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to top, #5B86E5, #36D1DC);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to top, #5B86E5, #36D1DC);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
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
								<input type="text" name="class" id="class" class="form-control form-control-sm" readonly value="<?= $allocated_class['SPATESD_std']; ?>">
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for="">Division</label>
								<input type="text" name="division" id="division" class="form-control form-control-sm" readonly value="<?= $allocated_class['SPATESD_div']; ?>">
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
								foreach ($all_students as $all_student) {
								?>
									<tr>
										<td class="text-center"><?= $all_student['STVCLAS_rollno']; ?></td>
										<td><?= $all_student['SPRIDEN_surname'] . " " . $all_student['SPRIDEN_student_name'] . " " . $all_student['SPRIDEN_father_name']; ?></td>
										<td class="text-left"><input type="checkbox" value="<?= $all_student['id']; ?>" checked name="student_row[]" id="" class="studentRowSelector"></td>

									</tr>
								<?php } ?>
							</tbody>
						</table>
						<input type="hidden" id="allo_standard" value="<?= $allocated_class['SPATESD_std']; ?>">
						<input type="hidden" id="allo_div" value="<?= $allocated_class['SPATESD_div']; ?>">
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
			background: #11998e;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to top, #38ef7d, #11998e);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to top, #38ef7d, #11998e);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


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
								<input id="class_with_div" value="<?= $allocated_class['SPATESD_std'] . " - " . $allocated_class['SPATESD_div']; ?>" type="text" style="border: none;" readonly class="form-control form-control-sm">
								<input id="class_with_div_std_hidden" value="<?= $allocated_class['SPATESD_std']; ?>" type="hidden" style="border: none;" class="form-control form-control-sm">
								<input id="class_with_div_hidden" value="<?= $allocated_class['SPATESD_div']; ?>" type="hidden" style="border: none;" class="form-control form-control-sm">
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
			background: #7F00FF;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to right, #E100FF, #7F00FF);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to right, #E100FF, #7F00FF);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
		}

		.my-cust-bg {
			background: #7F00FF;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to right, #E100FF, #7F00FF);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to right, #E100FF, #7F00FF);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
			color: #fff;
			border: 1px solid #7F00FF;
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
								<input type="text" name="marks_class" id="marks_class" class="form-control form-control-sm" readonly value="<?= $allocated_class['SPATESD_std']; ?>">
							</div>
						</div>
						<div class="box ">
							<div class="form-group ml-1">
								<label for="">Division</label>
								<input type="text" name="marks_division" id="marks_division" class="form-control form-control-sm" readonly value="<?= $allocated_class['SPATESD_div']; ?>">
							</div>
						</div>
						<div class="box mobile-margin-top-minus-20">
							<div class="form-group ml-1">
								<label for="">Subject</label>
								<select name="marks_subject" class="form-control form-control-sm" id="marks_subject">
									<option selected disabled value="">Select Subject</option>
									<?php
									foreach ($all_subjects as $all_subject) {
									?>
										<option value="<?= $all_subject['stvsubj_id']; ?>"><?= $all_subject['stvsubj_subject']; ?></option>
									<?php } ?>
								</select>
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
								<?php
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
								<?php } ?>
							</tbody>
						</table>
						<input type="hidden" id="allo_standard" value="<?= $allocated_class['SPATESD_std']; ?>">
						<input type="hidden" id="allo_div" value="<?= $allocated_class['SPATESD_div']; ?>">
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
			background: #7F00FF;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to right, #E100FF, #7F00FF);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to right, #E100FF, #7F00FF);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
		}

		.my-cust-bg {
			background: #7F00FF;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to right, #E100FF, #7F00FF);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to right, #E100FF, #7F00FF);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
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
					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Admission Status</h5>
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

					<div class="table-responsive pb-0 mb-0">
						<table class="table table-bordered pb-0 " id="studentMarksTable">
							<thead>
								<tr>
									<th class="text-center">No.</th>
									<th>Name</th>
									<th>
										Status
									</th>
								</tr>
							</thead>
							<tbody id="tbodyMarks">
								<?php
								foreach ($admission_status as $adm_status) {
									$status_color = '';
									if ($adm_status['visiting_fees_status_of'] == "Full") {
										$status_color = "bg-success";
									} else {
										$status_color = "bg-secondary";
									}
								?>
									<tr>
										<td class="text-center"><?= $adm_status['STVCLAS_rollno']; ?></td>
										<td><?= $adm_status['SPRIDEN_surname'] . " " . $adm_status['SPRIDEN_student_name'] . " " . $adm_status['SPRIDEN_father_name']; ?></td>
										<td class="text-left">
											<div class="<?= $status_color; ?> text-center text-white ">
												<?= $adm_status['visiting_fees_status_of']; ?>
											</div>
										</td>

									</tr>
								<?php } ?>
							</tbody>
						</table>
						<input type="hidden" id="allo_standard" value="<?= $allocated_class['SPATESD_std']; ?>">
						<input type="hidden" id="allo_div" value="<?= $allocated_class['SPATESD_div']; ?>">
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
					student_id_marks: student_id_marks
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
	</script>
</body>

</html>
