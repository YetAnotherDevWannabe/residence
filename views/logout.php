<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Log-out page</title>
	<?php include VIEWS_DIR .'/partials/header.php'; ?>
</head>

<body>
	<?php
	$elem1 = new stdClass();
	$elem1->type = 'Apartment';
	$elem1->name = 'Apart Lyon';

	$elem2 = new stdClass();
	$elem2->type = 'House';
	$elem2->name = 'House Paris';

	$residence = $elem1;
	// $residence = $elem2;
	if (isConnected()) {
		$TopNavStart = VIEWS_DIR .'/partials/navbar/breadcrumb.php';
		$TopNavCenter = '';
		$TopNavEnd = VIEWS_DIR .'/partials/navbar/connected-user.php';
	} else {
		$TopNavStart = VIEWS_DIR .'/partials/navbar/breadcrumb.php';
		$TopNavCenter = '';
		$TopNavEnd = '';
	}
	include VIEWS_DIR .'/partials/navbar/_top-navbar.php';
	?>
	<div class="container">

		<div class="row my-5">
			<div class="col-6 offset-3">

			<div class="row my-3">
				<div class="col-12">
					<p class="alert alert-warning fw-bold text-center">You have been disconnected from your account</p>
				</div>
			</div>
	</div>


	<?php include VIEWS_DIR .'/partials/footer.php'; ?>
</body>
</html>