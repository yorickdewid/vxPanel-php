<?php

require_once 'XML/RPC2/Client.php';


abstract class domainNameSpace {

	const API_KEY = "MxhsiVu1qy58kgpnXbVDnFiX";
	const LINK = 'https://rpc.ote.gandi.net/xmlrpc/';


	/**
	* @param $prefix string //e.g 'host.'
	* @return connection
	*/
	protected function createConnection($prefix = null){
		$connect_api = XML_RPC2_Client::create(self::LINK,
  		array('prefix' => 'domain.'.$prefix,'sslverify'=>false));
  		return $connect_api;
	}

	public final function __getInfo($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->info($apikey, 'mydomain.net');
	}

	public final function __getList($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->list($apikey, 'mydomain.net');
	}

	public final function __getCount($prefix,$params){
		$conn = $this->createConnection($prefix);
		return $conn->count(array_merge(self::API_KEY,$params));
	}

	public final function __create($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->create($apikey, 'mydomain.net',$webredir_specs);
	}

	public final function __delete($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->delete($apikey,"mydomain.net", $webredir_specs);
	}

	public final function __update()
	{
		$conn = $this->createConnection($prefix);
		$conn->update($apikey,"mydomain.net", $webredir_specs);
	}
}
?>