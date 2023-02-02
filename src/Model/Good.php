<?php

namespace App\Src\Model;

class Good
{

	/**
	 * @var int $id
	 * @var string $name
	 * @var float $price
	 * @var Tag[] $tags
	 * @var string[] $imgLinks
	 */
	private int $id;
	private string $name;
	private float $price;
	private array $tags;
	private array $imgLinks;

	public function __construct(
		int $id,
		string $name,
		float $price,
		array $tags = [],
		array $imgLinks = []
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->price = $price;
		$this->tags = $tags;
		$this->imgLinks = $imgLinks;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function getTags(): array
	{
		return $this->tags;
	}

	public function setTags(array $tags): void
	{
		$this->tags = $tags;
	}

	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @return array
	 */
	public function getImgLinks(): array
	{
		return $this->imgLinks;
	}

}