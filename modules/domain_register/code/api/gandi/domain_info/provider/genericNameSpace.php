
<?php

require_once 'XML/RPC2/Client.php';


abstract class genericNameSpace {

	const API_KEY = "MxhsiVu1qy58kgpnXbVDnFiX";
	const LINK = 'https://rpc.ote.gandi.net/xmlrpc/';
	const HANDLE = 'DP6238-GANDI';
	private $prefix = '';

	/**
	* @param $prefix string //e.g 'host.'
	* @return connection
	*/
	protected function createConnection(){
		$connect_api = XML_RPC2_Client::create(self::LINK,
  		array('prefix' => $this->prefix,'sslverify'=>false));
  		return $connect_api;
	}

	protected final function setPrefix($prefix){
		$this->prefix = $prefix;
	}
}


?>