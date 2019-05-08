<?php 

	$app->get("/categories/:idcategory", function($idcategory) {

		$category = new Category();

		$category->get((int) $category);

		$page = new Page();

		$page->setTpl("category", array(
			"category" => $category->getValues(),
			"products" => array()
		));

	});
