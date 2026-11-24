<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Student Fees Form</title>
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
		<form action="<?php echo base_url(); ?>Student/add_rte" method="post">
			<div class="pcoded-content">
				<div class="row margin-top-minus-50">
					<?php if ($this->session->userdata('msg') != '') { ?>
						<div class="col-md-12">
							<div class="alert alert-info">
								<?= $this->session->userdata('msg'); ?>
							</div>
						</div>
					<?php } ?>
					<div class="col-md-12 mb-1">
						<button class="btn btn-sm btn-secondary mb-1">Save Data</button>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header pt-0 pb-0 mt-0 mb-0">
								<h5 class="pt-0 pb-0">RTE Students</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group row">
											<label for="" class="col-md-4">Class <span class="float-right">:</span> </label>
											<select class="form-control form-control-sm col-md-6 find-students" name="admission_class" id="admission_class" required>
												<option value="0">Select Class</option>
												<?php
												foreach ($classes as $class) {
												?>
													<option value="<?php echo $class['stvfees_class']; ?>">
														<?php echo $class['stvfees_class']; ?></option>
												<?php } ?>
											</select>
											<input type="hidden" name="total_students" id="total_students">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group row">
											<label for="" class="col-md-4">Division <span class="float-right">:</span> </label>
											<select class="form-control form-control-sm col-md-6 find-students" name="division" id="division" required>
												<option value="0">Select Division</option>
												<option value="A">A</option>
												<option value="B">B</option>
                                                <option value="C">C</option>
											</select>
										</div>
									</div>
								</div>
								<style>
									.table thead tr th,
									.table tbody tr td {
										padding: 5px !important;
										font-size: 12px;
										text-transform: capitalize;
										border: 1px solid #77777740;
									}
								</style>
								<div class="row">
									<div class="col-md-12">
										<table class="table table-sm table-bordered">
											<thead>
												<tr>
													<th class="text-center">#</th>
													<th>ID</th>
													<th>Name</th>
													<th class="text-center">Class</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="tbody">
												<tr>
													<td colspan="5" class="text-center">No data available</td>
												</tr>
											</tbody>
										</table>
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





	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>
	<script>
		$(document).on('change', '.find-students', function() {
			var class_name = $('#admission_class').val();
			var divison = $('#division').val();

			if (class_name != 0) {
				$.ajax({
					url: "<?= base_url('index.php/Student/student_list_by_class') ?>",
					method: "POST",
					data: {
						class: class_name,
						div: divison
					},
					dataType: "JSON",
					success: function(data) {
						if (data != null) {
							var html = '';
							for (var loop = 0; loop < data.length; loop++) {
								var isCheckedYes = '';
								var isCheckedNo = '';
								if (data[loop].rte_status == 'Yes') {
									isCheckedYes = 'checked';
								}else {
									isCheckedNo = 'checked';
								}
								var count = loop + 1;
								html += '<tr>';
								html += '<td class="text-center">' + count;
								html += '<input type="hidden" name="student_id_hidden' + count + '" id="student_id_hidden' + count + '" value="' + data[loop].id + '">';
								html += '</td>';
								html += '<td class="">' + data[loop].SPRIDEN_student_id + '</td>';
								html += '<td>' + data[loop].name + '</td>';
								html += '<td class="text-center">' + data[loop].STVCLAS_standard + ' - ' + data[loop].STVCLAS_class + '</td>';
								html += '<td>';
								html += '<input ' + isCheckedYes + ' type="radio" name="status' + count + '" id="status_yes' + count + '" value="Yes"> Yes';
								html += '<input class="ml-2" ' + isCheckedNo + ' type="radio" name="status' + count + '" id="status_no' + count + '" value="No"> No';
								html += '</td>';
								html += '</tr>';
							}
							$('#tbody').html(html);
							$('#total_students').val(data.length);

						}
					}
				});
			} else {
				alert("Select Class");
			}
		});
	</script>
</body>

</html>
