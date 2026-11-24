<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Issue LC</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Phoenixcoded" />
	<!---Include CSS Files-->
	<?php include 'application/views/cssPages.php'; ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css" />
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

	<?php include 'application/views/sidebar.php'; ?> <?php include
			'application/views/topNav.php'; ?>
	<div class="pc-container">
		<div class="pcoded-content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="col-md-6">
								<h5 class="card-title">Student Books Report</h5>
							</div>
							<div class="col-md-6">

								<button class="btn btn-secondary btn-sm float-right" id="save-btn" type="submit"> <i class="fas fa-user-graduate"></i> <span id="dynamicLabelAddButton">Save Details</span></button>
											

							</div>
						</div>
						<div class="card-body">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Class
													<span class="float-right">:</span></label>

												<select name="std" id="std" class="form-control form-control-sm col-md-7">
												<option value="">Select Class</option>
												<?php foreach ($classes as $clas) { ?>
													<option value="<?= $clas['stvfees_class'] ?>"><?= $clas['stvfees_class'] ?></option>
												<?php } ?>
											</select>
											<input type="hidden" name="max_rows" id="max_rows">

											</div>
										</div>
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Academic year
													<span class="float-right">:</span></label>

												<select name="acdm_year" id="acdm_year" class="form-control form-control-sm col-md-7">
												<option value="">Select Academic Year</option>
												<?php foreach ($academic_years as $academic_year) {
													if ($academic_details['stvacdm_id'] == $academic_year['stvacdm_id']) {
														$selected = 'selected';
													} else {
														$selected = '';
													}
												?>
													<option <?= $selected; ?> value="<?= $academic_year['stvacdm_id']; ?>"><?= $academic_year['stvacdm_year']; ?></option>
												<?php } ?>
											</select>

											</div>
										</div>

										<div class="col-md-4">

											<button id="find-students" type="button" class="btn btn-sm btn-outline-secondary">View Class</button>


										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="example" class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
														<th style="width: 5%;">#</th>
														<th style="width: 10%;">Student ID</th>
														<th style="width: 30%;">Student Name</th>
														<th style="width: 5%;">Amount</th>
														<th style="width: 40%;">Comments</th>
														<th style="width: 10%;">Date</th>
													</tr>
											</thead>

											<tbody id="tbody">
												<?php $i = 0;
													foreach ($all_records as $record) { ?>
														<tr>
															<td><?= ++$i; ?></td>
															<td><?= $record['SPRIDEN_student_id'] ?></td>
															<td><?= $record['name'] ?></td>
															<td><?= $record['camount'] ?></td>
															<td><?= $record['comments'] ?></td>
															<td><?= $record['cdate'] != '0000-00-00' ? date('d-M-Y', strtotime($record['cdate'])) : '' ?></td>
														</tr>
													<?php } ?>
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
		document.getElementById("find-students").addEventListener('click', async () => {
			var html = '';
			let std = document.getElementById("std").value;
			let acdm_year = document.getElementById("acdm_year").value;
			let base_url = '<?= base_url(); ?>';
			window.location = base_url + 'student/books_records/' + std + '/' + acdm_year;

		});
		$(document).ready(function() {
			$('#example').DataTable({
				pageLength: '25',
				dom: 'Bfrtip',
				buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
			});
		});
	</script>

</html>