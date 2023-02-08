<?php

namespace App\Model;

class User
{
    private int $id;
    private string $login;
    private string $email;
    private string $hashed_password;
    private string $role;

    public function __construct(int $id, string $login, string $email, string $hashed_password, string $role)
    {
        $this->setId($id);
        $this->setLogin($login);
        $this->setEmail($email);
        $this->setHashedPassword($hashed_password);
        $this->setRole($role);
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
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
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
    public function getHashedPassword(): string
    {
        return $this->hashed_password;
    }

    /**
     * @param string $hashed_password
     */
    public function setHashedPassword(string $hashed_password): void
    {
        $this->hashed_password = $hashed_password;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}