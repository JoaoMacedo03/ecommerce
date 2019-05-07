<?php 

	namespace Hcode;

	class Model 

	{

		private $values = array();

		public function __call($nameMethod, $args) 

		{

			$method = substr($nameMethod, 0, 3);
			$fieldName = substr($nameMethod, 3);

			switch ($method) {
				
				case "get":
					
					return $this->values[$fieldName];

				break;
				
				case "set":
				
					$this->values[$fieldName] = $args[0];
				
				break;
				
			}

		}

		public function setData($data = array()) 

		{

			foreach ($data as $key => $value) {
				
				$this->{"set".$key}($value);

			}

		}

		public function getValues() 

		{

			return $this->values;

		}

	}
