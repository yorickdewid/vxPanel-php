<?php

require_once __DIR__ . '/../../../provider/params.php';


class ContactCreateFormDescription extends params{

	const CITY = 'city'; // required
	const COUNTRY = 'country'; // required
	const EMAIL = 'email'; // required
	const FAMILY = 'family'; // required
	const GIVEN = 'given'; // required
	const PASSWORD = 'password'; // required
	const PHONE = 'phone'; // required
	const STREETADDR = 'streetaddr'; // required
	const TYPE = 'type'; // required
	const ACCEPT_CONTRACT = 'accept_contract';
	const BRAND_NUMBER = 'brand_number';
	const COMMUNITY = 'community';
	const COMMUNITY_HASH = 'community_hash';
	const COMMUNITY_REFERER = 'community_referer';
	const DATA_OBFUSCATED = 'data_obfuscated';
	const EXTRA_PARAMETERS = 'extra_parameters';
	const FAX = 'fax';
	const JO_ANNOUNCE_NUMBER = 'jo_announce_number';
	const JO_ANNOUNCE_PAGE = 'jo_announce_page';
	const JO_DECLARATION_DATE = 'jo_declaration_date';
	const JO_PUBLICATION_DATE = 'jo_publication_date';
	const LANG = 'lang';
	const MAIL_OBFUSCATED = 'mail_obfuscated';
	const MOBILE = 'mobile';
	const NEWSLETTER = 'newsletter';
	const ORGNAME = 'orgname';
	const SECURITY_QUESTION_ANSWER = 'security_question_answer';
	const SECURITY_QUESTION_NUM = 'security_question_num';
	const SIREN = 'siren';
	const STATE = 'state';
	const THIRD_PART_RESELL = 'third_part_resell';
	const VAT_NUMBER = 'vat_number';
	const ZIP = 'zip';

	protected static $params = array(
		self::CITY => '',
		self::COUNTRY => '',
		self::EMAIL => '',
		self::FAMILY => '',
		self::GIVEN => '',
		self::PASSWORD => '',
		self::PHONE => '',
		self::STREETADDR => '',
		self::TYPE => '',
		self::ACCEPT_CONTRACT => false,
		self::BRAND_NUMBER => '',
		self::COMMUNITY => '',
		self::COMMUNITY_HASH => '',
		self::COMMUNITY_REFERER => '',
		self::DATA_OBFUSCATED => false,
		self::EXTRA_PARAMETERS => array(),
		self::FAX => '',
		self::JO_ANNOUNCE_NUMBER => '',
		self::JO_ANNOUNCE_PAGE => '',
		self::JO_DECLARATION_DATE => '',
		self::JO_PUBLICATION_DATE => '',
		self::LANG => '',
		self::MAIL_OBFUSCATED => '',
		self::MOBILE => '',
		self::NEWSLETTER => '',
		self::ORGNAME => '',
		self::SECURITY_QUESTION_ANSWER => '',
		self::SECURITY_QUESTION_NUM => '',
		self::SIREN => '',
		self::STATE => '',
		self::THIRD_PART_RESELL => '',
		self::VAT_NUMBER => '',
		self::ZIP => '');
}

?>