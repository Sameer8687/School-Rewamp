<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Olive English School - Student Books & Result Form</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />
	<?php include 'application/views/cssPages.php'; ?>
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
		<form action="" id="classCreateForm" method="post">
			<div class="pcoded-content">
				<div class="row margin-top-minus-40  mb-2">
					<div class="col-md-7">
						<button class="btn btn-secondary btn-sm" type="submit" id="createClassButton"> <i
								class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span></button>
					</div>
					<div class="col-md-5 student-form-search">
						<input type="text" class="form-control form-control-sm" id="search_student_name"
							placeholder="Search Student Name" autocomplete="off">
						<span class="left-icon"><i class="fas fa-search"></i></span>
						<span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>
					</div>
				</div>
				<div class="row" id="alertRow">
					<div class="col-md-12">
						<?php $message = $this->session->userdata('message');
						$alert_class = $this->session->userdata('alert_class');
						if ($message != "") { ?>
							<div class="alert <?= $alert_class; ?>">
								<?= $message; ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-body">
								<h5 class="mb-3 margin-top-minus-10">Create / Update Class </h5>
								<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active " id="home-tab" data-toggle="tab" href="#home"
											role="tab" aria-controls="home" aria-selected="true">Create New Class</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
											role="tab" aria-controls="profile" aria-selected="false">Update Student Class</a>
									</li>
									<li class="nav-item">
										<a class="nav-link " id="class-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Update Mass Class</a>
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
									<div class="tab-pane fade show active" id="home" role="tabpanel"
										aria-labelledby="home-tab">
										<div class="row justify-content-center">
											<div class="col-md-11">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group row">
															<label for="" class="col-md-5">Standard<span
																	class="text-danger">*</span> :</label>
															<select required class="form-control form-control-sm col-md-7" name="class" id="class">
																<option selected disabled value="">Select Class</option>
																<?php foreach ($classes as $class) { ?>
																	<option value="<?php echo $class['stvfees_class']; ?>"><?php echo $class['stvfees_class']; ?></option>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group row">
															<label for="" class="col-md-5">Division<span
																	class="text-danger">*</span> :</label>
															<select required class="form-control form-control-sm col-md-7" name="division" id="division">
																<option selected disabled value="">Select Division </option>
																<option value="A">A</option>
																<option value="B">B</option>
																<option value="C">C</option>
															</select>
														</div>
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
																<th>ID</th>
																<th>Student Name</th>
																<th>Admission Class</th>
																<th>Status</th>
															</tr>
														</thead>
														<tbody id="tbody">
															<?php $i = 1; foreach ($all_non_class_students as $all_non_class_st) { ?>
																<tr>
																	<td><?= $i; ?></td>
																	<td><?= $all_non_class_st['SPRIDEN_student_id'] ?></td>
																	<td><?= $all_non_class_st['SPRIDEN_surname'] . " " . $all_non_class_st['SPRIDEN_student_name'] . " " . $all_non_class_st['SPRIDEN_father_name'] ?>
																	</td>
																	<td><?= $all_non_class_st['SPRIDEN_admission_class'] ?>
																	</td>
																	<td><input type="checkbox" value="<?= $all_non_class_st['id'] ?>" name="student_row[]" id="" class="studentRowSelector"></td>
																</tr>
																<?php $i++;
															} ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade " id="profile" role="tabpanel"
										aria-labelledby="profile-tab">
										<div class="row margin-top-minus-0">
											<div class="col-md-4">
												<div class="form-group row mobile-margin-top-minus-20">
													<label for="" class="col-md-6 text-right">ID <span class="text-danger">*</span> :</label>
													<input type="text" readonly class="form-control form-control-sm col-md-6" name="student_id" id="student_id">
													<input type="hidden" class="form-control form-control-sm col-md-7" name="student_id_hidden" id="student_id_hidden" readonly>
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group row mobile-margin-top-minus-15">
													<label for="" class="col-md-3 text-right">Student Name :</label>
													<input type="text" readonly class="form-control form-control-sm col-md-9" name="student_name" id="student_name">
												</div>
											</div>
										</div>
										<div class="row margin-top-minus-20">
											<div class="col-md-3">
												<div class="form-group mobile-margin-top-minus-15 row">
													<label for="" class="col-md-6 text-right">Select Class <span
															class="text-danger">*</span> :</label>
													<select class="form-control form-control-sm col-md-6"
														name="update_class" id="update_class">
														<option value="" selected disabled>Select Class</option>
														<?php foreach ($classes as $class) { ?>
															<option value="<?php echo $class['stvfees_class']; ?>"> <?php echo $class['stvfees_class']; ?>
															</option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group mobile-margin-top-minus-15 row">
													<label for="" class="col-md-6 text-right">Select Division :</label>
													<select class="form-control form-control-sm col-md-6"
														name="update_divi" id="update_divi">
														<option value="" selected disabled>Select Division</option>
														<option value="A">A</option>
														<option value="B">B</option>
														<option value="C">C</option>
													</select>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group mobile-margin-top-minus-15 row">
													<label for="" class="col-md-6 text-right">Select Year :</label>
													<select class="form-control form-control-sm col-md-6" name="update_year" id="update_year">
														<option value="" selected disabled>Select Year</option>
														<?php foreach ($all_years as $year) { ?>
															<option value="<?= $year['stvacdm_id']; ?>">
																<?= $year['stvacdm_year']; ?>
															</option>
														<?php } ?>
													</select>
												</div>
											</div>

											<div class="col-md-3">
												<button id="updateStudentClassButton" class="btn btn-sm btn-olive"> <i class="fas fa-user-edit"></i> Update Class</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="search-list-container-panel">
								<div class="list-group" id="dynamic_name_list"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>
	<script>
		$(document).ready(function () {
			var student_ids = [];
			$('#selectAll').click(function () {
				var isChecked = $(this).prop("checked");
				$('#studentTable tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
			});
			$('#classCreateForm').on('submit', function (e) {
				student_ids = [];
				var std = $('#class option:selected').val();
				var division = $('#division option:selected').val();
				e.preventDefault();
				$('#studentTable').find('input[type="checkbox"]:checked').each(function () {
					student_ids.push($(this).val());
				});
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Student/createNewClass",
					data: {
						student_ids: student_ids,
						std: std,
						division: division
					},
					method: "POST",
					beforeSend: function () {
						$('#createClassButton').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
					},
					success: function (data) {
						$('#createClassButton').html('<i class="fas fa-book"></i> <span id="dynamicLabelAddButton">Add Data</span>');
						if (data != "fail") {
							alert("Class Created Successfully !!!");
							window.location.reload();
						} else {
							swal("Failed to create Class");
						}
					}
				});
			});
		});
	</script>
	<script>
		$(document).ready(function () {
			// -------------------------- Search Student Names  -----------------------------
			$('#search_student_name').keyup(function () {
				var names = $('#search_student_name').val().trim();
				if (names != '') {
					$.ajax({
						url: "<?php echo base_url(); ?>index.php/Student/search_student_names",
						method: "POST",
						data: {
							name: names
						},
						dataType: "JSON",
						success: function (data) {
							if (data != '') {
								var html = '';
								for (var count = 0; count < data.length; count++) {
									html += '<a data-div="' + data[count].STVCLAS_class + '" data-class="' + data[count].STVCLAS_standard + '" id="' + data[count].id +
										'" href="#" style="border:none; border-bottom: 1px solid #77777720; border-radius: 0px; padding: 5px; font-size: 12px; font-weight:bold;" class="list-group-item list-group-item-action getDataOfStudent"> <span class="text-info">' +
										data[count].SPRIDEN_surname + " " + data[count]
											.SPRIDEN_student_name + " " + data[count]
											.SPRIDEN_father_name +
										'</span><span class="text-danger"> ( Standard :  ' +
										data[count].STVCLAS_standard + ' ) </span>' +
										'</a>';
								}
								$('#dynamic_name_list').html(html);
								$('.search-list-container-panel').css('display', 'block');
							} else {
								var notAvailable =
									'<a href="#" style="border-radius: 0px; border:none; padding: 5px; font-size: 12px; font-weight:bold;" class="list-group-item list-group-item-action noDataOfStudent"> <span class="text-info">Data Not Available</span></a>';
								$('#dynamic_name_list').html(notAvailable);
							}
						}
					});
				} else {
					clearFormInputIcon();
					$('#dynamic_name_list').html('');
					$('.search-list-container-panel').css('display', 'none');
				}
			});

			// Get Details After Clicking on Student Names
			$(document).on('click', '.getDataOfStudent', function () {
				var names = $(this).text();
				var student_ids = $(this).attr('id');
				var class_na = $(this).attr('data-class');
				var class_div = $(this).attr('data-div');
				$('#show-list').html('');
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Student/get_selected_student_data",
					method: "POST",
					data: {
						student_id: student_ids
					},
					dataType: "JSON",
					success: function (data) {
						if (data != '') {
							//--------- Assign Value to PERONAL DETAILS text box ------ 
							$('#student_id').val(data['info'].SPRIDEN_student_id);
							$('#student_id_hidden').val(data['info'].id);
							$('#student_name').val(data['info'].SPRIDEN_surname + " " + data['info'].SPRIDEN_student_name + " " + data['info'].SPRIDEN_father_name);
							$('#update_class').val(class_na);
							$('#update_divi').val(class_div);
							$('#dynamic_name_list').html('');
							$('.search-list-container-panel').css('display', 'none');
							//Display Previous Transaction
							var htmlTable = '';
							if (data['visiting'] == "") {
								htmlTable += '<tr>';
								htmlTable += '<td colspan="5" class="text-center">No Transaction</td>';
								htmlTable += '</tr>';
							} else {
								for (var count = 0; count < data['visiting'].length; count++) {

									var newCount = count + 1;
									htmlTable += '<tr>';
									htmlTable += '<td>' + newCount + '</td>';
									htmlTable += '<td>' + data['visiting'][count].visiting_student_fees_paid + '</td>';
									htmlTable += '<td>' + data['visiting'][count].visiting_fees_status_of + '</td>';
									htmlTable += '<td>' + data['visiting'][count].visiting_date + '</td>';
									htmlTable += '<td>' + data['visiting'][count].visiting_fees_due_date + '</td>';
									htmlTable += '</tr>';
								}
							}
							$('#pre-tra-tab-body').html(htmlTable);
							$('#pre-tra-row').slideDown();
						} else {
							clearFormInputIcon();
							$('#dynamic_name_list').html('');
							$('.search-list-container-panel').css('display', 'none');
						}
					}
				});
			});
			$(document).on('click', '.noDataOfStudent', function () {
				clearFormInputIcon();
				$('#search_student_name').val('');
				$('#dynamic_name_list').html('');
				$('.search-list-container-panel').css('display', 'none');
			});
		});

		function clearFormInputIcon() {
			$('#search_student_name').val('');
			//--------- Assign Value to PERONAL DETAILS text box ------ 
			$('#student_id').val('');
			$('#student_name').val('');
			$('#update_class').val('');
			$('#update_divi').val('');
			$('#student_id_hidden').val('');
			//--------- Dynamically Change The LAbels ------
			$('#dynamic_name_list').html('');
			$('.search-list-container-panel').css('display', 'none');
			$('#pre-tra-tab-body').html('');
			$('#pre-tra-row').slideUp();
		}

		$('#updateStudentClassButton').click(function () {
			var student_id = $('#student_id_hidden').val();
			var std = $('#update_class').val();
			var divi = $('#update_divi').val();
			var year = $('#update_year').val();
			if (student_id == "") {
				alert("Select Student");
				return false;
			} else {
				$.ajax({
					url: "<?php echo base_url(); ?>index.php/Student/updateStudentClass",
					data: {
						student_id: student_id,
						std: std,
						divi: divi,
						year: year
					},
					method: "POST",
					beforeSend: function () {
						$('#updateStudentClassButton').html('<i class="fas fa-spinner fa-spin"></i> Please Wait');
					},
					success: function (data) {
						$('#updateStudentClassButton').html('<i class="fas fa-user-edit"></i> Update Class</span>');
						if (data != "fail") {
							alert("Class Updated Successfully !!!");
							clearFormInputIcon();
						} else {
							swal("Failed to create Class");
						}
					}
				});
			}
		});
	</script>
</body>
</html>