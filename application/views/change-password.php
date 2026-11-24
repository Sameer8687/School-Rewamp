<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Change Password - Olive English School</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />

	<!---Include CSS Files-->
	<?php include 'cssPages.php'; ?>

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
	<?php include 'sidebar.php'; ?>

	<!--Include Top Navbar-->
	<?php include 'topNav.php'; ?>

	<!-- [ Main Content ] start -->
	<div class="pc-container">
		<div class="pcoded-content">
			<!-- [ Main Content ] start -->
			<div class="row margin-top-minus-40">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Change Password</h5>
							<hr>
							<div class="row">
								<div class="col-md-4">
									<form action="<?= base_url('index.php/stats/change_password') ?>" method="POST">
										<div class="form-group">
											<label for="">Current Password : </label>
											<input type="text" class="form-control form-control-sm" name="current" id="current">
										</div>
										<div class="form-group">
											<label for="">New Password : </label>
											<input type="text" class="form-control form-control-sm" name="new" id="new">
										</div>
										<div class="form-group">
											<label for="">Confirm Password : </label>
											<input type="text" class="form-control form-control-sm" name="confirm" id="confirm">
										</div>

										<?php if ($this->session->userdata('ps_msg') != '') { ?>
											<div class="alert <?= $this->session->userdata('ps_cls'); ?>">
												<?= $this->session->userdata('ps_msg'); ?>
											</div>
										<?php } ?>
										<button class="btn btn-sm btn-primary">Change Password</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- [ Main Content ] end -->
	</div>
	<!---Include JS Files-->
	<?php include 'jsPages.php'; ?>
</body>

</html>
