<?php

require 'provider/domainNameSpace.php';

class domain extends domainNameSpace{

	public function getVersion(){
		$connect_api = XML_RPC2_Client::create(self::LINK,
  		array('prefix' => 'version.','sslverify'=>false));
		$result = $connect_api->info(self::API_KEY);
		return $result['api_version'];
	}
	/**
	* @param $domains array of domain names
	* @return array keys are domain
	*/
	public function checkDomainAvailable(array $domains){
		$conn = $this->createConnection();
		print parent::API_KEY;
		$result = $conn->available(parent::API_KEY,$domains);
		print_r($result);

		while ( $result[$domain] == 'pending') {
			usleep(700000);
			$result = $conn->available(array(parent::API_KEY, $domains));
		}
		print_r($result);
	}

	/**
	* @param $opts options to retrieve result by
	* e.g array(id => 2304)
	* @return integer number of domains
	*/
	public function getCount($opts)
	{
		return $this->__getCount(null,$opts);
	}

	/**
	* @param $domain the domain name
	* @param $duration number of years 1-10
	*/
	public function createDomain($domain,$duration = 1){  // check state with operation.info?
		if($domain_spec = null)
		{
			$domain_spec = array(
			'owner' => 'FLN123-GANDI', //load from somwheree?
			'admin' => 'FLN123-GANDI',
			'bill' => 'FLN123-GANDI',
			'tech' =>'FLN123-GANDI',
			'nameservers' => array('a.dns.gandi-ote.net', 'b.dns.gandi-ote.net',
			'c.dns.gandi-ote.net'),
			'duration' => $duration);
		}
		$conn = $this->createConnection();
		$result = $conn->create(self::API_KEY,$domain,$domain_spec);
	}

	/**
	* @param $currency 
	* @param $lang
	* @param $phase 
	* currency
	* lang ISO-639-2
	* phase -> default = golive
	*/
	public function getPrice(array $domains,$currency,$lang = null,$phase = null){
		$conn = $this->createConnection();
		$result = $conn->price(parent::API_KEY, $domains,array($currency,$lang,$phase));
		print_r($result);
	}


/*
* single?
*/
public function releaseDomain(){

}

public function renewDomain($duration,$current_year){
	$renew_spec = array(
		'duration'=> $duration, 
  'current_year'=> $current_year); //current expire 
	$domain_api->renew($apikey, 'mydomain.net', $renew_spec);
}


public function restoreDomain(){
	$restore_spec = array(
		'duration'=> 1);
	$domain_api->restore($apikey, 'mydomain.net', $restore_spec);
}

public function setDomainOwner($domain){

}

public function setDomainContacts(){

}

public function listAllTLD()
{
	$domain_api = XML_RPC2_Client::create($api_uri, array('prefix' => 'domain.tld.'));
	$domain_api->list($apikey);
}

	// e.g europe,asia
public function listALLTLDByRegion(){
	$domain_api = XML_RPC2_Client::create($api_uri,
		array('prefix' => 'domain.tld.'));
	$domain_api->region($apikey);
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

}



public function checkDomainRegisterState(){

	$op = $operation_api->info($apikey, $op['id']);
//'BILL'

// and later...
	$op = $operation_api->info($apikey, $op['id']);
//'DONE'
}

// could be static

public function updateDomainContacts(){
	$domain_contacts_api = XML_RPC2_Client::create($api_uri,
		array('prefix' => 'domain.contacts.'));
	$contacts_spec = array(
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

public function setNameServers($domain,$nameservers,$options)
{

}



/** PROCESSING DOMAIN
*/



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
		'duration' => 1);
		$domain_transferin_api->proceed($apikey, 'mydomain.net', $transfer_spec);
}


/**
* Restricted according to API doc (no test?
*/
	public function deleteDomain(){

	}

	
	/* DNSSEC */

	public function deleteDNSSec($keyId){

	}

	public function listDNSSec($domain){

	}

	/* Reseller */

	public function setReseller($domain){

	}


	/* Expression of Interest
	*/

	public function setCountEOI($opts = null){

	}

	public function createEOI($domain){

	}


	public function deleteEOI($domain){

	}

	public function getInfoEOI($domain){

	}

	public function getListEOI($opts)
	{
		
	}

	public function activateAutoRenew($domain)
	{

	}

	public function deactivateAutoRenew($domain)
	{

	}	
/*
	PREORDER/sunrise/landurhs etc
*/

	// not is valid 48 hours
	// show customer trademark clame info method
	public function acceptClaims($noticeId)
	{

	}

	// useful first 90 days? some last forever some shorter..
	public function checkClaims($domains){

	}

	public function getClaimInfo($domain)
	{

	}


	public function countSMD($options = null){

	}

	public function deleteSMD($smdId)
	{

	}
	public function extractSMD($smd)
	{
		
	}

	public function getSMDInfo($smdId){

	}

	public function getSMDList($options = null){

	}

	/*
	MISC
	*/

	// limitation no subdomains e.g co.uk
	public function checkUkRights($params)
	{

	}

	public function acceptDelete($fqdn,$authcode){

	}

	public function declineDelete($fqdn,$authcode)
	{

	}

	public function getDeleteAvailable($domain){

	}

	public function getDeleteInfo($fqdn,$authcode = null){

	}

	//restricted
	public function proceedWithDelete($domain){

	}
}