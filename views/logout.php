<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Log-out page</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
</head>

<body>
	<?php
	$topNavStart = VIEWS_DIR.'partials/navbar/logo.php';
	$topNavCenter = '';
	$topNavEnd = '';
	include VIEWS_DIR.'partials/navbar/_top-navbar.php';
	?>
	
	<div class="card w-1/2 bg-base-200 shadow-xl mt-20 mx-auto">
		<div class="card-body items-center text-center p-4">
			<h2 class="card-title text-warning">
				<svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
				Warning:
			</h2>
			<p class="my-4">You have been disconnected from your account</p>
			<div class="card-actions flex justify-end">
				<a href="<?= PUBLIC_PATH ?>" class="btn btn-warning">Home</a>
			</div>
		</div>
	</div>

	<?php include VIEWS_DIR .'/partials/footer.php'; ?>
</body>
</html>