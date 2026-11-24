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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.1/dist/css/bootstrap-select.min.css"
		rel="stylesheet">
</head>

<style>
	#task-table td,
	#task-table th {
		padding: 0.25rem 0.4rem !important;
		font-size: 13px;
		vertical-align: middle;
	}

	#task-table select,
	#task-table input[type="date"] {
		font-size: 13px;
		height: calc(1.5em + .5rem + 2px);
		/* matches form-control-sm height */
	}
</style>

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

				<div class="col-xl-4 col-md-6 mb-3 d-flex">
					<div class="card w-100">
						<div class="card-body d-flex flex-column">
							<!-- Top Row: Title + Icon + Button + Badge -->
							<div class="d-flex justify-content-between align-items-center mb-3">
								<div class="d-flex align-items-center">
									<i class="fas fa-file text-warning f-30 mx-2"></i>
									<h6 class="mb-0">Quick Links</h6>
								</div>
								<div class="d-flex flex-column align-items-end">
									<button class="btn btn-sm btn-outline-warning mb-1" id="redirect_to_report">Open
										Report</button>
									<!-- Add both badges here -->
									<h6 class="pt-badge badge-light-danger mb-0"></h6>
									<h6 class="pt-badge badge-light-success mb-0"></h6>
								</div>
							</div>

							<!-- Dropdown -->
							<div class="flex-grow-1 d-flex flex-column justify-content-between">
								<select name="form_links_report" id="form_links_report" class="form-control">
									<option value="">Select Report</option>
									<option value="<?= base_url('ortool'); ?>">Ortool Report</option>
									<option value="<?= base_url('report/class_report'); ?>">Class Fees Report 1</option>
									<option value="<?= base_url('report/class_report2'); ?>">Class Fees Report 2
									</option>
									<option value="<?= base_url('student/student_grlist'); ?>">Student GR Details
									</option>
									<option value="<?= base_url('student/student_gr_contact'); ?>">Student
										GR-Class-Phone List</option>
									<option value="<?= base_url('fees-report'); ?>">Fees Report</option>
									<option value="<?= base_url('report/balance_fees'); ?>">Balance Fees Report</option>
									<option value="<?= base_url('FeesAlert/fees_report'); ?>">Send Fees Alert</option>
									<option value="<?= base_url('student/student_list'); ?>">Update Student Report
									</option>
									<option value="<?= base_url('student-attendance'); ?>">Student Attendance</option>
									<option value="<?= base_url('student-profile'); ?>">Student Profile</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row margin-top-minus-20">
				<!-- Top Row: Dashboard and Reports Side by Side -->
				<!-- <div class="col-md-6 d-flex">
					<div class="card quick-links-card mb-3 w-100">
						<div class="card-body d-flex flex-column">
							<h6><i class="fas fa-external-link-alt"></i>
								<?php echo ($this->session->userdata('oliveSchoolDashboardUser') == '1') ? 'Dashboard Link' : 'Quick Links'; ?>
							</h6>
							<ul class="mt-3">
								<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '1'): ?>
									<li><a target="_blank" href="<?php echo base_url(); ?>FeesDashboard/Fees_db"><i class="fas fa-caret-right"></i> Fees Dashboard</a></li>
									<li><a target="_blank" href="<?php echo base_url(); ?>Attend_DB"><i class="fas fa-caret-right"></i> Attendance Dashboard</a></li>
								<?php else: ?>
									<li><a href="https://hrms.oliveschool.org.in/" class="text-success" target="_blank"><i class="fas fa-caret-right"></i> HR Portal</a></li>
									<li><a href="<?php echo base_url(); ?>report/aadhar_report"><i class="fas fa-caret-right"></i> Aadhar Card Report</a></li>
									<li><a href="<?php echo base_url(); ?>admission-form"><i class="fas fa-caret-right"></i> Admission Form</a></li>
									<li><a href="<?php echo base_url(); ?>fees"><i class="fas fa-caret-right"></i> Fees Form</a></li>
									<li><a href="<?php echo base_url(); ?>fees-report"><i class="fas fa-caret-right"></i> Fees Report</a></li>
									<li><a href="<?php echo base_url(); ?>visiting"><i class="fas fa-caret-right"></i> Visiting Form</a></li>
									<li><a href="<?php echo base_url(); ?>student-attendance"><i class="fas fa-caret-right"></i> Student Attendance</a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div> -->
				<!-- Bottom Row: Task Manager -->

				<div class="col-md-12">
					<div class="card">
						<div class="card-body pt-3">
							<!-- Task Form -->
							<input type="hidden" name="id" id="id">
							<!-- Header with optional title and action buttons -->
							<!-- <div class="d-flex justify-content-between align-items-center mb-2 	bg-light p-2 rounded">
									<h5 class="mb-0 ">📝 Task Manager</h5>
									<div class="d-flex mt-2" style="gap: 10px;">
										<button class="btn btn-outline-secondary btn-sm g-4" onclick="toggleFilterOptions()">
											<i class="fa fa-filter"></i> Filter
										</button>
									</div>
								</div> -->
							<div class="bg-light p-3 rounded shadow-sm">
								<!-- Header Section: Title & Toggle Button -->
								<div class="d-flex justify-content-between align-items-center ">
									<h5 class="mb-0 fw-bold col-2">📝 Task Manager</h5>
									<!-- Filter Options Section (Hidden by default) -->
									<div id="filterOptions" style="display: none;"
										class="animate-fade col-8 align-items-center">
										<div class="row g-2 align-items-center">
											<!-- 'g-2' gives gaps between columns -->
											<div class="col-md-2"><label for="filterTitle"
													class="form-label small fw-bold text-muted">🔍
													Task Name</label></div>
											<!-- Filter by Title -->
											<div class="col-md-3">

												<input type="text" id="filterTitle" class="form-control form-control-sm"
													placeholder="Search by task name..." onkeyup="filterTasks()">
											</div>
											<div class="col-md-2"><label for="filterStatus"
													class="form-label small fw-bold text-muted">📌
													Status</label></div>
											<!-- Filter by Status -->
											<div class="col-md-3">

												<select id="filterStatus" class="form-control form-control-sm">
													<option value="all">All</option>
													<option value="pending">Pending</option>
													<option value="complete">Complete</option>
													<option value="close">Close</option>
													<option value="cancelled">Cancelled</option>
													<option value="restart">Restart</option>
												</select>
											</div>

											<!-- Filter Button -->
											<div class="col-md-2">
												<button type="button" class="btn btn-primary btn-sm w-100"
													onclick="filterTasks()">
													🔎 View
												</button>
											</div>
										</div>
									</div>

									<button class="btn btn-outline-secondary btn-sm col-2"
										onclick="toggleFilterOptions()">
										<i class="fa fa-filter"></i> Filter
									</button>
								</div>
							</div>
							<form id="taskForm" onsubmit="SubmitTask(event)" method="POST"
								enctype="multipart/form-data">
								<div class="row g-3 align-items-end mb-3 mt-2">
									<!-- Title -->
									<div class="col-md-6">
										<label for="title" class="form-label mb-1">Title <span
												class="text-danger">*</span></label>
										<input type="text" class="form-control form-control-sm" name="title" id="title"
											placeholder="Enter Task Title">
										<p class="text-danger err-lbl mb-0" id="lbl-title"></p>
									</div>

									<!-- Start Date -->
									<div class="col-md-2">
										<label for="start_date" class="form-label mb-1">Start Date</label>
										<input type="date" id="start_date" name="start_date"
											class="form-control form-control-sm">
									</div>

									<!-- Reminder Date -->
									<div class="col-md-2">
										<label for="reminder_date" class="form-label mb-1">Reminder Date</label>
										<input type="date" id="reminder_date" name="reminder_date"
											class="form-control form-control-sm">
									</div>

									<!-- Submit -->
									<div class="col-md-2 d-flex">
										<button type="submit" class="btn btn-success btn-sm btn-block">
											<i class="fa fa-check"></i> Save
										</button>
									</div>

								</div>
							</form>

							<!-- Filter Section -->
							<!-- <div class="mb-4" id="filterOptions" style="display: none;">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group mb-0">
											<label for="filterTitle">🔍 Task Name</label>
											<input type="text" id="filterTitle" class="form-control form-control-sm" placeholder="Search by task name..." onkeyup="filterTasks()">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group mb-0">
											<label for="filterStatus">📌 Status</label>
											<select id="filterStatus" class="form-control form-control-sm">
												<option value="all">All</option>
												<option value="pending">Pending</option>
												<option value="complete">Complete</option>
												<option value="close">Close</option>
												<option value="cancelled">Cancelled</option>
												<option value="restart">Restart</option>
											</select>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group mb-0">
											<label>&nbsp;</label>
											<button type="button" class="btn btn-primary btn-sm btn-block" onclick="filterTasks()">🔎 View</button>
										</div>
									</div>
								</div>
							</div> -->

							<div class="row">
								<div class="col-md-12" style="margin-top:-10px; overflow-x: auto;">
									<table class="table table-sm table-borderless align-middle mb-0" id="task-table">
										<thead class="thead-dark">
											<tr class="text-start">
												<th style="width: 5%;">#</th>
												<th style="width: 50%;">Task Name</th>
												<th style="width: 15%;">Status</th>
												<th style="width: 12%;">Start Date</th>
												<th style="width: 12%;">Reminder Date</th>
												<th style="width: 10%;">Action</th>
											</tr>
										</thead>
										<tbody id="task-table-tbody"> <!-- Dynamically populated --> </tbody>
									</table>
									<!-- Pagination Controls -->
									<div class="d-flex justify-content-between mt-2" id="pagination-controls">
										<div id="page-info" class="align-self-center"></div>
										<div id="pagination-buttons" class="d-flex gap-2 align-items-center"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- ... modal code ... -->
					<style>
						/* Pagination Styling */
						#pagination-controls {
							font-size: 14px;
							margin-top: 20px;
						}

						#page-info {
							align-self: center;
							color: #6c757d;
						}

						#pagination-buttons {
							display: flex;
							gap: 7px;
							align-items: center;
						}

						#pagination-buttons button {
							padding: 6px 12px;
							border: 1px solid #ddd;
							background-color: #fff;
							color: rgb(43, 45, 48);
							cursor: pointer;
							border-radius: 4px;
							font-size: 14px;
							transition: background-color 0.3s, color 0.3s;
						}

						#pagination-buttons button:hover {
							background-color: rgb(39, 41, 43);
							color: white;
						}

						#pagination-buttons button:disabled {
							background-color: #e9ecef;
							color: #6c757d;
							/* cursor: not-allowed; */
						}

						#pagination-buttons .active {
							background-color: rgb(50, 52, 54);
							color: white;
							font-weight: bold;
						}

						.modal {
							overflow: hidden !important;
						}

						body.modal-open {
							overflow: hidden;
						}
					</style>

					<!-- Modal -->
					<div class="modal fade" id="appModal" tabindex="-1" role="dialog" aria-labelledby="appModalLabel"
						aria-hidden="true">
						<div class="modal-dialog modal-xl" role="document"
							style="max-width: 100%; height: 100%; margin: 0;">
							<div class="modal-content" style="height: 100vh;">
								<div class="modal-header">
									<h5 class="modal-title" id="appModalLabel">MyTasks Application</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"
										style="font-size: 2rem;">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body p-0" style="height: calc(100% - 56px);">
									<iframe src="https://olivesofts.com/mytasks/" width="100%" height="100%"
										style="border: none;"></iframe>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- [ Main Content ] end -->
	</div>
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