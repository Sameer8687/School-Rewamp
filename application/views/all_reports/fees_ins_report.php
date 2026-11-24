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
						<div class="card-body">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Class
													<span class="float-right">:</span></label>
												<select class="form-control form-control-sm col-md-7" name="standard"
													id="standard" 
													value="<?= $filter_option; ?>">
													<option <?= $std_selected == 0 ? 'selected' : '' ?> value="0">Select
														Class</option>
													<?php foreach ($classes as $cls): ?>
														<option <?= $std_selected == $cls['stvfees_class'] ? 'selected' : '' ?>
															value="<?= $cls['stvfees_class'] ?>">
															<?= $cls['stvfees_class'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Division
													<span class="float-right">:</span></label>
												<select class="form-control form-control-sm col-md-7" name="division"
													id="division" 													value="<?= $filter_option; ?>">
													<option <?= $div_selected == 0 ? 'selected' : '' ?> value="0">Select
														Division</option>
													<option <?= $div_selected == 'A' ? 'selected' : '' ?> value="A">A
													</option>
													<option <?= $div_selected == 'B' ? 'selected' : '' ?> value="B">B
													</option>
													<option <?= $div_selected == 'C' ? 'selected' : '' ?> value="C">C
													</option>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<button id="view-records" class="btn btn-sm btn-outline-secondary">View
												Records</button>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th class="text-capitalize" style="display: none;">Class Number</th>
													<th class="text-capitalize">Sr.No</th>
													<th class="text-capitalize">Student Name</th>
													<th class="text-capitalize">Class</th>
													<th class="text-capitalize">Fees Paid</th>
													<th class="text-capitalize">RTE</th>
													<th class="text-capitalize">Installment </th>
												</tr>
											</thead>

											<tbody id="tbody">
												<?php $i = 1;
												foreach ($all_records as $record) {
													if ($record['STVCLAS_standard'] == "I") {
														$classStd = "First";
													} else if ($record['STVCLAS_standard'] == "II") {
														$classStd = "Second";
													} else if ($record['STVCLAS_standard'] == "III") {
														$classStd = "Third";
													} else if ($record['STVCLAS_standard'] == "IV") {
														$classStd = "Fourth";
													} else if ($record['STVCLAS_standard'] == "V") {
														$classStd = "Fifth";
													} else if ($record['STVCLAS_standard'] == "VI") {
														$classStd = "Sixth";
													} else if ($record['STVCLAS_standard'] == "VII") {
														$classStd = "Seventh";
													} else if ($record['STVCLAS_standard'] == "VIII") {
														$classStd = "Eighth";
													} else if ($record['STVCLAS_standard'] == "IX") {
														$classStd = "Ninth";
													} else if ($record['STVCLAS_standard'] == "Sr.Kg.") {
														$classStd = "Sr.Kg.";
													} else if ($record['STVCLAS_standard'] == "Jr.Kg.") {
														$classStd = "Jr.Kg.";
													} else if ($record['STVCLAS_standard'] == "Nursery") {
														$classStd = "Nursery";
													} else {
														$classStd = "";
													} ?>

													<?php if ($record['rte_status'] == 'No') { ?>
														<?php echo '<tr data-id=" ' . $record['id'] . '" class="add-remark">';
													} else {
														echo '<tr style="background-color:#fff0f0;"   data-id=" ' . $record['id'] . '" class="add-remark">' ?>
													<?php } ?>

													<td style="display: none;"><?= $classStd; ?></td>
													<td><?= $i ?></td>
													<td><?= $record['name'] ?></td>
													<td><?= $record['class_name'] ?></td>
													<td><?= $record['total_fees_paid'] ?></td>
													<td><?= $record['rte_status'] ?></td>
													<td class="ins-inner-box">
														<?php
														$ins_array = explode(",", $record['installments']);
														foreach ($ins_array as $ins_a) {
															$inner = explode("->", $ins_a); ?>

															<?php if ($inner != null) { ?>
																<div class="box-ins">
																	<p>
																		<?= $inner[0]; ?> <br>
																		<?php if (count($inner) > 1) { ?>
																			<span><?= $inner[1] != '' ? date('d/m/y', strtotime($inner[1])) : ''; ?></span>
																		<?php } ?>
																	</p>
																</div>
															<?php } ?>
														<?php } ?>
													</td>
													</tr>
													<?php $i++;
												} ?>
											</tbody>
										</table>
										<style>
										.ins-inner-box {
										display: flex;
											flex-direction: row;
										}
										.ins-inner-box .box-ins {
											display: inline-block;
										padding: 2px;
										background-color: #d2efe8;
											margin: 2px;
											text-align: center;
											border-radius: 3px;
										}

										.ins-inner-box .box-ins p {
											margin-bottom: 0px;
											font-size: 12px !important;
											font-weight: bold;
										}

										.ins-inner-box .box-ins p span {
											font-size: 10px !important;
											margin-top: -5px !important;
										}
									</style>
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
		$("#exampletable").DataTable({
			"pageLength": 10,
			dom: "Bfrtip",
			buttons: [
				'excel', 'pdf', 'print'
			]
		});
	</script>

	<script>
		$('#view-records').click(function () {
			var std = $('#standard').val();
			var div = $('#division').val();
			window.location = `<?= base_url() ?>report/fees_instment_report/${std}/${div}`;
		});

		function printDiv(printableArea) {
			var printContents = document.getElementById(printableArea).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
	</script>
</body>

</html>