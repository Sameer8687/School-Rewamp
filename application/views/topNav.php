<style type="text/css" media="print">
	@media print {

		.no-print {

			display: none !important;

		}

	}

	#showLoginStatusPre {

		color: #fff;

		font-size: 12px;

	}

	#showLoginStatusPre:hover {

		background-color: transparent;

	}

	.list-button {

		font-size: 10px;

	}

	.custom-pc-header {
		min-height: 33px !important;
	}
</style>

<!-- [ Header ] start -->

<div class="no-print">

	<header class="pc-header custom-pc-header ">


		<div class="ml-auto no-print" style="margin-bottom: 0px;">

			<div class="header-wrapper p-0  m-0">

				<ul class="list-unstyled p-0 margin-top-minus-20 no-print">


					<li class="dropdown pc-h-item no-print" style="position: absolute; left: 5px; top: 1px;">

						<!-- ------------Ansari Kamran-------------- -->
						<?php
						if (isset($filter_view)) {
							include 'application/views/filters/' . $filter_view . '.php';
						}
						?>

						<!-- <span style="margin-right: 20px; cursor: pointer;" class="go-back no-print">
							<h3 style="color:#fff;"> All Status Report</h3>

						</span> -->



					</li>
					<!-- ----------------Ansari Kamran------------------ -->
					<!-- ----------------09-10-23------------------ -->

					<?php if ($this->router->fetch_class() == 'welcome') { ?>

						<!-- ─── Toggle Old / New Dashboard ─── -->
						<li class="pc-h-item no-print" style="position: absolute; right: 280px; top: -3px;">
							<?php if ($this->router->fetch_method() == 'dashboard_new'): ?>
								<a href="<?= base_url('welcome') ?>" class="btn-sm"
									style="color:#fff; white-space: nowrap; padding: 4px 10px;">
									<i class="fas fa-arrow-left"></i> Main Dashboard
								</a>
							<?php else: ?>
								<a href="<?= base_url('welcome/dashboard_new') ?>" class="btn-sm"
									style="color:#fff; white-space: nowrap; padding: 4px 10px;">
									<i class="fas fa-star"></i> New Dashboard
								</a>
							<?php endif; ?>
						</li>

						<li class="pc-h-item no-print" style="position: absolute; right: 160px; top: -3px;">

							<!-- <?php $selected_year = $this->session->userdata('academic_year'); ?> -->

							<select name="academic_year" id="academic_year" class="form-control form-control-sm"
								onchange="changeAcademicYear(this.value)">

								<option value="">Select Year</option>

								<?php foreach ($all_acdm_years as $all): ?>

									<option value="<?= $all['stvacdm_id']; ?>" <?= ($selected_year == $all['stvacdm_id']) ? 'selected' : ''; ?>>

										<?= $all['stvacdm_year']; ?>

									</option>

								<?php endforeach; ?>

							</select>

						</li>

					<?php } ?>

					<?php if (isset($all_due_records) && count($all_due_records) > 0): ?>
						<li class="dropdown pc-h-item no-print" class="list-button" data-toggle="modal"
							data-target="#staticBackdrop" style="position: absolute; right: 114px; top: -3px;">

							<a class="pc-head-link dropdown-toggle arrow-none mr-0" href="#" id="due-date-counter">

								<span class="badge badge-danger"
									id="total-due-date-counter"><?= count($all_due_records) ?></span>
								<i data-feather="bell" class="text-white"></i>

							</a>
						</li>
					<?php endif; ?>



					<li class="dropdown pc-h-item no-print"
						style="position: absolute; right: 60px; top: -3px;font-family: emoji !important; ">

						<a href="<?php echo base_url(); ?>" class="btn-sm" style="color: #fff;"> <i
								class="fas fa-arrow-alt-circle-left"></i> Back</a>

					</li>


					<li class="dropdown pc-h-item no-print" class="list-button"
						style="position: absolute; right: 30px; top: -4px;">

						<a class="pc-head-link dropdown-toggle arrow-none mr-0" href="#" id="getFullScreenMode">

							<i data-feather="minimize" class="text-white"></i>

						</a>

					</li>

					<li class="dropdown pc-h-item no-print" class="list-button"
						style="position: absolute; right: 0; top: -4px;">

						<a class="pc-head-link dropdown-toggle arrow-none mr-0" href="#" data-toggle="modal"
							data-target="#notification-modal" id="searchInputButtonFormName" aria-haspopup="false"
							aria-expanded="false">

							<i data-feather="search" class="text-white"></i>

						</a>

					</li>

				</ul>

			</div>

		</div>

	</header>

	<script>
		function changeAcademicYear(year) {

			$.ajax({
				url: "<?= base_url('welcome/setAcademicYear'); ?>",
				type: "POST",
				data: {
					academic_year: year
				},
				dataType: "json",
				success: function (response) {

					if (response.status) {
						//location.reload();
					} else {
						alert(response.message);
					}
				}
			});
		}
	</script>

</div>



<!-- Modal -->

<div class="modal notification-modal fade" id="notification-modal" tabindex="-1" role="dialog" aria-hidden="true">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-body">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">&times;</span>

				</button>

				<h5>Search Form Name</h5>

				<hr>

				<!--List-->

				<div class="list-container-form-name">

					<input type="text" class="form-control" id="search_form_name_list"
						placeholder="Search Form Name Here ....">

					<i class="fas fa-window-close" id="clear_search_form_Data"></i>

				</div>

				<div class="list-container-list-holder">

					<div class="list-group" id="dynamic_formname_list"></div>

				</div>

			</div>

		</div>

	</div>

</div>

<!-- [ Header ] end -->

<style>
	.list-container-form-name {

		position: relative;

	}

	.alpha {
		margin-left: 15px;
		margin-right: 15px;
	}

	.list-container-form-name i {

		position: absolute;

		top: 11px;

		font-size: 20px;

		right: 10px;

		color: #777777;

	}



	.list-container-form-name i:hover {

		cursor: pointer;

	}



	.list-container-list-holder {

		width: 100%;

		height: 500px;

		border-radius: 5px;

		overflow-y: auto;

	}

	.mobile-wali-row {
		margin-top: 10px !important;
	}

	/* Extra small devices (phones, 600px and down) */
	@media only screen and (max-width: 600px) {
		.pc-container {
			margin-left: 0px !important;
		}

		.mobile-wali-row {
			margin-top: 55px !important;
		}

		.card-wali-css {
			top: 25px;
		}
	}
</style>