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
							<h5 class="card-title">All Student Status Report</h5>
						</div>
						<div class="card-body p-2">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">Class<span
														class="float-right">:</span></label>
												<select name="class" id="class"
													class="form-control form-control-sm col-md-7">
													<option value="0">Select Class</option>
													<option <?= $class_filter == 'ALL' ? 'selected' : '' ?> value="ALL">ALL
													</option>
													<?php foreach ($classes as $clas) { ?>
														<option <?= $class_filter == $clas['stvfees_class'] ? 'selected' : '' ?> value="<?= $clas['stvfees_class'] ?>">
															<?= $clas['stvfees_class'] ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>

										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">Div
													<span class="float-right">:</span></label>
												<select name="division" id="division"
													class="form-control form-control-sm col-md-7">
													<option value="0">Division</option>
													<option <?= $division_filter == 'A' ? 'selected' : '' ?> value="A">A
													</option>
													<option <?= $division_filter == 'B' ? 'selected' : '' ?> value="B">B
													</option>
													<option <?= $division_filter == 'C' ? 'selected' : '' ?> value="C">C
													</option>
												</select>
											</div>
										</div>

										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-6">Due Date
													<span class="float-right">:</span></label>
												<input type="date" class="form-control form-control-sm col-md-6"
													name="due_date" id="due_date" value="<?= $filter_option; ?>" />
											</div>
										</div>

										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">RTE
													<span class="float-right">:</span></label>
												<select name="rte_status" id="rte_status"
													class="form-control form-control-sm col-md-7">
													<option <?= $checkBox == 'yes' ? 'selected' : '' ?> value="yes">Yes
													</option>
													<option <?= $checkBox == 'no' ? 'selected' : '' ?> value="no">No
													</option>
												</select>
											</div>
										</div>

										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">Year
													<span class="float-right">:</span></label>
												<select name="acdm_year" id="acdm_year"
													class="form-control form-control-sm col-md-7">
													<option value="">Select Year</option>
													<?php foreach ($academic_years as $acdm) { ?>
														<option <?= $filter_acdm == $acdm['stvacdm_id'] ? 'selected' : '' ?>
															value="<?= $acdm['stvacdm_id'] ?>">
															<?= $acdm['stvacdm_year'] ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>

										<div class="col-md-2">
											<button class="btn btn-sm btn-outline-secondary" id='btn'>View</button>

										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th class="text-capitalize" style="display: none;">Class</th>
													<th class="text-capitalize" style="display: none;">Div</th>
													<th class="text-capitalize" style="display: none;">Class Code</th>
													<th class="text-capitalize">#</th>
													<th class="text-capitalize">ID</th>
													<th class="text-capitalize">Student ID</th>
													<th class="text-capitalize">Student Name </th>
													<th class="text-capitalize">Gender</th>
													<th class="text-capitalize">Total Fees </th>
													<th class="text-capitalize">Paid </th>
													<th class="text-capitalize">Exemp </th>
													<th class="text-capitalize">Bal </th>
													<th class="text-capitalize">RTE</th>
													<th class="text-capitalize">Status</th>
													<th class="text-capitalize">Action</th>
													<th class="text-capitalize">Last Paid </th>
													<th class="text-capitalize">Due Date </th>
													<th class="text-capitalize">Next Date</th>
													<th class="text-capitalize">Mobile No</th>
													<th class="text-capitalize">Remark </th>
												</tr>
											</thead>
											<tbody id="tbody">

												<?php $i = 1;
												$total_fees_all = 0;
												$total_fees_paid_by = 0;
												$total_fees_bal = 0;
												$total_ex_val = 0;

												foreach ($all_records as $record) {
													// $record['bharna'] = $record['bharna'] == '' ? 0 : $record['bharna'];
													$record['exumtion'] = $record['exumtion'] == '' ? 0 : $record['exumtion'];

													$exumption = (float) $record['exumtion'];
													$total_fees_of_class = $record['total_fees_of_class'] == '' ? 0 : (float) $record['total_fees_of_class'];
													$total_paid = $record['total_fees_paid'] == '' ? 0 : (float) $record['total_fees_paid'];

													$labelOfPaid = '';
													if($exumption > 0){
														$labelOfPaid = 'text-primary font-weight-bold';
													}

													$total_paid_with_exumption = $total_paid + $exumption;

													$bharna = (($total_fees_of_class - $total_paid) - $exumption);
													$total_fees_all += (float) $total_fees_of_class;
													$total_fees_paid_by += (float) $total_paid;
													$total_fees_bal += (float) $bharna;
													$total_ex_val += (float) $exumption;

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
													} else if ($record['STVCLAS_standard'] == "X") {
														$classStd = "Tenth";
													} else if ($record['STVCLAS_standard'] == "XI") {
														$classStd = "Eleventh";
													} else if ($record['STVCLAS_standard'] == "XII") {
														$classStd = "Twelfth";
													} else if ($record['STVCLAS_standard'] == "Sr.Kg.") {
														$classStd = "Sr.Kg.";
													} else if ($record['STVCLAS_standard'] == "Jr.Kg.") {
														$classStd = "Jr.Kg.";
													} else if ($record['STVCLAS_standard'] == "Nursery") {
														$classStd = "Nursery";
													} else {
														$classStd = "";
													}
													$last_paid_date = $record['last_paid'] != null || $record['last_paid'] != "" ? date('d M y', strtotime($record['last_paid'])) : "";
													$due_date_time = $record['due_date'] != null || $record['due_date'] != "" ? date('d M y', strtotime($record['due_date'])) : "";
													$fees = 0;
													if ($record['STVCLAS_standard'] == "Nursery" || $record['STVCLAS_standard'] == 'Jr.Kg.') {
														$fees = 7000;
													} elseif ($record['STVCLAS_standard'] == "Sr.Kg.") {
														$fees = 8000;
													} else {
														$fees = 10000;
													}
													$bal = $fees - ($record['exumtion'] + $record['total_fees_paid']);
													if ($bal == 10000) {
														$color = 'text-danger';
													} else if ($bal > 1000 && $bal < 10000) {
														$color = 'text-info';
													} else {
														$color = 'text-success';
													}
													?>
													<tr>
														<td style="display: none;">
															<?= $record['STVCLAS_standard'] ?? ''; ?>
														</td>
														<td style="display: none;">
															<?= $record['STVCLAS_class']; ?>
														</td>
														<td style="display: none;">
															<?= $classStd; ?>
														</td>

														<td class="add-remark"><?= $i ?></td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= "<span class='font-weight-bold'>" . $record['id'] . "</span>" ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= "<span class=''>" . $record['SPRIDEN_student_id'] ?>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $record['name'] . ' ( ' . $record['STVCLAS_standard'] . ' - ' . $record['STVCLAS_class'] . ' ) ' ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $record['SPRIDEN_gender']?>
														</td>

														<td>
															<?= $record['total_fees_of_class'] ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark <?= $labelOfPaid ?>">
															<?= $total_paid_with_exumption ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $record['exumtion'] ?>
														</td>
														

														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $bharna ?>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $record['rte_status'] ?>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $record['SPRIDEN_student_status'] ?>
														</td>
														<?php
														$mobile_num = $record['whatsapp'];
														$message_wha = "अस्सलामु अलैकुम पैरेंट्स,
   आपका बच्चा / बच्ची " . $record['name'] . " STD. " . $record['STVCLAS_standard'] . " " . $record['STVCLAS_class'] . " में पढाई करता / करती है. उसकी फीस आपने " . $record['total_fees_paid'] . " रुपये जमा की है. " . $bharna . " रूपये जमा करवाना बाकि है. इसलिए उसकी फीस स्कूल में आकर जमा करवा दीजिये ताकी उसकी पढाई में कोई दिक्कत ना हो।
   \n
   

   Assalamualaikum Parents,
   Your Son/Daughter " . $record['name'] . " studies in STD. " . $record['STVCLAS_standard'] . " " . $record['STVCLAS_class'] . ". Till now you have paid Fees Amount of Rs " . $record['total_fees_paid'] . ". Rs " . $bharna . " is yet to be paid. So please pay the remaining fees amount of your child to avoid inconvenience in his/her studies.";

														?>
														<td class="font-weight-bold text-success text-center"
															style="font-weight:900; font-size: 20px;">
															<?php if ($mobile_num != '' && $mobile_num != null) {
																$mobile_num = str_replace(" ", "", $mobile_num);
																?>
																<a target="_blank"
																	href="https://api.whatsapp.com/send?phone=+91<?= $mobile_num ?>&text=<?= $message_wha ?>">
																	<i class='bx bxl-whatsapp'></i>
																</a>
															<?php } ?>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $last_paid_date; ?>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $due_date_time; ?>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= !empty($record['next_date']) ? date('d M y', strtotime($record['next_date'])) : ''; ?>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark"><a
																href="tel:<?= $record['phone']; ?>">
																<?= $record['phone']; ?>
															</a>
														</td>
														<td data-id="<?= $record['id'] ?>" class="add-remark">
															<?= $record['note']; ?>
														</td>
													</tr>
													<?php $i++;
												} ?>
											</tbody>
											<tfoot>
												<tr>
													<th colspan="5">Total</th>
													<th>
														<?= number_format($total_fees_all) ?>
													</th>
													<th>
														<?= number_format($total_fees_paid_by) ?>
													</th>
													<th>
														<?= number_format($total_ex_val) ?>
													</th>
													<th>
														<?= number_format($total_fees_bal) ?>
													</th>
													<th colspan="9"></th>
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
		// $('#simpletable_kis').DataTable({
		// 	dom: 'Bfrtip',
		// 	"pageLength": 20,
		// 	"order": [
		// 		[0, "asc"],
		// 		[1, "asc"]
		// 	],
		// 	buttons: [
		// 		'excel', 'pdf', 'print'
		// 	]
		// });
		if ($.fn.DataTable.isDataTable('#table')) {
			$('#table').DataTable().destroy();
		}

		$('#table').DataTable({
			dom: 'Bfrtip',
			pageLength: 20,
			order: [
				[0, "asc"],
				[1, "asc"]
			],
			buttons: [
				'excel',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A4',
					exportOptions: {
						columns: [3, 0, 1, 5, 6, 7, 8, 10, 11, 14, 17]
					},
					customize: function (doc) {

						doc.defaultStyle.fontSize = 7;
						doc.styles.tableHeader.fontSize = 8;

						doc.content[1].table.widths = [
							'5%',
							'3%',
							'3%',
							'6%',
							'40%',
							'6%',
							'6%',
							'6%',
							'6%',
							'10%',
							'10%'
						];

						var body = doc.content[1].table.body;

						for (var i = 1; i < body.length; i++) {
							for (var j = 0; j < body[i].length; j++) {

								if (j === 4) {
									body[i][j].alignment = 'left';
								} else {
									body[i][j].alignment = 'center';
								}
							}
						}

						for (var k = 0; k < body[0].length; k++) {
							body[0][k].alignment = 'center';
						}

						doc.pageMargins = [10, 10, 10, 10];
					}
				},
				{
					extend: 'print',
					exportOptions: {
						columns: [3, 0, 1, 5, 6, 7, 8, 10, 11, 14, 17]
					}
				}
			]
		});

		$('#btn').click(function () {
			var filter_date = $('#due_date').val();
			var class_name = $('#class').val();
			var division = $('#division').val();
			let checkBox = $('#rte_status').val();
			let acdm_year = $('#acdm_year').val();
			var url = `<?= base_url('report/report_stats') ?>?action=search&due-date=${filter_date}&type=${checkBox}&class=${class_name}&division=${division}&acdm_year=${acdm_year}`;
			window.location = url;
		});

		$(document).on('click', '.add-remark', function () {
			var id = $(this).attr('data-id');
			window.location = "<?= base_url() ?>Report/student_report?student-id=" + id + "&type=stats";
		});
	</script>
</body>

</html>