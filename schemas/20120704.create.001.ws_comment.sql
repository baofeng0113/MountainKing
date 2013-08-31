CREATE TABLE `ws_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` int(10) unsigned NOT NULL,
  `comment` varchar(500) NOT NULL,
  `publish` tinyint(1) unsigned NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_ip` int(10) unsigned NOT NULL,
  `updated_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;