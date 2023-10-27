<?php
// Inlcudes composer dependencies
require __DIR__ .'/../vendor/autoload.php';

// Includes general functions and configs
require __DIR__ .'/../configs/functions.php';

// Includes all non sensitive parameters
require __DIR__ .'/../configs/params.php';

// Includes sensitive parameters
require __DIR__ .'/../configs/params.'.PARAMS_FILE.'.php';

// Includes the routing file
try {
	// Includes routing file
	require __DIR__ .'/../configs/routes.php';
} catch (Throwable $e) {
	?>
	<div style="background-color: #FFa2a2; padding: 15px; border-radius: 8px">
		<h1><b>PHP error !</b></h1>
		<hr>
		<p><b>File : </b><?= $e->getFile(); ?></p>
		<p><b>Line : </b><?= $e->getLine(); ?></p>
		<p><b>Message : </b><span style="font-size: 20px"><?= $e->getMessage(); ?></span></p>
		<hr>
		<?php dump($e->getTrace()); ?>
	</div>
	<?php
}