<?php


require_once(__DIR__.'/transip/DomainService.php');

class config{

	private static $gandi = null;
	private static $transIp = null;

	public function __construct($testMode = true){
		if($testMode)
		{
			self::$transIp =  new DomainService();
			self::$mode = 'readonly';
		}
		else{
			self::$transIp =  new DomainService();
			self::$mode = 'readwrite';
		}
	}

	public static function getTransIpAPI(){
		return self::$transIp;
	}
}