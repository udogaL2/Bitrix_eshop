<?php

namespace App\Src\Model;

/*
 * Класс для хранения информации о товаре.
 * Имеет конструктор; getter для всех параметров; setter для всего, за исключением $id
 */
class Good
{
	private ?int $id;
	private string $name;
	private float $price;
	private string $shortDesc;
	private string $fullDesc;
	private int $numberOfOrders;

    /** @var Tag[] $tags */
    private array $tags;

    /** @var Image[] $images  */
    private array $images;
    private \DateTime $timeCreate;
	private \DateTime $timeUpdate;
	private string $article;
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
        string $article,
        string $shortDesc = "No description",
        string $fullDesc = "No description",
        int    $id = null,
		\DateTime $timeUpdate = null,
        \DateTime $timeCreate = null,
		bool   $isActive = true,
        array $images = [],
		array $tags = []
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->price = $price;
		$this->shortDesc = $shortDesc;
		$this->fullDesc = $fullDesc;
        $this->tags = $tags;
        $this->images = $images;
		$this->timeCreate = $timeCreate ?? new \DateTime();
        $this->timeUpdate = $timeUpdate ?? new \DateTime();
        $this->article = $article;
		$this->isActive = $isActive;
	}

	public function getId(): ?int
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

	public function setIsActive(bool $isActive): void
	{
		$this->isActive = $isActive;
	}

    public function getTimeCreate(): \DateTime
    {
        return $this->timeCreate;
    }

    public function getTimeUpdate(): \DateTime
    {
        return $this->timeUpdate;
    }

    public function setTimeUpdate(\DateTime $timeUpdate): void
    {
        $this->timeUpdate = $timeUpdate;
    }

    public function getArticle(): int
    {
        return $this->article;
    }

    /** @return Tag[] */
    public function getTags(): array
    {
        return $this->tags;
    }

    /** @param Tag[] $tags */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /** @return Image[] */
    public function getImages(): array
    {
        return $this->images;
    }
}