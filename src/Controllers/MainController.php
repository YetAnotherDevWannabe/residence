<?php
// Namespace must be the precise location of the file while replacing 'src' by 'App'
// File location: 'src/Controllers/MainController.php' => 'App/Controllers'
// Namespace: 'App\Controllers'
// Class name needs to be the same as the file name: 'MainController'
namespace App\Controllers;

// Importing necessary classes
use App\Models\Managers\UserManager;
use App\Models\User;
use \DateTime;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

class MainController
{
	/**
	 * Controller for home page
	 */
	public function home() {
		// Load home view
		require VIEWS_DIR . 'home.php';
	}


	/**
	 * Controller for Sign-up
	 */
	public function signUp() {

		// Redirect if already connected
		if ( isConnected() ) {
			header('location: ' . PUBLIC_PATH);
			die();
		}

		////----- 1. Check if form vars exists -----////
		if ( isset($_POST['name']) &&
			isset($_POST['email']) &&
			isset($_POST['password']) &&
			isset($_POST['confirm-password']) ) {

			////----- 2. Check if there are errors -----////
			// name
			if ( mb_strlen($_POST['name']) < 3 || mb_strlen($_POST['name']) > 255 ) {
				$errors['name'] = 'Your name must be between 3 and 255 characters';
				updateLog(ERROR_LOG, $errors['name']);
			}

			// email
			if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
				if( mb_strlen($_POST['password']) == 0) {
					$errors['email'] = 'Email cannot be empty';
				} else {
					$errors['email'] = 'Invalid email';
				}
				updateLog(ERROR_LOG, $errors['email']);
			}

			// password
			if(ENV == 'prod') {
				// $regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{16,4096}$/';
				$regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{16,255}$/';
				$min = 16;
			} else if(ENV == 'dev') {
				$regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,255}$/';
				$min = 8;
			}
			if ( !preg_match($regex, $_POST['password']) ) {
				if( mb_strlen($_POST['password']) == 0) {
					$errors['password'] = 'Password cannot be empty';
				} else {
					$errors['password'] = 'Password doesn\'t respect complexity rules ([a-z][A-Z][0-9][special_chars] minimum length: '.$min.')';
				}
				updateLog(ERROR_LOG, $errors['password']);
			}

			// confirm-password
			if ( $_POST['confirm-password'] != $_POST['password'] ) {
				if( mb_strlen($_POST['confirm-password']) == 0) {
					$errors['confirm-password'] = 'Password confirmation cannot be empty';
				} else {
					$errors['confirm-password'] = 'Password and confirmation don\'t match';
				}
				updateLog(ERROR_LOG, $errors['confirm-password']);
			}

			// terms
			if ( !isset($_POST['terms']) ) {
				$errors['terms'] = 'You must accept the terms & conditions';
				updateLog(ERROR_LOG, $errors['terms']);
			}

			////----- 3. If no error then continue -----////
			if ( !isset($errors) ) {
				$userManager = new UserManager();
				$checkIfUserExists = $userManager->getOneBy('email', $_POST['email']);

				if ( !empty($checkIfUserExists) ) {
					$errors['email'] = 'An account is already associated with this email';
					updateLog(ERROR_LOG, $errors['email']);
				} else {
					$user = new User();
					$user
						->setEmail($_POST['email'])
						->setPasswordHash(password_hash($_POST['password'], PASSWORD_BCRYPT))
						->setName($_POST['name'])
						->setAvatar(PUBLIC_PATH.'img/avatar_base.svg')
						->setRegistrationDate(new DateTime())
						->setDeleted(false);

					// We save the new user in DB
					$status = $userManager->save($user);

					if ( $status ) {
						$success = 'Your account was successfully created';
						updateLog(INFO_LOG, 'Account successfully created by '.$user->getEmail());
					} else {
						$errors['server'] = 'Problem with the Database, please try again later';
						updateLog(ERROR_LOG, $errors['server']);
					}
				}

			}
		}

		// Charge la vue register.php dans le dossier views
		require VIEWS_DIR . 'signup.php';
	}

	/**
	 * Controller for Log-in
	 */
	public function logIn() {
		// Redirect if already connected
		if ( isConnected() ) {
			header('location: ' . PUBLIC_PATH);
			die();
		}

		////----- 1. Check if form vars exists -----////
			if ( isset($_POST['email']) &&
			isset($_POST['password']) ) {

			////----- 2. Check if there are errors -----////
			// email
			if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
				if( mb_strlen($_POST['email']) == 0) {
					$errors['email'] = 'Email cannot be empty';
				} else {
					$errors['email'] = 'Invalid email';
				}
				updateLog(ERROR_LOG, $errors['email']);
			}

			// password
			if(ENV == 'prod') {
				// $regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{16,4096}$/';
				$regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{16,255}$/';
				$min = 16;
			} else if(ENV == 'dev') {
				$regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,255}$/';
				$min = 8;
			}
			if ( !preg_match($regex, $_POST['password']) ) {
				if( mb_strlen($_POST['password']) == 0) {
					$errors['password'] = 'Password cannot be empty';
				} else {
					$errors['password'] = 'Password doesn\'t respect complexity rules ([a-z][A-Z][0-9][special_chars] minimum length: '.$min.')';
				}
				updateLog(ERROR_LOG, $errors['password']);
			}

			////----- 3. If no error then continue -----////
			if ( !isset($errors) ) {

				$userManager = new UserManager();
				$userToConnect = $userManager->getOneBy('email', $_POST['email']);

				if ( empty($userToConnect) ) {
					$errors['email'] = 'No account associated with this email';
					updateLog(ERROR_LOG, $errors['email']);
				} else {
					// Verify the password gives the same hash as the hash saved in DB
					if ( password_verify($_POST['password'], $userToConnect->getPasswordHash()) ) {

						// StayConnected option
						if( isset($_POST['stayConnected']) ) {
							// TODO:StayConnected
						}

						// Connect user
						$_SESSION['user'] = $userToConnect;
						$success = 'Your are now logged-in';
						updateLog(INFO_LOG, 'user '.$userToConnect->getEmail().' logged-in');
					} else {
						$errors['password'] = 'Incorrect password';
						updateLog(ERROR_LOG, $errors['password']);
					}
				}

			}
		}

		// Charge la vue login.php dans le dossier des vues "views"
		require VIEWS_DIR . 'login.php';
	}

	/**
	 * Controller for Log-out
	 */
	public function logOut() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: ' . PUBLIC_PATH);
			die();
		}

		// Remove user form $_SESSION
		unset($_SESSION['user']);

		// Load signout view
		require VIEWS_DIR . 'logout.php';
	}


	/**
	 * Controller for profil
	 */
	public function profil() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: ' . PUBLIC_PATH);
			die();
		}

		//TODO: Do things here

		// Load profil view
		require VIEWS_DIR . 'profil.php';
	}


	/**
	 * Controller for 404 page
	 */
	public function page404() {
		// Create a log entry in the ERROR_LOG file
		updateLog(INFO_LOG, 'access to page : "'.ROUTE.'" created a 404 error.');
		updateLog(ERROR_LOG, '404');

		// Modify HTTP code to 404 instead of 200
		header('HTTP/1.0 404 Not Found');

		// Load 404 view
		require VIEWS_DIR . '404.php';
	}
}