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
							<h5 class="card-title">Admission Report</h5>
						</div>
						<div class="card-body p-2">
							<div class="print-container">

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" data-custom="true"
											class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th class="text-capitalize">ID</th>
													<th class="text-capitalize">Student ID</th>
													<th class="text-capitalize">Gender</th>
													<th class="text-capitalize">Name</th>
													<th class="text-capitalize">Class</th>
													<th class="text-capitalize">Total Fees</th>
													<th class="text-capitalize">Paid</th>
													<th class="text-capitalize">Exumption</th>
													<th class="text-capitalize">Mobile</th>
													<th class="text-capitalize">whatsapp</th>
													<th class="text-capitalize">Bal</th>
													<th class="text-capitalize">Admission Date</th>
													<th class="text-capitalize">Type</th>
												</tr>
											</thead>

											<tbody id="tbody">
												<?php $i = 1;
												$total_fees_all = 0;
												$total_fees_paid_by = 0;
												$total_fees_bal = 0;
												$total_ex_val = 0;
												foreach ($all_records as $record) {
													$total_fees_all += (float) $record['total_fees_of_class'];
													$total_fees_paid_by += (float) $record['fees'];
													$total_fees_bal += (float) $record['bharna'];
													$total_ex_val += (float) $record['exumtion'];

													?>
													<tr>
														<td><?= $record['id'] ?></td>
														<td><?= $record['student_id'] ?></td>
														<td><?= $record['SPRIDEN_gender'] ?></td>
														<td><?= $record['name'] ?></td>
														<td><?= $record['class'] ?></td>
														<td><?= $record['total_fees_of_class'] ?></td>
														<td><?= $record['fees'] ?></td>
														<td><?= $record['exumtion'] ?></td>
														<td><?= $record['mobile'] ?></td>
														<td><?= $record['whatsapp'] ?></td>
														<td><?= $record['bharna'] ?></td>
														<td><?= $record['adm_date'] == '' || $record['adm_date'] == null ? '' : date('d-M-Y', strtotime($record['adm_date'])) ?>
														</td>
														<td
															class="<?= $record['type'] == 'New Admission' ? 'text-danger' : 'text-primary' ?>">
															<?= substr($record['type'], 0, 1) ?>
														</td>
													</tr>
													<?php $i++;
												} ?>
											</tbody>
											<tfoot>
												<tr>
													<th colspan="4">Total</th>
													<th><?= number_format($total_fees_all) ?></th>
													<th><?= number_format($total_fees_paid_by) ?></th>
													<th><?= number_format($total_ex_val) ?></th>
													<th><?= number_format($total_fees_bal) ?></th>
													<th colspan="2"></th>
												</tr>
											</tfoot>
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
		$(document).on("change", '.view-record', function (e) {
			let vls = $(this).val();
			window.location = '<?= base_url() ?>' + 'report/readmission/' + vls;
		});
	</script>
</body>

</html>