<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Home page</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
	<!-- <link rel="stylesheet" href="<?= PUBLIC_PATH; ?>css/home.css"> -->
</head>

<body>
	<?php
	if (isConnected()) {
		$topNavStart = VIEWS_DIR.'partials/navbar/breadcrumb.php';
		$topNavCenter = VIEWS_DIR.'partials/top-navbar.php';
		$topNavEnd = VIEWS_DIR.'partials/navbar/connected-user.php';

		// Load the $_SESSION user from DB
		$userManager = new App\Models\Managers\UserManager();
		$dbUser = $userManager->getOneById($_SESSION['user']->getId());

		// Load the Residences for connected User
		$residenceManager = new App\Models\Managers\ResidenceManager();
		
		$residence = new stdClass();
		$residence->type = 'Apartment';
		$residence->name = 'Apart Lyon';
	}
	include VIEWS_DIR.'partials/navbar/_top-navbar.php';
	?>

	<div class="flex justify-around">
		<div class="card w-96 glass m-4">
			<figure><img src="https://daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.jpg" alt="car!"/></figure>
			<div class="card-body">
				<h2 class="card-title">Life hack</h2>
				<p>How to park your car at your garage?</p>
				<div class="card-actions justify-end">
					<button class="btn btn-primary">Learn now!</button>
				</div>
			</div>
		</div>
	</div>

	<?php include VIEWS_DIR .'/partials/footer.php'; ?>
</body>
</html>