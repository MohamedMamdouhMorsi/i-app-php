CREATE TABLE `usersSessions` (
  `userId` int(11) NOT NULL ,
  `userToken` varchar(255)  NOT NULL,
  `deviceToken` varchar(255)  NOT NULL,
  `scureToken` varchar(255)  NOT NULL,
  `connectToken` text   NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1  COLLATE utf8_unicode_ci ;
