CREATE TABLE `ws_contents_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contid` int(10) unsigned NOT NULL,
  `detail` text NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;