<?php

abstract class domainNameSpace {

	protected $type = null;

	private function createConnection($type){
		$connect_api = XML_RPC2_Client::create(apiConfig::API_KEY,
  		array('prefix' => 'domain.' . $type .'.'));
  		return $connect_api;
	}

	public final function setType($type){
		$this->$type = $type;
	}	

	public function getInfo(){
		$domain_api->info($apikey, 'mydomain.net'));
	}

	public function getList(){
		$domain_api->list($apikey, 'mydomain.net'));
	}

	public function getCount(){
		$domain_api->count($apikey, 'mydomain.net'));
	}

	public function create(){
		$connect_api->__call('create',
    	array($apikey, "mydomain.net", $webredir_specs)));
	}

	public function delete(){
		$connect_api->__call('delete',
    	array($apikey, "mydomain.net", $webredir_specs)));
	}

	public function update()
	{
		$connect_api = $this->getConnection();

	}
	
/*
*	You can use the following domain namespaces if you need to retrieve more data:
*
*   domain.host
*   domain.webredir
*   domain.mailbox
*   domain.forward
*	4 subclasses
*/ 



}

?>