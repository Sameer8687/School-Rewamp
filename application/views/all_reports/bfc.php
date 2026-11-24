<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Bonafide Certificate</title>
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

		@media print {
			body * {
				visibility: hidden;
			}

			.print-container {
				margin-top: -200px;
			}

			.print-container,
			.print-container * {
				visibility: visible;
			}
		}
	</style>
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
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">
								Bonafide Certificate
							</h5>
						</div>
						<style>
							.border {
								border: 1px solid;
							}
						</style>
						<div class="card-body">
							<style>
								.table tr td,
								.table tr th {
									padding: 5px !important;
									font-size: 11px !important;
									cursor: pointer;
								}
							</style>

							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-6">
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
													<th class="text-capitalize" style="display: none;">Class Number</th>
													<th class="text-capitalize">ID</th>
													<th class="text-capitalize">Student Name</th>
													<th class="text-capitalize">Standard</th>
													<th class="text-capitalize">Class</th>
													<th class="text-capitalize">Mobile Number</th>
												</tr>
											</thead>
											<tbody id="tbody">
												<?php $i = 1;
												foreach ($all_records as $record) {
													?>
													<tr data-id="<?= $record['id'] ?>"
														data-acdm="<?= $record['STVCLAS_acdm'] ?>" class="add-remark">
														<td style="display: none;"><?= $classStd; ?></td>
														<td><?= $record['SPRIDEN_student_id'] ?></td>
														<td><?= $record['name'] ?></td>
														<td><?= $record['STVCLAS_standard'] ?></td>
														<td><?= $record['STVCLAS_class'] ?></td>
														<td><a
																href="tel:<?= $record['phone']; ?>"><?= $record['phone']; ?></a>
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
			<!-- [ Main Content ] end -->
		</div>
	</div>
	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>
	<script>
		$('#due_date').change(function () {
			var filter_date = $(this).val();
			var url = "<?= base_url('report/report_stats') ?>?action=search&due-date=" + filter_date;
			window.location = url;
		});

		$(document).on('click', '.add-remark', function () {
			var id = $(this).attr('data-id');
			var acdm = $(this).attr('data-acdm');
			window.open("<?= base_url() ?>Report/student_report_bfc?student-id=" + id + "&year=" + acdm, "_blank");
		});

		function getRecords(element) {
			console.log('element');
			let acdm_year = document.getElementById("acdm_year").value;
			if (acdm_year == '') {
				alert("Please Select Academic Year");
				return;
			}
			window.location.href = `<?= base_url() ?>report/report_stats_bf?acdm_year=${acdm_year}`;
		}
	</script>
</body>

</html>