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
</head>

<body>
	<!-- As a heading -->
	<nav class="navbar navbar-light" style="background-color: #17a2b8;">
		<span class="navbar-brand mb-0 h1">
			<a href="<?= base_url('dashboard') ?>" class="btn btn-sm text-white"><i
					class='bx bx-left-arrow-alt'></i></a>
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
									<!-- row1 -->
									 <input type="hidden" name="hidden_header_id" id="hidden_header_id" value="">
									<div class="row">
										<div class="col-lg-6">
											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Standard <span
														class="float-right">:</span></label>
												<select required name="class"
													class="form-control form-control-sm col-md-8 col-sm-8 col-xs-8 mb-1"
													id="class">
													<option value="">Select Standard</option>
													<?php foreach ($classes as $st_class) { ?>
														<option value="<?= $st_class['stvfees_class']; ?>">
															<?= $st_class['stvfees_class']; ?>
														</option>
													<?php } ?>
												</select>
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Division <span
														class="float-right">:</span></label>
												<select required name="division"
													class="form-control form-control-sm col-md-8 mb-1" id="division">
													<option value="" selected disabled>Select Division
													</option>
													<option value="A">A</option>
													<option value="B">B</option>
													<option value="C">C</option>
												</select>
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Subject <span
														class="float-right">:</span></label>
												<select required name="subject"
													class="form-control form-control-sm col-md-8 mb-1 view-lessons"
													id="subject">
													<option value="">Select Subject</option>
												</select>
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Chapter No
													<span class="float-right">:</span></label>
												<select required name="chapter"
													class="form-control form-control-sm col-md-8 mb-1" id="chapter">
													<option value="">Select Chapter</option>
												</select>

											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Chapter Name
													<span class="float-right">:</span></label>
												<select required name="lesson_name"
													class="form-control form-control-sm col-md-8 mb-1" id="lesson_name">
													<option value="">Select Lesson</option>
												</select>
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Topic
													Covered <span class="float-right">:</span></label>

												<textarea name="lesson_remark"
													class="form-control form-control-sm col-md-8 mb-1"
													id="lesson_remark" cols="30" rows="1"></textarea>
											</div>

										</div>

										<div class="col-lg-6">

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Lesson PDF
													<span class="float-right">:</span></label>

												<input type="file" name="lesson_pdf" id="lesson_pdf"
													class="form-control form-control-sm col-md-8 mb-1" accept=".pdf">
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">PreRequisites
													<span class="float-right">:</span></label>

												<input type="file" name="pre_req_pdf" id="pre_req_pdf"
													class="form-control form-control-sm col-md-8 mb-1">
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Que &
													Ans <span class="float-right">:</span></label>

												<input type="file" name="question_pdf" id="question_pdf"
													class="form-control form-control-sm col-md-8 mb-1">
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Quiz
													<span class="float-right">:</span></label>

												<input type="file" name="quiz_pdf" id="quiz_pdf"
													class="form-control form-control-sm col-md-8 mb-1" accept=".csv">
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Worksheet <span
														class="float-right">:</span></label>

												<input type="file" name="worksheet_pdf" id="worksheet_pdf"
													class="form-control form-control-sm col-md-8 mb-1">
											</div>

											<div class="row">
												<label for="lesson_pdf" class="form-label col-md-4 mb-1">Explanation
													<span class="float-right">:</span></label>

												<input type="file" name="learning_pdf" id="learning_pdf"
													class="form-control form-control-sm col-md-8 mb-1">
											</div>

										</div>
									</div>

									<!-- row7 -->
									<div class="row mb-2">

										<label for="lesson_pdf"
											class="form-label col-md-2 col-sm-2 col-xs-2 mb-1">Teaching <span
												class="float-right">:</span></label>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<input required type="radio" name="teaching" value="Yes">
											Yes
											<input type="radio" name="teaching" value="No"> No
											<span class="float-right" id="teaching_dat_span"></span>
										</div>
										<label for="lesson_pdf" class="form-label col-md-2 col-sm-2 col-xs-2 mb-1">Notes
											<span class="float-right">:</span></label>

										<div class="col-md-2 col-sm-2 col-xs-2">
											<input required type="radio" name="notes" value="Yes"> Yes
											<input type="radio" name="notes" value="No"> No
											<span class="float-right" id="notes_date_span"></span>

										</div>

										<label for="lesson_pdf" class="form-label col-md-2 col-sm-2 col-xs-2 mb-1">Exam
											Status <span class="float-right">:</span></label>
										<div class="col-md-2 col-sm-2 col-xs-2">

											<input required type="radio" name="exam_status" value="Yes">
											Yes
											<input type="radio" name="exam_status" value="No"> No
											<span class="float-right" id="exam_status_span"></span>
										</div>
									</div>
									<!-- row8 -->
									<div class="row">

										<label for="lesson_pdf" class="form-label col-md-2 col-sm-2 col-xs-2 mb-1">Start
											Date <span class="float-right">:</span></label>

										<input type="date" name="start_date" id="start_date"
											class="form-control form-control-sm col-md-4 col-sm-4 col-xs-4 mb-1">

										<label for="lesson_pdf" class="form-label col-md-2 col-sm-2 col-xs-2 mb-1">End
											Date <span class="float-right">:</span></label>

										<input type="date" name="end_date" id="end_date"
											class="form-control form-control-sm col-md-4 col-sm-4 col-xs-4 mb-1">
									</div>
									<!-- row9 -->
									<div class="row">
										<div class="col-md-12 text-right">
											<button style="display: none;" class="btn btn-sm" type="button"
												onclick="window.location.reload()" id="refresh">New</button>
											<button class="btn btn-sm btn-info" id="view-lessons"><i
													class='bx bxs-save'></i> Add Lesson</button>
										</div>

									</div>
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
												<th>Lesson Name</th>
												<th>Standard</th>
												<th>Subject</th>
												<th>Chapter</th>
												<th>Start Date</th>
												<th>End Date</th>
												<th>Teaching</th>
												<th>Notes</th>
												<th>Exam</th>
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

		$('#class').change(function () {
			var std = $('#class').val();
			if (std) {
				$.ajax({
					url: "<?= base_url('index.php/lesson/getAllSubjectsByStd') ?>", // Typo fix in URL
					method: "GET",
					data: { std: std }, // Better than using `?std=` string
					dataType: "JSON",
					success: function (data) {
						console.log(data); // Debugging line to check the data
						var html = '<option value="">Select Subject</option>';
						if (data && data.length > 0) {
							for (var i = 0; i < data.length; i++) {
								html += '<option value="' + data[i].stvsubj_id + '">' + data[i].stvsubj_subject + '</option>';
							}
						}
						$('#subject').html(html);
					}
				});
			}
		});


		$('#subject').on('change', function () {
			var std = $('#class').val();
			var div = $('#division').val();
			var sub = $('#subject').val();

			// All lessons load karein
			if (std != '' && div != '' && sub != '') {
				$.ajax({
					url: "<?= base_url('index.php/lesson/all_lessons') ?>?class=" + std + "&sub=" + sub + "&div=" + div,
					method: "GET",
					dataType: "JSON",
					success: function (data) {
						if (data != null) {
							generate_table(data)
						}
					}
				});
			}

			// Chapters load karein
			$.ajax({
				url: "<?= base_url('index.php/lesson/getChapters') ?>?class=" + std + "&sub=" + sub,
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					console.log(response);
					let chapterHtml = '<option value="">Select Chapter</option>';
					response.chapters.forEach(function (item) {
						chapterHtml += `<option value="${item.lesson_no}">${item.lesson_no}</option>`;
					});
					$('#chapter').html(chapterHtml);
				}
			});

			// Lessons load karein
			$.ajax({
				url: "<?= base_url('index.php/lesson/getLesson') ?>?class=" + std + "&sub=" + sub,
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					console.log(response);
					let lessonHtml = '<option value="">Select Lesson</option>';
					response.lessons.forEach(function (item) {
						lessonHtml += `<option value="${item.lesson_name}">${item.lesson_name}</option>`;
					});
					$('#lesson_name').html(lessonHtml);
				}
			});
		});

		// $(document).on('click', '.view-lesson-review', function () {
		// 	var id = $(this).attr("data-id");
		// 	$.ajax({
		// 		url: "<?= base_url('index.php/Lesson/lesson_data') ?>?id=" + id,
		// 		method: "GET",
		// 		dataType: "JSON",
		// 		success: function (data) {
		// 			console.log(data);
		// 			if (data != null) {
		// 				$('#hidden_header_id').val(first.stvlmst_id);
		// 				$('#lesson_name').val(first.stvlmst_lesson);
		// 				$('#lesson_remark').val(first.stvlmst_teacher_comment);
		// 				$('#lesson_review').val(first.stvlmst_review);
		// 				$('#exam_status').val(first.stvlmst_exam_status);
		// 				$('#start_date').val(first.stvlmst_start_date);
		// 				$('#chapter').val(first.chapter);
		// 				$('#lesson_pdf').val(first.lesson_pdf);
		// 				$('#quiz_pdf').val(first.quiz_pdf);
		// 				$('#question_pdf').val(first.question_pdf);
		// 				$('#worksheet_pdf').val(first.worksheet_pdf);
		// 				$('#pre_req_pdf').val(first.pre_request);
		// 				$('#learning_pdf').val(first.learning_outcome);
		// 				$('#end_date').val(first.stvlmst_end_date);
		// 				$('#end_date').val(data.stvlmst_end_date);

		// 				if (data.stvlmst_teached == 'Yes') {
		// 					var radio1 = document.querySelector('#teaching_yes');
		// 					radio1.checked = true;
		// 				} else {
		// 					var radio1 = document.querySelector('#teaching_no');
		// 					radio1.checked = true;
		// 				}
		// 				if (data.stvlmst_notes == 'Yes') {
		// 					var radio1 = document.querySelector('#notes_yes');
		// 					radio1.checked = true;
		// 				} else {
		// 					var radio1 = document.querySelector('#notes_no');
		// 					radio1.checked = true;
		// 				}
		// 				if (data.stvlmst_exam_status == 'Yes') {
		// 					var radio1 = document.querySelector('#exam_status_yes');
		// 					radio1.checked = true;
		// 				} else {
		// 					var radio1 = document.querySelector('#exam_status_no');
		// 					radio1.checked = true;
		// 				}
		// 				$('#refresh').css("display", "inline-block");
		// 				// dynamic content
		// 				var html = '<td style="width: 30%;">Remark <span style="float:right">:</span> </td>';
		// 				html += '<td style="width: 70%;">';
		// 				html += '<textarea readonly name="lesson_review" id="lesson_review" cols="30" rows="3" class="form-control form-control-sm"></textarea>';
		// 				html += '</td>';
		// 				$('#dyna').html(html);
		// 				//dynamic date
		// 				$('#teaching_date_span').html(data.stvlmst_teached_at);
		// 				$('#notes_date_span').html(data.stvlmst_notes_at);
		// 			}
		// 		}
		// 	});
		// });

		$(document).on('click', '.view-lesson-review', function () {
    var id = $(this).attr("data-id");
    $.ajax({
        url: "<?= base_url('index.php/Lesson/lesson_data') ?>?id=" + id,
        method: "GET",
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            if (data != null) {
                // Use data directly, not data.first
                $('#hidden_header_id').val(data.stvlmst_id);
                $('#lesson_name').val(data.stvlmst_lesson);
                $('#lesson_remark').val(data.stvlmst_teacher_comment);
                $('#lesson_review').val(data.stvlmst_review);
                $('#exam_status').val(data.stvlmst_exam_status);
                $('#start_date').val(data.stvlmst_start_date);
                $('#chapter').val(data.chapter);
                // Note: You can't set file input values for security reasons
                // $('#lesson_pdf').val(data.lesson_pdf);
                // $('#quiz_pdf').val(data.quiz_pdf);
                // $('#question_pdf').val(data.question_pdf);
                // $('#worksheet_pdf').val(data.worksheet_pdf);
                // $('#pre_req_pdf').val(data.pre_request);
                // $('#learning_pdf').val(data.learning_outcome);
                $('#end_date').val(data.stvlmst_end_date);

                // Set radio buttons
                $('input[name="teaching"][value="' + data.stvlmst_teached + '"]').prop('checked', true);
                $('input[name="notes"][value="' + data.stvlmst_notes + '"]').prop('checked', true);
                $('input[name="exam_status"][value="' + data.stvlmst_exam_status + '"]').prop('checked', true);
                
                $('#refresh').css("display", "inline-block");
                
                // dynamic content
                var html = '<td style="width: 30%;">Remark <span style="float:right">:</span> </td>';
                html += '<td style="width: 70%;">';
                html += '<textarea readonly name="lesson_review" id="lesson_review" cols="30" rows="3" class="form-control form-control-sm">' + (data.stvlmst_review || '') + '</textarea>';
                html += '</td>';
                $('#dyna').html(html);
                
                //dynamic date
                $('#teaching_date_span').html(data.stvlmst_teached_at || '');
                $('#notes_date_span').html(data.stvlmst_notes_at || '');
            }
        }
    });
});

		// save data using ajax
		// $('#lesson-form').on('submit', function (e) {
		// 	e.preventDefault();
		// 	$.ajax({
		// 		url: "<?= base_url('index.php/Lesson/add_lesson') ?>",
		// 		method: "POST",
		// 		dataType: "JSON",
		// 		data: new FormData(this),
		// 		processData: false,
		// 		contentType: false,
		// 		beforeSend: function () {
		// 			$('#view-lessons').html("<i class='bx bx-loader bx-spin'></i> Saving ...");
		// 		},
		// 		success: function (data) {
		// 			$('#view-lessons').html("<i class='bx bxs-save'></i> Add Lesson");
		// 			$('#lesson_remark').val('');
		// 			var radio1 = document.querySelector('input[type=radio][name=teaching]:checked');
		// 			radio1.checked = false;
		// 			var radio2 = document.querySelector('input[type=radio][name=notes]:checked');
		// 			radio2.checked = false;
		// 			var radio3 = document.querySelector('input[type=radio][name=exam_status]:checked');
		// 			radio3.checked = false;
		// 			$('#lesson_name').val('');
		// 			$('#chapter').val('');
		// 			$('#start_date').val('');
		// 			$('#end_date').val('');
		// 			$('#lesson_pdf').val('');
		// 			$('#pre_req_pdf').val('');
		// 			$('#question_pdf').val('');
		// 			$('#quiz_pdf').val('');
		// 			$('#worksheet_pdf').val('');
		// 			$('#learning_pdf').val('');
		// 			$('#start_date').val('');
		// 			$('#end_date').val('');
		// 			$('#hidden_header_id').val('');
		// 			$('#dyna').html('');
		// 			if (data != null) {
		// 				generate_table(data)
		// 			}
		// 		}
		// 	});
		// });

		// save data using ajax
$('#lesson-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: "<?= base_url('index.php/Lesson/add_lesson') ?>",
        method: "POST",
        dataType: "JSON",
        data: new FormData(this),
        processData: false,
        contentType: false,
        beforeSend: function () {
            $('#view-lessons').html("<i class='bx bx-loader bx-spin'></i> Saving ...");
        },
        success: function (data) {
            resetForm();
            if (data != null) {
                generate_table(data)
            }
        },
        error: function() {
            resetForm();
            alert('Error saving data!');
        }
    });
});

// Reset form function
function resetForm() {
    $('#view-lessons').html("<i class='bx bxs-save'></i> Add Lesson");
    $('#lesson-form')[0].reset();
    $('#hidden_header_id').val('');
    $('#refresh').css("display", "none");
    $('.file-info').remove();
    
    // Reset specific fields
    $('#subject').html('<option value="">Select Subject</option>');
    $('#chapter').html('<option value="">Select Chapter</option>');
    $('#lesson_name').html('<option value="">Select Lesson</option>');
}

		function generate_table(data) {
			var html = '';
			if (data.length > 0) {
				for (var count = 0; count < data.length; count++) {
					var next = count + 1;
					// Yes/No fields
					var teached = data[count].stvlmst_teached == "Yes" ? "Yes" : "No";
					var notes = data[count].stvlmst_notes == "Yes" ? "Yes" : "No";
					var exam = data[count].stvlmst_exam_status == "Yes" ? "Yes" : "No";
					// Review text
					var review = data[count].stvlmst_review ? data[count].stvlmst_review : '';
					// Other fields
					var standard = data[count].stvlmst_std || '';  // Ensure these keys exist in your JSON
					var subject = data[count].stvlmst_sub || '';
					var chapter = data[count].chapter || '';
					var startDate = data[count].stvlmst_start_date || '';
					var endDate = data[count].stvlmst_end_date || '';
					var lesson = data[count].stvlmst_lesson || '';
					html += '<tr class="view-lesson-review" data-id="' + data[count].stvlmst_id + '">';
					html += '<td>' + next + '</td>';
					html += '<td>' + lesson + '</td>';
					html += '<td>' + standard + '</td>';
					html += '<td>' + subject + '</td>';
					html += '<td>' + chapter + '</td>';
					html += '<td>' + startDate + '</td>';
					html += '<td>' + endDate + '</td>';
					html += '<td class="text-center">' + teached + '</td>';
					html += '<td class="text-center">' + notes + '</td>';
					html += '<td class="text-center">' + exam + '</td>';
					html += '<td>' + review + '</td>';
					html += '</tr>';
				}
			} else {
				html += '<tr><td colspan="11" class="text-center">No data available</td></tr>';
			}
			$('#lesson-table-body').html(html);
		}
	</script>
</body>

</html>