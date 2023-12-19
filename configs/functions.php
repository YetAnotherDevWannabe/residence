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
	$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset:utf8', DB_USER, DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}


// Function that logs the User in using SESSION
function logUserIn(App\Models\User $user) {
	$_SESSION['user'] = $user;
	updateLog(INFO_LOG, 'user '.$user->getEmail().' logged-in');
}


// Function that returns true if user is connected, false if not
function isConnected() {
	return isset($_SESSION['user']);
}


// Function that returns true if user is connected via StayConnnected COOKIE, false if not
function isRemembered() {
	// Check if a 'rememberme' COOKIE exists and gets it's value
	$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';

	if ($cookie) {
		// Get the info from the COOKIE
		list($cookieUserId, $cookieToken, $cookieMac) = explode(':', $cookie);

		// Check if the info from the COOKIE as not been tempered with, if it has been modified, abort and log out user forcefully
		if (!hash_equals(hash_hmac('sha256', $cookieUserId.':'.$cookieToken, STAY_CONNECTED_KEY), $cookieMac)) {

			// Invalidates Rememberme token if one is found & Logout user out
			invalidateRemembermeToken($cookieUserId, true);
			logUserOut($cookieUserId, true);

			return false;
		}

		//// If info from COOKIE matches that of DB, proceed to log user in
		$remembermeManager = new App\Models\Managers\RemembermeManager();
		$rememberme = $remembermeManager->getOneByUserId($cookieUserId);

		if (!isset($rememberme)) {
			return false;
		}

		// Check if the data from the COOKIE matches the data from the DB
		if (hash_equals($rememberme->getToken(), $cookieToken)) {

			// Load user from DB
			$userManager = new App\Models\Managers\UserManager();
			$user = $userManager->getOneById($cookieUserId);

			// Log user in
			logUserIn($user);
			return true;

		} else {

			// Load user from DB
			$userManager = new App\Models\Managers\UserManager();
			$user = $userManager->getOneById($cookieUserId);

			// Invalidates Rememberme token if one is found & Logout user out
			invalidateRemembermeToken($user);
			logUserOut($user);

			return false;

		}
	}
}

/**
 * Function that logs the user out (SESSION + COOKIE + Rememberme in DB). If sending only userId, set $userIdOnly to true
 */
function logUserOut($user, bool $userIdOnly = false) {
	if($userIdOnly) {
		// Load user from DB
		$userManager = new App\Models\Managers\UserManager();
		$user = $userManager->getOneById($user);
	}

	// Remove user from SESSION
	unset($_SESSION['user']);

	// Remove token CRSF from SESSION
	unset($_SESSION['tokenCSRF']);

	// Remove user from COOKIE
	unset($_COOKIE['rememberme']);

	// Update the log
	updateLog(INFO_LOG, 'user '.$user->getEmail().' logged-out');
}


/**
 * Function that invalidates all Rememberme tokens from a user. If sending only userId, set $userIdOnly to true
 */
function invalidateRemembermeToken($user, bool $userIdOnly = false) {
	if($userIdOnly) {
		// Load user from DB
		$userManager = new App\Models\Managers\UserManager();
		$user = $userManager->getOneById($user);
	}

	// Search for a valid Rememberme for the user
	$remembermeManager = new App\Models\Managers\RemembermeManager();
	$existingUserToken = $remembermeManager->getOneByUserId($user->getId());

	// If a token is found, Invalidates it
	if( !empty($existingUserToken) ) {
		$invalidStatus = $remembermeManager->invalidate($existingUserToken);
		return $invalidStatus;
	}
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
// function configMailSMTP($mail, $smtp) {
// 	$mail->SMTPDebug	= $smtp::DEBUG_OFF;					// DEBUG_SERVER: Enable verbose debug output, DEBUG_OFF
// 	$mail->isSMTP();												// Send using SMTP
// 	$mail->Host			= MAIL_HOST;							// Set the SMTP server to send through
// 	$mail->SMTPAuth	= false;									// Enable SMTP authentication
// 	$mail->Username	= MAIL_USERNAME;						// SMTP username
// 	$mail->Password	= MAIL_PASSWORD;						// SMTP password
// 	$mail->SMTPSecure	= $mail::ENCRYPTION_SMTPS;			// Enable implicit TLS encryption
// 	$mail->Port			= MAIL_PORT;							// TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

// 	return $mail;
// }


/**
 * Generate and returns a cryptographically safe random string
 * 
 * @param int $length : length of the string to return
 * 
 * @return int $hex|0
 */
function opensslRandomGenerate(int $length) {
	// Generate cryptographically safe random string
	$bytes = openssl_random_pseudo_bytes($length, $strong_result);

	// Converte from Binary to Hexadecimal
	$hex = bin2hex($bytes);

	if($strong_result) {
		return $hex;
	} else {
		return 0;
	}
}

/**
 * Returns the size of a file in a user friendly readable form
 * 
 * @param string $sizeInBytes	A string containing the size of a file in bytes
 * @return string
 */
function sizeConverter($sizeInBytes) {
	$sizeLen = strlen($sizeInBytes);
	switch ($sizeLen) {
		case 1: case 2: case 3:
			return $sizeInBytes.' B';
			break;

		case 4: case 5: case 6:
			return substr(($sizeInBytes / 1024), 0, strpos(($sizeInBytes / 1024), '.') + 3).' KB';
			break;

		case 7: case 8: case 9:
			return substr(($sizeInBytes / 1048576), 0, strpos(($sizeInBytes / 1048576), '.') + 3).' MB';
			break;

		case 10: case 11: case 12:
			return substr(($sizeInBytes / 1073741824), 0, strpos(($sizeInBytes / 1073741824), '.') + 3).' GB';
			break;

		case 13: case 14: case 15:
			return substr(($sizeInBytes / 1099511627776), 0, strpos(($sizeInBytes / 1099511627776), '.') + 3).' TB';
			break;

		default:
			return $sizeInBytes;
			break;
	}
}

/**
 * Returns a random CRSF token
 * 
 * @return string
 */
function createToken() {
	return md5(uniqid(mt_rand(), true));
}

/**
 * Clears the unused CSRF tokens from $_SESSION
 * 
 * @return string
 */
function clearTokenFromSession() {
	foreach ($_SESSION as $key => $item) {
		if( mb_substr($item, 0, 10) == 'tokenEdit_' || mb_substr($item, 0, 12) == 'tokenDelete_' ) {
			unset($_SESION[$key]);
		}
	}
}