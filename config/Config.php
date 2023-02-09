<?php

namespace App\Config;

class Config
{
	public const ROOT = __DIR__ . '/..';
	public const DB_HOST = 'localhost';
	public const DB_USERNAME = 'm_user';
	public const DB_PASSWORD = 'm_user_pass';
	public const DB_NAME = 'eshop';
	public const COUNT_GOODS_ON_PAGE='2';
	public const IS_DEV_ENVIRONMENT = false; // dev - true, prod - false

}