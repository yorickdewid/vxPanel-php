<?php
require_once __DIR__ . '/../etc/lib/api/transip/DomainService.php';

if (isset($_POST['tld'])) {
	echo getTldPrice($_POST['tld']);
}

function getTldPrice($tld) {
	try{
		$object = Transip_DomainService::getTldInfo($tld);
		return $object->price; //test value]
	}
	catch(Exception $e){
	  return -1;
	}
}

?>
