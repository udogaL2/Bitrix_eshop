create table if not exists migration
(
	ID   int          not null auto_increment,
	NAME varchar(255) not null,
	PRIMARY KEY (ID)
);