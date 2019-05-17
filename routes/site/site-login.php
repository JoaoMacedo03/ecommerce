<?php 

	use \Hcode\Page;
	use \Hcode\Model\Cart;
	use \Hcode\Model\Product;
	use \Hcode\Model\Address;
	use \Hcode\Model\User;
	
	$app->get("/checkout", function() {

		User::verifyLogin(false);

		$cart = Cart::getFromSession();

		$address = new Address();

		$page = new Page();

		$page->setTpl("checkout", array(
			"cart" => $cart->getValues(),
			"address" => $address->getValues()
		));
	
	});

	$app->get("/login", function() {

		$page = new Page();

		$page->setTpl("login", array(
			"error" => User::getError(),
			"errorRegister" => User::getErrorRegister(),
			"registerValues" => (isset($_SESSION["registerValues"])) ? $_SESSION["registerValues"] : array("name" => "", "email" => "", "phone" => "")
		));
	
	});

	$app->post("/login", function() {

		try {

			User::login($_POST["login"], $_POST["password"]);
			
		} catch(Exception $e) {

			User::setError($e->getMessage());

		}

		header("Location: /checkout");
		exit;

	});

	$app->get("/logout", function() {

		User::logout();

		header("Location: /login");
		exit;

	});

	$app->post("/register", function() {

		$_SESSION["registerValues"] = $_POST;

		if(!isset($_POST["name"]) || empty($_POST["name"])) {

			User::setErrorRegister("Preencha o seu nome.");

			header("Location: /login");
			exit;

		}

		if(!isset($_POST["email"]) || empty($_POST["email"])) {

			User::setErrorRegister("Preencha o seu e-mail.");

			header("Location: /login");
			exit;

		}

		if(!isset($_POST["password"]) || empty($_POST["password"])) {

			User::setErrorRegister("Preencha a senha.");

			header("Location: /login");
			exit;

		}

		if(User::checkLoginExist($_POST["email"]) === true) {

			User::setErrorRegister("Este endereço de e-mail já está sendo usado por outro usuário.");

			header("Location: /login");
			exit;

		}

		$user = new User();

		$user->setData(array(
			"inadmin" => 0,
			"deslogin" => $_POST["email"],
			"desperson" => $_POST["name"],
			"desemail" => $_POST["email"],
			"despassword" => $_POST["password"],
			"nrphone" => $_POST["phone"]
		));

		$user->save();

		User::login($_POST["email"], $_POST["password"]);

		header("Location: /checkout");
		exit;

	});
