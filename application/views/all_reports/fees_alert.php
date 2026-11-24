<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Fees Alert Receipt</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<?php include 'application/views/cssPages.php'; ?>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />

	<style>
		.table thead tr th,
		.table tbody tr td {
			padding: 5px !important;
			font-size: 16px !important;
			border: 1px solid #77777730;
		}

		.table tbody tr td.no-padding {
			padding: 0 !important;
		}

		.table tbody tr td .form-control.no-border {
			border: none !important;
			outline: none !important;
		}

		.dt-button.buttons-print,
		.dt-button.buttons-copy,
		.dt-button.buttons-csv,
		.dt-button.buttons-excel,
		.dt-button.buttons-pdf {
			background-color: #53769c !important;
			color: #fff !important;
			padding: 0px 10px 0px 10px !important;
		}

		#multi-colum-dts {
			border: none !important;
		}

		.pcoded-content {
			padding: 13px !important;
		}

		.card-head {
			padding: 10px !important;
		}

		.card-body {
			padding: 10px !important;
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
	<?php include 'application/views/sidebar.php'; ?>
	<!--Include Top Navbar-->
	<?php include 'application/views/topNav.php'; ?>
	<!-- [ Main Content ] start -->
	<div class="pc-container" id="printableArea">
		<form action="" id="classCreateForm" method="post">
			<div class="pcoded-content">
				<div class="print-container margin-top-minus-40 mobile-margin-top-minus-0">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="row no-print">
										<label for="" class="col-md-1">Year <span class="">:</span></label>
										<select name="filter_year" id="filter_year"
											class="col-md-1 form-control form-control-sm">
											<option value="0">Select Year</option>

											<?php foreach ($academic_year as $acdm) { ?>
												<option
													<?= ($year_filter == $acdm['stvacdm_id'] || ($year_filter == 0 && $selected_academic_year == $acdm['stvacdm_id'])) ? 'selected' : ''; ?>
													value="<?= $acdm['stvacdm_id'] ?>">
													<?= $acdm['stvacdm_year'] ?>
												</option>
											<?php } ?>
										</select>

										<label for="" class="col-md-1">Class <span class="">:</span></label>
										<select name="filter_std" id="filter_std"
											class="col-md-1 form-control form-control-sm">
											<option value="0">Select Class</option>
											<?php foreach ($classes as $clas) { ?>
												<option <?= $std_filter == $clas['stvfees_class'] ? 'selected' : ''; ?>
													value="<?= $clas['stvfees_class'] ?>"><?= $clas['stvfees_class'] ?>
												</option>
											<?php } ?>
										</select>

										<label for="" class="col-md-1">Div <span class="">:</span></label>
										<select name="filter_div" id="filter_div"
											class="col-md-1 form-control form-control-sm">
											<option <?= $div_filter == 0 ? 'selected' : '' ?> value="0">Select Division
											</option>
											<option <?= $div_filter == 'A' ? 'selected' : '' ?> value="A">A</option>
											<option <?= $div_filter == 'B' ? 'selected' : '' ?> value="B">B</option>
											<option <?= $div_filter == 'C' ? 'selected' : '' ?> value="C">C</option>
										</select>

										<label class="col-md-1"> Fees : </label>
										<input type="text" class="form-control form-control-sm col-md-1" id="amount"
											name="amount" value="<?= $amount ?>">

										<div class="col-md-3">
											<button id="view-record" type="button"
												class="btn btn-sm btn-outline-secondary">View Class</button>
											<a href="" onclick="printDiv('printableArea')"
												class="btn btn-primary btn-sm" value="print a div!"> <i
													class="fa fa-print" aria-hidden="true"></i> Print</a>
										</div>

										<style type="text/css" media="print">
											@media print {
												.no-print {
													display: none !important;
												}
											}

											@media print {

												html,
												body .print-box {
													height: auto;
													page-break-after: unset;
													font-size: 12px;
												}

												.print:last-child {
													page-break-after: always;
												}
											}

											@page {
												size: A4 portrait;
												margin: 40px 5px 35px;
											}
										</style>
									</div>
								</div>

								<div class="card-body">
									<?php $amount_to_paid = 0;
									foreach ($all_records as $record):
										$primary_array = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII'];
										$secondary_arr = ['VIII', 'IX', 'X'];
										$amount_to_paid = 0;
										// Updated 2024 Sep 26 - Ansari Kamran 
										if (in_array($record['STVCLAS_standard'], $primary_array)) {
											if ((float) $record['total_paid'] < (float) $amount) {
												$amount_to_paid = (float) $amount - (float) $record['total_paid'];
											}
										}

										if (in_array($record['STVCLAS_standard'], $secondary_arr)) {
											if ((float) $record['total_paid'] < (float) $amount) {
												$amount_to_paid = (float) $amount - (float) $record['total_paid'];
											}
										}
										$rte = '';
										$rte_status = '';
										$rte_flag = false;
										if ($record['rte_status'] == 'Yes') {
											$rte = 'bg-secondary';
											$rte_status = ' - RTE';
											$rte_flag = true;
										}

										// calculate fees exemption and balance
										$record['balance'] = max(0, (float) $record['balance'] - (float) $record['total_exemption']);

										$mobile_num = $record['stvpcnf_whatsapp'];
										$message_wha = "अस्सलामु अलैकुम पैरेंट्स,
   												आपका बच्चा / बच्ची " . $record['name'] . " STD. " . $record['STVCLAS_standard'] . " - " . $record['STVCLAS_class'] . " में पढाई करता / करती है. उसकी फीस आपने " . $record['total_paid'] . " रुपये जमा की है. " . $record['balance'] . " रूपये जमा करवाना बाकि है. इसलिए उसकी फीस स्कूल में आकर जमा करवा दीजिये ताकी उसकी पढाई में कोई दिक्कत ना हो।
   																					\n
   
   												Assalamualaikum Parents,
   												Your Son/Daughter " . $record['name'] . " studies in STD. " . $record['STVCLAS_standard'] . " " . $record['STVCLAS_class'] . ". Till now you have paid Fees Amount of Rs " . $record['total_paid'] . ". Rs " . $record['balance'] . " is yet to be paid. So please pay the remaining fees amount of your child to avoid inconvenience in his/her studies.";

									?>

										<?php if ($record['balance'] > 0 && $rte == false): ?>
											<!-- // Updated 2024 Aug 05 - Ansari Kamran -->
											<div class="row print <?= $rte; ?>">
												<div class="col-md-12 print-box">
													<p class="text-center font-weight-bold "> <?= $rte_status ?></p>
													<p style="font-size: 21.2px;">
														<?php if ($mobile_num != '' && $mobile_num != null) {
															$mobile_num = str_replace(" ", "", $mobile_num);
														?>
															<a target="_blank"
																href="https://api.whatsapp.com/send?phone=+91<?= $mobile_num ?>&text=<?= $message_wha ?>">
																<i class="bi bi-whatsapp"></i>
															</a>
														<?php } ?>
														<span style="font-weight: 600;">नोटिस:</span>
														अस्सलामु अलैकुम पैरेंट्स,
														आपका बच्चा / बच्ची <span class=""
															style="font-weight:550 ;"><?= $record['name'] ?></span> कक्षा <span
															class="" style="font-weight:550 ;">[
															<?= $record['STVCLAS_standard'] . '-' . $record['STVCLAS_class'] ?>
															]</span> में पढाई करता / करती है.उसकी फीस आपने <span class=""
															style="font-weight:550 ;"><u><?= number_format($record['total_paid']) ?></u></span>
														रुपये जमा की है. <span class=""
															style="font-weight:550 ;"><u><?= number_format($record['balance']) ?></u></span>
														रूपये फीस जमा करवाना बाकि है.जल्द से जल्द उसकी फीस स्कूल में आकर जमा
														करवा दीजिये ताकी उसकी पढाई में कोई दिक्कत ना हो।
													</p>
													<hr>
												</div>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<!-- [ Main Content ] end -->
	</div>

	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>
	<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
	<script>
		$("#table-gr").DataTable({
			"pageLength": 25,
			dom: "Bfrtip",
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
	</script>

	<script>
		$('#view-record').click(function(e) {
			var year = $('#filter_year').val();
			var std = $('#filter_std').val();
			var div = $('#filter_div').val();
			var amount = $('#amount').val();
			window.location = `<?= base_url() ?>report/fees_alert/${year}/${std}/${div}/${amount}`;
		});

		$(document).ready(function() {
			$('#example').DataTable();
		});

		function printDiv(printableArea) {
			var printContents = document.getElementById(printableArea).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
	</script>
	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
</body>

</html>