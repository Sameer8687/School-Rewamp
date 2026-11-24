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
							<h5 class="card-title">Fees Alert Update</h5>
						</div>
						<div class="card-body p-2">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">

										<!-- 1 -->
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">Year <span
														class="float-right">:</span></label>

												<select name="year" id="year"
													class="form-control form-control-sm col-md-7">
													<option value="0">Select Class</option>
													<?php foreach ($academic_years as $year) { ?>
														<option <?= $academic_filter == $year['stvacdm_id'] ? 'selected' : '' ?> value="<?= $year['stvacdm_id'] ?>">
															<?= $year['stvacdm_year'] ?>
														</option>
													<?php } ?>
												</select>

											</div>
										</div>

										<!-- 2 -->
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">Class <span
														class="float-right">:</span></label>

												<select name="classLIst" id="classLIst"
													class="form-control form-control-sm col-md-7">
													<option value="0">Select Class</option>
													<?php foreach ($classes as $clas) { ?>
														<option <?= $selected_class == $clas['stvfees_class'] ? 'selected' : '' ?> value="<?= $clas['stvfees_class'] ?>">
															<?= $clas['stvfees_class'] ?>
														</option>
													<?php } ?>
												</select>

											</div>
										</div>

										<!-- 3 -->
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">Div <span
														class="float-right">:</span></label>

												<select name="filter_div" id="filter_div"
													class="form-control form-control-sm col-md-7">
													<option value="0">Select Division</option>
													<option <?= $div_filter == 'A' ? 'selected' : '' ?> value="A">A
													</option>
													<option <?= $div_filter == 'B' ? 'selected' : '' ?> value="B">B
													</option>
													<option <?= $div_filter == 'C' ? 'selected' : '' ?> value="C">C
													</option>
												</select>

											</div>
										</div>

										<!-- 4 -->
										<div class="col-md-2">
											<div class="row">
												<label for="" class="col-md-5">Amount <span
														class="float-right">:</span></label>

												<input type="text" name="amount_filter" id="amount_filter"
													class="form-control form-control-sm col-md-7"
													placeholder="Enter Amount to filter"
													value="<?= $selected_amount ?>">

											</div>
										</div>

										<!-- 5 -->
										<div class="col-md-2">

											<button id="filter-btn" type="button"
												class="btn btn-sm btn-outline-secondary">Search</button>

										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" data-custom="true"
											class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th class="text-capitalize" style="display: none;">Class Number</th>
													<th class="text-capitalize">ID</th>
													<th class="text-capitalize">Student Name</th>
													<th class="text-capitalize">Class</th>
													<th class="text-capitalize d-none">Class Hiden</th>
													<th class="text-capitalize">Fees Paid </th>
													<th class="text-capitalize">Exemption </th>
													<th class="text-capitalize">Balance </th>
													<th class="text-capitalize">Due Date </th>
													<th class="text-capitalize">Phone Number</th>
													<th class="text-capitalize">Send</th>
													<th class="text-capitalize">Remark</th>
												</tr>
											</thead>

											<tbody id="tbody">
												<?php $i = 1;
												$total_remainnig = 0;
												foreach ($all_records as $record) {

													if ($record['fees_paid'] == null || $record['fees_paid'] == '') {
														$vbal = 0;
													} else {
														$vbal = $record['fees_paid'];
													}

													$fee_with_ex = $record['exumption'] + $vbal;
													$exumption = $record['exumption'];
													$due_date_time = $record['due_date'] != null || $record['due_date'] != "" ? date('d M Y', strtotime($record['due_date'])) : "";

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
													}

													$mobile_num = $record['whatsapp'];
													$message_wha = "अस्सलामु अलैकुम पैरेंट्स,
															आपका बच्चा " . $record['name'] . " STD. " . $record['class'] . " में पढाई करता है. उसकी फीस Rs. " . $record['balance_fees'] . " जमा करवाना बाकि है.  आपकी पिछली किश्त बाकि है, इसलिए जल्द से जल्द आकर फीस जमा करवा दीजिये ताकी उसकी पढाई में कोई दिक्कत ना हो।
															\n
															Assalamualaikum Parents,
															Your child " . $record['name'] . " studying in STD. " . $record['class'] . " has pending fees of Rs. " . $record['balance_fees'] . ". Your previous installment also pending. Kindly pay the fees as soon as possible to avoid inconvenience in his/her studies."; ?>

													<tr>
														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="display: none; font-weight: bold !important">
															<?= $classStd; ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important">
															<?= $record['SPRIDEN_student_id'] ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important"><?= $record['name'] ?></td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important">
															<?= $record['STVCLAS_standard'] ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark d-none"
															style="font-weight: bold !important"><?= $classStd ?></td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important"><?= $record['fees_paid'] ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important"><?= $record['exumption'] ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important">
															<?= $record['balance_fees'] ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important"><?= $due_date_time; ?>
														</td>

														<td style="font-weight: bold !important"><a
																href="tel:<?= $record['phone'] ?>"
																class="Blondie"><?= $record['phone'] ?></a></td>
														<td style="font-weight: bold !important">

															<?php if ($mobile_num != '' && $mobile_num != null) {
																$mobile_num = str_replace(" ", "", $mobile_num);
																?>
																<a class="text-primary" target="_blank"
																	href="https://api.whatsapp.com/send?phone=+91<?= $mobile_num ?>&text=<?= $message_wha ?>">
																	<i class='bx bxl-whatsapp'></i>
																</a>
															<?php } ?>
														</td>

														<td data-id="<?= $record['id'] ?>" class="add-remark"
															style="font-weight: bold !important"><?= $record['note'] ?></td>
													</tr>
												<?php }
												$i++; ?>
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
		$('#due_date').change(function () {

			var filter_date = $(this).val();

			var url = "<?= base_url('report/report_stats') ?>?action=search&due-date=" + filter_date;

			window.location = url;

		});

		$(document).on('click', '.add-remark', function () {

			var id = $(this).attr('data-id');

			var url = '<?= base_url() ?>report/student_report?student-id=' + id + "&type=alert";

			window.location = url;

		});
		$('#filter-btn').click(function () {
			let class_name = $('#classLIst').val();
			let amount = $('#amount_filter').val();
			let due_date = $('#filter_div').val();
			let year = $('#year').val();

			if (class_name == 0) {
				alert("Select Class");
				return false;
			}
			if (amount == 0 || amount == '') {
				alert("Enter Amount > 0");
				return false;
			}
			if (year == 0) {
				alert("Select Academic Year");
				return false;
			}

			// Fixed the URL - removed the undefined $year variable
			window.location = `<?= base_url() ?>report/report/${class_name}/${amount}/${due_date}/${year}`;
		});
	</script>
</body>

</html>