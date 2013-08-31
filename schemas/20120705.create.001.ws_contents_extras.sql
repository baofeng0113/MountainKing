CREATE TABLE `ws_contents_extras` (
  `contid` int(10) unsigned NOT NULL,
  `config` varchar(100) NOT NULL,
  `params` varchar(200) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  PRIMARY KEY (`contid`,`config`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;