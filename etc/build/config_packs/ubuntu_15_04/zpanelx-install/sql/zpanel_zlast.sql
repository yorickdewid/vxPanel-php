
USE zpanel_core;
CREATE TABLE IF NOT EXISTS `x_status` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `status` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `x_wallet` (
  `id` int(11) NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `x_credit_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` double NOT NULL DEFAULT '5',
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
update x_modules set `mo_folder_vc` = 'register',`mo_folder_path` ='domains' where mo_id_pk = 15;
insert into x_modules set `mo_category_fk`=5,`mo_name_vc`='Manage Whois',`mo_version_in`=1,`mo_folder_vc`='manage_whois',`mo_type_en`='user',`mo_desc_tx`='With Manage Whois you can alter your Whois information of your domain(s).',`mo_enabled_en`= true;


insert into x_status (`status`) VALUES("The transaction failed");
insert into x_status (`status`) VALUES("The transaction was succesful");
