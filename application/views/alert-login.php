<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from html.phoenixcoded.net/nextro-able/bootstrap/default/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 08 Oct 2020 11:09:44 GMT -->

<head>

	<title>Olive English School - Student Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="" />
	<meta name="keywords" content="">
	<meta name="author" content="Phoenixcoded" />

	<!--Include CSS-->
	<?php include 'cssPages.php'; ?>
</head>

<!-- [ auth-signin ] start -->
<div class="auth-wrapper">
	<div class="auth-content">
		<div class="card">
			<div class="row align-items-center text-center">
				<div class="col-md-12">
					<div class="card-body">
						<img src="<?php echo base_url(); ?>images/logoNewDev.png" class="login-logo" alt="" class="img-fluid mb-4">

						<form action="<?php echo base_url(); ?>Student_login/validate" method="post">
							<h6 class="pb-2" style="color: #53769c;">Student Login</h6>
							<div class="input-group mb-3">
							<h6 class="text-danger">Message: Error</h6>
									<p>
										<?= $message; ?>
									</p>
									<p>Access Time : <?= date('d-M-y h:i:s'); ?></p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- [ auth-signin ] end -->

<!-- Required Js -->
<?php include 'jsPages.php'; ?>

</body>

</html>
