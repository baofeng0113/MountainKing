CREATE TABLE `ws_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catename` varchar(100) NOT NULL,
  `catecode` varchar(100) NOT NULL,
  `catelogo` varchar(200) DEFAULT '',
  `catetype` tinyint(5) unsigned NOT NULL,
  `template` varchar(100) DEFAULT '',
  `keywords` varchar(200) DEFAULT '',
  `description` varchar(500) DEFAULT '',
  `disabled` tinyint(1) unsigned NOT NULL,
  `sequence` int(10) unsigned NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_ip` int(10) unsigned NOT NULL,
  `updated_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;