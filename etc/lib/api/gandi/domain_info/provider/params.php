<?php

abstract class params{
	
	protected static $params;

	public static function getParams(){
		return static::$params;
	}

	public static function cleanArrayKeys(&$params){
		foreach($params as $key => $value)
		{
			if(is_null($value) || $value == '' || empty($value))
       			unset($params[$key]);
		}
	}
}

?>