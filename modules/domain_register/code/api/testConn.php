<?php
require_once('gandi/domain_info/children/domain/domain.php');
require_once('gandi/domain_info/children/domain/params/DomainCreate.php');
require_once('gandi/domain_info/children/contact/contact.php');
require_once('gandi/domain_info/children/contact/params/ContactCreateFormDescription.php');



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
	$contactParams[ContactCreateFormDescription::STREETADDR] = 'koekkoekstraat';
	$contactParams[ContactCreateFormDescription::TYPE] = '0';
	ContactCreateFormDescription::cleanArrayKeys($contactParams);
	$result = $contact->create($contactParams);
	print_r($result);
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

function createDomain($domain){
	try{
		$dom = new domain();
		createContact();
		$params = DomainCreate::getParams();
		DomainCreate::cleanArrayKeys($params);
		$result = $dom->createDomain('ariekaas.nl',$params); // backend a does not exist ?
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
	print_r($result);
}
//checkAssociateDomain('ariekaas.nl','WN9-GANDI');
//updateContact('WN9-GANDI');
// if(checkAssociateDomain('ariekaas.nl','WN9-GANDI')){
// 	createDomain('ariekaas.nl');
// }
print_r(countContact());
print_r(countDomain());
print_r(infoDomain('ariekaas.nl'));


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
