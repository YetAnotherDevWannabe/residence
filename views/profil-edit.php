<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Profil page</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
</head>

<body>
	<?php
	if (isConnected()) {
		$topNavStart = VIEWS_DIR.'partials/navbar/logo.php';
		$topNavCenter = VIEWS_DIR.'partials/top-navbar.php';
		$topNavEnd = VIEWS_DIR.'partials/navbar/connected-user.php';

		// Load the $_SESSION user from DB
		$userManager = new App\Models\Managers\UserManager();
		$dbUser = $userManager->getOneById($_SESSION['user']->getId());
	}
	include VIEWS_DIR.'partials/navbar/_top-navbar.php';
	?>

	<div class="hero bg-base-100 my-4">
		<div class="hero-content flex-col lg:flex-row-reverse">

			<!---------------->
			<!-- Right side -->
			<!---------------->
			<div class="w-1/4 me-2">

					<label for="avatar" class="flex flex-col items-center gap-2 border-2 cursor-pointer    border-rose-500">
						<!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 fill-white stroke-indigo-500" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> -->
						<img id="preview_img" class="rounded-lg" src="<?= ($dbUser->getAvatar()) ? PUBLIC_PATH.'../upload/'.$dbUser->getAvatar() : PUBLIC_PATH.'img/avatar_base.svg' ?>" alt="login_image">
					</label>
					<input form="profilEditForm" onchange="loadFile(event)" type="file" name="avatar" id="avatar" class="hidden" accept="image/png, image/jpeg, image/svg+xml, image/gif, image/webp"/>

			</div>

			<!--------------->
			<!-- Left side -->
			<!--------------->
			<div class="w-1/3 flex-shrink-0  mx-12">

				<h2 class="text-accent-content text-2xl">Modify account details <span class="emoji">✏️</span></h2>

				<p class="my-6 text-xs">Fill in your details to create an account</p>

				<!-- Sign-Up Form -->
				<!------------------>
				<form id="profilEditForm" method="POST" action="<?= PUBLIC_PATH ?>profil/edit/" enctype="multipart/form-data">

					<!-- Full Name -->
					<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
						<div class="relative bg-inherit">
							<input 
								type="text"
								name="name"
								class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
										focus:border-2 focus:outline-none <?= isset($errors['name']) ? ' border-rose-400' : ' focus:border-teal-600' ?>
										<?= isset($_POST['name']) && !isset($errors['name']) ? ' border-teal-600' : ''; ?>"
								pplaceholder="Type inside me"
								value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($dbUser->getName()); ?>"
								required
							/>
							<label
								for="name"
								class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
										peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
										peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['name']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
							>Full name</label>
						<?= isset($errors['name']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['name'].'</p>' : ''; ?>
						</div>
					</div>

					<!-- Email -->
					<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
						<div class="relative bg-inherit">
							<input 
								type="email"
								name="email"
								class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
										focus:border-2 focus:outline-none <?= isset($errors['email']) ? ' border-rose-400' : ' focus:border-teal-600' ?>
										<?= isset($_POST['email']) && !isset($errors['email']) ? ' border-teal-600' : ''; ?>"
								pplaceholder="Type inside me"
								value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($dbUser->getEmail()); ?>"
								required
							/>
							<label
								for="email"
								class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
										peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
										peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['email']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
							>Email address</label>
						<?= isset($errors['email']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['email'].'</p>' : ''; ?>
						</div>
					</div>

					<!-- Registration date -->
					<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
						<div class="relative bg-inherit">
							<input type="text" name="registration_date"
								style="cursor: default !important;"
								class="input input-bordered peer h-11 w-full rounded-lg border-gray-500"
								value="<?= htmlspecialchars($dbUser->getRegistrationDate()->format('Y/m/d H:i:s')); ?>"
								disabled
								required
							/>
							<label for="registration_date" class="absolute cursor-auto left-0 -top-3 text-sm bg-transparent mx-1 px-1">Registration date (cannot be edited)</label>
						</div>
					</div>

					<!-- Old password -->
					<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
						<div class="relative bg-inherit">
							<input 
								type="password"
								name="old-password"
								class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
										focus:border-2 focus:outline-none <?= isset($errors['old-password']) ? ' border-rose-400' : ' focus:border-teal-600' ?>"
								required
							/>
							<label
								for="old-password"
								class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
										peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
										peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['old-password']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
							>Old password</label>
						<?= isset($errors['old-password']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['old-password'].'</p>' : ''; ?>
						</div>
					</div>

					<!-- New password -->
					<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
						<div class="relative bg-inherit">
							<input 
								type="password"
								name="new-password"
								class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
										focus:border-2 focus:outline-none <?= isset($errors['new-password']) ? ' border-rose-400' : ' focus:border-teal-600' ?>"
							/>
							<label
								for="new-password"
								class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
										peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
										peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['new-password']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
							>New password</label>
						<?= isset($errors['new-password']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['new-password'].'</p>' : ''; ?>
						</div>
					</div>
					
					<!-- Confirm password -->
					<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
						<div class="relative bg-inherit">
							<input 
								type="password"
								name="confirm-password"
								class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
										focus:border-2 focus:outline-none <?= isset($errors['confirm-password']) ? ' border-rose-400' : ' focus:border-teal-600' ?>"
							/>
							<label
								for="confirm-password"
								class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
										peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
										peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['confirm-password']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
							>Confirm new password</label>
						<?= isset($errors['confirm-password']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['confirm-password'].'</p>' : ''; ?>
						</div>
					</div>

					<!-- Divider -->
					<div class="divider divider-accent">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</div>

					<!-- Sign-Up button -->
					<div class="form-control mt-10 lg:mt-6 sm:mt-2">
						<button class="btn btn-error" style="text-transform: none !important;">Save changes</button>
					</div>

				</form>

			</div>

		</div>
	</div>

	<?php include VIEWS_DIR . '/partials/footer.php'; ?>
	<script src="<?= PUBLIC_PATH ?>js/profil-edit.js"></script>
</body>
</html>