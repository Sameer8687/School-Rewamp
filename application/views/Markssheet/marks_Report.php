<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Subject Master</title>
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
		<form action="<?php echo base_url(); ?>index.php/Marks/all_exams" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->

				<!-- [ breadcrumb ] end -->

				<!-- [ Main Content ] start -->

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
				<div class="row margin-top-minus-40 mobile-margin-top-minus-10" style="margin-right:0px; ">
					<div class="col-md-12">
						<div class="card pb-0 mb-0">
							<div class="card-header mb-0 pb-2 ">
								<h5 class="margin-top-minus-10 mb-0 pb-0"></h5>

								<div class="row w-100">
									<div class="col-md-6">
										<h5 class="pt-2">Marks-Report</h5>
									</div>
									<div class="col-md-6">
									<button class="btn btn-secondary btn-sm float-right"> <i class="fas fa-bars"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
									</div>
								</div>

							</div>
							<div class="card-body pb-0 mb-0">
								<style>
									label {
										text-align: right;

									}

									.no-margin {
										margin-top: -10px;
									}
								</style>
								<div class="row" style="margin-right: 10px;">
									<div class="col-md-4">
										<div class="form-group row">
											<label for="" class="col-md-5">Exam Name<span class="text-danger">*</span>
												:</label>
											<select name="exam" id="exam" class="form-control form-control-sm col-md-6">
												<option value="">Select exam</option>

												<?php
												foreach ($exams as $exam) :
												?>
													<option value="<?= $exam['stvexam_id']; ?>">
														<?= $exam['stvexam_name']; ?></option>
												<?php endforeach; ?>

											</select>
										</div>
										<!-- <div class="select-box" style="width: 65%;">
                                        </div> -->
									</div>
									<div class="col-md-4">
										<div class="form-group row"> <label for="" class="col-md-5">STD<span class="text-danger">*</span>
												:</label>
											<select class="form-control form-control-sm col-md-6" name="class" id="class" required>
												<option value="">Select STD</option>
												<?php
												foreach ($classes as $class) {
												?>
													<option value="<?= $class['stvfees_class']; ?>">
														<?= $class['stvfees_class']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group row">
											<label for="" class="col-md-5">Division<span class="text-danger">*</span>
												:</label>
											<select required class="form-control form-control-sm col-md-6" name="division" id="division">
												<option selected disabled value="">Select Division
												</option>
												<option value="A">A</option>
												<option value="B">B</option>
												<option value="C">C</option>
											</select>
										</div>
									</div>





									<!-- <?php print_r($sub['0']); ?> -->

								</div>
								<div class="row" style="margin-right: 10px;">






								</div>
							</div>
							<!-- [ Search List ] Start -->
							<div class="search-list-container-panel">
								<div class="list-group" id="dynamic_name_list"></div>
							</div>
							<!-- [ Search List ] End -->
						</div>
					</div>
				</div>
				<div class="row mt-1" style="margin-right: 0px;">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<!-- <input type="hidden" name="total_marks" id="total_marks"
                                            value="<?= $stats != null ? $stats['marks'] : 0 ?>">
                                        <input type="hidden" name="marks" id="marks"
                                            value="<?= $stats != null ? count($stats['records']) : 0 ?>"> -->
										<div class="dt-responsive table-responsive margin-top-minus-10">
											<table id="simpletable" class="table table-striped table-bordered nowrap">
												<thead>

													<tr>
														<th>Sr.No.</th>
														<th>Stud.Id</th>
														<th>Student Name</th>
														<th>Result</th>

														<!-- <?php if ($stats != null) {
																	for ($ins_loop = 1; $ins_loop <= $stats['last_installment']; $ins_loop++) { ?>
                                                        <th>Ins
                                                            <?= $ins_loop; ?></th>
                                                        <?php }
																} ?> -->
													</tr>
												</thead>
												<style>
													#simpletable tbody tr td {
														padding: 5px;
														font-size: 12px;
														font-weight: 500;
													}

													#simpletable tbody tr td:hover {
														cursor: pointer;
													}
												</style>
												<tbody id="stvstuDetails">



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
		</form>
		<!-- [ Main Content ] end -->

	</div>
	</div>
	<!-- [ Main Content ] end -->
	</div>
	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>
	<script>
		$('.rowToUpdateData').on('click', function() {

			var rowID = $(this).attr('data-name');

			$.ajax({

				url: "<?= base_url(); ?>index.php/MasterForm/getSubjectDataByID",

				method: "POST",

				dataType: "JSON",

				data: {

					id: rowID

				},

				success: function(data) {

					if (data != "fail") {

						$('#subject').val(data.stvsubj_subject);

						$('#school_id').val(rowID);

						$('#class').val(data.stvsubj_std);

						$('#dynamicLabelAddButton').html("Update Data")
					}
				}
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$('dynamicLabelAddButton').on('click', function() {
				$("dynamicLabelAddButton").attr("disabled", "disabled");
				var exam = $('exam').val();
				var stu = $('class').val();
				var subject = $('subject').val();
				var marks = $('marks').val();
				if (exam != "" && stu != "" && subject != "" && marks != "") {
					$.ajax({
						url: "<?= base_url(); ?>index.php/Marks/all_exams",

						type: "POST",

						data: {
							exam: exam,
							stu: stu,
							subject: subject,
							marks: marks
						},
						cache: false,
						success: function(data) {

							if (data != "fail") {

								$('#subject').val(data.stvsubj_subject);

								$('#school_id').val(rowID);

								$('#class').val(data.stvsubj_std);

								$('#dynamicLabelAddButton').html("Update Data")
							}
						}
					});
				}
			});
		});
	</script>

	<script>
		// $('#class').change(function() {

		//     var std = $('#class').val();

		//     $.ajax({

		//         url: "<?= base_url('index.php/Marks/getAllSubjetcsBystd') ?>?std=" + std,

		//         method: "GET",

		//         dataType: "JSON",

		//         success: function(data) {

		//             if (data != null) {

		//                 var html = '<option>Select Subject</option>';

		//                 for (var count = 0; count < data.length; count++) {

		//                     html += '<option value="' + data[count].stvsubj_id + '">' + data[count]
		//                         .stvsubj_subject + '</option>';

		//                 }

		//                 $('#subject').html(html);

		//             }

		//         }



		//     })

		// });





		$('#division').change(function() {
			var exam = $("#exam").val();
			var div = $('#division').val();
			var std = $('#class').val();



			$.ajax({

				url: "<?= base_url('index.php/Marks/getClassStd') ?>?std=" + std +
					"&div=" + div,

				method: "GET",

				dataType: "JSON",

				success: function(data) {

					if (data != null) {

						var html = '';
						var index = 1;

						for (var count = 0; count < data.length; count++) {

							html += `<tr>
                        <td>${index++}</td>
                        <td>${data[count].STVCLAS_student_id}</td>;
                        <td>${data[count].name}</td>
                        <td><a href='<?= base_url('index.php/Result/Marksheet') ?>?id=${data[count].STVCLAS_student_id}&exam=${exam}' class="badge bg-info">Marksheet</a></td>
                       </tr>`;

						}

						$('#stvstuDetails').html(html);


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

					url: "<?= base_url('index.php/Marks/all_lessons') ?>?class=" + std + "&sub=" +
						sub,

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
	</script>
</body>



</html>