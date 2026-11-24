<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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

	<?php include 'application/views/sidebar.php'; ?>
	<?php include 'application/views/topNav.php'; ?>
	<div class="pc-container">
		<div class="pcoded-content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="col-md-2">
								<h5 class="card-title">Daily Fees Report</h5>
							</div>
							<div class="col-md-10 d-flex align-items-center justify-content-between">
								<!-- Current Fees -->
								<div>
									<span class="text-info mr-1">
										Cur.:
									</span>
									<?php foreach ($stats['current_fees_breakdown'] as $currentBreakDown): ?>
										<a href="javascript:void(0)" class="mr-1">
											<?= ucfirst($currentBreakDown['payment_mode']) ?> :
											<?= number_format($currentBreakDown['amount']) ?>
										</a>
									<?php endforeach; ?>
									<a href="javascript:void(0)" class="text-success">
										Total :
										<?= number_format($stats['current_total']) ?>
									</a>
								</div>
								<!-- Previous Fees -->
								<div>
									<span class="text-info mr-1">
										Pre. :
									</span>
									<?php foreach ($stats['previous_fees_breakdown'] as $previousBreakDown): ?>
										<a href="javascript:void(0)" class="mr-1">
											<?= ucfirst($previousBreakDown['payment_mode']) ?> :
											<?= number_format($previousBreakDown['amount']) ?>
										</a>
									<?php endforeach; ?>
									<a href="javascript:void(0)" class="text-success">
										Total :
										<?= number_format($stats['previous_total']) ?>
									</a>
								</div>
								<!-- Grand Total -->
								<div class="">
									<span class="">
										Grand Total :
										<?= number_format($stats['grand_total']) ?>
									</span>
								</div>
							</div>
						</div>
						<div class="card-body p-2">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-4">From <span
														class="float-right">:</span></label>
												<input type="date" class="form-control form-control-sm col-md-8"
													name="form_date" id="from_date" value="<?= $from_date ?>" />
											</div>
										</div>
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-4">To <span
														class="float-right">:</span></label>
												<input type="date" class="form-control form-control-sm col-md-8"
													name="to_date" id="to_date" value="<?= $to_date ?>" />
											</div>
										</div>
										<div class="col-md-2">
											<div class="row">
												<select name="year" id="year"
													class="form-control form-control-sm col-md-12">
													<option value="current" <?= ($selected_year == 'current') ? 'selected' : ''; ?>>
														Current Academic Year
													</option>

													<option value="all" <?= ($selected_year == 'all') ? 'selected' : ''; ?>>
														All Academic Year
													</option>
												</select>
											</div>
										</div>
										<div class="col-md-3" id="newAdmissionDiv">
											<div class="row align-items-center">
												<label for="" class="col-md-8">
													New Admission<span class="float-right">:</span>
												</label>
												Yes <input value="on" type="radio" name="new_admission" class="p-1"
													id="yes" <?= ($new_admission == 'on') ? 'checked' : ''; ?>> &nbsp;
												No <input value="off" type="radio" name="new_admission" class="p-1"
													id="no" <?= ($new_admission == 'off' || $new_admission == '') ? 'checked' : ''; ?>>
											</div>
										</div>
										<div class="col-md-2">
											<div class="row">&nbsp
												<button type="button" id="all_search"
													class="btn btn-sm btn-outline-primary px-2">Search</button> &nbsp
												<button type="button" id="today_search"
													class="btn btn-sm btn-outline-secondary px-2">Today</button>&nbsp
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="dt-responsive table-responsive margin-top-minus-10">
											<table id="simpletable" class="table table-striped table-bordered nowrap">
												<thead>
													<tr>
														<th class="text-capitalize">#</th>
														<th class="text-capitalize">ID</th>
														<th class="text-capitalize">Student ID</th>
														<th class="text-capitalize">Student Name</th>
														<th class="text-capitalize">Class</th>
														<th class="text-capitalize">Prev Total </th>
														<th class="text-capitalize">Amount </th>
														<th class="text-capitalize">Time Stamp </th>
														<th class="text-capitalize">Due Date </th>
														<th class="text-capitalize">Recieved By </th>
														<th class="text-capitalize">Note </th>
														<th class="text-capitalize">Payment Mode </th>
														<th class="text-capitalize">Source </th>
														<th class="text-capitalize">Year </th>
													</tr>
												</thead>
												<tbody id="fees-tbody">
													<?php
													$invalid = ['', ' ', null, '0000-00-00'];
													$i = 1;
													if ($fees_data != null) {
														foreach ($fees_data as $fees) { ?>
															<tr>
																<td><?= $i; ?></td>
																<td><?= $fees['id']; ?></td>
																<td><?= $fees['SPRIDEN_student_id']; ?></td>
																<td><?= $fees['name']; ?></td>
																<td><?= $fees['sprfees_standard']; ?></td>
																<td title="<?= $fees['stvacdm_year'] ?>">
																	<?= $fees['total_prev_fees']; ?>
																</td>
																<td title="<?= $fees['stvacdm_year'] ?>">
																	<?= $fees['sprfees_amount']; ?>
																</td>
																<td><?= $fees['sprfees_timestamp']; ?></td>
																<td><?= in_array($fees['sprfees_due_date'], $invalid) ? '' : date('d-M-Y', strtotime($fees['sprfees_due_date'])); ?>
																</td>
																<td><?= $fees['spruser_name']; ?></td>
																<td class="txtcapitalize"><?= $fees['sprfees_notes']; ?></td>
																<td class="txtcapitalize"><?= $fees['payment_mode']; ?></td>
																<td><?= $fees['sprfees_source']; ?></td>
																<td><?= $fees['stvacdm_year']; ?></td>
															</tr>
															<?php $i++;
														} ?>
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
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<style>
							.text-oliver {
								color: #2d73bf !important;
							}
						</style>
						<h5 class="modal-title text-oliver" id="exampleModalLabel">Receiverwise Fees Collection
							(<?= date('d M Y', strtotime($curr_date)); ?>) </h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<table class="table table-sm table-bordered">
									<tbody>
										<tr>
											<th class="alert alert-success">#</th>
											<th class="alert alert-success">User</th>
											<th class="alert alert-success">Payment Type</th>
											<th class="alert alert-success">Amount</th>
										</tr>
										<?php $counter = 0;
										$final = 0;
										foreach ($collections as $collection):
											$isFirst = true;
											$total = 0;
											foreach ($collection['history'] as $history):
												$total += (float) $history['total'];
												if ($isFirst): ?>
													<tr>
														<td rowspan="<?= count($collection['history']) ?>" class="font-weight-bold">
															<?= ++$counter; ?>
														</td>
														<td rowspan="<?= count($collection['history']) ?>" class="font-weight-bold">
															<?= $collection['user'] ?>
														</td>
														<td><?= $history['mode'] ?></td>
														<td><?= $history['total'] ?></td>
													</tr>
													<?php $isFirst = false; else: ?>
													<tr>
														<td><?= $history['mode'] ?></td>
														<td><?= $history['total'] ?></td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
											<tr class="alert alert-warning">
												<th colspan="3" class="text-right">Total Amount</th>
												<th><?= $total ?></th>
											</tr>
											<?php
											$final += $total;
										endforeach; ?>
										<tr class="alert alert-success">
											<th colspan="3" class="text-right">Final Total Amount</th>
											<th><?= $final ?></th>
										</tr>
									</tbody>
								</table>
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
		$('#year').change(function () {
			$('#newAdmissionDiv').toggle($(this).val() != 'all');
		}).trigger('change');
		$('#all_search').click(function () {
			var From_date = $('#from_date').val();
			var To_date = $('#to_date').val();
			var year = $('#year').val();
			var new_admission = $('input[name="new_admission"]:checked').val() || '';
			var url = '<?= base_url('report/daily_fees_report') ?>';
			if (year != '') {
				url += '/' + year;
			}
			url += '?from-date=' + From_date +
				'&to-date=' + To_date +
				'&new_admission=' + new_admission;
			window.location = url;
		});
		$('#today_search').click(function () {
			var From_date = '<?= $today; ?>';
			var year = $('#year').val();
			var new_admission =
				$('input[name="new_admission"]:checked').val() || '';
			var url = '<?= base_url('report/daily_fees_report') ?>';
			if (year != '') {
				url += '/' + year;
			}
			url += '?from-date=' + From_date +
				'&to-date=' + From_date +
				'&new_admission=' + new_admission;
			window.location = url;
		});
	</script>
</body>
</html>