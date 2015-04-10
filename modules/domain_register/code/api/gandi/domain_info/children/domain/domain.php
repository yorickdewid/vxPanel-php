<?php

require_once __DIR__ .'/../../provider/domainNameSpace.php';


class domain extends domainNameSpace{


	/**
	* @param $domains array of domain names
	* @return array keys are the domain
	*/
	public function checkDomainAvailable(array $domains){
		$conn = $this->createConnection();
		$result = $conn->available(parent::API_KEY,$domains);

		while ( $result[$domain] == 'pending') {
			usleep(700000);
			$result = $conn->available(array(parent::API_KEY, $domains));
		}
		return $result;
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
	* @param string $domain the domain name
	* @param array $params DomainCreate class
	*/
	public function createDomain($domain,$domain_spec){  // check state with operation.info?
		try{
			$conn = $this->createConnection();
			$result = $conn->__call('create',array(self::API_KEY,$domain,$domain_spec));
			return $result;
		}
		catch(XML_RPC2_FaultException $e)
		{
			echo $e->getMessage() . "\n\n";
		}
	}

	/**
	 * [getInfo description]
	 * @param string $domain
	 * @return array
	 */
	public function getInfo($domain){
		return $this->__getInfo($domain);
	}


	/**
	 * [getList description]
	 * @param $opts huge list
	 * @return [type]
	 */
	public function getList($opts){
		return $this->_getList($opts);
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
		return $result;
	}


	/**
	 * Used to release a domain, reseller only
	 * @param string $domain
	 * @return boolean
	 */
	public function releaseDomain($domain){
		$conn = $this->createConnection();
		$result = $conn->release(parent::API_KEY,$domain);
		return $result['succes'];
	}

	/**
	 * Not a free operation
	 * @param  string $domain
	 * @param  int $duration in years (1-10?)
	 * @param  int $current_year e.g 2015?
	 * @return array (lot of info)
	 */
	public function renewDomain($domain,$duration,$current_year){
		$renew_spec = array(
		'duration'=> $duration, 
  		'current_year'=> $current_year); //current expire 
  		$conn = $this->createConnection();
		$result = $conn->renew(parent::API_KEY,$domain,$renew_spec);
		return $result;
	}

	/**
	 * Not a free operation
	 * @param  string $domain example.com
	 * @param  int $duration (1-10)
	 * @return array (lot of info)
	 */
	public function restoreDomain($domain,$duration){
		$restore_spec = array(
		'duration'=> 1);
		$conn = $this->createConnection();
		$result = $conn->restore(parent::API_KEY,$domain,$restore_spec);
		return $result;
	}
	/**
	 * [setDomainOwner description]
	 * @param string $domain
	 * @param string $admin Administrative contact handle
	 * @param string $bill Billing contact handle
	 * @param string $old_owner Old registrant handle
	 * @param string $owner New registrant handle
	 * @param string $tech Technical contact handle
	 */
	public function setDomainOwner($domain,$admin,$bill,$old_owner,$owner,$tech){
		$domain_owner = array(
		'admin' => $admin,
		'bill' => $bill,
		'old_owner' => $old_owner,
		'owner' => $owner,  
		'tech' =>	$tech);
		$conn = $this->createConnection('owner.');
		$result = $conn->set(parent::API_KEY,$domain,$restore_spec);
		return $result;
	}	

	/**
	 * [setDomainContacts description]
	 * @param string $domain
	 * @param string $admin optional
	 * @param string $bill optional
	 * @param string $tech optional
	 * @param string $extra optional huge list see domain API
 	 * @param array
 	 */
	public function setDomainContacts($domain,$admin,$bill,$tech,$extra){
		$domain_owner = array(
		'admin' => $admin,
		'bill' => $bill,
		'old_owner' => $old_owner,
		'owner' => $owner,  
		'tech' => $tech,
		'extra' => $extra);
		$conn = $this->createConnection('owner.');
		$result = $conn->set(parent::API_KEY,$domain,$restore_spec);
		return $result;
	}

	/**
	 * [lockDomain description]
	 * @param string $domain
	 * @return array DomainStatusUpdateOperationReturn
	 */
	public function lockDomain($domain){
		$conn = $this->createConnection('status.');
		$result = $conn->restore(parent::API_KEY,$domain);
		return $result;
	}

	/**
	 * [unlockDomain description]
	 * @param string $domain
	 * @return array DomainStatusUpdateOperationReturn
	 */
	public function unlockDomain(string $domain){
		$conn = $this->createConnection('status.');
		$result = $conn->restore(parent::API_KEY,$domain);
		return $result;
	}

	/**
	 * All params are optional
	 * @param int $items_per_page default 800
	 * @param int $page page offset
	 * @param string $phase (eoi,sunrise,landrush,golive) default golive
	 * @param string $sort_by (phase or visibility)
	 * @param string $visibility (mine or all) default mine
	 * @return array id,name,phase,region and visibility
	 */
	public function listAllTLD($items_per_page = null,$page = null,$phase = null,$sort_by = null,$visibility = null)
	{
		$opts = array(
			'items_per_page' => $items_per_page,
			'page' => $page,
			'phase' => $phase,
			'sort_by' => $sort_by,
			'visibility' => $visibility);
		foreach($opts as $key => $value)
		{
			if(is_null($value) || $value == '')
       			unset($opts[$key]);
		}
		$conn = $this->createConnection('tld.');
		if(!empty($opts))
		{
			$result = $conn->list(parent::API_KEY,$opts);
		}
		else{
			$result = $conn->list(parent::API_KEY);
		}
		return $result;
	}

	/**
	 * 	All params are optional
	 * @param int $items_per_page default 800
	 * @param int $page page offset
	 * @param string $phase (eoi,sunrise,landrush,golive) default golive
	 * @param string $sort_by (phase or visibility)
	 * @param string $visibility (mine or all) default mine
	 * @return array (name & region)
	 */
	public function listALLTLDByRegion($items_per_page = null,$page = null,$phase = null,$sort_by = null,$visibility = null){
		$opts = array(
			'items_per_page' => $items_per_page,
			'page' => $page,
			'phase' => $phase,
			'sort_by' => $sort_by,
			'visibility' => $visibility);
		foreach($opts as $key => $value)
		{
			if(is_null($value) || $value == '')
       			unset($opts[$key]);
		}
		$conn = $this->createConnection('tld.');
		if(!empty($opts))
		{
			$result = $conn->region(parent::API_KEY,$opts);
		}
		else{
			$result = $conn->region(parent::API_KEY);
		}
		return $result;
	}

	/**
	 * [transferDomainAvailable description]
	 * @param string $fqdn e.g transip.ariekaas.nl? fully qualified domain name
	 * @param string $auth_info 
	 * @return nil?
	 */
	public function transferDomainAvailable($fqdn,$auth_info = null){
		$conn = $this->createConnection('transferin.');
		$result = $conn->available(parent::API_KEY, $fqdn ,$auth_info);
		return $result;
	}

	/**
	 * [transferDomainProceed description]
	 * @param string $domain
	 * @param string $owner
	 * @param string $admin
	 * @param string $tech
	 * @param string $bill
	 * @param array $optParams see gandi doc
	 * @return array see gandi doc
	 */
	public function transferDomainProceed($domain,$owner,$admin,$tech,$bill,$opt_params = null){
		$transfer_spec = array(
			'owner' => $owner,
			'admin' => $admin,
			'tech' => $tech,
			'bill' => $bill);
		if($opt_params != null){
			$transfer_spec = array_merge($transfer_spec,$opt_params);
		}
		$conn = $this->createConnection('transferin.');
		$result = $conn->proceed(parent::API_KEY, $domain,$transfer_spec);
		return $result;
	}

	/**
	 * [setNameServers description]
	 * @param string $domain
	 * @param array $name_servers
	 * @param array $options (nameservers_ips,override,x-fi_authkey,zone_id)
	 * @return array see gandi doc
	 */
	public function setNameServers($domain,$name_servers,$options)
	{
		$conn = $this->createConnection('nameservers.');
		if($options != null)
		{
			$result = $conn->set(parent::API_KEY, $domain,$name_servers,$options);
		}
		else{
			$result = $conn->set(parent::API_KEY, $domain,$name_servers);
		}
		return $result;
	}

	/**
	 * DNS SEC
	 */
	
	/**
	 * [createDNSSec description]
	 * @param  string $domain
	 * @param  int $algorithm iana number?
	 * @param  int $flags 256 (ZSK) or 257 (KSK)
	 * @param  string $public_key The base64-encoded public key
	 * @return array see gandi doc dnssec.create params
	 */
	public function createDNSSec($domain,$algorithm,$flags,$public_key){
		$conn = $this->createConnection('dnssec.');
		$result = $conn->create(parent::API_KEY, $domain,$algorithm,$flags,$public_key);
		return $result;
	}
	/**
	 * [deleteDNSSec description]
	 * @param  string $keyId dnssec keyid
	 * @return array see gandi doc dnssec.delete params
	 */
	public function deleteDNSSec($keyId){
		$conn = $this->createConnection('dnssec.');
		$result = $conn->delete(parent::API_KEY, $keyId);
		return $result;
	}

	/**
	 * list keys of the associated domain
	 * @param  string $domain
	 * @return array DnssecReturn param
	 */
	public function listDNSSec($domain){
		$conn = $this->createConnection('dnssec.');
		$result = $conn->list(parent::API_KEY, $domain);
		return $result;
	}

	/**
	 * [setReseller description
	 * @param string $domain
	 * @return array see param operationreturn gandi docs
	 */
	public function setReseller($domain){
		$conn = $this->createConnection('reseller.');
		$result = $conn->set(parent::API_KEY, $domain);
		return $result;
	}


	/* Expression of Interest
	*/

	/**
	 * [getCountEOI description]
	 * @param  array $opts see EOIListOptions gandi doc
	 * @return int number of eoi
	 */
	public function getCountEOI($opts = null){
		$conn = $this->createConnection('eoi.');
		if($opts != null)
		{
			$result = $conn->count(parent::API_KEY, $opts);
		}
		else{
			$result = $conn->count(parent::API_KEY);
		}
		return $result;
	}
	/**
	 * [createEOI description]
	 * @param  string $domain
	 * @return array EOIReturn gandi doc
	 */
	public function createEOI($domain){
		$conn = $this->createConnection('eoi.');
		$result = $conn->create(parent::API_KEY,$domain);
		return $result;
	}

	/**
	 * [deleteEOI description]
	 * @param  string $domain
	 * @return boolean 
	 */
	public function deleteEOI($domain){
		$conn = $this->createConnection('eoi.');
		$result = $conn->delete(parent::API_KEY,$domain);
		return $result;
	}

	/**
	 * [getInfoEOI description]
	 * @param  string $domain
	 * @return array EOIReturn gandi doc
	 */
	public function getInfoEOI($domain){
		$conn = $this->createConnection('eoi.');
		$result = $conn->info(parent::API_KEY,$domain);
		return $result;
	}
	/**
	 * [getListEOI description]
	 * @param  array $opts see EOIListOptions gandi doc
	 * @return array EOIReturn gandi doc
	 */
	public function getListEOI($opts)
	{
		$conn = $this->createConnection('eoi.');
		if($opts != null)
		{
			$result = $conn->list(parent::API_KEY, $opts);
		}
		else{
			$result = $conn->list(parent::API_KEY);
		}
		return $result;
	}

	/**
	 * AUTORENEW
	 */
	
	/**
	 * [activateAutoRenew description]
	 * @param string $domain
	 * @param int $duration (1-10) years
	 * @return array Autorenewreturn gandi doc
	 */
	public function activateAutoRenew($domain,$duration = null)
	{
		$conn = $this->createConnection('autorenew.');
		if($duration != null)
		{
			$result = $conn->activate(parent::API_KEY,$domain,$duration);
		}
		else{
			$result = $conn->activate(parent::API_KEY,$domain);
		}
		return $result;
	}
	/**
	 * [deactivateAutoRenew description]
	 * @param string $domain
	 * @return array Autorenewreturn gandi doc
	 */
	public function deactivateAutoRenew($domain)
	{
		$conn = $this->createConnection('autorenew.');
		$result = $conn->deactivate(parent::API_KEY,$domain);
		return $result;
	}	

	/**
	 *  CLAIMS
	 */
	
	/**
	 * [acceptClaim description]
	 * @param  string $noticeId retrieve with getClaimInfo
	 * @return boolean
	 */
	public function acceptClaim($noticeId)
	{
		$conn = $this->createConnection('claims.');
		$result = $conn->deactivate(parent::API_KEY,$noticeId);
		return $result;
	}

	//
	/**
	 *  useful first 90 days? some last forever some shorter..
	 * [checkClaims description]
	 * @param  array $domains
	 * @return array Struct mapping a domain name with its claims status (boolean)
	 */
	public function checkClaims($domains){
		$conn = $this->createConnection('claims.');
		$result = $conn->deactivate(parent::API_KEY,$domains);
		return $result;
	}
	/**
	 * do checkclaims first
	 * @param  string $domain
	 * @return array claimsrequestreturn
	 */
	public function getClaimInfo($domain)
	{
		$conn = $this->createConnection('claims.');
		$result = $conn->deactivate(parent::API_KEY,$domain);
		return $result;
	}

	/**
	 * [countSMD description]
	 * @param  array $options SMDListOptions
	 * @return int count
	 */
	public function countSMD(array $options = null)
	{
		$conn = $this->createConnection('smd.');
		if($options != null)
		{
			$result = $conn->count(parent::API_KEY,$options);
		}
		else{
			$result = $conn->count(parent::API_KEY);
		}
		return $result;
	}

	/**
	 * [createSMD description]
	 * @param  string $MD
	 * @return array SMDReturn
	 */
	public function createSMD($SMD)
	{
		$conn = $this->createConnection('smd.');
		$result = $conn->create(parent::API_KEY,$SMD);
		return $result;
	}

	/**
	 * [deleteSMD description]
	 * @param  int $smdId
	 * @return null 
	 */
	public function deleteSMD($smdId)
	{
		$conn = $this->createConnection('smd.');
		$result = $conn->delete(parent::API_KEY,$smdId);
		return $result;
	}

	/**
	 * [createSMD description]
	 * @param  string $MD
	 * @return array domainInformation
	 */
	public function extractSMD($SMD)
	{
		$conn = $this->createConnection('smd.');
		$result = $conn->extract(parent::API_KEY,$SMD);
		return $result;
	}
	/**
	 * [getSMDInfo description]
	 * @param  int $smdId
	 * @return array SMD information
	 */
	public function getSMDInfo($smdId)
	{
		$conn = $this->createConnection('smd.');
		$result = $conn->info(parent::API_KEY,$smdId);
		return $result;
	}

	/**
	 *  list
	 * @param  array $options SMDListOptions
	 * @return array SMDListreturn gandi doc
	 */
	public function getSMDList(array $options = null)
	{
		$conn = $this->createConnection('smd.');
		if($options != null)
		{
			$result = $conn->list(parent::API_KEY,$options);
		}
		else{
			$result = $conn->list(parent::API_KEY);
		}
		return $result;
	}

	/**
	 * [checkUkRights description]
	 * @param  array $domains 
	 * @param  string $owner
	 * @return array domain -> status (bool allowed,string reason)
	 */
	public function checkUkRights($domains,$owner)
	{
		$conn = $this->createConnection('misc.');
		$result = $conn->ukrights(parent::API_KEY,$domains,$owner);
		return $result;
	}

	/**
	 * [acceptDelete description]
	 * @param  string $fqdn
	 * @param  string $authcode
	 * @return boolean status
	 */
	public function acceptDelete(string $fqdn,string $authcode)
	{
		$conn = $this->createConnection('delete.');
		$result = $conn->accept(parent::API_KEY,$fqdn,$authcode);
		return $result;
	}

	/**
	 * [getDeleteAvailable description]
	 * @param  string $domain
	 * @return boolean 
	 */
	public function getDeleteAvailable($domain)
	{
		$conn = $this->createConnection('delete.');
		$result = $conn->available(parent::API_KEY,$domain);
		return $result;
	}

	/**
	 * [acceptDelete description]
	 * @param  string $fqdn
	 * @param  string $authcode
	 * @return boolean status
	 */
	public function declineDelete($fqdn,$authcode)
	{
		$conn = $this->createConnection('delete.');
		$result = $conn->decline(parent::API_KEY,$fqdn,$authcode);
		return $result;
	}

	/**
	 * [acceptDelete description]
	 * @param  string $fqdn
	 * @param  string $authcode
	 * @return array TransferinReturn
	 */
	public function getDeleteInfo($fqdn,$authcode)
	{
		$conn = $this->createConnection('delete.');
		$result = $conn->info(parent::API_KEY,$fqdn,$authcode);
		return $result;
	}

	/**
	 * [getDeleteAvailable description]
	 * @param  string $domain
	 * @return array DomainDeleteOperationReturn
	 * 	
	 */
	public function proceedWithDelete($domain)
	{
		$conn = $this->createConnection('delete.');
		$result = $conn->proceed(parent::API_KEY,$domain);
		return $result;
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



	public function updateDomainNameServers(){
		$domain_nameservers_api = XML_RPC2_Client::create($api_uri,
			array('prefix' => 'domain.nameservers.'));
		$domain_nameservers_api->set($apikey, 'mydomain.net',
			array('a.dns.mydomain.net', 'b.dns.mydomain.net', 'c.dns.mydomain.net'));
	}




	/** PROCESSING DOMAIN
	*/




/**
* Restricted according to API doc (no test?
*/
	public function deleteDomain(){

	}


	

/*
	PREORDER/sunrise/landurhs etc
*/




	/*
	MISC
	*/

	


}