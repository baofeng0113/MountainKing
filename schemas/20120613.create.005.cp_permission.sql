CREATE TABLE `cp_permission` (
  `modulename` varchar(100) NOT NULL,
  `moduleauth` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`modulename`,`moduleauth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;