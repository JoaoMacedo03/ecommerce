<?php 

	use Hcode\Page;
	use Hcode\Model\Product;

	$app->get('/', function() {

		$products = Product::listAll();

		$page = new Page();

		$page->setTpl("index");

	});

	$app->get("/forgot", function() {
	
		$page = new Page();

		$page->setTpl("forgot");

	});

	$app->post("/forgot", function() {

		$userEmailValidated = User::getForgot($_POST['email'], false);

		header("Location: /forgot/sent");
		exit;

	});

	$app->get("/forgot/sent", function() {

		$page = new Page();

		$page->setTpl("forgot-sent");

	});

	$app->get("/forgot/reset", function() {

		$user = User::validForgotDecrypt($_GET["code"]);

		$page = new Page();

		$page->setTpl("forgot-reset", array(
			"name" => $user["desperson"],
			"code" => $_GET["code"]
		));

	});

	$app->post("/forgot/reset", function() {

		$forgot = User::validForgotDecrypt($_POST["code"]);

		User::setForgotUsed($forgot["idrecovery"]);

		$user = new User();

		$user->get((int)$forgot["iduser"]);

		$password = password_hash($_POST["password"], PASSWORD_DEFAULT, array(
			"cost" => 12
		));

		$user->setPassword($password);

		$page = new Page();

		$page->setTpl("forgot-reset-success");		

	});

