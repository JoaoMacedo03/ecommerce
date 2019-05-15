<?php 

	use \Hcode\Page;
	use \Hcode\Model\Cart;
	
	$app->get("/cart", function() {

		$cart = Cart::getFromSession();

		$page = new Page();
			
		$page->setTpl("cart");

	});

