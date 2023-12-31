<!DOCTYPE HTML>
<html lang="fr">
<head>
	<title>Home page</title>
	<?php include VIEWS_DIR.'partials/header.php'; ?>
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

		// Load all the Residences for the connected User
		$residenceManager = new App\Models\Managers\ResidenceManager();
		$residences = $residenceManager->getAllByUserId($dbUser->getId());
		$selectedResidence = null;
	}
	include VIEWS_DIR.'partials/navbar/_top-navbar.php';

	$houseSVG = '<svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 me-2" fill="none" viewBox="0 0 45 45"><path d="M24.155 8.78099L24.33 8.94499L37.402 21.787L36 23.213L34.2 21.445L34.201 35C34.201 36.054 33.385 36.918 32.35 36.994L32.201 37H12.201C11.147 37 10.283 36.184 10.206 35.149L10.201 35L10.2 21.446L8.402 23.213L7 21.787L20.058 8.95799C21.171 7.82199 22.966 7.75899 24.155 8.78099ZM21.569 10.285L21.473 10.372L12.2 19.481L12.201 35L17.2 34.999L17.201 25C17.201 23.946 18.017 23.082 19.052 23.005L19.201 23H25.201C26.255 23 27.119 23.816 27.196 24.851L27.201 25L27.2 34.999L32.201 35L32.2 19.48L22.901 10.344C22.537 9.98699 21.969 9.96499 21.569 10.285ZM25.201 25H19.201L19.2 34.999H25.2L25.201 25Z" fill="var(--primaryColor)" /></svg>';

	$apartmentSVG = '<svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 me-2" fill="none" viewBox="0 0 45 45"><path d="M34 13.5H26V10.5C25.999 9.97 25.788 9.461 25.413 9.086C25.039 8.712 24.53 8.501 24 8.5H10C9.47 8.501 8.961 8.711 8.586 9.086C8.211 9.461 8.001 9.97 8 10.5V34.5C8.001 35.03 8.212 35.539 8.586 35.913C8.961 36.288 9.47 36.499 10 36.5H34C34.53 36.499 35.038 36.288 35.413 35.913C35.788 35.538 35.999 35.03 36 34.5V15.5C35.999 14.97 35.788 14.461 35.413 14.086C35.039 13.712 34.53 13.501 34 13.5ZM19 34.5H15V29.5H19V34.5ZM24 34.5H21V29.5C20.999 28.97 20.788 28.461 20.413 28.087C20.039 27.712 19.53 27.501 19 27.5H15C14.47 27.501 13.961 27.712 13.586 28.087C13.212 28.461 13.001 28.97 13 29.5V34.5H10L9.999 10.5H24V34.5ZM34 34.5H26V15.5H34V34.5ZM31 22.5C31 22.698 30.941 22.891 30.831 23.056C30.722 23.22 30.565 23.348 30.383 23.424C30.2 23.5 29.999 23.519 29.805 23.481C29.611 23.442 29.433 23.347 29.293 23.207C29.153 23.067 29.058 22.889 29.019 22.695C28.981 22.501 29 22.3 29.076 22.117C29.152 21.935 29.28 21.778 29.444 21.669C29.609 21.559 29.802 21.5 30 21.5C30.265 21.5 30.52 21.605 30.707 21.793C30.895 21.98 31 22.235 31 22.5ZM29 18.5C29 18.302 29.059 18.109 29.169 17.944C29.278 17.78 29.435 17.652 29.617 17.576C29.8 17.5 30.001 17.481 30.195 17.519C30.389 17.558 30.567 17.653 30.707 17.793C30.847 17.933 30.942 18.111 30.981 18.305C31.019 18.499 31 18.7 30.924 18.883C30.848 19.065 30.72 19.222 30.556 19.331C30.391 19.441 30.198 19.5 30 19.5C29.735 19.5 29.48 19.395 29.293 19.207C29.105 19.02 29 18.765 29 18.5ZM21 22.5C21 22.698 20.941 22.891 20.831 23.056C20.722 23.22 20.565 23.348 20.383 23.424C20.2 23.5 19.999 23.519 19.805 23.481C19.611 23.442 19.433 23.347 19.293 23.207C19.153 23.067 19.058 22.889 19.019 22.695C18.981 22.501 19 22.3 19.076 22.117C19.152 21.935 19.28 21.778 19.444 21.669C19.609 21.559 19.802 21.5 20 21.5C20.265 21.5 20.52 21.605 20.707 21.793C20.895 21.98 21 22.235 21 22.5ZM21 18.5C21 18.698 20.941 18.891 20.831 19.056C20.722 19.22 20.565 19.348 20.383 19.424C20.2 19.5 19.999 19.519 19.805 19.481C19.611 19.442 19.433 19.347 19.293 19.207C19.153 19.067 19.058 18.889 19.019 18.695C18.981 18.501 19 18.3 19.076 18.117C19.152 17.935 19.28 17.778 19.444 17.669C19.609 17.559 19.802 17.5 20 17.5C20.265 17.5 20.52 17.605 20.707 17.793C20.895 17.98 21 18.235 21 18.5ZM21 14.5C21 14.698 20.941 14.891 20.831 15.056C20.722 15.22 20.565 15.348 20.383 15.424C20.2 15.5 19.999 15.519 19.805 15.481C19.611 15.442 19.433 15.347 19.293 15.207C19.153 15.067 19.058 14.889 19.019 14.695C18.981 14.501 19 14.3 19.076 14.117C19.152 13.935 19.28 13.778 19.444 13.669C19.609 13.559 19.802 13.5 20 13.5C20.265 13.5 20.52 13.605 20.707 13.793C20.895 13.98 21 14.235 21 14.5ZM15 22.5C15 22.698 14.941 22.891 14.831 23.056C14.722 23.22 14.565 23.348 14.383 23.424C14.2 23.5 13.999 23.519 13.805 23.481C13.611 23.442 13.433 23.347 13.293 23.207C13.153 23.067 13.058 22.889 13.019 22.695C12.981 22.501 13 22.3 13.076 22.117C13.152 21.935 13.28 21.778 13.444 21.669C13.609 21.559 13.802 21.5 14 21.5C14.265 21.5 14.52 21.605 14.707 21.793C14.895 21.98 15 22.235 15 22.5ZM15 18.5C15 18.698 14.941 18.891 14.831 19.056C14.722 19.22 14.565 19.348 14.383 19.424C14.2 19.5 13.999 19.519 13.805 19.481C13.611 19.442 13.433 19.347 13.293 19.207C13.153 19.067 13.058 18.889 13.019 18.695C12.981 18.501 13 18.3 13.076 18.117C13.152 17.935 13.28 17.778 13.444 17.669C13.609 17.559 13.802 17.5 14 17.5C14.265 17.5 14.52 17.605 14.707 17.793C14.895 17.98 15 18.235 15 18.5ZM15 14.5C15 14.698 14.941 14.891 14.831 15.056C14.722 15.22 14.565 15.348 14.383 15.424C14.2 15.5 13.999 15.519 13.805 15.481C13.611 15.442 13.433 15.347 13.293 15.207C13.153 15.067 13.058 14.889 13.019 14.695C12.981 14.501 13 14.3 13.076 14.117C13.152 13.935 13.28 13.778 13.444 13.669C13.609 13.559 13.802 13.5 14 13.5C14.265 13.5 14.52 13.605 14.707 13.793C14.895 13.98 15 14.235 15 14.5Z" fill="var(--primaryColor)" /> </g></svg>';
	?>

	<div class="m-4 flex flex-wrap">
		
		<?php foreach ($residences as $k => $residence) { ?>

			<div id="residence_<?= $k; ?>" class="w-full rounded-lg md:w-1/2 lg:w-1/3 cursor-pointer group" onclick="intercept(event);">

				<!-- <a href="<?= PUBLIC_PATH; ?>residence/"> -->
				<div class="grid grid-cols-3 gap-2 p-3 m-2 bg-base-300 rounded-lg border border-2 border-base-300 hover:border-teal-600">
					<div class="h-28 rounded-xl col-1 p-4">
						<svg width="80px" height="80px" viewBox="<?= ($residence->getType() == 'house') ? '425 0 1024 1024' : '-575 0 1024 1024'; ?>" class="icon mx-auto"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M512 191.648V32a32 32 0 0 0-32-32h-96a32 32 0 0 0-32 32v351.648H64v640h864v-416l-416-416z" fill="#EAEAEA" /><path d="M512 1024V32a32 32 0 0 0-32-32h-96a32 32 0 0 0-32 32v992h160z" fill="" /><path d="M608 768h224v256h-224z" fill="#434854" /><path d="M832 608l-224-224v256h224zM128 480h160v160H128z" fill="#469FCC" /><path d="M64 384h288v32H64zM928 672L512 256V192l416 416z" fill="" /><path d="M352 288H32a32 32 0 0 0-32 32v32a32 32 0 0 0 32 32h320V288zM1009.472 573.6L512 76.128v135.744l429.6 429.6a31.968 31.968 0 0 0 45.248 0l22.624-22.624a31.968 31.968 0 0 0 0-45.248z" fill="#EF4D4D" /><path d="M128 480h160v32H128zM608 416l224 224v-32l-224-224z" fill="" /><path d="M238.24 1024A126.656 126.656 0 0 0 256 960a128 128 0 0 0-256 0c0 23.424 6.752 45.088 17.76 64h220.48zM896 832a127.744 127.744 0 0 0-116.224 75.04A94.848 94.848 0 0 0 736 896a96 96 0 0 0-96 96c0 11.296 2.304 21.952 5.888 32h360.384A126.944 126.944 0 0 0 1024 960a128 128 0 0 0-128-128z" fill="#3AAD73" /><path d="M779.776 907.04A94.848 94.848 0 0 0 736 896a96 96 0 0 0-96 96c0 11.296 2.304 21.952 5.888 32h139.872A126.656 126.656 0 0 1 768 960c0-18.944 4.384-36.768 11.776-52.96z" fill="" /></svg>
					</div>

					<div class="h-28 rounded-xl p-3 col-span-2 grid items-end">
						<h3 class="text-xl font-bold text-accent sm:text-2xl lg:text-xl xl:text-2xl"><?= $residence->getName(); ?></h3>
					</div>

					<div class="h-44 rounded-xl p-3 col-span-3 grid-rows-3">
						<p class="text-base font-medium px-2 mt-2"><?= $residence->getAddress(); ?></p>
						<p class="text-base font-medium px-2 mt-2"><?= $residence->getPostalCode(); ?></p>
						<p class="text-base font-medium px-2 mt-2"><?= $residence->getCity(); ?></p>
					</div>

					<div id="menu_icon_<?= $k; ?>" class="absolute group-hover:opacity-100 opacity-0 h-10 w-10 transition duration-500">
						<svg viewBox="0 0 24 24" class="" xmlns="http://www.w3.org/2000/svg" stroke="" stroke-width="0.00024"><g stroke-linecap="round" stroke-linejoin="round" stroke="#15191E" stroke-width="2.4"> <path d="M16.19 2H7.81C4.17 2 2 4.17 2 7.81V16.18C2 19.83 4.17 22 7.81 22H16.18C19.82 22 21.99 19.83 21.99 16.19V7.81C22 4.17 19.83 2 16.19 2ZM17 17.25H7C6.59 17.25 6.25 16.91 6.25 16.5C6.25 16.09 6.59 15.75 7 15.75H17C17.41 15.75 17.75 16.09 17.75 16.5C17.75 16.91 17.41 17.25 17 17.25ZM17 12.75H7C6.59 12.75 6.25 12.41 6.25 12C6.25 11.59 6.59 11.25 7 11.25H17C17.41 11.25 17.75 11.59 17.75 12C17.75 12.41 17.41 12.75 17 12.75ZM17 8.25H7C6.59 8.25 6.25 7.91 6.25 7.5C6.25 7.09 6.59 6.75 7 6.75H17C17.41 6.75 17.75 7.09 17.75 7.5C17.75 7.91 17.41 8.25 17 8.25Z" fill="#292D32"></path> </g> <path d="M16.19 2H7.81C4.17 2 2 4.17 2 7.81V16.18C2 19.83 4.17 22 7.81 22H16.18C19.82 22 21.99 19.83 21.99 16.19V7.81C22 4.17 19.83 2 16.19 2ZM17 17.25H7C6.59 17.25 6.25 16.91 6.25 16.5C6.25 16.09 6.59 15.75 7 15.75H17C17.41 15.75 17.75 16.09 17.75 16.5C17.75 16.91 17.41 17.25 17 17.25ZM17 12.75H7C6.59 12.75 6.25 12.41 6.25 12C6.25 11.59 6.59 11.25 7 11.25H17C17.41 11.25 17.75 11.59 17.75 12C17.75 12.41 17.41 12.75 17 12.75ZM17 8.25H7C6.59 8.25 6.25 7.91 6.25 7.5C6.25 7.09 6.59 6.75 7 6.75H17C17.41 6.75 17.75 7.09 17.75 7.5C17.75 7.91 17.41 8.25 17 8.25Z" fill="var(--primaryColor)"></path></svg>

						<div id="menu_list_<?= $k; ?>" class="absolute opacity-0 left-[-9999px] transition duration-500">
						<?php
						// Create a token for the edit/view link and store it in _SESSION
						$tokenEdit = mb_substr(password_hash(EDIT_TOKEN.$residence->getUserId().$residence->getId().$residence->getName(), PASSWORD_BCRYPT), 7);
						$_SESSION['tokenEdit_'.$residence->getId()] = '$2y$10$'.$tokenEdit;

						// Create a token for the delete link and store it in _SESSION
						$tokenDelete = mb_substr(password_hash(DELETE_TOKEN.$residence->getUserId().$residence->getId().$residence->getName().$residence->getPostalCode(), PASSWORD_BCRYPT), 7);
						$_SESSION['tokenDelete_'.$residence->getId()] = '$2y$10$'.$tokenDelete;
						?>

							<div class="flex-row items-center rounded-md ">
								<!-- View -->
								<a href="<?= PUBLIC_PATH.'residence/?id='.$residence->getId().'&token='.$tokenEdit; ?>"
									class="text-content hover:text-teal-600 text-sm font-medium bg-base-100 hover:bg-base-200 border border-slate-200 rounded-t-lg p-2 inline-flex space-x-1 items-center">
									<span>
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /> <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> </svg>
									</span>
									<span class="hidden md:inline-block md:w-10">View</span>
								</a>

								<!-- Edit -->
								<a href="<?= PUBLIC_PATH.'residence/edit/?id='.$residence->getId().'&token='.$tokenEdit; ?>"
									class="text-content hover:text-teal-600 text-sm font-medium bg-base-100 hover:bg-base-200 border-x border-slate-200 p-2 inline-flex space-x-1 items-center">
									<span>
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
												stroke="currentColor" class="w-6 h-6">
												<path stroke-linecap="round" stroke-linejoin="round"
													d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
										</svg>
									</span>
									<span class="hidden md:inline-block md:w-10">Edit</span>
								</a>

								<!-- Delete -->
								<a href="<?= PUBLIC_PATH.'residence/delete/?id='.$residence->getId().'&token='.$tokenDelete; ?>"
									class="text-content hover:text-teal-600 text-sm font-medium bg-base-100 hover:bg-base-200 border border-slate-200 rounded-b-lg p-2 inline-flex space-x-1 items-center">
									<span>
											<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
												stroke="currentColor" class="w-6 h-6">
												<path stroke-linecap="round" stroke-linejoin="round"
													d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
											</svg>
									</span>
									<span class="hidden md:inline-block md:w-10">Delete</span>
								</a>
							</div>

						</div>

					</div>
				</div>
				<!-- </a> -->

			</div>

		<?php } ?>

		<div class="w-full rounded-lg md:w-1/2 lg:w-1/3">

			<a href="<?= PUBLIC_PATH; ?>residence/add/">
			<div class="grid grid-cols-3 gap-2 p-3 m-2 bg-base-200 hover:bg-base-200 rounded-lg border border-2 border-base-200 hover:border-teal-700">
				<div class="h-28 rounded-xl col-1 p-4">
					<svg width="80px" height="80px" viewBox="0 0 1024 1024" class="icon mx-auto"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M512 191.648V32a32 32 0 0 0-32-32h-96a32 32 0 0 0-32 32v351.648H64v640h864v-416l-416-416z" fill="#EAEAEABB" /><path d="M512 1024V32a32 32 0 0 0-32-32h-96a32 32 0 0 0-32 32v992h160z" fill="" /><path d="M608 768h224v256h-224z" fill="#434854BB" /><path d="M832 608l-224-224v256h224zM128 480h160v160H128z" fill="#469FCCBB" /><path d="M64 384h288v32H64zM928 672L512 256V192l416 416z" fill="" /><path d="M352 288H32a32 32 0 0 0-32 32v32a32 32 0 0 0 32 32h320V288zM1009.472 573.6L512 76.128v135.744l429.6 429.6a31.968 31.968 0 0 0 45.248 0l22.624-22.624a31.968 31.968 0 0 0 0-45.248z" fill="#EF4D4DBB" /><path d="M128 480h160v32H128zM608 416l224 224v-32l-224-224z" fill="" /><path d="M238.24 1024A126.656 126.656 0 0 0 256 960a128 128 0 0 0-256 0c0 23.424 6.752 45.088 17.76 64h220.48zM896 832a127.744 127.744 0 0 0-116.224 75.04A94.848 94.848 0 0 0 736 896a96 96 0 0 0-96 96c0 11.296 2.304 21.952 5.888 32h360.384A126.944 126.944 0 0 0 1024 960a128 128 0 0 0-128-128z" fill="#3AAD73BB" /><path d="M779.776 907.04A94.848 94.848 0 0 0 736 896a96 96 0 0 0-96 96c0 11.296 2.304 21.952 5.888 32h139.872A126.656 126.656 0 0 1 768 960c0-18.944 4.384-36.768 11.776-52.96z" fill="" /></svg>
				</div>

				<div class="h-28 rounded-xl p-3 col-span-2 grid items-end">
					<h3 class="text-xl font-bold text-teal-700 sm:text-2xl lg:text-xl xl:text-2xl">Add a Residence</h3>
				</div>

				<div class="h-44 rounded-xl p-3 col-span-3 grid-rows-3">
					<svg class="" viewBox="-50 -10 125 55" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22ZM12 8.25C12.4142 8.25 12.75 8.58579 12.75 9V11.25H15C15.4142 11.25 15.75 11.5858 15.75 12C15.75 12.4142 15.4142 12.75 15 12.75H12.75L12.75 15C12.75 15.4142 12.4142 15.75 12 15.75C11.5858 15.75 11.25 15.4142 11.25 15V12.75H9C8.58579 12.75 8.25 12.4142 8.25 12C8.25 11.5858 8.58579 11.25 9 11.25H11.25L11.25 9C11.25 8.58579 11.5858 8.25 12 8.25Z" fill="#134e4a"></path></svg>
				</div>
			</div>
			</a>

		</div>

	</div>

	<script>let residences = <?= json_encode($residences, JSON_HEX_TAG); ?>;</script>
	<?php include VIEWS_DIR .'/partials/footer.php'; ?>
	<script src="<?= PUBLIC_PATH ?>js/dashboard.js"></script>
</body>
</html>