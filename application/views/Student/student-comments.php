<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Olive English School - Issue LC</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Phoenixcoded" />
	<!---Include CSS Files-->
	<?php include 'application/views/cssPages.php'; ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/id-card.css" />
	<style>
		.pc-container {
			top: 0px !important;
		}

		.border {
			border: 1px solid;
		}

		.table tr td,
		.table tr th {
			padding: 5px !important;
			font-size: 11px !important;
			cursor: pointer;
		}

	</style>
</head>

<body class="">
	<div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>

	<?php include 'application/views/sidebar.php'; ?> <?php include
			'application/views/topNav.php'; ?>
	<div class="pc-container">
		<div class="pcoded-content">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="col-md-6">
								<h5 class="card-title">Student Comments</h5>
							</div>
							<div class="col-md-6">

								<button class="btn btn-secondary btn-sm float-right" id="save-btn" type="submit"> <i class="fas fa-user-graduate"></i> <span id="dynamicLabelAddButton">Save Details</span></button>
											

							</div>
						</div>
						<div class="card-body">
							<div class="print-container">
								<div class="col-md-12">
									<div class="row mb-3">
										<div class="col-md-4">
											<div class="row">
												<label for="" class="col-md-5">Class
													<span class="float-right">:</span></label>

												<select name="std" id="std" class="form-control form-control-sm col-md-7">
												<option value="">Select Class</option>
												<?php foreach ($classes as $clas) { ?>
													<option value="<?= $clas['stvfees_class'] ?>"><?= $clas['stvfees_class'] ?></option>
												<?php } ?>
											</select>
											<input type="hidden" name="max_rows" id="max_rows">

											</div>
										</div>

										<div class="col-md-4">

											<button id="find-students" type="button" class="btn btn-sm btn-outline-secondary">View Class</button>


										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="dt-responsive table-responsive margin-top-minus-10">
										<table id="simpletable" class="table table-striped table-bordered nowrap">
											<thead>
												<tr>
														<th style="width: 5%;">#</th>
														<th style="width: 10%;">Student ID</th>
														<th style="width: 30%;">Student Name</th>
														<th style="width: 6%;">Class</th>
														<th style="width: 40%;">Comments</th>
														<th style="width: 10%;">Date</th>
													</tr>
											</thead>

											<tbody id="tbody">
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
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
		</div>
	</div>

	<!---Include JS Files-->
	<?php include 'application/views/jsPages.php'; ?>
	<script>
		document.getElementById("find-students").addEventListener('click', async () => {
			var html = '';
			let std = document.getElementById("std").value;
			let base_url = '<?= base_url(); ?>';
			const res = await fetch(`${base_url}index.php/Student/student_comments_api?std=${std}`);
			const data = await res.json();
			const length_of_data = data.length;
			if (data) {
				var counter = 0;
				data.forEach(element => {
					var cmnt = element.comments == '' || element.comments == null ? '' : element.comments;
					counter++;
					html += `<tr>
									<td>${counter}</td>
													<td style="padding: 0px !important;">
													<input type="text" readonly class="form-control form-control-sm border-0" name="student_id${counter}" id="student_id${counter}" value="${element.SPRIDEN_student_id}">
													<input type="hidden" readonly class="form-control form-control-sm border-0" name="sid${counter}" id="sid${counter}" value="${element.id}">
													</td>
													<td style="padding: 0px !important;">
														<input type="text" readonly class="form-control form-control-sm border-0" name="student_name${counter}" id="student_name${counter}" value="${element.name}">
													</td>
													<td style="padding: 0px !important;">
														<input type="text" readonly class="form-control form-control-sm border-0" name="class${counter}" id="class${counter}" value="${element.STVCLAS_standard}-${element.STVCLAS_class}">
													</td>
													<td style="padding: 0px !important;">
														<input type="text"  class="form-control form-control-sm border-0" name="comments${counter}" id="comments${counter}" value="${cmnt}">
													</td>
													<td style="padding: 0px !important;">
														<input type="date"  class="form-control form-control-sm border-0" name="cdate${counter}" id="cdate${counter}" value="${element.cdate}">
													</td>
												</tr>`;
				});
				document.getElementById("tbody").innerHTML = html;
				document.getElementById("max_rows").value = length_of_data;
				if (data.length >= 25) {
					document.getElementById("tholder").style.height = "500px";
					document.getElementById("tholder").style.overflowY = "scroll";
				}
			}

		});
		$('#form_data_data').on('submit', function(e) {
			var btn_text = document.getElementById("save-btn").innerHTML;
			e.preventDefault();
			$.ajax({
				url: "<?= base_url() ?>index.php/Student/add_stu_cmnt_dt",
				method: "POST",
				data: new FormData(this),
				processData: false,
				contentType: false,
				beforeSend: function() {
					document.getElementById("save-btn").innerHTML = "<i class='bx bx-loader bx-spin'></i> Please Wait ...";
				},
				success: function(res) {
					document.getElementById("save-btn").innerHTML = btn_text;
					if (res != 'fail') {
						alert("Details Saved");
					}
				}
			})
		})
	</script>
</body>

</html>