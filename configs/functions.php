<?php

// Start a new session on the whole website ($_SESSION)
session_start();


// Convert all classic errors to PHP ErrorException  (this way we can catch them in a 'try{}catch{}')
function exceptions_error_handler($severity, $message, $filename, $lineno) {
	throw new ErrorException($message, 0, $severity, $filename, $lineno);
}
set_error_handler('exceptions_error_handler');


// Function to get the current path
// ex: "http://monsite.com/contact-us/", gives "/contact-us/"
// ex: "http://monsite.fr/article/test/?name=alice", gives "/article/test/"
function request_path() {
	$request_uri = explode('/', ( $_SERVER['REQUEST_URI'] ));
	$script_name = explode('/', ( $_SERVER['SCRIPT_NAME'] ));
	$parts = array_diff_assoc($request_uri, $script_name);
	$path = implode('/', $parts);

	if ( empty($path) ) {
		return '/';
	}

	$path = '/' . $path;
	if ( ( $position = strpos($path, '?') ) !== FALSE ) {
		$path = substr($path, 0, $position);
	}

	return $path;
}


// Function to create and send an instance of a connection to the DB
function connectDB() {
	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset:utf8', DB_USER, DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}


// Function that returns true if user is connected, false if not
function isConnected() {
	return isset($_SESSION['user']);
}


// Function that returns true if connected user is admin
function isAdmin() {
	if ( isset($_SESSION['user']) ) {
		if ($_SESSION['user']->gettype() == 'ADMIN') {
			return true;
		}
	}

	return false;
}


// Log function (exemple, very basic)
function updateLog(string $logfile, string $logInfo) {
	$logDate = "<<-- ".$logfile." --- from the ".(new DateTime())->format('Y-m-d - H:i:s')." :\n";
	$logSeparator = "----------------------------------------------------->>\n";
	file_put_contents(LOG_DIR.$logfile, $logDate, FILE_APPEND);
	file_put_contents(LOG_DIR.$logfile, $_SERVER['REQUEST_URI'].PHP_EOL, FILE_APPEND);
	file_put_contents(LOG_DIR.$logfile, $logInfo.PHP_EOL, FILE_APPEND); // Actual Log
	file_put_contents(LOG_DIR.$logfile, $logSeparator, FILE_APPEND);
	file_put_contents(LOG_DIR.$logfile, $logSeparator.PHP_EOL, FILE_APPEND);
}


/**
 * Configure a PHPMailer instance and return it for later usage
 * 
 * @param PHPMailer $mail : new PHPMailer(true) passing `true` enables exceptions
 * @param SMTP $smtp : new SMTP()
 * 
 * @return PHPMailer $mail
 */
function configMailSMTP($mail, $smtp) {
	$mail->SMTPDebug	= $smtp::DEBUG_OFF;					// DEBUG_SERVER: Enable verbose debug output, DEBUG_OFF
	$mail->isSMTP();												// Send using SMTP
	$mail->Host			= MAIL_HOST;							// Set the SMTP server to send through
	$mail->SMTPAuth	= false;									// Enable SMTP authentication
	$mail->Username	= MAIL_USERNAME;						// SMTP username
	$mail->Password	= MAIL_PASSWORD;						// SMTP password
	$mail->SMTPSecure	= $mail::ENCRYPTION_SMTPS;			// Enable implicit TLS encryption
	$mail->Port			= MAIL_PORT;							// TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

	return $mail;
}