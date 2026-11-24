<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Student Attendance</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />

	<!---Include CSS Files-->
	<?php include 'application/views/cssPages.php'; ?>

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
		<form action="" id="classCreateForm" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->

				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->

				<div class="row margin-top-minus-40  mb-2">
					<div class="col-md-7">
						<!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
						<!-- <button class="btn btn-secondary btn-sm" type="submit" id="createClassButton"> <i class="fas fa-clipboard-list"></i> <span id="dynamicLabelAddButton">Mark Attendance</span></button> -->

					</div>
				</div>
				<div class="row" id="alertRow">
					<div class="col-md-12">
						<?php
						$message = $this->session->userdata('message');
						$alert_class = $this->session->userdata('alert_class');
						if ($message != "") {
						?>
							<div class="alert <?= $alert_class; ?>">
								<?= $message; ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-head row">
								<div class="col-md-9 pt-2 pl-4">
									<h5>Student Attendance </h5>
								</div>
								<div class="col-md-3 pt-2 pr-4">
									<button class="btn btn-secondary btn-sm float-right" type="submit" id="createClassButton"> <i class="fas fa-clipboard-list"></i> <span id="dynamicLabelAddButton">Mark Attendance</span></button>
								</div>
							</div>
							<div class="card-body">

								<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Take Attendance</a>
									</li>
									<li class="nav-item">
										<a class="nav-link " id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">View Class Attendance</a>
									</li>
									<li class="nav-item">
										<a class="nav-link " id="student-tab" data-toggle="tab" href="#profile1" role="tab" aria-controls="profile" aria-selected="false">View Student Attendance</a>
									</li>
									<li class="nav-item">
										<a class="nav-link " id="student-tab" data-toggle="tab" href="#profile2" role="tab" aria-controls="profile" aria-selected="false">View All Class Attendance</a>
									</li>
								</ul>
								<style>
									label {
										text-align: right;
									}

									.no-margin {
										margin-top: -10px;
									}
								</style>
								<div class="tab-content booksTabContents" id="myTabContent">
									<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
										<div class="row justify-content-center">
											<div class="col-md-11">
												<div class="row">
													<div class="col-md-3">
														<div class="form-group row">
															<label for="" class="col-md-5">Date:</label>
															<input type="date" class="form-control form-control-sm col-md-7" id="attendancedate" value="<?= date('Y-m-d'); ?>">
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group row">
															<label for="" class="col-md-5">Standard:</label>
															<select required class="form-control form-control-sm col-md-7" name="class" id="class">
																<option selected disabled value="">Select Class</option>
																<?php
																foreach ($classes as $class) {
																?>
																	<option value="<?php echo $class['stvfees_class']; ?>"><?php echo $class['stvfees_class']; ?></option>
																<?php } ?>

															</select>
														</div>
													</div>
													<div class="col-md-3">
														<div class="form-group row">
															<label for="" class="col-md-5">Division:</label>
															<select required class="form-control form-control-sm col-md-7" name="division" id="division">
																<option selected disabled value="">Select Division</option>
																<option value="A">A</option>
																<option value="B">B</option>
																<option value="C">C</option>
															</select>
														</div>
													</div>
													<div class="col-md-3">
														<button type="button" id="findStudentsForAtt" class="btn btn-secondary btn-sm" style="border-radius: 30px;">View Students</button>
													</div>
												</div>
											</div>
											<div class="col-md-11">
												<div class="table-responsive">
													<style>
														.table thead tr th {
															padding: 5px;
															font-size: 12px;
														}

														.table tbody tr td {
															padding: 5px;
															font-size: 12px;
														}
													</style>
													<table class="table table-bordered" id="studentTable">
														<thead>
															<tr>
																<th>Sr.No</th>
																<th>Roll No.</th>
																<th>Student Name</th>
																<th>
																	<input type="checkbox" id="selectAll" value="allPresent"> P -All
																	<input type="checkbox" id="deselectAll" value="allAbsent"> A -All
																</th>
															</tr>
														</thead>
														<tbody id="tbody">
															<tr>
																<td colspan="5" class="text-center">No Data Available</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
										<div class="row ">
											<div class="col-md-4">
												<div class="form-group row">
													<label for="" class="col-md-5">Standard:</label>
													<select class="form-control form-control-sm col-md-7" name="class_find" id="class_find">
														<option selected disabled value="">Select Class</option>
														<?php
														foreach ($classes as $class) {
														?>
															<option value="<?php echo $class['stvfees_class']; ?>"><?php echo $class['stvfees_class']; ?></option>
														<?php } ?>

													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row">
													<label for="" class="col-md-5">Division:</label>
													<select class="form-control form-control-sm col-md-7" name="division_find" id="division_find">
														<option selected disabled value="">Select Division</option>
														<option value="A">A</option>
														<option value="B">B</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<button class="btn btn-secondary btn-sm" id="findStudentAttendanceButton" type="button">View Attendance</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<style>
													.table-responsive-hidden {
														display: none;
														overflow-y: auto;
													}
												</style>
												<div class="table-responsive table-responsive-hidden">
													<table class="table">
														<thead>
															<tr>
																<th>#</th>
																<th>Student Name</th>
																<th>Present Days</th>
																<th>Absent Days</th>
																<th>Total Days</th>
															</tr>
														</thead>
														<tbody id="attendance-table-body-student">

														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade " id="profile1" role="tabpanel" aria-labelledby="profile-tab">
										<div class="row ">
											<div class="col-md-4">
												<div class="form-group row">
													<label for="" class="col-md-5">Standard:</label>
													<select class="form-control form-control-sm col-md-7" name="class_find_student" id="class_find_student">
														<option selected disabled value="">Select Class</option>
														<?php
														foreach ($classes as $class) {
														?>
															<option value="<?php echo $class['stvfees_class']; ?>"><?php echo $class['stvfees_class']; ?></option>
														<?php } ?>

													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group row">
													<label for="" class="col-md-5">Division:</label>
													<select class="form-control form-control-sm col-md-7" name="division_find_student" id="division_find_student">
														<option selected disabled value="">Select Division</option>
														<option value="A">A</option>
														<option value="B">B</option>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<button class="btn btn-secondary btn-sm" id="findStudentAttendanceButtonByName" type="button">View Students</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<style>
													.table-responsive {
														overflow-y: auto;
													}

													.table-responsive tr td:hover {
														cursor: pointer;
													}
												</style>
												<div class="table-responsive">
													<table class="table">
														<thead>
															<tr>
																<th>#</th>
																<th>Student Name</th>
																<th>Student ID</th>
																<th>Class</th>

															</tr>
														</thead>
														<tbody id="show-data-student-name">

														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade " id="profile2" role="tabpanel" aria-labelledby="profile-tab">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group row">
													<label for="" class="col-md-5">Date:</label>
													<input type="date" onchange="handler(event);" class="form-control form-control-sm col-md-7" id="date-for-all-std">
												</div>
											</div>
											<div class="col-md-12">
												<div class="table-responsive">
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>#</th>
																<th>Class</th>
																<th>Present</th>
																<th>Absent</th>
																<th>Total</th>
															</tr>
														</thead>
														<style>
															#display-total-std-total tr td {
																font-weight: 600;
															}
														</style>
														<tbody id="display-total-std-total">

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


			</div>
		</form>
		<!-- [ Main Content ] end -->
	</div>



	</div>
	<!-- [ Main Content ] end -->
	</div>


	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<style>
					.modal-title {
						font-size: 14px;
						color: #fff;
					}
				</style>
				<div class="modal-header bg-info pt-2 pb-2">
					<h5 class="modal-title" id="exampleModalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<style>
							#dynamic-container-days {
								display: flex;
								width: 100%;
								height: 200px;
								flex-direction: row;
								align-items: center;
								flex-wrap: wrap;
							}

							#dynamic-container-days .attendance-status {
								height: 30px;
								width: 30px;

								align-items: center;
								color: #fff;
								text-align: center;
								background-color: #607D8B;
								border-radius: 50%;
								margin: 10px;
							}

							#dynamic-container-days .attendance-status span {
								line-height: 30px;
							}

							.dis-att-stat {
								height: 10px;
								width: 10px;
								border-radius: 50%;
								background-color: red;
							}

							.bg-ba {

								border-radius: 30px;
							}

							.bg-success {
								background-color: #00C853 !important;
							}

							.bg-danger {
								background-color: #f44336 !important;
							}

							.bg-secondary {
								background-color: #607D8B !important;
							}
						</style>
						<div class="col-md-7">
							<div id="dynamic-container-days">

							</div>

							<div class="text-left mt-4">
								<h6 style="font-size: 12px; font-weight: 600;">
									<span class="bg-success text-white p-2 pt-1 pb-1 bg-ba">Present</span>
									<span class="bg-danger ml-2 text-white p-2 pt-1 pb-1 bg-ba">Absent</span>
									<span class="bg-primary ml-2 text-white p-2 pt-1 pb-1 bg-ba">Sunday</span>

								</h6>
							</div>
						</div>
						<div class="col-md-5">
							<div id="attendance-chart"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer p-2 pr-3">
					<button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>
	<script>
		$(document).ready(function() {
			var student_ids = [];
			$('#selectAll').click(function() {
				var isChecked = $(this).prop("checked");
				if (isChecked == true) {
					$('#deselectAll').prop('checked', false);
					$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
				}
				//$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
			});
			$('#deselectAll').click(function() {
				var isChecked = $(this).prop("checked");
				if (isChecked == true) {
					$('#selectAll').prop('checked', false);
					$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', false);
				}
				//$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
			});
		});
	</script>
	<script>
		$('#findStudentsForAtt').click(function() {
			var className = $('#class').val();
			var division = $('#division').val();
			var html = '';
			if (className == null && division == null) {
				alert("Select Class And Division");
				return false;
			} else if (className != null && division == null) {
				//Get Only By Std
			} else {
				//Get by All
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Student/getStudentForAttendanceByAll",
					method: "POST",
					data: {
						class: className,
						divi: division
					},
					dataType: "JSON",
					success: function(data) {
						if (data != "fail") {
							$('#selectAll').prop('checked', true);
							for (var count = 0; count < data.length; count++) {
								var countNew = count + 1;
								html += '<tr>';
								html += '<td>' + countNew + '</td>';
								html += '<td>' + data[count].STVCLAS_rollno + '</td>';
								html += '<td>' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '</td>';
								html += '<td><input type="checkbox" value="' + data[count].id + '" checked name="student_row[]" id="" class="studentRowSelector"></td>';
								html += '</tr>';
							}
							$('#tbody').html(html);
						} else {
							console.log("No Data");
						}
					}
				})
			}
		});
	</script>
	<script>
		$('#classCreateForm').on('submit', function(e) {
			student_ids = [];
			attendanceStatus = [];
			var std = $('#class option:selected').val();
			var division = $('#division option:selected').val();
			var dateOfAtt = $('#attendancedate').val();
			e.preventDefault();
			$('#tbody').find('input[type="checkbox"]').each(function() {
				if ($(this).prop("checked") == true) {
					student_ids.push($(this).val());
					attendanceStatus.push('P');
				} else {
					student_ids.push($(this).val());
					attendanceStatus.push('A');
				}
			});
			$.ajax({
				url: "<?php echo base_url(); ?>index.php/Student/fillStudentsAttendance",
				data: {
					student_ids: student_ids,
					std: std,
					division: division,
					attendanceStatus: attendanceStatus,
					dateOfAtt: dateOfAtt
				},
				method: "POST",
				beforeSend: function() {
					$('#createClassButton').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
				},
				success: function(data) {
					$('#createClassButton').html('<i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span>');
					if (data != "fail") {
						alert("Attendence Marked Successfully !!!");
						window.location.reload();
					} else {
						swal("Failed to Mark Attendance");
					}
				}
			});
		});

		//View Student Aettendance By Class
		$('#findStudentAttendanceButton').click(function() {
			var std = $('#class_find').val();
			var div = $('#division_find').val();

			if (std == null && div == null) {
				swal("Select Standard & Division");
			} else if (std == null && div != null) {
				swal("Select Standard");
			} else if (std != null && div == null) {
				swal("Select Division");
			} else {
				$.ajax({
					url: "<?= base_url(); ?>index.php/Student/getClassWiseAttendanceDetails",
					method: "POST",
					dataType: "JSON",
					data: {
						std: std,
						div: div
					},
					success: function(data) {
						if (data != "fail") {
							var html = '';
							for (var count = 0; count < data.length; count++) {
								var total = parseInt(data[count].Present) + parseInt(data[count].Absent);
								var newCount = count + 1;
								html += '<tr>';
								html += '<td>' + newCount + '</td>'
								html += '<td>' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '</td>';
								html += '<td class="text-success">' + data[count].Present + '</td>';
								html += '<td class="text-danger">' + data[count].Absent + '</td>';
								html += '<td>' + total + '</td>';
								html += '</tr>';
							}
							$('#attendance-table-body-student').html(html);
							$('.table-responsive').css("height", "500px");
							$('.table-responsive').slideDown();
						}
					}
				});
			}

		});

		//View Student Aettendance
		$('#findStudentAttendanceButtonByName').click(function() {
			var std = $('#class_find_student').val();
			var div = $('#division_find_student').val();

			if (std == null && div == null) {
				swal("Select Standard & Division");
			} else if (std == null && div != null) {
				swal("Select Standard");
			} else if (std != null && div == null) {
				$.ajax({
					url: "<?= base_url(); ?>index.php/Student/getStudentNamesForAttendanceByStad",
					method: "POST",
					dataType: "JSON",
					data: {
						std: std
					},
					success: function(data) {
						if (data != "fail") {
							var html = '';
							for (var count = 0; count < data.length; count++) {
								var newCount = count + 1;
								html += '<tr data-toggle="modal" data-target="#exampleModal" class="check-attendance-status" data-id="' + data[count].id + '">';
								html += '<td>' + newCount + '</td>'
								html += '<td>' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '</td>';
								html += '<td class="text-success">' + data[count].SPRIDEN_student_id + '</td>';
								html += '<td class="text-danger">' + data[count].STVCLAS_standard + " - " + data[count].STVCLAS_class + '</td>';

								html += '</tr>';
							}
							$('#show-data-student-name').html(html);
							$('.table-responsive').css("height", "500px");
						}
					}
				});
			} else {
				$.ajax({
					url: "<?= base_url(); ?>index.php/Student/getStudentNamesForAttendanceDetails",
					method: "POST",
					dataType: "JSON",
					data: {
						std: std,
						div: div
					},
					success: function(data) {
						if (data != "fail") {
							var html = '';
							for (var count = 0; count < data.length; count++) {
								var newCount = count + 1;
								html += '<tr data-toggle="modal" data-target="#exampleModal" class="check-attendance-status" data-id="' + data[count].id + '">';
								html += '<td>' + newCount + '</td>'
								html += '<td>' + data[count].SPRIDEN_surname + ' ' + data[count].SPRIDEN_student_name + ' ' + data[count].SPRIDEN_father_name + '</td>';
								html += '<td class="text-success">' + data[count].SPRIDEN_student_id + '</td>';
								html += '<td class="text-danger">' + data[count].STVCLAS_standard + " - " + data[count].STVCLAS_class + '</td>';

								html += '</tr>';
							}
							$('#show-data-student-name').html(html);
							$('.table-responsive').css("height", "500px");
						}
					}
				});
			}

		});

		$(document).on('click', '.check-attendance-status', function() {

			var student_id = $(this).attr("data-id");

			var int_pre = 0;
			var int_abs = 0;
			$.ajax({
				url: "<?= base_url(); ?>index.php/Student/getStudentAttendanceByID",
				method: "POST",
				dataType: "JSON",
				data: {
					student_id: student_id
				},
				success: function(data) {
					if (data != "fail") {
						//Assign Student Name to the Header
						$('#exampleModalLabel').html("Student Name : " + data['personal']['SPRIDEN_surname'] + " " + data['personal']['SPRIDEN_student_name'] + " " + data['personal']['SPRIDEN_father_name'] + " ( Class - " + data['personal']['STVCLAS_standard'] + " " + data['personal']['STVCLAS_class'] + " )")
						//generate / display attendnace date with status
						//======= GET TOTAL DAYS IN MOnths
						function daysInMonth(month, year) {
							return new Date(year, month, 0).getDate();
						}
						var d = new Date();
						var year_this = d.getFullYear();
						var month_this = d.getMonth();
						month_this = month_this + 1;
						var total_day_this_month = daysInMonth(month_this, year_this);

						var output = '';
						for (var counter = 0; counter < total_day_this_month; counter++) {
							var bg_color = "bg-secondary";
							var display_num = counter + 1;

							//Display Sundays in Attendance
							// var d = new Date("2021-01-28");
							// var n = d.getDay();
							var dateString = year_this + "-" + month_this + "-" + display_num;
							var da = new Date(dateString);
							var n = da.getDay();
							console.log(n);

							for (var jcount = 0; jcount < data['attendance_details'].length; jcount++) {
								var now_date = data['attendance_details'][jcount].SPRATND_date;
								var substrs = now_date.substr(8, 2);
								// if (data['attendance_details'][jcount].SPRATND_status == "P" && substrs == display_num) {
								// 	bg_color = "bg-success";

								// } else if (data['attendance_details'][jcount].SPRATND_status == "A" && substrs == display_num) {
								// 	bg_color = "bg-danger";

								// } else {
								// 	bg_color = "bg-secondary";

								// }
								if (n == 0) {
									bg_color = "bg-primary";
								} else {
									if (substrs == display_num) {
										if (data['attendance_details'][jcount].SPRATND_status == "P") {
											bg_color = "bg-success";
										} else if (data['attendance_details'][jcount].SPRATND_status == "A") {
											bg_color = "bg-danger";
										} else {
											bg_color = "bg-secondary";
										}
										break;
									} else {
										continue;
									}
								}
							}
							output += '<div class="attendance-status ' + bg_color + '"><span>' + display_num + '</span></div>';
						}
						// genearte attendance days
						$('#dynamic-container-days').html(output);
						//console.log(data['attendance_details']);

						// Display Chart For the stats
						int_pre = parseInt(data['present_days']);
						int_abs = parseInt(data['absent_days']);

					}
				}
			});
		});
	</script>

	<script>
		function handler(e) {
			var get_date = e.target.value;
			$.ajax({
				url: "<?= base_url(); ?>index.php/Student/display_all_std_att_by_day",
				method: "POST",
				dataType: "JSON",
				data: {
					get_date: get_date
				},
				success: function(data) {
					if (data != "fail") {
						var html = '';
						var p_all_to = 0;
						var a_all_to = 0;
						var row_total_total = 0;
						for (var count = 0; count < data.length; count++) {
							var row_total = parseInt(data[count].Present) + parseInt(data[count].Absent);
							var newCount = count + 1;
							html += '<tr class="dis-std-to-class-data" data-class="' + data[count].SPRATND_std + '">';
							html += '<td>' + newCount + '</td>'
							html += '<td class="text-success">' + data[count].SPRATND_std + '</td>';
							html += '<td class="text-success">' + data[count].Present + '</td>';
							html += '<td class="text-danger">' + data[count].Absent + '</td>';
							html += '<td class="text-primary">' + row_total + '</td>';
							html += '</tr>';
							p_all_to = p_all_to + parseInt(data[count].Present);
							a_all_to = a_all_to + parseInt(data[count].Absent);
							row_total_total = row_total_total + row_total;
						}
						html += '<tr class="bg-secondary text-white">';
						html += '<td colspan="2" class="text-center">Total</td>';

						html += '<td>' + p_all_to + '</td>';
						html += '<td>' + a_all_to + '</td>';
						html += '<td>' + row_total_total + '</td>';
						html += '</td>';
						$('#display-total-std-total').html(html);
						console.log(a_all_to);
					}
				}
			});
		}
	</script>
</body>

</html>