<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Student Books & Result Form</title>
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
			<div class="row mobile-wali-row">
				<div class="col-md-12">
					<div class="card" style="margin-top: -20px;">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12 pb-0" style="margin-top: -15px; margin-bottom:0px;">
									<div class="form-group row">
										<label for="" class="col-md-2 text-left">Academic Year:</label>
										<select class="form-control form-control-sm col-md-2" name="fees_academic_year"
											id="fees_academic_year" required>
											<option value="">Select Year</option>
											<?php
											$cur_year = $selected_year; // Use the selected year from controller
											foreach ($all_years as $all_year) {
												$isSelected = ($all_year['stvacdm_id'] == $cur_year) ? "selected" : '';
												?>
												<option <?= $isSelected; ?> value="<?php echo $all_year['stvacdm_id']; ?>">
													<?php echo $all_year['stvacdm_year']; ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<hr style="margin-top: -20px;">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="dt-responsive table-responsive" style="margin-top: -10px;">
										<table id="simpletable" class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th>Sr.No.</th>
													<th>ID</th>
													<th>Name</th>
													<th>Std</th>
													<th style="display:none;">Class</th>
													<th>Division</th>
													<th>Total Fees Paid</th>
													<th>Balance</th>
													<th>Due Date</th>
													<th>Status</th>
													<th>Paid To</th>
													<th>All Records</th>
												</tr>
											</thead>
											<style>
												#simpletable thead tr th {
													padding: 5px;
													font-size: 12px;
													font-weight: 500;
													text-transform: capitalize;
												}

												#simpletable tbody tr td {
													padding: 2px 5px 2px 5px;
													font-size: 12px;
													font-weight: normal;
													text-transform: capitalize;
												}
											</style>
											<tbody>
												<?php $i = 1;
												$flag = 0;
												foreach ($feesDetails as $fees) {
													//Convert and Display dates ----------------------
													$servDate = $fees['due_date'];
													$timestamp = strtotime($servDate);
													$due_date = date("d M Y", $timestamp);
													//Check if Due date gone change color
													$expire_due_date = $fees['due_date'];
													$expire = strtotime($expire_due_date);
													$today = strtotime("today midnight");
													//Get Next 2 Days
													$previous_two_days_in_seconds = "s";
													$previous_two_days = date("d M Y", strtotime(date('Y-m-d', strtotime($fees['due_date'] . ' -2 days'))));
													$date = $fees['due_date'];
													$two_days_ago = date('Y-m-d', strtotime($date . ' - 2 days'));

													if ($fees['STVCLAS_standard'] == "I") {
														$classStd = "First";
													} else if ($fees['STVCLAS_standard'] == "II") {
														$classStd = "Second";
													} else if ($fees['STVCLAS_standard'] == "III") {
														$classStd = "Third";
													} else if ($fees['STVCLAS_standard'] == "IV") {
														$classStd = "Fourth";
													} else if ($fees['STVCLAS_standard'] == "V") {
														$classStd = "Fifth";
													} else if ($fees['STVCLAS_standard'] == "VI") {
														$classStd = "Sixth";
													} else if ($fees['STVCLAS_standard'] == "VII") {
														$classStd = "Seventh";
													} else if ($fees['STVCLAS_standard'] == "VIII") {
														$classStd = "Eighth";
													} else if ($fees['STVCLAS_standard'] == "IX") {
														$classStd = "Ninth";
													} else if ($fees['STVCLAS_standard'] == "Sr.Kg.") {
														$classStd = "Sr.Kg.";
													} else if ($fees['STVCLAS_standard'] == "Jr.Kg.") {
														$classStd = "Jr.Kg.";
													} else if ($fees['STVCLAS_standard'] == "Nursery") {
														$classStd = "Nursery";
													} else {
														$classStd = "";
													}

													if ($today > $expire && $fees['balance'] > 0) {
														?>
														<tr class="bg-danger text-white">
														<?php } elseif (strtotime(date('Y-m-d')) == strtotime($two_days_ago) && $fees['balance'] > 0) {
														$flag = 1; ?>
														<tr class="bg-warning text-white">
														<?php } else { ?>
														<tr>
														<?php } ?>
														<td><?php echo $i; ?></td>
														<td><?php echo $fees['SPRIDEN_student_id']; ?></td>
														<td><?php echo $fees['name']; ?></td>
														<td><?php echo $fees['STVCLAS_standard']; ?></td>
														<td style="display: none;"><?php echo $classStd; ?></td>
														<td><?php echo $fees['STVCLAS_class']; ?></td>
														<td><?php echo $fees['total_paid']; ?></td>
														<?php
														if ($fees['balance'] > 0 && $flag != 1) {
															$td_class = "bg-dansger texst-white";
														} else {
															$td_class = '';
														}
														?>
														<td class="<?= $td_class; ?>">
															<?php echo $fees['balance']; ?>
														</td>
														<td><?php echo $due_date; ?></td>
														<td><?php echo $fees['spriden_student_status']; ?></td>
														<td><?php echo $fees['spruser_name']; ?></td>
														<td style="cursor: pointer;" class="view-record text-center"
															data-id="<?php echo $fees['id']; ?>">
															View
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
		<!-- [ Main Content ] end -->
	</div>
	</div>
	<!-- [ Main Content ] end -->
	</div>
	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$(document).ready(function () {
			// Handle academic year change
			$('#fees_academic_year').change(function () {
				var acdm_year = $(this).val();
				if (acdm_year) {
					window.location = '<?= base_url('welcome/fees_report/') ?>' + acdm_year;
				}
			});

			// Handle form submission if you want to keep POST method
			$('form').on('submit', function (e) {
				var acdm_year = $('#fees_academic_year').val();
				if (!acdm_year) {
					e.preventDefault();
					alert('Please select an academic year');
				}
			});
		});
	</script>

	<!-- <script>
		$('#fees_academic_year').change(function () {
			var acdm_year = $(this).val();
			window.location = '<?= base_url('welcome/fees_report/') ?>' + acdm_year;
		});
	</script> -->
</body>

</html>