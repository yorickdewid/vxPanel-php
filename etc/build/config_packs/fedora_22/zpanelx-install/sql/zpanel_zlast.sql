
USE zpanel_core;
CREATE TABLE IF NOT EXISTS `x_status` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `status` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `x_wallet` (
  `id` int(11) NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `user_id` int(6) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `x_wallet`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

ALTER TABLE `x_wallet` ADD `hash` VARCHAR(40) NOT NULL AFTER `total`, ADD UNIQUE (`hash`) ;

ALTER TABLE `x_wallet`
ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `x_accounts` (`ac_id_pk`) ON UPDATE CASCADE;

INSERT INTO `x_wallet` (`id`,`total`,`hash`,`user_id`) VALUES
(0, 0,'356a192b7913b04c54574d18c28d46e6395428ab', 1);

CREATE TABLE IF NOT EXISTS `x_credit_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` double NOT NULL DEFAULT '5',
  `ref_id` varchar(60) NOT NULL,
  `status_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet` (`wallet_id`),
  KEY `FK_credit_transaction_status` (`status_id`),
  CONSTRAINT `FK_credit_transaction_status` FOREIGN KEY (`status_id`) REFERENCES `x_status` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wallet` FOREIGN KEY (`wallet_id`) REFERENCES `x_wallet` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE x_modules ADD mo_folder_path VARCHAR(60) AFTER mo_folder_vc;

insert into x_modules (`mo_category_fk`,`mo_name_vc`,`mo_version_in`,`mo_folder_vc`,`mo_folder_path`,`mo_type_en`,`mo_desc_tx`) VALUES (5,'WHOIS','001','whois','domains','user','You can use this to check if a domain is available for registering.');
insert into x_modules (`mo_category_fk`,`mo_name_vc`,`mo_version_in`,`mo_folder_vc`,`mo_folder_path`,`mo_type_en`,`mo_desc_tx`) VALUES (5,'Manage Domains','001','manage','domains','user','You can manage your domains here.');
insert into x_modules (`mo_category_fk`,`mo_name_vc`,`mo_version_in`,`mo_folder_vc`,`mo_type_en`,`mo_desc_tx`) VALUES (3,'Credit Funds','001','credits','user','With credits you can pay for your services.');
update x_modules set `mo_folder_vc` = 'register',`mo_folder_path` ='domains',`mo_name_vc`='Register Domains' where mo_id_pk = 15;
insert into x_modules set `mo_category_fk`=5,`mo_name_vc`='Manage Whois',`mo_version_in`=1,`mo_folder_vc`='manage_whois',`mo_type_en`='user',`mo_desc_tx`='With Manage Whois you can alter your Whois information of your domain(s).',`mo_enabled_en`= true;

insert into x_status (`status`) VALUES("Failure");
insert into x_status (`status`) VALUES("Succesful");
insert into x_status (`status`) VALUES("Refund");

CREATE TABLE IF NOT EXISTS `x_profiles_detail` (
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `number` int(6) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `company_kvk` varchar(20) DEFAULT NULL,
  `company_type` varchar(30) DEFAULT NULL,
  `faxnumber` varchar(32) DEFAULT NULL,
  `user_id` int(6) unsigned NOT NULL,
`profile_detail_id` mediumint(9) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

ALTER TABLE `x_profiles_detail`
 ADD PRIMARY KEY (`profile_detail_id`), ADD UNIQUE KEY `company_kvk` (`company_kvk`), ADD KEY `user_id` (`user_id`), ADD KEY `user_id_2` (`user_id`);

ALTER TABLE `x_profiles_detail`
MODIFY `profile_detail_id` mediumint(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `x_profiles_detail`
ADD CONSTRAINT `x_profiles_detail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `x_accounts` (`ac_id_pk`) ON UPDATE CASCADE;

INSERT INTO `x_profiles_detail` (`firstname`, `lastname`, `street`, `number`, `city`, `postcode`, `phone`, `email`, `country`, `company_name`, `company_kvk`, `company_type`, `faxnumber`, `user_id`, `profile_detail_id`) VALUES
('ariekaass', 'kaas', 'kaasstraat', 33, 'blauwkaas', '', '', '', 'GB', NULL, NULL, NULL, NULL, 1, 1);
