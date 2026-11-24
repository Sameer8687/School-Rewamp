<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Dashboard</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />

	<!---Include CSS Files-->
	<?php include 'cssPages.php'; ?>
</head>

<body class="">
	<!-- [ Pre-loader ] start -->
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
	<!-- [ Pre-loader ] End -->


	<!-- MODAL TO SHOW DUE DATE STUDENT DATA  -->
	<!-- Modal -->
	<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
		aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Student Fees Due Date (
						<?= date('d-M-Y') ?>)
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-sm table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>ID</th>
								<th>Name</th>
								<th>Class</th>
								<th>Total Fees</th>
								<th>Total Paid</th>
								<th>action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 0;
							if ($all_due_records != null):
								foreach ($all_due_records as $rec):
									?>
									<tr>
										<td>
											<?= ++$i; ?>
										</td>
										<td>
											<?= $rec['SPRIDEN_student_id'] ?>
										</td>
										<td class="text-left">
											<a target="_blank"
												href="<?= base_url('fees?source=due-date&student-id=' . $rec['id']) ?>">
												<?= $rec['name'] ?>
											</a>
										</td>
										<td>
											<?= $rec['STVCLAS_standard'] ?>
										</td>
										<td>
											<?= $rec['class_fees'] ?>
										</td>
										<td>
											<?= $rec['total_paid'] ?>
										</td>
										<td>
											<?php if ($rec['mobile'] != ''): ?>
												<a href="tel:+91<?= $rec['mobile'] ?>"></a>
											<?php endif; ?>
										</td>
									</tr>
									<?php
								endforeach;
							else: ?>
								<tr>
									<td colspan="6" class="text-center">No Records for Today's Date</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL ENDS HERE  -->


	<!--Include Sidebar -->
	<?php include 'sidebar.php'; ?>

	<!--Include Top Navbar-->
	<?php include 'topNav.php'; ?>


	<!-- [ Main Content ] start -->
	<div class="pc-container">
		<div class="pcoded-content">
			<!-- [ Main Content ] start -->
			<style>
				/* =========================================
				COMPACT DASHBOARD STAT CARD
				========================================= */
				.card {
					box-shadow: none !important;
					border: 1px solid #e9e9e9 !important;
				}

				.dash-card {
					border: 1px solid #e9e9e9 !important;
					/* match nav's subtle 1px hairline feel */
					border-radius: 8px;
					/* was 12px — closer to template/nav */
					background: #ffffff;
					/* box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); */
					/* was 0 3px 14px */
					box-shadow: none;
					overflow: hidden;
				}

				.dash-card .card-body {
					padding: 15px 18px;
				}


				/* =========================================
				SECTION
				========================================= */

				.dash-section {
					padding: 0;
				}

				.dash-section+.dash-section {
					margin-top: 3px;
					padding-top: 7px;
					border-top: 1px solid #edf0f4;
				}


				/* =========================================
					SECTION TITLE  (no icon, no bold)
				========================================= */

				.dash-head {
					display: flex;
					align-items: center;
					margin: 0 0 5px 0;
					font-size: 12px;
					font-weight: 500;
					color: #253247;
					text-transform: uppercase;
					letter-spacing: 0.6px;
				}


				/* =========================================
				STAT ROW
				========================================= */

				.dash-row {
					display: flex;
					align-items: center;
					justify-content: space-between;

					height: 25px;

					padding: 0 3px;

					font-size: 12px;
				}

				.dash-row .lbl {
					color: #7b8798;
					font-weight: 500;
				}

				.dash-row .val {
					color: #273449;
					font-size: 13px;
					font-weight: 560;
				}


				/* =========================================
					TOTAL ROW
					========================================= */

				.dash-row.total {
					margin-top: 3px;
					height: 27px;

					padding: 0 8px;

					border-radius: 6px;
					background: #fff6f6;
				}

				.dash-row.total .lbl {
					color: #f04b58;
					font-size: 10px;
					font-weight: 700;
					letter-spacing: 0.5px;
				}

				.dash-row.total .val {
					color: #f04b58;
					font-size: 14px;
					font-weight: 560;
				}


				/* =========================================
				LOGIN
				========================================= */

				.dash-login {
					display: flex;
					align-items: center;
					justify-content: space-between;

					height: 27px;

					padding: 0 3px;

					font-size: 11px;
					color: #7b8798;
				}

				.dash-login a {
					display: inline-flex;
					align-items: center;
					gap: 5px;

					color: #2497ad;
					font-size: 11px;
					font-weight: 700;

					text-decoration: none;
				}

				.dash-login a:hover {
					color: #147c91;
					text-decoration: none;
				}


				/* =========================================
						SMALL SCREEN
						========================================= */

				@media (max-width: 575px) {

					.dash-card .card-body {
						padding: 13px 15px;
					}

					.dash-login {
						font-size: 10px;
					}

					.dash-login a {
						font-size: 10px;
					}
				}
			</style>
			<style>
				/* =========================================
				QUICK LINKS CARD
				Matches Dashboard Stats Card
				========================================= */

				.quick-links-card {
					border: 1px solid #edf0f4;
					border-radius: 8px;
					background: #ffffff;
					overflow: hidden;
				}

				.quick-links-card .card-body {
					padding: 15px 18px;
				}


				/* =========================================
				HEADER  (no icon, no bold)
				========================================= */

				.quick-links-head {
					display: flex;
					align-items: center;

					margin: 0 0 10px 0;
					padding: 0 0 8px 0;

					border-bottom: 1px solid #edf0f4;

					font-size: 10px;
					font-weight: 500;
					color: #253247;

					text-transform: uppercase;
					letter-spacing: 0.6px;
				}

				/* =========================================
				LINKS
				========================================= */

				.quick-links-list {
					list-style: none;
					margin: 0;
					padding: 0;
				}

				.quick-links-list li {
					margin: 0;
					padding: 0;

					border-bottom: 1px solid #edf0f4;
				}

				.quick-links-list li:last-child {
					border-bottom: 0;
				}


				/* =========================================
				LINK ROW
				========================================= */

				.quick-links-list li a {
					display: flex;
					align-items: center;

					height: 25px;

					padding: 0 3px;

					color: #7b8798;

					font-size: 12px;
					font-weight: 500;

					text-decoration: none;
				}


				/* Small arrow only */
				.quick-links-list li a .link-arrow {
					width: 18px;

					margin-right: 5px;

					color: #9aa5b4;

					font-size: 9px;
				}

				/* =========================================
   ACTIVITY LIST (like Quick Links)
   ========================================= */
				.activity-list {
					list-style: none;
					margin: 0;
					padding: 0;
				}

				.activity-list li {
					display: flex;
					align-items: flex-start;
					padding: 5px 3px;
					border-bottom: 1px solid #edf0f4;
					font-size: 12px;
					line-height: 1.6;
				}

				.activity-list li:last-child {
					border-bottom: 0;
				}

				.activity-label {
					color: #7b8798;
					font-weight: 500;
					margin-right: 5px;
					flex-shrink: 0;
				}

				.activity-value {
					color: #273449;
					font-weight: 500;
					word-break: break-word;
				}


				/* =========================================
				HOVER
				========================================= */

				.quick-links-list li a:hover {
					color: #6c5ce7;
					text-decoration: none;
				}


				/* =========================================
				MOBILE
				========================================= */

				@media (max-width: 575px) {

					.quick-links-card .card-body {
						padding: 13px 15px;
					}

					.quick-links-list li a {
						font-size: 11px;
					}
				}
			</style>
			<div class="row margin-top-minus-40">
				<!-- Left: col-4 -->
				<div class="col-xl-4 col-md-4">

					<!-- Combined Card -->
					<div class="card proj-t-card dash-card">
						<div class="card-body">

							<!-- STUDENTS -->
							<div class="dash-section">

								<p class="dash-head">
									<span>Total Students</span>
								</p>

								<div class="dash-row">
									<span class="lbl">Active</span>
									<span class="val"><?= $active['active_student'] ?></span>
								</div>

								<div class="dash-row">
									<span class="lbl">RTE</span>
									<span class="val"><?= $rte['rte'] ?></span>
								</div>

								<div class="dash-row total">
									<span class="lbl">TOTAL</span>
									<span class="val">
										<?= (isset($active['active_student']) ? (int) $active['active_student'] : 0)
											+ (isset($rte['rte']) ? (int) $rte['rte'] : 0) ?>
									</span>
								</div>

							</div>


							<!-- TEACHERS -->
							<div class="dash-section">

								<p class="dash-head">
									<span>Teachers</span>
								</p>

								<div class="dash-row">
									<span class="lbl">Active</span>
									<span class="val"><?= $count['teacher_total'] ?></span>
								</div>

							</div>


							<!-- CURRENT LOGIN -->
							<div class="dash-section">

								<p class="dash-head">
									<span>Current Login</span>
								</p>

								<div class="dash-login">

									<span>
										<?= date("d M Y", strtotime($currentLoginData['stvlgin_date'])) ?>

										&nbsp;&middot;&nbsp;

										<?= date('g:i A', strtotime($currentLoginData['stvlgin_time'])) ?>
									</span>

									<a href="<?= base_url(); ?>stvlogh">
										<i class="fas fa-history"></i>
										History
									</a>

								</div>

							</div>

						</div>
					</div>

					<!-- Quick Links Card -->
					<div class="card quick-links-card mb-3">

						<div class="card-body">

							<!-- Header -->
							<h6 class="quick-links-head">

								<?= ($this->session->userdata('oliveSchoolDashboardUser') == '1')
									? 'Dashboard Links'
									: 'Quick Links'; ?>

							</h6>


							<!-- Links -->
							<ul class="quick-links-list">

								<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '1'): ?>

									<li>
										<a target="_blank" href="<?= base_url(); ?>FeesDashboard/Fees_db">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>Fees Dashboard</span>

										</a>
									</li>

									<li>
										<a target="_blank" href="<?= base_url(); ?>Attend_DB">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>Attendance Dashboard</span>

										</a>
									</li>

								<?php else: ?>

									<li>
										<a href="https://oliveenglishschool.com/hrms/" class="hr-link" target="_blank">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>HR Portal</span>

										</a>
									</li>

									<!-- <li>
												<a href="<?= base_url(); ?>report/aadhar_report">
													<i class="fas fa-caret-right link-arrow"></i>
													<span>Aadhar Card Report</span>
												</a>
											</li> -->

									<li>
										<a href="<?= base_url(); ?>admission-form">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>Admission Form</span>

										</a>
									</li>

									<li>
										<a href="<?= base_url(); ?>fees">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>Fees Form</span>

										</a>
									</li>

									<li>
										<a href="<?= base_url(); ?>fees-report">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>Fees Report</span>

										</a>
									</li>

									<li>
										<a href="<?= base_url(); ?>reports/students/daily-fees-report">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>Daily Fees Report</span>

										</a>
									</li>

									<li>
										<a href="<?= base_url(); ?>report/teacher_activity_report">

											<i class="fas fa-caret-right link-arrow"></i>
											<span>Teacher's Activity Report</span>

										</a>
									</li>

									<!-- Removed: Student Attendance -->
									<!-- Removed: Lc For Aadhar -->

								<?php endif; ?>

							</ul>

						</div>

					</div>
				</div>


				<!-- ===================Daily Fees Report Stats Card===================== -->
				<!-- Right: col-8 - Daily Fees Report Stats Card -->
				<div class="col-xl-8 col-md-8">

					<!-- ============================= -->
					<!-- CARD 1 : Daily Fees Report    -->
					<!-- ============================= -->
					<div class="card quick-links-card mb-3">
						<div class="card-body">

							<!-- Header with "View Full Report" link -->
							<h6 class="quick-links-head" style="justify-content: space-between;">
								<span>
									Daily Fees Report
								</span>

								<?php
								$yearSeg = ($dash_year && $dash_year !== 'current') ? '/' . $dash_year : '';
								$reportUrl = base_url('report/daily_fees_report' . $yearSeg)
									. '?from-date=' . $dash_from
									. '&to-date=' . $dash_to
									. '&new_admission=' . $dash_new_admission;
								?>
								<a href="<?= $reportUrl ?>" target="_blank"
									style="font-size: 10px; font-weight: 700; color:#2497ad; text-transform: none; letter-spacing: 0;">
									View Full Report <i class="fas fa-external-link-alt"></i>
								</a>
							</h6>

							<!-- Filter form -->
							<form method="get" action="" class="mb-3" id="dailyFeesForm">
								<div class="row align-items-end">

									<div class="col-md-3 col-6">
										<label class="small mb-1">From</label>
										<input type="date" name="dash_from" value="<?= $dash_from ?>"
											class="form-control form-control-sm">
									</div>

									<div class="col-md-3 col-6">
										<label class="small mb-1">To</label>
										<input type="date" name="dash_to" value="<?= $dash_to ?>"
											class="form-control form-control-sm">
									</div>

									<div class="col-md-3 col-6">
										<label class="small mb-1">Year</label>
										<select name="dash_year" class="form-control form-control-sm">
											<option value="current" <?= $dash_year === 'current' ? 'selected' : '' ?>>
												Current</option>
											<option value="all" <?= $dash_year === 'all' ? 'selected' : '' ?>>All</option>
										</select>
									</div>

									<div class="col-auto">
										<label class="small mb-1 d-none d-md-block">&nbsp;</label>
										<button type="submit" class="btn btn-sm btn-outline-primary btn-block"
											style="padding: 2px 8px; font-size: 13px;">
											Search
										</button>
									</div>

								</div>
							</form>

							<!-- Stats row -->
							<div class="row">

								<!-- Current Fees -->
								<div class="col-md-4">
									<div class="dash-section">
										<p class="dash-head">
											<span>Current</span>
										</p>
										<div id="df-current-breakdown">
											<?php if (!empty($dash_fees_stats['current_fees_breakdown'])): ?>
												<?php foreach ($dash_fees_stats['current_fees_breakdown'] as $b): ?>
													<div class="dash-row">
														<span class="lbl"><?= ucfirst($b['payment_mode']) ?></span>
														<span class="val"><?= number_format($b['amount']) ?></span>
													</div>
												<?php endforeach; ?>
											<?php else: ?>
												<div class="dash-row">
													<span class="lbl">No records</span>
													<span class="val">0</span>
												</div>
											<?php endif; ?>
										</div>

										<div class="dash-row total">
											<span class="lbl">TOTAL</span>
											<span class="val" id="df-current-total">
												<?= number_format($dash_fees_stats['current_total'] ?? 0) ?>
											</span>
										</div>
									</div>
								</div>

								<!-- Previous Fees -->
								<div class="col-md-4">
									<div class="dash-section">
										<p class="dash-head">
											<span>Previous</span>
										</p>

										<div id="df-previous-breakdown">
											<?php if (!empty($dash_fees_stats['previous_fees_breakdown'])): ?>
												<?php foreach ($dash_fees_stats['previous_fees_breakdown'] as $b): ?>
													<div class="dash-row">
														<span class="lbl"><?= ucfirst($b['payment_mode']) ?></span>
														<span class="val"><?= number_format($b['amount']) ?></span>
													</div>
												<?php endforeach; ?>
											<?php else: ?>
												<div class="dash-row">
													<span class="lbl">No records</span>
													<span class="val">0</span>
												</div>
											<?php endif; ?>
										</div>

										<div class="dash-row total">
											<span class="lbl">TOTAL</span>
											<span class="val" id="df-previous-total">
												<?= number_format($dash_fees_stats['previous_total'] ?? 0) ?>
											</span>
										</div>
									</div>
								</div>

								<!-- Grand Total -->
								<div class="col-md-4">
									<div class="dash-section">
										<p class="dash-head">
											<span>Grand Total</span>
										</p>

										<div class="dash-row" style="height: 55px;">
											<span class="lbl">Collected</span>
											<span class="val" id="df-grand-total"
												style="font-size: 22px; color:#35a563;">
												₹
												<?= number_format($dash_fees_stats['grand_total'] ?? 0) ?>
											</span>
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>

					<!-- ============================= -->
					<!-- CARD 2 : Teacher Activity     -->
					<!-- ============================= -->
					<div class="card quick-links-card mb-3">
						<div class="card-body">

							<!-- Header with "View Full Report" link -->
							<h6 class="quick-links-head" style="justify-content: space-between;">
								<span>
									Teacher's Daily Activity
								</span>

								<?php
								$tchReportUrl = base_url('report/teacher_activity_report')
									. '?activity_date=' . $tch_from
									. '&toDateValue=' . $tch_to;
								?>
								<a href="<?= $tchReportUrl ?>" target="_blank"
									style="font-size: 10px; font-weight: 700; color:#2497ad; text-transform: none; letter-spacing: 0;">
									View Full Report <i class="fas fa-external-link-alt"></i>
								</a>
							</h6>

							<!-- Filter form -->
							<form method="get" action="" class="mb-3" id="teacherActivityForm">
								<div class="row align-items-end">

									<div class="col-md-4 col-6">
										<label class="small mb-1">From</label>
										<input type="date" name="tch_from" value="<?= $tch_from ?>"
											class="form-control form-control-sm">
									</div>

									<div class="col-md-4 col-6">
										<label class="small mb-1">To</label>
										<input type="date" name="tch_to" value="<?= $tch_to ?>"
											class="form-control form-control-sm">
									</div>

									<div class="col-auto">
										<label class="small mb-1 d-none d-md-block">&nbsp;</label>
										<button type="submit" class="btn btn-sm btn-outline-primary btn-block"
											style="padding: 2px 8px; font-size: 13px;">
											View
										</button>
									</div>

								</div>
							</form>

							<?php
							/* ────────────────────────────────────────────
							   Build 5 lists of teacher names.
							   A teacher is added only if their count >= 1.
							   ──────────────────────────────────────────── */
							$attendance_names = [];
							$remark_names = [];
							$absent_sms_names = [];
							// $quiz_names = [];
							// $marks_names = [];
							
							if (!empty($tch_activities)) {
								foreach ($tch_activities as $act) {
									$name = trim($act['teacher_name'] ?? '');
									if ($name === '')
										continue;

									if ((int) ($act['total_attendance'] ?? 0) >= 1)
										$attendance_names[] = $name;
									if ((int) ($act['total_remark'] ?? 0) >= 1)
										$remark_names[] = $name;
									if ((int) ($act['total_absent_sms'] ?? 0) >= 1)
										$absent_sms_names[] = $name;
									// if ((int) ($act['total_quiz'] ?? 0) >= 1)
									// 	$quiz_names[] = $name;
									// if ((int) ($act['total_marks'] ?? 0) >= 1)
									// 	$marks_names[] = $name;
								}
							}
							?>

							<!-- Stats rows - tabular layout like Quick Links -->
							<ul class="activity-list">
								<li>
									<span class="activity-label">Attendance:</span>
									<span class="activity-value" id="ta-attendance">
										<?= !empty($attendance_names) ? htmlspecialchars(implode(', ', $attendance_names)) : '<em>No attendance taken by any teacher.</em>' ?>
									</span>
								</li>
								<li>
									<span class="activity-label">Remarks:</span>
									<span class="activity-value" id="ta-remarks">
										<?= !empty($remark_names) ? htmlspecialchars(implode(', ', $remark_names)) : '<em>No remarks by teacher.</em>' ?>
									</span>
								</li>
								<li>
									<span class="activity-label">Absent Message:</span>
									<span class="activity-value" id="ta-absent-sms">
										<?= !empty($absent_sms_names) ? htmlspecialchars(implode(', ', $absent_sms_names)) : '<em>No absent messages sent by any teacher.</em>' ?>
									</span>
								</li>

							</ul>

						</div>
					</div>

				</div>







			</div>
		</div>
		<!-- [ Main Content ] end -->
	</div>
	<!-- [ Main Content ] end -->
	<!---Include JS Files-->
	<?php include 'jsPages.php'; ?>
	<script>
		$(document).ready(function () {
			var chart_data = <?php echo json_encode($chart_data) ?>;
			var series = [];
			var value = [];
			chart_data.forEach(element => {
				series.push(element.STVCLAS_standard);
				value.push(parseFloat(element.TOTAL_STUDENTS));
			});
			$(function () {
				var options = {
					chart: {
						height: 350,
						type: 'bar',
					},
					plotOptions: {
						bar: {
							horizontal: false,
							columnWidth: '55%'
						},
					},
					dataLabels: {
						enabled: false
					},
					colors: ["#53769c"],
					stroke: {
						show: true,
						width: 2,
						colors: ['transparent']
					},
					series: [{
						name: 'Total Students',
						data: value
					}],
					xaxis: {
						categories: series,
					},
					yaxis: {
						title: {
							text: 'Total Students'
						}
					},
					fill: {
						opacity: 1
					},
					tooltip: {
						y: {
							formatter: function (val) {
								return val + " Students"
							}
						}
					}
				}
				var chart = new ApexCharts(
					document.querySelector("#bar-chart-1"),
					options
				);
				chart.render();
			});
		});

		// $('#redirect_to_report').click(function () {
		// 	var url = $('#form_links_report').val();
		// 	if (url != '') {
		// 		window.location = url;
		// 	}
		// });

		$('#redirect_to_report').click(function () {
			var url = $('#form_links_report').val();

			if (url != '') {
				// check if selected option is Student Profile
				if ($('#form_links_report option:selected').text() === 'Student Profile') {
					window.open(url, '_blank'); // new tab
				} else {
					window.location.href = url; // same tab
				}
			}
		});

		$('#student_list_search').change(function () {
			var id = $(this).val().trim();
			$.ajax({
				url: "<?= base_url() ?>index.php/Student/fetchReadmissionDetails?student-id=" + id + "&source=db",
				method: "GET",
				dataType: "JSON",
				success: function (res) {
					if (res['student_details'] != null) {
						// Student Details
						$('#name_stu').val(res['student_details'].SPRIDEN_surname + " " + res['student_details'].SPRIDEN_student_name + " " + res['student_details'].SPRIDEN_father_name);
						// Class Details
						$('#clas_stu').val(res['class_details'].STVCLAS_standard);
						// Fees Paid Details
						var html = '';
						var style = '';
						if (res['fees_details'] != null) {
							for (var loop = 0; loop < res['fees_details'].length; loop++) {
								if (res['fees_details'][loop].balance > 0) {
									style = "color: red;";
								}
								html += `<tr>
									<td style="width: 10%; ">${loop + 1}</td>
									<td style="width: 15%; "> ${res['fees_details'][loop].class} </td>
									<td style="width: 15%; ">${res['fees_details'][loop].class_fees}</td>
									<td style="width: 15%; ">${res['fees_details'][loop].total_paid}</td>
									<td style="width: 15%; ">${res['fees_details'][loop].exumption}</td>
									<td style="width: 15%;  ${style}">${res['fees_details'][loop].balance}</td>
									<td style="width: 15%; ">${res['fees_details'][loop].academic_year}</td>
								</tr>`;
							}
						} else {
							html += '<tr><td colspan="7" style="text-align:center;">No Data Available</td></tr>';
						}
						$('#tbody-main').html(html);
					}
				}
			});
		});
		$('#student_list_search').keyup(function () {
			var id = $(this).val().trim();
			if (id == '') {
				$('#name_stu').val('');
				// Class Details
				$('#clas_stu').val('');
				$('#tbody-main').html('');
			}
		});

	</script>

	<script>
		/* ────────────────────────────────────────────
		   HELPERS
		   ──────────────────────────────────────────── */
		function ucfirst(str) {
			return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
		}

		function number_format(num) {
			return parseInt(num || 0).toLocaleString('en-IN');
		}

		function escapeHtml(text) {
			var div = document.createElement('div');
			div.appendChild(document.createTextNode(text));
			return div.innerHTML;
		}

		function formatTeachers(arr, emptyMsg) {
			if (!arr || arr.length === 0) {
				return '<em>' + emptyMsg + '</em>';
			}
			return escapeHtml(arr.join(', '));
		}

		/* ────────────────────────────────────────────
		   DAILY FEES — AJAX submit
		   ──────────────────────────────────────────── */
		$(document).on('submit', '#dailyFeesForm', function (e) {
			e.preventDefault();

			$.ajax({
				url: '<?= base_url("welcome/ajax_daily_fees_stats") ?>',
				method: 'GET',
				data: $(this).serialize(),
				dataType: 'JSON',
				success: function (res) {
					if (!res.success) return;
					var s = res.stats || {};

					// Current breakdown
					var html = '';
					if (s.current_fees_breakdown && s.current_fees_breakdown.length) {
						s.current_fees_breakdown.forEach(function (b) {
							html += '<div class="dash-row">'
								+ '<span class="lbl">' + ucfirst(b.payment_mode) + '</span>'
								+ '<span class="val">' + number_format(b.amount) + '</span>'
								+ '</div>';
						});
					} else {
						html = '<div class="dash-row">'
							+ '<span class="lbl">No records</span>'
							+ '<span class="val">0</span>'
							+ '</div>';
					}
					$('#df-current-breakdown').html(html);
					$('#df-current-total').text(number_format(s.current_total || 0));

					// Previous breakdown
					var html2 = '';
					if (s.previous_fees_breakdown && s.previous_fees_breakdown.length) {
						s.previous_fees_breakdown.forEach(function (b) {
							html2 += '<div class="dash-row">'
								+ '<span class="lbl">' + ucfirst(b.payment_mode) + '</span>'
								+ '<span class="val">' + number_format(b.amount) + '</span>'
								+ '</div>';
						});
					} else {
						html2 = '<div class="dash-row">'
							+ '<span class="lbl">No records</span>'
							+ '<span class="val">0</span>'
							+ '</div>';
					}
					$('#df-previous-breakdown').html(html2);
					$('#df-previous-total').text(number_format(s.previous_total || 0));

					// Grand total
					$('#df-grand-total').text('₹ ' + number_format(s.grand_total || 0));
				}
			});
		});

		/* ────────────────────────────────────────────
		   TEACHER ACTIVITY — AJAX submit
		   ──────────────────────────────────────────── */
		$(document).on('submit', '#teacherActivityForm', function (e) {
			e.preventDefault();

			$.ajax({
				url: '<?= base_url("welcome/ajax_teacher_activity_stats") ?>',
				method: 'GET',
				data: $(this).serialize(),
				dataType: 'JSON',
				success: function (res) {
					if (!res.success) return;

					$('#ta-attendance').html(formatTeachers(res.attendance, 'No attendance taken by any teacher.'));
					$('#ta-remarks').html(formatTeachers(res.remarks, 'No remarks by teacher.'));
					$('#ta-absent-sms').html(formatTeachers(res.absent_sms, 'No absent messages sent by any teacher.'));

					// If you enable Quiz / Marks later:
					if ($('#ta-quiz').length) $('#ta-quiz').html(formatTeachers(res.quiz, 'No quiz created by any teacher.'));
					if ($('#ta-marks').length) $('#ta-marks').html(formatTeachers(res.marks, 'No marks entered by any teacher.'));
				}
			});
		});
	</script>

	<script src="assets/js/task/list.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>
</body>

</html>