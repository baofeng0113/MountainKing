CREATE TABLE `ws_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filesize` int(10) unsigned NOT NULL,
  `filetype` varchar(50) NOT NULL,
  `server_path` varchar(100) NOT NULL,
  `server_host` varchar(100) NOT NULL,
  `server_uri1` varchar(255) NOT NULL,
  `server_uri2` varchar(255) NOT NULL,
  `sequence` tinyint(5) unsigned NOT NULL,
  `description` varchar(500) DEFAULT '',
  `created_time` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01',
  `created_ip` int(10) unsigned NOT NULL,
  `created_user` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;