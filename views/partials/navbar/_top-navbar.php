<div class="navbar bg-base-300 h-14">

	<div class="navbar-start ms-14">
		<?php $TopNavStart ? include $TopNavStart : ''; ?>
	</div>

	<div class="navbar-center lg:flex">
		<?php $TopNavCenter ? include $TopNavCenter : ''; ?>
	</div>

	<?php if (isConnected()) { ?>
	<div class="navbar-end">
		<?php $TopNavEnd ? include $TopNavEnd : ''; ?>
	</div>
	<?php } ?>
</div>