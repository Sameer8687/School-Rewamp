<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - Student Lists</title>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="description" content="" />

	<meta name="keywords" content="">

	<meta name="author" content="Phoenixcoded" />


	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!---Include CSS Files-->

	<?php include 'application/views/cssPages.php'; ?>



</head>

<style>
	/* -------------------Ansari Kamran-------------------------------- */
	.buttons-excel {
		padding: 0px 5px 0px 5px !important;
		background-color: #53769c !important;
		color: #fff !important;
	}

	.buttons-pdf {
		padding: 0px 5px 0px 5px !important;
		background-color: #53769c !important;
		color: #fff !important;
	}

	.buttons-print {
		padding: 0px 5px 0px 5px !important;
		background-color: #53769c !important;
		color: #fff !important;
	}

	.paginate_button {
		padding: 0px 5px 0px 5px !important;
	}

	.dataTables_info {
		padding: 0px 5px 0px 5px !important;
	}

	div.dataTables_wrapper div.dataTables_filter input {
		padding: 0px !important;
		padding-left: 5px !important;
		width: 75% !important;
	}

	.no-gutterss .col-md-2 {
		margin-left: 0px !important;
		margin-right: 0px !important;
	}

	.table-responsive::-webkit-scrollbar {
		height: 10px !important;
		color: #53769c !important;
	}

	.dataTables_wrapper .dt-buttons {
		float: right;
	}

	div.dataTables_wrapper div.dataTables_paginate {
		margin: 0;
		white-space: nowrap;
		text-align: right;
		padding: 0;
	}

	table.dataTable {
		clear: both;
		margin-top: 0px !important;
		margin-bottom: 0px !important;
		max-width: none !important;
		border-collapse: separate !important;
		border-spacing: 0;
		padding: 0px;
	}

	.pcoded-content {
		padding: 10px !important;
	}

	.pc-container {
		position: relative;
		top: 35px;
	}

	.form-group {
		margin-bottom: 0.5rem !important;
	}

	::-webkit-scrollbar {
		width: 5px;
		height: 1px;

	}

	/* #simpletable ::-webkit-scrollbar {
		
		height: 2px;

	} */

	::-webkit-scrollbar-thumb {
		background-color: #53769c;
		border-radius: 20px;
	}
</style>

<body class="">

	<!-- [ Pre-loader ] start -->

	<div class="loader-bg">

		<div class="loader-track">

			<div class="loader-fill"></div>

		</div>

	</div>

	<!--Include Sidebar -->

	<?php include 'application/views/sidebar.php'; ?>

	<!--Include Top Navbar-->

	<?php include 'application/views/topNav.php'; ?>
	<!-- [ Main Content ] start -->

	<div class="pc-container">
		<div class="pcoded-content">
			<!-- [ Main Content ] start -->
			<div class="row">
				<div class="col-md-12">
					<nav>
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<!-- Update 04-09-24 -->
							<button class="nav-link <?= $selected_tab == 'tabid-1' ? 'active' : '' ?>" style="padding:5px" id="nav-nur-ssc-tab" data-bs-toggle="tab" data-bs-target="#nav-nur-ssc" type="button" role="tab" aria-controls="nur-ssc" aria-selected="true" style="font-size:20px;font-weight:550;">Nursery to X</button>
							<!-- Update 04-09-24 -->
							<button class="nav-link <?= $selected_tab == 'tabid-2' ? 'active' : '' ?>" style="padding:5px" id="nav-jc-tab" data-bs-toggle="tab" data-bs-target="#nav-jc" type="button" role="tab" aria-controls="nav-jc" aria-selected="false" style="font-size:20px;font-weight:550;">XI - XII</button>
						</div>
					</nav>

					<div class="tab-content" id="nav-tabContent">
					<!-- Update 04-09-24 -->
						<div class="tab-pane fade <?= $selected_tab == 'tabid-1' ? 'show active' : '' ?>" id="nav-nur-ssc" role="tabpanel" aria-labelledby="nav-nur-ssc-tab">
							<!-- // Outer Sales tab  -->

							<div class="row mb-1 mobile-wali-row">

								<div class="col-md-9 d-flex">

									<!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
									<!-- Update 04-09-24 -->
									<select name="acdm_year_filter" id="acdm_year_filter_1" class="form-control form-control-sm ml-2" style="width: 15%;">
										<option value="">Select</option>
										<?php foreach ($academic_years as $academic_year) : ?>
											<option <?= $current_acdm == $academic_year['stvacdm_id'] ? 'selected' : ''  ?> value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>
										<?php endforeach; ?>
									</select>
									<!-- Update 04-09-24 -->
									<select name="status_filter" id="status_filter_1" class="form-control form-control-sm ml-2" style="width: 15%;">
										<option value="">Select</option>
										<option <?= $current_status == 'ALL' ? 'selected' : '' ?> value="ALL">All</option>
										<?php foreach ($all_status as $sts) : ?>
											<option <?= $current_status == $sts['spriden_student_status'] ? 'selected' : ''  ?> value="<?= $sts['spriden_student_status']; ?>"><?= $sts['spriden_student_status']; ?></option>
										<?php endforeach; ?>
									</select>
									<!-- Update 04-09-24 -->
									<button class="btn btn-sm btn-primary ml-2" type="button" onclick="filter_data('1')">Apply</button>
								</div>

								<div class="col-md-2 student-form-search">

									<input type="text" class="form-control form-control-sm" id="search_student_name" placeholder="Enter Student ID" autocomplete="off">

									<span class="left-icon"><i class="fas fa-search"></i></span>

								</div>

								<div class="col-md-1">

									<button class="btn btn-sm btn-outline-secondary" type="button" id="findStudent">Find</button>

								</div>

							</div>

							<div class="row" id="alertsRow">

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

									<div class="card" style=" margin-bottom: 0px !important; height: 590px !important; overflow: auto;">

										<div class="card-body" style="padding: 5px !important;">
											<style>
												.table tr td,

												.table tr th {

													padding: 5px !important;

													font-size: 11px !important;

												}

												.text-capitalize {
													background-color: #53769c !important;
													color: #fff !important;
												}
											</style>
											<div class="row">

												<div class="col-md-12">

													<div class="dt-responsive table-responsive">

														<table id="simpletable" class="table table-striped table-bordered nowrap" style="width: 100%;">

															<thead>

																<tr class="text-center">

																	<th class="text-capitalize">Sr.No.</th>

																	<th class="text-capitalize">ID</th>

																	<th class="text-left text-capitalize">Name</th>

																	<th class="text-capitalize">Std</th>

																	<th class="text-capitalize">Division</th>

																	<th class="text-capitalize">Status</th>

																	<th class="text-capitalize">GR Number</th>

																	<th class="text-capitalize">Student Id Govt.</th>



																	<th style="display:none;">Status 1</th>

																	<th class="text-capitalize">Print</th>
																	<th class="text-capitalize">LC Status</th>



																</tr>

															</thead>

															<style>
																#simpletable tbody tr td {

																	padding: 5px;

																	font-size: 12px;

																	font-weight: 500;

																}



																#simpletable tbody tr {

																	cursor: pointer;

																}
															</style>

															<tbody>

																<?php $i = 1;

																$flag = 0;

																foreach ($students as $student) {

																	$staus_name = '';

																	if ($student['spriden_student_status'] == "A") {

																		$staus_name = "Active";
																	} else if ($student['spriden_student_status'] == "I") {

																		$staus_name = "Leave";
																	} else if ($student['spriden_student_status'] == "N") {

																		$staus_name = "No Show";
																	} else if ($student['spriden_student_status'] == "null") {

																		$staus_name = "Null";
																	} else {

																		$staus_name = 'NO DATA';
																	}

																	$lc_issued = $student['lc_status'] === 'Issued' ?  'text-danger' : '';

																	// echo '<pre>';
																	// print_r($student);

																?>
																	<?php $text = $student['lc_status'] == 'Issued' ?
																		'text-danger' : 'text-dark';
																	?>

																	<tr class="text-center <?= $text ?>">

																		<td class="view-record <?= $text ?>" data-id="<?= $student['id']; ?>"><?= $i; ?></td>

																		<td class="view-record <?= $text ?>" data-id="<?= $student['id']; ?>"><?= $student['id'] . " - " . $student['SPRIDEN_student_id']; ?></td>

																		<td class="view-record <?= $text ?> text-left" data-id="<?= $student['id']; ?>"><?= $student['name']; ?></td>

																		<td class="view-record <?= $text ?>" data-id="<?= $student['id']; ?>"><?= $student['STVCLAS_standard']; ?></td>

																		<td class="view-record <?= $text ?>" data-id="<?= $student['id']; ?>"><?= $student['STVCLAS_class']; ?></td>

																		<td class="text-center <?= $text ?>">

																			<!-- <input class="change-status-of-student" type="checkbox" <?= $isActive; ?> name="status_button_checkbox<?= $i; ?>" id="status_button_checkbox<?= $i; ?>" data-row-id="<?= $i; ?>" data-stu-id="<?= $student['id']; ?>"> <span id="status_show_label<?= $i; ?>"><?= $label; ?></span> -->

																			<?php
																			$userid = $this->session->userdata('oliveSchoolDashboardUser');
																			if (in_array($userid, [1, 3,5,8])):
																			?>
																				<select

																					onclick="captureOldValue(this)"
																					class="change-status-of-student <?= $text ?>" name="status_button_checkbox<?= $i; ?>" id="status_button_checkbox<?= $i; ?>" data-row-id="<?= $i; ?>" data-stu-id="<?= $student['id']; ?>">

																					<option <?= $student['spriden_student_status'] == 'A' ? 'selected' : ''; ?> value="A">A</option>

																					<option <?= $student['spriden_student_status'] == 'I' ? 'selected' : ''; ?> value="I">I</option>

																					<option <?= $student['spriden_student_status'] == 'N' ? 'selected' : ''; ?> value="N">N</option>

																					<option <?= $student['spriden_student_status'] == 'null' ? 'selected' : ''; ?> value="null">Null</option>

																				</select>
																			<?php else: ?>
																				<span class=""><?= $student['spriden_student_status'] ?> </span>
																			<?php endif; ?>

																		</td>

																		<!-- update  6-12-2021 -->

																		<td class="view-record <?= $text ?>" data-id="<?= $student['id']; ?>"><?= $student['spriden_gr_number']; ?></td>

																		<td class="view-record <?= $text ?>" data-id="<?= $student['id']; ?>"><?= $student['SPRIDEN_reg_number']; ?></td>

																		<!-- end -->


																		<td style="display:none;" class="<?= $text ?>"><?= $staus_name ?></td>

																		<td>

																			<a href="<?= base_url('student/admission_form/' . $student['id']) ?>" class="<?= $text ?>">Reprint</a>

																		</td>
																		<?php $text = $student['lc_status'] == 'Issued' ?
																			'text-danger' : 'text-dark';
																		?>
																		<td class="view-record <?= $text ?>" data-id="<?= $student['id']; ?>"><?= $student['lc_status']; ?></td>

																	</tr>



																<?php $i++;
																} ?>

															</tbody>

														</table>

													</div>

												</div>

											</div>

										</div>

									</div>

								</div>

							</div>

						</div>
						<!-- Update 04-09-24 -->
						<div class="tab-pane fade <?= $selected_tab == 'tabid-2' ? 'show active' : '' ?>" id="nav-jc" role="tabpanel" aria-labelledby="nav-jc-tab">
							<div class="row mb-1 mobile-wali-row">

								<div class="col-md-9 d-flex">

									<!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
									<!-- Update 04-09-24 -->
									<select name="acdm_year_filter" id="acdm_year_filter_2" class="form-control form-control-sm ml-2" style="width: 15%;">
										<option value="">Select</option>
										<?php foreach ($academic_years as $academic_year) : ?>
											<option <?= $current_acdm == $academic_year['stvacdm_id'] ? 'selected' : ''  ?> value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>
										<?php endforeach; ?>
									</select>
									<!-- Update 04-09-24 -->
									<select name="status_filter" id="status_filter_2" class="form-control form-control-sm ml-2" style="width: 15%;">
										<option value="">Select</option>
										<option <?= $current_status == 'ALL' ? 'selected' : '' ?> value="ALL">All</option>
										<?php foreach ($all_status as $sts) : ?>
											<option <?= $current_status == $sts['spriden_student_status'] ? 'selected' : ''  ?> value="<?= $sts['spriden_student_status']; ?>"><?= $sts['spriden_student_status']; ?></option>
										<?php endforeach; ?>
									</select>
									<!-- Update 04-09-24 -->
									<button class="btn btn-sm btn-primary ml-2" type="button" onclick="filter_data('2')">Apply</button>
								</div>

								<div class="col-md-2 student-form-search">

									<input type="text" class="form-control form-control-sm" id="search_student_name" placeholder="Enter Student ID" autocomplete="off">

									<span class="left-icon"><i class="fas fa-search"></i></span>

								</div>

								<div class="col-md-1">

									<button class="btn btn-sm btn-outline-secondary" type="button" id="findStudent">Find</button>

								</div>

							</div>

							<div class="row" id="alertsRow">

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

									<div class="card" style=" margin-bottom: 0px !important; height: 590px !important; overflow: auto;">

										<div class="card-body" style="padding: 5px !important;">
											<style>
												.table tr td,

												.table tr th {

													padding: 5px !important;

													font-size: 11px !important;

												}

												.text-capitalize {
													background-color: #53769c !important;
													color: #fff !important;
												}
											</style>
											
											<div class="row">

												<div class="col-md-12">

													<div class="dt-responsive table-responsive">

														<table id="simpletable1" class="table table-striped table-bordered nowrap" style="width: 100%;">

															<thead>

																<tr class="text-center">

																	<th class="text-capitalize">Sr.No.</th>

																	<th class="text-capitalize">ID</th>

																	<th class="text-left text-capitalize">Name</th>

																	<th class="text-capitalize">Std</th>

																	<th class="text-capitalize">Division</th>

																	<th class="text-capitalize">Status</th>

																	<th class="text-capitalize">GR Number</th>

																	<th class="text-capitalize">Student Id Govt.</th>



																	<th style="display:none;">Status 1</th>

																	<th class="text-capitalize">Print</th>
																	<th class="text-capitalize">LC Status</th>



																</tr>

															</thead>

															<style>
																#simpletable tbody tr td {

																	padding: 5px;

																	font-size: 12px;

																	font-weight: 500;

																}



																#simpletable tbody tr {

																	cursor: pointer;

																}
															</style>

															<tbody>

																<?php $i = 1;

																$flag = 0;

																foreach ($students_jc as $student) {

																	$staus_name = '';

																	if ($student['spriden_student_status'] == "A") {

																		$staus_name = "Active";
																	} else if ($student['spriden_student_status'] == "I") {

																		$staus_name = "Leave";
																	} else if ($student['spriden_student_status'] == "N") {

																		$staus_name = "No Show";
																	} else if ($student['spriden_student_status'] == "null") {

																		$staus_name = "Null";
																	} else {

																		$staus_name = 'NO DATA';
																	}

																	$lc_issued = $student['lc_status'] == 'Issued' ? $lc_issued = 'text-danger' : 'text-dark';

																?>

																	<tr class="text-center <?= $lc_issued ?>">

																		<td class="view-record <?= $lc_issued ?>" data-id="<?= $student['id']; ?>"><?= $i; ?></td>

																		<td class="view-record <?= $lc_issued ?>" data-id="<?= $student['id']; ?>"><?= $student['id'] . " - " . $student['SPRIDEN_student_id']; ?></td>

																		<td class="view-record <?= $lc_issued ?> text-left" data-id="<?= $student['id']; ?>"><?= $student['name']; ?></td>

																		<td class="view-record <?= $lc_issued ?>" data-id="<?= $student['id']; ?>"><?= $student['STVCLAS_standard']; ?></td>

																		<td class="view-record <?= $lc_issued ?>" data-id="<?= $student['id']; ?>"><?= $student['STVCLAS_class']; ?></td>

																		<td class="text-center">
																			<?php
																			$userid = $this->session->userdata('oliveSchoolDashboardUser');
																			if (in_array($userid, [1, 3,5])):
																			?>
																				<select
																					onclick="captureOldValue(this)"

																					class="change-status-of-student" name="status_button_checkbox<?= $i; ?>" id="status_button_checkbox<?= $i; ?>" data-row-id="<?= $i; ?>" data-stu-id="<?= $student['id']; ?>">

																					<option <?= $student['spriden_student_status'] == 'A' ? 'selected' : ''; ?> value="A">A</option>

																					<option <?= $student['spriden_student_status'] == 'I' ? 'selected' : ''; ?> value="I">I</option>

																					<option <?= $student['spriden_student_status'] == 'N' ? 'selected' : ''; ?> value="N">N</option>

																					<option <?= $student['spriden_student_status'] == 'null' ? 'selected' : ''; ?> value="null">Null</option>

																				</select>
																			<?php else: ?>
																				<span class=""><?= $student['spriden_student_status'] ?> </span>
																			<?php endif; ?>
																		</td>

																		<td class="view-record <?= $lc_issued ?>" data-id="<?= $student['id']; ?>"><?= $student['spriden_gr_number']; ?></td>

																		<td class="view-record <?= $lc_issued ?>" data-id="<?= $student['id']; ?>"><?= $student['SPRIDEN_reg_number']; ?></td>

																		<td style="display:none;" class="<?= $lc_issued ?>"><?= $staus_name ?></td>

																		<td>

																			<a href="<?= base_url('student/admission_form/' . $student['id']) ?>" class="<?= $lc_issued ?>">Reprint</a>

																		</td>
																		<td class="view-record <?= $lc_issued ?>" data-id="<?= $student['id']; ?>"><?= $student['lc_status']; ?>
																		</td>

																	</tr>

																<?php $i++;
																} ?>

															</tbody>

														</table>

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
			</div>
		</div>
	</div>

	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
	</script>
	<!-- Optional JavaScript -->
	</div>
	</div>
	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$(document).ready(function() {
			$("#myInput").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#simpletable tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
		});
	</script>

	<script>
		$(document).on('click', '.view-record', function() {
			var stu_id = $(this).attr("data-id");
			var cur_acdm = '<?= $current_acdm ?>';
			var url = `<?= base_url(); ?>student/update_details/${stu_id}?acdm-year=${cur_acdm}`;
			window.open(url);

		});
		// Update 04-09-24
		function filter_data(tabid) {
			let acdm_year = document.querySelector('#acdm_year_filter_' + tabid).value;
			let status = document.querySelector('#status_filter_'+ tabid).value;
			if (acdm_year != '' && status != '') {
				window.location = `<?= base_url() ?>student/student_list?acdm-year=${acdm_year}&status=${status}&tab=tabid-${tabid}`;
			}

		}

		$('#findStudent').click(function() {
			var id = $('#search_student_name').val().trim();
			if (id != '') {
				$.ajax({
					url: "<?= base_url('Student/StudentUniqueId') ?>?id=" + id,
					method: "GET",
					dataType: "JSON",
					success: function(data) {
						if (data != null) {
							window.location = '<?= base_url('student/update_details/') ?>' + data.id;
						} else {
							alert("Data Not Available");
						}
					}
				});
			}
		});

		$(document).on('change', '.change-status-of-student', function(e) {
			var row_id = $(this).attr("data-row-id");
			var student_id = $(this).attr("data-stu-id");
			var ids = "status_button_checkbox" + row_id;
			var jids = "#status_button_checkbox" + row_id;
			var status_flag = $(jids).val();
			if (confirm("Do you want to change status?")) {
				// Updated 30 Sep 24 - Azz Shaikh
				let reason = prompt("Enter Reason to Change Student Status");
				if (reason == null || reason == '') {
					alert("Please Enter Reason");
					return;
				}
				// Updated 30 Sep 24 - Azz Shaikh
				$.ajax({
					url: "<?= base_url('Student/changeStudentStatus') ?>",
					method: "POST",
					data: {
						id: student_id,
						status: status_flag,
						reason
					},
					dataType: "JSON",
					success: function(data) {
						$(jids).val(status_flag);
						if (data != null || data != "fail") {
							if (data.spriden_student_status != '0') {
								console.log("Changed");
							} else {
								alert("Failed to Update");
							}
						} else {
							alert("Failed");
						}
					}
				});
			}
		});
	</script>
</body>

</html>