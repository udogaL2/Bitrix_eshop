<?php

namespace App\Src\Model;

use DateTime;

class Order
{
    private int $id;
    private int $good_id;
    private DateTime $date_create;
    private Customer $customer;
    private string $status;
    private string $address;
    private float $price;

    public function __construct(
        //int      $id,
        int      $good_id,
        Customer $customer,
        string   $address,
        float    $price,
        string   $status = 'new'
    )
    {
        //$this->setId($id);
        $this->setAddress($address);
        $this->setCustomer($customer);
        $this->setGoodId($good_id);
        $this->setPrice($price);
        $this->setStatus($status);
        $this->setDateCreate(new DateTime());
    }

    /**
     * @return DateTime
     */
    public function getDateCreate(): DateTime
    {
        return $this->date_create;
    }

    /**
     * @param DateTime $date_create
     */
    public function setDateCreate(DateTime $date_create): void
    {
        $this->date_create = $date_create;
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
     * @return int
     */
    public function getGoodId(): int
    {
        return $this->good_id;
    }

    /**
     * @param int $good_id
     */
    public function setGoodId(int $good_id): void
    {
        $this->good_id = $good_id;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}