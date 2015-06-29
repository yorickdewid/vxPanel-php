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

	function getvhost()
	{		$host = 'localhost';
		$dbname = 'zpanel_core';
		$user = 'root';
		$pass = 'ETOlwGQxhrGV8rp6';
		print "noooo\n";
		$domain = 'ariekaas.nl';
		$zdbh = new db_driver("mysql:host=$host;dbname=$dbname", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$zdbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		     $numrows = $zdbh->prepare('SELECT vh_id_pk,vh_acc_fk FROM x_vhosts WHERE vh_name_vc = :domainname AND vh_type_in !=2 AND vh_deleted_ts IS NULL');
            $numrows->bindParam(':domainname', $domain);
            $numrows->execute();
            print "after execute";
            while($row = $numrows->fetch(PDO::FETCH_ASSOC)){
            	 print $numrows->rowCount();
            	print_r($numrows);
            	print_r($result);
            	$vhostPK = $result['vh_id_pk'];
            	$userId = $result['vh_acc_fk'];
            	print "kaas";
            }
	}
}

//test::getDefaultDns();
test::getvhost();
//test::getDomains();

?>