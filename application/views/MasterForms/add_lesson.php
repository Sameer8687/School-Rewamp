<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Lesson Master</title>
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
		<form action="<?php echo base_url(); ?>MasterForm/add_lesson" method="post">
			<div class="pcoded-content">
				<!-- [ breadcrumb ] start -->

				<!-- [ breadcrumb ] end -->
				<!-- [ Main Content ] start -->

				<div class="row mb-2">

					<div class="col-md-7">
						<!-- <a href="<?php echo base_url(); ?>" class="btn btn-danger btn-sm"> <i class="fas fa-arrow-alt-circle-left"></i> Back</a> -->
						

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
				<div class="row margin-top-minus-40 mobile-margin-top-minus-10">
					<div class="col-md-12">
						<div class="card pb-0 mb-0">
							<div class="card-header mb-0 pb-2 ">

								<div class="row w-100">
									<div class="col-md-6">
										<h5 class="pt-2">Add Subject</h5>
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
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<label for="" class="col-md-5">Standard<span class="text-danger">*</span> :</label>
											<select class="form-control form-control-sm col-md-7" name="class" id="class" required>
												<option selected disabled value="">Select Standard</option>
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
											<label for="" class="col-md-5">Subject<span class="text-danger">*</span> :</label>
											<select autocomplete="off" class="form-control form-control-sm col-md-7" name="subject" id="subject" required>

											</select>
											<input type="hidden" class="form-control form-control-sm col-md-7" name="lesson_id" id="lesson_id">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<label for="" class="col-md-5">Lesson<span class="text-danger">*</span> :</label>
											<input type="text" autocomplete="off" class="form-control form-control-sm col-md-7" name="lesson" id="lesson" required>

										</div>
									</div>


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
				<div class="row mt-1">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="dt-responsive table-responsive margin-top-minus-10">
											<table id="simpletable" class="table table-striped table-bordered nowrap">
												<thead>
													<tr>
														<th>Sr.No.</th>
														<th>Standard</th>
														<th>Subject</th>
														<th>Lesson Name</th>
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
												<tbody>
													<?php
													$i = 1;
													foreach ($lessons as $lesson) {
													?>
														<tr data-name="<?= $lesson['stvlmst_id']; ?>" class="rowToUpdateData">
															<td><?= $i; ?></td>
															<td><?= $lesson['stvlmst_std']; ?></td>
															<td><?= $lesson['stvsubj_subject']; ?></td>
															<td><?= $lesson['stvlmst_lesson'] ?></td>

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
				url: "<?= base_url(); ?>index.php/MasterForm/getLessonDataByID",
				method: "POST",
				dataType: "JSON",
				data: {
					id: rowID
				},
				success: function(data) {
					if (data['main'] != "fail") {
						var html = '<option>Select Subject</option>';
						for (var count = 0; count < data['sub'].length; count++) {
							html += '<option value="' + data['sub'][count].stvsubj_id + '">' + data['sub'][count].stvsubj_subject + '</option>';
						}
						$('#subject').html(html);
						$('#subject').val(data['main'].stvlmst_sub);
						$('#lesson_id').val(rowID);
						$('#lesson').val(data['main'].stvlmst_lesson);
						$('#class').val(data['main'].stvlmst_std);

						$('#dynamicLabelAddButton').html("Update Data")
					}
				}
			});
		});

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
	</script>
</body>

</html>