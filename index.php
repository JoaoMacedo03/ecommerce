<?php 

	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;

	$app = new Slim();

	$app->config('debug', true);

	//==============================================REQUIRE GERAL===========================================//
	require_once("functions.php");
	//==============================================REQUIRE SITE===========================================//
	require_once("routes/site/site.php");
	require_once("routes/site/site-categories.php");
	require_once("routes/site/site-products.php");
	require_once("routes/site/site-cart.php");
	require_once("routes/site/site-login.php");
	require_once("routes/site/site-profile.php");
	//==============================================REQUIRE ADMIN===========================================//
	require_once("routes/admin/admin.php");
	require_once("routes/admin/admin-users.php");
	require_once("routes/admin/admin-categories.php");
	require_once("routes/admin/admin-products.php");
	//======================================================================================================//
	
	$app->run();
