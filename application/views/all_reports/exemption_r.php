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

	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" />

	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />

	<style>
		.table thead tr th,

		.table tbody tr td {

			padding: 5px !important;

			font-size: 12px !important;

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

		<form action="" id="submit-form" method="post">

			<div class="pcoded-content">

				<!-- [ breadcrumb ] start -->

				<?php include 'application/views/all_reports/links.php'; ?>

				<!-- [ breadcrumb ] end -->

				<!-- [ Main Content ] start -->



				<div class="row margin-top-minus-40 mobile-margin-top-minus-40 mb-2">



				</div>

				<div class="print-container">

					<div class="row">

						<div class="col-md-12">

							<div class="card">

								<div class="card-body p-2">

									<div class="row">

										<div class="col-md-12">

											<!-- <h6>Filter Report</h6> -->

											<div class="row">

												<div class="col-md-3">

													<div class="form-group">

														<label for="">From Date <span class="float-right">:</span></label>
														<input type="date" name="from_date" id="from_date" class=" form-control form-control-sm" value="<?= $from_date ?>" />
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label for="">To Date <span class="float-right">:</span></label>
														<input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="<?= $to_date ?>" />
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="" style="opacity: 0;">Click on the</label>
														<button id="find-students" type="button" class="btn btn-sm btn-outline-secondary" style="margin:27px;" value="">View </button>
														<button class="btn btn-secondary btn-sm" type="submit" id="submit-btn"> <i class="fas fa-book"></i> <span id="submit-btn">Add Data</span></button>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<input type="hidden" name="total_installments" id="total_installments" value="<?= $stats != null ? $stats['last_installment'] : 0 ?>">
											<input type="hidden" name="total_students" id="total_students" value="<?= $stats != null ? count($stats['records']) : 0 ?>">
											<div class="table-responsive">
												<table class="table table-sm table-bordered table-striped" id="table-gr">
													<thead class="text-center">
														<tr>
															<th>#</th>
															<th></th>
															<th>ID</th>
															<th>Name</th>
															<th>Class</th>
															<th>Total Paid</th>

															<?php if ($stats != null) {
																for ($ins_loop = 1; $ins_loop <= $stats['last_installment']; $ins_loop++) { ?>
																	<th>Ins
																		<?= $ins_loop; ?></th>
															<?php }
															} ?>
														</tr>
													</thead>

													<tbody class="text-center">
														<?php
														$i = 1;
														$grand_total = 0;
														$grand_total_paid = 0;

														if ($stats != null) {
															foreach ($stats['records'] as $records) {
																$grand_total_paid += (float)$records['total_fees_paid'];
														?>
																<tr>
																	<td><?= $i ?></td>
																	<td>
																		<input id="checkbox<?= $i ?>" data-id="<?= $i ?>" class="action-selector" name="checkbox<?= $i ?>" type="checkbox">
																	</td>
																	<td>
																		<?= $records['id'] ?>
																		<input name="student-id<?= $i ?>" type="hidden" value="<?= $records['id'] ?>">
																	</td>
																	<td><?= $records['name'] ?></td>
																	<td><?= $records['STVCLAS_standard'] ?></td>
																	<td><?= $records['total_fees_paid'] ?></td>
																	<?php
																	$fees_installment  = explode(",", $records['installment']);
																	for ($ins_i = 0; $ins_i < $stats['last_installment']; $ins_i++) {
																		$fees_ins = array_key_exists($ins_i, $fees_installment) ? explode(":", $fees_installment[$ins_i]) : [];
																		$to_display = $fees_ins != null && array_key_exists(0, $fees_ins) ? $fees_ins[0] : '';
																		$to_display_id = $fees_ins != null && array_key_exists(1, $fees_ins) ? $fees_ins[1] : '';
																		$to_display_date = $fees_ins != null && array_key_exists(2, $fees_ins) ? $fees_ins[2] : '';
																	?>
																		<td>
																			<input name="line_id<?= $i ?>-<?= $ins_i ?>" type="hidden" id="line_id<?= $i ?>-<?= $ins_i ?>" value="<?= $to_display_id ?>">
																			<input class="text-box" id="box<?= $i ?>-<?= $ins_i ?>" name="box<?= $i ?>-<?= $ins_i ?>" type="text" value="<?= $to_display ?>" style="width:50px;" readonly>
																			<br><small><?= $to_display_date; ?></small>
																		</td>
																	<?php } ?>
																</tr>
														<?php
																$i++;
															}
														} ?>
													</tbody>
													<?php if ($stats != null) { ?>
														<tfoot>
															<th colspan="5">TOTAL</th>
															<th colspan="<?= $records['total_fees_paid'] ?>">
																<?= number_format($grand_total_paid); ?>
															</th>
														</tfoot>
													<?php } ?>
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
		</form>
	</div>


	<!-- [ Main Content ] end -->

	</div>

	</div>

	<!-- [ Main Content ] end -->

	</div>

	</div>

	<!---Include JS Files-->

	<?php include 'application/views/jsPages.php'; ?>

	<script src=" https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

	<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js">
	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js">
	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
	</script>

	<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js">
	</script>

	<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js">
	</script>

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
		document.getElementById("find-students").addEventListener('click',
			async () => {
				var html = '';
				let from = document.getElementById("from_date")
					.value != '' ? document.getElementById(
						"from_date")
					.value : 0;
				let to = document.getElementById("to_date")
					.value != '' ? document.getElementById("to_date")
					.value : 0;
				let base_url = '<?= base_url(); ?>';
				window.location =


					`${base_url}index.php/report/exemption_fees/${from}/${to}`;


			});
	</script>

	<script>
		// filter hide / show text boxes of params
		const actions = document.getElementsByClassName('action-selector');
		for (var i = 0; i < actions.length; i++) {
			actions[i].addEventListener("change", checkBoxesActions, false);
		}

		function checkBoxesActions() {
			var row_id = this.getAttribute("data-id");
			var all_ins = document.getElementById("total_installments").value;
			console.log(row_id);
			const action = document.getElementById(`checkbox${row_id}`);

			if (action.checked == true) {
				// enable 
				for (var loop = 0; loop < all_ins; loop++)
					document.getElementById(`box${row_id}-${loop}`).removeAttribute("readonly");
			} else {
				// disable
				for (var loop = 0; loop < all_ins; loop++)
					document.getElementById(`box${row_id}-${loop}`).setAttribute("readonly", "true");
			}
		}
		$('#submit-form').on('submit', function(e) {
			var btn_text = document.getElementById("submit-btn").innerHTML;
			e.preventDefault();
			$.ajax({
				url: "<?= base_url() ?>index.php/Report/update_exemption_fees",
				data: new FormData(this),
				processData: false,
				contentType: false,
				method: "POST",
				beforeSend: function() {
					$('#submit-btn').html(
						'<i class="fas fa-spinner fa-spin"></i> Please Wait');
				},
				success: function(data) {
					$('#submit-btn').html(
						'<i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span>'
					);
					console.log(data);
				}
			})
		})
	</script>

</body>

</html>