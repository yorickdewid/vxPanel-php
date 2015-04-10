<?php

require_once __DIR__ . '/../../../provider/params.php';

class DomainCreate extends params {
	
	const HANDLE = 'DP6238-GANDI';

	const ADMIN = 'admin';
	const BILL = 'bill';
	const DURATION = 'duration';
	const OWNER  = 'owner';
	const TECH = 'tech';
	const ACCEPT_CONTRACT = 'accept_contract';
	const AUTHINFO = 'authinfo';
	const EOI_ID = 'eoi_id';
	const EXTRA = 'extra';
	const LANG  = 'lang';
	const NAMESERVERS = 'nameservers';
	const NAMESERVERS_IPS = 'nameservers_ips';
	const SMD = 'smd';
	const SMD_ID = 'smd_id';
	const TLD_PHASE = 'tld_phase';
	const WEBREDIR = 'webredir';
	const ZONE_ID = 'zone_id';


	protected static $params = array(
		self::ADMIN => self::HANDLE,  //required
		self::BILL => self::HANDLE, // required
		self::DURATION => 1, // required
		self::OWNER => self::HANDLE, //required 
		self::TECH =>self::HANDLE, //required
		self::ACCEPT_CONTRACT => false, // default true if not given
		self::AUTHINFO => '',
		self::EOI_ID => 0, //Id of an Expression Of Interest to transform
		self::EXTRA => array(), //domainextraparameters
		self::LANG => '', //ISO-639-2 language code of the domains, required for some IDN domains
		self::NAMESERVERS => array(), //e.g array('a.dns.gandi-ote.net', 'b.dns.gandi-ote.net','c.dns.gandi-ote.net') List of nameservers. Gandi DNS used if omitted
		self::NAMESERVERS_IPS => array(), //for glue records only – struct associating a nameserver to a list of IP addresses
		self::SMD => '', //Contents of a Signed Mark Data file (used for newgtld sunrises, tld_phase must be sunrise)
		self::SMD_ID => 0,
		self::TLD_PHASE => 'golive',//Phase of the tld //sunrise,landrush,golive
		self::WEBREDIR => '', //Default web redirection to setup after creation
		self::ZONE_ID => 0 ); //Zone to set after creation

}

?>