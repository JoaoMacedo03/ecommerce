<?php 

	namespace Hcode;

	use Rain\Tpl;

	class Page 

	{

		private $tpl;
		private $defaults = array(
			"header" => true,
			"footer" => true,
			"data" => array()
		);
		private $options = array();

		public function __construct($opts = array(), $tpl_dir = "/views/") 
		
		{

			$this->options = array_merge($this->defaults, $opts);
			
			$config = array(
				"tpl_dir"       => $_SERVER['DOCUMENT_ROOT'].$tpl_dir,
				"cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/",
				"debug"         => false // set to false to improve the speed
			);

			Tpl::configure($config);

			$this->tpl = new Tpl;

			$this->setData($this->options['data']);

			if ($this->options['header'] === true) $this->tpl->draw('header');

		}

		public function __destruct() 
		
		{

			if ($this->options['footer'] === true) $this->tpl->draw('footer');

		}

		public function setTpl($name, $data = array(), $returnHTML = false) 

		{

			$this->setData($data);

			return $this->tpl->draw($name, $returnHTML);

		}

		private function setData($data = array()) 

		{

			foreach ($data as $key => $value) {
				
				$this->tpl->assign($key, $value);

			}			

		}

	}

