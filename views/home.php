<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Home page</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
	<!-- <link rel="stylesheet" href="<?= PUBLIC_PATH; ?>css/home.css"> -->
</head>

<body>
	<?php
	$residence = new stdClass();
	$residence->type = 'Apartment';
	$residence->name = 'Apart Lyon';

	if (isConnected()) {
		$topNavStart = VIEWS_DIR.'partials/navbar/logo.php';
		$topNavCenter = VIEWS_DIR.'partials/top-navbar.php';
		$topNavEnd = VIEWS_DIR.'partials/navbar/connected-user.php';

		// Load the $_SESSION user from DB
		$userManager = new App\Models\Managers\UserManager();
		$dbUser = $userManager->getOneById($_SESSION['user']->getId());
	} else {
		$topNavStart = VIEWS_DIR.'partials/navbar/logo.php';
		$topNavCenter = VIEWS_DIR.'partials/top-navbar.php';
		$topNavEnd = null;
	}
	include VIEWS_DIR.'partials/navbar/_top-navbar.php';
	?>

	<div class="flex justify-around">
		<?php
		if (isConnected()) {
			?>
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
			<?php
		} else {
			?>
			<div class="hero min-h-screen" style="background-image: url(https://daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.jpg);">
				<div class="hero-overlay bg-opacity-60"></div>
					<div class="hero-content text-center text-neutral-content">
						<div class="max-w-md">
						<h1 class="mb-5 text-5xl font-bold">Hello there</h1>
						<p class="mb-5">Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem quasi. In deleniti eaque aut repudiandae et a id nisi.</p>
						<button class="btn btn-primary">Get Started</button>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>

	<?php include VIEWS_DIR .'/partials/footer.php'; ?>
</body>
</html>