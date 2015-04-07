<?php
require_once('gandi/domain_info/domain.php');

$dom = new domain();
//print $dom->getVersion(); Works
//$dom->checkDomainAvailable(array('ariekaas.nl','kaasbom.eu')); works
//$dom->getCount(null); // no domain atm
$dom->createDomain('ariekaas.nl',2); // backend a does not exist ?
//$dom->getPrice(array('ariekaas.nl'),'EUR'); // not supported in OTE ?

?>
