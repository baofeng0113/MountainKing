CREATE TABLE `cp_linkauthed` (
  `authoriz` varchar(50) NOT NULL,
  `link` varchar(200) NOT NULL,
  PRIMARY KEY (`authoriz`,`link`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;