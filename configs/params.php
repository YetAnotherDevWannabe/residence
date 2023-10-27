<?php
// What params.php file is getting loaded
define('PARAMS_FILE', 'local');

// Environment 'dev' or 'prod'
define('ENV', 'dev');

// The route of the current URL
define('ROUTE', request_path());

// Location of the folder containing the views
define('VIEWS_DIR', __DIR__ . '/../views/');

// Location of the public folder (containing CSS/JS/Images/etc..)
// Will be used to construct links from the front-end (include of images, css, js, etc...)
define('PUBLIC_PATH', mb_substr($_SERVER['SCRIPT_NAME'], 0, -(mb_strlen(basename(__FILE__)))) . '/');

// Returns the name of the host server (such as www.google.com)
define('HOST', $_SERVER['SERVER_NAME']);

// Location of the upload folder
define('UPLOAD_DIR', __DIR__ . '/../upload/');

// Max upload file size allowed by user
define('FILE_SIZE_MAX_USER', '5242880'); // 5 Mo

// Log directory
define('LOG_DIR', __DIR__.'/logs/');
define('DB_LOG',		'db_logs.txt');		// exemple of log files
define('ERROR_LOG',	'error_logs.txt');	// exemple of log files
define('INFO_LOG',	'info_logs.txt');		// exemple of log files