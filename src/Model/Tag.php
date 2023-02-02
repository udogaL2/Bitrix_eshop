<?php

namespace App\Model;

/*
 * Класс для хранения информации о теге.
 * Имеет конструктор; getter для всех параметров; setter для всего, за исключением $id
 */
class Tag
{
	private int $id;
	private string $name;

	public function __construct(string $name, int $id=null)
	{
		$this->name = $name;
		$this->id = $id;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}
}