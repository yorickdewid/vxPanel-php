
<?php

require_once 'XML/RPC2/Client.php';


abstract class contactNameSpace {

	const API_KEY = "MxhsiVu1qy58kgpnXbVDnFiX";
	const LINK = 'https://rpc.ote.gandi.net/xmlrpc/';


	/**
	* @param $prefix string //e.g 'host.'
	* @return connection
	*/
	protected function createConnection($prefix = null){
		$connect_api = XML_RPC2_Client::create(self::LINK,
  		array('prefix' => 'contact.'.$prefix,'sslverify'=>false));
  		return $connect_api;
	}
}


?>