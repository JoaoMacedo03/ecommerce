<?php 

	namespace Hcode\Model;

	use \Hcode\DB\Sql;
	use \Hcode\Model;
	use \Hcode\Model\User;

	class Cart extends Model

	{

		const SESSION = "Cart";

		public static function getFromSession() 

		{

			$cart = new Cart();

			if(isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0) {

				$cart->get((int)$_SESSION[Cart::SESSION]['idcart']);

			} else {

				$cart->getFromSessionID();

				if(!(int) $cart->getidcart() > 0) {

					$data = array(
						"dessessionid" => session_id()
					);

					if (User::checkLogin(false)) {

						$user = User::getFromSession();

						$data["iduser"] = $user->getiduser();

					}

					$cart->setData($data);

					$cart->save();

					$cart->setToSession();

				}

			}

			return $cart;

		}

		public function setToSession() 

		{

			$_SESSION[Cart::SESSION] = $this->getValues();

		}

		public function getFromSessionID() 

		{

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid = :dessessionid", array(
				":dessessionid" => session_id()
			));

			if(count($results) > 0) {

				$this->setData($results);
				
			}

		}

		public function get(int $idcart) 

		{

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart", array(
				":idcart" => $idcart
			));

			if(count($results) > 0) {

				$this->setData($results);
				
			}

		}

		public function save() 

		{

			$sql = new Sql();

			$results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", array(
					":idcart" => $this->getidcart(),
					":dessessionid" => $this->getdessessionid(),
					":iduser" => $this->getiduser(),
					":deszipcode" => $this->getdeszipcode(),
					":vlfreight" => $this->getvlfreight(),
					":nrdays" => $this->getnrdays() 
			));

			$this->setData($results[0]);

		}

		public function addProduct(Product $product) 

		{

			$sql = new Sql();

			$sql->query("INSERT INTO tb_cartsproducts (idcart, idproduct) VALUES (:idcart, :idproduct)", array(
				":idcart" => $this->getidcart(),
				":idproduct" => $product->getidproduct()
			));

		}

		public function removeProduct(Product $product, $all = false) 

		{

			$sql = new Sql();

			if($all) {

				$sql->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL", array(
					":idcart" => $this->getidcart(),
					":idproduct" => $product->getidproduct()
				));

			} else {

				$sql->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL LIMIT 1", array(
					":idcart" => $this->getidcart(),
					":idproduct" => $product->getidproduct()
				));				

			}

		}

		public function getProducts() 

		{

			$sql = new Sql();

			$rows = $sql->select("SELECT p.idproduct, p.desproduct, p.vlprice, p.vlheigth, p.vlwidth, p.vllenght, p.vlweight, p.desurl, COUNT(*) AS nrqtd, SUM(p.vlprice) AS vltotal FROM tb_cartsproducts cp INNER JOIN tb_products p ON cp.idproduct = p.idproduct WHERE cp.idcart = :idcart AND cp.dtremoved IS NULL GROUP BY p.idproduct, p.desproduct, p.vlprice, p.vlheigth, p.vlwidth, p.vllenght, p.vlweight, p.desurl ORDER BY p.desproduct", array(
					":idcart" => $this->getidcart()
			));

			return Product::checkList($rows);

		}

	}