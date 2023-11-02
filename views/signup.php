<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Signup page</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
	<!-- <link rel="stylesheet" href="<?= PUBLIC_PATH; ?>/css/signup.css"> --><!-- CSS for this page would go here -->
</head>

<body>
	<?php
	$residence = new stdClass();
	$residence->type = 'House';
	$residence->name = 'House Paris';

	if (isConnected()) {
		header('location: ' . PUBLIC_PATH);
	} else {
		$topNavStart = VIEWS_DIR.'partials/navbar/logo.php';
		$topNavCenter = VIEWS_DIR.'partials/top-navbar.php';
		$topNavEnd = null;
	}
	include VIEWS_DIR.'partials/navbar/_top-navbar.php';
	
	if ( isset($success) ) {
		?>
		<div class="card w-1/2 bg-base-200 shadow-xl mt-20 mx-auto">
			<div class="card-body items-center text-center p-4">
				<h2 class="card-title text-success">Success!</h2>
				<p class="my-4"><?= $success ?></p>
				<div class="card-actions flex justify-end mt-4">
					<a href="<?= PUBLIC_PATH ?>login/" class="btn btn-success">Log-In</a>
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
					<a href="<?= PUBLIC_PATH ?>" class="btn btn-error">Back</a>
				</div>
			</div>
		</div>
		<?php
	} else {
	?>

		<div class="hero bg-base-100 my-4">
			<div class="hero-content flex-col lg:flex-row-reverse">

				<!---------------->
				<!-- Right side -->
				<!---------------->
				<div class="w-2/3 me-2">
					<img class="rounded-lg" src="<?= PUBLIC_PATH ?>img/signup_hero.jpg" alt="login_image">
				</div>

				<!--------------->
				<!-- Left side -->
				<!--------------->
				<div class="w-1/3 flex-shrink-0  mx-12">

					<h2 class="text-accent-content text-2xl">Create an account <span class="emoji">👋</span></h2>

					<p class="my-6 text-xs">Fill in your details to create an account</p>

					<!-- Sign-Up Form -->
					<!------------------>
					<form method="POST" action="<?= PUBLIC_PATH ?>signup/">

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
									value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
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
									value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
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

						<!-- Password -->
						<div class="bg-base-100 p-1 rounded-lg mt-10 lg:mt-6 sm:mt-2">
							<div class="relative bg-inherit">
								<input 
									type="password"
									name="password"
									class="input input-bordered peer bg-transparent h-11 w-full rounded-lg placeholder-transparent border-gray-500 
											focus:border-2 focus:outline-none <?= isset($errors['password']) ? ' border-rose-400' : ' focus:border-teal-600' ?>"
									pplaceholder="Type inside me"
									required
								/>
								<label
									for="password"
									class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
											peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
											peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['password']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
								>Password</label>
							<?= isset($errors['password']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['password'].'</p>' : ''; ?>
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
									pplaceholder="Type inside me"
									required
								/>
								<label
									for="confirm-password"
									class="absolute cursor-text left-0 -top-3 text-sm bg-inherit mx-1 px-1 
											peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 
											peer-focus:-top-3 peer-focus:text-sm transition-all<?= isset($errors['confirm-password']) ? ' text-rose-400' : ' peer-focus:text-teal-600' ?>"
								>Confirm password</label>
							<?= isset($errors['confirm-password']) ? '<p class="text-rose-400 text-xs italic mt-2">'.$errors['confirm-password'].'</p>' : ''; ?>
							</div>
						</div>

						<!-- Terms and Conditions -->
						<div class="form-control mt-4 lg:mt-3 sm:mt-2 w-3/5">
							<label class="cursor-pointer label">
								<input <?= isset($_POST['terms']) ? 'checked ' : ' '; ?>type="checkbox" name="terms" class="checkbox checkbox-md lg:checkbox-md sm:checkbox-sm checkbox-accent" />
								<span 
									onclick="modal_termsAndConditions.showModal()" 
									class="label-text hover:link hover:link-accent hover:underline<?= isset($errors['terms']) ? ' text-error' : ' text-neutral-content'; ?>">Agree to Terms & Conditions<?= isset($errors['terms']) ? '*' : ''; ?>
								</span>
							</label>

							<dialog id="modal_termsAndConditions" class="modal">
								<div class="modal-box">
									<h3 class="font-bold text-lg">Terms & Conditions</h3>
									<p class="py-4">Press ESC key to close</p>
								</div>
							</dialog>

						</div>

						<!-- Sign-Up button -->
						<div class="form-control mt-6">
							<button class="btn btn-primary normal-case">Register account</button>
						</div>

						<!-- Divider -->
						<div class="divider divider-accent">or</div>

						<!-- Google Sign-Up button -->
						<div class="form-control mt-6">
							<button onclick="modal_googleLogin.showModal();" class="btn bg-black text-white normal-case">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 50 50"><title>Google-color</title><desc>Created with Sketch.</desc><path d="M9.82727273,24 C9.82727273,22.4757333 10.0804318,21.0144 10.5322727,19.6437333 L2.62345455,13.6042667 C1.08206818,16.7338667 0.213636364,20.2602667 0.213636364,24 C0.213636364,27.7365333 1.081,31.2608 2.62025,34.3882667 L10.5247955,28.3370667 C10.0772273,26.9728 9.82727273,25.5168 9.82727273,24" id="Fill-1" fill="#FBBC05" /> <path d="M23.7136364,10.1333333 C27.025,10.1333333 30.0159091,11.3066667 32.3659091,13.2266667 L39.2022727,6.4 C35.0363636,2.77333333 29.6954545,0.533333333 23.7136364,0.533333333 C14.4268636,0.533333333 6.44540909,5.84426667 2.62345455,13.6042667 L10.5322727,19.6437333 C12.3545909,14.112 17.5491591,10.1333333 23.7136364,10.1333333" id="Fill-2" fill="#EB4335" /> <path d="M23.7136364,37.8666667 C17.5491591,37.8666667 12.3545909,33.888 10.5322727,28.3562667 L2.62345455,34.3946667 C6.44540909,42.1557333 14.4268636,47.4666667 23.7136364,47.4666667 C29.4455,47.4666667 34.9177955,45.4314667 39.0249545,41.6181333 L31.5177727,35.8144 C29.3995682,37.1488 26.7323182,37.8666667 23.7136364,37.8666667" id="Fill-3" fill="#34A853" /> <path d="M46.1454545,24 C46.1454545,22.6133333 45.9318182,21.12 45.6113636,19.7333333 L23.7136364,19.7333333 L23.7136364,28.8 L36.3181818,28.8 C35.6879545,31.8912 33.9724545,34.2677333 31.5177727,35.8144 L39.0249545,41.6181333 C43.3393409,37.6138667 46.1454545,31.6490667 46.1454545,24" id="Fill-4" fill="#4285F4" /></svg>Login with Google
							</button>

							<dialog id="modal_googleLogin" class="modal">
								<div class="modal-box">
									<h3 class="font-bold text-lg">Login with Google</h3>
									<p class="py-4">Not yet implemented</p>
									<p class="py-4">Press ESC key to close</p>
								</div>
							</dialog>
						</div>

					</form>

				</div>

			</div>
		</div>

		<?php
	}
	?>

	<?php include VIEWS_DIR . '/partials/footer.php'; ?>
</body>
</html>