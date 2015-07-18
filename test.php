<?php

require_once(__DIR__.'/etc/lib/api/transip/DomainService.php');
require_once(__DIR__.'/etc/lib/api/transip/WhoisContact.php');
require_once(__DIR__.'/modules/credits/code/credits.php');
require_once(__DIR__.'/dryden/db/driver.class.php');

class test
{

    const ADMIN_SUFFIX = 'Adm';
    const TECHNICAL_SUFFIX = 'Tec';
    const REGISTRANT_SUFFIX = 'Reg';
    //security that someone only edits their domains>
    static $editwhois = null;
    static $whoisContacts = array(
                Transip_WhoisContact::TYPE_REGISTRANT => self::REGISTRANT_SUFFIX,
                Transip_WhoisContact::TYPE_ADMINISTRATIVE => self::ADMIN_SUFFIX,
                Transip_WhoisContact::TYPE_TECHNICAL => self::TECHNICAL_SUFFIX
                );


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

	function getDNSRecords()
	{	$host = 'localhost';
		$dbname = 'zpanel_core';
		$user = 'root';
		$pass = 'ETOlwGQxhrGV8rp6';
		$domain = 'domdomdomdom.nl';
		$userid = 1;
		$zdbh = new db_driver("mysql:host=$host;dbname=$dbname", $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$zdbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $numrows = $zdbh->prepare('SELECT * FROM x_dns WHERE dn_name_vc = :domainname AND dn_acc_fk = :user AND dn_deleted_ts IS NULL');
            $numrows->bindParam(':domainname', $domain);
            $numrows->bindParam(':user', $userid);
            $numrows->execute();
            while($row = $numrows->fetch(PDO::FETCH_ASSOC)){
            	//$row['dn_type_vc'] //type
            	// $row['dn_host_vc']; //name
            	// $row['dn_ttl_in']; // expire
            	//  //content
            	// $row['dn_texttarget_tx']; //content
            	// $row['dn_priority_in']; //content
            	// $row['dn_port_in']; //content
            	// $row['dn_weight_in']; //content
            	$content = '';
            	switch($row['dn_type_vc']){
                    case Transip_DnsEntry::TYPE_A:
                    $content = $row['dn_target_vc'];
                    break;
                    case Transip_DnsEntry::TYPE_AAAA:
                    $content = $row['dn_target_vc'];
                    break;
                    case Transip_DnsEntry::TYPE_CNAME:
                    $content = $row['dn_target_vc'];
                    break;
                    case Transip_DnsEntry::TYPE_MX:
                    $content = $row['dn_priority_in'].$row['dn_target_vc'];
                    break;
                    case Transip_DnsEntry::TYPE_NS:
                    $content = $row['dn_target_vc'];
                    break;
                    case Transip_DnsEntry::TYPE_TXT:
                    $content = $row['dn_target_vc'];
                    break;
                    case Transip_DnsEntry::TYPE_SRV:
                    $content = $row['dn_priority_in'].' '.$row['dn_weight_in'].' '.$row['dn_port_in'].' '.$row['dn_target_vc'];
                    break;
                    default:
                    return;
                }
                $dnsEntries[] = new Transip_DnsEntry($row['dn_host_vc'],$row['dn_ttl_in'],strtoupper($row['dn_type_vc']),$content);
            }
            Transip_DomainService::setDnsEntries($domain,$dnsEntries);
	}
static function getExistingWhoisContacts(){
    //getinfo
    //$domainObject = Transip_DomainService::getInfo($domain);
    $whoisContacts = array(self::createFakeWhoisContacts(Transip_WhoisContact::TYPE_REGISTRANT));
    $contacts = array();
   foreach($whoisContacts as $whoisContact)
    {
        $type = $whoisContact->type;
        $formContact['inType'.self::$whoisContacts[$type]] = $type;
        $formContact['inFirstName'.self::$whoisContacts[$type]] = $whoisContact->firstName;
        $formContact['inLastName'.self::$whoisContacts[$type]] = $whoisContact->lastName;
        $formContact['inCompanyName'.self::$whoisContacts[$type]] = $whoisContact->companyName;
        $formContact['inCompanyKvk'.self::$whoisContacts[$type]] = $whoisContact->companyKvk;
        $formContact['inCompanyType'.self::$whoisContacts[$type]] = $whoisContact->companyType;
        $formContact['inStreet'.self::$whoisContacts[$type]] = $whoisContact->street;
        $formContact['inNumber'.self::$whoisContacts[$type]] = $whoisContact->number;
        $formContact['inPostalCode'.self::$whoisContacts[$type]] = $whoisContact->postalCode;
        $formContact['inCity'.self::$whoisContacts[$type]] = $whoisContact->city;
        $formContact['inPhoneNumber'.self::$whoisContacts[$type]] = $whoisContact->phoneNumber;
        $formContact['inFaxNumber'.self::$whoisContacts[$type]] = $whoisContact->faxNumber;
        $formContact['inEmail'.self::$whoisContacts[$type]] = $whoisContact->email;
        $formContact['inCountry'.self::$whoisContacts[$type]]  = $whoisContact->country;
        $contacts[] = $whoisContact;
    }
    print_r($contacts);
}

static function testCreateWallet(){
    credits::createWallet(1);
}

static function createFakeWhoisContacts($type){
    $contact  = new Transip_WhoisContact();
    $contact->type = $type;
    $contact->firstName = 'arie';
    $contact->lastName = 'kaas';
    $contact->companyName = 'ariekaas productions';
    $contact->companyKvk = 122425535;
    $contact->companyType = 'BV';
    $contact->street = 'kaasfabriekstraat';
    $contact->number = 117;
    $contact->postalCode = '4437ll';
    $contact->city = 'Kaaszilla';
    $contact->phoneNumber = "+31020300302";
    $contact->faxNumber = '2-10-240-2-40-1';
    $contact->email = 'nopeeee@hotmail.com';
    $contact->country = 'nederkaas';
    return $contact;
    }
}

//test::getExistingWhoisContacts()
//test::getDNSRecords();
//test::getDefaultDns();
//test::getvhost();
//test::getDomains();
test::testCreateWallet();
?>