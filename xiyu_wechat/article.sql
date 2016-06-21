drop table if exists `article`;
create table `article`(
id int(10) primary key auto_increment,
filename char(20) not null,
title varchar(100) not null default "",
description varchar(100) not null default "",
picurl varchar(255) not null default "",
create_time timestamp default current_timestamp
)engine=innodb;