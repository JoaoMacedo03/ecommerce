<?php 

	use \Hcode\Model\User;

	function formatPrice(float $vlprice) 

	{

		return number_format($vlprice, 2, ",", ".");

	}

	function checkLogin($inAdmin = true) 

	{

		return User::checkLogin($inAdmin);

	}

	function getUserName() 

	{

		$user = User::getFromSession();

		return $user->getdesperson();

	}



