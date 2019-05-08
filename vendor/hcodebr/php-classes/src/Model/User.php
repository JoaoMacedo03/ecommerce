<?php 

	namespace Hcode\Model;

	use \Hcode\DB\Sql;
	use \Hcode\Model;

	class User extends Model

	{

		const SESSION = "User";
		const SECRET = "HcodePhp7_Secret"; //Tem que ter pelo menos 16 caracteres no secret

		public static function login($login, $password) 

		{

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
				":LOGIN" => $login
			));

			if (count($results) === 0) {

				throw new \Exception("Usuário inexistente ou senha inválida.");

			}

			$data = $results[0];

			if (password_verify($password, $data["despassword"])) {

				$user = new User();

				$user->setData($data);

				$_SESSION[User::SESSION] = $user->getValues();

				return $user;

			} else {

				throw new \Exception("Usuário inexistente ou senha inválida.");				

			}

		}

		public static function verifyLogin($inAdmin = true) 

		{

			if (!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION] || (int)$_SESSION[User::SESSION]["iduser"] <= 0 || (bool)$_SESSION[User::SESSION]["inadmin"] !== $inAdmin) {

				// header("Location: /admin/login");
				// exit;

			}

		}

		public static function logout() 

		{

			$_SESSION[User::SESSION] = NULL;

		}

		public static function listAll() 

		{

			$sql = new Sql();

			return $sql->select("SELECT * FROM tb_users u INNER JOIN tb_persons p USING(idperson) ORDER BY p.desperson");

		}

		public function save() 

		{

			$sql = new Sql();

			$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
				":desperson" => $this->getdesperson(),
				":deslogin" => $this->getdeslogin(),
				":despassword" => $this->getdespassword(),
				":desemail" => $this->getdesemail(),
				":nrphone" => $this->getnrphone(),
				":inadmin" => $this->getinadmin()
			));

			$this->setData($results[0]);

		}

		public function get($iduser) 

		{

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
				":iduser" => $iduser
			));

			$this->setData($results[0]);

		}

		public function update() 

		{

			$sql = new Sql();

			$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
				":iduser" => $this->getiduser(),
				":desperson" => $this->getdesperson(),
				":deslogin" => $this->getdeslogin(),
				":despassword" => $this->getdespassword(),
				":desemail" => $this->getdesemail(),
				":nrphone" => $this->getnrphone(),
				":inadmin" => $this->getinadmin()
			));

			$this->setData($results[0]);

		}

		public function delete() 

		{

			$sql = new Sql();

			$sql->query("CALL sp_users_delete(:iduser)", array(
				":iduser" => $this->getiduser() 
			));

		}

		public static function getForgot($email) 

		{

			$sql = new Sql();

			$results = select("SELECT * FROM tb_persons p INNER JOIN tb_users u USING(idperson) WHERE p.email = :EMAIL", array(
				":EMAIL" => $email
			));

			if(count($results) === 0) {

				throw new \Exception("Não foi possível recuperar a senha.");

			} else {

				$data = $results[0];

				$resultsRecoveryPassword = $sql->select("CALL sp_userspasswordsrecoveries_create (:iduser, :desip)", array(
					":iduser" => $data["iduser"],
					":desip" => $_SERVER["REMOTE_ADDR"] 
				));

				if(count($resultsRecoveryPassword) === 0) {

					throw new \Exception("Não foi possível recuperar a senha.");

				} else {

					$dataRecovery = $resultsRecoveryPassword[0];

					$code = base64_encode(openssl_encrypt(
        				$dataRecovery["idrecovery"],
        				'AES-128-CBC',
	        			User::SECRET,
        				0,
        				User::SECRET_IV
    				));

    				$link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$code";

    				$mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir senha da Hcode Store", "forgot", array(
    					"name" => $data["desperson"],
    					"link" => $link
    				));

    				$mailer->send();

    				return $data;

				} 

			}

		}

		public static function validForgotDecrypt($code) 

		{

			$idRecovery = openssl_decrypt(base64_decode($code), 'AES-128-CBC', User::SECRET, 0, User:;SECRET_IV);

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_userspasswordsrecoveries upr INNER JOIN tb_users u USING(iduser) INNER JOIN tb_persons p USING(idperson) WHERE upr.idrecovery = :idrecovery AND upr.dtrecovery IS NULL AND DATE_ADD(upr.dtregister, INTERVAL 1 HOUR) >= NOW();", array(
				":idrecovery" => $idRecovery
			));

			if(count($results) === 0) {

				throw new \Exception("Não foi possível recuperar a senha");

			} else {

				return $results[0];

			}

		}

		public static function setForgotUsed($idRecovery) 

		{

			$sql = new Sql();

			$sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
				":idrecovery" => $idRecovery
			));

		}

		public funtion setPassword($password) 

		{

			$sql = new Sql();

			$sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
				":password" => $password,
				":iduser" => $this->getiduser()
			));

		}

	}