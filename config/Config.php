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
	public const FIRST_PAGE_ON_PAGINATION='1';
	public const COUNT_PAGES_ON_PAGINATION='3';

	public const GOODS_NOT_FOUND="Ой, никаких товаров не было найдено";

	public const IS_DEV_ENVIRONMENT = true; // dev - true, prod - false

}