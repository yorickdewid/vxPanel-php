<?php
require_once('gandi/domain_info/children/domain/domain.php');
require_once('gandi/domain_info/children/domain/params/DomainCreate.php');
require_once('gandi/domain_info/children/contact/contact.php');
require_once('gandi/domain_info/children/contact/params/ContactCreateFormDescription.php');
require_once('gandi/domain_info/children/operation/operation.php');



//print_r($dom->checkDomainAvailable(array('ariekaas.nl','kaasbom.eu'))); //works
//$dom->getCount(null); // no domain atm

echo "\n\n";
//testDOmain

function createContact(){
	$contact = new contact();
	$contactParams = ContactCreateFormDescription::getParams();
	$contactParams[ContactCreateFormDescription::CITY] = 'koekkoekcity';
	$contactParams[ContactCreateFormDescription::COUNTRY] = 'NL';
	$contactParams[ContactCreateFormDescription::EMAIL] = 'koek@thecook.com';
	$contactParams[ContactCreateFormDescription::FAMILY] = 'nope';
	$contactParams[ContactCreateFormDescription::GIVEN] = 'wtf';
	$contactParams[ContactCreateFormDescription::PASSWORD] = 'ariekaas';
	$contactParams[ContactCreateFormDescription::PHONE] = '0923056969';
	$contactParams[ContactCreateFormDescription::ZIP] = '3333 LL'; 
	$contactParams[ContactCreateFormDescription::STREETADDR] = 'koekkoekstraat';
	$contactParams[ContactCreateFormDescription::TYPE] = '0'; // 0,1,2,3,4 
	ContactCreateFormDescription::cleanArrayKeys($contactParams);
	$result = $contact->create($contactParams);
	print_r($result);
	return $result;
}

function createContactReseller(){
	$contact = new contact();
	$contactParams = ContactCreateFormDescription::getParams();
	$contactParams[ContactCreateFormDescription::CITY] = 'koekkoekcity';
	$contactParams[ContactCreateFormDescription::COUNTRY] = 'NL';
	$contactParams[ContactCreateFormDescription::EMAIL] = 'koek@thecook.com';
	$contactParams[ContactCreateFormDescription::FAMILY] = 'nope';
	$contactParams[ContactCreateFormDescription::GIVEN] = 'wtf';
	$contactParams[ContactCreateFormDescription::PASSWORD] = 'ariekaas';
	$contactParams[ContactCreateFormDescription::PHONE] = '0923056969';
	$contactParams[ContactCreateFormDescription::ZIP] = '3333 LL'; 
	$contactParams[ContactCreateFormDescription::STREETADDR] = 'koekkoekstraat';
	$contactParams[ContactCreateFormDescription::TYPE] = '4'; // 0,1,2,3,4 
	$contactParams[ContactCreateFormDescription::ORGNAME] = 'QUENZA';
	ContactCreateFormDescription::cleanArrayKeys($contactParams);
	$result = $contact->create($contactParams);
	print_r($result);
	return $result;
}


function updateContact($contactHandle){
	$contact = new contact();
	$contactParams = ContactCreateFormDescription::getParams();
	$contactParams[ContactCreateFormDescription::ZIP] = '3333 LL'; 
	ContactCreateFormDescription::cleanArrayKeys($contactParams);
	$result = $contact->update($contactHandle,$contactParams);
	print_r($result);
}

function countContact($opts = null){
	try{
		$contact = new contact();
		return $contact->getCount($opts);
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}	
}

function createDomain($domain,$params){
	try{
		$dom = new domain();
		$result = $dom->createDomain('ariekaas.nl',$params); 
		return $result;
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}	
}

function countDomain($opts = null)
{
	try{
		$dom = new domain();
		return $dom->getCount($opts);
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}	
}

function infoDomain($domain){
	try{
		$dom = new domain();
		return $dom->getInfo($domain);
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}

function checkAssociateDomain($domain,$contactHandle)
{
	$contact = new contact();
	$result = $contact->checkCanAssociateDomainContact($domain,$contactHandle);
	return $result;
}
//checkAssociateDomain('ariekaas.nl','WN9-GANDI');
//updateContact('WN9-GANDI');
// print_r(infoDomain('ariekaas.nl'));
// print "checking domain first..";
// $dom  = new domain();
// $result = $dom->checkDomainAvailable(array('ariekaas.nl'));
// 	if(checkAssociateDomain('ariekaas.nl','WN9-GANDI')){
// 		print "creating domain...";
// 		$params = DomainCreate::getParams();
// 		DomainCreate::cleanArrayKeys($params);
// 		$params['owner'] = 'WN9-GANDI';
// 		$params['admin'] = 'WN9-GANDI';
// 		$params['bill'] = 'WN9-GANDI';
// 		$params['tech'] = 'WN9-GANDI';
// 		$result = createDomain('ariekaas.nl',$params);
// 		print_r($result);
//	}	
//print_r(countContact());
//print_r(countDomain());

function testCreateDomain(){
		//checkAssociateDomain('ariekaas.nl','WN9');
		//$result = createContact();
		//$handle = $result['handle'];
		$handle = 'WN13-GANDI';
	if(checkAssociateDomain('ariekaassssssss.nl','WN10-GANDI') == true){
		print "we can associate";
		$params = DomainCreate::getParams();
		DomainCreate::cleanArrayKeys($params);
		print "overwriting admin and tech keys..";
		//$params['owner'] = 'WN10-GANDI'; // keep default we are the owner
		// $params['reseller'] = '';
		// owne and admin example?

		$params['owner'] = 'WN10-GANDI';
        $params['admin'] = 'WN13-GANDI';
		$params['bill'] = 'WN13-GANDI'; // keep default we are billed
		$params['tech'] = 'WN13-GANDI';
		//admin tech and owner must differ?
		// $params['admin'] = 'WN11-GANDI';
		// $params['bill'] = 'WN13-GANDI'; // keep default we are billed
		// $params['tech'] = 'WN12-GANDI';
		/*$params['nameservers'] = array('a.dns.gandi-ote.net', 'b.dns.gandi-ote.net',
                           'c.dns.gandi-ote.net');*/
		print_r($params);
		print "creating domain...";
		$result = createDomain('ariekaassssssss.nl',$params);
		print_r($result);
		return $result;
	}else{
		print "kutt";
	}
}


// wn9,wn10,wn11,wn12 private customer
// reseller wn13
//createContactReseller();
testCreateDomain();
print_r(checkAssociateDomain('ariekaas.nl','WN10-GANDI'));
print_r(checkAssociateDomain('ariekaas.nl','WN11-GANDI'));
print_r(checkAssociateDomain('ariekaas.nl','WN12-GANDI'));
print_r(checkAssociateDomain('ariekaas.nl','WN13-GANDI'));
//$operation = new operation();
//print_r($operation->getInfo(array($result['id'])));
//createDomain('ariekaas.nl');

//print_r(ContactCreateFormDescription::getParams());
//print_r($result);

//$dom->getPrice(array('ariekaas.nl'),'EUR'); // not supported in OTE ?
// idk how to use
//$dom->releaseDomain();
//$dom->renewDomain();
//$dom->restoreDomain();
//$dom->setDomainOwner();
//$dom->setDomainContacts();
// tld
//print_r($dom->listAllTLD()); works
//print_r($dom->listALLTLDByRegion()); works
?>
