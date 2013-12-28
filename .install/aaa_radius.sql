#read pass ; echo 'DROP DATABASE radius; CREATE DATABASE radius; ALTER DATABASE `radius` COLLATE utf8_bin;' | mysql --password=$pass ; cat /etc/freeradius/sql/mysql/schema.sql | mysql --password=$pass radius ; cat /etc/freeradius/sql/mysql/nas.sql | mysql --password=$pass radius

ALTER DATABASE `radius` COLLATE utf8_bin;

ALTER TABLE `nas` COMMENT='' COLLATE 'utf8_bin';
ALTER TABLE `radacct` COMMENT='' COLLATE 'utf8_bin';
ALTER TABLE `radcheck` COMMENT='' COLLATE 'utf8_bin';
ALTER TABLE `radgroupcheck` COMMENT='' COLLATE 'utf8_bin';
ALTER TABLE `radgroupreply` COMMENT='' COLLATE 'utf8_bin';
ALTER TABLE `radpostauth` COMMENT='' COLLATE 'utf8_bin';
ALTER TABLE `radreply` COMMENT='' COLLATE 'utf8_bin';
ALTER TABLE `radusergroup` COMMENT='' COLLATE 'utf8_bin';

ALTER TABLE `radcheck` ADD UNIQUE (username, attribute, op, value);
ALTER TABLE `radgroupcheck` ADD UNIQUE (groupname, attribute, op, value);
ALTER TABLE `radgroupreply` ADD UNIQUE (groupname, attribute, op, value);
ALTER TABLE `radreply` ADD UNIQUE (username, attribute, op, value);
ALTER TABLE `radusergroup` ADD UNIQUE (username, groupname);

CREATE TABLE `raduser` (
    `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY
)
;
CREATE TABLE `radgroup` (
    `groupname` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY
)
;
CREATE TABLE `radop` (
    `op` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY
)
;
CREATE TABLE `radattr` (
    `attribute` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY 
)
;


ALTER TABLE `radacct` ADD CONSTRAINT `fk_radgroup_groupname_radacct_groupname` FOREIGN KEY (`groupname`) REFERENCES `radgroup` (`groupname`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radacct` ADD CONSTRAINT `fk_raduser_username_radacct_username` FOREIGN KEY (`username`) REFERENCES `raduser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radcheck` ADD CONSTRAINT `fk_radattr_attribute_radcheck_attribute` FOREIGN KEY (`attribute`) REFERENCES `radattr` (`attribute`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radcheck` ADD CONSTRAINT `fk_radop_op_radcheck_op` FOREIGN KEY (`op`) REFERENCES `radop` (`op`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radcheck` ADD CONSTRAINT `fk_raduser_username_radcheck_username` FOREIGN KEY (`username`) REFERENCES `raduser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radgroupcheck` ADD CONSTRAINT `fk_radattr_attribute_radgroupcheck_attribute` FOREIGN KEY (`attribute`) REFERENCES `radattr` (`attribute`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radgroupcheck` ADD CONSTRAINT `fk_radgroup_groupname_radgroupcheck_groupname` FOREIGN KEY (`groupname`) REFERENCES `radgroup` (`groupname`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radgroupcheck` ADD CONSTRAINT `fk_radop_op_radgroupcheck_op` FOREIGN KEY (`op`) REFERENCES `radop` (`op`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radgroupreply` ADD CONSTRAINT `fk_radattr_attribute_radgroupreply_attribute` FOREIGN KEY (`attribute`) REFERENCES `radattr` (`attribute`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radgroupreply` ADD CONSTRAINT `fk_radgroup_groupname_radgroupreply_groupname` FOREIGN KEY (`groupname`) REFERENCES `radgroup` (`groupname`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radgroupreply` ADD CONSTRAINT `fk_radop_op_radgroupreply_op` FOREIGN KEY (`op`) REFERENCES `radop` (`op`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radpostauth` ADD CONSTRAINT `fk_raduser_username_radpostauth_username` FOREIGN KEY (`username`) REFERENCES `raduser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radreply` ADD CONSTRAINT `fk_radattr_attribute_radreply_attribute` FOREIGN KEY (`attribute`) REFERENCES `radattr` (`attribute`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radreply` ADD CONSTRAINT `fk_radop_op_radreply_op` FOREIGN KEY (`op`) REFERENCES `radop` (`op`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radreply` ADD CONSTRAINT `fk_raduser_username_radreply_username` FOREIGN KEY (`username`) REFERENCES `raduser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radusergroup` ADD CONSTRAINT `fk_radgroup_groupname_radusergroup_groupname` FOREIGN KEY (`groupname`) REFERENCES `radgroup` (`groupname`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `radusergroup` ADD CONSTRAINT `fk_raduser_username_radusergroup_username` FOREIGN KEY (`username`) REFERENCES `raduser` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE INDEX `i_radacct_username` ON `radacct` (`username`);
CREATE INDEX `i_radacct_groupname` ON `radacct` (`groupname`);
CREATE INDEX `i_radcheck_username` ON `radcheck` (`username`);
CREATE INDEX `i_radcheck_groupname` ON `radcheck` (`attribute`);
CREATE INDEX `i_radcheck_op` ON `radcheck` (`op`);
CREATE INDEX `i_radgroupcheck_groupname` ON `radgroupcheck` (`groupname`);
CREATE INDEX `i_radgroupcheck_attribute` ON `radgroupcheck` (`attribute`);
CREATE INDEX `i_radgroupcheck_op` ON `radgroupcheck` (`op`);
CREATE INDEX `i_radgroupreply_groupname` ON `radgroupreply` (`groupname`);
CREATE INDEX `i_radgroupreply_attribute` ON `radgroupreply` (`attribute`);
CREATE INDEX `i_radgroupreply_op` ON `radgroupreply` (`op`);
CREATE INDEX `i_radpostauth_username` ON `radpostauth` (`username`);
CREATE INDEX `i_radreply_eusername` ON `radreply` (`username`);
CREATE INDEX `i_radreply_attribute` ON `radreply` (`attribute`);
CREATE INDEX `i_radreply_op` ON `radreply` (`op`);
CREATE INDEX `i_radusergroup_username` ON `radusergroup` (`username`);
CREATE INDEX `i_radusergroup_groupname` ON `radusergroup` (`groupname`);



INSERT INTO radop VALUES ('=');
INSERT INTO radop VALUES (':=');
INSERT INTO radop VALUES ('==');
INSERT INTO radop VALUES ('+=');
INSERT INTO radop VALUES ('!=');
INSERT INTO radop VALUES ('>');
INSERT INTO radop VALUES ('>=');
INSERT INTO radop VALUES ('<');
INSERT INTO radop VALUES ('<=');
INSERT INTO radop VALUES ('=~');
INSERT INTO radop VALUES ('!~');
INSERT INTO radop VALUES ('=*');
INSERT INTO radop VALUES ('!*');

INSERT INTO radattr VALUES ('ARAP-Challenge-Response');
INSERT INTO radattr VALUES ('ARAP-Features');
INSERT INTO radattr VALUES ('ARAP-Password');
INSERT INTO radattr VALUES ('ARAP-Security');
INSERT INTO radattr VALUES ('ARAP-Security-Data');
INSERT INTO radattr VALUES ('ARAP-Zone-Access');
INSERT INTO radattr VALUES ('Access-Accept');
INSERT INTO radattr VALUES ('Access-Challenge');
INSERT INTO radattr VALUES ('Access-Reject');
INSERT INTO radattr VALUES ('Access-Request');
INSERT INTO radattr VALUES ('Accounting-Request');
INSERT INTO radattr VALUES ('Accounting-Response');
INSERT INTO radattr VALUES ('Acct-Authentic');
INSERT INTO radattr VALUES ('Acct-Delay-Time');
INSERT INTO radattr VALUES ('Acct-Input-Gigawords');
INSERT INTO radattr VALUES ('Acct-Input-Octets');
INSERT INTO radattr VALUES ('Acct-Input-Packets');
INSERT INTO radattr VALUES ('Acct-Interim-Interval');
INSERT INTO radattr VALUES ('Acct-Link-Count');
INSERT INTO radattr VALUES ('Acct-Multi-Session-Id');
INSERT INTO radattr VALUES ('Acct-Output-Gigawords');
INSERT INTO radattr VALUES ('Acct-Output-Octets');
INSERT INTO radattr VALUES ('Acct-Output-Packets');
INSERT INTO radattr VALUES ('Acct-Session-Id');
INSERT INTO radattr VALUES ('Acct-Session-Time');
INSERT INTO radattr VALUES ('Acct-Status-Type');
INSERT INTO radattr VALUES ('Acct-Terminate-Cause');
INSERT INTO radattr VALUES ('Acct-Tunnel-Connection');
INSERT INTO radattr VALUES ('Acct-Tunnel-Packets-Lost');
INSERT INTO radattr VALUES ('CHAP-Challenge');
INSERT INTO radattr VALUES ('CHAP-Password');
INSERT INTO radattr VALUES ('Callback-Id');
INSERT INTO radattr VALUES ('Callback-Number');
INSERT INTO radattr VALUES ('Called-Station-Id');
INSERT INTO radattr VALUES ('Calling-Station-Id');
INSERT INTO radattr VALUES ('Change-of-Authorization');
INSERT INTO radattr VALUES ('Class');
INSERT INTO radattr VALUES ('Configuration-Token');
INSERT INTO radattr VALUES ('Connect-Info');
INSERT INTO radattr VALUES ('Delegated-IPv6-Prefix');
INSERT INTO radattr VALUES ('Digest-AKA-Auts');
INSERT INTO radattr VALUES ('Digest-Algorithm');
INSERT INTO radattr VALUES ('Digest-Auth-Param');
INSERT INTO radattr VALUES ('Digest-CNonce');
INSERT INTO radattr VALUES ('Digest-Domain');
INSERT INTO radattr VALUES ('Digest-Entity-Body-Hash');
INSERT INTO radattr VALUES ('Digest-HA1');
INSERT INTO radattr VALUES ('Digest-Method');
INSERT INTO radattr VALUES ('Digest-Nextnonce');
INSERT INTO radattr VALUES ('Digest-Nonce');
INSERT INTO radattr VALUES ('Digest-Nonce-Count');
INSERT INTO radattr VALUES ('Digest-Opaque');
INSERT INTO radattr VALUES ('Digest-Qop');
INSERT INTO radattr VALUES ('Digest-Realm');
INSERT INTO radattr VALUES ('Digest-Response');
INSERT INTO radattr VALUES ('Digest-Response-Auth');
INSERT INTO radattr VALUES ('Digest-Stale');
INSERT INTO radattr VALUES ('Digest-URI');
INSERT INTO radattr VALUES ('Digest-Username');
INSERT INTO radattr VALUES ('EAP-Message');
INSERT INTO radattr VALUES ('Error-Cause');
INSERT INTO radattr VALUES ('Event-Timestamp');
INSERT INTO radattr VALUES ('Filter-ID');
INSERT INTO radattr VALUES ('Filter-Id');
INSERT INTO radattr VALUES ('Framed-AppleTalk-Link');
INSERT INTO radattr VALUES ('Framed-AppleTalk-Network');
INSERT INTO radattr VALUES ('Framed-AppleTalk-Zone');
INSERT INTO radattr VALUES ('Framed-Compression');
INSERT INTO radattr VALUES ('Framed-IP-Address');
INSERT INTO radattr VALUES ('Framed-IP-Netmask');
INSERT INTO radattr VALUES ('Framed-IPX-Network');
INSERT INTO radattr VALUES ('Framed-IPv6-Pool');
INSERT INTO radattr VALUES ('Framed-IPv6-Prefix');
INSERT INTO radattr VALUES ('Framed-IPv6-Route');
INSERT INTO radattr VALUES ('Framed-Interface-Id');
INSERT INTO radattr VALUES ('Framed-MTU');
INSERT INTO radattr VALUES ('Framed-Pool');
INSERT INTO radattr VALUES ('Framed-Protocol');
INSERT INTO radattr VALUES ('Framed-Route');
INSERT INTO radattr VALUES ('Framed-Routing');
INSERT INTO radattr VALUES ('Idle-Timeout');
INSERT INTO radattr VALUES ('Keep-Alives');
INSERT INTO radattr VALUES ('Login-IP-Host');
INSERT INTO radattr VALUES ('Login-IPv6-Host');
INSERT INTO radattr VALUES ('Login-LAT-Group');
INSERT INTO radattr VALUES ('Login-LAT-Node');
INSERT INTO radattr VALUES ('Login-LAT-Port');
INSERT INTO radattr VALUES ('Login-LAT-Service');
INSERT INTO radattr VALUES ('Login-Service');
INSERT INTO radattr VALUES ('Login-TCP-Port');
INSERT INTO radattr VALUES ('MS-ARAP-Challenge');
INSERT INTO radattr VALUES ('MS-ARAP-Password-Change-Reason');
INSERT INTO radattr VALUES ('MS-Acct-Auth-Type');
INSERT INTO radattr VALUES ('MS-Acct-EAP-Type');
INSERT INTO radattr VALUES ('MS-BAP-Usage');
INSERT INTO radattr VALUES ('MS-CHAP-CPW-1');
INSERT INTO radattr VALUES ('MS-CHAP-CPW-2');
INSERT INTO radattr VALUES ('MS-CHAP-Challenge');
INSERT INTO radattr VALUES ('MS-CHAP-Domain');
INSERT INTO radattr VALUES ('MS-CHAP-Error');
INSERT INTO radattr VALUES ('MS-CHAP-LM-Enc-PW');
INSERT INTO radattr VALUES ('MS-CHAP-MPPE-Keys');
INSERT INTO radattr VALUES ('MS-CHAP-NT-Enc-PW');
INSERT INTO radattr VALUES ('MS-CHAP-Response');
INSERT INTO radattr VALUES ('MS-CHAP2-CPW');
INSERT INTO radattr VALUES ('MS-CHAP2-Response');
INSERT INTO radattr VALUES ('MS-CHAP2-Success');
INSERT INTO radattr VALUES ('MS-Filter');
INSERT INTO radattr VALUES ('MS-Link-Drop-Time-Limit');
INSERT INTO radattr VALUES ('MS-Link-Utilization-Threshold');
INSERT INTO radattr VALUES ('MS-MPPE-Encryption-Policy');
INSERT INTO radattr VALUES ('MS-MPPE-Encryption-Types');
INSERT INTO radattr VALUES ('MS-MPPE-Recv-Key');
INSERT INTO radattr VALUES ('MS-MPPE-Send-Key');
INSERT INTO radattr VALUES ('MS-New-ARAP-Password');
INSERT INTO radattr VALUES ('MS-Old-ARAP-Password');
INSERT INTO radattr VALUES ('MS-Primary-DNS-Server');
INSERT INTO radattr VALUES ('MS-Primary-NBNS-Server');
INSERT INTO radattr VALUES ('MS-RAS-Vendor');
INSERT INTO radattr VALUES ('MS-RAS-Version');
INSERT INTO radattr VALUES ('MS-Secondary-DNS-Server');
INSERT INTO radattr VALUES ('MS-Secondary-NBNS-Server');
INSERT INTO radattr VALUES ('Message-Authenticator');
INSERT INTO radattr VALUES ('NAS-Filter-Rule');
INSERT INTO radattr VALUES ('NAS-IP-Address');
INSERT INTO radattr VALUES ('NAS-IPv6-Address');
INSERT INTO radattr VALUES ('NAS-Identifier');
INSERT INTO radattr VALUES ('NAS-Port');
INSERT INTO radattr VALUES ('NAS-Port-Id');
INSERT INTO radattr VALUES ('NAS-Port-Type');
INSERT INTO radattr VALUES ('Password-Retry');
INSERT INTO radattr VALUES ('Port-Limit');
INSERT INTO radattr VALUES ('Proxy-State');
INSERT INTO radattr VALUES ('Reply-Message');
INSERT INTO radattr VALUES ('SIP-AOR');
INSERT INTO radattr VALUES ('Service-Type');
INSERT INTO radattr VALUES ('Session-Timeout');
INSERT INTO radattr VALUES ('State');
INSERT INTO radattr VALUES ('Termination-Action');
INSERT INTO radattr VALUES ('Tunnel-Assignment-ID');
INSERT INTO radattr VALUES ('Tunnel-Client-Auth-ID');
INSERT INTO radattr VALUES ('Tunnel-Client-Endpoint');
INSERT INTO radattr VALUES ('Tunnel-Link-Reject');
INSERT INTO radattr VALUES ('Tunnel-Link-Start');
INSERT INTO radattr VALUES ('Tunnel-Link-Stop');
INSERT INTO radattr VALUES ('Tunnel-Medium-Type');
INSERT INTO radattr VALUES ('Tunnel-Password');
INSERT INTO radattr VALUES ('Tunnel-Preference');
INSERT INTO radattr VALUES ('Tunnel-Private-Group-ID');
INSERT INTO radattr VALUES ('Tunnel-Reject');
INSERT INTO radattr VALUES ('Tunnel-Server-Auth-ID');
INSERT INTO radattr VALUES ('Tunnel-Server-Endpoint');
INSERT INTO radattr VALUES ('Tunnel-Start');
INSERT INTO radattr VALUES ('Tunnel-Stop');
INSERT INTO radattr VALUES ('Tunnel-Type');
INSERT INTO radattr VALUES ('User-Name');
INSERT INTO radattr VALUES ('User-Password');
INSERT INTO radattr VALUES ('Vendor-Specific');
