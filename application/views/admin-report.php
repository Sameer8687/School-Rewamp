<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Report - Olive English School</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
	<link rel="icon" href="<?php echo base_url(); ?>images/fav.png" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/admin-report.css">
	<link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
	<!-- Boxicons CSS -->
	<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

	<div class="header-top bg-info">
		<img src="<?= base_url('images/logoNewDevWhite.png'); ?>" class="img-fluid logo" alt="">
		<span>Olive English School</span>
	</div>
	<div class="container-fluid mt-2">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body bg-select">
						<div class="split">
							<div class="split-box1">
								<label for="">Teacher :</label>
							</div>
							<div class="split-box2">
								<select class="form-control form-control-sm" name="teacher_name" id="teacher_name">
									<option value="" selected disabled>Select Teacher</option>
									<?php foreach ($teachers as $teacher) { ?>
										<option value="<?php echo $teacher['SPRIDEN_student_id']; ?>"><?php echo $teacher['SPRIDEN_surname'] . " " . $teacher['SPRIDEN_student_name'] . " " . $teacher['SPRIDEN_father_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="split">
							<div class="split-box1">
								<label for="">Date :</label>
							</div>
							<div class="split-box2">
								<input type="date" class="form-control form-control-sm" name="date_of_result" id="date_of_result">
								<div class="button-box text-right mt-2">
									<button type="button" id="getStat" class="btn btn-info btn-sm"><i class='bx bxs-report'></i> View Data</button>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-lg-6 mt-2">
				<div class="card">
					<div class="card-header">
						<i class='bx bx-message-edit'></i> Teacher Remark
					</div>
					<div class="card-body scroll">
						<table class="table table-bordered table-striped table-sm">
							<thead>
								<tr class="">
									<th>ID</th>
									<th>Class</th>
									<th>Name</th>
									<th>Remark</th>
								</tr>
							</thead>
							<tbody id="rmrk_tbody">
								<tr>
									<td colspan="4" class="text-center">Select Teacher & Date To View Records</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-lg-6 mt-2">
				<div class="card">
					<div class="card-header">
						<i class='bx bx-bar-chart'></i> Student Marks
					</div>
					<div class="card-body scroll">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-sm">
								<thead>
									<tr class="">
										<th>ID</th>
										<th>Class</th>
										<th>Name</th>
										<th>Exam</th>
										<th>Subject</th>
										<th>Out Of</th>
										<th>Marks</th>
									</tr>
								</thead>
								<tbody id="mark_tbody">
									<tr>
										<td colspan="7" class="text-center">Select Teacher & Date To View Records</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-lg-6 mt-2">
				<div class="card">
					<div class="card-header">
						<i class='bx bxs-window-alt'></i> Quiz
					</div>
					<div class="card-body scroll">
						<table class="table table-bordered table-striped table-sm">
							<thead>
								<tr class="">
									<th>ID</th>
									<th>Class</th>
									<th>Name</th>
									<th>Quiz</th>
									<th>Marks</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5" class="text-center">Select Teacher & Date To View Records</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-lg-6 mt-2 mb-2">
				<div class="card">
					<div class="card-header">
						<i class='bx bxs-calendar-check'></i> Attendance <span class="float-right" id="date"></span>
					</div>
					<div class="card-body scroll">
						<table class="table table-bordered table-striped table-sm">
							<thead>
								<tr class="">
									<th>ID</th>
									<th>Class</th>
									<th>Name</th>
									<th>Status</th>

								</tr>
							</thead>
							<tbody id="attnd_body">
								<tr>
									<td colspan="4" class="text-center">Select Teacher & Date To View Records</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-12 text-center mt-2 mb-2 contact-box">
				<div class="contact-wrapper">
					<a href="" class="icon-box btn-call">
						<i class='bx bx-phone-call'></i>

					</a>
					<a href="" class="icon-box btn-wha">
						<i class='bx bxl-whatsapp'></i>
					</a>
					<a href="" class="icon-box btn-sms">
						<i class='bx bx-message-detail'></i>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!--JS Files-->
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

	<script>
		$('#getStat').click(function() {
			var teacher = $('#teacher_name').val();
			var dates = $('#date_of_result').val();
			var attnd_html = '';
			var rmrk_html = '';
			var quiz_html = '';
			var marks_html = '';
			if (teacher == null && dates == "") {
				alert("Select Teacher Name & Date");
				return false;
			} else if (teacher != null && dates == "") {
				alert("Select Date");
				return false;
			} else if (teacher == null && dates != "") {
				alert("Select Teacher Name");
				return false;
			} else {
				$.ajax({
					url: "<?= base_url('index.php/Report/getStatsForReport'); ?>",
					method: "POST",
					data: {
						teacher: teacher,
						date: dates
					},
					dataType: "JSON",
					success: function(data) {
						//Attendance Data Display
						let st_bg = '';
						if (data['attendance_data'] != "0") {
							for (var count = 0; count < data['attendance_data'].length; count++) {
								if (data['attendance_data'][count].SPRATND_status == "P") {
									st_bg = "bg-success";
								} else {
									st_bg = "bg-danger";
								}
								attnd_html += '<tr>';
								attnd_html += '<td class="text-center">' + data['attendance_data'][count].SPRIDEN_student_id + '</td>';
								attnd_html += '<td class="text-center">' + data['attendance_data'][count].STVCLAS_standard + ' - ' + data['attendance_data'][count].STVCLAS_class + '</td>';
								attnd_html += '<td>' + data['attendance_data'][count].name + '</td>';
								attnd_html += '<td class="text-center ' + st_bg + ' text-white">' + data['attendance_data'][count].SPRATND_status + '</td>';
								attnd_html += '</tr>';

							}
						} else {
							attnd_html += '<tr>';
							attnd_html += '<td colspan="4" class="text-center font-weight-bold">No Data in Table</td>';
							attnd_html += '</tr>';
						}

						$('#date').html(dates)
						//Remrks Display
						if (data['remarks'] != "0") {
							for (var count = 0; count < data['remarks'].length; count++) {
								rmrk_html += '<tr>';
								rmrk_html += '<td class="text-center">' + data['remarks'][count].SPRIDEN_student_id + '</td>';
								rmrk_html += '<td class="text-center">' + data['remarks'][count].STVCLAS_standard + ' - ' + data['remarks'][count].STVCLAS_class + '</td>';
								rmrk_html += '<td>' + data['remarks'][count].name + '</td>';
								rmrk_html += '<td>' + data['remarks'][count].stvrmrk_remark + '</td>';
								rmrk_html += '</tr>';

							}
						} else {
							rmrk_html += '<tr>';
							rmrk_html += '<td colspan="4" class="text-center font-weight-bold">No Data in Table</td>';
							rmrk_html += '</tr>';
						}

						//Marks Display
						if (data['marks_data'] != "0") {
							for (var count = 0; count < data['marks_data'].length; count++) {
								marks_html += '<tr>';
								marks_html += '<td class="text-center">' + data['marks_data'][count].SPRIDEN_student_id + '</td>';
								marks_html += '<td class="text-center">' + data['marks_data'][count].sprmark_class + ' - ' + data['marks_data'][count].sprmark_div + '</td>';
								marks_html += '<td>' + data['marks_data'][count].name + '</td>';
								marks_html += '<td>' + data['marks_data'][count].stvexam_name + '</td>';
								marks_html += '<td class="text-center">' + data['marks_data'][count].stvsubj_subject + '</td>';
								marks_html += '<td class="text-center">' + data['marks_data'][count].sprmark_total_marks + '</td>';
								marks_html += '<td class="text-center">' + data['marks_data'][count].sprmark_mark + '</td>';
								marks_html += '</tr>';

							}
						} else {
							marks_html += '<tr>';
							marks_html += '<td colspan="7" class="text-center font-weight-bold">No Data in Table</td>';
							marks_html += '</tr>';
						}
						$('#attnd_body').html(attnd_html);
						$('#rmrk_tbody').html(rmrk_html);
						$('#mark_tbody').html(marks_html);

					}
				})
			}

		});
	</script>
</body>

</html>
