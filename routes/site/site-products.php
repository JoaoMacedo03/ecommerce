<?php 

	use Hcode\Page;
	use \Hcode\Model\Product;
	
	$app->get("/products/:desurl", function($desurl) {

		$product = new Product();

		$product->getFromUrl($desurl);
		
		$page = new Page();
			
		$page->setTpl("product-detail", array(
			"products" => $product->getValues(),
			"categories" => $product->getCategories()
		));

	});

