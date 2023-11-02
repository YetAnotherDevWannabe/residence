<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Profil page</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
	<!-- <link rel="stylesheet" href="<?= PUBLIC_PATH; ?>/css/signup.css"> --><!-- CSS for this page would go here -->
</head>

<body>
	<?php
	$residence = new stdClass();
	$residence->type = 'House';
	$residence->name = 'House Paris';

	if (isConnected()) {
		$topNavStart = VIEWS_DIR.'partials/navbar/logo.php';
		$topNavCenter = VIEWS_DIR.'partials/top-navbar.php';
		$topNavEnd = VIEWS_DIR.'partials/navbar/connected-user.php';
	}
	include VIEWS_DIR.'partials/navbar/_top-navbar.php';
	?>

	<div class="hero bg-base-100 my-4">
		<div class="hero-content flex-col lg:flex-row-reverse">

			<!---------------->
			<!-- Right side -->
			<!---------------->
			<div class="w-1/4 me-2">
				<img class="rounded-lg" src="<?= (USER->getAvatar()) ? PUBLIC_PATH.USER->getAvatar() : PUBLIC_PATH.'img/avatar_base.svg' ?>" alt="login_image">
			</div>

			<!--------------->
			<!-- Left side -->
			<!--------------->
			<div class="w-1/3 flex-shrink-0  mx-12">

				<h2 class="text-accent-content text-2xl"><span class="emoji">👋</span> Hello <?= htmlspecialchars(USER->getName()) ?> !</h2>

				<p class="my-6 text-xs">Here's your account details</p>

				<!-- Account details Form -->
				<!-------------------------->

				<!-- Full Name -->
				<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
					<div class="relative bg-inherit">
						<input type="text" name="name"
							style="cursor: default !important;"
							class="input input-bordered peer h-11 w-full rounded-lg border-gray-500"
							value="<?= htmlspecialchars(USER->getName()); ?>"
							disabled required
						/>
						<label for="name" class="absolute cursor-auto left-0 -top-3 text-sm bg-transparent mx-1 px-1">Full name</label>
					</div>
				</div>

				<!-- Email -->
				<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
					<div class="relative bg-inherit">
						<input type="email" name="email"
							style="cursor: default !important;"
							class="input input-bordered peer h-11 w-full rounded-lg border-gray-500"
							value="<?= htmlspecialchars(USER->getEmail()); ?>"
							disabled required
						/>
						<label for="email" class="absolute cursor-auto left-0 -top-3 text-sm bg-transparent mx-1 px-1">Email address</label>
					</div>
				</div>

				<!-- Registration date -->
				<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
					<div class="relative bg-inherit">
						<input type="text" name="registration_date"
							style="cursor: default !important;"
							class="input input-bordered peer h-11 w-full rounded-lg border-gray-500"
							value="<?= htmlspecialchars(USER->getRegistrationDate()->format('Y/m/d H:i:s')); ?>"
							disabled required
						/>
						<label for="registration_date" class="absolute cursor-auto left-0 -top-3 text-sm bg-transparent mx-1 px-1">Registration date</label>
					</div>
				</div>

				<!-- Registration date -->
				<!-- <div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
					<div class="relative bg-inherit">
						<input 
							type="text"
							name="registration_date"
							style="cursor: default !important;"
							class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
									focus:border-2 focus:outline-none <?= isset($errors['registration_date']) ? ' border-rose-400' : ' focus:border-teal-600' ?>
									<?= isset($_POST['registration_date']) && !isset($errors['registration_date']) ? ' border-teal-600' : ''; ?>"
							pplaceholder="Type inside me"
							value="<?= htmlspecialchars(USER->getEmail()); ?>"
							disabled
							required
						/>
						<label
							for="registration_date"
							class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
									peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
									peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['registration_date']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
						>Email address</label>
					<?= isset($errors['registration_date']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['registration_date'].'</p>' : ''; ?>
					</div>
				</div> -->

				<!-- Divider -->
				<div class="divider divider-accent">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</div>

				<!-- Sign-Up button -->
				<div class="form-control mt-10 lg:mt-6 sm:mt-2">
					<a href="<?= PUBLIC_PATH.'profil/edit/' ?>" class="btn btn-accent normal-case hover:btn-danger">Edit account</a>
				</div>

			</div>

		</div>
	</div>

	<?php include VIEWS_DIR . '/partials/footer.php'; ?>
</body>
</html>