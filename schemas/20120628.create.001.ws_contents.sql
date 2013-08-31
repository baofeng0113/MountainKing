CREATE TABLE `ws_contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(10) unsigned NOT NULL,
  `keywords` varchar(200) DEFAULT '',
  `viewtype` int(10) unsigned NOT NULL,
  `description` varchar(500) DEFAULT '',
  `subject` varchar(100) NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  `publish` tinyint(1) unsigned NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_ip` int(10) unsigned NOT NULL,
  `updated_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;