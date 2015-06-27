<?php


require_once(__DIR__.'/etc/lib/api/transip/DomainService.php');

class test
{

	function getDomains(){
		print_r(Transip_DomainService::getDomainNames());
	}
}

test::getDomains();

?>