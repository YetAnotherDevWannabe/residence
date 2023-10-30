<nav class="navbar navbar-expand-custom navbar-mainbg d-flex">
	
	<nav class="nav nav-pills me-2">
		<!-- Home -->
		<a class="nav-link<?= (ROUTE == '/') ? ' link link-primary' : ''; ?>" href="<?= PUBLIC_PATH; ?>"><i class="bi bi-house"></i>Home</a>
		<span class="mx-2">></span>
		<?php if (isConnected()) { ?>
			<!-- Log-out -->
			<a class="nav-link<?= (ROUTE == '/logout/') ? ' link link-dark' : ''; ?>" href="<?= PUBLIC_PATH; ?>logout/"><i class="bi bi-box-arrow-right"></i>Log-out</a>
			<span class="mx-2">></span>
		<?php } else { ?>
			<!-- Log-in -->
			<a class="nav-link<?= (ROUTE == '/login/') ? ' link link-warning' : ''; ?>" href="<?= PUBLIC_PATH; ?>login/"><i class="bi bi-box-arrow-in-right"></i>Log-in</a>
			<span class="mx-2">></span>
			<!-- Sign-up -->
			<a class="nav-link<?= (ROUTE == '/signup/') ? ' link link-success' : ''; ?>" href="<?= PUBLIC_PATH; ?>signup/"><i class="bi bi-person-plus"></i>Sign-up</a>
		<?php } ?>
	</nav>
</nav>