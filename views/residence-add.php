<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Residence add</title>
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
	} else if ( isset($errors['server']) ) {
		?>
		<div class="card w-1/2 bg-base-200 shadow-xl mt-20 mx-auto">
			<div class="card-body items-center text-center p-4">
				<h2 class="card-title text-error">Error!</h2>
				<p class="my-4"><?= $errors['server'] ?></p>
				<div class="card-actions justify-end">
					<a href="<?= PUBLIC_PATH ?>dashboard/" class="btn btn-error">Back</a>
				</div>
			</div>
		</div>
		<?php
	} else {
	?>

		<div class="contain bg-base-100 my-4">
			<div class="max-w-lg mx-auto shadow px-6 py-7 rounded overflow-hidden">

					<h2 class="text-accent-content text-2xl">Add a Residence</h2>

					<p class="my-6 text-xs">Fill in your residence details</p>

					<!-- Sign-Up Form -->
					<!------------------>
					<form method="POST" action="<?= PUBLIC_PATH ?>residence/add/">


						<!-- Residence Type -->
						<div class="form-control">
							<label class="label cursor-pointer">
								<span class="label-text">House</span>
								<input 
									type="radio"
									name="type"
									class="radio radio-sm<?= isset($errors['type']) ? ' checked:bg-rose-400' : ' checked:bg-teal-600' ?>"
									value="house"
									required
									<?= isset($_POST['type']) && $_POST['type'] == 'house' ? 'checked' : ''; ?>
								/>
							</label>
							<label class="label cursor-pointer">
								<span class="label-text">Apartment</span>
								<input 
									type="radio"
									name="type"
									class="radio radio-sm<?= isset($errors['type']) ? ' checked:bg-rose-400' : ' checked:bg-teal-600' ?>"
									value="apartment"
									required
									<?= isset($_POST['type']) && $_POST['type'] == 'apartment' ? 'checked' : ''; ?>
								/>
							</label>
						</div>

						<!-- Residence Name -->
						<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
							<div class="relative bg-inherit">
								<input 
									type="text"
									name="name"
									class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
											focus:border-2 focus:outline-none <?= isset($errors['name']) ? ' border-rose-400' : ' focus:border-teal-600' ?>
											<?= isset($_POST['name']) && !isset($errors['name']) ? ' border-teal-600' : ''; ?>"
									value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
									required
								/>
								<label
									for="name"
									class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
											peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
											peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['name']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
								>Residence name</label>
							<?= isset($errors['name']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['name'].'</p>' : ''; ?>
							</div>
						</div>

						<!-- Residence Address -->
						<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
							<div class="relative bg-inherit">

								<textarea
									name="address"
									class="textarea textarea-bordered peer bg-transparent h-36 w-full rounded-lg placeholder-transparent border-gray-500 
											focus:border-2 focus:outline-none <?= isset($errors['address']) ? ' border-rose-400' : ' focus:border-teal-600' ?>
											<?= isset($_POST['address']) && !isset($errors['address']) ? ' border-teal-600' : ''; ?>"
									required
								><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
								<label
									for="address"
									class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
											peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
											peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['address']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
								>Residence address</label>
							<?= isset($errors['address']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['address'].'</p>' : ''; ?>
							</div>
						</div>

						<!-- Postal Code -->
						<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
							<div class="relative bg-inherit">
								<input 
									type="tel"
									name="postal-code"
									minlength="5"
									maxlength="6"
									pattern="[0-9]{5,6}"
									class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
											focus:border-2 focus:outline-none <?= isset($errors['residence-name']) ? ' border-rose-400' : ' focus:border-teal-600' ?>
											<?= isset($_POST['residence-name']) && !isset($errors['residence-name']) ? ' border-teal-600' : ''; ?>"
									value="<?= isset($_POST['postal-code']) ? htmlspecialchars($_POST['postal-code']) : ''; ?>"
									required
								/>
								<label
									for="postal-code"
									class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
											peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
											peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['postal-code']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
								>Postal code</label>
							<?= isset($errors['postal-code']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['postal-code'].'</p>' : ''; ?>
							</div>
						</div>

						<!-- City -->
						<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
							<div class="relative bg-inherit">
								<input 
									type="text"
									name="city"
									class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
											focus:border-2 focus:outline-none <?= isset($errors['city']) ? ' border-rose-400' : ' focus:border-teal-600' ?>"
									value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>"
									required
								/>
								<label
									for="city"
									class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
											peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
											peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['city']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
								>City</label>
							<?= isset($errors['city']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['city'].'</p>' : ''; ?>
							</div>
						</div>

						<!-- Sign-Up button -->
						<div class="form-control mt-6">
							<button class="btn btn-accent normal-case">Add Residence</button>
						</div>

					</form>


			</div>
		</div>

		<?php
	}
	?>

	<?php include VIEWS_DIR . '/partials/footer.php'; ?>
</body>
</html>