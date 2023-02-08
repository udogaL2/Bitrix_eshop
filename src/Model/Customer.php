<?php

namespace App\Model;

class Customer
{
    private int $id;
    private string $name;
    private string $phone;
    private string $email;
    private string $wish;

    public function __construct(int $id, string $name, string $phone, string $email, string $wish)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setPhone($phone);
        $this->setEmail($email);
        $this->setWish($wish);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getWish(): string
    {
        return $this->wish;
    }

    /**
     * @param string $wish
     */
    public function setWish(string $wish): void
    {
        $this->wish = $wish;
    }
}