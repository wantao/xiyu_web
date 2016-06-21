/*
 * 本次更新内容：（详细说明本次更新的记录以及更新的原因，注意：下面的call update_version(version_id, ret) 这一行一定要把version_id替换成新的版本id）
 *
 * 微信积分及cdkey相关
 */
 
delimiter $$
DROP PROCEDURE IF EXISTS update_sql;
create procedure update_sql()
us:begin
	DECLARE ret int(4);
	call update_version(1, ret);

	if(ret <> 1) THEN
	LEAVE us;
	end if;
	
CREATE TABLE `player_weixin_jifen` (
  `open_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '玩家微信openid',
  `number`  int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分数',
  `get_time` datetime NOT NULL DEFAULT '1970-01-01 08:00:00' COMMENT '获取时间',
  PRIMARY KEY (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='玩家微信每日签到积分表';
CREATE TABLE `player_weixin_cdkey` (
  `keyno` varchar(64) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '' COMMENT '领取序列号（只能使用字母+数字）',
  `id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '礼包id',
  `open_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '玩家微信openid',
  `get_time` datetime NOT NULL DEFAULT '1970-01-01 08:00:00' COMMENT '获取时间',
  KEY `id_open_id_idx` (`id`,`open_id`),
  PRIMARY KEY (`keyno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信cdkey发送情况表';
CREATE TABLE `jifen_config` (
  `weekday` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:周日,1:周一,....,6:周六',
  `number`  int(11) unsigned NOT NULL DEFAULT '0' COMMENT '周几领取时，能得到的积分数',
  PRIMARY KEY (`weekday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分配置表';
CREATE TABLE `libao_config` (
  `id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '礼包id',
  `pic_url` varchar(255) DEFAULT NULL COMMENT '图片url',
  `need_jifen` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '兑换该礼包所需要的积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='礼包配置表';

end us$$
delimiter ;
call update_sql();
DROP PROCEDURE update_sql;