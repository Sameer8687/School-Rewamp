<?php
// get the base url in a variable
$base_url = base_url();
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Student Fees Form</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />
	<!---Include CSS Files-->
	<?php include 'application/views/cssPages.php'; ?>
	<!-- Boxicons CSS -->
	<link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
</head>
<style>
	.col {
		padding-right: 5px;
		padding-left: 5px;
	}

	.student-form-search .left-icon i {
		left: 10px !important;
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
	<!--Include Sidebar -->
	<?php include 'application/views/sidebar.php'; ?>
	<!--Include Top Navbar-->
	<?php include 'application/views/topNav.php'; ?>
	<!-- [ Main Content ] start -->
	<div class="pc-container">
		<form action="<?php echo base_url(); ?>Student/add_fees_data" method="post">
			<div class="pcoded-content">
				<!-- [ Main Content ] start -->
				<div class="row margin-top-minus-40 mobile-margin-top-minus-10 mb-1">
					<div class="col-md-2 col">
						<button class="btn btn-secondary btn-sm" disabled id="add-fees-btn"> <i
								class="fas fa-dollar-sign"></i> <span id="dynamicLabelAddButton">Add
								Fees</span></button>
					</div>

					<div class="col-md-3 col">
						<label for="" class="text-danger">include Inactive</label>
						<input type="radio" name="include_inactive" id="include_inactive" value="Yes"> Yes
						<input type="radio" name="include_inactive" id="include_inactive" value="No" checked> No
					</div>

					<div class="col-md-6 student-form-search col">
						<input type="text" autocomplete="off" class="form-control form-control-sm"
							id="search_student_name" placeholder="Search Student Name">
						<span class="left-icon"><i class="fas fa-search"></i></span>
						<span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>
					</div>

					<div class="col-md-1 col">
						<?php if ($type != '') { ?>
														<a href="<?php echo base_url(); ?>welcome/fees"
															class="btn btn-sm btn-outline-primary btn-sm">Fees From</a>
						<?php } elseif ($type != 'pre' && 'current') { ?>
														<a href="<?php echo base_url(); ?>welcome/fees?type=pre"
															class="btn btn-sm btn-outline-secondary btn-sm">Previous Fees</a>
						<?php } ?>
					</div>
				</div>
				<input type="hidden" id="auto_student_id" value="<?= $student_id ?>">
				<div class="row" id="alertRow">
					<div class="col-md-12">
						<?php
						$message = $this->session->userdata('message');
						$alert_class = $this->session->userdata('alert_class');
						if ($message != "") { ?>
										<div class="alert <?= $alert_class; ?>">
											<?= $message; ?>
										</div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card fees-card">
							<div class="card-header" style="background-color: <?= $header_color ?> !important;">
								<h5 class="text-white pl-2" style="font-weight: normal !important;">
									Student Detail:
									<span id="student_id_display_span"></span>

									<span class="ml-2 text-white">
										<?= $header_year ?>
									</span>
								</h5>
							</div>

							<div class="card-body">
								<div id="fees-section">
									<style>
										label {
											text-align: right;
										}

										.no-margin {
											margin-top: -10px;
										}
									</style>

									<div class="row">
										<div class="col-md-4">
											<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-20">
												<input type="hidden" class="form-control form-control-sm col-md-7"
													required name="student_id" id="student_id" readonly>
												<input type="hidden" class="form-control form-control-sm col-md-7"
													name="student_id_hidden" id="student_id_hidden" readonly value="<?= $student_id ?>">
											</div>
										</div>

										<div class="col-md-4">
											<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-20">
												<input type="hidden" class="form-control form-control-sm col-md-7"
													required name="student_standard" id="student_standard" readonly>
											</div>
										</div>

										<div class="col-md-4">
											<div class="form-group row margin-top-minus-10 mobile-margin-top-minus-20">
												<input type="hidden" class="form-control form-control-sm col-md-7"
													name="student_name" id="student_name" readonly>
											</div>
										</div>
									</div>

									<div class="row desktop-display-none mobile-margin-top-minus-10">
										<div class="col-md-12">
											<label style="text-align: left;" for="" class="">Total Fees :<span
													class="text-primary" id="dynamic_total_fees_label_sm"
													style="font-weight: 600;"></span> Rs. </label>
											<label style="text-align: left; float: right;" for="" class="">Balance Fees
												: <span class="text-primary" id="dynamic_fees_label_sm"
													style="font-weight: 600;"></span> Rs. </label>
										</div>
									</div>

									<style>
										.mini-label label {
											font-size: 12px !important;
											font-weight: normal !important;
										}

										.margin-top-minus-20 {
											margin-top: -16px;
										}
									</style>

									<div class="row mini-label">
										<div class="col-md-6">
											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Academic Year </label>
												<select name="academic_year_hidden" id="academic_year_hidden" required class="form-control form-control-sm cust-border-bottom col-md-3" onchange="update_fees_form()" data-mode="initial">
												<option value="">Select Academic Year</option>
												<?php foreach ($academic_years as $academic_year) {
													if (!empty($selected_academic_year)) {
														$selected = ($selected_academic_year == $academic_year['stvacdm_id']) ? 'selected' : '';
													} else {
														$selected = ($academic_details['stvacdm_id'] == $academic_year['stvacdm_id']) ? 'selected' : '';
													}?>
												<option <?= $selected; ?> value="<?= $academic_year ['stvacdm_id']; ?>"> <?= $academic_year['stvacdm_year']; ?>
												</option>
												<?php } ?>
												</select>
											</div>
											<!-- <div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Academic Year </label>
												<select name="academic_year_hidden" id="academic_year_hidden" required class="form-control form-control-sm cust-border-bottom col-md-3" onchange="update_fees_form()" data-mode="initial">
													<option value="">Select Academic Year</option>
													<?php foreach ($academic_years as $academic_year) {
														if ($academic_details['stvacdm_id'] == $academic_year['stvacdm_id']) {
															$selected = 'selected';
														} else {
															$selected = '';
														} ?>
														<option <?= $selected; ?>
															value="<?= $academic_year['stvacdm_id']; ?>">
															<?= $academic_year['stvacdm_year']; ?>
														</option>
													<?php } ?>
												</select>
											</div> -->

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Total Fees</label>
												<input readonly type="text" autocomplete="off"
													class="form-control form-control-sm col-md-3" required
													name="dynamic_total_fees_label" id="dynamic_total_fees_label">
											</div>

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Amount <span
														class="text-danger">*</span></label>
												<input type="text" autocomplete="off"
													class="form-control form-control-sm col-md-3" required
													name="amount_paid" id="amount_paid" value="0">
											</div>

											<!-- // updated by kamran 21-08-26 -->
											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
											<label class="col-md-4 text-left">Fees Date</label>
											<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '1'): ?>
												<input type="date" class="form-control form-control-sm col-md-3" required name="sprfees_feesdate" id="sprfees_feesdate" value="<?= date('Y-m-d'); ?>">
											<?php else: ?>
												<input type="date" name="sprfees_feesdate" id="sprfees_feesdate" class="form-control form-control-sm col-md-3" value="<?= date('Y-m-d'); ?>" readonly>
											<?php endif; ?>
											</div>
											<!-- // updated by kamran 21-08-26 -->

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Details </label>
												<input type="text" autocomplete="off"
													class="form-control form-control-sm col-md-7" required
													name="details" id="details" value="-">
											</div>

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Fees Card Issued </label>
												<div class="col-md-7 d-flex">
													<div class="w-50">
														<input type="radio" autocomplete="off" class="update-book-issue"
															name="book_issues" id="book_issues_yes" value="Yes"> Yes

														<input value="No" type="radio" autocomplete="off"
															class="update-book-issue" name="book_issues"
															id="book_issues_no"> No <br>

														<small class="font-weight-bold text-danger"
															id="card-loader-small"></small>
													</div>
													<div class="w-50 text-right">
														<a href="javascript:void(0)" id="gen-rec">Generate Fees
															Receipt</a>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Total Balance :</label>
												<input type="text" readonly autocomplete="off"
													class="form-control form-control-sm col-md-3" value=""
													name="dynamic_fees_label" id="dynamic_fees_label">
											</div>

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Concession / Other </label>
												<?php if ($this->session->userdata('oliveSchoolDashboardUser', $user['spruser_id']) == 1): ?>
																				<input type="text" autocomplete="off"
																					class="form-control form-control-sm col-md-3" name="con_amnt"
																					id="con_amnt" value="0">
												<?php else: ?>
																				<input type="text" autocomplete="off"
																					class="form-control form-control-sm col-md-3" name="con_amnt"
																					id="con_amnt" value="0" readonly>
												<?php endif; ?>
											</div>

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Con. Note </label>
												<input type="text" autocomplete="off"
													class="form-control form-control-sm col-md-8" name="con_note"
													id="con_note" value="NA">
											</div>

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<?php $next_month_date = date('Y-m-d', strtotime('+1 month')); ?>
												<label for="" class="col-md-4 text-left">Due Date <span
														class="text-danger">*</span></label>
												<input type="date" autocomplete="off"
													class="form-control form-control-sm col-md-3" required
													name="due_date" id="due_date" value="<?= $next_month_date ?>">
											</div>

											<div class="form-group row margin-top-minus-20 mobile-margin-top-minus-15">
												<label for="" class="col-md-4 text-left">Payment Mode <span
														class="text-danger">*</span></label>
												<select class="form-control form-control-sm col-md-3" required
													name="payment_mode" id="payment_mode">
													<option value="">Select</option>
													<option selected value="cash">Cash</option>
													<option value="online">Online</option>
												</select>
											</div>
										</div>

										<style>
											#feesTable thead tr th,
											#feesTable tbody tr td {
												padding: 5px !important;
												text-transform: capitalize !important;
												font-size: 12px !important;
												border: 1px solid #77777750;
											}

											#feesTable thead tr th {
												background-color: #53769c !important;
												color: #fff;
											}
										</style>

										<div class="col-md-12">
											<table class="table table-striped table-sm table-bordered" id="feesTable"
												style="visibility: hidden;">
												<thead>
													<tr>
														<th>#</th>
														<th>Year</th>
														<th>Class</th>
														<th>Total Fees</th>
														<th>Paid</th>
														<th>Balance</th>
														<th>Exumption</th>
														<th>Class</th>
														<th>Fees</th>
														<th>Date</th>
														<th>Exem.</th>
														<th>Paid To</th>
														<th>Payment Mode</th>
														<th>Comments</th>
													</tr>
												</thead>
												<tbody id="tbody" class="tbody-striped">
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div id="rte-message" style="display:none;">
									<div class="alert alert-warning text-center">
										This is RTE Student
									</div>
								</div>
							</div>
							<!-- [ Search List ] Start -->
							<div class="search-list-container-panel">
								<div class="list-group" id="dynamic_name_list"></div>
							</div>
							<!-- [ Search List ] End -->
						</div>
					</div>
				</div>
			</div>
		</form>
		<!-- [ Main Content ] end -->
	</div>

	<!--  New Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="width: 120% !important;">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Previous Fees</h1>
				</div>
				<div class="modal-body" id="modal-body">
					<div class="row">
						<table class="table table-striped table-sm table-bordered" id="feesballance"
							style="visibility: hidden;">
							<thead>
								<tr>
									<th>#</th>
									<th>Class</th>
									<th>Acdm</th>
									<th>Paid Fees</th>
									<th>Exum</th>
									<th>Total</th>
									<th>Balance</th>
								</tr>
							</thead>
							<tbody id="ttbody" class="tbody-striped">
							</tbody>
						</table>
					</div>
				</div>

				<div class="modal-footer text-center justify-content-center">
					<?php if ($this->session->userdata('oliveSchoolDashboardUser', $user['spruser_id']) == 1): ?>
													<button type="button" id="PreviousFeeshere" class="btn btn-success">
														Current Fees
													</button>
					<?php endif; ?>
					<button type="button" id="PreviousFees" class="btn btn-primary">
						Previous Fees
					</button>
				</div>
			</div>
		</div>
	</div>


	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		const allAcademicYears = <?= json_encode($academic_years) ?>;
		// console.log(allAcademicYears, 'All Academic');


		$(document).on('click', '#closeButton', function () {
			$('#closeModalForFees').modal('hide');
		});

		const formFeesMode = "<?= $this->input->get('type') ?>";
		const loggedInuser = "<?= $this->session->userdata('oliveSchoolDashboardUser') ?>";
		$(document).ready(function () {
			// -------------------------- Search Student Names  -----------------------------
			$('#search_student_name').keyup(function () {
				var names = $('#search_student_name').val().trim();
				var academic_year = $('#academic_year_hidden').val();
				var include_flag = $('input[name="include_inactive"]:checked').val();
				if (names != '') {
					if (names.length >= 3) {
						$.ajax({
							url: "<?php echo base_url(); ?>index.php/Student/findStudentNameForFees",
							method: "POST",
							data: {
								name: names,
								academic_year: academic_year,
								include_flag: include_flag
							},
							dataType: "JSON",
							success: function (data) {
								if (data != '') {
									var html = '';
									for (var count = 0; count < data.length; count++) {
										html += '<a id="' + data[count].STVCLAS_student_id + '" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' + data[count].name + '</span><span class="text-danger"> ( Standard :  ' + data[count].STVCLAS_standard + ' ) </span>' + '</a>';
									}
									$('#dynamic_name_list').html(html);
									$('.search-list-container-panel').css('display', 'block');
								} else {
									var notAvailable = '<a href="#" style="border-radius: 0px; border:none; padding: 5px; font-size: 12px;" class="list-group-item list-group-item-action noDataOfStudent"> <span class="text-info">Data Not Available</span></a>';
									$('#dynamic_name_list').html(notAvailable);
								}
							}
						});
					} else {
						$('#dynamicLabelAddButton').html("Add Fees Data");
						$('#dynamic_name_list').html('');
						$('.search-list-container-panel').css('display', 'none');
					}
				} else {
					clearFormInputIcon();
					$('#dynamicLabelAddButton').html("Add Fees Data");
					$('#dynamic_name_list').html('');
					$('.search-list-container-panel').css('display', 'none');
				}
			});

			// Get Details After Clicking on Student Names
			$(document).on('click', '.getDataOfStudent', function () {
				var names = $(this).text();
				var academic_year = $('#academic_year_hidden').val();
				var student_ids = $(this).attr('id');
				fetch_and_display_data(academic_year, student_ids);
			});

			$(document).on('click', '.noDataOfStudent', function () {
				clearFormInputIcon();
				$('#search_student_name').val('');
				$('#dynamicLabelAddButton').html("Add Fees Data");
				$('#dynamic_name_list').html('');
				$('.search-list-container-panel').css('display', 'none');
			});
		});


		function update_fees_form() {
			var academic_year = $('#academic_year_hidden').val();
			var student_ids = $('#student_id_hidden').val();
			if (student_ids != '')
				fetch_and_display_data(academic_year, student_ids);
		}
		var initialSelected = true;
		function fetch_and_display_data(academic_year, student_ids) {
			$('#show-list').html('');

			$.ajax({
				url: "<?php echo base_url(); ?>index.php/Student/all_fees_details_of_student",
				method: "POST",
				data: {
					student_id: student_ids,
					academic_year: academic_year
				},
				dataType: "JSON",
				beforeSend: function () {
					clearFormInputIcon();
				},
				success: function (data) {
					if (data != '') {
						document.getElementById("add-fees-btn").removeAttribute("disabled");
						$('#student_id').val(data['info'].SPRIDEN_student_id);
						$('#student_id_hidden').val(data['info'].id);
						$('#student_standard').val(data['info'].STVCLAS_standard);
						$('#student_name').val(data['info'].name);
						const mobile = data?.['contact_details']?.['mobile'] || null;
						const whatsapp = data?.['contact_details']?.['whatsapp'] || null;
						let contactInfo = [];
						if (mobile) {
							contactInfo.push(`<i class="fas fa-phone-alt"></i> ${mobile}`);
						}
						if (whatsapp) {
							contactInfo.push(`<i class="fab fa-whatsapp"></i> ${whatsapp}`);
						}

						// Student Name Display
						$('#student_id_display_span').html(

							`${data['info'].name} (${data['info'].SPRIDEN_student_id} - ${data['info'].id}) - ${data['info'].STVCLAS_standard}
								<span class="ml-4" style="color: #d6ff04;">
									${contactInfo.length ? `${contactInfo.join(' - ')}` : ''}
								</span>
								${data['info'].rte_status === 'Yes' ? '- RTE Student' : ''}`
						);

						// ===== RTE Condition =====
						if (data['info'].rte_status == 'Yes') {
							$('#fees-section').hide();
							$('#rte-message').show();
							document.getElementById("add-fees-btn").setAttribute("disabled", true);
							$('#exampleModal').modal('hide');
						} else {
							$('#fees-section').show();
							$('#rte-message').hide();
							document.getElementById("add-fees-btn").removeAttribute("disabled");

						}

						// total fees label
						$('#dynamic_total_fees_label').val(data['fees_details'].stvfstr_fees);
						$('#dynamic_total_fees_label_sm').html(data['fees_details'].stvfstr_fees);
						var intBal = parseInt(data.total_balance);
						if (intBal > 0) {
							$('#dynamic_fees_label').val(intBal);
							$('#dynamic_fees_label').addClass("text-danger");
							$('#dynamic_fees_label').removeClass("text-success");
							$('#dynamic_fees_label_sm').html(intBal);
							$('#dynamic_fees_label_sm').addClass("text-danger");
							$('#dynamic_fees_label_sm').removeClass("text-success");
						} else {
							$('#dynamic_fees_label').val("Full Fees Paid");
							$('#dynamic_fees_label').removeClass("text-danger");
							$('#dynamic_fees_label').addClass("text-success");
							$('#dynamic_fees_label_sm').html("Full Fees Paid");
							$('#dynamic_fees_label_sm').removeClass("text-danger");
							$('#dynamic_fees_label_sm').addClass("text-success");
						}
						if (data['fees'] == null || data['fees'].length == 0) {
							$('#exumption').val(0);
						} else {
							$('#exumption').val(data['fees'][0].exumption);
						}

						//fees details
						var html = '';
						for (var i = 0; i < data['all_fees'].length; i++) {
							let installments = data['all_fees'][i].installments?.split(",");
							let rowspan = parseFloat(installments?.length) + 1;
							var newI = i + 1;
							html += '<tr>';
							html += '<td rowspan="' + rowspan + '">' + newI + '</td>';
							html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].stvacdm_year + '</td>';
							html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].sprfees_standard + '</td>';
							html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].class_fees + '</td>';
							html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].total_paid + '</td>';
							html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].final_balance + '</td>';
							html += '<td rowspan="' + rowspan + '">' + data['all_fees'][i].total_exumpted + '</td>';
							html += '</tr>';
							for (var ins = 0; ins < installments?.length; ins++) {
								let ins_details = installments[ins].split("->");
								html += '<tr>';
								html += '<td>' + ins_details[0] + '</td>';
								html += '<td>' + ins_details[1] + '</td>';
								html += '<td>' + ins_details[2] + '</td>';
								html += '<td>' + ins_details[3] + '</td>';
								html += '<td>' + ins_details[4] + '</td>';
								html += '<td>' + ins_details[6] + '</td>';
								html += '<td>' + ins_details[5] + '</td>';
								html += '</tr>';
							}
						}
						$('#tbody').html(html);
						if (data['all_fees'].length > 0) {
							$('#feesTable').css("visibility", "visible");
						}

						//--------- Dynamically Change The LAbels ------
						$('#dynamic_name_list').html('');
						$('.search-list-container-panel').css('display', 'none');

						// -------------------- MODAL PREVIOUS RECORD -----------------------------
						if (data.prev_balances.length > 0
							&& initialSelected == true
							&& data['info'].rte_status === 'No') {
							// if there is any record
							initialSelected = false;
							var html = '';
							var acdm_opt = '<option value="">Select</option>';
							// Iterate through data and build HTML for table rows and dropdown options
							data.prev_balances.forEach(function (item, index) {
								acdm_opt += `<option value="${item.acdm}" >${item.stvacdm_year}</option>`;
								html += `<tr>
										<td>${index + 1}</td>
										<td>${item.class}</td>
										<td data-academic-id="${item.acdm}" class="prev-academic-id">${item.stvacdm_year}</td>
										<td>${item.paid}</td>
										<td>${item.exumption}</td>
										<td>${item.total}</td>
										<td>${item.balance}</td>
									</tr>`;
							});

							// Update table body and dropdown
							$('#ttbody').html(html);

							/// check if the user if ab ahad sir then do not update select option
							// if (!['1', '3'].includes(loggedInuser))
							// 	$('#academic_year_hidden').html(acdm_opt);
							if (
								!['1', '3'].includes(loggedInuser) &&
								formFeesMode != 'current'
							) {
								$('#academic_year_hidden').html(acdm_opt);
							}

							if (
								!['1', '3'].includes(loggedInuser) &&
								formFeesMode == 'pre'
							) {
								$('#academic_year_hidden').html(acdm_opt);
							}

							// Show fees balance if there are previous balances
							if (formFeesMode != 'pre' && formFeesMode != 'current') {
								if (data.prev_balances.length > 0) {
									$('#exampleModal').modal({
										backdrop: 'static',
										keyboard: false
									});
									$('#exampleModal').modal('show');
									$('#feesballance').css("visibility", "visible");
									$('#closeButton').on('click', function () {
										$('#exampleModal').modal('hide');
									});
								}
							}

						}
						// -------------------- MODAL PREVIOUS RECORD END -----------------------------
					} else {
						document.getElementById("add-fees-btn").setAttribute("disabled", 'true');
						clearFormInputIcon();
						$('#dynamic_name_list').html('');
						$('.search-list-container-panel').css('display', 'none');
					}

					// display student card status
					if (data['card_status'] == "Yes") {
						$("#book_issues_yes").attr('checked', 'checked');
					} else {
						$("#book_issues_no").attr('checked', 'checked');
					}
				}
			});

		}

		$(document).ready(function () {

			var student_id = $('#auto_student_id').val();

			if (student_id != '') {

				var academic_year = $('#academic_year_hidden').val();

				fetch_and_display_data(
					academic_year,
					student_id
				);
			}

		});

		function clearFormInputIcon() {
			$('#student_id').val('');
			$('#student_id_hidden').val('');
			$('#student_standard').val('');
			$('#student_name').val('');
			$('#dynamic_fees_label').val('');
			$('#dynamic_total_fees_label').val('');
			$('#dynamic_fees_label_sm').html('');
			$('#dynamic_total_fees_label_sm').html('');
			$('#search_student_name').val('');

			//--------- Assign Value to BOOKS & RESULT text box ------
			let next_month_date = "<?= $next_month_date ?>"
			$('#books_given').val('');
			$('#result_given').val('');
			$('#teacher_name').val('');
			$('#amount_paid').val('0');
			$('#balance_amt').val('');
			$('#due_date').val(next_month_date);
			$('#details').val('-');
			$('#area').val('');
			$('#tbody').html('');
			$('#feesTable').css("visibility", "hidden");
			$('#student_id_display_span').html('');

			//--------- Dynamically Change The LAbels ------
			$('#dynamicLabelAddButton').html("Add Fees Data");
			$('#dynamic_name_list').html('');
			$('.search-list-container-panel').css('display', 'none');
			$('#fees-section').show();
			$('#rte-message').hide();
		}

		$(document).on('change', '.update-book-issue', function () {
			var value = $(this).val();
			var s_id = $('#student_id_hidden').val();
			$.ajax({
				url: "<?= base_url() ?>Student/updateCardIssueStatus",
				method: "POST",
				data: {
					student_id: s_id,
					status: value
				},
				beforeSend: function () {
					$('#card-loader-small').removeClass('text-success');
					$('#card-loader-small').addClass('text-danger');
					$('#card-loader-small').html('<i class="bx bx-loader bx-spin"></i> Please Wait');
				},
				success: function (res) {
					$('#card-loader-small').html('Status Updated');
					$('#card-loader-small').removeClass('text-danger');
					$('#card-loader-small').addClass('text-success');
				}
			})
		});
	</script>
	<script>
		document.getElementById("gen-rec").addEventListener("click", function () {
			let student_id = document.getElementById("student_id_hidden").value;
			if (student_id == '' || student_id == 0) {
				alert("Select Student name");
			} else {
				window.open(`<?= base_url() ?>student/feesreceipt/${student_id}`)
			}
		})

		$(document).on('click', '#PreviousFeeshere', function () {

			var student_id = $('#student_id_hidden').val();

			if (student_id == '') {
				alert('Please select student first');
				return false;
			}

			window.location.href =
				"<?= base_url(); ?>welcome/fees?type=current&student_id=" + student_id;
		});

		$(document).on('click', '#PreviousFees', function () {

	var student_id = $('#student_id_hidden').val();

	if (student_id == '') {
		alert('Please select student first');
		return false;
	}

	// Sabhi academic ids collect karo
	var academic_year = [];

	$('.prev-academic-id').each(function () {
		academic_year.push($(this).data('academic-id'));
	});

	academic_year = academic_year.join('-');

	console.log(academic_year);

	window.location.href =
		"<?= base_url(); ?>welcome/fees?type=pre&student_id=" + student_id + "&academic_year=" + academic_year;
});

		// $(document).on('click', '#PreviousFees', function () {

		// 	var student_id = $('#student_id_hidden').val();
		// 	var academic_year = $('.prev-academic-id').val();
		// 	console.log(academic_year);
			

		// 	if (student_id == '') {
		// 		alert('Please select student first');
		// 		return false;
		// 	}

		// 	// read all acadmic terms from the balance fees
			

		// 	window.location.href =
		// 		"<?= base_url(); ?>welcome/fees?type=pre&student_id=" + student_id + "&academic_year=" + academic_year;
		// });
	</script>
</body>

</html>