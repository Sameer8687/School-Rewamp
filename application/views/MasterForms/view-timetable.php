<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - View Timetable Master</title>
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

		<div class="pcoded-content">
			<!-- [ breadcrumb ] start -->
			<?php include 'application/views/MasterForms/time_link.php'; ?>
			<!-- [ breadcrumb ] end -->
			<!-- [ Main Content ] start -->

			<div class="row margin-top-minus-30 mb-2">
				<div class="col-md-7">
					<a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a>
					<!-- <button onclick="showTableData()" id="generate-timetable" class="btn btn-secondary btn-sm"> <i class="fas fa-calendar-alt"></i> Create Timetable</button> -->

				</div>
				<!-- <div class="col-md-5 student-form-search">
                        <input type="text" class="form-control form-control-sm" id="search_student_name" placeholder="Search Student Name">
                        <span class="left-icon"><i class="fas fa-search"></i></span>
                        <span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>
                    </div> -->
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
					<div class="card pb-0 mb-0">
						<div class="card-header mb-0 pb-2 ">
							<h5 class="margin-top-minus-10 mb-0 pb-0">View Timetable</h5>
						</div>
						<div class="card-body pb-0 mb-0">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group row">
										<label for="" class="col-md-4 text-right">Standard:</label>
										<select class="form-control form-control-sm col-md-8" name="class" id="class">
											<option selected disabled value="">Select Class</option>
											<?php
											foreach ($classes as $class) {
											?>
												<option value="<?php echo $class['stvfees_class']; ?>"><?php echo $class['stvfees_class']; ?></option>
											<?php } ?>

										</select>
									</div>
								</div>
								<div class="col-md-8">
									<p id="subject-shower"></p>
								</div>
								<!-- <div class="col-md-4">
									<div class="form-group row">
										<label for="" class="col-md-4 text-right">Time:</label>
										<select class="form-control form-control-sm col-md-8" name="class" id="class">
											<option selected disabled value="">Select Time</option>
											<?php
											foreach ($all_times as $all_time) {
											?>
												<option value="<?php echo $all_time['stvtbtm_id']; ?>"><?php echo date("g:i A", strtotime($all_time['stvtbtm_start_time'])); ?></option>
											<?php } ?>

										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group row">
										<label for="" class="col-md-4 text-right">Day:</label>
										<select class="form-control form-control-sm col-md-8" name="class" id="class">
											<option selected disabled value="">Select Day</option>
											<option value="Monday">Monday</option>
											<option value="Tuesday">Tuesday</option>
											<option value="Wednesday">Wednesday</option>
											<option value="Thursday">Thursday</option>
											<option value="Friday">Friday</option>
											<option value="Saturday">Saturday</option>
										</select>
									</div>
								</div>
								<div class="col-md-4 margin-top-minus-20">
									<div class="form-group row">
										<label for="" class="col-md-4 text-right">Subject:</label>
										<select class="form-control form-control-sm col-md-8" name="subject_selector_picker" id="subject_selector_picker">
											<option selected disabled value="">Select</option>
										</select>
									</div>
								</div>
								<div class="col-md-4 margin-top-minus-20">
									<div class="form-group row">
										<div class="col-md-4"></div>
										<button for="" class="col-md-8 btn btn-sm btn-secondary">Add To Table</button>
									</div>
								</div> -->
							</div>
							<style>
								#time-table-table {
									display: none;
								}

								#time-table-table tr td {
									padding: 8px;
								}
							</style>
							<div class="table-responsive">
								<table class="table table-bordered" id="time-table-table">
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

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- [ Main Content ] end -->
	</div>



	</div>
	<!-- [ Main Content ] end -->
	</div>





	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>

	<script>
		$('#class').change(function() {
			var class_name = $('#class').val();
			$.ajax({
				url: "<?= base_url(); ?>index.php/Timetable/getTimetableByStd",
				method: "POST",
				dataType: "JSON",
				data: {
					class_name: class_name
				},
				success: function(data) {
					if (data != "fail") {
						var html = '';
						if (data == "") {
							html += '<tr><td colspan="7" class="text-center">Timetable Not Generated for ' + class_name + ' Standard</td></tr>';
						} else {

							for (var count = 0; count < data.length; count++) {
								var alert_class = '';
								if (data[count].stvttbl_monday == "Recess") {
									alert_class = 'alert-info';
								} else {
									alert_class = '';
								}
								html += '<tr>';
								html += '<td class="' + alert_class + '">' + data[count].stvttbl_time + '</td>';
								if (data[count].stvttbl_monday == "Recess") {
									html += '<td colspan="6" class="text-center font-weight-bold alert-info">' + data[count].stvttbl_monday + '</td>';
								} else {
									html += '<td>' + data[count].stvttbl_monday + '</td>';
									html += '<td>' + data[count].stvttbl_tuesday + '</td>';
									html += '<td>' + data[count].stvttbl_wednesday + '</td>';
									html += '<td>' + data[count].stvttbl_thurday + '</td>';
									html += '<td>' + data[count].stvttbl_friday + '</td>';
									html += '<td>' + data[count].stvttbl_saturday + '</td>';
								}
								html += '</tr>';

							}
						}
						$('#timetable-table').html(html);
						$('#time-table-table').slideDown();
					}
				}
			});
		});
	</script>

</body>

</html>
