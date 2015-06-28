<?php

require_once(__DIR__.'/etc/lib/api/transip/DomainService.php');
require_once(__DIR__.'/dryden/db/driver.class.php');

class test
{


	function getDomains(){
		print_r(Transip_DomainService::getDomainNames());
	}

	function getDefaultDns(){
		$host = 'localhost';
		$dbname = 'zpanel_core';
		$user = 'root';
		$pass = 'ETOlwGQxhrGV8rp6';

		$zdbh = new db_driver("mysql:host=$host;dbname=$dbname", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$zdbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM x_dns_create WHERE dc_acc_fk = 0";
		$numrows = $zdbh->prepare($sql);
		$numrows->execute();

		$result = array();
		while($row = $numrows->fetch(PDO::FETCH_ASSOC)){
			$result[] = array('type' => $row['dc_type_vc'],
				'host' => $row['dc_host_vc'],
				'ttl' => $row['dc_ttl_in'],
				'prio' => $row['dc_priority_in'],
				'target' => $row['dc_target_vc']);
			print_r($result);
			print "\n";
		}
		return $result;
	}
}

test::getDefaultDns();
//test::getDomains();

?>