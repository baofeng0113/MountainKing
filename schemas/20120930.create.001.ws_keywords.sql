CREATE TABLE `ws_keywords` (
  `keyword` varchar(100) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  PRIMARY KEY (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;