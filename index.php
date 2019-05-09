<?php 

	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;

	$app = new Slim();

	$app->config('debug', true);

	//==============================================REQUIRE GERAL===========================================//
	require_once("functions.php");
	//==============================================REQUIRE SITE===========================================//
	require_once("site.php");
	require_once("site-categories.php");
	//==============================================REQUIRE ADMIN===========================================//
	require_once("admin.php");
	require_once("admin-users.php");
	require_once("admin-categories.php");
	require_once("admin-products.php");
	//======================================================================================================//
	
	$app->run();
