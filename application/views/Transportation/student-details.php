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
							<h5 class="card-title">Student Vehicle Details</h5>
						</div>
						<div class="card-body p-2">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<!-- 1 -->
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Class <span
														class="float-right">:</span></label>

												<select name="Class" id="Class"
													class="form-control form-control-sm col-md-7">
													<option>Select</option>
													<?php
													foreach ($classes as $class) {
														?>
														<option <?= $class['stvfees_class'] == $class_filter ? 'selected' : ''; ?> value="<?= $class['stvfees_class']; ?>">
															<?= $class['stvfees_class']; ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>

										<!-- 2 -->
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Division <span
														class="float-right">:</span></label>

												<select name="DIVISION" id="DIVISION"
													class="form-control form-control-sm col-md-7">
													<option value="">Select</option>
													<option <?= $division_filter == 'A' ? 'selected' : '' ?> value="A">A
													</option>
													<option <?= $division_filter == 'B' ? 'selected' : '' ?> value="B">B
													</option>
													<option <?= $division_filter == 'C' ? 'selected' : '' ?> value="C">C
													</option>
												</select>
											</div>
										</div>

										<!-- 3 -->
										<div class="col-md-4">

											<button id="find_students" type="button"
												class="btn btn-sm btn-outline-secondary"
												value="">View </button>

											<button type="submit" onclick="submit()"
												class="btn btn-sm btn-outline-primary"
												>Save</button>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="table-gr" data-custom="true"
											class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th>#</th>
													<th>Student ID</th>
													<th>Student Name</th>
													<th>Driver</th>
												</tr>
											</thead>

											<tbody id="tbody">
												<form id="Editmaster"
													action="<?= base_url('index.php/Transportation/Save') ?>"
													method="POST">
													<input type="hidden" name="max_rows" id="max_rows"
														value="<?= count($rows) ?>" />
													<?php $i = 1;
													foreach ($rows as $row): ?>
														<tr>
															<td>
																<?= $i ?>
																<input type="hidden"
																	value="<?= $row['detail_id'] == '' || $row['detail_id'] == null ? 0 : $row['detail_id'] ?>"
																	name="detail_id<?= $i ?>" id="detail_id<?= $i ?>">
																<input type="hidden" name="spriden_id<?= $i ?>"
																	id="spriden_id<?= $i ?>" value="<?= $row['id'] ?>">
															</td>
															<td>
																<?= $row['SPRIDEN_student_id'] ?>
															</td>
															<td>
																<?= $row['name'] ?>
															</td>
															<td style="padding: 0;">
																<select name="driver_id<?= $i ?>" id="driver_id<?= $i ?>"
																	class="form-control form-control-sm blank"
																	value="<?= $row['Driver_name'] ?>">
																	<option>Select </option>
																	<option <?= $row['driver_id'] == '0' ? 'selected' : '' ?>
																		value="0">Parents</option>
																	<?php foreach ($Driver_name as $d_name): ?>
																		<option <?= $row['driver_id'] == $d_name['sr_no'] ? 'selected' : '' ?> value="<?= $d_name['sr_no'] ?>">
																			<?= $d_name['Driver_name'] ?>
																		</option>
																	<?php endforeach; ?>
																</select>
															</td>
														</tr>
														<?php ++$i ?>
													<?php endforeach; ?>
												</form>
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
		$("#table-gr").DataTable({
			"pageLength": 25,
			dom: "Bfrtip",
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
	</script>

	<script>
		$(document).on("submit", "#submit_form", function (e) {
			return true;
		})
	</script>

	<script>
		document.getElementById("find_students").addEventListener('click',
			async () => {
				var html = '';
				let Class = document.getElementById("Class")
					.value != '' ? document.getElementById(
						"Class")
					.value : 0;
				let Division = document.getElementById("DIVISION")
					.value != '' ? document.getElementById("DIVISION")
					.value : 0;
				let base_url = '<?= base_url(); ?>';
				window.location =
					`${base_url}index.php/Transportation/student_details/${Class}/${Division}`;
			});
	</script>
</body>

</html>