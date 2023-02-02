<?php

namespace App\Src\Model;

/*
 * Класс для хранения информации о товаре.
 * Имеет конструктор; getter для всех параметров; setter для всего, за исключением $id
 */
class Good
{
	private int $id;
	private string $name;
	private float $price;
	private string $shortDesc;
	private string $fullDesc;
	private string $dateUpdate;
	private int $numberOfOrders;
	private bool $isActive;

	/*
	 * На данном этапе предлагаю сортировать товары по дате обновления, однако в дальнейшем возможно придумать
	 * некую функцию ранжирования, например, от количества заказов.
	 * Чем выше $sortRating, тем выше товар в ленте.
	 *
	 * private int $sortRating;
	 */

	public function __construct(
		string $name,
		float  $price,
		string $dateUpdate,
		string $shortDesc = "No description",
		string $fullDesc = "No description",
		int    $numberOfOrders = 0,
		bool   $isActive = true,
		int    $id = 0
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->price = $price;
		$this->shortDesc = $shortDesc;
		$this->fullDesc = $fullDesc;
		$this->dateUpdate = $dateUpdate;
		$this->numberOfOrders = $numberOfOrders;
		$this->isActive = $isActive;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function getShortDesc(): string
	{
		return $this->shortDesc;
	}

	public function getFullDesc(): string
	{
		return $this->fullDesc;
	}

	public function getDateUpdate(): string
	{
		return $this->dateUpdate;
	}

	public function getNumberOfOrders(): int
	{
		return $this->numberOfOrders;
	}

	public function isActive(): bool
	{
		return $this->isActive;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function setShortDesc(string $shortDesc): void
	{
		$this->shortDesc = $shortDesc;
	}

	public function setFullDesc(string $fullDesc): void
	{
		$this->fullDesc = $fullDesc;
	}

	public function setDateUpdate(string $dateUpdate): void
	{
		$this->dateUpdate = $dateUpdate;
	}

	public function setNumberOfOrders(int $numberOfOrders): void
	{
		$this->numberOfOrders = $numberOfOrders;
	}

	public function setIsActive(bool $isActive): void
	{
		$this->isActive = $isActive;
	}
}