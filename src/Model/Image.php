<?php

namespace App\Src\Model;

/*
 * Класс для хранения информации об изображении.
 * Имеет конструктор; getter для всех параметров; setter для $isMain
 */

use App\Config\Config;

class Image
{

	private int $id;
    private string $path;
    private int $width;
    private int $height;
    private bool $isMain;

	public function __construct(string $path, int $width, int $height, bool $isMain=false, int $id=null)
	{
		$this->id = $id;
		$this->path = $path;
		$this->width = $width;
		$this->height = $height;
		$this->isMain = $isMain;
	}

	public function getAbsolutePath(): string
	{
		return Config::ROOT . "/".$this->getPath();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getWidth(): int
	{
		return $this->width;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	public function isMain(): bool
	{
		return $this->isMain;
	}

	public function setIsMain(bool $isMain): void
	{
		$this->isMain = $isMain;
	}
}