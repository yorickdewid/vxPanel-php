<?php

require_once __DIR__ .'/../../provider/contactNameSpace.php';

class contact extends contactNameSpace {

	
	public function checkCanAssociateDomainContact()
	{
		$contact_api = XML_RPC2_Client::create($api_uri,
			array('prefix' => 'contact.'));
		$association_spec = array(
			'domain' => 'mydomain.fr',
			'owner' => true,
			'admin' => true );
		print_r( $contact_api->can_associate_domain($apikey, 'FLN123-GANDI',
			$association_spec) );
		// 1
		// OR

	}

	public function create($params){
		try{
			$conn = $this->createConnection();
			$result = $conn->__call('create',array(self::API_KEY,$params));
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}
}

?>