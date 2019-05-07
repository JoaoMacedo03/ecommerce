<?php 

	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use Hcode\Page;
	use Hcode\PageAdmin;
	use \Hcode\Model\User;

	$app = new Slim();

	$app->config('debug', true);

	//=============================PRINCIPAL=================================================//

	$app->get('/', function() {

		$page = new Page();

		$page->setTpl('index');

	});

	//=============================ADMIN PRINCIPAL=================================================//	

	$app->get('/admin', function() {
		
		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl('index');

	});

	//=============================LOGIN=================================================//

	$app->get('/admin/login', function() {
		
		$page = new PageAdmin(array(
			'header' => false,
			'footer' => false
		));

		$page->setTpl('login');

	});

	$app->get('/admin/logout', function() {
		
		User::logout();

		header("Location: /admin/login");
		exit;

	});

	$app->post('/admin/login', function() {
		
		User::login($_POST['login'], $_POST['password']);

		header('Location: /admin');
		exit;

	});

	//=============================DADOS USUARIOS=================================================//

	$app->get('/admin/users', function() {

		User::verifyLogin();

		$users = User::listAll();

		$page = new PageAdmin();

		$page->setTpl('users', array(
			"users" => $users
		));

	});

	$app->get("/admin/users/create", function() {

		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("users-create");

	});

	$app->get("/admin/users/:iduser/delete", function($iduser) {

		User::verifyLogin();

		$user = new User();

		$user->get((int) $iduser);

		$user->delete();

		header("Location: /admin/users");
		exit;

	});

	$app->get("/admin/users/:iduser", function($iduser) {

		User::verifyLogin();

		$user = new User();

		$user->get((int) $iduser);

		$page = new PageAdmin();

		$page->setTpl("users-update", array(
			"user" => $user->getValues()
		));

	});

	$app->post("/admin/users/create", function() {

		User::verifyLogin();

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

		$user->setData($_POST);

		$user->save();

		header("Location: /admin/users");
		exit;

	});

	$app->post("/admin/users/:iduser", function($iduser) {

		User::verifyLogin();

		$user = new user();

		$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

		$user->get((int) $iduser);

		$user->setData($_POST);

		$user->update();

		header("Location: /admin/users");
		exit;

	});

	//=============================DADOS USUARIOS=================================================//

	$app->get("/admin/forgot", function() {
	
		$page = new PageAdmin(array(
			"header" => false,
			"footer" => false
		));

		$page->setTpl("forgot");

	});

	//=======================================================================================//

	$app->run();
