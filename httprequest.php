<?php
	class httpRequest
	{
		private $ip;
		private $browser;
	
		public function __construct()
		{
			$this -> ip = $_SERVER['REMOTE_ADDR'];
			$this -> browser = $_SERVER['HTTP_USER_AGENT'];		
		} // end __construct();	
 
		public function getIp()
		{
			return $this -> ip;
		} // end getIp();
 
		public function getBrowser()
		{
			return $this -> browser;
		} // end getBrowser();
	}
?>