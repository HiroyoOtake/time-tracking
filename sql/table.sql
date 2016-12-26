// input_infoテーブル
create database timetracking character set utf8;

use timetracking;

craete table input_info (
	id int primary key auto_increment,
	action varchar(255),
	start_time datetime,
	end_time datetime,
	created_at datetime
);

ALTER TABLE input_info ADD user_id int;


// usersテーブル
use timetracking;

create table users (
	id int primary key auto_increment,
	name varchar(32),
	password varchar(32),
	created_at datetime
);

ALTER TABLE users ADD user_name varchar(255);

alter table users modify user_name varchar(255) after email;

alter table users drop email;

insert into users (id, user_name, password, created_at) values (1,'guest','guest',now());
insert into users (id, user_name, password, created_at) values (2,'otake','otake',now());
