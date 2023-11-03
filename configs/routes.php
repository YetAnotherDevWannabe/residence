<?php

use App\Controllers\MainController;

$mainController = new MainController();

// List every route and their controller. Every URL corresponds to a page on the website
switch ( ROUTE ) {
	// Home page
	case '/';
		$mainController->home();
		break;

	// Dashboard
	case '/dashboard/';
		$mainController->dashboard();
		break;



	/*
	 * Sessions pages
	 */
	// SignUp
	case '/signup/';
	$mainController->signUp();
	break;

	// LogIn
	case '/login/';
		$mainController->logIn();
		break;

	// LogOut
	case '/logout/';
		$mainController->logOut();
		break;



	/*
	 * Profil pages
	 */
	// Profil
	case '/profil/';
		$mainController->profil();
		break;

	// Edit
	case '/profil/edit/';
		$mainController->profilEdit();
		break;


	/*
	 * Other pages
	 */
	// Page 404: if the path is not found, then 404 is loaded
	default:
		$mainController->page404();
		break;
}