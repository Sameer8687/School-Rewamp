<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lesson Module - Olive English School</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
	<!-- Boxicons CSS -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<style>

	</style>
</head>

<body>

	<!-- As a heading -->
	<nav class="navbar navbar-light" style="background-color: #17a2b8;">
		<span class="navbar-brand mb-0 h1">
			<a href="<?= base_url('dashboard') ?>" class="btn btn-sm text-white"><i class='bx bx-left-arrow-alt'></i></a>
			<a href="" class="btn text-white">Lesson Management</a>
		</span>
	</nav>
	<div class="container mt-2">
		<div class="row">
			<div class="col-md-12">
				<div class="alert pt-1 pb-1" id="alert" style="display: none;">

				</div>
			</div>
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h6><i class='bx bx-book-open'></i> Add Lessons Status</h6>
					</div>
					<div class="card-body pb-1 pt-2">
						<div class="row">
							<div class="col-md-12">
								<form action="" id="lesson-form">
									<table class="table table-sm table-borderless">
										<tr>
											<td style="width: 30%;">Standard <span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<select required name="class" class="form-control form-control-sm" id="class">
													<option value="">Select Standard</option>
													<?php foreach ($classes as $st_class) { ?>
														<option value="<?= $st_class['stvfees_class']; ?>"><?= $st_class['stvfees_class']; ?></option>
													<?php } ?>
												</select>
												<input type="hidden" name="hidden_header_id" id="hidden_header_id" value="0">
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">Subject <span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<select required class="form-control form-control-sm view-lessons" name="subject" id="subject">
													<option value="">Select Subject</option>
												</select>
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">Lesson <span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<input type="text" name="lesson_name" id="lesson_name" class="form-control form-control-sm">
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">Topics Covered <span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<textarea name="lesson_remark" id="lesson_remark" cols="30" rows="3" class="form-control form-control-sm"></textarea>
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">Teaching <span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<input required type="radio" name="teaching" id="teaching_yes" value="Yes"> Yes
												<input type="radio" name="teaching" id="teaching_no" value="No"> No
												<span class="float-right" id="teaching_date_span"></span>
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">Notes <span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<input required type="radio" name="notes" id="notes_yes" value="Yes"> Yes
												<input type="radio" name="notes" id="notes_no" value="No"> No
												<span class="float-right" id="notes_date_span"></span>
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">Exam Status <span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<input required type="radio" name="exam_status" id="exam_status_yes" value="Yes"> Yes
												<input type="radio" name="exam_status" id="exam_status_no" value="No"> No
												<span class="float-right" id="exam_status_span"></span>
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">Start Date<span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<input style="width: 20%;" type="date" class="form-control form-control-sm" name="start_date" id="start_date">
											</td>
										</tr>
										<tr>
											<td style="width: 30%;">End Date<span style="float:right">:</span> </td>
											<td style="width: 70%;">
												<input style="width: 20%;" type="date" class="form-control form-control-sm" name="end_date" id="end_date">
											</td>
										</tr>
										<tr id="dyna">

										</tr>
										<tr>
											<td style="width: 30%;">

											</td>
											<td style="width: 70%; text-align: right;">
												<button class="btn btn-sm" type="button" onclick="window.location.reload()" id="refresh">Refresh</button>
												<button class="btn btn-sm btn-info" id="view-lessons"><i class='bx bxs-save'></i> Add Remark</button>
											</td>
										</tr>
									</table>
								</form>
							</div>
						</div>
					</div>
				</div>
				<style>
					#lesson-table-body tr td.add-lesson-review {
						cursor: pointer;
					}
				</style>
				<div class="card mt-2">
					<div class="card-body p-0 ">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive p-0">
									<table class="table table-bordered table-sm m-0">
										<thead>
											<tr class="text-center bg-info text-white">
												<th>#</th>
												<th>Lesson</th>
												<th>Teaching</th>
												<th>Notes</th>
												<th>Review</th>
											</tr>
										</thead>
										<tbody id="lesson-table-body">

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

	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

	<script>
		$('#class').change(function() {
			var std = $('#class').val();
			$.ajax({
				url: "<?= base_url('MasterForm/getAllSubjetcsByStd') ?>?std=" + std,
				method: "GET",
				dataType: "JSON",
				success: function(data) {
					if (data != null) {
						var html = '<option>Select Subject</option>';
						for (var count = 0; count < data.length; count++) {
							html += '<option value="' + data[count].stvsubj_id + '">' + data[count].stvsubj_subject + '</option>';
						}
						$('#subject').html(html);
					}
				}

			})
		});

		$('#subject').change(function() {
			var std = $('#class').val();
			var sub = $('#subject').val();

			if (std == '' && sub == '') {
				$('#alert').removeClass("alert-success");
				$('#alert').addClass("alert-danger");
				$('#alert').html("Select Class & Subject");
				$('#alert').fadeIn();
			} else if (std != '' && sub == '') {
				$('#alert').removeClass("alert-success");
				$('#alert').addClass("alert-danger");
				$('#alert').html("Select Subject");
				$('#alert').fadeIn();
			} else if (std == '' && sub != '') {
				$('#alert').removeClass("alert-success");
				$('#alert').addClass("alert-danger");
				$('#alert').html("Select Class ");
				$('#alert').fadeIn();
			} else {
				// get lessons
				$.ajax({
					url: "<?= base_url('index.php/lesson/all_lessons') ?>?class=" + std + "&sub=" + sub,
					method: "GET",
					dataType: "JSON",
					beforeSend: function() {},
					success: function(data) {
						if (data != null) {
							generate_table(data)
						} else {
							$('#alert').removeClass("alert-success");
							$('#alert').addClass("alert-danger");
							$('#alert').html("Lessons not available");
							$('#alert').fadeIn();
						}
					}
				});
			}
		});

		$(document).on('click', '.view-lesson-review', function() {
			var id = $(this).attr("data-id");
			$.ajax({
				url: "<?= base_url('index.php/Lesson/lesson_data') ?>?id=" + id,
				method: "GET",
				dataType: "JSON",
				success: function(data) {
					if (data != null) {
						$('#hidden_header_id').val(data.stvlmst_id);
						$('#lesson_name').val(data.stvlmst_lesson);
						$('#lesson_remark').val(data.stvlmst_teacher_comment);
						$('#lesson_review').val(data.stvlmst_review);
						var review = data.stvlmst_review;
						if (review == null) {
							review = '';
						}
						if (data.stvlmst_teached == 'Yes') {
							var radio1 = document.querySelector('#teaching_yes');
							radio1.checked = true;
						} else {
							var radio1 = document.querySelector('#teaching_no');
							radio1.checked = true;
						}
						if (data.stvlmst_notes == 'Yes') {
							var radio1 = document.querySelector('#notes_yes');
							radio1.checked = true;
						} else {
							var radio1 = document.querySelector('#notes_no');
							radio1.checked = true;
						}
						if (data.stvlmst_notes == 'Yes') {
							var radio1 = document.querySelector('#notes_yes');
							radio1.checked = true;
						} else {
							var radio1 = document.querySelector('#notes_no');
							radio1.checked = true;
						}
						$('#refresh').css("display", "inline-block");
						// dynamic content
						var html = '<td style="width: 30%;">Remark <span style="float:right">:</span> </td>';
						html += '<td style="width: 70%;">';
						html += '<textarea name="lesson_review" id="lesson_review" cols="30" rows="3" class="form-control form-control-sm">' + review + '</textarea>';
						html += '</td>';
						$('#dyna').html(html);

						$('#teaching_date_span').html(data.stvlmst_teached_at);
						$('#notes_date_span').html(data.stvlmst_notes_at);

					}
				}
			});

		});

		// save data using ajax
		$('#lesson-form').on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				url: "<?= base_url('index.php/Lesson/add_review') ?>",
				method: "POST",
				dataType: "JSON",
				data: new FormData(this),
				processData: false,
				contentType: false,
				beforeSend: function() {
					$('#view-lessons').html("<i class='bx bx-loader bx-spin'></i> Saving ...");
				},
				success: function(data) {
					$('#view-lessons').html("<i class='bx bxs-save'></i> Add Lesson");
					$('#class').val('');
					$('#subject').html('');
					$('#lesson_remark').val('');
					var radio1 = document.querySelector('input[type=radio][name=teaching]:checked');
					radio1.checked = false;
					var radio2 = document.querySelector('input[type=radio][name=notes]:checked');
					radio2.checked = false;
					$('#lesson_name').val('');
					$('#hidden_header_id').val('');
					$('#dyna').html('');
					if (data != null) {
						generate_table(data)
					}
				}
			});
		});

		//generate dynamic content
		function generate_table(data) {
			var html = '';
			if (data.length > 0) {
				for (var count = 0; count < data.length; count++) {
					var next = count + 1;
					var teched_yes = '';
					var notes_yes = '';
					var review = '';
					//teaching
					if (data[count].stvlmst_teached == 1) {
						teched_yes = 'Yes';
					} else {
						teched_yes = 'No';
					}

					//notes
					if (data[count].stvlmst_notes == 1) {
						notes_yes = 'Yes';
					} else {
						notes_yes = 'No';
					}
					if (data[count].stvlmst_review == null) {
						review = '';
					} else {
						review = data[count].stvlmst_review;
					}
					//dynamic content
					html += '<tr class="view-lesson-review" data-id="' + data[count].stvlmst_id + '">';
					html += '<td>' + next + '</td>';
					html += '<td>' + data[count].stvlmst_lesson + '</td>';
					html += '<td class="text-center">' + teched_yes + '</td>';
					html += '<td class="text-center">' + notes_yes + '</td>';
					html += '<td>' + review + '</td>';
					html += '</tr>';
				}
			} else {
				html += '<tr>';
				html += '<td colspan="5" class="text-center">No data available</td>';
				html += '</tr>';
			}
			$('#lesson-table-body').html(html);
		}
	</script>
</body>

</html>
