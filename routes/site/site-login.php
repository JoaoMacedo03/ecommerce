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
			"error" => User::getError()
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
