
/* 玩家微信数据库 */

-- ----------------------------
-- Table structure for `tbl_version`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `tbl_version` (
  `versionid` int(4) unsigned NOT NULL DEFAULT '0',
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`versionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_version
-- ----------------------------
insert into tbl_version(versionid, date) values(0, now());



delimiter $$
DROP PROCEDURE IF EXISTS update_version;
create procedure update_version(in cur_version int, out ret int)
uv:begin
	DECLARE last_version int(4);
	SELECT max(versionid) from tbl_version into last_version;
	
	if(cur_version is NULL) THEN
	set ret = 0;
	leave uv;
	end if;

	if (last_version + 1 <> cur_version) THEN
	set ret = 0;
	LEAVE uv;	
	end IF;

	set ret = 1;
	insert into tbl_version(versionid, date) values(cur_version, now());
end uv$$
delimiter ;
