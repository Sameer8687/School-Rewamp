<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Teacher Registration Form</title>
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
		<form action="<?php echo base_url(); ?>Teacher/add_review_sup" enctype="multipart/form-data" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->
				
				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->


				<div class="row margin-top-minus-50 mobile-margin-top-minus-40 mb-2">
					<div class="col-md-7">
						<button class="btn btn-secondary btn-sm"> Add Remark</button>
					</div>
				</div>
				<?php if ($this->session->userdata('msg') != '') { ?>
					<div class="row">
						<div class="col-md-12">
							<div class="alert <?= $this->session->userdata('class'); ?>">
								<?= $this->session->userdata('msg'); ?>
							</div>
						</div>
					</div>
				<?php } ?>
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
				<style>
					#add-table tr td {
						padding: 2px !important;
						border: none;
					}

					/* Extra small devices (phones, 600px and down) */
					@media only screen and (max-width: 600px) {}

					/* Small devices (portrait tablets and large phones, 600px and up) */
					@media only screen and (min-width: 600px) {}

					/* Medium devices (landscape tablets, 768px and up) */
					@media only screen and (min-width: 768px) {}

					/* Large devices (laptops/desktops, 992px and up) */
					@media only screen and (min-width: 992px) {}

					/* Extra large devices (large laptops and desktops, 1200px and up) */
					@media only screen and (min-width: 1200px) {
						.select-list-width {
							width: 30%;
						}
					}

					.lesson-table thead tr th,
					.lesson-table tbody tr td {
						text-transform: capitalize;
						font-size: 12px;
						border: 1px solid #77777730;
						padding: 5px !important;
					}
				</style>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">
									Lesson Management
								</h5>
							</div>
							<div class="card-body">
								<table class="table table-sm table-borderless" id="add-table" style="margin-top: -25px;">
									<tr>
										<td style="width: 30%;">Standard <span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<select readonly name="class" class="form-control form-control-sm select-list-width" id="class">
												<option value="">Select Standard</option>
												<?php foreach ($classes as $st_class) {

												?>
													<option <?= $lesson_record['stvlmst_std'] ==  $st_class['stvfees_class'] ? 'selected' : '' ?> value="<?= $st_class['stvfees_class']; ?>"><?= $st_class['stvfees_class']; ?></option>
												<?php } ?>
											</select>
											<input type="hidden" name="hidden_header_id" id="hidden_header_id" value="<?= $lesson_record['stvlmst_id']; ?>">
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Subject <span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<select readonly class="form-control form-control-sm view-lessons select-list-width" name="subject" id="subject">
												<option value="">Select Subject</option>
												<?php foreach ($subjects as $sub) { ?>
													<option <?= $lesson_record['stvlmst_sub'] ==   $sub['stvsubj_id'] ? 'selected' : '' ?> value="<?= $sub['stvsubj_id']; ?>"><?= $sub['stvsubj_subject']; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Lesson <span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<input readonly type="text" name="lesson_name" id="lesson_name" class="form-control form-control-sm" value="<?= $lesson_record['stvlmst_lesson'] ?>">
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Topics Covered <span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<textarea readonly name="lesson_remark" id="lesson_remark" cols="30" rows="3" class="form-control form-control-sm" value="<?= $lesson_record['stvlmst_teacher_comment'] ?>"></textarea>
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Teaching <span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<input <?= $lesson_record['stvlmst_teached'] ==  "Yes" ? 'checked' : '' ?> type="radio" name="teaching" id="teaching_yes" value="Yes"> Yes
											<input <?= $lesson_record['stvlmst_teached'] !=  "Yes" ? 'checked' : '' ?> type="radio" name="teaching" id="teaching_no" value="No"> No
											<span class="float-right" id="teaching_date_span"></span>
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Notes <span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<input <?= $lesson_record['stvlmst_notes'] ==  "Yes" ? 'checked' : '' ?> type="radio" name="notes" id="notes_yes" value="Yes"> Yes
											<input <?= $lesson_record['stvlmst_notes'] !=  "Yes" ? 'checked' : '' ?> type="radio" name="notes" id="notes_no" value="No"> No
											<span class="float-right" id="notes_date_span"></span>
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Exam Status <span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<input readonly <?= $lesson_record['stvlmst_exam_status'] ==  "Yes" ? 'checked' : '' ?> type="radio" name="exam_status" id="exam_status_yes" value="Yes"> Yes
											<input readonly <?= $lesson_record['stvlmst_exam_status'] !=  "Yes" ? 'checked' : '' ?> type="radio" name="exam_status" id="exam_status_no" value="No"> No
											<span class="float-right" id="exam_status_span"></span>
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Start Date<span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<input readonly type="date" class="form-control form-control-sm select-list-width" name="start_date" id="start_date" value="<?= $lesson_record['stvlmst_start_date'] ?>">
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">End Date<span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<input readonly type="date" class="form-control form-control-sm select-list-width" name="end_date" id="end_date" value="<?= $lesson_record['stvlmst_end_date'] ?>">
										</td>
									</tr>
									<tr>
										<td style="width: 30%;">Remark<span style="float:right">:</span> </td>
										<td style="width: 70%;">
											<textarea class="form-control form-control-sm" name="lesson_review" id="lesson_review"><?= $lesson_record['stvlmst_review'] ?></textarea>
										</td>
									</tr>
								</table>
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

	</script>
</body>

</html>
