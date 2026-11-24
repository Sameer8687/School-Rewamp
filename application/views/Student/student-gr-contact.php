<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Student GR, Class and Contact Details</title>
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

		#multi-colum-dts {
			border: none !important;
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
		<form id="form_data_data" enctype="multipart/form-data" method="post">
			<div class="pcoded-content">
				<!-- [ Main Content ] start -->
				<div class="row" style="margin-top:-40px;">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group row">
									<label for="" class="col-5">Class <span class="float-right">:</span></label>
									<select name="std" id="std" class="form-control form-control-sm col-md-7">
										<option value="">Select Class</option>
										<?php foreach ($classes as $clas) { ?>
											<option <?= $clas['stvfees_class'] == $selected_class ? 'selected' : '' ?>
												value="<?= $clas['stvfees_class'] ?>"><?= $clas['stvfees_class'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group row">
									<label for="" class="col-5">Division <span class="float-right">:</span></label>
									<select name="div" id="div" class="form-control form-control-sm col-md-7">
										<option value="">Select Division</option>
										<option <?= $selected_div == 'A' ? 'selected' : '' ?> value="A">A</option>
										<option <?= $selected_div == 'B' ? 'selected' : '' ?> value="B">B</option>
										<option <?= $selected_div == 'C' ? 'selected' : '' ?> value="C">C</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group row">
									<label for="" class="col-5">Year <span class="float-right">:</span></label>
									<select name="year" id="year" class="form-control form-control-sm col-md-7">
										<option value="">Select Year</option>
										<?php foreach ($academic_years as $year): ?>
											<option <?= ($year['stvacdm_id'] == $selected_year) ? 'selected' : '' ?>
												value="<?= $year['stvacdm_id']; ?>">
												<?= $year['stvacdm_year']; ?>
											</option><?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<button type="button" onclick="viewDetails()" class="btn btn-sm btn-secondary">View
										Details</button>
								</div>
							</div>
							<div class="col-md-3">
								<!-- showing male and female counts -->
								<?php
								$male_count = 0;
								$female_count = 0;

								foreach ($all_records as $record) {

									$gender = strtoupper(trim($record['SPRIDEN_gender']));

									if ($gender == 'M' || $gender == 'MALE') {
										$male_count++;
									}

									if ($gender == 'F' || $gender == 'FEMALE') {
										$female_count++;
									}
								}
								?>
								<div class="alert alert-primary p-2 mb-0">
									<strong>Total :</strong> <?= count($all_records); ?>
									&nbsp; | &nbsp;
									<strong>Male :</strong> <?= $male_count; ?>
									&nbsp; | &nbsp;
									<strong>Female :</strong> <?= $female_count; ?>
								</div>

							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<table class="table table-sm table-bordered" id="table-gr">
									<thead>
										<tr>
											<th>#</th>
											<th>Reg No.</th>
											<th>Gender</th>
											<th>Student Name</th>
											<th>Mother Name</th>
											<th>DOB</th>
											<th>Class</th>
											<th>GR Number</th>
											<th>Aadhar No</th>
											<th>Mobile Number</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;
										foreach ($all_records as $record) {
											?>
											<tr>
												<td><?= $i; ?></td>
												<td>'<?= str_replace("ID:", '', $record['SPRIDEN_reg_number']); ?>'</td>
												<td><?= $record['name']; ?></td>
												<td><?= $record['SPRIDEN_gender']; ?></td>
												<td><?= $record['SPRIDEN_mother_name']; ?></td>
												<td><?= $record['SPRIDEN_birthdate'] != '0000-00-00' ? date('d-M-Y', strtotime($record['SPRIDEN_birthdate'])) : ''; ?>
												</td>
												<td><?= $record['STVCLAS_standard'] . ' - ' . $record['STVCLAS_class']; ?>
												</td>
												<td><?= $record['spriden_gr_number']; ?></td>
												<td><?= $record['SPRIDEN_aadhar_no']; ?></td>
												<td><?= $record['mobile']; ?></td>
											</tr>
											<?php $i++;
										} ?>
									</tbody>
								</table>
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

		function viewDetails() {
			let std = document.getElementById("std").value;
			let div = document.getElementById("div").value;
			let year = document.getElementById("year").value;
			if (std != '') {
				window.location = `<?= base_url() ?>Student/student_gr_contact/${std}/${div}/${year}`;
			}
		}
	</script>
</body>

</html>