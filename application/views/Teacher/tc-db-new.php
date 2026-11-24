<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>

<html lang="en">
<head>

	<title> | Olive English School</title>

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

	<!-- Boxicons CSS -->

	<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

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

											<img class="img-radius img-fluid wid-90" style="margin-top: -15px;"
												src="<?= base_url(); ?>images/user.png" alt="User image">

										</div>

									</div>

									<div class="col text-right pb-3">

									</div>

								</div>

							</div>

							<div class="text-center">

								<h6 class="mb-1 mt-3 olive-title-color">
									<?= $userData['SPRIDEN_surname'] . " " . $userData['SPRIDEN_student_name'] . " " . $userData['SPRIDEN_father_name']; ?>
								</h6>

								<p class="text-muted"
									style="font-size: 12px; font-weight: 600; padding-bottom: 0px; margin-bottom: 0px;">
									<?= $userData['SPRIDEN_student_id']; ?>
								</p>

								<p class="mb-3 olive-title-color"
									style="font-size: 12px; font-weight: 600; padding-bottom: 0px;">

									<?php

									if ($this->session->userdata('session_type') == "Random") {

										echo "Random Class";
									} else {

										if ($allocated_class != null) {

											echo $allocated_class['SPATESD_std'] . " - " . $allocated_class['SPATESD_div'];
										}
									}

									?>

								</p>

								<button id="btn-p" data-toggle="modal" data-target="#examplemodalprofile"
									class="btn btn-sm btn-olive"><i class="fa fa-user"></i> Profile</button>

								<a href="<?= base_url(); ?>index.php/Dashboard/logout" class="btn btn-sm btn-olive"> <i
										class="fas fa-sign-out-alt"></i> Logout</a>

								<a href="<?= base_url(); ?>index.php/lesson/index/<?= $userData['SPRIDEN_student_id']; ?>"
									style="border-radius: 20px;" class="btn btn-sm btn-outline-secondary"> <i
										class="fa fa-book"></i> Lesson</a>
								<a href="<?= base_url(); ?>/dashboard/forms" style="border-radius: 20px;"
									class="btn btn-sm"> <i class="fa fa-book"></i> Forms</a>

							</div>

							<hr class="wid-80 b-wid-3 my-4">
							<?php if ($this->session->flashdata('message')): ?>
								<div class="text-center alert alert-success flash-message">
									<span class="text-center text-primary">
										<?= $this->session->flashdata('message') ?>
									</span>
								</div>
							<?php endif; ?>

							<div class="row text-center label-row">

								<div class="col">
									<?php if (isset($allocated_class) && $allocated_class['SPATESD_teacher_id'] != '') { ?>
										<a
											href="<?= base_url('dashboard/restud?std=' . $allocated_class['SPATESD_std'] . '&div=' . $allocated_class['SPATESD_div'] . '') ?>">
											<h6 class="mb-1">

												<?= count($all_students); ?>

											</h6>

											<p class="mb-0">Students</p>
										</a>
									<?php } else { ?>

										<h6 class="mb-1">
											<a href="#">
												<?php echo '0' ?>
											</a>
										</h6>

										<p class="mb-0">Students</p>

									<?php } ?>

								</div>

								<div class="col">

									<h6 class="mb-1">
										<?= $total_pending; ?>
									</h6>

									<p class="mb-0">Pending Remarks</p>

								</div>

								<div class="col">

									<h6 class="mb-1">
										<?= $total_replied; ?>
									</h6>

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
				<!-- Add Remark Modal-->
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

			<form method="post" action="<?= base_url('dashboard/update_rollno') ?>">
				<div class="row" style="padding: 5px;">

					<div class="col-md-12">

						<div class="card">

							<div class="card-header bg-student-details">

								<h5 class="text-white">Student Details</h5>


								<button class="btn btn-success btn-sm" style="margin-left: 15px;" type="submit"
									id="update_rollNoButton"> <i class="fas fa-edit"></i> Update Roll No</button>

							</div>

							<style>
								#studentTable tr:hover {

									cursor: pointer;

									color: #318eb9;

									font-weight: normal;

									transition: all 0.2s ease-in-out;

								}

								#studentTable tr td,

								#studentTable th td {

									padding: 5px;

									font-size: 12px;

								}

								label {
									display: inline-block;
									margin-bottom: 0.1rem
								}
							</style>

							<div class="card-body student_style_table p-0">

								<div class="student_details_div">

									<table class="table table-bordered table-striped pb-0 mb-0" id="studentTable">

										<tbody id="tbody">

											<tr style="text-align: center;">

												<td style="font-weight: bold;">Profile</td>

												<td style="font-weight: bold; width:10%">Roll No</td>

												<td style="font-weight: bold; text-align: left;">Name</td>

												<td style="font-weight: bold; text-align: left;">Action</td>

											</tr>

											<?php
											$total_students = count($all_students);
											for ($i = 0; $i < $total_students; $i++) {

												$next = $i + 1;

											?>

												<tr>

													<td style="text-align: center; padding: 2px;" data-toggle="modal"
														data-target="#exampleModal" class="addFeedbackByClick"
														data-id="<?php echo $all_students[$i]['STVCLAS_code'] ?>">

														<img style="height: 30px; border-radius: 50%;"
															src="<?= base_url(); ?>images/user.png" alt="">

													</td>
													<!-- <?php echo $all_students[$i]['STVCLAS_code'] ?> -->

													<td>
														<input type="hidden" name="line_id<?= $next ?>"
															id="line_id<?= $next ?>"
															value="<?= $all_students[$i]['STVCLAS_code'] ?>">


														<input type="hidden" name="line_sid<?= $next ?>"
															id="line_sid<?= $next ?>"
															value="<?= $all_students[$i]['STVCLAS_student_id'] ?>">

														<input type="text" class="form-control form-control-sm text-left"
															style="border:none;"
															value="<?= $all_students[$i]['STVCLAS_rollno'] ?>"
															name="roll_no<?= $next ?>">
													</td>

													</td>

													<td data-toggle="modal" data-target="#exampleModal"
														class="addFeedbackByClick"
														data-id="<?php echo $all_students[$i]['STVCLAS_code'] ?>">

														<?php echo $all_students[$i]['SPRIDEN_surname'] . " " . $all_students[$i]['SPRIDEN_student_name'] . " " . $all_students[$i]['SPRIDEN_father_name'] ?>

													</td>

													<!--<td>-->

													<!--	<select name="change_status<?= $next; ?>" class="change-spriden-status" id="change_status<?= $next; ?>" data-id="<?= $next ?>" class="form-control form-control-sm" data-student-id="<?= $all_students[$i]['id'] ?>">-->

													<!--		<option <?= $all_students[$i]['spriden_student_status'] == 'A' ? 'selected' : ''; ?> value="A">A</option>-->

													<!--		<option <?= $all_students[$i]['spriden_student_status'] == 'I' ? 'selected' : ''; ?> value="I">I</option>-->

													<!--		<option <?= $all_students[$i]['spriden_student_status'] == 'N' ? 'selected' : ''; ?> value="N">N</option>-->

													<!--		<option <?= $all_students[$i]['spriden_student_status'] == 'null' ? 'selected' : ''; ?> value="null">Null</option>-->

													<!--	</select>-->

													<!--</td>-->
													<td>
														<?php if ($all_students[$i]['stvpcnf_whatsapp'] != ''):
															$student_id = $all_students[$i]['SPRIDEN_student_id'];
															$token = base64_encode($student_id);
															$share_url = base_url() . "profile?source=sharer&student-id=$student_id&token=$token";
															$message = "Olive English School - Student Profile Link \n";
															$message .= $share_url;
														?>
															<a target="_blank"
																href="https://api.whatsapp.com/send?text=<?= urlencode($message) ?>&phone=+91<?= $all_students[$i]['stvpcnf_whatsapp'] ?>">
																<i class='bx bxl-whatsapp'></i> Share Link
															</a>
														<?php endif; ?>
													</td>

												</tr>

											<?php } ?>

										</tbody>

									</table>
									<input type="hidden" name="total_students" value="<?= $total_students ?>">

								</div>

							</div>

						</div>

					</div>

				</div>
			</form>

			<!--- Hidden / Display Contact Card--->

			<div class="row mobile-margin-top-minus-20" style="padding: 5px;">

				<div class="col-md-12">

					<div class="card" id=studentIDCard>

						<div class="card-header bg-contact-card justify-content-center">

							<img src="<?= base_url(); ?>images/logoNewDevWhite.png" style="height: 40px;" alt="">

							<h5 class="text-center ml-2" style="color: #fff;">Olive English School - Student Contact
								Details</h5>

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

										<button type="button" class="btn btn-sm btn-remark-bg addFeedbackByClick1"
											data-toggle="modal" data-target="#exampleModal1">Add Remark</button>

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

					<form id="feedback-form" method="POST">

						<div class="card ">

							<div class="card-header bg-student-remark">

								<h5 class="text-white">Student Remarks</h5>

								<button class="btn btn-success btn-sm" style="margin-left: 15px;" type="submit"
									id="add_feedbackButton"> <i class="fas fa-edit"></i> Add Feedback</button>

							</div>

							<style>
								.remarkTable tr:hover {

									color: #36d1dc !important;

								}
							</style>

							<div class="card-body student_style_table remark p-0">

								<div class="student_details_div">

									<table class="table remarkTable table-bordered table-striped pb-0 mb-0"
										id="studentTable">

										<tbody id="tbody">

											<tr style="text-align: center;">

												<td style="font-weight: bold;">#</td>

												<td style="font-weight: bold; text-align: left;">Name</td>

												<td style="font-weight: bold; text-align: left;">Feedback</td>

											</tr>

											<?php

											$j = 1;

											for ($i = 0; $i < count($all_remarks); $i++) {

											?>

												<tr data-id="<?php echo $all_remarks[$i]['stvrmrk_id'] ?>">

													<td style="text-align: center;">

														<?= $i + 1; ?>

														<input type="hidden" name="total_rows_feedback"
															id="total_rows_feedback" value="<?= count($all_remarks) ?>">

														<input type="hidden" name="rowid<?= $i; ?>" id="rowid<?= $i; ?>"
															value="<?= $all_remarks[$i]['stvrmrk_id'] ?>">

													</td>

													<td>

														<?php echo $all_remarks[$i]['SPRIDEN_surname'] . " " . $all_remarks[$i]['SPRIDEN_student_name'] . " " . $all_remarks[$i]['SPRIDEN_father_name'] . "<span class='text-primary'> ( " . $all_remarks[$i]['stvrmrk_remark'] . " ) </span>"; ?>

													</td>

													<td><input type="text" class="form-control form-control-sm"
															name="feedback_data<?= $i; ?>" id="feedback_data<?= $i; ?>"
															value="<?= $all_remarks[$i]['stvrmrk_reply'] ?>"></td>

												</tr>

											<?php $j++; } ?>

										</tbody>

									</table>

								</div>

							</div>

						</div>

					</form>

				</div>

			</div>

			<!-- student Admission Status Card--->

			<div class="row" style="padding: 5px;">

				<div class="col-md-12">

					<div class="card ">

						<div class="card-header bg-student-remark">

							<h5 class="text-white">Balance Fees Report</h5>

						</div>

						<style>
							.remarkTable tr:hover {

								color: #36d1dc !important;

							}
						</style>

						<div class="card-body student_style_table remark p-0">

							<div class="student_details_div">

								<table class="table remarkTable table-bordered table-striped pb-0 mb-0"
									id="studentTable">

									<tbody id="tbodyMarkss">

										<tr>

											<td style="font-weight: bold; text-align: center;">Roll No</td>

											<td style="font-weight: bold;">Name</td>

											<td style="font-weight: bold;">Class</td>

											<td style="font-weight: bold;">Total Paid</td>

											<td style="font-weight: bold;">Due Date</td>

											<td style="font-weight: bold;">Balance</td>



										</tr>

										<?php

										if ($fees_details != null) {


											$i = 1;

											foreach ($fees_details as $fees_dt) {

												if (date('Y-m-d') == $fees_dt['due_date'] || $fees_dt['balance'] == $fees_dt['total_class_fees']) {

													$color = 'alert-danger';
												} else {

													$color = 'none';
												}

										?>
												<tr onclick="addFees(this)"
													class="<?= date('Y-m-d') == $fees_dt['due_date'] || $fees_dt['balance'] == $fees_dt['total_class_fees'] ? 'bg-danger text-white get-details' : ''; ?>"
													data-name="<?= $fees_dt['name'] ?>" data-phone="<?= $fees_dt['whatsapp'] ?>"
													data-id="<?= $fees_dt['id'] ?>" data-balance="<?= $fees_dt['balance'] ?>"
													data-due="<?= $fees_dt['due_date'] != '0000-00-00' && $fees_dt['due_date'] != '' ? date('d-M-Y', strtotime($fees_dt['due_date'])) : $fees_dt['due_date'] ?>"
													data-standard="<?= $fees_dt['STVCLAS_standard'] ?>"
													data-division="<?= $fees_dt['STVCLAS_class'] ?>">
													<td class="text-center">
														<?= $fees_dt['STVCLAS_rollno'] ?>
													</td>

													<td>
														<?= $fees_dt['name'] ?>
													</td>

													<td>
														<?= $fees_dt['STVCLAS_standard'] ?>
													</td>

													<td>
														<?= $fees_dt['total_paid'] ?>
													</td>

													<td>
														<?= $fees_dt['due_date'] != '0000-00-00' && $fees_dt['due_date'] != '' ? date('d-M-Y', strtotime($fees_dt['due_date'])) : $fees_dt['due_date'] ?></span>
													</td>

													<td>
														<?= $fees_dt['balance'] ?>
													</td>

												</tr>

											<?php $i++;
											}
										} else { ?>

											<tr>

												<td class="text-center" colspan="6">No Data Available</td>

											</tr>

										<?php } ?>

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

	<!-- <?php

			if ($allocated_class != null) {

				$dis_std = $allocated_class['SPATESD_std'];

				$dis_div = $allocated_class['SPATESD_div'];
			} else {

				$dis_std = '';

				$dis_div = '';
			}

			?> -->

	<!-- Feedback -->

	<!-- <style>

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

	</style> -->

	<!-- <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

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

							<td class="right-label">Feedback : </td>

							<td>

								<textarea class="form-control form-control-sm" name="student_remark_feedback" id="student_remark_feedback" cols="30" rows="3"></textarea>

							</td>

						</tr>

						<tr>

							<td class="right-label"></td>

							<td>

								<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close"> <i class="fas fa-window-close"></i> Cancel</button>



							</td>

						</tr>

					</table>

				</div>

			</div>

		</div>

	</div> -->







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

			background: #318eb9 !important;

		}



		.my-cust-bg {

			background: #318eb9 !important;

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

	<!-- ------------- Student Att ------------------- -->
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal2" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header">

					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student
						Attendance</h5>

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
				<!-- -------------changes----------------------- -->
				<form id="att_form">
					<!-- -----------changes----------- -->
					<input type="hidden" name="rowMax" id="rowMax" value="">

					<div class="modal-body p-0 pb-0 mb-0">

						<div class="grid2x2 p-1">

							<div class="box">

								<div class="form-group ml-1">

									<label for="">Date</label>

									<input type="date" class="form-control form-control-sm border border-secondary pl-2"
										name="attendancedate" id="attendancedate" value="<?php echo date('Y-m-d'); ?>">

								</div>

							</div>

							<div class="box">

								<div class="form-group ml-1">

									<label for="">Standard</label>

									<input type="text" name="class" id="class"
										class="form-control form-control-sm pl-2 border border-secondary" readonly
										value="<?= $dis_std; ?>">

								</div>

							</div>

							<div class="box mobile-margin-top-minus-20">

								<div class="form-group ml-1">

									<label for="">Division</label>

									<input type="text" name="division" id="division"
										class="form-control form-control-sm pl-2 border border-secondary" readonly
										value="<?= $dis_div; ?>">

								</div>

							</div>

							<div class="box mobile-margin-top-minus-20">
								<!-- -----------changes----------- -->
								<div class="form-group ml-1">
									<label for=""></label>
									<button type="button" onclick="stud_attendance()"
										class="btn btn-sm myBitton btn-secondary"><i class="fas fa-eye mt-1"></i>
										View</button>
									<!-- id="markAttendanceButton" -->
									<button id="mark-at-btn-id" type="submit"
										class="btn btn-sm myBitton my-cust-bg d-none"><i class="fas fa-user-plus"></i>
										Mark
										Attendance</button>

								</div>
								<!-- -------------changes----------------------- -->
							</div>

						</div>

						<div class="table-responsive pb-0 mb-0 mobile-margin-top-minus-5">

							<table class="table table-bordered pb-0 " id="studentAttendanceTable">

								<thead>

									<tr>

										<th class="text-center">Roll No.</th>

										<th>Name</th>

										<th>

											<input type="checkbox" checked id="selectAll" value="allPresent"> P

											<input type="checkbox" id="deselectAll" value="allAbsent"> A

										</th>
										<th>Comment</th>

									</tr>

								</thead>

								<tbody id="tbodyAttendance">



								</tbody>

							</table>

							<!-- <input type="hidden" id="allo_standard" value="<?= $dis_std; ?>">

						<input type="hidden" id="allo_div" value="<?= $dis_div; ?>"> -->
				</form>
			</div>

			<!-- <div class="pb-2 pr-2 text-right">

						<button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>

						<button type="button" id="markAttendanceButton" class="btn btn-sm btn-success">Mark Attendance</button>

					</div> -->

		</div>

	</div>

	</div>

	</div>
	<!-- --------------Student Att------------------ -->

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

			background: #318eb9 !important;





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

	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal11" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

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

								<input id="class_with_div" value="<?= $dis_std . " - " . $dis_div; ?>" type="text"
									style="border: none;" readonly class="form-control form-control-sm">

								<input id="class_with_div_std_hidden" value="<?= $dis_std; ?>" type="hidden"
									style="border: none;" class="form-control form-control-sm">

								<input id="class_with_div_hidden" value="<?= $dis_div; ?>" type="hidden"
									style="border: none;" class="form-control form-control-sm">

							</td>



						</tr>

						<tr>

							<td class="right-label">Event Title : </td>

							<td><input id="event_title" type="text" class="form-control form-control-sm"></td>

						</tr>

						<tr>

							<td class="right-label">Event Detail : </td>

							<td>

								<textarea class="form-control form-control-sm" name="event_details" id="event_details"
									cols="30" rows="3"></textarea>

							</td>

						</tr>

						<tr>

							<td class="right-label"></td>

							<td>

								<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"
									aria-label="Close"> <i class="fas fa-window-close"></i> Cancel</button>

								<button class="btn btn-success btn-sm" type="button" id="add_eventButton"> <i
										class="fas fa-edit"></i> Add Event</button>

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

			background: #318eb9 !important;

		}



		.my-cust-bg {

			background: #318eb9 !important;

			color: #fff;

			border: 1px solid #318eb9 !important;

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

	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal2112" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

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

											<td>
												<?= $i; ?>
											</td>

											<td>
												<?= $tmtb['stvttbl_time']; ?>
											</td>

											<td>
												<?= $tmtb['stvttbl_monday']; ?>
											</td>

											<td>
												<?= $tmtb['stvttbl_tuesday']; ?>
											</td>

											<td>
												<?= $tmtb['stvttbl_wednesday']; ?>
											</td>

											<td>
												<?= $tmtb['stvttbl_thurday']; ?>
											</td>

											<td>
												<?= $tmtb['stvttbl_friday']; ?>
											</td>

											<td>
												<?= $tmtb['stvttbl_saturday']; ?>
											</td>

										</tr>

									<?php }

									$i++;
								} else { ?>

									<tr>

										<td colspan="8" class="text-center">Time Table Not Generated For
											<?= $dis_std . " - " . $dis_div; ?>
										</td>

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

			background: #318eb9 !important;

		}



		.my-cust-bg {

			background: #318eb9 !important;

			color: #fff;

			border: 1px solid #318eb9 !important;

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
	<!-- Add Remark Modal-->
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal21" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header">

					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Marks
					</h5>

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

					.form-group {
						margin-bottom: 0px;

					}

					.box {
						margin: 0px;
						padding: 3px;
					}
				</style>

				<div class="modal-body p-0 pb-0 mb-0">

					<div class="grid2x2 p-1">

						<div class="box">

							<div class="form-grou">

								<label for="">Standard</label>

								<!-- <input type="text" name="marks_class" id="marks_class" class="form-control form-control-sm" readonly value="<?= $dis_std; ?>"> -->

								<select name="marks_class" class="form-control form-control-sm" id="marks_class" Required onchange="fetchStudentsForMarks()">

									<option value="" selected disabled>Select Standard</option>

									<?php foreach ($class as $st_class) { ?>

										<option value="<?= $st_class['stvfees_class']; ?>"><?= $st_class['stvfees_class']; ?></option>

									<?php } ?>

								</select>

							</div>

						</div>

						<div class="box ">

							<div class="form-group">

								<label for="">Division</label>

								<!-- <input type="text" name="marks_division" id="marks_division" class="form-control form-control-sm" readonly value="<?= $dis_div; ?>"> -->

								<select name="marks_division" class="form-control form-control-sm" id="marks_division" Required onchange="fetchStudentsForMarks()">

									<option value="" selected disabled>Select Division</option>

									<option value="A">A</option>

									<option value="B">B</option>

									<option value="C">C</option>

								</select>

							</div>

						</div>

						<div class="box mobile-margin-top-minus-20">
							<div class="form-group">
								<label for="">Exam Name</label>
								<select name="exam_name" class="form-control form-control-sm" id="exam_name" required onchange="fetchStudentsForMarks()">
									<option selected disabled value="">Select Exam</option>
									<option value="Individual">Individual</option>
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

						<div class="box mobile-margin-top-minus-20" id="individual_exam_box" style="display: none;">
							<div class="form-group">
								<label for="individual_exam">Individual Exam</label>
								<input type="text" name="individual_exam" id="individual_exam" class="form-control form-control-sm border border-dark" required>
							</div>
						</div>


						<div class="box mobile-margin-top-minus-20">

							<div class="form-group">

								<label for="">Subject</label>

								<select name="marks_subject" class="form-control form-control-sm" id="marks_subject" required onchange="fetchStudentsForMarks()">

									<option selected disabled value="">Select Subject</option>



								</select>

							</div>

						</div>

						<!-- update -->

						<style>
							.myBitton {
								margin-top: 0px;
							}
						</style>
						<div class="box mobile-margin-top-minus-20">

							<div class="form-group">

								<label for="">Exam Type</label>

								<select name="exam_type" class="form-control form-control-sm" id="exam_type" required onchange="fetchStudentsForMarks()">

									<option selected disabled value="">Exam Type</option>
									<option value="written">Written</option>
									<option value="oral">Oral</option>
									<option value="grade">Grade</option>
								</select>

							</div>

						</div>

						<!-- update -->

						<div class="box mobile-margin-top-minus-20">

							<div class="form-group">

								<label for="">Total Marks</label>

								<input type="text" name="total_marks" id="total_marks"
									class="form-control form-control-sm border border-dark" required>

							</div>

						</div>
						<!-- update -->
						<div class="box mobile-margin-top-minus-20">

							<div class="form-group">
								<button type="button" class="btn btn-sm myBitton my-cust-bg text-danger" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">Close</span>
								</button>

								<button type="button" id="fillMarksButton" class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-chart-bar"></i> Fill Marks</button>

							</div>

						</div>
						<!-- update -->
					</div>

					<div class="table-responsive pb-0 mb-0 mobile-margin-top-minus-5">

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

			background: #318eb9 !important;

		}



		.my-cust-bg {

			background: #318eb9 !important;

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

	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModal211" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header">

					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Student Details
					</h5>

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

					<form id="remark-form" method="post">

						<div class="grid2x2 p-1">

							<div class="box">

								<div class="form-group ml-1">

									<label for="">Standard</label>

									<select name="student_details_std" class="form-control form-control-sm"
										id="student_details_std">

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

									<select name="student_details_div" class="form-control form-control-sm"
										id="student_details_div">

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

									<input type="date" class="form-control form-control-sm" name="attendancedate"
										id="attendancedate" value="<?php echo date('Y-m-d'); ?>">

								</div>

							</div>

							<div class="box mobile-margin-top-minus-20">

								<!-- <div class="form-group row ml-1">

									<label for=""></label>

									<button type="button" id="findStudentByRemarkStdDivButton" class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-user"></i> View Students</button>

									<div class="row" style="display: none;" id="save">

										<button id="save-btn" type="submit" class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-save"></i> Save Remarks</button>

									</div>

								</div> -->
								<div class="form-group row d-flex" style=" position: relative; left: -4px;">
									<div style="width: 141px !important;">
										<label for=""></label>

										<button type="button" id="findStudentByRemarkStdDivButton"
											class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-user"></i> View
											Students</button>
									</div>
									<div>
										<div class="row" style="display: none;" id="save">

											<button id="save-btn" type="submit"
												class="btn btn-sm myBitton my-cust-bg"><i class="fas fa-save"></i> Save
												Remarks</button>

										</div>

									</div>
								</div>

							</div>

						</div>



						<div class="table-responsive pb-0 mb-0">

							<input type="hidden" class="form-control form-control-sm" name="total_rows" id="total_rows">

							<table class="table table-bordered pb-0 " id="studentMarksTable">

								<thead>

									<tr>



										<th>Name</th>

										<th>

											Remarks

										</th>

									</tr>

								</thead>

								<tbody id="students-for-remark-tbody">



									</td>

								</tbody>

							</table>

						</div>

					</form>

				</div>

			</div>

		</div>

	</div>

	<!--Teacher Profile-->

	<!-- Modal -->

	<style>
		#examplemodalprofile .modal-dialog,

		#examplemodalprofile .modal-dialog .modal-content,

		#examplemodalprofile .modal-dialog .modal-content .modal-header {



			border-color: #53769c;

		}

		#examplemodalpassword .modal-dialog,

		#examplemodalpassword .modal-dialog .modal-content,

		#examplemodalpassword .modal-dialog .modal-content .modal-header {



			border-color: #53769c;

		}



		#examplemodalprofile .modal-dialog .modal-content .modal-header {

			padding-top: 10px;

			padding-bottom: 10px;

			background: #318eb9 !important;

		}

		#examplemodalpassword .modal-dialog .modal-content .modal-header {

			padding-top: 10px;

			padding-bottom: 10px;

			background: #318eb9 !important;

		}



		.my-cust-bg {

			background: #318eb9 !important;

			color: #fff;

			border: 1px solid #318eb9 !important;

		}



		.my-cust-bg:hover {

			color: #fff;

		}



		#examplemodalprofile .modal-dialog .modal-content .modal-header {

			text-align: center !important;

		}


		#examplemodalpassword .modal-dialog .modal-content .modal-header {

			text-align: center !important;

		}







		#examplemodalprofile .modal-dialog .modal-content .modal-body .right-label {

			text-align: right;

		}

		#examplemodalpassword .modal-dialog .modal-content .modal-body .right-label {

			text-align: right;

		}



		#examplemodalprofile .modal-dialog .modal-content .modal-body table tr {

			padding: 0px;

		}

		#examplemodalpassword .modal-dialog .modal-content .modal-body table tr {

			padding: 0px;

		}



		#examplemodalprofile .modal-dialog .modal-content .modal-body table tr td {

			border: none;

			padding: 4px;

			font-size: 12px;

			font-weight: 400;

		}

		#examplemodalpassword .modal-dialog .modal-content .modal-body table tr td {

			border: none;

			padding: 4px;

			font-size: 12px;

			font-weight: 400;

		}

		select.form-control {
			color: #000;
		}

		.mystyle {
			padding: 15px 5px;
			border-radius: 5px;
			height: 25px;
			/* border:none; */
			border: 1px solid #e7e7ee;
		}
	</style>
	<!-- my js script -->
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="examplemodalprofile" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header">

					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Profile</h5>

					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

					</button>

				</div>

				<div class="container col py-3">
					<form action="<?php echo base_url() . 'index.php/dashboard/teacherProfile'; ?>" method="post">
						<input type="hidden" name="id" id="id"
							value="<?= $this->session->userdata('oliveMobileSystemUser'); ?>">
						<!-- <div class="form-group"> -->
						<label for="fname" class="form-xontrol-label"><b>Teacher Name</b></label>
						<input type="text" name="fname" id="fname" class="form-control mystyle"
							placeholder="Enter your first name">

						<label for="lname" class="form-control-label" style="margin-top: 15px;"><b>Surname </label>
						<input type="text" name="lname" id="lname" class="form-control mystyle"
							placeholder="Enter your last name">
						<!-- </div> -->
						<!-- <div class="form-group"> -->
						<!-- </div> -->

						<!-- <div class="form-group"> -->
						<label for="mname" class="form-control-label" style="margin-top: 15px;"><b>Father
								Name</b></label>
						<input type="text" name="mname" id="mname" class="form-control mystyle"
							placeholder="Enter your middle name">
						<!-- </div> -->
						<!-- <div class="form-group"> -->
						<label for="mobile" class="form-control-label" style="margin-top: 15px;"><b>Mobile
								Number</b></label>
						<input type="text" name="mobile" id="mobile" class="form-control mystyle"
							placeholder="Mobile Number">
						<!-- </div> -->
						<!-- <div class="form-group"> -->
						<label for="whatsapp" class="form-control-label" style="margin-top: 15px;"><b>Whatsapp
								Number</b></b></label>
						<input type="text" name="whatsapp" id="whatsapp" class="form-control mystyle"
							placeholder="Whatsapp Number">
						<label for="whatsapp" class="form-control-label" style="margin-top: 15px;"><b>Date of
								Birth</b></b></label>
						<input type="date" name="date_of_birth" id="date_of_birth" class="form-control mystyle"
							placeholder="Select DOB">
						<label for="whatsapp" class="form-control-label" style="margin-top: 15px;"><b>Blood
								Group</b></b></label>
						<input type="text" name="blood_group" id="blood_group" list="blood_list" class="form-control mystyle" style="color:black;">
						<datalist id="blood_list">
							<option value="Select">Select</option>
							<option value="A+">A+</option>
							<option value="A-">A-</option>
							<option value="B+">B+</option>
							<option value="B-">B-</option>
							<option value="AB+">AB+</option>
							<option value="AB-">AB-</option>
							<option value="O+">O+</option>
							<option value="O-">O-</option>
						</datalist>
						<label for="whatsapp" class="form-control-label" style="margin-top: 15px;"><b>Aadhar
								Number</b></b></label>
						<input type="text" name="aadhar_number" id="aadhar_number" class="form-control mystyle"
							placeholder="Enter Aadhar Number">
						<label for="whatsapp" class="form-control-label" style="margin-top: 15px;"><b>Email
								Address</b></b></label>
						<input type="text" name="email_address" id="email_address" class="form-control mystyle"
							placeholder="Enter Email Address">
						<div style="display:flex; justify-content: center">
							<button type="submit" class="btn btn-success float-left mt-3 w-50 h-10"><i
									class="fa fa-pen-to-square"></i> Update</button>

						</div>
						<div class="text-center mt-2 text-primary">
							<button id="btn-pass" data-toggle="modal" data-target="#examplemodalpassword"
								class="btn btn-sm text-primary" type="button"><i class="fa fa-key"></i> Change Password
							</button>
						</div>
					</form>
				</div>
				<div id="testd"></div>

				<!-- <div class="pb-2 pr-2 text-right">

						<button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>

						<button type="button" id="markAttendanceButton" class="btn btn-sm btn-success">Mark Attendance</button>

					</div> -->



			</div>

		</div>

	</div>

	<!-- --------------------------------------------------------------------->

	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="examplemodalpassword" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header">

					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Change Password
					</h5>

					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

					</button>

				</div>

				<div class="container col py-3">


					<form action="<?php echo base_url() . 'index.php/dashboard/teacherPassword'; ?>" method="post">
						<input type="hidden" name="id" id="id"
							value="<?= $this->session->userdata('oliveMobileSystemUser'); ?>">

						<label for="curpass" class="form-xontrol-label"><b>Current Password</b></label>
						<input autocomplete="off" type="text" name="curpass" id="curpass" class="form-control mystyle"
							placeholder="Enter Current Password">

						<label for="newpass" class="form-control-label" style="margin-top: 15px;"><b>New
								password</label>
						<input autocomplete="off" type="text" name="newpass" id="newpass" class="form-control mystyle"
							placeholder="Enter New Password">

						<label for="conpass" class="form-control-label" style="margin-top: 15px;"><b>Confirm New
								Password</b></label>
						<input autocomplete="off" type="text" name="conpass" id="conpass" class="form-control mystyle"
							placeholder="Confirm New Password">

						<div style="display:flex; justify-content: center">
							<button type="submit" class="btn btn-success float-left mt-3 w-50 h-10"><i
									class="fa fa-pen-to-square"></i> Update</button>

						</div>

					</form>
				</div>
				<div id="testd"></div>

				<!-- <div class="pb-2 pr-2 text-right">

						<button class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">Close</button>

						<button type="button" id="markAttendanceButton" class="btn btn-sm btn-success">Mark Attendance</button>

					</div> -->



			</div>

		</div>

	</div>
	<!-- ---------------------------------- KAMRAN CODE STARTS------------------------------- -->
	<!-- ---------------------------------Event Motel Start---------------------------------------- -->


	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="exampleModalEvent" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header" style="background-color: #318eb9 !important;">

					<h5 style="font-size: 14px;" class="modal-title text-white" id="exampleModalLabel">Event </h5>

					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

					</button>

				</div>

				<div class="modal-body">
					<?php

					$all_ev_count = count($events);

					$sty_he = '';

					if ($all_ev_count > 2) {

						$sty_he = "height : 400px";
					}

					?>
					<style>
						#card-event {

							overflow-y: auto;

						}

						.task-list {
							list-style: none;
							position: relative;
							margin: 0;
							padding: 5px 0;
						}
					</style>
					<div class="card-body" style="<?= $sty_he; ?>" id="card-event">
						<ul class="list-unstyled task-list">

							<?php
							$i = 0;
							foreach ($events as $all_event) {

							?>

								<li id="data-id" onclick="EventData('<?= ++$i ?>','<?= $all_event['stvevnt_id'] ?>')">

									<i class="feather icon-calendar task-icon bg-success" style="font-size: 12px;"></i>

									<p class="m-b-5">
										<?= date("d M", strtotime($all_event['stvevnt_start_date'])) ?>
									</p>

									<h6 class="text-muted">Title :
										<?= $all_event['stvevnt_title']; ?>
									</h6>

									<p>

										<?= $all_event['stvevnt_details']; ?>

									</p>

								</li>

							<?php } ?>

						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- -------------------------------Event Model End------------------------------------------ -->








	<!-- Required Js -->

	<?php include 'application/views/jsPages.php'; ?>

	<script>
		//--------------------------- fatch Event start -------------------------------- 
		let EventData = (id, stv_id) => {

			$.ajax({

				url: "<?= base_url('index.php/Event/fatch_event') ?>",

				method: "GET",
				data: {
					id: stv_id
				},
				dataType: "json",

				beforeSend: function() {

					$('#loader').html("<i class='bx bx-loader bx-spin'></i> Fetching Data ...");

				},

				success: function(data) {

					$("#add_eventButton").html('<i class="fas fa-edit"></i> Update Event');
					$('#loader').html("");


					if (data['header_data']) {

						$('#header_id').val(data['header_data'].stvevnt_id);
						$('#event_details').val(data['header_data'].stvevnt_details);
						console.log(data['header_data'].stvevnt_start_date);

						$('#event_title').val(data['header_data'].stvevnt_title);
						$('#event_start_date').val(data['header_data'].stvevnt_start_date);
						$('#event_end_date').val(data['header_data'].stvevnt_end_date);


						$('#update-btn').html("Update");


					} else {
						$('#header_id').val('');

						$('#event_start_date').val("");

						$('#event_end_date').val("");

						$('#event_title').val("");

						$('#event_details').val("");

						$('#save-btn').html("Save");

					}


					$("#exampleModalEvent").modal('hide')

				}

			});

		};
	</script>

	<!-- ------------------------fatch model event------------------------------ -->
	<!-- ---------------------------------- KAMRAN CODE ENDS------------------------------- -->

	<script>
		// my js script
		$(document).ready(function() {
			$('#btn-p').on('click', function() {
				var id = document.getElementById('id').value;
				$.ajax({
					url: "<?php echo base_url() ?>index.php/dashboard/fetchone",
					type: 'post',
					dataType: 'json',
					data: {
						id: id
					},
					success: function(result) {

						if (result.error) {
							alert("Error");
						} else {
							$('#fname').val(result.SPRIDEN_student_name);
							$('#lname').val(result.SPRIDEN_surname);
							$('#mname').val(result.SPRIDEN_father_name);
							$('#mobile').val(result.mobile);
							$('#whatsapp').val(result.whatsapp);
							$('#date_of_birth').val(result.SPRIDEN_birthdate);
							$('#aadhar_number').val(result.SPRIDEN_aadhar_no);
							$('#blood_group').val(result.SPRIDEN_BGRP);
							$('#email_address').val(result.SPRIDEN_EMAIL);
						}
					}
				})
			})

		})
	</script>

	<script>
		$(document).on('click', '.get-details', function() {

			var student_name = $(this).attr("data-name");

			var phone = $(this).attr("data-phone");

			var stud_id = $(this).attr("data-id");

			var teacher_id = '<?= $this->session->userdata('oliveMobileSystemUser') ?>';

			var balance = $(this).attr("data-balance");

			var due = $(this).attr("data-due");

			var std_class = $(this).attr("data-standard");

			var division = $(this).attr("data-division");

			if (phone != '') {

				var text = `OLIVE ENGLISH SCHOOL ${student_name} - ${std_class} ${division} Kindly pay the balance fees before due date ${due} and balance amount is ${balance}/-.  (ऑलिव इंग्लिश स्कूल  ${due} तारीख से पहले अपनी फीस जमा करे आप की बैलेंस फीस ${balance}/- है !)`;

				// send message on the whatsapp

				window.open(`https://api.whatsapp.com/send?phone=+91${phone}&text=${text}`);

				//add data into database

				$.ajax({

					url: "<?= base_url() ?>index.php/Dashboard/add_notification",

					method: "POST",

					data: {

						student_id: stud_id,

						teacher_id: teacher_id,

						message: text

					},

					success: function(res) {

						console.log(res);

					}

				});

			} else {

				alert("Whatsapp Number Not Available");

			}



		});
	</script>

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



		// Feedback Update Code

		// $('.getDataForUpdateFeedback').on('click', function() {

		// 	var rowID = $(this).attr("data-id");

		// 	$.ajax({

		// 		url: "<?= base_url(); ?>index.php/Dashboard/getDataForUpdateFeedback",

		// 		data: {

		// 			rowID: rowID

		// 		},

		// 		method: "POST",

		// 		dataType: "JSON",

		// 		success: function(data) {

		// 			$('#modal_stu_stuid').val(data.SPRIDEN_student_id);

		// 			$('#modal_stu_rowID').val(data.stvrmrk_id);

		// 			$('#modal_stu_name').val(data.SPRIDEN_surname + " " + data.SPRIDEN_student_name + " " + data.SPRIDEN_father_name);

		// 			$('#modal_stu_remark_date').val(data.stvrmrk_date);

		// 			$('#student_remark').val(data.stvrmrk_remark);

		// 		}

		// 	});

		// });



		$('#feedback-form').on('submit', function(e) {

			e.preventDefault();



			$.ajax({

				url: "<?= base_url(); ?>index.php/Dashboard/updateParentsFeedbackByTeacher",

				data: new FormData(this),

				contentType: false,

				processData: false,

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

						Swal("Failed to Add ")

					}

				}

			});



		});

		$('#remark-form').on('submit', function(e) {

			e.preventDefault();

			$.ajax({

				url: "<?= base_url(); ?>index.php/Dashboard/add_remark",

				data: new FormData(this),

				contentType: false,

				processData: false,

				method: "POST",

				beforeSend: function() {

					$('#save-btn').html('<i class="fa fa-spinner fa-spin"></i> Please Wait');

				},

				success: function(data) {

					if (data != "fail") {

						alert("Remark Added Successfully");

						window.location.reload();

					} else {

						$('#save-btn').html('<i class="fas fa-edit"></i> Add Remark');

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

			var std = [];

			var division = [];

			var dateOfAtt = $('#attendancedate').val();

			var loopto = 1;

			$('#tbodyAttendance').find('input[type="checkbox"]').each(function() {

				if ($(this).prop("checked") == true) {

					student_ids.push($(this).val());

					attendanceStatus.push('P');

				} else {

					student_ids.push($(this).val());

					attendanceStatus.push('A');

				}

				std.push($('#std' + loopto).val());

				division.push($('#div' + loopto).val());

				loopto++;

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

						Swal("Failed to Mark Attendance");

					}

				}

			});



		});

		document.getElementById('exam_name').addEventListener('change', function() {
			var selectedValue = this.value;
			var individualExamBox = document.getElementById('individual_exam_box');
			if (selectedValue === 'Individual') {
				individualExamBox.style.display = 'block';
			} else {
				individualExamBox.style.display = 'none';
			}
		});



		function fetchStudentsForMarks() {
			var std = $('#marks_class').val();
			var div = $('#marks_division').val();
			var exam = $('#exam_name').val();
			var subject = $('#marks_subject').val();
			var exam_type = $('#exam_type').val();

			$.ajax({
				url: "<?php echo base_url(); ?>index.php/Dashboard/getStudentsForFillMarks",
				data: {
					std,
					div,
					exam,
					subject,
					exam_type
				},
				method: "POST",
				dataType: "JSON",
				success: function(data) {
					if (data != "fail") {
						var html = '';
						let total_marks = parseFloat(data[0]?.total_marks) || 0;
						var gradeOptions = ['A+', 'A', 'B', 'C', 'D', 'E'];

						data.forEach(function(student, index) {
							var currentValue = student.obtain_marks || '';

							html += '<tr>';
							html += '<td class="text-center">' + (index + 1) + '</td>';
							html += '<td>' + [student.SPRIDEN_surname, student.SPRIDEN_student_name, student.SPRIDEN_father_name].filter(Boolean).join(' ') + '</td>';
							html += '<td class="text-left">';

							if (exam_type === 'grade') {
								html += '<select name="student_grade_input[]" class="studentGradeSelector form-control form-control-sm" style="width: 70px;">';
								html += '<option value="">Select</option>';
								gradeOptions.forEach(function(grade) {
									var selected = (currentValue === grade) ? 'selected' : '';
									html += `<option value="${grade}" ${selected}>${grade}</option>`;
								});
								html += '</select>';
							} else {
								html += `<input type="number" name="student_marks_input[]" 
                                    class="studentMarksSelector form-control form-control-sm" 
                                    style="width: 70px;" value="${currentValue}" min="0" step="0.01">`;
							}

							html += `<input type="hidden" value="${student.id}" name="student_id[]">`;
							html += '</td></tr>';
						});

						$('#total_marks').val(total_marks > 0 ? total_marks : '');
						$('#tbodyMarks').html(html);
					}
				},
				error: function() {
					console.error("Failed to fetch students");
				}
			});

		}



		$('#findStudentByRemarkStdDivButton').click(function() {

			var btn_text = '<i class="fas fa-user"></i> View Students';

			var btn_save = '<i class="fas fa-save"></i> Save Remarks';

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

					beforeSend: function() {

						$('#findStudentByRemarkStdDivButton').html("<i class='bx bx-loader bx-spin'></i>");

					},

					success: function(data) {

						$('#findStudentByRemarkStdDivButton').html(btn_text);



						if (data != "fail") {

							var html = '';

							for (var count = 0; count < data.length; count++) {

								var next = count + 1;

								html += '<tr>';

								html += '<td><input type="text" class="form-control form-control-sm" name="stud_name" id="stud_name" value="' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '"> <input type="hidden" class="form-control form-control-sm" name="stud_id' + next + '" id="stud_id' + next + '" data-id="' + next + '" value="' + data[count].id + '"> <input type="hidden" class="form-control form-control-sm" name="stud_std' + next + '" id="stud_std' + next + '" data-id="' + next + '" value="' + data[count].STVCLAS_standard + '"> </td>';

								html += `<td> <input type="text" class="form-control form-control-sm" name="stud_remark${next}" id="stud_remark${next}" data-id="${next}" value="" list="st_rm"> <datalist id="st_rm"> 

								<option value="">Remarks</option>

								<?php

								if ($all_remarks_masters != null) {

									foreach ($all_remarks_masters as $all_rem_mast) {

								?> <option value="<?= $all_rem_mast['stvrmst_data']; ?>"> 

																<?php }
														} ?>

									</datalist></td >`;

								html += '<input type="hidden" name="seq" id="seq" value="' + next + '">';

								html += '</tr>';

							}

							$('#students-for-remark-tbody').html(html);

							$('#save-btn').html(btn_save);

							$('#save').css("display", "inline-block");

						} else if (data == "fail") {

							alert("Class Not Allocated or Not a Subject Teacher");

							$('#students-for-remark-tbody').html('');

						}

						$('#total_rows').val(data.length);

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



		// changes mades 5/4/25

		$('#fillMarksButton').click(function() {
			// Get form values
			var formData = {
				stad: $('#marks_class').val(),
				divi: $('#marks_division').val(),
				subject: $('#marks_subject').val(),
				exam_name: $('#exam_name').val(),
				total_marks: $('#total_marks').val(),
				exam_type: $('#exam_type').val(),
				individual_exam: $('#individual_exam').val()
			};

			// Collect student data
			var values = [];
			var student_ids = [];
			var hasEmptyValues = false;

			if (formData.exam_type === 'grade') {
				$('.studentGradeSelector').each(function() {
					var val = $(this).val();
					if (!val) hasEmptyValues = true;
					values.push(val);
				});
			} else {
				$('.studentMarksSelector').each(function() {
					var val = $(this).val();
					if (val === "") hasEmptyValues = true;
					values.push(val);
				});
			}

			$('input[name="student_id[]"]').each(function() {
				student_ids.push($(this).val());
			});

			// Validate student data
			if (values.length === 0 || student_ids.length === 0) {
				Swal("Error", "No student data found to save", "error");
				return false;
			}

			if (hasEmptyValues) {
				Swal("Error", "Please provide values for all students", "error");
				return false;
			}

			// Prepare AJAX data
			var ajaxData = Object.assign({}, formData, {
				marks: values,
				student_id_marks: student_ids
			});

			// Submit data
			$.ajax({
				url: "<?php echo base_url(); ?>index.php/Dashboard/fillStudentsMarks",
				data: ajaxData,
				method: "POST",
				dataType: "JSON",
				beforeSend: function() {
					$('#fillMarksButton').html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
				},
				success: function(response) {
					
					if (response.status == "success") {
						// close the modal after successful
						$('#exampleModal21').modal('hide');
						Swal.fire("Success", "Data saved successfully", "success").then(function () {
      					window.location.reload();
    					});
					} else {
						Swal.fire("Error", "Failed to save data: " + (response || 'Unknown'), "error");
					}
				},
				error: function(xhr) {
					Swal.fire("Error", "Server error: " + (xhr.statusText || 'Unknown'), "error");

				},
				complete: function() {
					$('#fillMarksButton').html('<i class="fas fa-chart-bar"></i> Fill Marks').prop('disabled', false);
				}
			});
		});




		//get Division

		// $('#marks_class').change(function() {

		//     var std = $('#marks_class').val();

		//     if (std == null) {

		//         alert("Select Standard");

		//         return false;

		//     } else {

		//         $.ajax({

		//             url: "<?php echo base_url(); ?>index.php/Dashboard/getDivByStd",

		//             data: {

		//                 std: std

		//             },

		//             method: "POST",

		//             dataType: "JSON",

		//             success: function(data) {

		//                 if (data != "fail") {

		//                     var html = '<option value="" selected disabled>Select Division</option>';

		//                     for (var count = 0; count < data['division'].length; count++) {

		//                         html += '<option value="' + data['division'][count].division + '">' + data['division'][count].division + '</option>';

		//                     }

		//                     $('#marks_division').html(html);

		//                 }

		//             }

		//         });

		//     }

		// });



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



		$(document).on('change', '.change-spriden-status', function() {

			var data_id = $(this).attr("data-id");

			var student_id = $(this).attr("data-student-id");

			var status = $(this).val();

			$.ajax({

				url: "<?= base_url() ?>index.php/Dashboard/updateStudentStatus?student-id=" + student_id + "&status=" + status,

				method: "GET",

				success: function(res) {

					console.log(res);

				}

			})

		})
	</script>
	<!-- ----------------------add changes------------------------- -->
	<script>
		let stud_attendance = () => {

			let attendancedate = document.getElementById('attendancedate').value
			let stud_class = document.getElementById('class').value
			let division = document.getElementById('division').value
			$('#mark-at-btn-id').addClass("d-none");
			$("#tbodyAttendance").html('<tr><td colspan="4" class="text-center text-success">Loading Data</td></tr>');

			$.ajax({

				url: "<?= base_url('dashboard/stud_data') ?>",
				method: "POST",
				data: {
					att_date: attendancedate,
					class: stud_class,
					div: division
				},
				dataType: "JSON",
				success: function(data) {
					let str = ''
					let rowIdx = 0
					data.forEach(element => {

						let att = element.attendance_status == 'P' ? 'checked' : 'unchecked'

						str += `<tr id="${++rowIdx}">

					<td class="text-center">${element.STVCLAS_rollno}</td>

					<td>${element.name}</td>

					<td class="text-left"><input type="checkbox" value="p" ${att} name="attendance${rowIdx}" id="attendance${rowIdx}" class="studentRowSelector">

					<input type="hidden" name="std${rowIdx}" id="std${rowIdx}" value="${element.STVCLAS_standard}">

					<input type="hidden" name="div${rowIdx}" id="div${rowIdx}" value="${element.STVCLAS_class}">

					<input type="hidden" name="id${rowIdx}" id="id${rowIdx}" value="${element.attendance_id}">

					<input type="hidden" name="stud_id${rowIdx}" id="stud_id${rowIdx}" value="${element.id}">

				</td>
				<td style="padding:0px;">
				<input type="text" class="form-control form-control-sm border" placeholder="Absent Comment" name="comment${rowIdx}" id="comment${rowIdx}" value="${element.comment}">
				</td>
				</tr>`
					});

					$("#tbodyAttendance").html(str)
					$("#rowMax").val(rowIdx);
					$('#mark-at-btn-id').removeClass("d-none");
				}

			})

		}

		// stud_attendance()
	</script>

	<script>
		$("#att_form").on('submit', function(e) {

			e.preventDefault();

			$.ajax({
				url: "<?= base_url('dashboard/insert_att') ?>",
				method: "POST",
				data: new FormData(this),
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: function() {
					$("#mark-at-btn-id").html("Please Wait ...");
					$("#mark-at-btn-id").prop("disabled", true);
				},
				success: function(data) {
					alert(data);
					$("#mark-at-btn-id").html("<i class='fas fa-user-plus'></i> Mark Attendance");
					$("#mark-at-btn-id").prop("disabled", false);
					location.reload()
				}
			})

		})
	</script>

	<script>
		// Function to hide flash messages after 2 seconds
		function hideFlashMessages() {
			const flashMessages = document.querySelectorAll('.flash-message');
			flashMessages.forEach(message => {
				setTimeout(() => {
					message.style.display = 'none';
				}, 2000); // 2000 milliseconds = 2 seconds
			});
		}

		// Call the function when the page loads
		window.onload = hideFlashMessages;

		function addFees(element) {
			let id = element.getAttribute("data-id");

			if (element.classList.contains('bg-danger')) {
				$.ajax({
					url: "<?= base_url() ?>index.php/Dashboard/addFees",
					method: "POST",
					dataType: "json",
					data: {
						student_id: id
					},
					success: function(response) {},

				});

			}
		}
	</script>

	<!-- ------------------------add changes----------------------------- -->

</body>



</html>