CREATE TABLE `cp_admin_accounts` (
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `cryptkey` varchar(32) NOT NULL,
  `truename` varchar(20) NOT NULL,
  `mailbox` varchar(100) DEFAULT '',
  `mobcode` varchar(100) DEFAULT '',
  `telcode` varchar(100) DEFAULT '',
  `faxcode` varchar(100) DEFAULT '',
  `disabled` tinyint(1) unsigned NOT NULL,
  `description` varchar(500) DEFAULT '',
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_ip` int(10) unsigned NOT NULL,
  `updated_user` varchar(20) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;