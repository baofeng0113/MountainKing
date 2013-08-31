CREATE TABLE `cp_admin_loginlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loginresult` tinyint(1) unsigned NOT NULL,
  `description` varchar(500) DEFAULT '',
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 16:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;