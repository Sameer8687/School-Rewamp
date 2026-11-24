<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>

<!DOCTYPE html>

<html lang="en">



<head>

	<title>Olive English School - Student Registration Form</title>

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

	<style>
		.pc-container .pcoded-content {
			padding-top: 0px !important;
		}
	</style>

	<!-- [ Main Content ] start -->

	<div class="pc-container">



		<div class="pcoded-content">

			<!-- [ breadcrumb ] start -->

			<!-- [ breadcrumb ] end -->

			<!-- [ Main Content ] start -->

			<form action="<?php echo base_url(); ?>Student/add_visiting_data" enctype="multipart/form-data" method="post">

				<div class="row mobile-margin-top-minus-40 mb-2 mobile-wali-row">

					<div class="col-md-7">

						<button class="btn btn-secondary btn-sm"> <i class="fas fa-user-graduate"></i> <span id="dynamicLabelAddButton">Add Data</span></button>

						<button id="printDataButton" type="button" class="btn btn-sm" style="background-color: #53769c; color: #fff;"> <i class="fas fa-print"></i> Print

							Data</button>

					</div>

					<div class="col-md-5 student-form-search">

						<input type="text" class="form-control form-control-sm" id="search_student_name" placeholder="Search Student Name" autocomplete="off">

						<span class="left-icon"><i class="fas fa-search"></i></span>

						<span class="right-icon" onclick="clearFormInputIcon()"><i class="fas fa-times"></i></span>

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

						<div class="card fees-card">

							<div class="card-header bg-card-header">

								<h5>Student Details</h5>

							</div>

							<div class="card-body pb-2">

								<div class="row margin-top-minus-20">

									<div class="col-md-4">

										<div class="form-group row mobile-margin-top-minus-20">

											<label for="" class="col-md-6 text-right">ID <span class="text-danger">*</span> :</label>

											<input type="text" readonly class="form-control form-control-sm col-md-6" required name="student_id" id="student_id">

											<input type="hidden" class="form-control form-control-sm col-md-7" name="student_id_hidden" id="student_id_hidden" readonly>

											<input type="hidden" class="form-control form-control-sm col-md-7" name="student_row_hidden" id="student_row_hidden" readonly>

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

									<div class="col-md-4">

										<div class="form-group mobile-margin-top-minus-15 row">

											<label for="" class="col-md-6 text-right">Class <span class="text-danger">*</span> :</label>

											<select class="form-control form-control-sm col-md-6" name="admission_class" id="admission_class" required>

												<option value="">Select Class</option>

												<?php

												foreach ($classes as $class) {

												?>

													<option value="<?php echo $class['stvfees_class']; ?>">

														<?php echo $class['stvfees_class']; ?>

													</option>

												<?php } ?>



											</select>

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group mobile-margin-top-minus-15 row">

											<label for="" class="col-md-6 text-right">Amount <span class="text-danger">*</span> :</label>

											<input type="text" class="form-control form-control-sm col-md-6" required name="amount" id="amount">

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group mobile-margin-top-minus-15 row">

											<label for="" class="col-md-6 text-right">Date :</label>

											<input type="text" class="form-control form-control-sm col-md-6" name="date" id="date" value="<?= date('d M Y'); ?>">

										</div>

									</div>

								</div>

								<div class="row margin-top-minus-20 mobile-margin-top-minus-15">

									<div class="col-md-4">

										<div class="form-group row">

											<label for="" class="col-md-6 text-right">Status :</label>

											<!-- <select name="fees_status" id="fees_status" class="form-control form-control-sm col-md-6">

                                                <option selected disabled value="">Select Status</option>

                                                <option value="Full">Full</option>

                                                <option value="Half">Half</option>

                                                <option value="Three Quarter">Three Quarter</option>

                                                <option value="One Quarter">One Quarter</option>

                                            </select> -->

											<input type="text" class="form-control form-control-sm col-md-6" name="fees_status" id="fees_status">

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group  mobile-margin-top-minus-15 row">

											<label for="" class="col-md-6 text-right">Due Date :</label>

											<input type="date" class="form-control form-control-sm col-md-6" name="due_date" id="due_date">

										</div>

									</div>

									<div class="col-md-4">

										<div class="form-group  mobile-margin-top-minus-15 row">

											<label for="" class="col-md-6 text-right">Book Balance :</label>

											<input type="text" class="form-control form-control-sm col-md-6" name="book_fee_due" id="book_fee_due">

										</div>

									</div>

								</div>

								<style>
									#pre-tra-row {

										display: none;

									}
								</style>

								<div class="row" id="pre-tra-row">

									<div class="col-md-12">

										<style>
											.pre-tra {

												margin-bottom: 0;

												color: #525f71;

												font-size: 14px;

												font-weight: 600;

											}
										</style>

										<h5 class="pre-tra ">Previous Transaction</h5>

										<hr>

										<div class="table-responsive">

											<table class="table table-bordered">

												<thead>

													<tr>

														<th>#</th>

														<th>Amount</th>

														<th>Status</th>

														<th>Paid Date</th>

														<th>Due Date</th>

													</tr>

												</thead>

												<tbody id="pre-tra-tab-body">

												</tbody>

											</table>

										</div>

									</div>

								</div>





								<div class="row">

									<div class="col-md-12">



										<div class="dt-responsive table-responsive margin-top-minus-10">

											<table id="simpletable" class="table table-striped table-bordered nowrap">

												<thead>

													<tr>

														<th>No.</th>

														<th>ID</th>

														<th>Student Name</th>

														<th>Class</th>

														<th>Amount</th>

														<th>Date</th>

														<th>Action</th>



													</tr>

												</thead>

												<style>
													#simpletable tbody tr td,

													#printThisTable tbody tr td,

													#printThisTable thead tr th {

														padding: 5px;

														font-size: 12px;

														font-weight: 500;

													}



													#simpletable tbody tr td:hover {

														cursor: pointer;

													}
												</style>

												<tbody id="tbody_printed">

													<?php

													$i = 1;

													foreach ($all_visiting as $visiting) {

													?>

														<tr data-name="<?= $visiting['visiting_student_id']; ?>" class="rowToUpdateData select-print-row<?= $i; ?>">

															<td data-print-id="<?= $i; ?>" class="select-print">

																<?= $i; ?>

															</td>

															<td data-print-id="<?= $i; ?>" class="select-print">

																<?= $visiting['SPRIDEN_student_id']; ?>

															</td>

															<td data-print-id="<?= $i; ?>" class="select-print">

																<?= $visiting['name']; ?>

															</td>

															<td data-print-id="<?= $i; ?>" class="select-print">

																<?= $visiting['STVCLAS_standard'] . " - " . $visiting['STVCLAS_class']; ?>

															</td>

															<td data-print-id="<?= $i; ?>" class="select-print">

																<?= $visiting['visiting_student_fees_paid']; ?>

															</td>

															<td data-print-id="<?= $i; ?>" class="select-print">

																<?= date('d M Y', strtotime($visiting['visiting_date'])); ?>

															</td>

															<td>

																<a data-row-row-id="<?= $visiting['visiting_id']; ?>" data-row-id="<?= $visiting['visiting_student_id']; ?>" href="#" class="text-success edit-record">Edit</a>

																<a data-row-id="<?= $visiting['visiting_id']; ?>" href="#" class="text-danger delete-record">Delete</a>

															</td>



														</tr>

													<?php $i++;
													} ?>

												</tbody>

											</table>

										</div>

									</div>

									<div class="col-md-12 mt-5 d-none">

										<div class="table-responsive">

											<table class="table" id="printThisTable">

												<thead>

													<tr>

														<th>No.</th>

														<th>ID</th>

														<th>Student Name</th>

														<th>Class</th>

														<th>Amount</th>

														<th>Date</th>

														<th>Added By</th>

													</tr>

												</thead>

												<tbody id="tbody_print">

													<!-- <?php

															$i = 1;

															foreach ($all_visiting as $visiting) {

															?>

														<tr data-name="<?= $visiting['visiting_student_id']; ?>" class="rowToUpdateData">

															<td><?= $i; ?></td>

															<td><?= $visiting['SPRIDEN_student_id']; ?></td>

															<td><?= $visiting['name']; ?></td>

															<td><?= $visiting['STVCLAS_standard'] . " - " . $visiting['STVCLAS_class']; ?></td>

															<td><?= $visiting['visiting_student_fees_paid']; ?></td>

															<td><?= date('d M Y', strtotime($visiting['visiting_date'])); ?></td>

															<td><?= $visiting['spruser_name'] . " - " . $visiting['spruser_usertype']; ?></td>



														</tr>

													<?php $i++;
															} ?> -->

												</tbody>

											</table>

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

			</form>

			<!--Filter  Data-->

			<div class="row">

				<div class="col-md-12">

					<div class="accordion" id="accordionExample">

						<div class="card">

							<div class="card-header bg-olive pt-2 pb-2 bg-card-header" id="headingOne">

								<h5 class="mb-0 pre-tra"><a href="#!" class="pre-tra" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Filter Data</a></h5>

							</div>

							<div id="collapseOne" class=" card-body collapse show pb-0 pt-2" aria-labelledby="headingOne" data-parent="#accordionExample">

								<form action="<?= base_url(); ?>visiting" method="POST">

									<div class="row">

										<div class="col-md-4">

											<div class="form-group mobile-margin-top-minus-15 row">

												<label for="" class="col-md-6 text-right">Column Name :</label>

												<select class="form-control form-control-sm col-md-6" name="filter_col" id="filter_col">

													<option value="" selected disabled>Select Column</option>

													<?php

													foreach ($allColumns as $allColumn) {

													?>

														<option value="<?php echo $allColumn['COLUMN_NAME']; ?>">

															<?php echo $allColumn['COLUMN_NAME']; ?>

														</option>

													<?php } ?>



												</select>

											</div>

										</div>

										<div class="col-md-6">

											<div class="form-group  mobile-margin-top-minus-15 row">

												<label for="" class="col-md-4 text-right">Input Data :</label>

												<input type="text" class="form-control form-control-sm col-md-8" name="filter_data" id="filter_data">

											</div>

										</div>

										<div class="col-md-2">

											<button type="submit" name="filterVisiButton" id="sfilterVisitingDataButton" style="background-color: #53769c; color: #fff; border-radius: 30px;" class="btn btn-sm"> <i class="fas fa-search"></i> Search Data</button>

										</div>

									</div>

								</form>

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
		$(document).ready(function() {

			$('#besicwizard').bootstrapWizard({

				withVisible: false,

				'tabClass': 'nav nav-tabs',

				'nextSelector': '.button-next',

				'previousSelector': '.button-previous',

			});

			// -------------------------- Search Student Names  -----------------------------

			$('#search_student_name').keyup(function() {

				var names = $('#search_student_name').val().trim();

				if (names != '') {



					$.ajax({

						url: "<?php echo base_url(); ?>index.php/Student/search_student_names",

						method: "POST",

						data: {

							name: names

						},

						dataType: "JSON",

						success: function(data) {

							if (data != '') {

								var html = '';

								for (var count = 0; count < data.length; count++) {

									html += '<a data-class="' + data[count].STVCLAS_standard + '" id="' + data[count].id +

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

					$('#dynamicLabelAddButton').html("Add Student");

					$('#dynamic_name_list').html('');

					$('.search-list-container-panel').css('display', 'none');

				}

			});



			// Get Details After Clicking on Student Names

			$(document).on('click', '.getDataOfStudent', function() {

				var names = $(this).text();

				var student_ids = $(this).attr('id');

				var class_na = $(this).attr('data-class');



				$('#show-list').html('');

				$.ajax({

					url: "<?php echo base_url(); ?>index.php/Student/get_selected_student_data",

					method: "POST",

					data: {

						student_id: student_ids

					},

					dataType: "JSON",

					success: function(data) {

						if (data != '') {

							//--------- Assign Value to PERONAL DETAILS text box ------ 

							$('#student_id').val(data['info'].SPRIDEN_student_id);

							$('#student_id_hidden').val(data['info'].id);

							$('#student_name').val(data['info'].SPRIDEN_surname + " " + data['info'].SPRIDEN_student_name + " " + data['info'].SPRIDEN_father_name);

							$('#admission_class').val(class_na);

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



			$(document).on('click', '.noDataOfStudent', function() {

				clearFormInputIcon();

				$('#search_student_name').val('');

				$('#dynamicLabelAddButton').html("Add Student");

				$('#dynamic_name_list').html('');

				$('.search-list-container-panel').css('display', 'none');

			});

		});



		function clearFormInputIcon() {

			$('#search_student_name').val('');

			//--------- Assign Value to PERONAL DETAILS text box ------ 

			$('#student_id').val('');

			$('#student_name').val('');

			$('#admission_class').val('');



			//--------- Dynamically Change The LAbels ------

			$('#dynamicLabelAddButton').html("Add Student");

			$('#dynamic_name_list').html('');

			$('.search-list-container-panel').css('display', 'none');



			$('#pre-tra-tab-body').html('');

			$('#pre-tra-row').slideUp();

		}
	</script>



	<script>
		$('#amount').keyup(function() {

			var amountGiven = $('#amount').val();

			if (amountGiven == 4000) {

				$('#fees_status').val("Full");

			} else if (amountGiven == 3000) {

				$('#fees_status').val("Three Quarter");

			} else if (amountGiven == 2000) {

				$('#fees_status').val("Half");

			} else if (amountGiven == 1000) {

				$('#fees_status').val("One Quarter");

			} else {

				$('#fees_status').val('');

			}

		});

		$('.rowToUpdasteData').on('click', function() {

			var rowID = $(this).attr('data-name');

			$.ajax({

				url: "<?= base_url(); ?>index.php/Student/getStudentDataVisitingByID",

				method: "POST",

				dataType: "JSON",

				data: {

					id: rowID

				},

				success: function(data) {

					if (data != "fail") {

						$('#student_id').val(data.stvsubj_std);

						$('#student_id_hidden').val(data.stvsubj_std);

						$('#admission_class').val(data.stvsubj_std);

						$('#amount').val(data.stvsubj_std);

						$('#date').val(data.stvsubj_std);

						$('#class').val(data.stvsubj_std);



						$('#dynamicLabelAddButton').html("Update Data");

					}

				}

			});

		});
	</script>



	<script>
		$(document).ready(function() {

			var dataArray = [];

			var table = $('#simpletable').DataTable();



			// $('#simpletable tbody').on('click', '.select-print', function() {

			// 	var id = $(this).attr("data-print-id");

			// 	//alert(id);

			// 	var class_name = ".select-print-row"+id;

			// 	$(class_name).toggleClass('selected');

			// });



			$('#printDataButton').click(function() {

				var html = '';

				dataArray = [];

				dataArray.push(table.rows('.selected').data());

				var tc = (table.rows('.selected').data().length);

				for (var count = 0; count < tc; count++) {

					var newCount = count + 1;

					html += '<tr>';

					html += '<td>' + newCount + '</td>';

					html += '<td>' + dataArray[0][count][1] + '</td>'

					html += '<td>' + dataArray[0][count][2] + '</td>'

					html += '<td>' + dataArray[0][count][3] + '</td>'

					html += '<td>' + dataArray[0][count][4] + '</td>'

					html += '<td>' + dataArray[0][count][5] + '</td>'

					html += '<td>' + dataArray[0][count][6] + '</td>'

					html += '</tr>';

				}

				//console.log(dataArray[0][0][0]);

				$('#tbody_print').html(html);



				//Print Data Now

				var printme = document.getElementById("printThisTable");

				var wme = window.open("", "", "width=900", "height=700");

				wme.document.write(printme.outerHTML);

				wme.moveTo(0, 0);

				wme.resizeTo(screen.availWidth, screen.availHeight);

				wme.document.close();

				wme.focus();

				wme.print();

				wme.close();

			});

		});



		$('#filterVisitingDataButton').click(function() {

			var col_name = $('#filter_col').val();

			var input_data = $('#filter_data').val().trim();

			if (col_name == null && input_data == "") {

				alert("Select Column Name & Enter Input Data");

				return false;

			} else if (col_name != null && input_data == "") {

				alert("Enter Input Data");

				return false;

			} else if (col_name == null && input_data != "") {

				alert("Select Column Name");

				return false;

			} else {

				$.ajax({

					url: "<?= base_url(); ?>index.php/Student/filterVisitingData",

					method: "POST",

					dataType: "JSON",

					data: {

						col_name: col_name,

						input_data: input_data

					},

					success: function(data) {

						if (data != "fail") {

							var html = '';

							for (var count = 0; count < data.length; count++) {

								var newCount = count + 1;

								html += '<tr>';

								html += '<td>' + newCount + '</td>';

								html += '<td>' + data[count].SPRIDEN_student_id + '</td>';

								html += '<td>' + data[count].name + '</td>';

								html += '<td>' + data[count].STVCLAS_standard + ' - ' + data[count].STVCLAS_class + '</td>';

								html += '<td>' + data[count].visiting_student_fees_paid + '</td>';

								html += '<td>' + data[count].visiting_date + '</td>';

								html += '<td>' + data[count].spruser_name + ' - ' + data[count].spruser_usertype + '</td>';

								html += '</tr>';

							}

							$('#tbody_printed').html(html);

						}

					}

				});

			}

		})
	</script>



	<script>
		$(document).on('click', '.edit-record', function() {

			var id = $(this).attr("data-row-id");

			var rowid = $(this).attr("data-row-row-id");

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/Student/getVisitingDataForUpdate",

				data: {

					id: id,

					row_id: rowid

				},

				dataType: "JSON",

				method: "POST",

				success: function(data) {

					if (data != "fail") {

						$('#student_id').val(data['info'].SPRIDEN_student_id);

						$('#student_id_hidden').val(data['info'].id);

						$('#student_name').val(data['info'].name);

						$('#admission_class').val(data['info'].STVCLAS_standard);

						$('#amount').val(data['visiting_data'].visiting_student_fees_paid);

						$('#fees_status').val(data['visiting_data'].visiting_fees_status_of);

						$('#due_date').val(data['visiting_data'].visiting_fees_due_date);

						$('#book_fee_due').val(data['visiting_data'].visiting_book_fees);

						$('#student_row_hidden').val(data['visiting_data'].visiting_id);

						$('#dynamicLabelAddButton').html("Update Data");

						//alert(data['visiting_data'].visiting_book_fees);





						//display in table

						var htmlTable = '';

						for (var count = 0; count < data['visiting_rows'].length; count++) {

							var newCount = 1;

							htmlTable += '<tr>';

							htmlTable += '<td>' + newCount + '</td>';

							htmlTable += '<td>' + data['visiting_rows'][count].visiting_student_fees_paid + '</td>';

							htmlTable += '<td> ' + data['visiting_rows'][count].visiting_fees_status_of + ' </td>';

							htmlTable += '<td>' + data['visiting_rows'][count].visiting_date + '</td>';

							htmlTable += '<td>' + data['visiting_rows'][count].visiting_fees_due_date + '</td>';

							htmlTable += '</tr>';

						}







						$('#pre-tra-tab-body').html(htmlTable);

						$('#pre-tra-row').slideDown();

					}

				}

			});

		});



		$(document).on('click', '.delete-record', function() {

			var id = $(this).attr("data-row-id");



			if (confirm("Do you want to delete data")) {

				$.ajax({

					url: "<?php echo base_url(); ?>index.php/Student/deleteVisitingDataByID",

					data: {

						id: id



					},

					method: "POST",

					success: function(data) {

						if (data != "fail") {

							alert("Data Deleted SUccessfully !!!");

							window.location.reload();

						} else {

							alert("Failed to delete data !!!");

							window.location.reload();

						}

					}

				});

			} else {



			}

		});
	</script>

</body>



</html>