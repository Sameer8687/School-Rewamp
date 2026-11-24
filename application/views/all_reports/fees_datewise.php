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
							<h5 class="card-title">Generate L.C Report</h5>
						</div>
						<div class="card-body p-2">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">From
													<span class="float-right">:</span></label>

												<input type="date" name="from_date" id="from_date"
													class="form-control form-control-sm col-md-7"
													value="<?= $from_date ?>" />
											</div>
										</div>

										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">To
													<span class="float-right">:</span></label>

												<input type="date" name="to_date" id="to_date"
													class="form-control form-control-sm col-md-7"
													value="<?= $to_date ?>" />
											</div>
										</div>

										<div class="col-md-3">
											<div class="row">
												<label for="" class="col-md-5">Class Filter
													<span class="float-right">:</span></label>

												<select name="class_filter"
													class="form-control form-control-sm col-md-7" id="class_filter">
													<option value="">Select Type</option>
													<option <?= $class_type == '1' ? 'selected' : '' ?> value="1">With
														Class
													</option>
													<option <?= $class_type == '0' ? 'selected' : '' ?> value="0">Without
														Class
													</option>
												</select>
											</div>
										</div>

										<div class="col-md-3">
											<div class="row">
												<label for="" class="col-md-5">Type Filter
													<span class="float-right">:</span></label>

												<select name="type_filter" class="form-control form-control-sm col-md-7"
													id="type_filter">
													<option value="">Select Type</option>
													<option <?= $type_filter == '0' ? 'selected' : '' ?> value="0">DateWise
													</option>
													<option <?= $type_filter == '1' ? 'selected' : '' ?> value="1">
														Monthwise
													</option>
												</select>
											</div>
										</div>

										<div class="col-md-2">
											<button id="find-students" type="button"
												class="btn btn-sm btn-outline-secondary">View</button>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="table-gr" class="table table-striped table-bordered nowrap">

											<?php if ($class_type == '0' && $type_filter == '0'): ?>

												<thead>
													<tr>
														<th class="text-center alert-primary">#</th>
														<th class="text-center alert-primary">Academic Year</th>
														<th class="text-center alert-primary">Date</th>
														<th class="text-center alert-primary">Fees</th>
													</tr>
												</thead>

												<tbody>
													<?php
													$grand_total = 0;
													$counter = 0;
													$invalid = ['', ' ', null, '0000-00-00'];

													foreach ($all_records as $rec):
														$isFirst = true;
														$total = 0;

														foreach ($rec['data'] as $fees_record):

															$total += (float) $fees_record['sum'];

															if ($isFirst):
																?>
																<tr>
																	<td rowspan="<?= count($rec['data']) ?>" class="text-center">
																		<?= ++$counter; ?>
																	</td>

																	<td rowspan="<?= count($rec['data']) ?>" class="text-center">
																		<?= $rec['acdm_year'] ?>
																	</td>

																	<td class="text-center">
																		<?= in_array($fees_record['sprfees_feesdate'], $invalid)
																			? ''
																			: date('d-M-Y', strtotime($fees_record['sprfees_feesdate'])) ?>
																	</td>

																	<td class="text-center">
																		<?= number_format($fees_record['sum']) ?>
																	</td>
																</tr>

																<?php
																$isFirst = false;
															else:
																?>

																<tr>
																	<td class="text-center">
																		<?= in_array($fees_record['sprfees_feesdate'], $invalid)
																			? ''
																			: date('d-M-Y', strtotime($fees_record['sprfees_feesdate'])) ?>
																	</td>

																	<td class="text-center">
																		<?= number_format($fees_record['sum']) ?>
																	</td>
																</tr>

																<?php
															endif;

														endforeach;

														$grand_total += $total;
													endforeach;
													?>
												</tbody>

												<tfoot>
													<tr>
														<td colspan="3" class="text-right font-weight-bold">
															Grand Total
														</td>

														<td class="text-center font-weight-bold">
															<?= number_format($grand_total) ?>
														</td>
													</tr>
												</tfoot>

											<?php else: ?>

												<thead>
													<tr>
														<th>#</th>
														<th>Date</th>

														<?php if ($class_type == 1) { ?>
															<th>Class</th>
														<?php } ?>

														<th>Fees</th>
													</tr>
												</thead>

												<tbody>

													<?php
													$i = 1;
													$grand_total = 0;

													foreach ($all_records as $records):

														$grand_total += (float) $records['sum'];
														?>

														<tr>
															<td><?= $i++ ?></td>

															<td>
																<?php
																if ($type_filter == 0) {
																	echo $records['sprfees_feesdate'] == '0000-00-00'
																		? ''
																		: date('d-M-Y', strtotime($records['sprfees_feesdate']));
																} else {

																	$month_array = [
																		'January',
																		'February',
																		'March',
																		'April',
																		'May',
																		'June',
																		'July',
																		'August',
																		'September',
																		'October',
																		'November',
																		'December'
																	];

																	echo $month_array[(int) $records['sprfees_feesdate'] - 1]
																		. ' - ' .
																		$records['sprfees_month'];
																}
																?>
															</td>

															<?php if ($class_type == 1) { ?>
																<td><?= $records['sprfees_standard'] ?></td>
															<?php } ?>

															<td><?= $records['sum'] ?></td>
														</tr>

													<?php endforeach; ?>

												</tbody>

												<tfoot>
													<tr>
														<td colspan="<?= $class_type == 1 ? 3 : 2 ?>" class="text-right">
															Total Amount
														</td>

														<td class="text-center">
															<?= number_format($grand_total) ?>
														</td>
													</tr>
												</tfoot>

											<?php endif; ?>

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
		$("#table-gr").DataTable({
			"pageLength": 17,
			dom: "Bfrtip",
			buttons: [
				'excel', 'pdf', 'print'
			]
		});
	</script>

	<script>
		document.getElementById("find-students").addEventListener('click', async () => {
			var html = '';
			let from = document.getElementById("from_date").value != '' ? document.getElementById("from_date").value : 0;
			let to = document.getElementById("to_date").value != '' ? document.getElementById("to_date").value : 0;
			let base_url = '<?= base_url(); ?>';
			let class_type = document.getElementById("class_filter").value;
			let type_filter = document.getElementById("type_filter").value;
			window.location = `${base_url}report/fees_date_wise/${from}/${to}/${class_type}/${type_filter}`;
		});

		$(document).on("click", ".getRowData", function () {
			let header_id = $(this).attr("data-header-id");
			let url = "<?= base_url(); ?>index.php/report/admissionreport/" + header_id;
			window.location = url;
		});
	</script>
</body>

</html>