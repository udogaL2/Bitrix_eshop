<?php

namespace App\Src\Model;

use App\Config\Config;

class Image
{
    private const mainWidth = 1000;
    private const mainHeight = 800;
    private const nonMainWidth = 300;
    private const nonMainHeight = 200;
    private string $name;
    private string $directory;
    private int $width;
    private int $height;
    private bool $isMain;


    public function __construct($name, $isMain = false, $directory = '/src/images/', $width = 1000, $height = 800)
    {
        $this->name = $name;
        $this->directory = $directory;
        $this->height = $height;
        $this->width = $width;
        $this->isMain = $isMain;
    }

    function getAbsolutePath()
    {
        return Config::ROOT . $this->directory . $this->name;
    }

    function resize()
    {
        //TODO: implement resizing
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