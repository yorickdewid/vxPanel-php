<?php

class mailbox extends domainNameSpace{

	public function __construct(){
		$this->setType('mailbox');
	}

	public function setAlias(){
		$domain_mailbox_api->mailbox.aliases.set($apikey, 'mydomain.net', 'admin',array('stephanie'));
	}

	public function purge($domain,$login){

	}

	public function activateResponder($domain,$login,$params){

	}

	public function deactivateResponder($domain,$login,$params = null){

	}
}

?>