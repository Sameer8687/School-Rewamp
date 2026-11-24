<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Create Timetable Master</title>
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
					<button id="generate-timetable" class="btn btn-secondary btn-sm"> <i class="fas fa-calendar-alt"></i> Create Timetable</button>

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
							<h5 class="margin-top-minus-10 mb-0 pb-0">Create Timetable</h5>
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
										<?php foreach ($all_times as $all_tm) { ?>
											<tr>
												<style>
													#timetable-table td {
														padding: 5px;
													}

													#timetable-table td .input-taken-cell {
														border: none;
													}
												</style>
												<td><?= $all_tm['stvtbtm_start_time']; ?></td>
												<td>
													<select name="input-select-picker" id="" class="form-control form-control-sm input-select-picker"></select>
												</td>
												<td>
													<select name="input-select-picker" id="" class="form-control form-control-sm input-select-picker"></select>
												</td>
												<td>
													<select name="input-select-picker" id="" class="form-control form-control-sm input-select-picker"></select>
												</td>
												<td>
													<select name="input-select-picker" id="" class="form-control form-control-sm input-select-picker"></select>
												</td>
												<td>
													<select name="input-select-picker" id="" class="form-control form-control-sm input-select-picker"></select>
												</td>
												<td>
													<select name="input-select-picker" id="" class="form-control form-control-sm input-select-picker"></select>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<p id="info"></p>
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
				url: "<?= base_url(); ?>index.php/Teacher/getSubjectByStd",
				method: "POST",
				dataType: "JSON",
				data: {
					class_name: class_name
				},
				success: function(data) {
					if (data != "fail") {
						var html = '<option selected disabled value="">Select Subject</option>';
						for (var count = 0; count < data.length; count++) {
							html += '<option value="' + data[count].stvsubj_id + '">' + data[count].stvsubj_subject + '</option>';
						}
						$('.input-select-picker').html(html);
					}
				}
			});
		});
	</script>

	<script>
		function showTableData() {
			var rows = '<?php echo count($all_times); ?>';
			const myArray1 = [];
			var myTab = document.getElementById('time-table-table');
			var std = $('#class').val();

			if (std == null) {
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

				// $.ajax({
				// 	url: "<?php echo base_url(); ?>index.php/Timetable/create_timetable",
				// 	data: {
				// 		timetable: tRows,
				// 		std: std,
				// 	},
				// 	method: "POST",
				// 	beforeSend: function() {
				// 		$('#generate-timetable').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
				// 	},
				// 	success: function(data) {
				// 		$('#generate-timetable').html('<i class="fas fa-calendar-alt"></i> Create Timetable');
				// 		if (data != "fail") {
				// 			alert("Timetable Created Successfully !!!");
				// 			window.location.reload();
				// 		} else {
				// 			swal("Failed to Create Time Table");
				// 		}
				// 	}
				// });
			}
		}
	</script>
	<script>
		$('#generate-timetable').click(function() {
			var arr = [];
			$('#timetable-table').closest('tr').find('td').each(function() {
				var that = $(this);
				if (that.find('select').length > 0) {
					arr.push(that.find('select').val());
				} else {
					arr.push(that.text());
				}
			});
			alert(arr);
		});
	</script>
</body>

</html>
