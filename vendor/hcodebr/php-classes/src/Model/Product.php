<?php 

	namespace Hcode\Model;

	use \Hcode\DB\Sql;
	use \Hcode\Model;

	class Product extends Model

	{

		public static function listAll() 

		{

			$sql = new Sql();

			return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");

		}

		public function save() 

		{

			$sql = new Sql();

			$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
				":idcategory" => $this->getidcategory(),
				":descategory" => $this->getdescategory(),
				":vlprice" => $this->getdvlprice(),
				":vlwidth" => $this->getvlwidth(),
				":vlheight" => $this->getvlheight(),
				":vllength" => $this->getdvllength(),
				":desurl" => $this->getdesurl()
			));

			$this->setData($results[0]);

		}

		public function get($idproduct) 

		{

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(
				":idproduct" => $idproduct
			));

			$this->setData($results[0]);

		}

		public function delete() 

		{

			$sql = new Sql();

			$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", array(
				":idproduct" => $this->getidproduct()
			));

		}
		
	}