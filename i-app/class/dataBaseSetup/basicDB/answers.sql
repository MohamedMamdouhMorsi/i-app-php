CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `ownerId` varchar(255) NOT NULL,
  `answerId` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=utf8_unicode_ci;