<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Resience delete</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
</head>

<body>
	<?php
	if (!isConnected()) {
		header('location: ' . PUBLIC_PATH);
	} else {
		$topNavStart = VIEWS_DIR.'partials/navbar/breadcrumb.php';
		$topNavCenter = VIEWS_DIR.'partials/top-navbar.php';
		$topNavEnd = VIEWS_DIR.'partials/navbar/connected-user.php';

		// Load the $_SESSION user from DB
		$userManager = new App\Models\Managers\UserManager();
		$dbUser = $userManager->getOneById($_SESSION['user']->getId());

		// Load all the Residences for the connected User
		$residenceManager = new App\Models\Managers\ResidenceManager();
		$residences = $residenceManager->getAllByUserId($dbUser->getId());
		$selectedResidence = null;
	}

	include VIEWS_DIR.'partials/navbar/_top-navbar.php';
	if ( isset($success) ) {
		?>
		<div class="card w-1/2 bg-base-200 shadow-xl mt-20 mx-auto">
			<div class="card-body items-center text-center p-4">
				<h2 class="card-title text-success">Success!</h2>
				<p class="my-4"><?= $success ?></p>
				<div class="card-actions flex justify-end mt-4">
					<a href="<?= PUBLIC_PATH ?>dashboard/" class="btn btn-success">Dashboard</a>
				</div>
			</div>
		</div>
		<?php
	} else if ( isset($errors['server']) || isset($errors['token']) ) {
		?>
		<div class="card w-1/2 bg-base-200 shadow-xl mt-20 mx-auto">
			<div class="card-body items-center text-center p-4">
				<h2 class="card-title text-error">Error!</h2>
				<p class="my-4"><?= isset($errors['server']) ? $errors['server'] : (isset($errors['token']) ? $errors['token'] : 'What happened ?'); ?></p>
				<div class="card-actions justify-end">
					<a href="<?= PUBLIC_PATH ?>dashboard/" class="btn btn-error">Back</a>
				</div>
			</div>
		</div>
		<?php
	}
	?>

	<?php include VIEWS_DIR . '/partials/footer.php'; ?>
</body>
</html>