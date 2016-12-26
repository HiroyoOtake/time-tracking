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


// usersテーブル
use timetracking;

create table users (
	id int primary key auto_increment,
	name varchar(32),
	password varchar(32),
	created_at datetime
);
