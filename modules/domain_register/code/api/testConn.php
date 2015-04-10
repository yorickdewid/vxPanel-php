<?php
require_once('gandi/domain_info/children/domain/domain.php');
require_once('gandi/domain_info/children/domain/params/DomainCreate.php');
require_once('gandi/domain_info/children/contact/params/ContactCreateFormDescription.php');


$dom = new domain();
//print_r($dom->checkDomainAvailable(array('ariekaas.nl','kaasbom.eu'))); //works
//$dom->getCount(null); // no domain atm

echo "\n\n";
//testDOmain

function createDomain($domain){
	try{

		$params = DomainCreate::getParams();
		DomainCreate::cleanArrayKeys($params);
		$result = $dom->createDomain('ariekaas.nl',$params); // backend a does not exist ?
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}	
}

print_r(ContactCreateFormDescription::getParams());
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
