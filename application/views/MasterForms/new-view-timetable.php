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
							<h5 class="card-title">View Time Table</h5>
						</div>
					
					<div class="card-body p-2">
						<div class="print-container">


							<div class="col-md-12">
								<div class="dt-responsive table-responsive margin-top-minus-10">
									<table id="simpletable" data-custom="true"
										class="table table-striped table-bordered nowrap">
										<thead>
											<tr>
												<th>Sr.No.</th>
												<th>Standard</th>
												<th>Division</th>
												<th>Action</th>
											</tr>
										</thead>

										<tbody id="tbody">
											<?php $i = 1;
											foreach ($classes as $class) { ?>
												<tr>
													<td><?= $i; ?></td>
													<td data-div="<?= $class['SPATESD_div']; ?>"
														data-std="<?= $class['stvfees_class']; ?>" data-toggle="modal"
														data-target="#exampleModal" class="display-standard-timetable">
														<?= $class['stvfees_class']; ?></td>
													<td data-div="<?= $class['SPATESD_div']; ?>"
														data-std="<?= $class['stvfees_class']; ?>" data-toggle="modal"
														data-target="#exampleModal" class="display-standard-timetable">
														<?= $class['SPATESD_div']; ?></td>
													<td>
														<a href="#" data-div="<?= $class['SPATESD_div']; ?>"
															data-std="<?= $class['stvfees_class']; ?>"
															class="text-info edit-timetable" data-toggle="modal"
															data-target="#exampleModal"> <i class="fas fa-edit"></i> </a>
														<a href="#" class="text-danger"> <i class="fas fa-trash-alt"></i>
														</a>
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

	<!-- View Modal -->
	<div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
		aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-center pb-2 pt-2 bg-info">
					<h5 class="modal-title text-white text-center" id="exampleModalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body p-0">
					<div class="table-responsive">
						<table class="table table-bordered" id="time-table-table">
							<thead>
								<tr>
									<th>Sr.No</th>
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

							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer p-2 pr-3">
					<button data-dismiss="modal" aria-label="Close" class="btn btn-info btn-sm">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$(document).on('click', '.display-standard-timetable', function () {
			var class_name = $(this).attr("data-std");
			var div_name = $(this).attr("data-div");
			$.ajax({
				url: "<?= base_url(); ?>index.php/Timetable/getTimetableByStd",
				method: "POST",
				dataType: "JSON",
				data: {
					class_name: class_name,
					div_name: div_name
				},
				success: function (data) {
					if (data != "fail") {
						var html = '';
						if (data == "") {
							html += '<tr><td colspan="8" class="text-center">Timetable Not Generated for ' + class_name + ' - ' + div_name + '</td></tr>';
						} else {

							for (var count = 0; count < data.length; count++) {
								var alert_class = '';
								var newCount = count + 1;
								if (data[count].stvttbl_monday == "Recess") {
									alert_class = 'alert-info';
								} else {
									alert_class = '';
								}
								html += '<tr>';
								html += '<td>' + newCount + '</td>';
								html += '<td class="' + alert_class + '">' + data[count].stvttbl_time + '</td>';
								if (data[count].stvttbl_monday == "Recess") {
									html += '<td colspan="6" class="text-center font-weight-bold alert-info">' + data[count].stvttbl_monday + '</td>';
								} else {
									html += '<td editable>' + data[count].stvttbl_monday + '</td>';
									html += '<td editable>' + data[count].stvttbl_tuesday + '</td>';
									html += '<td editable>' + data[count].stvttbl_wednesday + '</td>';
									html += '<td editable>' + data[count].stvttbl_thurday + '</td>';
									html += '<td editable>' + data[count].stvttbl_friday + '</td>';
									html += '<td editable>' + data[count].stvttbl_saturday + '</td>';
								}
								html += '</tr>';
							}
						}
						$('#exampleModalLabel').html("Olive English School - Time Table : " + class_name + " - " + div_name);
						$('#timetable-table').html(html);
						$('#time-table-table').slideDown();

					}
				}
			});
		});

		$(document).on('click', '.edit-timetable', function () {
			var class_name = $(this).attr("data-std");
			var div_name = $(this).attr("data-div");
			$.ajax({
				url: "<?= base_url(); ?>index.php/Timetable/getTimetableByStd",
				method: "POST",
				dataType: "JSON",
				data: {
					class_name: class_name,
					div_name: div_name
				},
				success: function (data) {
					if (data != "fail") {
						var html = '';
						if (data == "") {
							html += '<tr><td colspan="8" class="text-center">Timetable Not Generated for ' + class_name + ' Standard</td></tr>';
						} else {

							for (var count = 0; count < data.length; count++) {
								var alert_class = '';
								var newCount = count + 1;
								if (data[count].stvttbl_monday == "Recess") {
									alert_class = 'alert-info';
								} else {
									alert_class = '';
								}
								html += '<tr>';
								html += '<td>' + newCount + '</td>';
								html += '<td class="' + alert_class + '">' + data[count].stvttbl_time + '</td>';
								if (data[count].stvttbl_monday == "Recess") {
									html += '<td colspan="6" class="text-center font-weight-bold alert-info">' + data[count].stvttbl_monday + '</td>';
								} else {
									html += '<td class="updatetimetable_data" data-row_id="' + data[count].stvttbl_id + '" data-column_name="stvttbl_monday">' + data[count].stvttbl_monday + '</td>';
									html += '<td class="updatetimetable_data" data-row_id="' + data[count].stvttbl_id + '" data-column_name="stvttbl_tuesday">' + data[count].stvttbl_tuesday + '</td>';
									html += '<td class="updatetimetable_data" data-row_id="' + data[count].stvttbl_id + '" data-column_name="stvttbl_wednesday">' + data[count].stvttbl_wednesday + '</td>';
									html += '<td class="updatetimetable_data" data-row_id="' + data[count].stvttbl_id + '" data-column_name="stvttbl_thurday">' + data[count].stvttbl_thurday + '</td>';
									html += '<td class="updatetimetable_data" data-row_id="' + data[count].stvttbl_id + '" data-column_name="stvttbl_friday">' + data[count].stvttbl_friday + '</td>';
									html += '<td class="updatetimetable_data" data-row_id="' + data[count].stvttbl_id + '" data-column_name="stvttbl_saturday">' + data[count].stvttbl_saturday + '</td>';
								}
								html += '</tr>';
							}
						}
						$('#exampleModalLabel').html("Olive English School - Time Table : " + class_name);
						$('#timetable-table').html(html);
						$('#time-table-table').slideDown();
						$('#time-table-table tr td').attr("contentEditable", "true");
					}
				}
			});
		});

		$(document).on('blur', '.updatetimetable_data', function () {
			var id = $(this).data('row_id');
			var table_colomn = $(this).data('column_name');
			var value = $(this).text();

			if (confirm("Do You Want to Update ?")) {
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Timetable/updt_timetable_cell",
					method: "POST",
					data: {
						id: id,
						table_column: table_colomn,
						value: value
					},
					success: function (data) {
						if (data != "fail") {
							alert("Refresh Page !!!");
							//window.location.reload();
						}
					}
				});
			} else {
			}
		});
	</script>
</body>

</html>