create database if not exists eshop;
use eshop;
create user 'm_user'@'localhost' identified by 'm_user_pass';
grant all privileges on eshop.* to 'm_user'@'localhost';
