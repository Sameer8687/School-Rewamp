<!-- AnsariKamran(KS) -->
<style type="text/css" media="print">
	@media print {

		.no-print {

			display: none !important;

		}

	}
</style>



<!-- [ Mobile header ] start -->

<div class="pc-mob-header pc-header no-print">

	<div class="pcm-logo">

		<img src="<?php echo base_url(); ?>images/logoNewDevWhite.png" style="height: 40px;" alt=""
			class="logo logo-lg">

		<span>Olive English School</span>

	</div>

	<div class="pcm-toolbar">

		<a href="#!" class="pc-head-link" id="mobile-collapse" onclick="toggleSidebar()">

			<div class="hamburger hamburger--arrowturn">

				<div class="hamburger-box">

					<div class="hamburger-inner"></div>

				</div>

			</div>

			<!-- <i data-feather="menu"></i> -->

		</a>

		<a href="#!" class="pc-head-link" id="header-collapse">

			<i data-feather="more-vertical"></i>

		</a>

	</div>

</div>

<!-- [ Mobile header ] End -->



<!-- [ navigation menu ] start -->



<!--Admin Section-->
<style>
	/* .pc-sidebar {
		position: fixed;
		top: 0;
		left: -280px;
		width: 250px;
		height: 100%;
		background-color: #333;
		transition: left 0.5s ease;
		z-index: 1;
	} */

	.pc-sidebar {
		position: fixed;
		top: 0;
		left: -250px;
		/* Sidebar hidden by default */
		width: 250px;
		height: 100%;
		background-color: #333;
		transition: left 0.3s ease;
		/* Smooth slide effect */
		z-index: 1;
	}

	/* #pc-collapse {
		position: fixed;
		top: 2px;
		left: 1px;
		transition: left 0.3s ease;
		z-index: 2;
		background-color: none;
		color: #fff;
		width: 30px;
		height: 30px;
		font-size: 20px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 4px;
		cursor: pointer;
	}

	.pc-sidebar.active {
		left: 0;
	}

	.pc-sidebar.active+#pc-collapse {
		left: 260px;
		
	} */

	#pc-collapse {
		position: fixed;
		top: 3px;
		left: 3px;
		/* Default toggle button position */
		transition: left 0.3s ease;
		/* Smooth transition for button */
		background-color: transparent;
		color: #fff;
		width: 30px;
		height: 30px;
		font-size: 20px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 4px;
		cursor: pointer;
		z-index: 2;
	}

	.pc-sidebar.active {
		left: 0;
		/* Slide-in effect for sidebar */
	}

	.pc-sidebar.active+#pc-collapse {
		left: 218px;
		/* Adjust button position with sidebar */
	}

	.pc-head-link {
		width: 30px;
		height: 30px;
		color: white;
		font-size: 20px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 4px;
		cursor: pointer;
		z-index: 2;
	}

	.hamburger--arrowturn .hamburger-inner,
	.hamburger--arrowturn .hamburger-inner::before,
	.hamburger--arrowturn .hamburger-inner::after {
		background-color: white;
		/* Lines and arrow default white */
	}

	/* Active state for close arrow */
	.hamburger--arrowturn.is-active .hamburger-inner,
	.hamburger--arrowturn.is-active .hamburger-inner::before,
	.hamburger--arrowturn.is-active .hamburger-inner::after {
		background-color: white;
		/* Close arrow color white */
	}

	.navbar-content {
		overflow-y: auto;
		height: calc(100% - 50px);
		/* Adjust to fit content properly */
	}

	/* @media (max-width: 768px) {
		.pc-sidebar {
			width: 200px;
		}

		.pc-sidebar.active+#pc-collapse {
			left: 210px;
		}

		#pc-collapse {
			left: 10px;
			
		}
	} */
	@media (max-width: 768px) {
		.pc-sidebar {
			width: 200px;
			/* Narrow sidebar for smaller screens */
		}

		.pc-sidebar.active+#pc-collapse {
			left: 210px;
			/* Adjust toggle button for smaller screens */
		}
	}
</style>

<nav class="pc-sidebar no-print">
	<div class="navbar-wrapper">
		<a href="#!" onclick="toggleSidebar()" class="pc-head-link text-white" id="pc-collapse">
			<div class="hamburger hamburger--arrowturn">
				<div class="hamburger-box">
					<div class="hamburger-inner"></div>
				</div>
			</div>
		</a>


		<div class="m-header text-center">
			<a href="<?= base_url(); ?>" class="b-brand">
				<img src="<?= base_url(); ?>images/logoNewDevWhite.png" style="height: 50px;" class="img-fluid"
					alt="Logo">
				<span class="text-white" style="margin-left: 10px;">Olive English School</span>
			</a>
		</div>

		<div class="navbar-content">
			<ul class="pc-navbar">
				<li class="pc-item pc-caption">
					<label>Administrative</label>
					<span>All Administrative Tasks</span>
				</li>

				<li class="pc-item">
					<a href="<?= base_url(); ?>" class="pc-link">
						<span class="pc-micon"><i data-feather="grid"></i></span>
						<span class="pc-mtext">Dashboard</span>
					</a>
				</li>

				<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '1') { ?>
					<li class="pc-item">
						<a href="<?= base_url('module'); ?>" class="pc-link">
							<span class="pc-micon"><i data-feather="grid"></i></span>
							<span class="pc-mtext">HR Dashboard</span>
						</a>
					</li>
				<?php } ?>


				<?php if ($this->session->userdata('oliveSchoolDashboardUser') == '1') { ?>
					<li class="pc-item pc-caption">
						<label>Administrative</label>
						<span>Account Manegement</span>
					</li>
					<li class="pc-item pc-hasmenu">

						<a href="#!" class="pc-link"><span class="pc-micon"><i data-feather="users"></i></span><span
								class="pc-mtext">Account Manegement</span><span class="pc-arrow"><i
									data-feather="chevron-right"></i></span></a>

						<ul class="pc-submenu">

							<li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>Welcome/user">User
									Access</a>
							</li>

							<li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>Welcome/forms">Forms</a>
							</li>

							<li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>Welcome/mode">Moduls</a>
							</li>

							<li class="pc-item"><a class="pc-link" href="<?php echo base_url(); ?>Welcome/user_report">User
									Report</a></li>

						</ul>

					</li>
				<?php } ?>




				<?php if (!empty($allocatedForms)) { ?>
					<?php foreach ($allocatedForms as $name) { ?>
						<li class="pc-item pc-hasmenu">
							<a href="#!" class="pc-link">
								<span class="pc-micon"><i data-feather="<?= htmlspecialchars($name['icon']); ?>"></i></span>
								<span class="pc-mtext"><?= htmlspecialchars($name['module_name']); ?></span>
								<span class="pc-arrow"><i data-feather="chevron-right"></i></span>
							</a>
							<ul class="pc-submenu">
								<?php foreach ($name['forms'] as $form) { ?>
									<li class="pc-item">
										<a href="<?= base_url(htmlspecialchars($form['form_link'])); ?>" class="pc-link">
											<?= htmlspecialchars($form['form_name']); ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					<?php } ?>
				<?php } else { ?>
					<li class="pc-item">No forms available.</li>
				<?php } ?>

				<li class="pc-item">
					<a href="<?= base_url(); ?>welcome/change_password" class="pc-link">
						<span class="pc-micon"><i data-feather="airplay"></i></span>
						<span class="pc-mtext">Change Password</span>
					</a>
				</li>

				<li class="pc-item">
					<a href="#" class="pc-link logoutEvent">
						<span class="pc-micon"><i data-feather="power"></i></span>
						<span class="pc-mtext">Log out</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</nav>


<!-- <script>
	function toggleSidebar() {
		var sidebar = document.querySelector('.pc-sidebar');
		var toggleButton = document.getElementById('pc-collapse');
		sidebar.classList.toggle('active');

		toggleButton.style.left = sidebar.classList.contains('active') ? '260px' : '10px'; // Adjust with sidebar width
	}

</script> -->

<!-- <script>
	function toggleSidebar() {
		var sidebar = document.querySelector('.pc-sidebar');
		var toggleButton = document.getElementById('pc-collapse');
		sidebar.classList.toggle('active');

		if (sidebar.classList.contains('active')) {
			toggleButton.style.left = '215px'; // Move button with sidebar
		} else {
			toggleButton.style.left = '1px'; // Reset button to original position
		}
	}

</script> -->