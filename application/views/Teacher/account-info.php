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
							<h5 class="card-title">Account Info</h5>
						</div>
						<div class="card-body">
							<div class="print-container">

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" class="table table-striped table-bordered nowrap">
											<thead>
												<tr class="text-center">
													<th>Sr.No.</th>
													<th>ID</th>
													<th class="text-left">Name</th>
													<th>Status</th>
												</tr>
											</thead>

											<tbody id="tbody">
												<?php $i = 1;
												$flag = 0;
												foreach ($all_teachers as $teacher) {
													?>
													<tr class="text-center">
														<td class="view-record" data-id="<?= $teacher['id']; ?>"><?= $i; ?>
														</td>
														<td class="view-record" data-id="<?= $teacher['id']; ?>">
															<?= $teacher['SPRIDEN_student_id']; ?>
														</td>
														<td class="view-record text-left" data-id="<?= $teacher['id']; ?>">
															<?= $teacher['SPRIDEN_surname'] . ' ' . $teacher['SPRIDEN_student_name'] . ' ' . $teacher['SPRIDEN_father_name']; ?>
														</td>
														<td>
															<select name="change_status<?= $i; ?>"
																class="change-spriden-status" id="change_status<?= $i; ?>"
																data-id="<?= $i ?>" class="form-control form-control-sm"
																data-student-id="<?= $teacher['id'] ?>">
																<option <?= $teacher['spriden_student_status'] == 'A' ? 'selected' : ''; ?> value="A">Active</option>
																<option <?= $teacher['spriden_student_status'] == 'I' ? 'selected' : ''; ?> value="I">Inactive</option>
															</select>
														</td>
													</tr>

													<?php $i++;
												} ?>
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
		$(document).on('change', '.change-spriden-status', function () {
			var data_id = $(this).attr("data-id");
			var student_id = $(this).attr("data-student-id");
			var status = $(this).val();
			$.ajax({
				url: "<?= base_url() ?>Welcome/updateStudentStatus?student-id=" + student_id + "&status=" + status, // Updated 2024 Aug 02 - kamran Ansari
				success: function (res) {
					console.log(res);
				}
			})
		})
	</script>

</html>