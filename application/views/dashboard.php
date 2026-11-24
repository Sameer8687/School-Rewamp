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

									<h6 class="m-b-0 text-danger">
										<?php echo date('d M Y'); ?>
									</h6>

								</div>

							</div>

							<div class="row align-items-center " style="margin-top: -7px;">

								<div class="col">

									<div class="col  text-center ">

										<h6 class="" style="margin-left: -24px;">A :
											<?= $active['active_student'] ?>
										</h6>



									</div>

								</div>

								<div class="col">

									<div class="col  text-center ">

										<h6 class="" style="margin-left: -12px;">R :
											<?= $rte['rte'] ?>
										</h6>



									</div>

								</div>

								<div class="col  text-center">

									<h6 class="">T :
										<?php
										$total_without_rte = isset($active['active_student']) ? (int) $active['active_student'] : 0;
										$total_with_rte = isset($rte['rte']) ? (int) $rte['rte'] : 0;
										$total_active = $total_with_rte + $total_without_rte;
										echo $total_active;
										?>
									</h6>
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

									<h6 class="m-b-0 text-success">
										<?php echo date('d M Y'); ?>
									</h6>

								</div>

							</div>

							<div class="row align-items-center" style="margin-top: -7px;">

								<div class="col">

									<div class="col p-l-0 text-left">

										<h6 class="">A :
											<?= $count['teacher_total'] ?>
										</h6>



									</div>

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
				<!-- Top Row: Dashboard and Reports Side by Side -->
				<div class="col-md-6">
					<div class="card quick-links-card mb-3">
						<div class="card-body">
							<h6><i class="fas fa-external-link-alt"></i>
								<?php echo ($this->session->userdata('oliveSchoolDashboardUser') == '1') ? 'Dashboard Link' : 'Quick Links'; ?>
							</h6>
							<ul class="mt-3">
								<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '1'): ?>
									<li><a target="_blank" href="<?php echo base_url(); ?>FeesDashboard/Fees_db"><i
												class="fas fa-caret-right"></i> Fees Dashboard</a></li>
									<li><a target="_blank" href="<?php echo base_url(); ?>Attend_DB"><i
												class="fas fa-caret-right"></i> Attendance Dashboard</a></li>
								<?php else: ?>
									<li><a href="https://oliveenglishschool.com/hrms/" class="text-success"
											target="_blank"><i class="fas fa-caret-right"></i> HR Portal</a></li>
									<li><a href="<?php echo base_url(); ?>report/aadhar_report"><i
												class="fas fa-caret-right"></i> Aadhar Card Report</a></li>
									<li><a href="<?php echo base_url(); ?>admission-form"><i class="fas fa-caret-right"></i>
											Admission Form</a></li>
									<li><a href="<?php echo base_url(); ?>fees"><i class="fas fa-caret-right"></i> Fees
											Form</a></li>
									<li><a href="<?php echo base_url(); ?>fees-report"><i class="fas fa-caret-right"></i>
											Fees Report</a></li>
									<li><a href="<?php echo base_url(); ?>visiting"><i class="fas fa-caret-right"></i>
											Visiting Form</a></li>
									<li><a href="<?php echo base_url(); ?>Fee-massage"><i class="fas fa-caret-right"></i>
											Fees Massage</a></li>
									<li><a href="<?php echo base_url(); ?>student-attendance"><i
												class="fas fa-caret-right"></i> Student Attendance</a></li>
									<li><a href="<?php echo base_url(); ?>Lc_certificate"><i class="fas fa-caret-right"></i>
											Lc For Aadhar</a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>


				<div class="col-md-6">
					<div class="card mb-3">
						<div class="card-body">
							<h6><i class="fas fa-file"></i> Reports Links</h6>
							<div class="row mt-3">
								<div class="col-md-12 text-center">
									<select name="form_links_report" id="form_links_report" class="form-control">
										<option value="">Select Report</option>
										<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '8'): ?>
											<option value="<?= base_url('ortool'); ?>">Ortool Report</option>
										<?php endif; ?>
										<option value="<?= base_url('report/class_report'); ?>">Class Fees Report 1
										</option>
										<option value="<?= base_url('report/class_report2'); ?>">Class Fees Report 2
										</option>
										<option value="<?= base_url('student/student_grlist'); ?>">Student GR Details
										</option>
										<option value="<?= base_url('student/student_gr_contact'); ?>">Student
											GR-Class-Phone List</option>
										<option value="<?= base_url('fees-report'); ?>">Fees Report</option>
										<option value="<?= base_url('report/balance_fees'); ?>">Balance Fees Report
										</option>
										<option value="<?= base_url('FeesAlert/fees_report'); ?>">Send Fees Alert
										</option>
										<option value="<?= base_url('student/student_list'); ?>">Update Student Report
										</option>
										<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '8'): ?>
											<option value="<?= base_url('student-profile'); ?>">Student Profile</option>
										<?php endif; ?>
									</select>
									<button class="btn btn-sm btn-outline-secondary mt-3" id="redirect_to_report">Open Report</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Bottom Row: Task Manager -->
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
	<script src="assets/js/task/list.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.1/dist/sweetalert2.all.min.js"></script>
</body>

</html>