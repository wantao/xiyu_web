/*
 * ���θ������ݣ�����ϸ˵�����θ��µļ�¼�Լ����µ�ԭ��ע�⣺�����call update_version(version_id, ret) ��һ��һ��Ҫ��version_id�滻���µİ汾id��
 * 
 */
 
delimiter $$
DROP PROCEDURE IF EXISTS update_sql;
create procedure update_sql()
us:begin
	DECLARE ret int(4);
	call update_version(0, ret);

	if(ret <> 1) THEN
	LEAVE us;
	end if;


	CREATE TABLE `tbl_shuang_se_qiu` (
  `qihao` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '0000' COMMENT '�ں�',
  `hongqiu` varchar(32) DEFAULT NULL,
  `lanqiu` varchar(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	

end us$$
delimiter ;
call update_sql();
DROP PROCEDURE update_sql;