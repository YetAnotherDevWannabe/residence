<?php

use App\Controllers\MainController;

$mainController = new MainController();

// List every route and their controller. Every URL corresponds to a page on the website
switch ( ROUTE ) {
	// Home page
	case '/';
		$mainController->home();
		break;


	/**
	 * Residence management
	 */
	// Dashboard
	case '/dashboard/';
		$mainController->dashboard();
		break;

	// Residence Add
	case '/residence/add/';
		$mainController->residenceAdd();
		break;

	// Residence View
	case '/residence/';
		// $mainController->residenceView();
		break;

	// Residence Delete
	case '/residence/delete/';
		$mainController->residenceDelete();
		break;

	// Residence Edit
	case '/residence/edit/';
		$mainController->residenceEdit();
		break;



	/**
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



	/**
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


	/**
	 * Other pages
	 */
	// Page 404: if the path is not found, then 404 is loaded
	default:
		$mainController->page404();
		break;
}