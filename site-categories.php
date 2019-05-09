<?php 

	use Hcode\Page;
	use \Hcode\Model\Category;
	use \Hcode\Model\Product;

	$app->get("/categories/:idcategory", function($idcategory) {

		$category = new Category();

		$category->get((int) $category);

		$page = new Page();

		$page->setTpl("category", array(
			"category" => $category->getValues(),
			"products" => Product::checkList($category->getProducts())
		));

	});

