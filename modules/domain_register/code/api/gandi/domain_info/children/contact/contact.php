<?php

class contact{

	
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
}

?>