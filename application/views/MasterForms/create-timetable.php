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

	<?php include 'application/views/sidebar.php'; ?>
	<?php include 'application/views/topNav.php'; ?>
	<div class="pc-container">
		<div class="pcoded-content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="col-md-6">
								<h5 class="card-title">Create Time Table</h5>
							</div>
							<div class="col-md-6">
								<button onclick="showTableData()" id="generate-timetable"
								class="btn btn-sm btn-outline-secondary float-right text-end"> <i
								class="fas fa-calendar-alt"></i> Create Timetable</button>
							
							</div>

						</div>

						<div class="card-body p-2">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">

										<!-- 1 -->
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Standard <span
														class="float-right">:</span></label>

												<select class="form-control form-control-sm col-md-7" name="class"
													id="class">
													<option selected disabled value="">Select Class</option>
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
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Division <span
														class="float-right">:</span></label>

												<select class="form-control form-control-sm col-md-7" name="division"
													id="division">
													<option selected disabled value="">Select Division</option>
													<option value="A">A</option>
													<option value="B">B</option>
													<option value="C">C</option>

												</select>

											</div>
										</div>

									</div>

								</div>
								<div class="col-md-12 text-center">
									<p id="subject-shower" class="text-primary"></p>
								</div>
								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" data-custom="true"
											class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
													<th>Time</th>
													<th>Monday</th>
													<th>Tuesday</th>
													<th>Wednesday</th>
													<th>Thursday</th>
													<th>Friday</th>
													<th>Saturday</th>
												</tr>
											</thead>

											<tbody id="timetable-table">
												<?php foreach ($all_times as $all_tm) { ?>
													<tr>
														<style>
															#timetable-table td {
																padding: 5px;
															}

															#timetable-table td .input-taken-cell {
																border: none;
															}

															.recess-title {
																word-spacing: 1cm;
																font-weight: bold;
																font-size: 16px;
															}
														</style>
														<?php
														if ($all_tm['stvtbtm_status'] == "Recess") {
															$bg_color_td = "alert-info";
														} else {
															$bg_color_td = "";
														}
														?>

														<td class="<?= $bg_color_td; ?>">
															<?= date("g:i A", strtotime($all_tm['stvtbtm_start_time'])); ?>
														</td>
														<!-- <td><input type="text" class="form-control form-control-sm input-taken-cell"></td>
												<td><input type="text" class="form-control form-control-sm input-taken-cell"></td>
												<td><input type="text" class="form-control form-control-sm input-taken-cell"></td>
												<td><input type="text" class="form-control form-control-sm input-taken-cell"></td>
												<td><input type="text" class="form-control form-control-sm input-taken-cell"></td>
												<td><input type="text" class="form-control form-control-sm input-taken-cell"></td> -->
														<?php
														if ($all_tm['stvtbtm_status'] == "Recess") {
															?>
															<td class="text-center alert-info recess-title" colspan="6">Recess
															</td>
														<?php } else { ?>
															<td contenteditable="true"></td>
															<td contenteditable="true"></td>
															<td contenteditable="true"></td>
															<td contenteditable="true"></td>
															<td contenteditable="true"></td>
															<td contenteditable="true"></td>
														<?php } ?>

													</tr>
												<?php } ?>
											</tbody>
										</table>
										<p id="info"></p>
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
		$('#class').change(function () {
			var class_name = $('#class').val();
			$.ajax({
				url: "<?= base_url(); ?>index.php/Teacher/getSubjectByStd",
				method: "POST",
				dataType: "JSON",
				data: {
					class_name: class_name
				},
				success: function (data) {
					if (data != "fail") {
						var html = '';
						for (var count = 0; count < data.length; count++) {
							if (count == (data.length - 1)) {
								html += data[count].stvsubj_subject;
							} else {
								html += data[count].stvsubj_subject + ', ';
							}
						}

						$('#subject-shower').html("<span style='font-weight: 600;'>Subjects :</span> " + html);
					}
				}
			});
		});
		$('.input-taken-cell').keyup(function () {

		});
	</script>
	<script>
		function showTableData() {
			var rows = '<?php echo count($all_times); ?>';
			const myArray1 = [];
			var myTab = document.getElementById('time-table-table');
			var std = $('#class').val();
			var div = $('#division').val();

			if (std == null && div == null) {
				alert("Select Standard & Division");
				return false;
			} else if (std != null && div == null) {
				alert("Select Division");
				return false;
			} else if (std == null && div != null) {
				alert("Select Standard");
				return false;
			} else {
				var tRows = [];
				tab = document.getElementById('timetable-table');

				for (var r = 0; r < tab.rows.length; r++) {
					var tRow = []; // start new row array
					for (var c = 0; c < tab.rows[r].cells.length; c++) {
						tRow[c] = tab.rows[r].cells[c].innerHTML;
					}
					tRows.push(tRow);
				}

				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Timetable/create_timetable",
					data: {
						timetable: tRows,
						std: std,
						div: div
					},
					method: "POST",
					beforeSend: function () {
						$('#generate-timetable').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
					},
					success: function (data) {
						$('#generate-timetable').html('<i class="fas fa-calendar-alt"></i> Create Timetable');
						if (data != "fail") {
							alert("Timetable Created Successfully !!!");
							window.location.reload();
						} else {
							swal("Failed to Create Time Table");
						}
					}
				});
			}
		}
	</script>
</body>

</html>