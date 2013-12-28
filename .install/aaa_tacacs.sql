DROP DATABASE tacacs;

CREATE DATABASE tacacs COLLATE utf8_bin;

GRANT ALL ON `tacacs`.* to 'tacacs'@'localhost' IDENTIFIED BY 'tacpass';

CREATE TABLE `tacuser` (
    `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY,
    `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NULL,
    `id` int NOT NULL AUTO_INCREMENT UNIQUE
)
;
CREATE TABLE `tacgroup` (
    `groupname` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY,
    `id` int NOT NULL AUTO_INCREMENT UNIQUE
)
;
CREATE TABLE `tacusergroup` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `groupname` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
)
;
CREATE TABLE `tacauths` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `groupname` varchar(64) COLLATE 'utf8_bin' NOT NULL,
  `attribute` varchar(20) COLLATE 'utf8_bin' NOT NULL,
  `value` varchar(200) COLLATE 'utf8_bin' NOT NULL
)
;
CREATE TABLE `tacattr` (
  `attribute` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY
)
;

ALTER TABLE `tacgroup` ADD UNIQUE (groupname, attribute);
ALTER TABLE `tacusergroup` ADD UNIQUE (groupname, username);
ALTER TABLE `tacusergroup` ADD FOREIGN KEY (`groupname`) REFERENCES `tacgroup` (`groupname`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tacusergroup` ADD FOREIGN KEY (`username`) REFERENCES `tacuser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tacauths` ADD FOREIGN KEY (`groupname`) REFERENCES `tacgroup` (`groupname`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tacauths` ADD FOREIGN KEY (`attribute`) REFERENCES `tacattr` (`attribute`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `tacattr` (`attribute`) VALUES ('host_deny');
INSERT INTO `tacattr` (`attribute`) VALUES ('host_allow');
INSERT INTO `tacattr` (`attribute`) VALUES ('device_deny');
INSERT INTO `tacattr` (`attribute`) VALUES ('device_permit');
INSERT INTO `tacattr` (`attribute`) VALUES ('command_deny');
INSERT INTO `tacattr` (`attribute`) VALUES ('command_permit');
INSERT INTO `tacattr` (`attribute`) VALUES ('av_pairs');