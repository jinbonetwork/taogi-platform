SET names utf8;

DROP TABLE IF EXISTS `taogi_user`;
CREATE TABLE `taogi_user` (
	`uid`			int(10) not null default 0,
	`taoginame`		char(255) default '',
	`display_name`	char(255) default '',
	`degree`		int(10) not null default 0,

	`portrait`		text,
	`summary`		mediumtext,

	`point`			int(10) default 0,

	`reg_date`		int(10) default 0,
	`last_login`	int(10) default 0,

	KEY `TAOGINAME` (`taoginame`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_privileges`;
CREATE TABLE `taogi_privileges` (
	`uid`			int(10) not null default 0,
	`eid`			int(10) not null default 0,
	`level`			smallint(5) not null default 0,
	`created`		int(10) not null default 0,

	KEY `UIDS` (`uid`,`eid`),
	KEY `EIDS` (`eid`,`uid`),
	KEY `LEVEL` (`eid`,`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_entry`;
CREATE TABLE `taogi_entry` (
	`eid`			int(10) not null primary key,
	`vid`			int(10) not null default 0,
	`owner`			int(10) not null default 0,
	`nickname`		char(128) not null default '',
	`subject`		char(255) not null default '',
	`summary`		char(255) not null default '',
	`asset`			text,
	`author`		char(255),	
	`era`			text,	
	`is_public`		int(1) not null default 0, /* 2: 공개 및 Top페이지 공개, 1: 공개, 0: 비공개, -1: 블라인드 */
	`is_forkable`	int(1) not null default 1, /* 1: allow fork this timeline, 0: disable fork this timeline */
	`published`		int(10) not null default 0,

	`approve`		int(10) DEFAULT 0,
	`opposite`		int(10) DEFAULT 0,
	`point`			int(10) DEFAULT 0,

	KEY `VID` (`vid`),
	KEY `NICKNAME` (`nickname`),
	KEY `OWNER` (`owner`),
	KEY `ISPUBLIC` (`is_public`),
	KEY `PUBLISHEd` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_revision`;
CREATE TABLE `taogi_revision` (
	`vid`			int(10) not null primary key,
	`eid`			int(10) not null default 0,
	`editor`		int(10) not null default 0,
	`subject`		char(255) not null default '',
	`timeline`		mediumtext,
	`comments`		text,
	`modified`		int(10) not null default 0,

	KEY `EID` (`eid`),
	KEY `EDITOR` (`editor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_entryExtra`;
CREATE TABLE `taogi_entryExtra` (
	`eid`			int(10) not null default 0,
	`name`			char(128) not null default '',
	`val`			text,

	KEY `EID` (`eid`),
	KEY `NAME` (`eid`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_comment`;
CREATE TABLE `taogi_comment` (
	`cid`			int(10) not null primary key,
	`eid`			int(10) not null default 0,
	`hid`			char(128) not null default '',

	`uid`			int(10) not null default 0,
	`openid`		char(128) default '',
	`name`			char(255) NOT NULL DEFAULT '',
	`password`		char(255),
	`secret`		int(1) default '0', /* 1: view only ge editor, 1: everybody */
	`homepage`		char(80) default '',
	`content`		text,

	`regdate`		int(10) NOT NULL DEFAULT 0,
	`isfiltered`	int(11) default '0',

	KEY `EID` (`eid`,`cid`),
	KEY `HID` (`eid`,`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_tag`;
CREATE TABLE `taogi_tag` (
	`tid`			int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`tag`			char(255) NOT NULL DEFAULT '',

	KEY `TAG` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_tag2entry`;
CREATE TABLE `taogi_tag2entry` (
	`tid`			int(10) NOT NULL DEFAULT 0,
	`eid`			int(10) NOT NULL DEFAULT 0,
	`reg_date`		int(0) NOT NULL DEFAULT 0,

	KEY `TAGDID` (`tid`,`eid`),
	KEY `EID` (`eid`,`reg_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_vocabulary`;
CREATE TABLE `taogi_vocabulary` (
	`vid`			int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`name`			char(255) NOT NULL DEFAULT '',
	`weight`		int(10) DEFAULT 0,

	KEY `WEIGHT` (`weight`),
	KEY `NAME` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `taogi_vocabulary` VALUES (1,'이슈별',0);

DROP TABLE IF EXISTS `taogi_terms`;
CREATE TABLE `taogi_terms` (
	`tid`			int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`vid`			int(10) NOT NULL DEFAULT 0,
	`parent`		int(10) NOT NULL DEFAULT 0,
	`weight`		int(10) DEFAULT 0,
	`name`			char(255) NOT NULL DEFAULT '',

	`total_cnt` int(0) default 0,

	KEY `VID` (`vid`,`weight`),
	KEY `PARENT` (`parent`,`weight`),
	KEY `NAME` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `taogi_entry2terms`;
CREATE TABLE `taogi_entry2terms` (
	eid int(10) NOT NULL DEFAULT 0,
	tid int(10) NOT NULL DEFAULT 0,
	weight int(10) NOT NULL DEFAULT 0,

	KEY `EID` (`eid`,`weight`),
	KEY `CID` (`tid`,`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
