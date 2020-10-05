CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verified` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

  CREATE TABLE `apps` (
    `appid` varchar(255) NOT NULL,
    `appname` varchar(255) NOT NULL,
    `appsecret` varchar(255) NOT NULL,
    `creator` varchar(255) NOT NULL,
    `ispublic` varchar(255) NOT NULL,
    `verified` varchar(255) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  ALTER TABLE `apps`
  ADD UNIQUE KEY `appid` (`appid`);
  ALTER TABLE `users`
  CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
