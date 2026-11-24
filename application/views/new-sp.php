<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - Student Profile</title>

	<meta charset="utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="" />

	<meta name="author" content="Phoenixcoded" />



	<!--OG Tags-->

	<meta property="og:url" content="<?php echo base_url(); ?>profile" />

	<meta property="og:type" content="website" />

	<meta property="og:title" content="Student Profile" />

	<meta property="og:description" content="Olive English School - Student Profile" />

	<meta property="og:site_name" content="<?php echo base_url(); ?>" />

	<meta property="og:image" content="<?php echo base_url(); ?>images/logoNewDev.png" />



	<!---Include CSS Files-->

	<?php include 'application/views/cssPages.php'; ?>

	<link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />

	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/profile-student.css" />

	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/new-student-profile.css" />

	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

</head>



<body class="profile-bg">

	<div class="sticky-header">

		<img src="<?= base_url(); ?>images/logoNewDevWhite.png" class="img-fluid header-img" alt="">

		<span>Olive English School</span>

	</div>

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="card user-card user-card-2 shape-center">

					<div class="card-header border-0 p-2 pb-0">

						<div class="cover-img-block">

							<img src="<?= base_url(); ?>images/olive1.jpg" alt="" class="img-fluid">
							<input type="hidden" id="getFullScreenMode">

						</div>

					</div>

					<div class="card-body pt-0">

						<div class="user-about-block text-center">

							<div class="row align-items-end">

								<div class="col text-left pb-3"><a href="#!"><i class=""></i></a></div>

								<div class="col">

									<div class="position-relative d-inline-block">

										<img class="img-radius img-fluid wid-90" style="margin-top: -20px;"
											src="<?= base_url(); ?>images/user.png" alt="User image">

									</div>

								</div>

								<div class="col text-right pb-3">



								</div>

							</div>

						</div>

						<div class="text-center name-div">

							<h6 class="mb-1 mt-3 name-title">

								<?php
								$student_name = $spriden_details['SPRIDEN_surname'] . " " . $spriden_details['SPRIDEN_student_name'] . " " . $spriden_details['SPRIDEN_father_name'];
								echo $student_name;

								?>

							</h6>

							<p class="mb-1 text-muted">

								<?php

								echo "ID : " . $spriden_details['SPRIDEN_student_id'];

								if ($stvclas_data != null) {

									echo " &nbsp;&nbsp;Class : " . $stvclas_data['STVCLAS_standard'] . " - " . $stvclas_data['STVCLAS_class'];
								} else {

									echo "Not Assigned";
								}

								?>

							</p>
							<p>Academic Year: <?= $academic_year_detail['stvacdm_year'] ?></p>
							<hr class="wid-80 b-wid-3 my-4">
							<?php
							$student_name = str_replace(" ", "-", $student_name);
							$aadhar_link = base_url() . "data/student_adhar/student?student-id=$spriden_details[id]&sid=$spriden_details[SPRIDEN_student_id]&name=$student_name&token=$spriden_details[spriden_auth_token]";
							?>
							<a target="_blank" href="<?= $aadhar_link ?>" class="btn btn-danger text-white">
								Update Student Aadhar Details
							</a>
							<hr class="wid-80 b-wid-3 my-4">

							<div class="text-left teacher-div">

								<h6 class="mb-1">Teacher Details </h6>



								<p class="mb-1 text-muted">

									<i class="feather icon-user mr-2 f-18"></i>Name :
									<?= isset($teacher_data['name']) ? $teacher_data['name'] : ''; ?>

								</p>

								<div class="clearfix"></div>

								<?php
								$url = '';
								$whatsapp_number = '';
								$mobile = '';
								if (isset($teacher_data['whatsapp'])) {
									$url = "https://api.whatsapp.com/send?phone=+91" . $teacher_data['whatsapp'];
									$whatsapp_number = $teacher_data['whatsapp'];
									$mobile = $teacher_data['mobile'];
								}
								?>
								<a href="<?= $url ?>"
									class="mb-1 text-muted link-contact d-flex align-items-end text-h-primary"><i
										class="feather icon-message-square mr-2 f-18"></i>

									Whatsapp :
									<?= $whatsapp_number ?>

								</a>

								<div class="clearfix"></div>

								<a href="tel:+91<?= $mobile ?>"
									class="mb-1 text-muted link-contact d-flex align-items-end text-h-primary"><i
										class="feather icon-phone-call mr-2 f-18"></i>

									Call :
									<?= $mobile ?>

								</a>

							</div>

						</div>

						<?php

						$ip_check = array(

							'127.0.0.1',

							'::1'

						);



						if (!in_array($_SERVER['REMOTE_ADDR'], $ip_check)) {

							$quiz_url = "http://oliveenglishschool.com/quiz/student?student-id=" . $spriden_details['SPRIDEN_student_id'];
						} else {

							$quiz_url = "https://localhost/oliveQuiz/student?student-id=" . $spriden_details['SPRIDEN_student_id'];
						}

						?>
						<?php
						$ip_check = ['127.0.0.1', '::1'];

						// Get IP and values
						$remote_addr = $_SERVER['REMOTE_ADDR'] ?? '';
						$student_id = $spriden_details['SPRIDEN_student_id'] ?? '';
						$standard = $stvclas_data['STVCLAS_standard'] ?? '';
						
						if (in_array($remote_addr, $ip_check)) {
							// Local URL
							$lms_url = "http://localhost/LMS/Std?standard=$standard&student_id=$student_id";
						} else {
							// Live URL
							$lms_url = "https://admin.oliveenglishschool.com/LMS/Std?standard=$standard&student_id=$student_id";
						}
						?>

						<hr class="wid-80 b-wid-3 my-4">

						<div class="row text-center">

							<div class="col">

								<a href="<?= base_url(); ?>profile/logout" class="btn btn-sm w-100 header-button-round">
									<i class="fas fa-sign-out-alt"></i> Logout</a>

							</div>

							<div class="col">

								<a href="<?= $lms_url; ?>" class="btn btn-sm w-100 header-button-round"> <i
										class="fas fa-book-open"></i> Learning System </a>

							</div>

							<div class="col">

								<a target="_blank" href="<?= $quiz_url; ?>"
									class="btn btn-sm w-100 header-button-round"> <i class="fas fa-desktop"></i> Take
									Quiz</a>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>



		<?php

		$display_hold = "d-none";

		$display_data = "";

		if ($hold_data != null) {

			if ($hold_data['sprhold_status'] == "Yes") {

				$display_hold = "";

				$display_data = "d-none";
			} else {

				$display_hold = "d-none";

				$display_data = "";
			}
		}

		?>

		<div class="<?= $display_data; ?>">

			<div class="row">

				<div class="col-md-12">

					<div class="card table-card myfeesCard">

						<div class="card-header borderless fees-card-bg text-white">

							<h5 class="text-white">Fees Details (फीस की जानकारी)</h5>

						</div>

						<style>
							@media screen and (max-width: 600px) {

								div.text {

									font-size: 10px;

								}

							}
						</style>



						<div class="row text">
							<?php
							$both_bal = 0;
							$total_class_fees_lbl = isset($total_class_fees) ? $total_class_fees['stvfstr_fees'] : 0;
							$total_paid = 0;
							foreach ($fees_details as $fs) {
								$total_paid += (float) $fs['sprfees_amount'];
							}
							$bal_lbl = $total_class_fees_lbl - $total_paid;

							$last_year_bal = isset($total_last_year_fees) ? ($total_last_year_fees['final_balance'] != '' ? $total_last_year_fees['final_balance'] : 0) : 0;

							$both_bal = (float) $bal_lbl + (float) $last_year_bal;
							?>

							<div class="col-6"><label class="form-label ml-2 text-info">Total Fees (टोटल फीस) : <br>
									<?= $total_class_fees_lbl ?> Rs /-
								</label></div>
							<div class="col-6"><label class="form-label ml-2 text-success">Paid Fees (जमा फीस) : <br>
									<?= $total_paid ?> Rs /-
								</label></div>
							<div class="col-6"><label class="form-label ml-2 text-danger">Balance Fees (बाकी फीस) : <br>
									<?= $bal_lbl ?> Rs /-
								</label></div>
							<?php if ($last_year_bal > 0): ?>
								<div class="col-6">
									<label class="form-label ml-2 text-danger">Last Balance (पिछली बाकी फीस) : <br>
										<?= $last_year_bal ?> Rs /-
									</label>
								</div>
							<?php endif; ?>
							<div class="col-6"><label class="form-label ml-2 text-danger">All Balance (सभी बाकी फीस) :
									<br>
									<?= $both_bal ?> Rs /-
								</label></div>







						</div>



						<div class="card-body pb-0">

							<style>
								#feesTable tr td,

								#feesTable tr th {

									padding: 5px;

									font-size: 12px;

									background-color: #E0F7FA;

									color: #006064;

									border-color: #80CBC4;



								}



								#table-res-fees {

									overflow-y: auto;

								}
							</style>

							<?php

							$t_fees = count($fees_details);

							$size_height_tr = '';

							if ($t_fees > 4) {

								$size_height_tr = "height: 100px;";
							}

							?>

							<div class="table-responsive" id="table-res-fees" style="<?= $size_height_tr; ?>">

								<table class="table table-bordered pb-0 mb-0" id="feesTable">

									<thead>

										<tr>

											<th>Fees Amount</th>

											<th>Fees Date</th>

											<th>Submitted To</th>

										</tr>

									</thead>

									<tbody>

										<?php foreach ($fees_details as $fees_detail) {



										?>

											<tr>

												<td>
													<?= $fees_detail['sprfees_amount'] ?>
												</td>

												<td>
													<?= date('d/m/y', strtotime($fees_detail['sprfees_feesdate'])) ?>
												</td>

												<td>
													<?= $fees_detail['submited_to'] ?>
												</td>

											</tr>

										<?php } ?>

									</tbody>

								</table>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="card table-card review-card">

						<div class="card-header borderless remark-card-bg text-white">

							<h5 class="text-white">Teacher's Remark (टीचर का रिमार्क)</h5>

						</div>

						<div class="card-body pb-0">

							<style>
								#remarkTable tr td,

								#remarkTable tr th {

									padding: 5px;

									font-size: 12px;

									background-color: #E0F7FA;

									color: #006064;

									border-color: #80CBC4;



								}



								#table-res-teacher_rmrk {

									overflow-y: auto;

								}
							</style>

							<?php

							$t_remark = count($remark_details);

							$size_height_tr = '';

							if ($t_remark > 4) {

								$size_height_tr = "height: 150px;";
							}

							?>

							<div class="table-responsive" id="table-res-teacher_rmrk" style="<?= $size_height_tr; ?>">

								<table class="table table-bordered pb-0 mb-0" id="remarkTable">

									<thead>

										<tr>



											<th>Teacher Remark</th>

											<th>Date</th>

											<th>Feedback</th>

											<th>Date</th>

										</tr>

									</thead>

									<tbody>

										<?php foreach ($remark_details as $remark_detail) {

											if ($remark_detail['stvrmrk_date_of_reply'] == "0000-00-00") {

												$newDate = " ";
											} else {

												$newDate = date('d/m/y', strtotime($remark_detail['stvrmrk_date_of_reply']));
											}

										?>

											<tr>

												<td>
													<?= $remark_detail['stvrmrk_remark'] ?>
												</td>

												<td>
													<?= date('d/m/y', strtotime($remark_detail['stvrmrk_date'])) ?>
												</td>

												<td>
													<?= $remark_detail['stvrmrk_reply'] ?>
												</td>

												<td>
													<?= $newDate; ?>
												</td>

											</tr>

										<?php } ?>

									</tbody>

								</table>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="card table-card remark-card">

						<div class="card-header borderless remark-card-bg text-white">

							<h5 class="text-white">Teacher's Lesson (टीचर लेशन )</h5>

						</div>

						<div class="card-body pb-0">

							<style>
								#lessionTable tr td,

								#lessionTable tr th {

									padding: 5px;

									font-size: 10px;

									background-color: #E0F7FA;

									color: #006064;

									border-color: #80CBC4;



								}



								#table-res-teacher_lession {

									height: 200px;

									overflow-y: auto;

								}



								.lesson-box {

									background-color: #ededed;

									margin-bottom: 3px;

									border-bottom: 1px solid #77777750;

								}



								.lesson-box label {

									font-size: 10px;

									margin: 0;

									padding: 0;

								}
							</style>

							<?php

							$t_remark = count($remark_details);

							$size_height_tr = '';

							if ($t_remark > 4) {

								$size_height_tr = "height: 150px;";
							}

							?>

							<div class="table-responsive" id="table-res-teacher_lession"
								style="height: <?= count($lessons) > 4 ? '150px' : 'auto' ?>">

								<?php $i = 1;
								foreach ($lessons as $less) { ?>

									<div class="lesson-box pl-2 pr-2">

										<label>
											<span class="font-weight-bold" style="color: #0097A7 ;">
												<?= $less['stvsubj_subject']; ?>
											</span> -
											<span class=""></span>
											<?= $less['stvlmst_lesson']; ?>
										</label>
										<?php if ($less['stvlmst_teacher_comment'] != ''): ?>
											<br>
											<label for=""><span class="font-weight-bold">
													<?= $less['stvlmst_teacher_comment'] ?>
												</span></label>
										<?php endif; ?>
									</div>

								<?php $i++;
								} ?>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="card table-card review-card">

						<div class="card-header borderless remark-card-bg text-white">

							<h5 class="text-white">Online Exam (Quiz) Analysis (ऑनलाइन एग्जाम - क्विज)</h5>

						</div>

						<div class="card-body pb-0">

							<style>
								.quiz-card-bg {

									background: #1B5E20 !important;

								}



								#QuizTable tr td,

								#QuizTable tr th {

									padding: 5px !important;

									font-size: 12px !important;

									background-color: #E0F7FA;

									color: #1B5E20;

									border-color: #A5D6A7;

								}



								#table-res-quiz-analysis {

									overflow-y: auto;

								}
							</style>

							<?php

							$t_count_quiz_ana = count($quiz_details);

							$size_height = '';

							if ($t_count_quiz_ana > 4) {

								$size_height = "height: 150px;";
							}

							?>

							<div class="table-responsive" id="table-res-quiz-analysis" style="<?= $size_height ?>">

								<table class="table table-bordered pb-0 mb-0" id="QuizTable">

									<thead>

										<tr>

											<th>Title</th>

											<th>Date</th>

											<th>Marks</th>

										</tr>

									</thead>

									<tbody>

										<?php

										foreach ($quiz_details as $quiz_dt) {

										?>

											<tr class="view-details"
												data-id="<?= $quiz_dt['quiz_id'] ?> <?= $quiz_dt['student_id'] ?>">


												<td>

													<div class="d-inline-block align-middle">

														<p class="text-muted m-b-0">

															<?= $quiz_dt['quiz_name']; ?>

														</p>

													</div>

												</td>

												<td>

													<?= date('d M Y', strtotime($quiz_dt['exam_date'])); ?>

												</td>

												<td>

													<?= $quiz_dt['marks_scored']; ?>

													/

													<?= $quiz_dt['total_questions']; ?>

												</td>

											</tr>

										<?php } ?>

									</tbody>

								</table>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<?php

					$all_ev_count = count($all_events);

					$sty_he = '';

					if ($all_ev_count > 2) {

						$sty_he = "height : 250px";
					}

					?>

					<style>
						#card-event {

							overflow-y: auto;

						}
					</style>

					<div class="card task-card">

						<div class="card-header event-card-bg">

							<h5 class="text-white">Events, Messages & Other Information (मैसेज और जानकारी)</h5>

						</div>

						<div class="card-body" style="<?= $sty_he; ?>" id="card-event">

							<ul class="list-unstyled task-list">

								<?php

								foreach ($all_events as $all_event) {

								?>

									<li>

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

			<div class="row">

				<div class="col-md-12">

					<div class="card table-card review-card">

						<div class="card-header borderless remark-card-bg text-white">

							<h5 class="text-white">Marks Analysis (सब्जेक्ट मार्क्स)</h5>

						</div>

						<div class="card-body pb-0">

							<style>
								#remarkTable tr td,

								#remarkTable tr th {

									padding: 5px;

									font-size: 12px;

									background-color: #E0F7FA;

									color: #006064;

									border-color: #80CBC4;



								}



								#table-res-teacher_rmrk {

									overflow-y: auto;

								}
							</style>

							<?php

							$t_remark = count($remark_details);

							$size_height_tr = '';

							if ($t_remark > 4) {

								$size_height_tr = "height: 150px;";
							}

							// check if the visibility is on
							if (isset($visibility['marks_visibility']) && $visibility['marks_visibility'] == 'yes'):
							?>

								<div class="grid2x2 p-1">

									<div class="box">

										<div class="form-group ml-1">

											<label for="">Exam</label>

											<select name="mrk_examname" id="mrk_examname"
												class="form-control form-control-sm" onchange="fetchStudentMarks()">

												<option value="" selected disabled>Select Exam</option>

												<?php

												if (!empty($all_exams)) {

													foreach ($all_exams as $acdm_yr) {

												?>

														<option value="<?= $acdm_yr['stvexam_id'] ?>">
															<?= $acdm_yr['stvexam_name'] ?>
														</option>

												<?php }
												} ?>

											</select>

										</div>

									</div>

									<div class="box">

										<div class="form-group ml-1">

											<label for="">Subject Name</label>

											<select name="mrk_subname" id="mrk_subname" onchange="fetchStudentMarks()"
												class="form-control form-control-sm">

												<option value="" selected disabled>Select Subject</option>

												<?php

												if ($all_subjects != null) {

													foreach ($all_subjects as $all_sub) {

												?>

														<option value="<?= $all_sub['stvsubj_id'] ?>">
															<?= $all_sub['stvsubj_subject'] ?>
														</option>

												<?php }
												} ?>

											</select>

										</div>

									</div>

								</div>

								<style>
									#marks_panel_stat_card {

										display: none;

									}
								</style>

								<div class="row">
									<style>
										#marks-table thead tr th,
										#marks-table tbody tr td {
											padding: 3px !important;
											font-size: 12px !important;
										}
									</style>
									<div class="col-md-12" id="marks-table">
										<table class="table table-sm table-bordered table-striped">
											<thead>
												<tr>
													<th>#</th>
													<th>Exam</th>
													<th>Subject</th>
													<th>Type</th>
													<th>Total</th>
													<th>Obtain</th>
												</tr>
											</thead>
											<tbody id="marks-tbody" class="tbody-striped"></tbody>
										</table>
										<!-- <div id="bar-chart-1"></div> -->

									</div>

								</div>

							<?php else: ?>
								<div class="row mt-4">
									<div class="col-md-12 text-center">
										<h5 class="text-danger">Marks preview is not available</h5>
										<p class="mt-2">Contact school administration or This will be enable after Open Day.</p>
									</div>
								</div>
							<?php endif; ?>

						</div>

					</div>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="card">

						<div class="card-header attendance-card-bg">

							<?php

							$current_month = date('m');

							$today = date('Y-m-d');

							$first_day = date('Y-m-01', strtotime($today));

							$firstDayFormat = date('d M', strtotime($first_day));



							$last_day = date('Y-m-t', strtotime($today));

							$lastDayFormat = date('d M', strtotime($last_day));

							?>

							<h5 class="text-white">



								<span class="text-white" id="dynamic-analysis-title">Attendance (हाज़िरी)-

									<?= date('F'); ?>
								</span>

								<style>
									.change-select {

										position: absolute;

										right: 10px;

									}
								</style>

								<span class="change-select">

									<select name="change_dis_data_att" id="change_dis_data_att"
										class=" form-control form-control-sm">

										<?php

										for ($dis_i = 1; $dis_i <= 12; $dis_i++) {

										?>

											<option <?= $current_month == $dis_i ? 'selected' : '' ?> value="<?= $dis_i; ?>">
												<?php echo $month_name = date("M", mktime(0, 0, 0, $dis_i, 10)); ?>
											</option>

										<?php } ?>

									</select>

								</span>

							</h5>

						</div>

						<div class="card-body">

							<div class="row">

								<div class="col-md-5 mb-5">

									<!-- <div class="attendance-container">

									<?php

									for ($i = 0; $i < count($attendance_details); $i++) {

										$icon = date('d', strtotime($attendance_details[$i]['SPRATND_date']));

										if ($attendance_details[$i]['SPRATND_status'] == "P") {

											$class = "bg-success";
										} else {

											$class = "bg-danger";
										}

									?>

										<span class="attendance-status <?= $class; ?> text-white">

											<?= $icon; ?>

										</span>

									<?php } ?>

								</div> -->

									<style>
										#dynamic-container-days {

											display: flex;

											width: 90%;

											height: 250px;

											flex-direction: row;

											align-items: center;

											flex-wrap: wrap;

											text-align: center;

											margin: auto;

										}



										#dynamic-container-days .attendance-status {

											height: 30px;

											width: 30px;

											align-items: center;

											color: #fff;

											text-align: center;

											background-color: #607D8B;

											border-radius: 50%;

											margin: 10px;

										}



										#dynamic-container-days .attendance-status span {

											line-height: 30px;

										}



										.dis-att-stat {

											height: 10px;

											width: 10px;

											border-radius: 50%;

											background-color: red;

										}



										.bg-ba {



											border-radius: 30px;

										}



										.bg-success {

											background-color: #00C853 !important;

										}



										.bg-danger {

											background-color: #f44336 !important;

										}



										.bg-secondary {

											background-color: #607D8B !important;

										}



										.bg-sunday {

											background-color: #0097A7 !important;

										}
									</style>

									<div id="dynamic-container-days">

										<?php

										$maxDays = date('t');

										$currentDayOfMonth = date('j');

										for ($i = 0; $i < $maxDays; $i++) {

											$new_dis = $i + 1;

											$class = "bg-secondary";

											// $icon = date('d', strtotime($attendance_details[$i]['SPRATND_date']));

											// if ($attendance_details[$i]['SPRATND_status'] == "P") {

											// 	$class = "bg-success";

											// } else {

											// 	$class = "bg-danger";

											// }

											$new_cr_dt = date('Y') . "-" . date('m') . "-" . $new_dis;

											for ($j = 0; $j < count($attendance_details); $j++) {

												$icon = date('d', strtotime($attendance_details[$j]['SPRATND_date']));



												if (date('D', strtotime(date($new_cr_dt))) == 'Sun') {

													$class = "bg-sunday";
												} else {

													if ($icon == $new_dis) {

														if ($attendance_details[$j]['SPRATND_status'] == "P") {

															$class = "bg-success";
														} elseif ($attendance_details[$j]['SPRATND_status'] == "A") {

															$class = "bg-danger";
														} else {

															$class = "bg-secondary";
														}

														break;
													} else {

														continue;
													}
												}
											}

										?>

											<div class="attendance-status text-white <?= $class; ?>">

												<span class="text-white">
													<?= $new_dis; ?>
												</span>

											</div>

										<?php } ?>

									</div>

								</div>

								<style>
									.alert-dis {

										display: flex;

										align-items: center;

										text-align: center;

										width: 100%;

										margin-top: 10px;



									}



									.alert-dis span {

										margin: auto;

										width: 100px;

										color: #fff;

										border-radius: 40px;

										padding: 2px;

										font-size: 12px;

										font-weight: 600;

									}



									.alert-dis span.pre {

										background-color: #00C853;

									}



									.alert-dis span.abs {

										background-color: #f44336;

									}



									.alert-dis span.sun {

										background-color: #0097A7;

									}



									@media only screen and (max-width: 600px) {

										.alert-dis {

											margin-top: 50px;

										}

									}
								</style>

								<div class="col-md-7">

									<div class="row">

										<div class="col-md-12">

											<div class="alert-dis">

												<span class="pre">Present</span>

												<span class="abs">Absent</span>

												<span class="sun">Sunday</span>

											</div>

										</div>

										<div class="col-md-12 mobile-margin-top-minus-40">

											<div class="alert-dis">

												<span class="pre">हाज़िर</span>

												<span class="abs">गैरहाज़िर</span>

												<span class="sun">सन्डे</span>

											</div>

										</div>



										<div class="col-md-12">

											<hr>

											<div id="attendance-chart"></div>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>
	<!-- Modal -->
	<div class="modal fade" id="DueDatePopUp" tabindex="-1" aria-labelledby="DueDatePopUpLabel" aria-hidden="true"
		data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<style>
					.text-olive {
						color: #53769c;
					}
				</style>
				<!-- <div class="modal-header">
					<h5 class="modal-title" id="DueDatePopUpLabel">Fees Due DATE </h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div> -->
				<div class="modal-body">
					<h5 class="modal-title text-danger" id="DueDatePopUpLabel">Fees Due Date Notification <span
							class="text-primary float-right">
							<?= $counter . "/3" ?>
						</span> </h5>
					<hr>
					<?php $invalid_dates = ['0000-00-00', '', null, ' '] ?>
					<p class="mt-2 text-olive">
						Dear Parents, <br>
						Fees Due Date of your child <span class="font-weight-bold">
							<?= $spriden_details['SPRIDEN_surname'] . " " . $spriden_details['SPRIDEN_student_name'] . " " . $spriden_details['SPRIDEN_father_name'] ?>
						</span> is due on <span class="font-weight-bold">
							<?= in_array($max_due_date['due_date'], $invalid_dates) ? date('d-M-Y') : date('d-M-Y', strtotime($max_due_date['due_date'])) ?>
						</span>. Kindly pay the fees.
					</p>

					<p class="mt-3 text-olive">
						अस्सलामु अलैकुम पेरेंट्स
						आप का बच्चा / बच्ची <span class="font-weight-bold">
							<?= $spriden_details['SPRIDEN_surname'] . " " . $spriden_details['SPRIDEN_student_name'] . " " . $spriden_details['SPRIDEN_father_name'] ?>
						</span> की फीस जमा करने की तारीख <span class="font-weight-bold">
							<?= in_array($max_due_date['due_date'], $invalid_dates) ? date('d-M-Y') : date('d-M-Y', strtotime($max_due_date['due_date'])) ?>
						</span> आ गयी है। स्कूल आकर फीस जमा करे
					</p>

					<div class="text-center mt-3">
						<button class="btn btn-sm btn-success" id="ihatekismatji" onclick="addDueDateAction()"
							type="button">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!---Include JS Files-->

	<?php include 'application/views/jsPages.php'; ?>

	<!-- <script src="<?= base_url(); ?>assets/js/pages/chart-apex.js"></script> -->

	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>





	<script>
		let due_date = '<?= $max_due_date['due_date'] ?>';
		let today = '<?= date('Y-m-d') ?>';
		let counter = '<?= $counter ?>';
		let balance = "<?= isset($both_bal) ? $both_bal : 0 ?>";
		let student_id = "<?= $this->session->userdata('StudentProfileSession') ?>"
		if (today == due_date && counter <= 3) {
			$('#DueDatePopUp').modal('show')
		}

		function fetchStudentMarks() {

			showDataLoaderAndNoData();

			let exam = document.getElementById("mrk_examname").value;
			let sub = document.getElementById("mrk_subname").value;
			$.ajax({
				url: '<?= base_url('Profile/fetchStudentMarks'); ?>',
				data: {
					exam,
					sub,
					student_id
				},
				dataType: "JSON",
				method: "POST",
				success: function(data) {
					showMarks(data);
				}
			})
		}

		function showDataLoaderAndNoData(action = 'loader') {
			let tbody = document.getElementById("marks-tbody");
			let content = action == 'loader' ? '<span class="text-info">Loading Result, Please Wait ...</span>' : "<span class='text-danger'>Result data not available</span>";
			tbody.innerHTML = `<tr class="text-center">
									<td colspan="6">${content}</td>
								</tr>`;
		}

		function showMarks(data) {
			let tbody = document.getElementById("marks-tbody");
			let content = '';
			if (data.length > 0) {
				let counter = 0;
				data.forEach((record) => {
					content += `<tr>
									<td>${++counter}</td>
									<td>${record?.stvexam_name ?? ''}</td>
									<td>${record?.stvsubj_subject ?? ''}</td>
									<td>${record?.sprmark_exam_type ?? ''}</td>
									<td>${record?.sprmark_total_marks ?? ''}</td>
									<td>${record?.sprmark_mark ?? ''}</td>
								</tr>`;
				});

				tbody.innerHTML = content;
			} else
				showDataLoaderAndNoData('no-data');
		}



		function addDueDateAction() {
			$.ajax({
				url: '<?= base_url('Profile/addDueDateAction'); ?>',
				data: {
					due_date: due_date,
					student_id: student_id
				},
				method: "POST",
				success: function(data) {
					if (data == 'success') {
						$('#DueDatePopUp').modal('hide')
					}
				}
			})
		}
		$('#change_dis_data_att').change(function() {

			var month_num = $('#change_dis_data_att').val();



			if (month_num == " ") {

				swal("Select Month");

				return false;

			} else {

				$.ajax({

					url: "<?php echo base_url(); ?>index.php/Profile/findAttendancebyMonth",

					data: {

						month: month_num

					},

					dataType: "JSON",

					method: "POST",

					success: function(data) {

						if (data != "fail") {

							var months = ["January", "February", "March", "April", "May", "June",

								"July", "August", "September", "October", "November", "December"

							];



							var selectedMonthName = months[month_num - 1];



							//Change Header Title

							$('#dynamic-analysis-title').html("Attendance (अटेंडेंस)- " + selectedMonthName);

							//generate / display attendnace date with status

							//======= GET TOTAL DAYS IN MOnths

							function daysInMonth(month, year) {

								return new Date(year, month, 0).getDate();

							}

							var d = new Date();

							var year_this = d.getFullYear();

							// var month_this = d.getMonth();

							// month_this = month_this + 1;

							var total_day_this_month = daysInMonth(month_num, year_this);



							var output = '';

							for (var counter = 0; counter < total_day_this_month; counter++) {

								var bg_color = "bg-secondary";

								var display_num = counter + 1;



								//Display Sundays in Attendance

								// var d = new Date("2021-01-28");

								// var n = d.getDay();

								var dateString = year_this + "-" + month_num + "-" + display_num;

								var da = new Date(dateString);

								var n = da.getDay();

								console.log(n);

								if (n == 0) {

									bg_color = "bg-sunday";

								}



								for (var jcount = 0; jcount < data['attendance_details'].length; jcount++) {

									var now_date = data['attendance_details'][jcount].SPRATND_date;

									var substrs = now_date.substr(8, 2);

									// if (data['attendance_details'][jcount].SPRATND_status == "P" && substrs == display_num) {

									// 	bg_color = "bg-success";



									// } else if (data['attendance_details'][jcount].SPRATND_status == "A" && substrs == display_num) {

									// 	bg_color = "bg-danger";



									// } else {

									// 	bg_color = "bg-secondary";



									// }

									if (n == 0) {

										bg_color = "bg-sunday";

									} else {

										if (substrs == display_num) {

											if (data['attendance_details'][jcount].SPRATND_status == "P") {

												bg_color = "bg-success";

											} else if (data['attendance_details'][jcount].SPRATND_status == "A") {

												bg_color = "bg-danger";

											} else {

												bg_color = "bg-secondary";

											}

											break;

										} else {

											continue;

										}

									}

								}

								output += '<div class="attendance-status ' + bg_color + '"><span>' + display_num + '</span></div>';

							}

							// genearte attendance days

							$('#dynamic-container-days').html(output);

							//console.log(data['attendance_details']);



							// Display Chart For the stats

							var int_pre_sm = parseInt(data['present_days']);

							var int_abs_sm = parseInt(data['absent_days']);

							// display_chart(int_pre_sm, int_abs_sm);



						}

					}

				});

			}

		});



		$('#mrk_subnames').change(function() {

			var newstat = [];

			var catgry = [];

			var subname = $('#mrk_subname').val();

			if (subname == null) {

				alert("Select Subject");

				return false;

			} else {

				$.ajax({

					url: "<?php echo base_url(); ?>index.php/Profile/getAllMarksBySubject",

					data: {

						subname: subname

					},

					dataType: "JSON",

					method: "POST",

					success: function(data) {

						if (data != "fail" && data != null && data.length != 0) {

							//console.log(data);

							for (var count = 0; count < data.length; count++) {

								newstat.push(data[count].marks);

								catgry.push(data[count].stvexam_name);

							}



							console.log(newstat);

							// var newstat = [44, 55, 57, 56, 61, 58, 93];

							// var catgry = ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug']



							display_chart(newstat, catgry);

						} else {

							$('#bar-chart-1').html("");

							swal("Oops !!!", "No Record Found", "error");

						}

					}

				})

			}

		});



		function display_chart(newSat, catgry) {

			$(function() {

				var options = {

					chart: {

						height: 300,

						type: 'bar',

					},

					plotOptions: {

						bar: {

							horizontal: false,

							columnWidth: '20%'

						},

					},

					dataLabels: {

						enabled: false

					},

					colors: ["#0097A7"],

					stroke: {

						show: true,

						width: 2,

						colors: ['transparent']

					},

					series: [{

						name: 'Marks',

						data: newSat

					}],

					xaxis: {

						categories: catgry,

					},

					yaxis: {

						title: {

							text: 'Subject Marks'

						}

					},

					fill: {

						opacity: 1



					},

					tooltip: {

						y: {

							formatter: function(val) {

								return val + " Marks"

							}

						}

					}

				}

				var chart = new ApexCharts(

					document.querySelector("#bar-chart-1"),

					options

				);

				$('#bar-chart-1').html("");

				chart.render();

			});

		}

		$(document).on('click', '.view-details', function() {
			var dataId = $(this).data("id");
			var values = dataId.split(" ");
			console.log(values[0]);
			console.log(values[1]);
			var url = "<?= base_url() ?>index.php/result/quiz_result?quiz-id=" + values[0] + "&student-id=" + values[1];
			window.location = url;
		});
	</script>



</body>



</html>