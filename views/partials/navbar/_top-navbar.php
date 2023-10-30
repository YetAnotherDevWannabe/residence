<div class="navbar bg-base-300 h-14">

	<div class="navbar-start ms-14">
		<?php $topNavStart ? include $topNavStart : ''; ?>
	</div>

	<div class="navbar-center lg:flex">
		<?php $topNavCenter ? include $topNavCenter : ''; ?>
	</div>

	<?php if (isConnected()) { ?>
	<div class="navbar-end">
		<?php $topNavEnd ? include $topNavEnd : ''; ?>
	</div>
	<?php } ?>
</div>