<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Issue LC</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />
	<!---Include CSS Files-->
	<?php include 'application/views/cssPages.php'; ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css">
	<style>
		.pc-container {
			top: 0px !important;
		}
		.border {
			border: 1px solid;
		}

		.table tr td,
		.table tr th {
			padding: 5px !important;
			font-size: 11px !important;
			cursor: pointer;
		}

	</style>
</head>

<body class="">
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>

	<?php include 'application/views/sidebar.php'; ?>
	<?php include 'application/views/topNav.php'; ?>
	<div class="pc-container">
		<div class="pcoded-content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">
								Generate L.C Report
							</h5>
						</div>
						<div class="card-body">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Academic Year <span
														class="float-right">:</span></label>
												<select name="acdm_year" id="acdm_year"
													class="form-control form-control-sm col-md-7">
													<option value="">Select Year</option>
													<?php foreach ($academic_years as $acdm) { ?>
														<option <?= ($filter_acdm == $acdm['stvacdm_id'] || (empty($filter_acdm) && $selected_academic_year == $acdm['stvacdm_id'])) ? 'selected' : '' ?>
															value="<?= $acdm['stvacdm_id'] ?>">
															<?= $acdm['stvacdm_year'] ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>

										<div class="col-md-3">
											<div class="row">
												<label for="" class="col-md-5">Select Class <span
														class="float-right">:</span></label>
												<select name="std" id="std"
													class="form-control form-control-sm col-md-7">
													<option value="">Select Class</option>
													<?php foreach ($classes as $clas) { ?>
														<option <?= $filter_class == $clas['stvfees_class'] ? 'selected' : '' ?> value="<?= $clas['stvfees_class'] ?>">
															<?= $clas['stvfees_class'] ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<button id="find-students" onclick="getRecords(this)" type="button"
												class="btn btn-sm btn-outline-secondary">View Class</button>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th class="text-capitalize">ID</th>
													<th class="text-capitalize">Student Name</th>
													<th class="text-capitalize">Standard</th>
													<th class="text-capitalize">Class</th>
													<th class="text-capitalize">Mobile Number</th>
													<th class="text-capitalize">LC Number</th>
													<th class="text-capitalize">JR LC Number</th>
													<th class="text-capitalize">LC Status</th>
													<th class="text-capitalize">Requests</th>
													<th class="text-capitalize">Action</th>
												</tr>
											</thead>

											<tbody id="tbody">
												<?php
												$i = 1;
												foreach ($all_records as $record) {
													$text = $record['lc_status'] == 'Issued' ? 'text-danger' : 'text-dark';
													?>
													<tr class="<?= $text ?>">
														<td><?= $record['id'] ?></td>
														<!-- <td><?= $record['SPRIDEN_student_id'] ?></td> -->
														<td><?= $record['name'] ?></td>
														<td><?= $record['STVCLAS_standard'] ?></td>
														<td><?= $record['STVCLAS_class'] ?></td>
														<td>
															<a
																href="tel:<?= $record['stvpcnf_mobile'] ?>"><?= $record['stvpcnf_mobile'] ?></a>
														</td>
														<td><?= $record['lc_no'] ?></td>
														<td><?= $record['lc_no_jc'] ?></td>
														<td><?= $record['lc_status'] ?></td>
														<td><?= ucfirst($record['lc_request_status']) ?></td>
														<td>
															<?php
															// Check if either lc_no or lc_no_jc is empty
															$showButton = !empty($record['lc_no']) || !empty($record['lc_no_jc']);

															// Check if the request status is 'draft' or 'rejected'
															if (($record['lc_request_status'] == 'draft' || $record['lc_request_status'] == 'rejected')) {
																if ($showButton) { ?>
																	<input type="hidden" id="lc_status"
																		value="<?= $record['lc_status'] ?>">
																	<input type="hidden" id="lc_request_status"
																		value="<?= $record['lc_request_status'] ?>">

																	<button type="button" name="request" id="request"
																		class="btn btn-sm btn-primary"
																		onclick="requestData('<?= $record['id'] ?>', '<?= $record['STVCLAS_class'] ?>', '<?= $record['STVCLAS_standard'] ?>')"
																		value="request">Request</button>
																<?php } else { ?>
																	<span class="text-muted">LC numbers missing</span>
																<?php }

																// Check if the request status is 'request'
															} elseif ($record['lc_request_status'] == 'requested') { ?>
																<a class="text-muted" href="javascript:void(0)">LC Requested</a>

																<?php
																// Check if the request status is 'approved'
															} elseif ($record['lc_request_status'] == 'approved' && $record['lc_status'] != 'issued') { ?>
																<button data-id="<?= $record['id'] ?>"
																	data-acdm="<?= $record['STVCLAS_acdm'] ?>"
																	data-token="<?= $record['token'] ?>" type="button"
																	name="Generate" id="Generate"
																	class="btn btn-sm btn-primary add-remark" value="Generate">
																	Generate
																</button>

																<?php
																// Check if the status is 'issued' and request status is 'approved'
															} elseif (strtolower($record['lc_status']) == 'issued' && $record['lc_request_status'] == 'approved') { ?>
																<button data-id="<?= $record['id'] ?>"
																	data-acdm="<?= $record['STVCLAS_acdm'] ?>"
																	data-token="<?= $record['token'] ?>" type="button"
																	name="Generate" id="Generate"
																	class="btn btn-sm btn-success add-remark" value="Generate">
																	View
																</button>
															<?php } ?>
														</td>
													</tr>
													<?php
													$i++;
												}
												?>
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

	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$(document).on('click', '#Generate', function () {
			var id = $(this).attr('data-id');
			var acdm = $(this).attr('data-acdm');
			var token = $(this).attr('data-token');
			window.location = "<?= base_url() ?>Report/student_jc_lc?student-id=" + id + "&year=" + acdm + "&token=" + token;
			console.log(window.location);
		});

		function getRecords(element) {
			let std = document.getElementById("std").value;
			let acdm_year = document.getElementById("acdm_year").value;
			if (std == '') {
				alert("Please Select Class");
				return;
			}
			if (acdm_year == '') {
				alert("Please Select Academic Year");
				return;
			}
			window.location.href = `<?= base_url() ?>report/report_stats_lc?acdm-year=${acdm_year}&class=${std}`;
		}

		function requestData(stud_id, stud_standard, stud_class) {
			let btn = document.getElementById('request');
			let acdm_year = document.getElementById('acdm_year').value;
			let request = document.getElementById('request').value;
			let lc_request_status = document.getElementById('lc_request_status').value;

			$.ajax({
				url: "<?= base_url('Report/manage_lc_request') ?>",
				method: 'POST',
				data: {
					stud_id: stud_id,
					stud_class: stud_class,
					request: request,
					acdm_year: acdm_year
				},
				beforeSend: function () {
					$(btn).html(`<i class="fas fa-spinner fa-spin"></i> Please Wait`);
				},
				success: function (data) {
					alert('Success');

					window.location.reload();
					btn.disabled = true;
					$(btn).html('Request'); // Reset button text after the operation
				}
			});
		}
	</script>
</body>

</html>