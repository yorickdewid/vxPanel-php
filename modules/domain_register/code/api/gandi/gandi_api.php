<?php
// Library installed from PEAR
require_once 'XML/RPC2/Client.php';

class exampleapi{
// Warning !
// PEAR::XML_RPC2 checks the SSL certificate with Curl
// Curl has its own CA bundle so you may :
// * disable the 'sslverify' option: leads to security issue
// * enable the 'sslverify' option (default) and add the Gandi
// SSL certificate to the Curl bundle: best choice for security
// See: http://curl.haxx.se/docs/sslcerts.html
$apikey = 'my 24-character API key';


public function authenticate(){
	// The first step is to connect to the API
	$version_api = XML_RPC2_Client::create(
		'https://rpc.gandi.net/xmlrpc/',
		array( 'prefix' => 'version.', 'sslverify' => True )
		);



// Now you can call API method
// You must authenticate yourself by passing the API key
// as the first method's argument
	$result = $version_api->info($apikey);

// Warning !
// PEAR::XML_RPC2 has known bugs on methods calls
// See http://pear.php.net/bugs/bug.php?id=13963
// You may use this call instead of the above one :
// $result = $version_api->__call("info", $apikey);

// dump the result

}

public function checkAvailabe(){


	$domain = "mydomain.net";
	$domain_api = XML_RPC2_Client::create(
		'https://rpc.gandi.net/xmlrpc/',
		array( 'prefix' => 'domain.' )
		);
	$result = $domain_api->available($apikey, array($domain));
	print_r($result);
/*
Array
(
    [mydomain.net] => pending
)
*/
while ( $result[$domain] == 'pending') {
	usleep(700000);
	$result = $domain_api->available($apikey, array($domain));
}
print_r($result);
/*
Array
(
    [mydomain.net] => unavailable
)
*/
}

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
	print_r( $contact_api->can_associate_domain($apikey, 'FLN123-GANDI',
		$association_spec) )
	/*
	[{'error': 'EC_INVALIDPARAM1+!EC_ENUMIN',
        'field': 'birth_country',
        'field_type': 'Enum',
        'reason': 'BirthCountryIso:  not in list ...
	},... ]	
	*/
}

public function createDomain(){  // check state with operation.info?
	$domain_spec = array(
		'owner' => 'FLN123-GANDI',
		'admin' => 'FLN123-GANDI',
		'bill' => 'FLN123-GANDI',
		'tech' =>'FLN123-GANDI',
		'nameservers' => array('a.dns.gandi-ote.net', 'b.dns.gandi-ote.net',
			'c.dns.gandi-ote.net'),
		'duration' => 1);
	$op = $domain_api->__call('create', array($apikey, 'mydomain.net',
		$domain_spec));
}


public function checkDomainRegisterState(){

	$op = $operation_api->info($apikey, $op['id'])
	echo $op['step']
//'BILL'

// and later...
	$op = $operation_api->info($apikey, $op['id'])
	echo $op['step']
//'DONE'
}

// could be static

public function updateDomainContacts(){
	$domain_contacts_api = XML_RPC2_Client::create($api_uri,
    array('prefix' => 'domain.contacts.'));
contacts_spec = array(
    'admin' => 'FLN123-GANDI',
    'tech' => 'FLN123-GANDI',
    'bill'=> 'FLN123-GANDI');
$domain_contacts_api->set($apikey, 'mydomain.net', $contacts_spec);

}

public function lockDomain(){
	$domain_status_api->lock($apikey, 'mydomain.net');
}

public function unlockDomain(){
	$domain_status_api->unlock($apikey, 'mydomain.net');
}

public function updateDomainNameServers(){
	$domain_nameservers_api = XML_RPC2_Client::create($api_uri,
    array('prefix' => 'domain.nameservers.'));
	$domain_nameservers_api->set($apikey, 'mydomain.net',
    array('a.dns.mydomain.net', 'b.dns.mydomain.net', 'c.dns.mydomain.net'));
}



/** PROCESSING DOMAIN
*/


public function renewDomain($duration,$current_year){
	$renew_spec = array(
  'duration'=> $duration, 
  'current_year'=> $current_year); //current expire 
$domain_api->renew($apikey, 'mydomain.net', $renew_spec);
}

public function transferDomain(){
	$domain_transferin_api = XML_RPC2_Client::create($api_uri,
    array('prefix' => 'domain.transferin.'));
	$transfer_spec = array(
  'owner' => 'FLN123-GANDI',
  'admin' => 'FLN123-GANDI',
  'tech' => 'FLN123-GANDI',
  'bill' => 'FLN123-GANDI',
  'nameservers' => array('a.dns.gandi.net', 'b.dns.gandi.net', 'c.dns.gandi.net'),
  'authinfo' => 'xxx',
  'duration' => 1)
	$domain_transferin_api->proceed($apikey, 'mydomain.net', transfer_spec);
}

public function restoreDomain(){
	$restore_spec = array(
  'duration'=> 1);
$domain_api->restore($apikey, 'mydomain.net', $restore_spec);
}



/**
* Restricted according to API doc (no test?)
*/
public function deleteDomain(){

}

?>