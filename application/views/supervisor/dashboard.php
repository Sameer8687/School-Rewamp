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
		<div class="pcoded-content">
			<!-- [ Main Content ] start -->
			<div class="row margin-top-minus-40">
				<div class="col-xl-4 col-md-4">
					<div class="card proj-t-card">
						<div class="card-body">
							<div class="row align-items-center m-b-30">
								<div class="col-auto">
									<i class="fas fa-user-friends text-danger f-30"></i>
								</div>
								<div class="col p-l-0">
									<h6 class="m-b-5">Total Students</h6>
									<h6 class="m-b-0 text-danger"><?php echo date('d M Y'); ?></h6>
								</div>
							</div>
							<div class="row align-items-center" style="margin-top: -7px;">
								<div class="col">
									<div class="col p-l-0 text-center">
										<h6 class="">A : <?= $active['active_student'] ?></h6>

									</div>
								</div>
								<div class="col">
									<div class="col p-l-0 text-center">
		  								<h6 class="">R : <?= $rte['rte'] ?></h6>

									</div>
								</div>
								<div class="col p-l-0 text-center">
									<h6 class="">N : <?= $inActive['noshow_student']; ?></h6>

								</div>
							</div>
							<h6 class="pt-badge badge-light-danger"></h6>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-4">
					<div class="card proj-t-card">
						<div class="card-body">
							<div class="row align-items-center m-b-30">
								<div class="col-auto">
									<i class="fas fa-user-tie text-success f-30"></i>
								</div>
								<div class="col p-l-0">
									<h6 class="m-b-5">Total Teachers</h6>
									<h6 class="m-b-0 text-success"><?php echo date('d M Y'); ?></h6>
								</div>
							</div>
							<div class="row align-items-center text-center">
								<div class="col">
									<h6 class="m-b-0"><?php
														echo $count['teacher_total'];
														?></h6>
								</div>

								<div class="col">
									<h6 class="m-b-0"></h6>
								</div>
							</div>
							<h6 class="pt-badge badge-light-success"></h6>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-4">
					<div class="card proj-t-card">
						<div class="card-body">
							<div class="row align-items-center m-b-30">
								<div class="col-auto">
									<i class="fas fa-user-clock text-info f-30"></i>
								</div>
								<div class="col p-l-0">
									<h6 class="m-b-5">Current Login</h6>
									<h6 class="m-b-0 text-info">
										<a href="<?php echo base_url(); ?>stvlogh" class="text-info">View Login
											History</a>
									</h6>
								</div>
							</div>
							<div class="row align-items-center text-left">
								<div class="col">
									<h6 class="m-b-0">
										<?php
										$loginDate = date("d M Y", strtotime($currentLoginData['stvlgin_date']));
										$time = $currentLoginData['stvlgin_time'];
										$loginTime = date('g:i A', strtotime($time));
										echo $loginDate . ", " . $loginTime;
										?>
									</h6>
								</div>
							</div>
						</div>
						<h6 class="pt-badge badge-light-info"></h6>
					</div>
				</div>
			</div>
			<div class="row margin-top-minus-20">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header bg-secondary">
							<h5 class="text-white"> <i class="fas fa-user-friends"></i> Total Students - Standard Wise</h5>
						</div>
						<div class="card-body">
							<div id="bar-chart-1"></div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">
							<div class="card quick-links-card">
								<div class="card-header bg-secondary">
									<h5 class="text-white"> <i class="fas fa-external-link-alt"></i> Quick Links</h5>
								</div>
								<div class="card-body">
									<ul>
										<li>
											<a href="<?php echo base_url(); ?>teacher_login">
												<i class="fas fa-caret-right"></i> Lesson
											</a>
										</li>
										<li>
											<a href="<?php echo base_url(); ?>admission-form">
												<i class="fas fa-caret-right"></i> Admission Form
											</a>
										</li>
										<li>
											<a href="<?php echo base_url(); ?>fees">
												<i class="fas fa-caret-right"></i> Fees Form
											</a>
										</li>
										<li>
											<a href="<?php echo base_url(); ?>fees-report">
												<i class="fas fa-caret-right"></i> Fees Report
											</a>
										</li>
										<li>
											<a href="<?php echo base_url(); ?>visiting">
												<i class="fas fa-caret-right"></i> Visiting Form
											</a>
										</li>
										<li>
											<a href="<?php echo base_url(); ?>student-attendance">
												<i class="fas fa-caret-right"></i> Student Attendance
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-md-12 margin-top-minus-20">
							<div class="card">
								<div class="card-header bg-secondary">
									<h5 class="text-white"> <i class="fas fa-file"></i> Reports Links</h5>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-12 text-center">
											<select name="form_links_report" id="form_links_report" class="form-control">
												<option value="">Select Report</option>
												<option value="<?= base_url('fees-report'); ?>">Fees Report</option>
												<option value="<?= base_url('report/balance_fees'); ?>">Balance Fees Report</option>
												<option value="<?= base_url('FeesAlert/fees_report'); ?>">Send Fees Alert</option>
												<option value="<?= base_url('student/student_list'); ?>">Update Student Report</option>
											</select>
											<button class="btn btn-sm btn-outline-secondary mt-3" id="redirect_to_report">Open Report</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- [ Main Content ] end -->
	</div>
	</div>
	<!-- [ Main Content ] end -->
	</div>


	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$(document).ready(function() {

			var all_A = <?php echo '[' . implode(',', $total_ACount) . ']' ?>;
			var all_B = <?php echo '[' . implode(',', $total_BCount) . ']' ?>;

			$(function() {
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
					colors: ["#3ec9d6", "#53769c"],
					stroke: {
						show: true,
						width: 2,
						colors: ['transparent']
					},
					series: [{
						name: 'Division A',
						data: all_A
					}, {
						name: 'Division B',
						data: all_B
					}],
					xaxis: {
						categories: ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII'],
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
							formatter: function(val) {
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

		$('#redirect_to_report').click(function() {
			var url = $('#form_links_report').val();
			if (url != '') {
				window.location = url;
			}
		});
	</script>
</body>

</html>
