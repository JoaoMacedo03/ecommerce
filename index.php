<?php 

	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	// use Hcode\Page;
	// use Hcode\PageAdmin;
	// use \Hcode\Model\User;
	// use \Hcode\Model\Category;

	$app = new Slim();

	$app->config('debug', true);

	//==============================================REQUIRE SITE===========================================//
	require_once("site.php");
	require_once("site-categories.php");
	//==============================================REQUIRE ADMIN===========================================//
	require_once("admin.php");
	require_once("admin-users.php");
	require_once("admin-categories.php");
	//======================================================================================================//
	
	$app->run();
