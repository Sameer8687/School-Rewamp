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
							<h5 class="card-title">View Class</h5>
						</div>
						<div class="card-body p-2">
							<form action="<?php echo base_url(); ?>class" method="post">
								<div class="print-container">
									<div class="col-md-12">
										<div class="row mb-3">

											<!-- 1 -->
											<div class="col-md-3">
												<div class="row">
													<label for="" class="col-md-5">Standard <span
															class="float-right">:</span></label>

													<select required class="form-control form-control-sm col-md-7"
														name="class" id="class">
														<option selected disabled value="">Select Standard</option>
														<?php
														foreach ($classes as $class) {
															?>
															<option value="<?php echo $class['stvfees_class']; ?>">
																<?php echo $class['stvfees_class']; ?>
															</option>
														<?php } ?>
													</select>
												</div>
											</div>

											<!-- 2 -->
											<div class="col-md-3">
												<div class="row">
													<label for="" class="col-md-5">Division <span
															class="float-right">:</span></label>

													<select class="form-control form-control-sm col-md-7" name="divi"
														id="divi">
														<option selected value="">Select Division</option>
														<option value="A">A</option>
														<option value="B">B</option>
														<option value="C">C</option>
													</select>
												</div>
											</div>

											<!-- Academic Year -->
											<div class="col-md-3">
												<div class="row">

													<label class="col-md-5">
														Academic Year <span class="float-right">:</span>
													</label>

													<?php $selected_year = $this->session->userdata('academic_year'); ?>

													<select name="acdm_year" id="acdm_year"
														class="form-control form-control-sm col-md-7">

														<option value="">Select Year</option>

														<?php foreach ($academic_years as $acdm) { ?>

															<option value="<?= $acdm['stvacdm_id']; ?>"
																<?= ($selected_year == $acdm['stvacdm_id']) ? 'selected' : ''; ?>>

																<?= $acdm['stvacdm_year']; ?>

															</option>

														<?php } ?>

													</select>

												</div>
											</div>

											<!-- 3 -->
											<div class="col-md-3">

												<button type="submit" class="btn btn-sm btn-outline-secondary"
													id="findClassButton" style="border-radius: 50px;">View</button>
											</div>
										</div>
									</div>

									<div class="col-md-12">
										<div class="dt-responsive table-responsive margin-top-minus-10">
											<table id="example" data-custom="true"
												class="table table-striped table-bordered nowrap">
												<thead>
													<tr>
														<th>Sr.No.</th>
														<th>Roll No.</th>
														<th>ID</th>
														<th>Student Name</th>
														<th>Class</th>
														<th>Action</th>
													</tr>
												</thead>

												<tbody id="tbody">
													<?php if ($action != "no") {
														if ($dataforClass != null) {
															$i = 1;
															foreach ($dataforClass as $stu_data) { ?>
																<tr>
																	<td><?= $i; ?></td>
																	<td><?= $stu_data['STVCLAS_rollno']; ?></td>
																	<td><?= $stu_data['SPRIDEN_student_id']; ?></td>
																	<td data-student-id="<?= $stu_data['id']; ?>"
																		class="view-student-all-details-by-id">
																		<?= $stu_data['SPRIDEN_student_name'] . " " . $stu_data['SPRIDEN_father_name'] . " " . $stu_data['SPRIDEN_surname']; ?>
																	</td>

																	<td><?= $stu_data['STVCLAS_standard'] . " - " . $stu_data['STVCLAS_class']; ?>

																	</td>

																	<style>
																		.td-icon i {
																			font-size: 16px;
																		}
																	</style>

																	<td class="td-icon" style="margin: auto;">
																		<i class="fas fa-phone-volume call"
																			style="margin-right: 10px; color: #2196F3;"></i>

																		<i class="fab fa-whatsapp wha call "
																			style="color: #25D366;"></i>
																	</td>
																</tr>
																<?php $i++;
															}
														}
													} ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$(document).ready(function () {

			var action = '<?php echo $action; ?>';

			if (action != "no") {

				$('#class').val('<?php echo $newStd; ?>');

				$('#divi').val('<?php echo $newDiv; ?>');

				$('#acdm_year').val('<?php echo $newAcademicYear; ?>');
			}

			$('#example').DataTable({
				pageLength: 50,
				dom: 'Bfrtip',
				buttons: [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
			});
		});
	</script>
</body>

</html>