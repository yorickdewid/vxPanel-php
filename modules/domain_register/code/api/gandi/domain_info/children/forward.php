<?php

class forward extends domainNameSpace{

	public function __construct(){
		$this->setType('forward');
	}

	public function getInfo($prefix,$params){
		$this->__getInfo($prefix,$params); // example
	}

	public function getList($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->list($apikey, 'mydomain.net');
	}

	public function getCount($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->count($apikey, 'mydomain.net');
	}

	public function create($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->create($apikey, 'mydomain.net',$webredir_specs);
	}

	public function delete($prefix,$params){
		$conn = $this->createConnection($prefix);
		$conn->delete($apikey,"mydomain.net", $webredir_specs);
	}

	public function update()
	{
		$conn = $this->createConnection($prefix);
		$conn->update($apikey,"mydomain.net", $webredir_specs);
	}
}

?>