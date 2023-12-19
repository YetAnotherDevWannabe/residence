<?php
// Namespace must be the precise location of the file while replacing 'src' by 'App'
// File location: 'src/Controllers/MainController.php' => 'App/Controllers'
// Namespace: 'App\Controllers'
// Class name needs to be the same as the file name: 'MainController'
namespace App\Controllers;

// Importing necessary classes

use App\Models\Managers\RemembermeManager;
use App\Models\Managers\ResidenceManager;
use App\Models\Managers\UserManager;
use App\Models\Rememberme;
use App\Models\Residence;
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
		// Check for Rememberme
		if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
		}

		// Load home view
		require VIEWS_DIR.'home.php';
	}

	/**
	 * Controller for dashboard page
	 */
	public function dashboard() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		} else if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
		}

		// Load home view
		require VIEWS_DIR.'dashboard.php';
	}

	/**
	 * Controller for residence add
	 */
	public function residenceAdd() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		} else if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
		}

		////----- 1. Check if form vars exists -----////
		if ( isset($_POST['type']) &&
			isset($_POST['name']) &&
			isset($_POST['address']) &&
			isset($_POST['postal-code']) &&
			isset($_POST['city']) ) {

			////----- 2. Check if there are errors -----////
			// type
			if ( $_POST['type'] != 'house' && $_POST['type'] != 'apartment' ) {
				$errors['type'] = 'Residence type error';
				updateLog(ERROR_LOG, $errors['type']);
			}

			// name
			if ( mb_strlen($_POST['name']) < 3 || mb_strlen($_POST['name']) > 255 ) {
				$errors['name'] = 'Your residence name must be between 3 and 255 characters';
				updateLog(ERROR_LOG, $errors['name']);
			}

			// address
			if ( mb_strlen($_POST['address']) < 3 || mb_strlen($_POST['address']) > 999 ) {
				$errors['address'] = 'Your residence address must be between 3 and 999 characters';
				updateLog(ERROR_LOG, $errors['address']);
			}

			// postal-code
			$regex = '/^[0-9]{5,6}$/';
			if ( !preg_match($regex, $_POST['postal-code']) ) {
				$errors['postal-code'] = 'Postal is incorrect (must 5 or 6 numbers long)';
				updateLog(ERROR_LOG, $errors['postal-code']);
			}

			// city
			if ( mb_strlen($_POST['city']) < 3 || mb_strlen($_POST['city']) > 100 ) {
				$errors['city'] = 'Your residence city must be between 3 and 100 characters';
				updateLog(ERROR_LOG, $errors['city']);
			}

			////----- 3. If no error then continue -----////
			if ( !isset($errors) ) {

				// Create a new Residence and start hydrating it
				$residence = new Residence();
				$residence
					->setUserId($_SESSION['user']->getId())
					->setName($_POST['name'])
					->setAddress($_POST['address'])
					->setPostalCode($_POST['postal-code'])
					->setCity($_POST['city'])
					->setType(mb_strtolower($_POST['type']))
					->setDeleted(false);

				// We save the new user in DB
				$residenceManager = new ResidenceManager();
				$status = $residenceManager->save($residence);

				if ( $status ) {
					$success = 'Your residence was edited successfully';
					updateLog(INFO_LOG, 'Residence edited successfully by '.$_SESSION['user']->getEmail());
				} else {
					$errors['server'] = 'Problem with the Database, please try again later';
					updateLog(ERROR_LOG, $errors['server']);
				}

			}
		}

		// Load home view
		require VIEWS_DIR.'residence-add.php';
	}

	/**
	 * Controller for residence delete
	 */
	public function residenceDelete() {
		

		//https://www.phptutorial.net/php-tutorial/php-csrf/
		
		// $_SESSION['tokenDelete_'.$id] = password_verify($_SESSION['user']->getId().$id.$name.$postal_code, $tokenDelete)
	}

	/**
	 * Controller for residence edit
	 */
	public function residenceEdit() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		} else if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
		}

		////----- 1. Check if form vars exists -----////
		if ( isset($_POST['id']) &&
			isset($_POST['token']) &&
			isset($_POST['type']) &&
			isset($_POST['name']) &&
			isset($_POST['address']) &&
			isset($_POST['postalCode']) &&
			isset($_POST['city']) ) {

			////----- 2. Check if there are errors -----////
			// id & token
			$residenceManager = new ResidenceManager();
			$residenceOldName = $residenceManager->getOneById($_POST['id'])->getName();
			if( !password_verify($_SESSION['user']->getId().$_POST['id'].$residenceOldName, $_POST['token']) ) {
				$errors['token'] = 'Token is being tempered with';
				updateLog(ERROR_LOG, $errors['token']);
			}

			// type
			if ( $_POST['type'] != 'house' && $_POST['type'] != 'apartment' ) {
				$errors['type'] = 'Residence type error';
				updateLog(ERROR_LOG, $errors['type']);
			}

			// name
			if ( mb_strlen($_POST['name']) < 3 || mb_strlen($_POST['name']) > 255 ) {
				$errors['name'] = 'Your residence name must be between 3 and 255 characters';
				updateLog(ERROR_LOG, $errors['name']);
			}

			// address
			if ( mb_strlen($_POST['address']) < 3 || mb_strlen($_POST['address']) > 999 ) {
				$errors['address'] = 'Your residence address must be between 3 and 999 characters';
				updateLog(ERROR_LOG, $errors['address']);
			}

			// postalCode
			$regex = '/^[0-9]{5,6}$/';
			if ( !preg_match($regex, $_POST['postalCode']) ) {
				$errors['postalCode'] = 'Postal is incorrect (must 5 or 6 numbers long)';
				updateLog(ERROR_LOG, $errors['postalCode']);
			}

			// city
			if ( mb_strlen($_POST['city']) < 3 || mb_strlen($_POST['city']) > 100 ) {
				$errors['city'] = 'Your residence city must be between 3 and 100 characters';
				updateLog(ERROR_LOG, $errors['city']);
			}

			////----- 3. If no error then continue -----////
			if ( !isset($errors) ) {				
				// Create a new Residence and start hydrating it
				$residenceUpdate = new Residence();
				$residenceUpdate
					->setId($_POST['id'])
					->setUserId($_SESSION['user']->getId())
					->setName($_POST['name'])
					->setAddress($_POST['address'])
					->setPostalCode($_POST['postalCode'])
					->setCity($_POST['city'])
					->setType(mb_strtolower($_POST['type']));

				// We save the new user in DB
				$status = $residenceManager->update($residenceUpdate);

				if ( $status ) {
					$success = 'Your residence was added successfully';
					updateLog(INFO_LOG, 'Residence added successfully by '.$_SESSION['user']->getEmail());
				} else {
					$errors['server'] = 'Problem with the Database, please try again later';
					updateLog(ERROR_LOG, $errors['server']);
				}
				clearTokenFromSession();

			}
		}

		// Load home view
		require VIEWS_DIR.'residence-edit.php';
	}

	/**
	 * Controller for Sign-up
	 */
	public function signUp() {
		// Redirect if already connected
		if ( isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		} else if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
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
		require VIEWS_DIR.'signup.php';
	}

	/**
	 * Controller for Log-in
	 */
	public function logIn() {
		// Redirect if already connected
		if ( isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		} else if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
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

						// Rememberme option \\
						//----------------------\\
						if( isset($_POST['rememberme']) ) {

							// Generates a crypographically secure random string
							$token = opensslRandomGenerate(128);

							// Check if the token is cryptographically strong (returns 0 if not)
							if($token) {

								// Load RemembermeManager
								$remembermeManager = new RemembermeManager();

								//	Get the current datetime
								$datetimeNow = new DateTime();

								// Save the token in DB
								$rememberme = new Rememberme();
								$rememberme->setToken($token)
									->setUserId($userToConnect->getId())
									->setCreationDate($datetimeNow)
									->setExpirationDate($datetimeNow->modify('+30 days'))
									->setValid(true);

								// Check if user already has a Rememberme token
								$existingUserToken = $remembermeManager->getOneByUserId($userToConnect->getId());

								if( !empty($existingUserToken) ) {

									// Inavalidate any valid token from the user
									$invalidStatus = $remembermeManager->invalidate($existingUserToken);
									// $invalidStatus should be true

								} else {

									// Save the Rememberme object in DB
									$remembermeStatus = $remembermeManager->save($rememberme);

									// If no object saved in DB w/o error, continue
									if($remembermeStatus) {
										// Create a COOKIE /w the rememberme info in it
										// https://stackoverflow.com/a/17266448
										$mac = hash_hmac('sha256', $userToConnect->getId().':'.$token, STAY_CONNECTED_KEY);
										$cookie = $userToConnect->getId().':'.$token.':'.$mac;
										setcookie('rememberme', $cookie, $datetimeNow->modify('+30 days')->format('U'));
									}

								}
							}
						}

						// Login user \\
						//------------\\
						logUserIn($userToConnect);
						$success = 'Your are now logged-in';

					} else {
						$errors['password'] = 'Incorrect password';
						updateLog(ERROR_LOG, $errors['password']);
					}
				}

			}
		}

		// Charge la vue login.php dans le dossier des vues "views"
		require VIEWS_DIR.'login.php';
	}

	/**
	 * Controller for Log-out
	 */
	public function logOut() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		}

		
		// Load $_SESSION user from DB
		$userManager = new UserManager();
		$user = $userManager->getOneById($_SESSION['user']->getId());

		// Invalidates Rememberme token if one is found & Logout user out
		invalidateRemembermeToken($user);
		logUserOut($user);

		// Load signout view
		require VIEWS_DIR.'logout.php';
	}


	/**
	 * Controller for profil
	 */
	public function profil() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		} else if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
		}

		// Load profil view
		require VIEWS_DIR.'profil.php';
	}

	/**
	 * Controller for profil edit
	 */
	public function profilEdit() {
		// Redirect if not connected
		if ( !isConnected() ) {
			header('location: '.PUBLIC_PATH);
			die();
		} else if( isRemembered() ) {

			// Load User from COOKIE
			$userManager = new UserManager();
			$userId = mb_substr($_COOKIE['rememberme'], 0, mb_strpos($_COOKIE['rememberme'], ':'));
			$userRemembered = $userManager->getOneById($userId);

			// Connect user \\
			//--------------\\
			$_SESSION['user'] = $userRemembered;
			$success = 'Your are now logged-in';
			updateLog(INFO_LOG, 'user '.$userRemembered->getEmail().' logged-in');
		}

		// Load the $_SESSION user from DB
		$userManager = new UserManager();
		$dbUser = $userManager->getOneById($_SESSION['user']->getId());

		if ( empty($dbUser) ) {
			$errors['server'] = 'Couldn\'t find the user in DB';
			updateLog(ERROR_LOG, $errors['server']);
		}

		////----- 1. Check if form vars exists -----////
		if ( isset($_POST['name']) &&
			isset($_POST['email']) &&
			isset($_POST['old-password']) ) {

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

			if( isset($_POST['new-password']) && isset($_POST['confirm-password']) && !empty($_POST['new-password']) && !empty($_POST['confirm-password']) ) {
				// new-password
				if(ENV == 'prod') {
					// $regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{16,4096}$/';
					$regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{16,255}$/';
					$min = 16;
				} else if(ENV == 'dev') {
					$regex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,255}$/';
					$min = 8;
				}
				if ( !preg_match($regex, $_POST['new-password']) ) {
					if( mb_strlen($_POST['new-password']) == 0) {
						$errors['new-password'] = 'New password cannot be empty';
					} else {
						$errors['new-password'] = 'New password doesn\'t respect complexity rules ([a-z][A-Z][0-9][special_chars] minimum length: '.$min.')';
					}
					updateLog(ERROR_LOG, $errors['new-password']);
				}

				//confirm-password
				if ( $_POST['confirm-password'] != $_POST['new-password'] ) {
					if( mb_strlen($_POST['confirm-password']) == 0) {
						$errors['confirm-password'] = 'Password confirmation cannot be empty';
					} else {
						$errors['confirm-password'] = 'Password and confirmation don\'t match';
					}
					updateLog(ERROR_LOG, $errors['confirm-password']);
				}
			}


			////----- 3. If no error then continue -----////
			if ( !isset($errors) ) {

				if( isset($FILES['avatar']) ) {

					////----- $_FILES['avatar'] -----////
					switch ($_FILES['avatar']['error']) {
						case 1:
							$errors['avatar'] = 'Server cannot accept file this big';
							updateLog(ERROR_LOG, $errors['avatar']);
							break;

						case 3:
							$errors['avatar'] = 'Bad connection to server. Try again later';
							updateLog(ERROR_LOG, $errors['avatar']);
							break;

						case 4:
							$errors['avatar'] = 'No file selected';
							updateLog(ERROR_LOG, $errors['avatar']);
							break;

						case 6:
							$errors['log'] = 'Destination folder not found on server';
							updateLog(ERROR_LOG, $errors['log']);
							break;

						case 7:
							$errors['log'] = 'Problem with writting privileges for destination folder';
							updateLog(ERROR_LOG, $errors['log']);
							break;

						case 8:
							$errors['log'] = 'PHP error';
							updateLog(ERROR_LOG, $errors['log']);
							break;
					}

					// Check if file size is under the accepted limit
					if ( $_FILES['avatar']['size'] > FILE_SIZE_MAX ) {
						$errors['avatar'] = 'File size over the accepted limit ('.sizeConverter(FILE_SIZE_MAX).')';
						updateLog(ERROR_LOG, $errors['avatar']);
					}

					// Check file type is allowed
					//TODO

					////Prepare the File
						// If no error, we move the file to it's correct folder on server
						$pathinfo = pathinfo($_FILES['avatar']['name']);
						$originalExt = $pathinfo['extension'];

						// We start by giving the file a new name
						$newFileName = htmlspecialchars(str_replace(' ', '', $dbUser->getName()));
						$newFilePath = $newFileName.'.'.$originalExt;

						// Then move it to the final folder
						move_uploaded_file($_FILES['avatar']['tmp_name'], $newFilePath);
						
						// Save old avatar to be deleted
						$oldAvatar = $dbUser->getAvatar();

				}

				// Check user password
				if( password_verify($_POST['old-password'], $dbUser->getPasswordHash()) ) {

					//// Prepare a new User to make the changes in DB
						$userUpdate = new User();
						$userUpdate
							->setId($dbUser->getId())
							->setEmail($_POST['email'])
							->setName($_POST['name']);

						if( !empty($_POST['new-password']) ) {
							$userUpdate->setPasswordHash( password_hash($_POST['new-password'], PASSWORD_BCRYPT) );
						}
						if( isset($FILES['avatar']) ) {
							$userUpdate->setAvatar($newFilePath);
						}

						// We save the new user in DB
						$status = $userManager->update($userUpdate);

						if ( $status ) {
							$success = 'Your account was successfully edited';
							updateLog(INFO_LOG, 'Account successfully edited by '.$userUpdate->getEmail());

							// Remove old avatar after update to account
							if( !empty($oldAvatar) ) { 
								unlink(__DIR__.'../../upload/'.$oldAvatar);
							}

							// Update $userDB to update $_SESSION user
							$dbUser
								->setEmail($_POST['email'])
								->setName($_POST['name'])
								->setAvatar($newFilePath);

							if( !empty($_POST['new-password']) ) {
								$dbUser->setPasswordHash( password_hash($_POST['new-password'], PASSWORD_BCRYPT) );
							}
							if( isset($FILES['avatar']) ) {
								$dbUser->setAvatar($newFilePath);
							}

							$_SESSION['user'] = $dbUser;

						} else {
							$errors['server'] = 'Problem with the Database, please try again later';
							updateLog(ERROR_LOG, $errors['server']);
						}

				} else {
					$errors['old-password'] = 'Old password is incorrect';
				}

			}
		}

		// Load profil view
		require VIEWS_DIR.'profil-edit.php';
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
		require VIEWS_DIR.'404.php';
	}
}