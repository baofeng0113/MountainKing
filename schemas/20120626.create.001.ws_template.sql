CREATE TABLE `ws_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `themename` varchar(100) NOT NULL,
  `directory` varchar(100) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT '',
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_ip` int(10) unsigned NOT NULL,
  `updated_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;